-- ===================================================================
-- SCRIPT DE CRIAÇÃO E POPULAÇÃO DA TABELA PERFIL_USUARIO
-- Projeto: SLPIRES.COM – MVP TCC UFF
-- Ambiente: Produção (HostGator / MySQL)
-- Data de Adaptação: 06/06/2025
-- Responsável: Sérgio Luís de Oliveira Pires
-- Origem: tcc_slpires_dev_perfil_usuario.sql
-- Referência: Modelo Conceitual do Projeto / Diretrizes do TCC
-- ===================================================================

USE slpir421_tcc_slpires;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS perfil_usuario;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE perfil_usuario (
    id_perfil INT PRIMARY KEY AUTO_INCREMENT,
    nome_perfil VARCHAR(40) NOT NULL,
    descricao_perfil VARCHAR(100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Perfis de usuário do sistema SLPIRES.COM';

-- =====================
-- POPULAÇÃO INICIAL
-- =====================

INSERT INTO perfil_usuario (nome_perfil, descricao_perfil) VALUES
    ('Administrador', 'Acesso total ao sistema, inclusive configurações e testes'),
    ('RH', 'Gestão dos créditos, simulação de folha, relatórios e consultas dos empregados de toda a empresa'),
    ('Empregado', 'Visualização dos próprios créditos e relatórios pessoais');
