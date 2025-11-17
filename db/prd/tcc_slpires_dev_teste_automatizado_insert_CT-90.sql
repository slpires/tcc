USE slpir421_tcc_slpires;

-- =============================================================================
-- SCRIPT DE INSERÇÃO DO CASO DE TESTE CT-90 (SIMULAÇÃO DE FALHA - MÓDULO TESTES)
-- Projeto  : SLPIRES.COM – MVP TCC UFF
-- Ambiente : Produção (HostGator / MySQL)
-- Data     : 2025-11-17
-- Autor    : Sérgio Luís de Oliveira Pires
-- =============================================================================
-- Objetivo:
--   Registrar o caso de teste interno CT-90 no catálogo oficial
--   TESTE_AUTOMATIZADO, utilizado para simular uma falha (FAIL) e validar:
--       catálogo → service → adapter TE → tratamento de FAIL → log → view.
--
-- Observações:
--   - Deve ser executado somente em AMBIENTE PRD.
--   - O campo cod_teste segue a lei de formação definida no projeto.
--   - status_teste inicial deve permanecer como 'nao_executado'.
--   - id_perfil_responsavel deve referenciar um ID válido em PRD.
--   - Este caso é exclusivo para simulação de falha controlada do Módulo TESTES.
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
    ativo,
    criado_em,
    atualizado_em
) VALUES (
    '[CT-90] - TE-FAIL',
    'TE',
    'Simulação de falha controlada do módulo TESTES (FAIL)',
    'alta',
    'Simulação de (FAIL) para validar o comportamento do módulo em caso de reprovação controlada.',
    'unitario',
    'nao_executado',
    NULL,
    '[SIM_FAIL] Caso de teste destinado exclusivamente à simulação de retorno FAIL.',
    1,
    1,
    NOW(),
    NULL
);
