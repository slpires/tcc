-- =============================================================================
-- QUERY DE VERIFICAÇÃO DA MATRIZ DE PERFIS DE ACESSO POR MÓDULO
-- Projeto: SLPIRES.COM – MVP TCC UFF
-- Ambiente: Produção (HostGator / MySQL)
-- Data de Adaptação: 06/06/2025
-- Autor: Sérgio Luís de Oliveira Pires
-- Origem: tcc_slpires_dev_query_verifica_matriz_perfis_de_acesso.sql
-- Referência: Modelo Conceitual do Projeto / Diretrizes do TCC
-- =============================================================================

USE slpir421_tcc_slpires;

SELECT
    pu.nome_perfil AS Perfil,
    MAX(CASE WHEN m.nome_modulo = 'SIMULACAO_FOLHA' THEN 'X' ELSE '' END) AS SIMULACAO_FOLHA,
    MAX(CASE WHEN m.nome_modulo = 'CONTROLE_CREDITO' THEN 'X' ELSE '' END) AS CONTROLE_CREDITO,
    MAX(CASE WHEN m.nome_modulo = 'RELATORIOS' THEN 'X' ELSE '' END) AS RELATORIOS,
    MAX(CASE WHEN m.nome_modulo = 'TESTES' THEN 'X' ELSE '' END) AS TESTES
FROM
    perfil_usuario pu
    LEFT JOIN perfil_modulo pm ON pu.id_perfil = pm.id_perfil
    LEFT JOIN modulo m ON pm.id_modulo = m.id_modulo
GROUP BY
    pu.nome_perfil
ORDER BY
    FIELD(pu.nome_perfil, 'Administrador', 'RH', 'Empregado');
