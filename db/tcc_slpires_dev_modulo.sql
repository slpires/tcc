USE tcc_slpires;

-- =============================================================================
-- SCRIPT DE CRIAÇÃO E CARGA INICIAL DA TABELA MODULO
-- Projeto: SLPIRES.COM – MVP TCC UFF
-- Ambiente: Desenvolvimento (MySQL Workbench)
-- Data: 2025-06-06
-- Autor: Sérgio Luís de Oliveira Pires
-- =============================================================================

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS modulo;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE modulo (
    id_modulo INT PRIMARY KEY AUTO_INCREMENT,
    nome_modulo VARCHAR(40) NOT NULL UNIQUE,
    descricao_modulo VARCHAR(200),
    status ENUM('ativo','inativo') NOT NULL DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabela de módulos do sistema SLPIRES.COM';

-- =============================
-- POPULAÇÃO INICIAL DA TABELA MODULO
-- =============================

INSERT INTO modulo (nome_modulo, descricao_modulo, status) VALUES
('SIMULACAO_FOLHA', 'Simulação de folha de pagamento e eventos associados.', 'ativo'),
('CONTROLE_CREDITO', 'Gestão dos créditos a recuperar, regras de parcelamento e rastreabilidade.', 'ativo'),
('RELATORIOS', 'Relatórios de créditos, histórico de recuperação, relatórios gerenciais e operacionais.', 'ativo'),
('TESTES', 'Testes automatizados e de integridade do sistema, garantindo validação dos fluxos principais do MVP.', 'ativo');
