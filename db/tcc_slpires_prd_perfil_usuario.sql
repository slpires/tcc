-- SCRIPT DE CRIAÇÃO E POPULAÇÃO DA TABELA PERFIL_USUARIO
-- Ambiente: Produção (HostGator)

DROP TABLE IF EXISTS perfil_usuario;

CREATE TABLE perfil_usuario (
    id_perfil INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome_perfil VARCHAR(20) NOT NULL UNIQUE,
    descricao_perfil VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO perfil_usuario (nome_perfil, descricao_perfil) VALUES
    ('Administrador', 'Acesso total ao sistema, inclusive configurações e testes'),
    ('RH', 'Gestão dos créditos, simulação de folha, relatórios e consultas dos empregados do núcleo'),
    ('Empregado', 'Visualização dos próprios créditos e relatórios pessoais');
