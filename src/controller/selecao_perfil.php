<?php
/*
    /src/controller/selecao_perfil.php
    [INCLUSÃO]
    Controlador responsável por processar a seleção de perfil do usuário no sistema SLPIRES.COM (TCC UFF).
    Aceita POST (preferencial) ou GET (fallback), valida o perfil e direciona via roteador central (?r=...).
*/

/* Sessão idempotente */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/* Caminhos institucionais (expõe $action_base) */
require_once __DIR__ . '/../../config/paths.php';

/* Conexão ao banco */
require_once __DIR__ . '/../model/conexao.php';
$conn = conectar();
if (!$conn) {
    header('Location: ' . $action_base . '?r=sistema&erro=db_indisponivel');
    exit;
}

/* Entrada: aceita POST ou GET */
$perfil    = trim($_POST['perfil']    ?? $_GET['perfil']    ?? '');
$nome      = trim($_POST['nome']      ?? $_GET['nome']      ?? '');
$matricula = trim($_POST['matricula'] ?? $_GET['matricula'] ?? '');

if ($perfil === '') {
    mysqli_close($conn);
    header('Location: ' . $action_base . '?r=sistema&erro=perfil_invalido');
    exit;
}

/* Validação de perfil (case-insensitive) */
$sql  = "SELECT 1 FROM PERFIL_USUARIO WHERE LOWER(nome_perfil) = ?";
$p    = mb_strtolower($perfil);
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 's', $p);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $ok);
$existe = mysqli_stmt_fetch($stmt) ? true : false;
mysqli_stmt_close($stmt);

if (!$existe) {
    mysqli_close($conn);
    header('Location: ' . $action_base . '?r=sistema&erro=perfil_invalido');
    exit;
}

/* NEGÓCIO: seleção de empregado conforme perfil */
$perfil_padronizado = mb_strtolower($perfil);
$selecionado = null;

if ($perfil_padronizado === 'administrador') {
    $sql = "SELECT matricula, nome, cargo, nucleo_setor_sigla
              FROM empregado
             WHERE status = 'ATIVO' AND nucleo_setor_sigla = 'TI'
          ORDER BY CASE 
                     WHEN cargo = 'Coordenadora de TI' THEN 1
                     WHEN cargo = 'Arquiteto de Sistemas' THEN 2
                     ELSE 3
                   END, matricula ASC";
    $result = mysqli_query($conn, $sql);

    $preferenciais = [];
    $outros = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['cargo'] === 'Coordenadora de TI' || $row['cargo'] === 'Arquiteto de Sistemas') {
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
        $sql = "SELECT matricula, nome, cargo, nucleo_setor_sigla
                  FROM empregado
                 WHERE status = 'ATIVO' AND cargo = 'Gerente do RH'
              ORDER BY matricula ASC LIMIT 1";
        $result = mysqli_query($conn, $sql);
        if ($result && ($row3 = mysqli_fetch_assoc($result))) {
            $selecionado = $row3;
            mysqli_free_result($result);
        } else {
            $sql = "SELECT matricula, nome, cargo, nucleo_setor_sigla
                      FROM empregado
                     WHERE status = 'ATIVO' AND nucleo_setor_sigla = 'RH'
                  ORDER BY matricula ASC LIMIT 1";
            $result = mysqli_query($conn, $sql);
            if ($result && ($row4 = mysqli_fetch_assoc($result))) {
                $selecionado = $row4;
                mysqli_free_result($result);
            } else {
                $sql = "SELECT matricula, nome, cargo, nucleo_setor_sigla
                          FROM empregado
                         WHERE status = 'ATIVO'
                      ORDER BY matricula ASC LIMIT 1";
                $result = mysqli_query($conn, $sql);
                if ($result && ($row5 = mysqli_fetch_assoc($result))) {
                    $selecionado = $row5;
                    mysqli_free_result($result);
                } else {
                    mysqli_close($conn);
                    header('Location: ' . $action_base . '?r=sistema&erro=nao_encontrado');
                    exit;
                }
            }
        }
    }

} elseif ($perfil_padronizado === 'rh') {
    $sql = "SELECT matricula, nome, cargo, nucleo_setor_sigla
              FROM empregado
             WHERE status = 'ATIVO' AND nucleo_setor_sigla = 'RH'
          ORDER BY RAND() LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $selecionado = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
    }

} else {
    $sql = "SELECT matricula, nome, cargo, nucleo_setor_sigla
              FROM empregado
             WHERE status = 'ATIVO'
          ORDER BY RAND() LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $selecionado = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
    }
}

/* Saída: grava sessão e redireciona */
if ($selecionado && !empty($selecionado['matricula'])) {
    session_regenerate_id(true);

    $_SESSION['perfil']    = $perfil;
    $_SESSION['nome']      = $selecionado['nome'] ?? '';
    $_SESSION['matricula'] = $selecionado['matricula'] ?? '';
    $_SESSION['cargo']     = $selecionado['cargo'] ?? '';
    $_SESSION['setor']     = $selecionado['nucleo_setor_sigla'] ?? '';

    mysqli_close($conn);
    header('Location: ' . $action_base . '?r=painel'); // Roteamento centralizado
    exit;
}

mysqli_close($conn);
header('Location: ' . $action_base . '?r=sistema&erro=nao_encontrado');
exit;
