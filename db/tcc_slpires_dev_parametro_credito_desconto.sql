-- ====================================================================
-- SCRIPT DE CRIAÇÃO E POPULAÇÃO DA TABELA PARAMETRO_CREDITO_DESCONTO
-- Projeto: SLPIRES.COM – MVP TCC UFF
-- Ambiente: Desenvolvimento (MySQL Workbench)
-- Data: 03/06/2025
-- Responsável: Sérgio Luís de Oliveira Pires
-- ====================================================================

DROP TABLE IF EXISTS parametro_credito_desconto;

CREATE TABLE parametro_credito_desconto (
    tipo_folha VARCHAR(20) NOT NULL PRIMARY KEY,     -- Ex: MENSAL, 13_SALARIO, FERIAS, PLR
    desconto_automatico ENUM('SIM', 'NAO') NOT NULL DEFAULT 'SIM',
    tipo_desconto ENUM('saldo_devedor', 'parcela') NOT NULL DEFAULT 'parcela',
    data_ultima_alteracao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ====================================================================
-- CARGA INICIAL – PARÂMETROS OBRIGATÓRIOS
-- ====================================================================

INSERT INTO parametro_credito_desconto (tipo_folha, desconto_automatico, tipo_desconto, data_ultima_alteracao) VALUES
    ('MENSAL', 'SIM', 'parcela', NOW()),            -- Folha Mensal: desconto automático ativo, em parcelas
    ('13_SALARIO', 'SIM', 'parcela', NOW()),  -- 13º Salário: permite desconto automático de parcela
    ('FERIAS', 'SIM', 'parcela', NOW()),            -- Férias: desconto automático desativado de parcela
    ('PLR', 'NAO', 'parcela', NOW()),         -- PLR: sem desconto automátioc permitido de parcela
    ('BONUS', 'SIM', 'saldo_devedor', NOW());             -- Bônus: com desconto automático até o saldo devedor se possível

-- ====================================================================
-- Fim do script – Revisar antes de executar em produção.
-- ====================================================================
