USE slpir421_tcc_slpires;

-- =============================================================================
-- SCRIPT DE INSERÇÃO DO CASO DE TESTE CT-91 (SIMULAÇÃO DE ERRO - MÓDULO TESTES)
-- Projeto  : SLPIRES.COM – MVP TCC UFF
-- Ambiente : Produção (HostGator / MySQL)
-- Data     : 2025-11-17
-- Autor    : Sérgio Luís de Oliveira Pires
-- =============================================================================
-- Objetivo:
--   Registrar o caso de teste interno CT-91 no catálogo oficial
--   TESTE_AUTOMATIZADO, utilizado para simular um erro técnico (ERROR) e validar:
--       catálogo → service → adapter TE → tratamento de ERROR → log → view de erro.
--
-- Observações:
--   - Deve ser executado somente em AMBIENTE PRD.
--   - O campo cod_teste segue a lei de formação definida no projeto.
--   - status_teste inicial deve permanecer como 'nao_executado'.
--   - id_perfil_responsavel deve referenciar um ID válido em PRD.
--   - Este caso é exclusivo para simulação de erro técnico controlado do Módulo TESTES.
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
    '[CT-91] - TE-ERRO',
    'TE',
    'Simulação de erro técnico controlado do módulo TESTES (ERROR)',
    'alta',
    'Simulação de exceção (ERROR) para validar o tratamento de erro técnico no módulo.',
    'unitario',
    'nao_executado',
    NULL,
    '[SIM_ERROR] Caso de teste destinado exclusivamente à simulação de retorno ERROR no Módulo.',
    1,
    1,
    NOW(),
    NULL
);
