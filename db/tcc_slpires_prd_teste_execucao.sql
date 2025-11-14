USE slpir421_tcc_slpires;

-- =============================================================================
-- SCRIPT DE CRIAÇÃO / RECRIAÇÃO DA TABELA TESTE_EXECUCAO
-- Projeto          : SLPIRES.COM – MVP TCC UFF
-- Ambiente         : Produção (HostGator / MySQL)
-- Data de Criação  : 2025-11-13
-- Autor            : Sérgio Luís de Oliveira Pires
-- Origem           : tcc_slpires_dev_teste_execucao.sql
-- Referências      : Diretrizes do TCC / Modelo Conceitual / Módulo TESTES
-- =============================================================================
-- Objetivo:
--   Criar a tabela TESTE_EXECUCAO, responsável por armazenar o histórico
--   detalhado de execuções do MÓDULO TESTES. Cada registro representa uma
--   execução isolada de um caso de teste descrito em TESTE_AUTOMATIZADO.
--
--   A tabela permite auditoria completa de:
--     - entrada do teste (entrada_json)
--     - resultado esperado (esperado_json)
--     - resultado obtido (saida_json)
--     - status da execução (PASS/FAIL/ERROR/SKIP)
--     - duração em ms
--     - modo dry-run
--     - data/hora da execução
--
-- Observações importantes:
--   1) Este script derruba e recria completamente a tabela.
--   2) Só deve ser aplicado enquanto o módulo TESTES ainda não estiver
--      operacional em PRD (tabela vazia ou inexistente).
--   3) Estrutura 100% alinhada com o ambiente DEV.
-- =============================================================================

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS teste_execucao;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE teste_execucao (
    id_execucao INT NOT NULL AUTO_INCREMENT,
    id_teste INT NOT NULL,

    -- Registro da execução
    entrada_json   JSON NULL,
    esperado_json  JSON NULL,
    saida_json     JSON NULL,

    -- Status e resultado
    status_execucao ENUM('PASS','FAIL','ERROR','SKIP') NOT NULL DEFAULT 'SKIP',
    duracao_ms INT NULL,
    mensagem VARCHAR(500) NULL,
    dry_run TINYINT(1) NOT NULL DEFAULT 1,

    -- Auditoria
    executado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id_execucao),

    CONSTRAINT fk_execucao_teste_prd
        FOREIGN KEY (id_teste)
        REFERENCES teste_automatizado(id_teste)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci
COMMENT='Histórico de execuções individuais do MÓDULO TESTES no ambiente PRD (SLPIRES.COM).';

-- Índice para relatórios, ordenações e análises de governança
CREATE INDEX idx_execucao_relatorio_prd
    ON teste_execucao (id_teste, status_execucao, executado_em);
