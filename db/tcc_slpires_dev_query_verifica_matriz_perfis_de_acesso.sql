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


