<?php
// /src/model/conexao.php

// Inclui as definições de conexão do arquivo de configuração centralizado
require_once __DIR__ . '/../../config/config.php';

// Função procedural para obter uma conexão MySQLi ativa conforme ambiente
function conectar() {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if (!$conn) {
        // Erro detalhado apenas em ambiente de desenvolvimento!
        die('Erro ao conectar ao banco de dados: ' . mysqli_connect_error());
    }
    // Garante charset UTF-8 para acentuação correta no banco
    mysqli_set_charset($conn, 'utf8');
    return $conn;
}
?>
