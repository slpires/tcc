USE tcc_slpires;

-- ===================================================================
-- SCRIPT DE CRIAÇÃO E POPULAÇÃO DA TABELA EMPREGADO
-- Projeto: SLPIRES.COM – MVP TCC UFF
-- Ambiente: Desenvolvimento (MySQL Workbench)
-- Data: 06/06/2025
-- Responsável: Sérgio Luís de Oliveira Pires
-- ===================================================================

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS empregado;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE empregado (
    matricula VARCHAR(10) PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    genero CHAR(1) NOT NULL,
    cargo VARCHAR(60),
    nucleo_setor_sigla VARCHAR(10),
    nucleo_setor_descricao VARCHAR(80),
    etnia VARCHAR(20),
    data_admissao DATE,
    status ENUM('ATIVO','SUSPENSO','DESLIGADO') NOT NULL DEFAULT 'ATIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Cadastro de empregados SLPIRES.COM';

-- =====================
-- POPULAÇÃO INICIAL
-- =====================

INSERT INTO empregado VALUES ('916550101','Zumbi dos Palmares','M','Gerente de Operações','OPS','Núcleo de Operações e Serviços','Preto','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('916800101','Dandara dos Palmares','F','Chief Executive Officer','DIR','Diretoria Executiva','Preta','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('917000101','Tereza de Benguela','F','Analista de Transformação','INOVAÇÃO','Núcleo de Inovação e Projetos','Preta','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('917070101','Antonil','M','Desenvolvedor Júnior','TI','Núcleo de Tecnologia da Informação','Branco','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('917510101','Esperança Garcia','F','Analista de Serviços','OPS','Núcleo de Operações e Serviços','Preta','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('917600211','Bárbara de Alencar','F','Coordenadora de TI','TI','Núcleo de Tecnologia da Informação','Branca','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('917920627','Maria Quitéria','F','Analista de Desenvolvimento','RH','Núcleo de Pessoas (RH)','Branca','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('918120101','Luiza Mahin','F','Analista Fiscal','ADM','Núcleo Administrativo-Financeiro','Preta','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('918210830','Anita Garibaldi','F','Analista de Contratos','OPS','Núcleo de Operações e Serviços','Branca','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('918300621','Luiz Gama','M','Assistente Administrativo','ADM','Núcleo Administrativo-Financeiro','Preto','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('918380113','André Rebouças','M','Gerente de Inovação','INOVAÇÃO','Núcleo de Inovação e Projetos','Preto','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('918390621','Machado de Assis','M','Arquiteto de Sistemas','TI','Núcleo de Tecnologia da Informação','Pardo/PCD','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('918471017','Chiquinha Gonzaga','F','Desenvolvedora Júnior','TI','Núcleo de Tecnologia da Informação','Branca','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('918490819','Joaquim Nabuco','M','Analista de Projetos','OPS','Núcleo de Operações e Serviços','Branco','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('918510728','Manuel Querino','M','Analista de Segurança','TI','Núcleo de Tecnologia da Informação','Preto','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('918531009','José do Patrocínio','M','Supervisor de Atendimento','OPS','Núcleo de Operações e Serviços','Preto','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('918650101','Iracema','F','Recepcionista (Staff)','ADM','Núcleo Administrativo-Financeiro','Indígena','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('918650102','Ceci','F','DevOps','TI','Núcleo de Tecnologia da Informação','Indígena','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('918650103','Dandara do Sertão','F','Técnica de Campo','OPS','Núcleo de Operações e Serviços','Parda/PCD','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('918650505','Cândido Mariano','M','Analista de Compras','ADM','Núcleo Administrativo-Financeiro','Pardo/índio','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('918720805','Oswaldo Cruz','M','Analista Financeiro Sênior','ADM','Núcleo Administrativo-Financeiro','Branco','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('918730720','Santos Dumont','M','Analista de DP','RH','Núcleo de Pessoas (RH)','Branco','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('918810513','Lima Barreto','M','Analista de Suporte','TI','Núcleo de Tecnologia da Informação','Pardo','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('918870305','Heitor Villa-Lobos','M','Desenvolvedor Sênior','TI','Núcleo de Tecnologia da Informação','Branco','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('918940802','Bertha Lutz','F','Cientista de Dados','INOVAÇÃO','Núcleo de Inovação e Projetos','Branca','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('918960414','Alfredo Volpi','M','Aux. Serviços Gerais (PCD)','ADM','Núcleo Administrativo-Financeiro','Branco/PCD','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('919010711','Antonieta de Barros','F','Controller','ADM','Núcleo Administrativo-Financeiro','Preta','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('919020711','Sérgio Buarque','M','Chief Operating Officer','DIR','Diretoria Executiva','Branco','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('919050215','Nise da Silveira','F','Analista Jurídico','ADM','Núcleo Administrativo-Financeiro','Branca','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('919100609','Pagu','F','Assistente de Operações','OPS','Núcleo de Operações e Serviços','Branca','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('919130113','Enedina Alves Marques','F','Engenheira de Dados','TI','Núcleo de Tecnologia da Informação','Preta','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('919140314','Carolina Maria de Jesus','F','Gerente de RH','RH','Núcleo de Pessoas (RH)','Preta','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('919140315','Abdias do Nascimento','M','Analista de Benefícios','RH','Núcleo de Pessoas (RH)','Preto','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('919221026','Darcy Ribeiro','M','Técnico de Campo','OPS','Núcleo de Operações e Serviços','Branco','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('919310618','Fernando H. Cardoso','M','Analista de Projetos','INOVAÇÃO','Núcleo de Inovação e Projetos','Branco/PCD','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('919320215','Cacique Raoni','M','Analista de Infraestrutura','TI','Núcleo de Tecnologia da Informação','Indígena','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('919340825','Zilda Arns','F','Analista de Suporte','TI','Núcleo de Tecnologia da Informação','Branca/PCD','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('919341130','Luiza Erundina','F','Supervisora de Atendimento','OPS','Núcleo de Operações e Serviços','Parda','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('919420410','Juruna','M','Assistente de Operações','OPS','Núcleo de Operações e Serviços','Indígena','2018-01-01','ATIVO');
INSERT INTO empregado VALUES ('919790727','Marielle Franco','F','Desenvolvedora Sênior','TI','Núcleo de Tecnologia da Informação','Parda','2018-01-01','ATIVO');
