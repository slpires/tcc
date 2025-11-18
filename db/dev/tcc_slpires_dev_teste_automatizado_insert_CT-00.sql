USE tcc_slpires;

-- =============================================================================
-- SCRIPT DE INSERÇÃO DO CASO DE TESTE CT-00 (SANIDADE DO MÓDULO TESTES)
-- Projeto  : SLPIRES.COM – MVP TCC UFF
-- Ambiente : Desenvolvimento (MariaDB 10.4.x)
-- Data     : 2025-11-16
-- Autor    : Sérgio Luís de Oliveira Pires
-- =============================================================================
-- Objetivo:
--   Registrar o caso de teste interno CT-00 no catálogo oficial
--   TESTE_AUTOMATIZADO, utilizado para validar o circuito completo do módulo:
--       catálogo → service → adapter TE → persistência → log → view.
--
-- Observações:
--   - Deve ser executado somente em AMBIENTE DEV.
--   - O campo cod_teste segue a lei de formação definida no projeto.
--   - status_teste inicial deve permanecer como 'nao_executado'.
--   - id_perfil_responsavel deve referenciar um ID válido em DEV.
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
