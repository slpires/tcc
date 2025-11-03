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
            /* [PRODUÇÃO] Redireciona para a landing page do front controller com código de erro padronizado */
            if (function_exists('error_log')) {
                error_log('[DB][CONN][FAIL] Falha de conexão com o banco (detalhes suprimidos em produção).');
            }

            // Preferência por constante de base (definida em config.php), com fallback para a raiz.
            $baseUrl = defined('BASE_URL') ? BASE_URL : (defined('URL_BASE') ? URL_BASE : '');

            http_response_code(302);
            header('Location: ' . rtrim($baseUrl, '/') . '/index.php?erro=falha_conexao_bd');
            exit;
        }
    }

    /* [INCLUSÃO] Define charset preferencialmente como UTF-8 completo; fallback para utf8 */
    if (!@mysqli_set_charset($conn, 'utf8mb4')) {
        mysqli_set_charset($conn, 'utf8');
    }

    return $conn;
}
