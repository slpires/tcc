USE tcc_slpires;

-- =============================================================================
-- SCRIPT DE CRIAÇÃO / RECRIAÇÃO DA TABELA TESTE_AUTOMATIZADO
-- Projeto : SLPIRES.COM – MVP TCC UFF
-- Ambiente: Desenvolvimento (MariaDB 10.4.x)
-- Data    : 2025-11-13 (Atualização)
-- Autor   : Sérgio Luís de Oliveira Pires
-- =============================================================================
-- Objetivo:
--   Recriar a tabela TESTE_AUTOMATIZADO como CATÁLOGO OFICIAL DE CASOS DE TESTE,
--   alinhada ao MÓDULO TESTES, servindo como matriz de casos, cenários e
--   prioridades. O histórico de execuções ficará em TESTE_EXECUCAO.
--
-- Notas:
--   - Este script deve ser executado apenas em AMBIENTE DEV.
--   - Remove a tabela anterior e recria toda a estrutura.
--   - Mantém compatibilidade com a FK para PERFIL_USUARIO.
-- =============================================================================

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS teste_automatizado;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE teste_automatizado (
    id_teste INT NOT NULL AUTO_INCREMENT,

    -- Metadados do caso de teste
    modulo        VARCHAR(40)  NOT NULL,   -- Ex.: SIMULACAO_FOLHA, CONTROLE_CREDITO, INFRA
    cenario       VARCHAR(60)  NOT NULL,   -- Ex.: calc, integration, sanity
    prioridade    ENUM('alta','media','baixa') NOT NULL DEFAULT 'media',

    -- Descrição do caso de teste
    descricao_teste VARCHAR(250) NOT NULL,

    -- Classificação do tipo de teste
    tipo_teste ENUM('unitario','integrado','exibicao') NOT NULL,

    -- Status global do caso (agregado da última execução)
    status_teste ENUM(
        'nao_executado',
        'executando',
        'aprovado',
        'reprovado'
    ) NOT NULL DEFAULT 'nao_executado',

    -- Informações administrativas
    data_execucao DATETIME NULL,
    observacoes   VARCHAR(250) NULL,

    -- Relacionamento institucional
    id_perfil_responsavel INT NOT NULL,

    -- Governança do catálogo
    ativo         TINYINT(1) NOT NULL DEFAULT 1,
    criado_em     DATETIME   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME   NULL ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id_teste),

    CONSTRAINT fk_teste_perfil
        FOREIGN KEY (id_perfil_responsavel)
        REFERENCES perfil_usuario(id_perfil)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci
COMMENT='Catálogo oficial de casos de teste automatizado (MÓDULO TESTES SLPIRES.COM), vinculado ao PERFIL_USUARIO.';

-- Índice funcional para listagens e filtros no MÓDULO TESTES
CREATE INDEX idx_teste_catalogo_modulo
    ON teste_automatizado (modulo, cenario, tipo_teste, prioridade);
