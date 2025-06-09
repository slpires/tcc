<?php
$protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$url_base = '/tcc/public';
header("Location: $protocolo://$host$url_base/index.php?pagina=modulos");
exit;
