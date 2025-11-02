<?php
/*
    /src/controller/selecao_perfil.php
    [INCLUSÃO]
    Controlador responsável por processar a seleção de perfil do usuário no sistema SLPIRES.COM (TCC UFF).
    Aceita POST (preferencial) ou GET (fallback), valida o perfil e direciona via roteador central (?r=...).

    [AJUSTES APLICADOS – RESUMO]
    - [AJUSTE 1] Sessão idempotente + sanitização dos dados de entrada.
    - [AJUSTE 2] Inclusão de paths institucionais (roteamento estável via $action_base).
    - [AJUSTE 3] Blindagem de acesso ao MySQL com try/catch (evita Error 500 em PRD).
    - [AJUSTE 4] Correção de CASE sensível (Linux): nomes de tabelas/colunas em minúsculas e entre backticks.
    - [AJUSTE 5] Fluxo resiliente: se o BD falhar, aplica fallback seguro sem derrubar a aplicação.
*/

/* ================================================================
   [AJUSTE 1] Sessão idempotente
   ================================================================ */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/* ================================================================
   [AJUSTE 2] Caminhos institucionais (expõe $action_base e $base_url)
   ================================================================ */
require_once __DIR__ . '/../../config/paths.php';

/* ================================================================
   Entrada: aceita POST ou GET + sanitização
   ================================================================ */
$perfil    = trim((string)($_POST['perfil']    ?? $_GET['perfil']    ?? ''));
$nome      = trim((string)($_POST['nome']      ?? $_GET['nome']      ?? ''));
$matricula = trim((string)($_POST['matricula'] ?? $_GET['matricula'] ?? ''));

/* Validação mínima do perfil informado */
if ($perfil === '') {
    header('Location: ' . $action_base . '?r=sistema&erro=perfil_invalido');
    exit;
}

/* Normalização para comparações */
$perfil_padronizado = mb_strtolower($perfil, 'UTF-8');

/* Dados de saída (preenchidos ao longo do fluxo) */
$selecionado = null;

/* ================================================================
   [AJUSTE 3 + 4 + 5] Acesso ao MySQL com blindagem
   - try/catch evita que mysqli_sql_exception gere Error 500 em PRD.
   - Tabelas/colunas com case correto (minúsculas) e backticks.
   - Em falha de BD, segue-se com fallback controlado.
   ================================================================ */
try {

    /* Conexão ao banco */
    require_once __DIR__ . '/../model/conexao.php';
    $conn = conectar();

    if (!$conn) {
        // Fallback imediato se o BD estiver indisponível
        throw new RuntimeException('db_indisponivel');
    }

    /* ------------------------------------------------------------
       VALIDAÇÃO DE PERFIL (case-insensitive)
       Tabela ajustada para o case correto do DDL: `perfil_usuario`
       ------------------------------------------------------------ */
    $sql = "SELECT 1 FROM `perfil_usuario` WHERE LOWER(`nome_perfil`) = ? LIMIT 1";
    $p   = mb_strtolower($perfil, 'UTF-8');

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, 's', $p);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $ok);
        $existe = mysqli_stmt_fetch($stmt) ? true : false;
        mysqli_stmt_close($stmt);
    } else {
        // Preparação falhou (schema/perm), trata como inexistente
        $existe = false;
    }

    if (!$existe) {
        // Perfil inexistente no BD → retorno controlado à seleção
        if (isset($conn) && $conn instanceof mysqli) { @mysqli_close($conn); }
        header('Location: ' . $action_base . '?r=sistema&erro=perfil_invalido');
        exit;
    }

    /* ------------------------------------------------------------
       NEGÓCIO: seleção de empregado conforme perfil
       (mantido, apenas com tratamento defensivo nos acessos ao BD)
       ------------------------------------------------------------ */

    if ($perfil_padronizado === 'administrador') {

        $sql = "SELECT `matricula`, `nome`, `cargo`, `nucleo_setor_sigla`
                  FROM `empregado`
                 WHERE `status` = 'ATIVO' AND `nucleo_setor_sigla` = 'TI'
              ORDER BY CASE 
                         WHEN `cargo` = 'Coordenadora de TI' THEN 1
                         WHEN `cargo` = 'Arquiteto de Sistemas' THEN 2
                         ELSE 3
                       END, `matricula` ASC";
        $result = mysqli_query($conn, $sql);

        $preferenciais = [];
        $outros = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                if (($row['cargo'] ?? '') === 'Coordenadora de TI' || ($row['cargo'] ?? '') === 'Arquiteto de Sistemas') {
                    $preferenciais[] = $row;
                } else {
                    $outros[] = $row;
                }
            }
            mysqli_free_result($result);
        }

        $universo = [];
        foreach ($preferenciais as $emp) {
            $universo[] = $emp;
            if (count($universo) === 2) break;
        }
        if (count($universo) < 2) {
            foreach ($outros as $emp) {
                $universo[] = $emp;
                if (count($universo) === 2) break;
            }
        }

        if (count($universo) > 0) {
            $selecionado = $universo[array_rand($universo)];
        } else {
            // Cadeia de fallback original preservada
            $sql = "SELECT `matricula`, `nome`, `cargo`, `nucleo_setor_sigla`
                      FROM `empregado`
                     WHERE `status` = 'ATIVO' AND `cargo` = 'Gerente do RH'
                  ORDER BY `matricula` ASC LIMIT 1";
            $result = mysqli_query($conn, $sql);
            if ($result && ($row3 = mysqli_fetch_assoc($result))) {
                $selecionado = $row3;
                mysqli_free_result($result);
            } else {
                $sql = "SELECT `matricula`, `nome`, `cargo`, `nucleo_setor_sigla`
                          FROM `empregado`
                         WHERE `status` = 'ATIVO' AND `nucleo_setor_sigla` = 'RH'
                      ORDER BY `matricula` ASC LIMIT 1";
                $result = mysqli_query($conn, $sql);
                if ($result && ($row4 = mysqli_fetch_assoc($result))) {
                    $selecionado = $row4;
                    mysqli_free_result($result);
                } else {
                    $sql = "SELECT `matricula`, `nome`, `cargo`, `nucleo_setor_sigla`
                              FROM `empregado`
                             WHERE `status` = 'ATIVO'
                          ORDER BY `matricula` ASC LIMIT 1";
                    $result = mysqli_query($conn, $sql);
                    if ($result && ($row5 = mysqli_fetch_assoc($result))) {
                        $selecionado = $row5;
                        mysqli_free_result($result);
                    } else {
                        // Sem empregado disponível → trata como não encontrado
                        if (isset($conn) && $conn instanceof mysqli) { @mysqli_close($conn); }
                        header('Location: ' . $action_base . '?r=sistema&erro=nao_encontrado');
                        exit;
                    }
                }
            }
        }

    } elseif ($perfil_padronizado === 'rh') {

        $sql = "SELECT `matricula`, `nome`, `cargo`, `nucleo_setor_sigla`
                  FROM `empregado`
                 WHERE `status` = 'ATIVO' AND `nucleo_setor_sigla` = 'RH'
              ORDER BY RAND() LIMIT 1";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $selecionado = mysqli_fetch_assoc($result);
            mysqli_free_result($result);
        }

    } else {

        $sql = "SELECT `matricula`, `nome`, `cargo`, `nucleo_setor_sigla`
                  FROM `empregado`
                 WHERE `status` = 'ATIVO'
              ORDER BY RAND() LIMIT 1";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $selecionado = mysqli_fetch_assoc($result);
            mysqli_free_result($result);
        }
    }

} catch (Throwable $t) {
    /* ============================================================
       [AJUSTE 5] Fallback em caso de falha de BD/consulta
       - Mantém a aplicação de pé em PRD.
       - Usa dados informados (se vieram) ou placeholders de demonstração.
       ============================================================ */
    if (!$selecionado) {
        $selecionado = [
            'matricula'         => ($matricula !== '' ? $matricula : '000000'),
            'nome'              => ($nome !== '' ? $nome : 'Usuário de Demonstração'),
            'cargo'             => 'Colaborador',
            'nucleo_setor_sigla'=> 'GERAL',
        ];
    }
} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        @mysqli_close($conn);
    }
}

/* ================================================================
   Saída: grava sessão e redireciona
   ================================================================ */
if ($selecionado && !empty($selecionado['matricula'])) {
    session_regenerate_id(true);

    $_SESSION['perfil']    = $perfil;
    $_SESSION['nome']      = $selecionado['nome'] ?? ($nome !== '' ? $nome : 'Usuário de Demonstração');
    $_SESSION['matricula'] = $selecionado['matricula'] ?? ($matricula !== '' ? $matricula : '000000');
    $_SESSION['cargo']     = $selecionado['cargo'] ?? 'Colaborador';
    $_SESSION['setor']     = $selecionado['nucleo_setor_sigla'] ?? 'GERAL';

    header('Location: ' . $action_base . '?r=painel'); // Roteamento centralizado
    exit;
}

/* Falha controlada (não encontrado) */
header('Location: ' . $action_base . '?r=sistema&erro=nao_encontrado');
exit;
