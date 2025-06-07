USE tcc_slpires;

-- =============================================================================
-- SCRIPT DE CRIAÇÃO DA TABELA TESTE_AUTOMATIZADO
-- Projeto: SLPIRES.COM – MVP TCC UFF
-- Ambiente: Desenvolvimento (MySQL Workbench)
-- Data: 2025-06-06
-- Autor: Sérgio Luís de Oliveira Pires
-- =============================================================================

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS teste_automatizado;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE teste_automatizado (
    id_teste INT NOT NULL AUTO_INCREMENT,
    descricao_teste VARCHAR(250) NOT NULL,
    tipo_teste ENUM('unitario','integrado','exibicao') NOT NULL,
    status_teste ENUM('nao_executado','executando','aprovado','reprovado') NOT NULL DEFAULT 'nao_executado',
    data_execucao DATETIME,
    resultado VARCHAR(200),
    observacoes VARCHAR(250),
    id_perfil_responsavel INT NOT NULL,
    PRIMARY KEY (id_teste),
    FOREIGN KEY (id_perfil_responsavel) REFERENCES perfil_usuario(id_perfil)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabela de testes automatizados e registro de execuções, vinculada ao perfil responsável (SLPIRES.COM)';
