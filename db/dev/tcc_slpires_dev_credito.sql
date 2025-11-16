USE tcc_slpires;

-- =============================================================================
-- SCRIPT DE CRIAÇÃO DA TABELA CREDITO
-- Projeto: SLPIRES.COM – MVP TCC UFF
-- Ambiente: Desenvolvimento (MySQL Workbench)
-- Data: 2025-06-06
-- Autor: Sérgio Luís de Oliveira Pires
-- =============================================================================

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS credito;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE credito (
    id_credito INT NOT NULL AUTO_INCREMENT,
    matricula VARCHAR(12) NOT NULL,
    valor_original DECIMAL(12,2) NOT NULL,
    tipo_erro VARCHAR(50),
    origem_credito VARCHAR(100),
    status_credito ENUM('iniciado','em_andamento','recuperado') NOT NULL DEFAULT 'iniciado', -- Estados do ciclo de vida do crédito, conforme modelo conceitual aprovado
    data_lancamento DATE NOT NULL,
    justificativa VARCHAR(250),
    observacoes VARCHAR(250),
    data_ultima_atualizacao DATETIME,
    PRIMARY KEY (id_credito),
    FOREIGN KEY (matricula) REFERENCES empregado(matricula)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabela de créditos a recuperar lançados para empregados no MVP SLPIRES.COM';
