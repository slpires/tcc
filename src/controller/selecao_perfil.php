<?php
/*
    /src/controller/verificar_permissao.php
    [FUNÇÃO]
    Middleware simples de autorização.
    Verifica se há sessão ativa e perfil válido antes de permitir acesso aos módulos.

    [PADRÃO DEV/PRD]
    Usa $url_base para redirecionamentos públicos e mantém inicialização unificada.
*/

/* [INICIALIZAÇÃO] Sessão + paths */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/paths.php';

/* [REGRAS] Perfis válidos e rota de fallback */
$perfis_validos = ['ADMINISTRADOR', 'RH', 'EMPREGADO'];

/* [CHECAGEM 1] Sessão e perfil presentes */
if (empty($_SESSION['perfil'])) {
    header("Location: {$url_base}/index.php?pagina=sistema&erro=sem_sessao");
    exit;
}

/* [CHECAGEM 2] Perfil permitido */
$perfil = strtoupper((string) $_SESSION['perfil']);
if (!in_array($perfil, $perfis_validos, true)) {
    header("Location: {$url_base}/index.php?pagina=sistema&erro=perfil_nao_autorizado");
    exit;
}

/* [OK] Autorizado — seguir fluxo do módulo chamador */
return true;
