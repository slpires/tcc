<?php
declare(strict_types=1);
/*
  /config/paths.php
  [VERSÃO REVISADA – 30/10/2025]
  SLPIRES.COM – TCC UFF (PoC)

  Expostas (sempre ABSOLUTAS):
    - $base_url    : base pública para assets (css/js/img)
    - $action_base : endpoint do front controller (/public/index.php)
    - $url_base    : alias de base pública (igual a $base_url)
*/

/* ---------- 1) Ambiente e protocolo ---------- */
$http_host = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? 'localhost');

$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
  $isHttps = (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https');
}
$protocolo = $isHttps ? 'https' : 'http';

/* ---------- 2) Descobrir a base pública atual ---------- */
/* dirname('/tcc/public/index.php') => '/tcc/public'
   dirname('/index.php')            => '/'  (normalizar para '') */
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
$scriptDir = rtrim($scriptDir, '/');
$publicBase = ($scriptDir === '' || $scriptDir === '/') ? '' : $scriptDir;

/* Permite override explícito via servidor (opcional) */
$override = getenv('APP_PUBLIC_BASE') ?: ($_SERVER['APP_PUBLIC_BASE'] ?? null);
if (is_string($override) && $override !== '') {
  $publicBase = '/' . trim($override, '/');
}

/* ---------- 3) Montagem das variáveis ABSOLUTAS ---------- */
$absBase = "{$protocolo}://{$http_host}{$publicBase}";

$base_url    = $absBase;               // ex.: http://localhost/tcc/public  |  https://tcc.slpires.com
$action_base = $absBase . '/index.php';// ex.: http://localhost/tcc/public/index.php
$url_base    = $absBase;               // alias usado em redirecionamentos

/* ---------- 4) Higiene: remover legado ---------- */
if (isset($controller_url)) { unset($controller_url); }

/* ---------- 5) Debug opcional ---------- */
$appDebug = getenv('APP_DEBUG') ?: ($_SERVER['APP_DEBUG'] ?? null);
if ($appDebug && filter_var($appDebug, FILTER_VALIDATE_BOOLEAN)) {
  // Descomente se precisar inspecionar rapidamente:
  // error_log('[paths.php] base_url='.$base_url.' action_base='.$action_base.' scriptDir='.$scriptDir);
}
