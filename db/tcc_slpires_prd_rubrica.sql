-- SCRIPT DE CRIAÇÃO E POPULAÇÃO DA TABELA RUBRICA
-- Ambiente: Produção (HostGator)
-- Data: 03/06/2025

DROP TABLE IF EXISTS rubrica;

CREATE TABLE rubrica (
    cod_rubrica VARCHAR(5) NOT NULL PRIMARY KEY,
    descricao_rubrica VARCHAR(80) NOT NULL,
    tipo_rubrica ENUM('provento', 'desconto', 'liquido') NOT NULL,
    limite_desconto BOOLEAN NOT NULL DEFAULT FALSE,
    ativo BOOLEAN NOT NULL DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Proventos
INSERT INTO rubrica (cod_rubrica, descricao_rubrica, tipo_rubrica, limite_desconto, ativo) VALUES
('0001', 'Salario', 'provento', FALSE, TRUE),
('0002', 'Hora Extra', 'provento', FALSE, TRUE),
('0003', 'Adiantamento do 13 Salario', 'provento', FALSE, TRUE),
('0004', '13 Salario', 'provento', FALSE, TRUE),
('0006', 'Ferias', 'provento', FALSE, TRUE),
('0007', 'Quitacao de Banco de Horas', 'provento', FALSE, TRUE),
('0008', 'Adicional Noturno', 'provento', FALSE, TRUE),
('0009', 'Pagamento de PLR', 'provento', FALSE, TRUE),
('0010', 'Pagamento de Bonus por Desempenho', 'provento', FALSE, TRUE),
('0011', 'Ajuste de Proventos', 'provento', FALSE, TRUE),
('0012', 'Valores a Recuperar', 'provento', FALSE, TRUE);

-- Descontos (TRUE no limite_desconto só para descontos facultativos/autorizados)
INSERT INTO rubrica (cod_rubrica, descricao_rubrica, tipo_rubrica, limite_desconto, ativo) VALUES
('1001', 'IRRF', 'desconto', FALSE, TRUE),
('1002', 'INSS', 'desconto', FALSE, TRUE),
('1003', 'Previdencia Privada', 'desconto', TRUE, TRUE),
('1004', 'Assistencia Medica Custo Fixo', 'desconto', TRUE, TRUE),
('1005', 'Assistencia Medica Coparticipacao', 'desconto', TRUE, TRUE),
('1006', 'Pensao Judicial', 'desconto', FALSE, TRUE),
('1007', 'Frequencia', 'desconto', TRUE, TRUE),
('1008', 'Emprestimo Consignado', 'desconto', TRUE, TRUE),
('1009', 'Valor Recuperado Integral', 'desconto', TRUE, TRUE),
('1010', 'Valor Recuperado em Parcela', 'desconto', TRUE, TRUE),
('1011', 'Desconto do Adiantamento do 13 Salario', 'desconto', FALSE, TRUE),
('1012', 'Ajuste de Descontos', 'desconto', TRUE, TRUE);

-- Líquido
INSERT INTO rubrica (cod_rubrica, descricao_rubrica, tipo_rubrica, limite_desconto, ativo) VALUES
('2000', 'Liquido', 'liquido', FALSE, TRUE);
