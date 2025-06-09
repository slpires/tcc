<?php
/*
    /config/paths.php
    [VERSÃO CORRIGIDA]
    Arquivo central de definição de caminhos dinâmicos para uso em views e controllers do sistema SLPIRES.COM (TCC UFF).
    Define as variáveis institucionais:
      - $base_url       : caminho base para assets (CSS, JS, imagens, etc.)
      - $controller_url : caminho absoluto para controllers (usado no action dos formulários)
    Garante portabilidade e compatibilidade entre ambientes de desenvolvimento e produção.
    [AUTO-DETECÇÃO]
    O cálculo do caminho base é feito automaticamente, tornando o projeto portável
    entre diferentes domínios, subdomínios ou subpastas, sem necessidade de ajustes manuais.
*/

$host       = $_SERVER['SERVER_NAME'] ?? '';
$http_host  = $_SERVER['HTTP_HOST'] ?? $host;

// [DETECÇÃO ROBUSTA] Extrai a subpasta do projeto automaticamente
$base_path = '';
if (isset($_SERVER['SCRIPT_NAME'])) {
    if (preg_match('#^(/[^/]+)/#', $_SERVER['SCRIPT_NAME'], $m)) {
        $base_path = $m[1];
    }
}

if (in_array($host, ['localhost', '127.0.0.1'])) {
    $base_url       = $base_path . '/public';
    $controller_url = "http://$http_host$base_path/src/controller";
} else {
    $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $base_url       = $base_path . '/public';
    $controller_url = "$protocolo://$http_host$base_path/src/controller";
}

/*
    [URL_BASE]
    Caminho absoluto do sistema para uso em redirecionamentos HTTP.
    Funciona tanto em desenvolvimento quanto produção.
*/
$url_base = $base_path . '/public';

/*
    [OBSERVAÇÃO]
    Inclua este arquivo em todas as views antes de referenciar assets ou definir action de formulários.
    Exemplo de uso:
        <link rel="stylesheet" href="<?= $base_url ?>/css/style.css">
        <form action="<?= $controller_url ?>/selecao_perfil.php" ...>
*/
?>
