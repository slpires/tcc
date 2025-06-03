-- ================================
-- SCRIPT OFICIAL - TABELA EMPREGADO (HostGator)
-- Projeto: SLPIRES.COM - TCC (UFF)
-- Última revisão: 02/06/2025
-- ================================

DROP TABLE IF EXISTS `empregado`;

CREATE TABLE `empregado` (
  `matricula` VARCHAR(10) NOT NULL,
  `nome` VARCHAR(100) NOT NULL,
  `cargo` VARCHAR(60) DEFAULT NULL,
  `nucleo_setor_sigla` VARCHAR(10) DEFAULT NULL,
  `nucleo_setor_descricao` VARCHAR(60) DEFAULT NULL,
  `genero` VARCHAR(10) DEFAULT NULL,
  `etnia` VARCHAR(30) DEFAULT NULL,
  `data_admissao` DATE NOT NULL DEFAULT '2018-01-01',
  `status` VARCHAR(15) NOT NULL DEFAULT 'ATIVO',
  PRIMARY KEY (`matricula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ================================
-- DADOS INICIAIS (INSERÇÃO EM BLOCOS)
-- ================================

INSERT INTO `empregado`
(`matricula`, `nome`, `cargo`, `nucleo_setor_sigla`, `nucleo_setor_descricao`, `genero`, `etnia`, `data_admissao`, `status`)
VALUES
('916800101','Dandara dos Palmares','Chief Executive Officer','DIR','Diretoria Executiva','F','Preta','2018-01-01','ATIVO'),
('919020711','Sérgio Buarque','Chief Operating Officer','DIR','Diretoria Executiva','M','Branco','2018-01-01','ATIVO'),
('919010711','Antonieta de Barros','Controller','ADM','Núcleo Administrativo-Financeiro','F','Preta','2018-01-01','ATIVO'),
('918720805','Oswaldo Cruz','Analista Financeiro Sênior','ADM','Núcleo Administrativo-Financeiro','M','Branco','2018-01-01','ATIVO'),
('918120101','Luiza Mahin','Analista Fiscal','ADM','Núcleo Administrativo-Financeiro','F','Preta','2018-01-01','ATIVO'),
('918650505','Cândido Mariano','Analista de Compras','ADM','Núcleo Administrativo-Financeiro','M','Pardo/índio','2018-01-01','ATIVO'),
('919050215','Nise da Silveira','Analista Jurídico','ADM','Núcleo Administrativo-Financeiro','F','Branca','2018-01-01','ATIVO'),
('918300621','Luiz Gama','Assistente Administrativo','ADM','Núcleo Administrativo-Financeiro','M','Preto','2018-01-01','ATIVO'),
('918650101','Iracema','Recepcionista (Staff)','ADM','Núcleo Administrativo-Financeiro','F','Indígena','2018-01-01','ATIVO'),
('918960414','Alfredo Volpi','Aux. Serviços Gerais (PCD)','ADM','Núcleo Administrativo-Financeiro','M','Branco/PCD','2018-01-01','ATIVO'),
('919140314','Carolina Maria de Jesus','Gerente de RH','RH','Núcleo de Pessoas (RH)','F','Preta','2018-01-01','ATIVO'),
('918730720','Santos Dumont','Analista de DP','RH','Núcleo de Pessoas (RH)','M','Branco','2018-01-01','ATIVO'),
('917920627','Maria Quitéria','Analista de Desenvolvimento','RH','Núcleo de Pessoas (RH)','F','Branca','2018-01-01','ATIVO'),
('919140315','Abdias do Nascimento','Analista de Benefícios','RH','Núcleo de Pessoas (RH)','M','Preto','2018-01-01','ATIVO'),
('917600211','Bárbara de Alencar','Coordenadora de TI','TI','Núcleo de Tecnologia da Informação','F','Branca','2018-01-01','ATIVO'),
('918390621','Machado de Assis','Arquiteto de Sistemas','TI','Núcleo de Tecnologia da Informação','M','Pardo/PCD','2018-01-01','ATIVO'),
('919130113','Enedina Alves Marques','Engenheira de Dados','TI','Núcleo de Tecnologia da Informação','F','Preta','2018-01-01','ATIVO'),
('918870305','Heitor Villa-Lobos','Desenvolvedor Sênior','TI','Núcleo de Tecnologia da Informação','M','Branco','2018-01-01','ATIVO'),
('919790727','Marielle Franco','Desenvolvedora Sênior','TI','Núcleo de Tecnologia da Informação','F','Parda','2018-01-01','ATIVO'),
('918510728','Manuel Querino','Analista de Segurança','TI','Núcleo de Tecnologia da Informação','M','Preto','2018-01-01','ATIVO'),
('919320215','Cacique Raoni','Analista de Infraestrutura','TI','Núcleo de Tecnologia da Informação','M','Indígena','2018-01-01','ATIVO'),
('918650102','Ceci','DevOps','TI','Núcleo de Tecnologia da Informação','F','Indígena','2018-01-01','ATIVO'),
('917070101','Antonil','Desenvolvedor Júnior','TI','Núcleo de Tecnologia da Informação','M','Branco','2018-01-01','ATIVO'),
('918471017','Chiquinha Gonzaga','Desenvolvedora Júnior','TI','Núcleo de Tecnologia da Informação','F','Branca','2018-01-01','ATIVO'),
('918810513','Lima Barreto','Analista de Suporte','TI','Núcleo de Tecnologia da Informação','M','Pardo','2018-01-01','ATIVO'),
('919340825','Zilda Arns','Analista de Suporte','TI','Núcleo de Tecnologia da Informação','F','Branca/PCD','2018-01-01','ATIVO'),
('916550101','Zumbi dos Palmares','Gerente de Operações','OPS','Núcleo de Operações e Serviços','M','Preto','2018-01-01','ATIVO'),
('918210830','Anita Garibaldi','Analista de Contratos','OPS','Núcleo de Operações e Serviços','F','Branca','2018-01-01','ATIVO'),
('917510101','Esperança Garcia','Analista de Serviços','OPS','Núcleo de Operações e Serviços','F','Preta','2018-01-01','ATIVO'),
('918490819','Joaquim Nabuco','Analista de Projetos','OPS','Núcleo de Operações e Serviços','M','Branco','2018-01-01','ATIVO'),
('919341130','Luiza Erundina','Supervisora de Atendimento','OPS','Núcleo de Operações e Serviços','F','Parda','2018-01-01','ATIVO'),
('918531009','José do Patrocínio','Supervisor de Atendimento','OPS','Núcleo de Operações e Serviços','M','Preto','2018-01-01','ATIVO'),
('919221026','Darcy Ribeiro','Técnico de Campo','OPS','Núcleo de Operações e Serviços','M','Branco','2018-01-01','ATIVO'),
('918650103','Dandara do Sertão','Técnica de Campo','OPS','Núcleo de Operações e Serviços','F','Parda/PCD','2018-01-01','ATIVO'),
('919420410','Juruna','Assistente de Operações','OPS','Núcleo de Operações e Serviços','M','Indígena','2018-01-01','ATIVO'),
('919100609','Pagu','Assistente de Operações','OPS','Núcleo de Operações e Serviços','F','Branca','2018-01-01','ATIVO'),
('918380113','André Rebouças','Gerente de Inovação','INOVAÇÃO','Núcleo de Inovação e Projetos','M','Preto','2018-01-01','ATIVO'),
('918940802','Bertha Lutz','Cientista de Dados','INOVAÇÃO','Núcleo de Inovação e Projetos','F','Branca','2018-01-01','ATIVO'),
('917000101','Tereza de Benguela','Analista de Transformação','INOVAÇÃO','Núcleo de Inovação e Projetos','F','Preta','2018-01-01','ATIVO'),
('919310618','Fernando H. Cardoso','Analista de Projetos','INOVAÇÃO','Núcleo de Inovação e Projetos','M','Branco/PCD','2018-01-01','ATIVO');

-- ================================
-- Fim do script oficial.
-- ================================
