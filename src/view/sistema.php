<?php
/*
    /src/view/sistema.php
    View de seleção de perfil – ponto de entrada interno do sistema após a landing.
    Carrega paths dinâmicos, mensagens institucionais e formulário de escolha de perfil.
    Implementa blindagem de consulta ao MySQL com fallback e corrige case do nome da tabela.
*/

/* ================================================================
   [AJUSTE 1] Sessão idempotente – evita erro ao reiniciar a sessão
   ================================================================ */
if (session_status() === PHP_SESSION_NONE) { session_start(); }

/* ================================================================
   [AJUSTE 2] Inclusão dos caminhos institucionais
   Garante acesso às variáveis $base_url e $action_base.
   ================================================================ */
require_once __DIR__ . '/../../config/paths.php';

/* ================================================================
   [AJUSTE 3] Conexão ao banco de dados via camada de modelo
   Usa função conectar() para obter o link ativo do MySQL.
   ================================================================ */
require_once __DIR__ . '/../model/conexao.php';
$conn = conectar();

/* ================================================================
   [AJUSTE 4] Inicialização preventiva da variável $perfis_validos
   Evita 'Undefined variable' em caso de falha de conexão.
   ================================================================ */
$perfis_validos = [];

/* ================================================================
   [AJUSTE 5] Consulta protegida à tabela (case corrigido)
   - PRD (Linux) é case-sensitive: 'PERFIL_USUARIO' ≠ 'perfil_usuario'.
   - Inclui tratamento com try/catch e fallback seguro.
   ================================================================ */
try {
    if ($conn) {
        $sql = "SELECT DISTINCT `nome_perfil`
                FROM `perfil_usuario`
                WHERE `nome_perfil` IS NOT NULL AND `nome_perfil` <> ''
                ORDER BY `nome_perfil`";
        if ($rs = mysqli_query($conn, $sql)) {
            while ($row = mysqli_fetch_assoc($rs)) {
                $nome = trim((string)($row['nome_perfil'] ?? ''));
                if ($nome !== '') { $perfis_validos[] = $nome; }
            }
            mysqli_free_result($rs);
        }
        mysqli_close($conn);
    }
} catch (Throwable $t) {
    // [AJUSTE 5.1] Blindagem contra falhas silenciosas de schema/conexão.
    // Em produção, o erro é suprimido e o fallback é aplicado automaticamente.
}

/* ================================================================
   [AJUSTE 6] Fallback – garante lista mínima de perfis válidos
   ================================================================ */
if (empty($perfis_validos)) {
    $perfis_validos = ['Administrador', 'Gestor', 'Colaborador'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>SLPIRES.COM – Sistema de Recuperação de Créditos | Seleção de Perfil de Acesso</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Ambiente de demonstração do sistema – Escolha de perfil de acesso | Sistema TCC SLPIRES.COM">
  <meta name="author" content="Sérgio Luís de Oliveira Pires">
  <meta name="robots" content="noindex, nofollow">
  <meta name="theme-color" content="#45763f">

  <!-- ============================================================
       [AJUSTE 7] Uso do $base_url para assets dinâmicos
       Evita hardcodes e garante compatibilidade DEV/PRD.
       ============================================================ -->
  <link rel="icon" type="image/png" sizes="32x32" href="<?= $base_url ?>/img/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= $base_url ?>/img/favicon-16x16.png">
  <link rel="shortcut icon" href="<?= $base_url ?>/img/favicon.ico" type="image/x-icon">
  <link rel="apple-touch-icon" sizes="180x180" href="<?= $base_url ?>/img/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="192x192" href="<?= $base_url ?>/img/android-chrome-192x192.png">
  <link rel="icon" type="image/png" sizes="512x512" href="<?= $base_url ?>/img/android-chrome-512x512.png">
  <link rel="manifest" href="<?= $base_url ?>/img/site.webmanifest">
  <link rel="stylesheet" href="<?= $base_url ?>/css/style.css">
</head>
<body class="sistema-bg">

  <div class="sistema-container app">
    <!-- ==========================================================
         [AJUSTE 8] Inclusão de mensagens institucionais padronizadas
         ========================================================== -->
    <?php include __DIR__ . '/componentes/mensagens.php'; ?>

    <div class="app-logo">Slpires.COM</div>
    <div class="app-title">Bem-vindo!</div>
    <div class="app-desc">
      Selecione abaixo o perfil desejado para explorar o MVP do sistema:
    </div>

    <!-- ==========================================================
         [AJUSTE 9] Formulário centralizado – roteamento unificado
         Usa $action_base e preserva compatibilidade com o controller.
         ========================================================== -->
    <form method="post" action="<?= $action_base ?>?r=perfil" class="app-btn-group" style="margin-top:1.5em;">
      <?php foreach ($perfis_validos as $perfil): ?>
        <button class="app-btn" name="perfil" value="<?= htmlspecialchars($perfil) ?>" type="submit">
          <?= htmlspecialchars($perfil) ?>
        </button>
      <?php endforeach; ?>
    </form>
    <!-- Fim do bloco de seleção -->

    <!-- ==========================================================
         [AJUSTE 10] Rodapé institucional padronizado
         ========================================================== -->
    <footer class="footer-version app-footer">
      <small>
        © 2025 – Sistema desenvolvido como Prova de Conceito no âmbito do TCC do curso de Tecnologia em Sistemas de Computação – UFF.<br>
        Autor: <strong>Sérgio Luís de Oliveira Pires</strong><br>
        Orientador: <strong>Prof. Leandro Soares de Sousa</strong>
      </small>
    </footer>
  </div>

</body>
</html>
