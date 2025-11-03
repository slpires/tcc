<?php
/*
    /src/controller/front_controller.php
    [INCLUSÃO]
    Front controller centralizado do sistema SLPIRES.COM (TCC UFF).
    Responsável pelo roteamento seguro, inicialização global da sessão e disponibilização de caminhos dinâmicos para views e controllers.
*/

/* [INCLUSÃO] Definição do diretório base do projeto (BASE_PATH), se ainda não definido. */
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__, 2)); // raiz do projeto
}

/* [INCLUSÃO] Inicialização da sessão global (apenas se não iniciada ainda). */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* [INCLUSÃO] Carrega caminhos institucionais ($base_url, $controller_url, $url_base) para assets e links. */
require_once BASE_PATH . '/config/paths.php';

/* [BLOCO] Roteamento principal via parâmetro GET 'pagina'.
   A área do sistema (MVP/PoC) usa 'sistema' como rota padrão (NÃO 'home'). */
$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 'sistema';

/* [INCLUSÃO] Checagem central de permissão ANTES de carregar módulos/views.
   O script deve redirecionar para {$url_base}/index.php?pagina=sistema&erro=acesso_negado quando necessário,
   sem produzir saída (echo/print). */
require_once BASE_PATH . '/src/controller/verificar_permissao.php';

switch ($pagina) {
    case 'sistema':
        /* [INCLUSÃO] Controller da preparação dinâmica de perfis (MVC). */
        require_once BASE_PATH . '/src/controller/prepara_perfis.php';
        exit;

    case 'relatorios':
        /* [INCLUSÃO] Controller do módulo de relatórios. */
        require_once BASE_PATH . '/src/controller/relatorios.php';
        exit;

    case 'modulos':
        /* [INCLUSÃO] Painel de módulos (view/painel). */
        require_once BASE_PATH . '/src/view/painel_modulos.php';
        exit;

    case 'creditos':
        /* [INCLUSÃO] Controller do controle de créditos. */
        require_once BASE_PATH . '/src/controller/controle_credito.php';
        exit;

    case 'simulacao_folha':
        /* [INCLUSÃO] Controller do módulo de simulação da folha de pagamento. */
        require_once BASE_PATH . '/src/controller/simulacao_folha.php';
        exit;

    case 'testes':
        /* [INCLUSÃO] Controller do módulo de testes automatizados. */
        require_once BASE_PATH . '/src/controller/testes.php';
        exit;

    default:
        /* [BLOCO] Página/módulo não encontrado: fallback estável para 'sistema'
           usando {$url_base}, evitando 404 e loops. */
        header("Location: {$url_base}/index.php?pagina=sistema&erro=rota_inexistente");
        exit;
}

/*
    [OBSERVAÇÃO]
    - Este arquivo é acionado por /public/index.php apenas quando há ?pagina=...
    - A landing pública ('home') é renderizada SOMENTE pelo index.php, não por este front controller.
    - $url_base garante portabilidade DEV/PRD nos redirecionamentos.
*/
