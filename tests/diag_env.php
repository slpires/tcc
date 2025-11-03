<?php
/* ===========================================================
   diag_env.php  —  Diagnóstico rápido DEV/PRD (SLPIRES.COM)
   Instruções:
   - Subir na mesmíssima pasta do index.php que atende a landing.
   - Acessar via navegador: https://seu-site/diag_env.php?token=OK
   - Remover após uso.
   =========================================================== */

declare(strict_types=1);

/* ---------- 0) Gate simples ----------- */
if (!isset($_GET['token']) || $_GET['token'] !== 'OK') {
    http_response_code(403);
    echo 'Forbidden (use ?token=OK)';
    exit;
}

/* ---------- Utilidades ---------- */
function stat_bool($ok) { return $ok ? 'OK' : 'FAIL'; }
function yn($ok) { return $ok ? 'yes' : 'no'; }
function row($k, $v) { echo "<tr><td><strong>{$k}</strong></td><td><code>{$v}</code></td></tr>"; }
function sec($title){ echo "<h2>{$title}</h2><table class='t'>"; }
function endsec(){ echo "</table>"; }
header('Content-Type: text/html; charset=UTF-8');
?><!doctype html><html lang="pt-BR"><head><meta charset="utf-8">
<title>Diagnóstico SLPIRES.COM</title>
<style>
body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Arial,sans-serif;margin:24px;line-height:1.35}
h1{margin:0 0 8px 0} h2{margin:18px 0 6px 0;font-size:1.05rem}
.badge{display:inline-block;padding:.15rem .5rem;border-radius:.5rem;font-size:.8rem;background:#eee}
.ok{color:#1b5e20} .fail{color:#b00020}
table.t{border-collapse:collapse;width:100%;max-width:980px}
.t td{border:1px solid #ddd;padding:6px 8px;vertical-align:top}
pre{background:#fafafa;border:1px solid #eee;padding:8px;overflow:auto}
hr{margin:18px 0;border:0;border-top:1px solid #eee}
small{color:#666}
.k{color:#444}
</style>
</head><body>
<h1>Diagnóstico SLPIRES.COM <span class="badge"><?php echo date('Y-m-d H:i:s'); ?></span></h1>
<?php
/* ---------- 1) Ambiente / servidor ---------- */
$phpv = PHP_VERSION;
$server = $_SERVER['SERVER_SOFTWARE'] ?? 'n/a';
$host = $_SERVER['HTTP_HOST'] ?? 'n/a';
$sn = $_SERVER['SCRIPT_NAME'] ?? 'n/a';
$sf = $_SERVER['SCRIPT_FILENAME'] ?? 'n/a';
$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'yes' : 'no';
$xproto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '';

sec('A) Servidor / Ambiente');
row('PHP_VERSION', $phpv);
row('SERVER_SOFTWARE', $server);
row('HTTP_HOST', $host);
row('HTTPS', $https);
row('X-Forwarded-Proto', $xproto);
row('SCRIPT_NAME', $sn);
row('SCRIPT_FILENAME', $sf);
endsec();

/* ---------- 2) Localização de paths.php (boot resiliente) ---------- */
$paths_candidates = [
  __DIR__ . '/../config/paths.php',  // quando index.php está em /public
  __DIR__ . '/config/paths.php',     // quando index.php está na raiz pública
];
$paths_found = null;
foreach ($paths_candidates as $p) {
  if (is_file($p)) { $paths_found = $p; break; }
}
sec('B) Boot de config/paths.php');
row('paths.php encontrado?', stat_bool((bool)$paths_found));
row('paths.php caminho', $paths_found ?: '—');
endsec();
if ($paths_found) {
  require_once $paths_found;
}

/* ---------- 3) Variáveis expostas por paths.php ---------- */
sec('C) Variáveis de rota/asset');
if (isset($base_url, $action_base, $url_base)) {
  row('$base_url', $base_url);
  row('$action_base', $action_base);
  row('$url_base', $url_base);
} else {
  row('ERRO', 'Variáveis não definidas por paths.php');
}
endsec();

/* ---------- 4) Checagem de includes de view/componentes ---------- */
$msg_candidates = [
  __DIR__ . '/../src/view/componentes/mensagens.php',
  __DIR__ . '/src/view/componentes/mensagens.php',
];
$msg_found = null;
foreach ($msg_candidates as $m) {
  if (is_file($m)) { $msg_found = $m; break; }
}
sec('D) Componentes / includes');
row('mensagens.php encontrado?', stat_bool((bool)$msg_found));
row('mensagens.php caminho', $msg_found ?: '—');
endsec();

/* ---------- 5) Extensões PHP necessárias ---------- */
$ext = [
  'mysqli'   => extension_loaded('mysqli'),
  'mbstring' => extension_loaded('mbstring'),
  'json'     => extension_loaded('json'),
];
sec('E) Extensões PHP');
foreach ($ext as $k => $ok) { row($k, stat_bool($ok)); }
endsec();

/* ---------- 6) Teste de rewrite básico ---------- */
$qs = http_build_query(['r' => 'sistema', 'from' => 'diag']);
$probe = ($action_base ?? './index.php') . '?' . $qs;
sec('F) Probe de roteamento (?r=sistema)');
row('URL probe', $probe);
echo '</table><p><small>Abrir manualmente em nova aba e verificar se a seleção de perfil carrega.</small></p>';
echo '<p><a href="'.htmlspecialchars($probe, ENT_QUOTES, 'UTF-8').'" target="_blank" rel="noopener">→ abrir probe</a></p>';

/* ---------- 7) (Opcional) Teste de banco — desligado por padrão ---------- */
/* Para ativar: alterar RUN_DB_TEST para true */
define('RUN_DB_TEST', false);
$dbc_status = 'skipped';
$dbc_msg = '—';
if (RUN_DB_TEST) {
  $conexao_candidates = [
    __DIR__ . '/../src/model/conexao.php',
    __DIR__ . '/src/model/conexao.php',
  ];
  $cx_found = null;
  foreach ($conexao_candidates as $c) {
    if (is_file($c)) { $cx_found = $c; break; }
  }
  if ($cx_found) {
    require_once $cx_found;
    if (function_exists('conectar')) {
      try {
        $link = @conectar();
        if ($link) {
          $ok = @mysqli_query($link, 'SELECT 1');
          $dbc_status = $ok ? 'OK' : 'FAIL';
          $dbc_msg = $ok ? 'SELECT 1 executado' : 'Falha no SELECT 1';
          @mysqli_close($link);
        } else {
          $dbc_status = 'FAIL';
          $dbc_msg = 'conectar() retornou falso';
        }
      } catch (Throwable $t) {
        $dbc_status = 'FAIL';
        $dbc_msg = $t->getMessage();
      }
    } else {
      $dbc_status = 'FAIL';
      $dbc_msg = 'função conectar() não encontrada';
    }
  } else {
    $dbc_status = 'FAIL';
    $dbc_msg = 'conexao.php não encontrado';
  }
}
sec('G) Banco de dados (opcional)');
row('Status', $dbc_status);
row('Mensagem', $dbc_msg);
endsec();

echo "<hr><small>Ao concluir, APAGAR este arquivo (diag_env.php).</small>";
?>
</body></html>
