<?php
// Configuração de conexão automática - NÃO versionar este arquivo!
// Incluir uma linha no arquivo .gitignore com o seguinte trecho: /config/config.php

if (in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1'])) {
    // Ambiente de Desenvolvimento (Notebook, XAMPP)
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'tcc_dev');
    define('DB_USER', 'root');
    define('DB_PASS', 'senha_local');
    define('ENVIRONMENT', 'development');
} else {
    // Ambiente de Produção (HostGator)
    define('DB_HOST', 'mysql01.hostgator.com.br');
    define('DB_NAME', 'tcc_producao');
    define('DB_USER', 'usuario_producao');
    define('DB_PASS', 'senha_producao');
    define('ENVIRONMENT', 'production');
}
?>

