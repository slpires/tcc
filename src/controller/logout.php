<?php
/*
    /src/controller/logout.php
    [INCLUSÃO]
    Controlador de logout do sistema SLPIRES.COM (TCC UFF).
    Responsável por destruir a sessão ativa do usuário e redirecionar para o ponto de entrada institucional,
    garantindo limpeza de dados e rastreabilidade conforme melhores práticas de segurança.
*/

/* [INCLUSÃO] Caminhos institucionais e variáveis de ambiente ($url_base, etc.) */
require_once __DIR__ . '/../../config/paths.php';

/* [SESSÃO] Inicialização idempotente antes da destruição */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/* [BLOCO] Limpeza completa da sessão */
$_SESSION = [];

/* [BLOCO] Invalidação do cookie de sessão (se aplicável) */
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

/* [BLOCO] Destruição final da sessão */
session_destroy();

/* [REDIRECIONAMENTO] Pós-logout para hub do sistema (evita loop com a homepage) */
header("Location: {$url_base}/index.php?pagina=sistema&sucesso=logout_ok");
exit;
