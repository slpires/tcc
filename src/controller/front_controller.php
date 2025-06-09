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

/* [INCLUSÃO] Carrega caminhos institucionais ($base_url, $controller_url) para assets e links. */
require_once BASE_PATH . '/config/paths.php';

/* [BLOCO] Roteamento principal via parâmetro GET 'pagina'.
   Determina qual módulo/controller será carregado, garantindo navegação centralizada. */
$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 'home';

switch ($pagina) {
    case 'sistema':
        /* [INCLUSÃO] Carrega controller da preparação dinâmica de perfis (MVC). */
        require_once BASE_PATH . '/src/controller/prepara_perfis.php';
        exit;
    case 'relatorios':
        /* [INCLUSÃO] Carrega controller do módulo de relatórios. */
        require_once BASE_PATH . '/src/controller/relatorios.php';
        exit;
    case 'modulos':
        /* [INCLUSÃO] Carrega controller do painel de módulos. */
        require_once BASE_PATH . '/src/view/painel_modulos.php';
        exit;
    case 'creditos':
        /* [INCLUSÃO] Carrega controller do controle de créditos. */
        require_once BASE_PATH . '/src/controller/controle_credito.php';
        exit;
    case 'simulacao_folha':
        /* [INCLUSÃO] Carrega controller do módulo de simulação da folha de pagamento. */
        require_once BASE_PATH . '/src/controller/simulacao_folha.php';
        exit;
    case 'testes':
        /* [INCLUSÃO] Carrega controller do módulo de testes automatizados. */
        require_once BASE_PATH . '/src/controller/testes.php';
        exit;
    case 'home':
        /* [BLOCO] Landing page será exibida normalmente pelo index.php público. */
        break;
    default:
        /* [BLOCO] Página/módulo não encontrado: retorna à landing page com código de erro padronizado. */
        header("Location: " . $base_url . "/index.php?erro=modulo_invalido");
        exit;
}

/*
    [OBSERVAÇÃO]
    Este arquivo deve ser incluído no início de /public/index.php.
    Toda navegação protegida do sistema deve ocorrer exclusivamente via parâmetro ?pagina=nome_modulo,
    e sempre passar pelo respectivo controller do módulo (não pela view diretamente).
    O $base_url estará disponível para todas as views carregadas após este ponto.
*/
?>
