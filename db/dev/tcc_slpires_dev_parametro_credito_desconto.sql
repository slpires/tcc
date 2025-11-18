USE tcc_slpires;

-- ===================================================================
-- SCRIPT DE CRIAÇÃO E POPULAÇÃO DA TABELA PARAMETRO_CREDITO_DESCONTO
-- Projeto: SLPIRES.COM – MVP TCC UFF
-- Ambiente: Desenvolvimento (MySQL Workbench)
-- Data: 06/06/2025
-- Responsável: Sérgio Luís de Oliveira Pires
-- ===================================================================

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS parametro_credito_desconto;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE parametro_credito_desconto (
    tipo_folha VARCHAR(15) PRIMARY KEY,
    desconto_automatico ENUM('SIM','NAO') NOT NULL,
    tipo_desconto VARCHAR(30) NOT NULL,
    data_ultima_alteracao DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Parâmetros de desconto por tipo de folha (SLPIRES.COM)';


-- =====================
-- POPULAÇÃO INICIAL
-- =====================

INSERT INTO parametro_credito_desconto (tipo_folha, desconto_automatico, tipo_desconto, data_ultima_alteracao) VALUES
    ('MENSAL', 'SIM', 'parcela', NOW()),            -- Folha Mensal: desconto automático ativo em parcelas
    ('13_SALARIO', 'SIM', 'parcela', NOW()),        -- 13º Salário: desconto automático ativo em parcelas
    ('FERIAS', 'SIM', 'parcela', NOW()),            -- Férias: desconto automático ativo em parcelas
    ('PLR', 'NAO', 'parcela', NOW()),               -- PLR: sem desconto automátioc permitido de parcela
    ('BONUS', 'SIM', 'saldo_devedor', NOW());       -- Bônus: desconto automático ativo para o saldo_devedor


