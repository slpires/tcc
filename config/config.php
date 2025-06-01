<?php
// Configuração de conexão automática - NÃO versionar este arquivo!
// Incluir uma linha no arquivo .gitignore com o seguinte trecho: /config/config.php

if (in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1'])) {
    // Ambiente de Desenvolvimento (Notebook, XAMPP)
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'tcc_slpires');
    define('DB_USER', 'root');
    define('DB_PASS', 'n#7R^xZ82!tM4qF');
    define('ENVIRONMENT', 'development');
} else {
    // Ambiente de Produção (HostGator)
    define('DB_HOST', 'br104.hostgator.com.br');
    define('DB_NAME', 'slpir421_tcc_slpires');
    define('DB_USER', 'slpir421_tcc_slpires_prd');
    define('DB_PASS', 'Wd8&kP@r1!mXeZ6');
    define('ENVIRONMENT', 'production');
}
?>

