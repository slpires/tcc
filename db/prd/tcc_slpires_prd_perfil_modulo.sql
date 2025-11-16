-- =============================================================================
-- SCRIPT DE CRIAÇÃO E CARGA INICIAL DA TABELA PERFIL_MODULO (USANDO id_perfil)
-- Projeto: SLPIRES.COM – MVP TCC UFF
-- Ambiente: Produção (HostGator / MySQL)
-- Data de Adaptação: 06/06/2025
-- Autor: Sérgio Luís de Oliveira Pires
-- Origem: tcc_slpires_dev_perfil_modulo.sql
-- Referência: Modelo Conceitual do Projeto / Diretrizes do TCC
-- =============================================================================

USE slpir421_tcc_slpires;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS perfil_modulo;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE perfil_modulo (
    id_modulo INT NOT NULL,
    id_perfil INT NOT NULL,
    PRIMARY KEY (id_modulo, id_perfil),
    FOREIGN KEY (id_modulo) REFERENCES modulo(id_modulo),
    FOREIGN KEY (id_perfil) REFERENCES perfil_usuario(id_perfil)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabela de associação entre perfis de usuário e módulos do sistema SLPIRES.COM';

-- =============================
-- CARGA INICIAL DA TABELA PERFIL_MODULO
-- =============================

-- SIMULACAO_FOLHA (id_modulo = 1)
INSERT INTO perfil_modulo (id_modulo, id_perfil) VALUES (1, 1); -- Administrador
INSERT INTO perfil_modulo (id_modulo, id_perfil) VALUES (1, 2); -- RH

-- CONTROLE_CREDITO (id_modulo = 2)
INSERT INTO perfil_modulo (id_modulo, id_perfil) VALUES (2, 1); -- Administrador
INSERT INTO perfil_modulo (id_modulo, id_perfil) VALUES (2, 2); -- RH

-- RELATORIOS (id_modulo = 3)
INSERT INTO perfil_modulo (id_modulo, id_perfil) VALUES (3, 1); -- Administrador
INSERT INTO perfil_modulo (id_modulo, id_perfil) VALUES (3, 2); -- RH
INSERT INTO perfil_modulo (id_modulo, id_perfil) VALUES (3, 3); -- Empregado

-- TESTES (id_modulo = 4)
INSERT INTO perfil_modulo (id_modulo, id_perfil) VALUES (4, 1); -- Administrador
