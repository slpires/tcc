-- ===================================================================
-- SCRIPT DE CRIAÇÃO E POPULAÇÃO DA TABELA RUBRICA
-- Projeto: SLPIRES.COM – MVP TCC UFF
-- Ambiente: Produção (HostGator / MySQL)
-- Data de Adaptação: 06/06/2025
-- Responsável: Sérgio Luís de Oliveira Pires
-- Origem: tcc_slpires_dev_rubrica.sql
-- Referência: Modelo Conceitual do Projeto / Diretrizes do TCC
-- ===================================================================

USE slpir421_tcc_slpires;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS rubrica;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE rubrica (
    cod_rubrica VARCHAR(10) PRIMARY KEY,
    descricao_rubrica VARCHAR(100),
    tipo_rubrica ENUM('provento','desconto','liquido') NOT NULL,
    limite_desconto BOOLEAN,
    ativo BOOLEAN
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabela de rubricas do SLPIRES.COM';

-- =====================
-- POPULAÇÃO INICIAL
-- =====================

-- ===================================================================
-- RUBRICAS DE PROVENTOS
-- ===================================================================
INSERT INTO rubrica (cod_rubrica, descricao_rubrica, tipo_rubrica, limite_desconto, ativo) VALUES
('0001', 'Salário', 'provento', FALSE, TRUE),
('0002', 'Hora Extra', 'provento', FALSE, TRUE),
('0003', 'Adiantamento do 13º Salário', 'provento', FALSE, TRUE),
('0004', '13º Salário', 'provento', FALSE, TRUE),
('0006', 'Férias', 'provento', FALSE, TRUE),
('0007', 'Quitação de Banco de Horas', 'provento', FALSE, TRUE),
('0008', 'Adicional Noturno', 'provento', FALSE, TRUE),
('0009', 'Pagamento de PLR', 'provento', FALSE, TRUE),
('0010', 'Pagamento de Bônus por Desempenho', 'provento', FALSE, TRUE),
('0011', 'Ajuste de Proventos', 'provento', FALSE, TRUE),
('0012', 'Valores a Recuperar', 'provento', FALSE, TRUE);

-- ===================================================================
-- RUBRICAS DE DESCONTOS
-- Observação: limite_desconto = TRUE apenas para descontos facultativos/autorizados (entra no limite legal de 30%)
-- ===================================================================
INSERT INTO rubrica (cod_rubrica, descricao_rubrica, tipo_rubrica, limite_desconto, ativo) VALUES
('1001', 'IRRF', 'desconto', FALSE, TRUE),
('1002', 'INSS', 'desconto', FALSE, TRUE),
('1003', 'Previdência Privada', 'desconto', TRUE, TRUE),
('1004', 'Assistência Médica Custo Fixo', 'desconto', TRUE, TRUE),
('1005', 'Assistência Médica Coparticipação', 'desconto', TRUE, TRUE),
('1006', 'Pensão Judicial', 'desconto', FALSE, TRUE),
('1007', 'Frequência', 'desconto', TRUE, TRUE),
('1008', 'Empréstimo Consignado', 'desconto', TRUE, TRUE),
('1009', 'Valor Recuperado Integral', 'desconto', TRUE, TRUE),
('1010', 'Valor Recuperado em Parcela', 'desconto', TRUE, TRUE),
('1011', 'Desconto do Adiantamento do 13º Salário', 'desconto', FALSE, TRUE),
('1012', 'Ajuste de Descontos', 'desconto', TRUE, TRUE);

-- ===================================================================
-- RUBRICA DE LÍQUIDO
-- ===================================================================
INSERT INTO rubrica (cod_rubrica, descricao_rubrica, tipo_rubrica, limite_desconto, ativo) VALUES
('2000', 'Líquido', 'liquido', FALSE, TRUE);
