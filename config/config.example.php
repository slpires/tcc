<?php
// Arquivo de exemplo - NÃO UTILIZAR EM PRODUÇÃO!
// Preencha os campos conforme cada ambiente e renomeie para config.php

if (in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1'])) {
    // Ambiente de Desenvolvimento (Notebook, XAMPP)
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'sua_base_desenvolvimento');
    define('DB_USER', 'seu_usuario_local');
    define('DB_PASS', 'sua_senha_local');
    define('ENVIRONMENT', 'development');
} else {
    // Ambiente de Produção (HostGator)
    define('DB_HOST', 'hostgator_mysql_host');
    define('DB_NAME', 'sua_base_producao');
    define('DB_USER', 'seu_usuario_producao');
    define('DB_PASS', 'sua_senha_producao');
    define('ENVIRONMENT', 'production');
}
?>
