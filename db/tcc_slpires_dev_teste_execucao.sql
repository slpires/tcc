USE tcc_slpires;

-- =============================================================================
-- SCRIPT DE CRIAÇÃO DA TABELA TESTE_EXECUCAO
-- Projeto : SLPIRES.COM – MVP TCC UFF
-- Ambiente: Desenvolvimento (MariaDB 10.4.x)
-- Data    : 2025-11-13
-- Autor   : Sérgio Luís de Oliveira Pires
-- =============================================================================
-- Objetivo:
--   Criar a tabela TESTE_EXECUCAO para armazenar o histórico detalhado
--   de execuções do MÓDULO TESTES. Cada linha representa uma execução
--   individual, vinculada a um caso de teste do catálogo TESTE_AUTOMATIZADO.
-- =============================================================================

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS teste_execucao;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE teste_execucao (
    id_execucao INT NOT NULL AUTO_INCREMENT,
    id_teste INT NOT NULL,

    -- Payloads da execução
    entrada_json   JSON NULL,
    esperado_json  JSON NULL,
    saida_json     JSON NULL,

    -- Status e controle
    status_execucao ENUM('PASS','FAIL','ERROR','SKIP') NOT NULL DEFAULT 'SKIP',
    duracao_ms INT NULL,
    mensagem VARCHAR(500) NULL,
    dry_run TINYINT(1) NOT NULL DEFAULT 1,

    -- Auditoria
    executado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id_execucao),

    CONSTRAINT fk_execucao_teste
        FOREIGN KEY (id_teste)
        REFERENCES teste_automatizado(id_teste)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci
COMMENT='Histórico de execuções do MÓDULO TESTES (SLPIRES.COM).';

-- Índice para facilitar relatórios e filtros
CREATE INDEX idx_execucao_relatorio
    ON teste_execucao (id_teste, status_execucao, executado_em);
