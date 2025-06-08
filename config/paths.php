<?php
// /config/paths.php
// Define a variável $base_url para ser usada em todas as views (CSS, JS, img etc.)

$host = $_SERVER['SERVER_NAME'] ?? '';
if ($host === 'localhost' || $host === '127.0.0.1') {
    $base_url = '/tcc/public'; // Ajuste conforme a subpasta local de desenvolvimento
} else {
    $base_url = ''; // Produção: assume raiz do site
}
?>
