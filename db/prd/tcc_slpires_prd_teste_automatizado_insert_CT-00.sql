USE slpir421_tcc_slpires;

-- =============================================================================
-- SCRIPT DE INSERÇÃO DO CASO DE TESTE CT-00 (SANIDADE DO MÓDULO TESTES)
-- Projeto  : SLPIRES.COM – MVP TCC UFF
-- Ambiente : Produção (HostGator / MySQL)
-- Data     : 2025-11-16
-- Autor    : Sérgio Luís de Oliveira Pires
-- Origem   : Script oficial do ambiente DEV
-- =============================================================================
-- Objetivo:
--   Registrar em PRODUÇÃO o caso de teste interno CT-00, utilizado para validar
--   o circuito completo do módulo TESTES:
--       catálogo → controller → service → adapter TE → persistência → log → view.
--
-- Observações:
--   - Executar SOMENTE se a tabela TESTE_AUTOMATIZADO estiver vazia em PRD.
--   - Utilizado apenas para validação inicial do MÓDULO TESTES em produção.
--   - O campo cod_teste deve ser idêntico ao utilizado em DEV.
--   - status_teste inicial deve ser 'nao_executado'.
--   - id_perfil_responsavel deve referenciar um ID válido em PRD.
-- =============================================================================

INSERT INTO teste_automatizado (
    cod_teste,
    modulo,
    cenario,
    prioridade,
    descricao_teste,
    tipo_teste,
    status_teste,
    data_execucao,
    observacoes,
    id_perfil_responsavel,
    ativo
) VALUES (
    '[CT-00] - TE-SANI',
    'TE',
    'Sanidade do módulo TESTES (circuito completo)',
    'alta',
    'Verificar se o módulo TESTES executa corretamente o fluxo catálogo → adapter → persistência → log → view.',
    'unitario',
    'nao_executado',
    NULL,
    'Caso de teste interno para validação do próprio módulo TESTES.',
    1,
    1
);
