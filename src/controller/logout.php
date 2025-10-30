<?php
/*
    /src/controller/logout.php
    [INCLUSÃO]
    Controlador de logout do sistema SLPIRES.COM (TCC UFF).
    Responsável por destruir a sessão ativa do usuário e redirecionar para a landing page,
    garantindo limpeza de dados e rastreabilidade conforme boas práticas de segurança.
*/

/* Sessão idempotente antes da destruição */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* Caminhos institucionais ($url_base, $action_base, etc.) */
require_once __DIR__ . '/../../config/paths.php';

/* Limpeza completa da sessão */
$_SESSION = [];

/* Invalidação do cookie de sessão (se aplicável) */
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

/* Destruição final da sessão */
session_destroy();

/* Redireciona para a landing page institucional (sem ?r=) */
header('Location: ' . $url_base . '/');
exit;
