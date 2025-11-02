<?php
/*
    /src/view/painel_modulos.php
    [INCLUSÃO]
    Painel de Módulos – Exibe os módulos permitidos conforme perfil da sessão.
    Inclui paths dinâmicos, conexão segura e mensagens institucionais padronizadas.

    [AJUSTES APLICADOS – VISÃO GERAL]
    - [AJUSTE 1] Sanitização/normalização do perfil em sessão.
    - [AJUSTE 2] Blindagem de consultas MySQL com try/catch para evitar Error 500 em PRD.
    - [AJUSTE 3] Correção de "case" sensível (Linux/HostGator): nomes de tabelas/colunas entre backticks e em minúsculas (ajuste ao seu DDL).
    - [AJUSTE 4] Verificações defensivas ao usar mysqli_* (prepare/execute/bind/result).
    - [AJUSTE 5] Leitura do JSON de módulos com fallback seguro quando arquivo ausente/inválido.
*/

/* [INCLUSÃO] Inicializa a sessão */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* [INCLUSÃO] Caminho base dinâmico para assets e roteamento centralizado */
require_once __DIR__ . '/../../config/paths.php';

/* [VALIDAÇÃO] Usuário autenticado (redireciona via front controller) */
if (!isset($_SESSION['perfil'], $_SESSION['nome'], $_SESSION['matricula'])) {
    header('Location: ' . $action_base . '?r=home');
    exit;
}

/* [AJUSTE 1] Sanitização/normalização do perfil (evita strings com espaços) */
$perfil = trim((string)$_SESSION['perfil']);

/* [INCLUSÃO] Conexão ao banco para buscar módulos permitidos do perfil */
require_once __DIR__ . '/../model/conexao.php';
$conn = conectar();

$modulos_permitidos = [];

/* ============================================================
   [AJUSTE 2 + 3 + 4] BLOCO de acesso ao MySQL com blindagem
   - try/catch evita que exceções do mysqli causem Error 500.
   - nomes de tabelas/colunas em "case" coerente com DDL (minúsculas).
   - uso defensivo de mysqli_prepare / execute / bind / fetch.
   ============================================================ */
try {
    if ($conn) {

        /* [BLOCO] 1. Buscar o id_perfil a partir do nome_perfil (case-insensitive) */
        $sql_perfil = "SELECT `id_perfil`
                       FROM `perfil_usuario`
                       WHERE LOWER(`nome_perfil`) = ?";
        if ($stmt_perfil = mysqli_prepare($conn, $sql_perfil)) {
            $perfil_lower = mb_strtolower($perfil, 'UTF-8');
            mysqli_stmt_bind_param($stmt_perfil, 's', $perfil_lower);
            mysqli_stmt_execute($stmt_perfil);
            mysqli_stmt_bind_result($stmt_perfil, $id_perfil);

            if (mysqli_stmt_fetch($stmt_perfil)) {
                mysqli_stmt_close($stmt_perfil);

                /* [BLOCO] 2. Buscar os módulos permitidos a partir do id_perfil */
                $sql_modulo = "SELECT M.`nome_modulo`
                               FROM `perfil_modulo` PM
                               JOIN `modulo` M ON PM.`id_modulo` = M.`id_modulo`
                               WHERE PM.`id_perfil` = ?
                               ORDER BY M.`nome_modulo`";
                if ($stmt_modulo = mysqli_prepare($conn, $sql_modulo)) {
                    mysqli_stmt_bind_param($stmt_modulo, 'i', $id_perfil);
                    mysqli_stmt_execute($stmt_modulo);
                    mysqli_stmt_bind_result($stmt_modulo, $nome_modulo);

                    while (mysqli_stmt_fetch($stmt_modulo)) {
                        // Armazena em lowercase para consistência de comparação
                        $modulos_permitidos[] = strtolower((string)$nome_modulo);
                    }
                    mysqli_stmt_close($stmt_modulo);
                }
            } else {
                // [AJUSTE 4.1] Nenhum perfil encontrado – fecha o statement
                mysqli_stmt_close($stmt_perfil);
            }
        }
    }
} catch (Throwable $t) {
    // [AJUSTE 2.1] Em PRD, qualquer falha (tabela ausente/conexão) não deve derrubar a página.
    // Opcional: registrar log discreto com detalhe técnico para auditoria.
} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        @mysqli_close($conn);
    }
}

/* [INCLUSÃO] Carrega nomes amigáveis dos módulos a partir do JSON institucional
   [AJUSTE 5] Fallback seguro: se o arquivo não existir ou não for JSON válido,
   $modulos_data vira array vazio e a lista simplesmente não renderiza. */
$modulos_data = [];
$modulos_json_path = __DIR__ . '/../../config/modulos_nome_amigavel.json';
if (is_file($modulos_json_path) && is_readable($modulos_json_path)) {
    $modulos_json = file_get_contents($modulos_json_path);
    $tmp = json_decode($modulos_json, true);
    if (is_array($tmp)) {
        $modulos_data = $tmp;
    }
}

/* [MAPA] Nomes técnicos (JSON/legado) → rotas canônicas do roteador
   (mantido; sem alteração de regra de negócio) */
$rotaMap = [
    'simulacao_folha'  => 'simulacao',
    'controle_credito' => 'creditos',
    // 'relatorios' e 'testes' já coincidem com as rotas
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Painel de Módulos | SLPIRES.COM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- [INCLUSÃO] CSS institucional unificado -->
  <link rel="stylesheet" href="<?= $base_url ?>/css/style.css">
</head>
<body class="sistema-bg">
  <div class="sistema-container app">
    <!-- [INCLUSÃO] Exibição padronizada de mensagens institucionais -->
    <?php include __DIR__ . '/componentes/mensagens.php'; ?>

    <div class="app-logo">Slpires.COM</div>
    <div class="app-title">Painel de Módulos</div>

    <!-- [BLOCO] Exibe os módulos permitidos ao usuário -->
    <div class="app-btn-group" style="margin-top:1.5em;">
      <?php
      if (isset($modulos_data['modulos']) && is_array($modulos_data['modulos'])) {
          foreach ($modulos_data['modulos'] as $modulo) {
              $nomeTecnicoLower = strtolower($modulo['nome_tecnico'] ?? '');
              if ($nomeTecnicoLower === '' || !in_array($nomeTecnicoLower, $modulos_permitidos, true)) {
                  continue;
              }

              // Rota canônica (aplica mapeamento se houver)
              $rota = $rotaMap[$nomeTecnicoLower] ?? $nomeTecnicoLower;

              // Link sempre via front controller
              $href = $action_base . '?r=' . $rota;
              $rotulo = htmlspecialchars($modulo['nome_amigavel'] ?? $rota);

              echo '<a class="app-btn" href="' . $href . '">' . $rotulo . '</a>';
          }
      }
      ?>
    </div>

    <!-- [INCLUSÃO] Rodapé dinâmico institucional com info do usuário -->
    <?php require_once __DIR__ . '/rodape_usuario.php'; ?>
  </div>
</body>
</html>
