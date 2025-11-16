-- =============================================================================
-- SCRIPT DE CRIAÇÃO DAS TABELAS CONTRACHEQUE E RUBRICA_CONTRACHEQUE
-- Projeto: SLPIRES.COM – MVP TCC UFF
-- Ambiente: Produção (Hostgator / MySQL)
-- Data de Adaptação: 06/06/2025
-- Autor: Sérgio Luís de Oliveira Pires
-- Origem: tcc_slpires_dev__contracheque__rubrica_contracheque.sql
-- Referência: Modelo Conceitual do Projeto / Diretrizes do TCC
-- =============================================================================

USE slpir421_tcc_slpires;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS rubrica_contracheque;
DROP TABLE IF EXISTS contracheque;
SET FOREIGN_KEY_CHECKS = 1;

-- -----------------------------------------------------
-- CRIAÇÃO DA TABELA CONTRACHEQUE
-- -----------------------------------------------------
CREATE TABLE contracheque (
    matricula             VARCHAR(10)   NOT NULL,
    data_pagamento        DATE          NOT NULL,
    tipo_folha            VARCHAR(15)   NOT NULL,
    status_contracheque   ENUM('GERADO','PAGO') NOT NULL DEFAULT 'GERADO',
    data_geracao          DATETIME      NOT NULL DEFAULT NOW(),
    observacoes           VARCHAR(200),
    PRIMARY KEY (matricula, data_pagamento, tipo_folha),
    FOREIGN KEY (matricula) REFERENCES empregado(matricula),
    FOREIGN KEY (tipo_folha) REFERENCES parametro_credito_desconto(tipo_folha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabela de contracheques do SLPIRES.COM (MVP)' ;

-- -----------------------------------------------------
-- CRIAÇÃO DA TABELA RUBRICA_CONTRACHEQUE
-- -----------------------------------------------------
CREATE TABLE rubrica_contracheque (
    matricula           VARCHAR(10)   NOT NULL,
    data_pagamento      DATE          NOT NULL,
    tipo_folha          VARCHAR(15)   NOT NULL,
    cod_rubrica         VARCHAR(10)   NOT NULL,
    descricao_rubrica   VARCHAR(100)  NOT NULL,
    tipo_rubrica        ENUM('provento','desconto','liquido') NOT NULL,
    valor               DECIMAL(12,2) NOT NULL,
    observacao          VARCHAR(200),
    PRIMARY KEY (matricula, data_pagamento, tipo_folha, cod_rubrica),
    FOREIGN KEY (matricula, data_pagamento, tipo_folha) REFERENCES contracheque(matricula, data_pagamento, tipo_folha),
    FOREIGN KEY (cod_rubrica) REFERENCES rubrica(cod_rubrica)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Rubricas associadas ao contracheque (MVP SLPIRES.COM)' ;
