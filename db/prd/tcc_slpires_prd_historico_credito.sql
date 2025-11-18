-- =============================================================================
-- SCRIPT DE CRIAÇÃO DA TABELA HISTORICO_CREDITO
-- Projeto: SLPIRES.COM – MVP TCC UFF
-- Ambiente: Produção (HostGator / MySQL)
-- Data de Adaptação: 06/06/2025
-- Autor: Sérgio Luís de Oliveira Pires
-- Origem: tcc_slpires_dev_historico_credito.sql
-- Referência: Modelo Conceitual do Projeto / Diretrizes do TCC
-- =============================================================================

USE slpir421_tcc_slpires;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS historico_credito;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE historico_credito (
    id_historico INT NOT NULL AUTO_INCREMENT,
    id_credito INT NOT NULL,
    data_movimento DATETIME NOT NULL,
    tipo_movimento ENUM('inclusao','ajuste','recuperacao','baixa','cancelamento') NOT NULL,
    valor_movimento DECIMAL(12,2) NOT NULL,
    saldo_apos_movimento DECIMAL(12,2) NOT NULL,
    id_perfil_responsavel INT NOT NULL,
    observacoes VARCHAR(250),
    PRIMARY KEY (id_historico),
    FOREIGN KEY (id_credito) REFERENCES credito(id_credito)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (id_perfil_responsavel) REFERENCES perfil_usuario(id_perfil)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Histórico das movimentações dos créditos a recuperar dos empregados (SLPIRES.COM)';
