<?php
/*
    /config/paths.php
    [VERSÃO REVISADA – 28/10/2025]
    [PROJETO] SLPIRES.COM – TCC UFF (PoC)
    [RESPONSÁVEL] Sérgio Luís de Oliveira Pires

    [OBJETIVO]
    Definir caminhos dinâmicos e portáveis para uso em views e controllers:
      - $base_url       : caminho base para assets (CSS, JS, imagens, etc.)
      - $controller_url : URL absoluta para controllers (action dos formulários)
      - $url_base       : caminho de base para redirecionamentos HTTP

    [CONTEXTO DE IMPLANTAÇÃO]
    - DEV (XAMPP): o projeto reside em /tcc e a aplicação pública fica em /tcc/public
    - PRD (HostGator): o subdomínio tcc.slpires.com aponta diretamente para /public_html/tcc/public
      ⇒ portanto, em PRD a pasta /public já é a raiz do site.

    [USO]
      <link rel="stylesheet" href="<?= $base_url ?>/css/style.css">
      <form action="<?= $controller_url ?>/selecao_perfil.php" method="post">
*/

$host      = $_SERVER['SERVER_NAME'] ?? '';
$http_host = $_SERVER['HTTP_HOST']   ?? $host;
$is_local  = in_array($host, ['localhost', '127.0.0.1'], true);

/* ------------------------------------------------------------------
   [DETECÇÃO DE SUBPASTA – COMPATÍVEL COM O CÓDIGO ATUAL]
   Mantém a lógica original apenas para DEV, preservando portabilidade.
------------------------------------------------------------------ */
$base_path = '';
if (!empty($_SERVER['SCRIPT_NAME'])) {
    if (preg_match('#^(/[^/]+)/#', $_SERVER['SCRIPT_NAME'], $m)) {
        $base_path = $m[1]; // em DEV tende a ser "/tcc"; em PRD, raiz do subdomínio
    }
}

/* ------------------------------------------------------------------
   [RESOLUÇÃO DOS CAMINHOS]
   - Em DEV: usa "/tcc/public" para assets e "/tcc/src/controller" para actions
   - Em PRD: "/public" já é a raiz; assets ficam em "" e controllers expostos por "/src/controller"
------------------------------------------------------------------ */
if ($is_local) {
    // Ambiente de Desenvolvimento (XAMPP)
    $base_url       = $base_path . '/public';
    $controller_url = "http://{$http_host}{$base_path}/src/controller";
    $url_base       = $base_path . '/public';
} else {
    // Ambiente de Produção (HostGator)
    $protocolo      = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

    // Em PRD, NÃO concatenar "/public" nos assets: o DocumentRoot já aponta para /public
    $base_url       = '';

    // Mantém a mesma convenção de roteamento do projeto para os controllers
    // (se futuramente os controllers forem roteados via front-controller, ajustar aqui)
    $controller_url = "{$protocolo}://{$http_host}/src/controller";

    // Base para redirecionamentos internos
    $url_base       = '';
}

/* ------------------------------------------------------------------
   [OBSERVAÇÕES]
   1) Inclua este arquivo ANTES de referenciar assets ou definir actions.
   2) Teste rápido:
        - DEV:  echo $base_url;       // esperado: /tcc/public
                 echo $controller_url; // esperado: http://localhost/tcc/src/controller
        - PRD:  echo $base_url;       // esperado: (string vazia)
                 echo $controller_url; // esperado: https://tcc.slpires.com/src/controller
   3) Caso a aplicação adote front-controller único (ex.: /public/index.php?route=...),
      adaptar $controller_url para apontar para a rota pública correspondente.
------------------------------------------------------------------ */
?>
