<?php
declare(strict_types=1);
/*
  /config/paths.php
  [VERSÃO REVISADA – 29/10/2025]
  [PROJETO] SLPIRES.COM – TCC UFF (PoC)
  [RESPONSÁVEL] Sérgio Luís de Oliveira Pires

  OBJETIVO:
    - Fornecer uma fonte única e portável de URLs para assets e ações HTTP.
    - Padronizar o ponto de entrada via front-controller (/public/index.php),
      funcionando de forma idêntica em DEV (XAMPP) e PRD (HostGator).

  VARIÁVEIS EXPOSTAS:
    - $base_url    : caminho base (relativo) sob /public para referenciar assets (css/js/img)
    - $action_base : URL absoluta do front-controller para usar em <form action="...">
    - $url_base    : alias compatível para redirecionamentos internos (mesmo valor de $base_url)

  USO:
    <link rel="stylesheet" href="<?= $base_url ?>/css/style.css">
    <form action="<?= $action_base ?>?r=perfil" method="post">
*/

// ------------------------------------------------------------------
// 1) Descoberta de ambiente e protocolo
//    - Preferência: variáveis definidas pelo Apache (.htaccess)
//    - Fallback: heurística por host (localhost/127.0.0.1)
// ------------------------------------------------------------------
$appEnv = getenv('APP_ENV') ?: ($_SERVER['APP_ENV'] ?? null);
if (!$appEnv) {
  $hostHeur = $_SERVER['SERVER_NAME'] ?? 'localhost';
  $appEnv   = (in_array($hostHeur, ['localhost', '127.0.0.1'], true) ? 'development' : 'production');
}

$http_host = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? 'localhost');
$isHttps   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
$protoHdr  = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? null;
if ($protoHdr) {
  // Compatível com proxy/CDN
  $isHttps = (strtolower($protoHdr) === 'https');
}
$protocolo = $isHttps ? 'https' : 'http';

// ------------------------------------------------------------------
// 2) Base pública (funciona em /tcc/public no DEV e / no PRD)
//    Ex.: SCRIPT_NAME = "/tcc/public/index.php"  → $publicBase = "/tcc/public"
//         SCRIPT_NAME = "/index.php"             → $publicBase = ""
// ------------------------------------------------------------------
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$publicBase = rtrim(str_replace('/index.php', '', $scriptName), '/');

// ------------------------------------------------------------------
// 3) Variáveis públicas para o app
//    - $base_url   : base relativa para assets
//    - $action_base: URL absoluta para o front-controller
//    - $url_base   : alias (compat) para base de redirecionamentos
// ------------------------------------------------------------------
$base_url    = $publicBase; // "" em PRD; "/tcc/public" em DEV
$action_base = "{$protocolo}://{$http_host}{$publicBase}/index.php";
$url_base    = $base_url;

// ------------------------------------------------------------------
// 4) Backward-compatibility: remover $controller_url
//    (Todas as views devem usar $action_base ?r=rota)
// ------------------------------------------------------------------
if (isset($controller_url)) {
  unset($controller_url);
}

// ------------------------------------------------------------------
// 5) Sanity-check (opcional em DEV)
//    Ativar com APP_DEBUG=true para inspecionar rapidamente.
// ------------------------------------------------------------------
$appDebug = getenv('APP_DEBUG') ?: ($_SERVER['APP_DEBUG'] ?? null);
if ($appDebug && filter_var($appDebug, FILTER_VALIDATE_BOOLEAN)) {
  // Nenhuma saída direta aqui para não poluir HTML; usar conforme necessidade:
  // error_log('[paths.php] base_url=' . $base_url . ' action_base=' . $action_base . ' env=' . $appEnv);
}
