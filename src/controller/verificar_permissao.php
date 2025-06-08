<?php
// /src/controller/verificar_permissao.php

// [INCLUSÃO] Verifica permissão de acesso do usuário ao módulo corrente, conforme matriz de acesso.
session_start();
if (!isset($_SESSION['perfil'])) {
    header("Location: index.php");
    exit;
}

require_once __DIR__ . '/../model/conexao.php';
$conn = conectar();

$perfil = $_SESSION['perfil'];
$modulo_atual = basename($_SERVER['SCRIPT_NAME'], '.php'); // ex: "simulacao_folha"
$modulo_atual_upper = strtoupper($modulo_atual);

// Busca nome técnico do módulo no banco
$sql_modulo = "SELECT id_modulo FROM MODULO WHERE UPPER(nome_modulo) = ?";
$stmt_modulo = mysqli_prepare($conn, $sql_modulo);
mysqli_stmt_bind_param($stmt_modulo, 's', $modulo_atual_upper);
mysqli_stmt_execute($stmt_modulo);
mysqli_stmt_bind_result($stmt_modulo, $id_modulo);

if (mysqli_stmt_fetch($stmt_modulo)) {
    mysqli_stmt_close($stmt_modulo);

    // Busca permissão na matriz
    // Descobrir id_perfil
    $sql_perfil = "SELECT id_perfil FROM PERFIL_USUARIO WHERE nome_perfil = ?";
    $stmt_perfil = mysqli_prepare($conn, $sql_perfil);
    mysqli_stmt_bind_param($stmt_perfil, 's', $perfil);
    mysqli_stmt_execute($stmt_perfil);
    mysqli_stmt_bind_result($stmt_perfil, $id_perfil);

    if (mysqli_stmt_fetch($stmt_perfil)) {
        mysqli_stmt_close($stmt_perfil);

        // Verifica permissão na matriz
        $sql_verifica = "SELECT 1 FROM PERFIL_MODULO WHERE id_perfil = ? AND id_modulo = ?";
        $stmt_verifica = mysqli_prepare($conn, $sql_verifica);
        mysqli_stmt_bind_param($stmt_verifica, 'ii', $id_perfil, $id_modulo);
        mysqli_stmt_execute($stmt_verifica);
        mysqli_stmt_store_result($stmt_verifica);
        if (mysqli_stmt_num_rows($stmt_verifica) === 0) {
            // Sem permissão!
            mysqli_stmt_close($stmt_verifica);
            mysqli_close($conn);
            header("Location: painel_modulos.php?erro=acesso_negado");
            exit;
        }
        mysqli_stmt_close($stmt_verifica);
    } else {
        // Perfil não encontrado, encerra sessão
        mysqli_close($conn);
        session_destroy();
        header("Location: index.php");
        exit;
    }
} else {
    // Módulo não existe, redireciona
    mysqli_close($conn);
    header("Location: painel_modulos.php?erro=modulo_inexistente");
    exit;
}
mysqli_close($conn);
?>
