<?php
/*
    /src/controller/logout.php
    [FUNÇÃO]
    Encerrar a sessão do usuário e retornar à homepage pública.

    [PADRÃO DEV/PRD]
    Redireciona usando $url_base (sem /public em PRD).
*/

/* [INICIALIZAÇÃO] paths para obter $url_base */
require_once __DIR__ . '/../../config/paths.php';

/* [SESSÃO] Encerramento seguro */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/* Limpa dados de sessão */
$_SESSION = [];

/* Remove cookie de sessão (se existir) */
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

/* Destrói a sessão */
session_destroy();

/* [REDIRECT] Volta para a landing page */
header("Location: {$url_base}/index.php");
exit;
