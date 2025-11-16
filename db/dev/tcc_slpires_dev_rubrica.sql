USE tcc_slpires;

-- ===================================================================
-- SCRIPT DE CRIAÇÃO E POPULAÇÃO DA TABELA RUBRICA
-- Projeto: SLPIRES.COM – MVP TCC UFF
-- Ambiente: Desenvolvimento (MySQL Workbench)
-- Data: 06/06/2025
-- Responsável: Sérgio Luís de Oliveira Pires
-- ===================================================================

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
('0001', 'Salário', 'provento', FALSE, TRUE),                           -- Salário base mensal
('0002', 'Hora Extra', 'provento', FALSE, TRUE),                        -- Provento adicional por horas extras
('0003', 'Adiantamento do 13º Salário', 'provento', FALSE, TRUE),       -- 1ª parcela do 13º
('0004', '13º Salário', 'provento', FALSE, TRUE),                       -- 2ª parcela do 13º (quitação)
('0006', 'Férias', 'provento', FALSE, TRUE),                            -- Pagamento de férias
('0007', 'Quitação de Banco de Horas', 'provento', FALSE, TRUE),        -- Pagamento de saldo de horas
('0008', 'Adicional Noturno', 'provento', FALSE, TRUE),                 -- Provento por trabalho noturno
('0009', 'Pagamento de PLR', 'provento', FALSE, TRUE),                  -- Participação nos Lucros e Resultados
('0010', 'Pagamento de Bônus por Desempenho', 'provento', FALSE, TRUE), -- Bônus variável anual ou semestral
('0011', 'Ajuste de Proventos', 'provento', FALSE, TRUE),               -- Ajuste/correção positiva de valores
('0012', 'Valores a Recuperar', 'provento', FALSE, TRUE);               -- Provento gerado para compensação de descontos indevidos

-- ===================================================================
-- RUBRICAS DE DESCONTOS
-- Observação: limite_desconto = TRUE apenas para descontos facultativos/autorizados (entra no limite legal de 30%)
-- ===================================================================
INSERT INTO rubrica (cod_rubrica, descricao_rubrica, tipo_rubrica, limite_desconto, ativo) VALUES
('1001', 'IRRF', 'desconto', FALSE, TRUE),                                    -- Desconto obrigatório de IRRF (fora do limite de 30%)
('1002', 'INSS', 'desconto', FALSE, TRUE),                                    -- Desconto obrigatório de INSS (fora do limite de 30%)
('1003', 'Previdência Privada', 'desconto', TRUE, TRUE),                      -- Facultativo/autorizado (entra no limite de 30%)
('1004', 'Assistência Médica Custo Fixo', 'desconto', TRUE, TRUE),            -- Facultativo (entra no limite de 30%)
('1005', 'Assistência Médica Coparticipação', 'desconto', TRUE, TRUE),        -- Facultativo (entra no limite de 30%)
('1006', 'Pensão Judicial', 'desconto', FALSE, TRUE),                         -- Obrigatório judicial (fora do limite de 30%)
('1007', 'Frequência', 'desconto', TRUE, TRUE),                               -- Facultativo (entra no limite de 30%)
('1008', 'Empréstimo Consignado', 'desconto', TRUE, TRUE),                    -- Facultativo/autorizado (entra no limite de 30%)
('1009', 'Valor Recuperado Integral', 'desconto', TRUE, TRUE),                -- Recuperação de crédito a recuperar (entra no limite de 30%)
('1010', 'Valor Recuperado em Parcela', 'desconto', TRUE, TRUE),              -- Recuperação de crédito parcelado (entra no limite de 30%)
('1011', 'Desconto do Adiantamento do 13º Salário', 'desconto', FALSE, TRUE), -- Desconto obrigatório (ajuste do 13º)
('1012', 'Ajuste de Descontos', 'desconto', TRUE, TRUE);                      -- Ajuste/lançamento excepcional autorizado (entra no limite de 30%)

-- ===================================================================
-- RUBRICA DE LÍQUIDO
-- ===================================================================
INSERT INTO rubrica (cod_rubrica, descricao_rubrica, tipo_rubrica, limite_desconto, ativo) VALUES
('2000', 'Líquido', 'liquido', FALSE, TRUE);                          -- Valor líquido do contracheque
