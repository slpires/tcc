-- SCRIPT DE CRIAÇÃO E POPULAÇÃO DA TABELA PARAMETRO_CREDITO_DESCONTO
-- Ambiente: Produção (HostGator)
-- Data: 03/06/2025
-- Responsável: Sérgio Luís de Oliveira Pires

DROP TABLE IF EXISTS parametro_credito_desconto;

CREATE TABLE parametro_credito_desconto (
    tipo_folha VARCHAR(20) NOT NULL PRIMARY KEY,   -- Ex: MENSAL, 13_SALARIO, FERIAS, PLR
    desconto_automatico ENUM('SIM', 'NAO') NOT NULL DEFAULT 'SIM',
    tipo_desconto ENUM('saldo_devedor', 'parcela') NOT NULL DEFAULT 'parcela',
    data_ultima_alteracao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO parametro_credito_desconto (tipo_folha, desconto_automatico, tipo_desconto, data_ultima_alteracao) VALUES
    ('MENSAL', 'SIM', 'parcela', NOW()),
    ('13_SALARIO', 'SIM', 'parcela', NOW()),
    ('FERIAS', 'SIM', 'parcela', NOW()),
    ('PLR', 'NAO', 'parcela', NOW()),
    ('BONUS', 'SIM', 'saldo_devedor', NOW());
