<?php
/*
    /src/controller/front_controller.php
    [FUNÇÃO] Front controller centralizado — roteamento seguro e inicializações globais.
    [PADRÃO DEV/PRD] Evita hardcodes; usa $url_base (view pública) e $controller_url (controller).
*/

/* [INICIALIZAÇÃO] Sessão + paths */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/paths.php';

/* [ROTEAMENTO] */
$pagina = $_GET['pagina'] ?? 'home';

switch ($pagina) {
    case 'home':
        // Páginas públicas → $url_base
        header("Location: {$url_base}/index.php");
        exit;

    case 'sistema':
        // Porta de entrada (seleção de perfis)
        require_once __DIR__ . '/selecao_perfil.php';
        exit;

    case 'logout':
        require_once __DIR__ . '/logout.php';
        exit;

    // Casos futuros (ex.: módulos)
    case 'painel_modulos':
        // Exemplo: controller específico do painel quando existir
        // require_once __DIR__ . '/painel_modulos.php';
        // exit;
        header("Location: {$url_base}/index.php?erro=rota_indisponivel");
        exit;

    default:
        // Página/módulo não encontrado → voltar à landing com código de erro padronizado
        header("Location: {$url_base}/index.php?erro=modulo_invalido");
        exit;
}

/* [OBS]:
   1) Em PRD, $url_base === '' (DocumentRoot aponta para /public).
   2) Em DEV, $url_base === '/tcc/public'.
   3) Para redirecionar a um controller, preferir $controller_url.
*/
