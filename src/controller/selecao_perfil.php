<?php
/*
    /src/controller/selecao_perfil.php
    [FUNÇÃO]
    Controlador responsável pela seleção de perfil do usuário.
    Define o perfil ativo na sessão e redireciona para o painel principal do sistema.

    [PADRÃO DEV/PRD]
    Utiliza variáveis dinâmicas ($url_base, $controller_url) para compatibilidade entre ambientes.
*/

/* [INICIALIZAÇÃO] Sessão e paths dinâmicos */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/paths.php';

/* [BLOCO] Verifica se o formulário de seleção foi enviado */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['perfil'])) {
    $perfil = trim($_POST['perfil']);

    /* [VALIDAÇÃO] Perfil obrigatório */
    if ($perfil === '') {
        header("Location: {$url_base}/index.php?pagina=sistema&erro=perfil_vazio");
        exit;
    }

    /* [VALIDAÇÃO] Perfis permitidos (ajustável conforme regras futuras) */
    $perfis_validos = ['ADMINISTRADOR', 'RH', 'EMPREGADO'];
    if (!in_array(strtoupper($perfil), $perfis_validos, true)) {
        header("Location: {$url_base}/index.php?pagina=sistema&erro=perfil_invalido");
        exit;
    }

    /* [SESSÃO] Armazena perfil ativo */
    $_SESSION['perfil'] = strtoupper($perfil);

    /* [REDIRECIONAMENTO] Encaminha para painel de módulos */
    header("Location: {$url_base}/index.php?pagina=painel_modulos");
    exit;
}

/* [CHAMADA DIRETA] Sem POST → retorna para seleção de perfil */
header("Location: {$url_base}/index.php?pagina=sistema");
exit;
