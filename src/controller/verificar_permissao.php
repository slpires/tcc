<?php
/*
    /src/controller/verificar_permissao.php
    [INCLUSÃO]
    Controlador de verificação de permissão de acesso do usuário ao módulo corrente (SLPIRES.COM – TCC UFF).
    Responsável por validar perfil, módulo e permissão de acordo com a matriz de acesso.
*/

// [VALIDAÇÃO] Garante que a sessão contém perfil
if (!isset($_SESSION['perfil'])) {
    /* [REDIRECIONAMENTO] Sessão inválida – retorna à landing page com erro padronizado */
    header("Location: ../../public/index.php?erro=acesso_negado");
    exit;
}

/* [INCLUSÃO] Conexão ao banco institucional */
require_once __DIR__ . '/../model/conexao.php';
$conn = conectar();

/* [INCLUSÃO] Identificação do módulo corrente */
$perfil = $_SESSION['perfil'];
$modulo_atual = basename($_SERVER['SCRIPT_NAME'], '.php'); // Ex: "simulacao_folha"
$modulo_atual_upper = strtoupper($modulo_atual);

/* [BLOCO] Busca id_modulo pelo nome técnico (case-insensitive) */
$sql_modulo = "SELECT id_modulo FROM MODULO WHERE UPPER(nome_modulo) = ?";
$stmt_modulo = mysqli_prepare($conn, $sql_modulo);
mysqli_stmt_bind_param($stmt_modulo, 's', $modulo_atual_upper);
mysqli_stmt_execute($stmt_modulo);
mysqli_stmt_bind_result($stmt_modulo, $id_modulo);

if (mysqli_stmt_fetch($stmt_modulo)) {
    mysqli_stmt_close($stmt_modulo);

    /* [BLOCO] Busca id_perfil conforme o nome salvo na sessão (case-insensitive) */
    $sql_perfil = "SELECT id_perfil FROM PERFIL_USUARIO WHERE LOWER(nome_perfil) = ?";
    $perfil_lower = mb_strtolower($perfil);
    $stmt_perfil = mysqli_prepare($conn, $sql_perfil);
    mysqli_stmt_bind_param($stmt_perfil, 's', $perfil_lower);
    mysqli_stmt_execute($stmt_perfil);
    mysqli_stmt_bind_result($stmt_perfil, $id_perfil);

    if (mysqli_stmt_fetch($stmt_perfil)) {
        mysqli_stmt_close($stmt_perfil);

        /* [BLOCO] Verifica permissão na matriz de acesso */
        $sql_verifica = "SELECT 1 FROM PERFIL_MODULO WHERE id_perfil = ? AND id_modulo = ?";
        $stmt_verifica = mysqli_prepare($conn, $sql_verifica);
        mysqli_stmt_bind_param($stmt_verifica, 'ii', $id_perfil, $id_modulo);
        mysqli_stmt_execute($stmt_verifica);
        mysqli_stmt_store_result($stmt_verifica);

        if (mysqli_stmt_num_rows($stmt_verifica) === 0) {
            /* [REDIRECIONAMENTO] Sem permissão – volta ao painel de módulos com erro padronizado */
            mysqli_stmt_close($stmt_verifica);
            mysqli_close($conn);
            header("Location: ../../public/index.php?pagina=modulos&erro=acesso_negado");
            exit;
        }
        mysqli_stmt_close($stmt_verifica);
    } else {
        /* [TRATAMENTO] Perfil não encontrado – encerra sessão e retorna à landing page com erro */
        mysqli_close($conn);
        session_destroy();
        header("Location: ../../public/index.php?erro=perfil_invalido");
        exit;
    }
} else {
    /* [TRATAMENTO] Módulo não existe – retorna ao painel com erro padronizado */
    mysqli_close($conn);
    header("Location: ../../public/index.php?pagina=modulos&erro=modulo_inexistente");
    exit;
}

mysqli_close($conn);
