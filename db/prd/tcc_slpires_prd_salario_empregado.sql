-- ===================================================================
-- SCRIPT DE CRIAÇÃO E POPULAÇÃO DA TABELA SALARIO_EMPREGADO
-- Projeto: SLPIRES.COM – MVP TCC UFF
-- Ambiente: Produção (HostGator / MySQL)
-- Data de Adaptação: 06/06/2025
-- Responsável: Sérgio Luís de Oliveira Pires
-- Origem: tcc_slpires_dev_salario_empregado.sql
-- Referência: Modelo Conceitual do Projeto / Diretrizes do TCC
-- ===================================================================

USE slpir421_tcc_slpires;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS salario_empregado;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE salario_empregado (
    matricula VARCHAR(10) NOT NULL,
    ano_referencia INT NOT NULL,
    valor_salario DECIMAL(12,2) NOT NULL,
    data_reajuste DATE NOT NULL,
    percentual_reajuste DECIMAL(5,2) NOT NULL,
    observacao VARCHAR(100),
    PRIMARY KEY (matricula, ano_referencia),
    FOREIGN KEY (matricula) REFERENCES empregado(matricula)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Histórico de salários dos empregados SLPIRES.COM';

-- =====================
-- POPULAÇÃO INICIAL
-- =====================
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('916800101', 2018, 30383.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('916800101', 2019, 33041.51, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('916800101', 2020, 36117.67, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('916800101', 2021, 39556.07, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('916800101', 2022, 45513.21, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('916800101', 2023, 50424.09, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('916800101', 2024, 55274.89, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('916800101', 2025, 60708.41, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('919020711', 2018, 23673.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('919020711', 2019, 25744.39, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('919020711', 2020, 28141.19, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('919020711', 2021, 30820.23, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('919020711', 2022, 35461.76, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('919020711', 2023, 39288.08, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('919020711', 2024, 43067.59, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('919020711', 2025, 47301.13, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('919010711', 2018, 16438.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('919010711', 2019, 17876.32, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('919010711', 2020, 19540.61, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('919010711', 2021, 21400.88, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('919010711', 2022, 24623.85, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('919010711', 2023, 27280.76, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('919010711', 2024, 29905.17, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('919010711', 2025, 32844.85, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('918720805', 2018, 8812.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('918720805', 2019, 9583.05, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('918720805', 2020, 10475.23, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('918720805', 2021, 11472.47, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('918720805', 2022, 13200.22, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('918720805', 2023, 14624.52, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('918720805', 2024, 16031.40, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('918720805', 2025, 17607.29, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('918120101', 2018, 9337.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('918120101', 2019, 10153.99, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('918120101', 2020, 11099.33, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('918120101', 2021, 12155.99, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('918120101', 2022, 13986.68, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('918120101', 2023, 15495.84, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('918120101', 2024, 16986.54, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('918120101', 2025, 18656.32, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('918650505', 2018, 8791.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('918650505', 2019, 9560.21, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('918650505', 2020, 10450.27, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('918650505', 2021, 11445.14, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('918650505', 2022, 13168.78, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('918650505', 2023, 14589.69, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('918650505', 2024, 15993.22, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('918650505', 2025, 17565.35, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('919050215', 2018, 8496.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('919050215', 2019, 9239.40, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('919050215', 2020, 10099.59, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('919050215', 2021, 11061.07, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('919050215', 2022, 12726.87, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('919050215', 2023, 14100.10, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('919050215', 2024, 15456.53, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('919050215', 2025, 16975.91, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('918300621', 2018, 3493.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('918300621', 2019, 3798.64, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('918300621', 2020, 4152.29, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('918300621', 2021, 4547.59, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('918300621', 2022, 5232.46, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('918300621', 2023, 5797.04, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('918300621', 2024, 6354.72, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('918300621', 2025, 6979.39, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('918650101', 2018, 3487.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('918650101', 2019, 3792.11, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('918650101', 2020, 4145.16, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('918650101', 2021, 4539.78, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('918650101', 2022, 5223.47, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('918650101', 2023, 5787.08, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('918650101', 2024, 6343.80, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('918650101', 2025, 6967.40, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('918960414', 2018, 2010.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('918960414', 2019, 2185.88, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('918960414', 2020, 2389.39, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('918960414', 2021, 2616.86, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('918960414', 2022, 3010.96, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('918960414', 2023, 3335.84, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('918960414', 2024, 3656.75, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('918960414', 2025, 4016.21, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('919140314', 2018, 13501.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('919140314', 2019, 14682.34, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('919140314', 2020, 16049.27, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('919140314', 2021, 17577.16, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('919140314', 2022, 20224.28, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('919140314', 2023, 22406.48, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('919140314', 2024, 24561.98, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('919140314', 2025, 26976.42, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('918730720', 2018, 6603.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('918730720', 2019, 7180.76, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('918730720', 2020, 7849.29, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('918730720', 2021, 8596.54, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('918730720', 2022, 9891.18, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('918730720', 2023, 10958.44, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('918730720', 2024, 12012.64, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('918730720', 2025, 13193.48, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('917920627', 2018, 6737.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('917920627', 2019, 7326.49, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('917920627', 2020, 8008.59, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('917920627', 2021, 8771.01, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('917920627', 2022, 10091.92, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('917920627', 2023, 11180.84, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('917920627', 2024, 12256.44, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('917920627', 2025, 13461.25, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('919140315', 2018, 6345.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('919140315', 2019, 6900.19, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('919140315', 2020, 7542.60, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('919140315', 2021, 8260.66, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('919140315', 2022, 9504.72, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('919140315', 2023, 10530.28, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('919140315', 2024, 11543.29, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('919140315', 2025, 12678.00, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('917600211', 2018, 11809.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('917600211', 2019, 12842.29, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('917600211', 2020, 14037.91, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('917600211', 2021, 15374.32, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('917600211', 2022, 17689.69, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('917600211', 2023, 19598.41, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('917600211', 2024, 21483.78, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('917600211', 2025, 23595.64, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('918390621', 2018, 14105.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('918390621', 2019, 15339.19, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('918390621', 2020, 16767.27, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('918390621', 2021, 18363.51, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('918390621', 2022, 21129.05, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('918390621', 2023, 23408.87, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('918390621', 2024, 25660.80, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('918390621', 2025, 28183.26, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('919130113', 2018, 15137.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('919130113', 2019, 16461.49, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('919130113', 2020, 17994.05, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('919130113', 2021, 19707.08, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('919130113', 2022, 22674.97, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('919130113', 2023, 25121.60, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('919130113', 2024, 27538.30, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('919130113', 2025, 30245.31, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('918870305', 2018, 10893.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('918870305', 2019, 11846.14, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('918870305', 2020, 12949.02, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('918870305', 2021, 14181.77, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('918870305', 2022, 16317.54, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('918870305', 2023, 18078.20, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('918870305', 2024, 19817.32, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('918870305', 2025, 21765.36, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('919790727', 2018, 9563.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('919790727', 2019, 10399.76, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('919790727', 2020, 11367.98, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('919790727', 2021, 12450.21, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('919790727', 2022, 14325.21, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('919790727', 2023, 15870.90, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('919790727', 2024, 17397.68, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('919790727', 2025, 19107.87, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('918510728', 2018, 8425.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('918510728', 2019, 9162.19, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('918510728', 2020, 10015.19, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('918510728', 2021, 10968.64, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('918510728', 2022, 12620.52, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('918510728', 2023, 13982.27, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('918510728', 2024, 15327.36, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('918510728', 2025, 16834.04, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('919320215', 2018, 7165.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('919320215', 2019, 7791.94, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('919320215', 2020, 8517.37, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('919320215', 2021, 9328.22, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('919320215', 2022, 10733.05, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('919320215', 2023, 11891.15, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('919320215', 2024, 13035.08, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('919320215', 2025, 14316.43, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('918650102', 2018, 11402.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('918650102', 2019, 12399.67, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('918650102', 2020, 13554.08, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('918650102', 2021, 14844.43, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('918650102', 2022, 17080.00, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('918650102', 2023, 18922.93, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('918650102', 2024, 20743.32, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('918650102', 2025, 22782.39, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('917070101', 2018, 5007.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('917070101', 2019, 5445.11, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('917070101', 2020, 5952.05, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('917070101', 2021, 6518.69, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('917070101', 2022, 7500.40, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('917070101', 2023, 8309.69, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('917070101', 2024, 9109.08, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('917070101', 2025, 10004.50, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('918471017', 2018, 4471.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('918471017', 2019, 4862.21, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('918471017', 2020, 5314.88, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('918471017', 2021, 5820.86, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('918471017', 2022, 6697.48, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('918471017', 2023, 7420.14, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('918471017', 2024, 8133.96, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('918471017', 2025, 8933.53, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('918810513', 2018, 6352.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('918810513', 2019, 6907.80, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('918810513', 2020, 7550.92, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('918810513', 2021, 8269.77, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('918810513', 2022, 9515.20, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('918810513', 2023, 10541.89, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('918810513', 2024, 11556.02, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('918810513', 2025, 12691.98, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('919340825', 2018, 5090.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('919340825', 2019, 5535.38, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('919340825', 2020, 6050.72, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('919340825', 2021, 6626.75, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('919340825', 2022, 7624.74, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('919340825', 2023, 8447.45, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('919340825', 2024, 9260.09, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('919340825', 2025, 10170.36, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('916550101', 2018, 14324.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('916550101', 2019, 15577.35, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('916550101', 2020, 17027.60, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('916550101', 2021, 18648.63, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('916550101', 2022, 21457.11, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('916550101', 2023, 23772.33, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('916550101', 2024, 26059.23, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('916550101', 2025, 28620.85, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('918210830', 2018, 6286.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('918210830', 2019, 6836.02, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('918210830', 2020, 7472.45, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('918210830', 2021, 8183.83, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('918210830', 2022, 9416.31, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('918210830', 2023, 10432.33, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('918210830', 2024, 11435.92, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('918210830', 2025, 12560.07, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('917510101', 2018, 6186.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('917510101', 2019, 6727.27, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('917510101', 2020, 7353.58, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('917510101', 2021, 8053.64, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('917510101', 2022, 9266.52, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('917510101', 2023, 10266.38, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('917510101', 2024, 11254.01, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('917510101', 2025, 12360.28, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('918490819', 2018, 10110.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('918490819', 2019, 10994.62, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('918490819', 2020, 12018.22, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('918490819', 2021, 13162.35, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('918490819', 2022, 15144.60, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('918490819', 2023, 16778.70, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('918490819', 2024, 18392.81, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('918490819', 2025, 20200.82, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('919341130', 2018, 4245.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('919341130', 2019, 4616.44, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('919341130', 2020, 5046.23, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('919341130', 2021, 5526.63, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('919341130', 2022, 6358.94, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('919341130', 2023, 7045.07, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('919341130', 2024, 7722.81, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('919341130', 2025, 8481.96, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('918531009', 2018, 5423.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('918531009', 2019, 5897.51, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('918531009', 2020, 6446.57, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('918531009', 2021, 7060.28, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('918531009', 2022, 8123.56, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('918531009', 2023, 9000.09, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('918531009', 2024, 9865.90, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('918531009', 2025, 10835.72, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('919221026', 2018, 2668.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('919221026', 2019, 2901.45, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('919221026', 2020, 3171.57, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('919221026', 2021, 3473.50, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('919221026', 2022, 3996.61, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('919221026', 2023, 4427.84, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('919221026', 2024, 4853.80, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('919221026', 2025, 5330.93, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('918650103', 2018, 3221.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('918650103', 2019, 3502.84, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('918650103', 2020, 3828.95, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('918650103', 2021, 4193.47, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('918650103', 2022, 4825.01, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('918650103', 2023, 5345.63, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('918650103', 2024, 5859.88, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('918650103', 2025, 6435.91, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('919420410', 2018, 3257.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('919420410', 2019, 3541.99, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('919420410', 2020, 3871.75, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('919420410', 2021, 4240.34, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('919420410', 2022, 4878.94, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('919420410', 2023, 5405.38, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('919420410', 2024, 5925.38, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('919420410', 2025, 6507.84, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('919100609', 2018, 3928.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('919100609', 2019, 4271.70, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('919100609', 2020, 4669.40, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('919100609', 2021, 5113.93, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('919100609', 2022, 5884.09, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('919100609', 2023, 6518.98, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('919100609', 2024, 7146.11, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('919100609', 2025, 7848.57, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('918380113', 2018, 14682.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('918380113', 2019, 15966.67, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('918380113', 2020, 17453.17, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('918380113', 2021, 19114.71, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('918380113', 2022, 21993.39, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('918380113', 2023, 24366.48, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('918380113', 2024, 26710.54, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('918380113', 2025, 29336.19, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('918940802', 2018, 15707.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('918940802', 2019, 17081.36, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('918940802', 2020, 18671.63, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('918940802', 2021, 20449.17, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('918940802', 2022, 23528.82, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('918940802', 2023, 26067.58, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('918940802', 2024, 28575.28, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('918940802', 2025, 31384.23, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('917000101', 2018, 6555.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('917000101', 2019, 7128.56, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('917000101', 2020, 7792.23, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('917000101', 2021, 8534.05, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('917000101', 2022, 9819.28, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('917000101', 2023, 10878.78, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('917000101', 2024, 11925.32, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('917000101', 2025, 13097.58, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES
('919310618', 2018, 9151.00, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência'),
('919310618', 2019, 9951.71, '2019-01-01', 8.75, 'Reajuste anual de salário conforme política da empresa'),
('919310618', 2020, 10878.21, '2020-01-01', 9.31, 'Reajuste anual de salário conforme política da empresa'),
('919310618', 2021, 11913.82, '2021-01-01', 9.52, 'Reajuste anual de salário conforme política da empresa'),
('919310618', 2022, 13708.04, '2022-01-01', 15.06, 'Reajuste anual de salário conforme política da empresa'),
('919310618', 2023, 15187.14, '2023-01-01', 10.79, 'Reajuste anual de salário conforme política da empresa'),
('919310618', 2024, 16648.14, '2024-01-01', 9.62, 'Reajuste anual de salário conforme política da empresa'),
('919310618', 2025, 18284.65, '2025-01-01', 9.83, 'Reajuste anual de salário conforme política da empresa');
