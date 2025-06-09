<?php
/*
    /src/model/conexao.php
    [INCLUSÃO]
    Função centralizada de conexão com o banco de dados para o sistema SLPIRES.COM (TCC UFF).
    Garante governança, rastreabilidade e padronização no acesso aos dados institucionais.
*/

/* [INCLUSÃO] Importa as definições de configuração e ambiente */
require_once __DIR__ . '/../../config/config.php';

/* [FUNÇÃO] Conecta ao banco de dados MySQL/MariaDB, retornando objeto mysqli ativo */
function conectar() {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if (!$conn) {
        /* [TRATAMENTO] Erro detalhado apenas em ambiente de desenvolvimento */
        if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
            die('Erro ao conectar ao banco de dados: ' . mysqli_connect_error());
        } else {
            /* [PRODUÇÃO] Redireciona para landing page com código de erro padronizado */
            header("Location: ../../public/index.php?erro=falha_conexao_bd");
            exit;
        }
    }
    /* [INCLUSÃO] Garante charset UTF-8 para acentuação correta no banco */
    mysqli_set_charset($conn, 'utf8');
    return $conn;
}
