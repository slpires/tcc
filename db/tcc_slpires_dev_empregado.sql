-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: tcc_slpires
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `empregado`
--

DROP TABLE IF EXISTS `empregado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empregado` (
  `matricula` varchar(10) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cargo` varchar(60) DEFAULT NULL,
  `nucleo_setor_sigla` varchar(10) DEFAULT NULL,
  `nucleo_setor_descricao` varchar(60) DEFAULT NULL,
  `genero` varchar(10) DEFAULT NULL,
  `etnia` varchar(30) DEFAULT NULL,
  `data_admissao` date NOT NULL DEFAULT '2018-01-01',
  `status` varchar(15) NOT NULL DEFAULT 'ATIVO',
  PRIMARY KEY (`matricula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empregado`
--

LOCK TABLES `empregado` WRITE;
/*!40000 ALTER TABLE `empregado` DISABLE KEYS */;
INSERT INTO `empregado` VALUES ('916550101','Zumbi dos Palmares','Gerente de Operações','OPS','Núcleo de Operações e Serviços','M','Preto','2018-01-01','ATIVO'),('916800101','Dandara dos Palmares','Chief Executive Officer','DIR','Diretoria Executiva','F','Preta','2018-01-01','ATIVO'),('917000101','Tereza de Benguela','Analista de Transformação','INOVAÇÃO','Núcleo de Inovação e Projetos','F','Preta','2018-01-01','ATIVO'),('917070101','Antonil','Desenvolvedor Júnior','TI','Núcleo de Tecnologia da Informação','M','Branco','2018-01-01','ATIVO'),('917510101','Esperança Garcia','Analista de Serviços','OPS','Núcleo de Operações e Serviços','F','Preta','2018-01-01','ATIVO'),('917600211','Bárbara de Alencar','Coordenadora de TI','TI','Núcleo de Tecnologia da Informação','F','Branca','2018-01-01','ATIVO'),('917920627','Maria Quitéria','Analista de Desenvolvimento','RH','Núcleo de Pessoas (RH)','F','Branca','2018-01-01','ATIVO'),('918120101','Luiza Mahin','Analista Fiscal','ADM','Núcleo Administrativo-Financeiro','F','Preta','2018-01-01','ATIVO'),('918210830','Anita Garibaldi','Analista de Contratos','OPS','Núcleo de Operações e Serviços','F','Branca','2018-01-01','ATIVO'),('918300621','Luiz Gama','Assistente Administrativo','ADM','Núcleo Administrativo-Financeiro','M','Preto','2018-01-01','ATIVO'),('918380113','André Rebouças','Gerente de Inovação','INOVAÇÃO','Núcleo de Inovação e Projetos','M','Preto','2018-01-01','ATIVO'),('918390621','Machado de Assis','Arquiteto de Sistemas','TI','Núcleo de Tecnologia da Informação','M','Pardo/PCD','2018-01-01','ATIVO'),('918471017','Chiquinha Gonzaga','Desenvolvedora Júnior','TI','Núcleo de Tecnologia da Informação','F','Branca','2018-01-01','ATIVO'),('918490819','Joaquim Nabuco','Analista de Projetos','OPS','Núcleo de Operações e Serviços','M','Branco','2018-01-01','ATIVO'),('918510728','Manuel Querino','Analista de Segurança','TI','Núcleo de Tecnologia da Informação','M','Preto','2018-01-01','ATIVO'),('918531009','José do Patrocínio','Supervisor de Atendimento','OPS','Núcleo de Operações e Serviços','M','Preto','2018-01-01','ATIVO'),('918650101','Iracema','Recepcionista (Staff)','ADM','Núcleo Administrativo-Financeiro','F','Indígena','2018-01-01','ATIVO'),('918650102','Ceci','DevOps','TI','Núcleo de Tecnologia da Informação','F','Indígena','2018-01-01','ATIVO'),('918650103','Dandara do Sertão','Técnica de Campo','OPS','Núcleo de Operações e Serviços','F','Parda/PCD','2018-01-01','ATIVO'),('918650505','Cândido Mariano','Analista de Compras','ADM','Núcleo Administrativo-Financeiro','M','Pardo/índio','2018-01-01','ATIVO'),('918720805','Oswaldo Cruz','Analista Financeiro Sênior','ADM','Núcleo Administrativo-Financeiro','M','Branco','2018-01-01','ATIVO'),('918730720','Santos Dumont','Analista de DP','RH','Núcleo de Pessoas (RH)','M','Branco','2018-01-01','ATIVO'),('918810513','Lima Barreto','Analista de Suporte','TI','Núcleo de Tecnologia da Informação','M','Pardo','2018-01-01','ATIVO'),('918870305','Heitor Villa-Lobos','Desenvolvedor Sênior','TI','Núcleo de Tecnologia da Informação','M','Branco','2018-01-01','ATIVO'),('918940802','Bertha Lutz','Cientista de Dados','INOVAÇÃO','Núcleo de Inovação e Projetos','F','Branca','2018-01-01','ATIVO'),('918960414','Alfredo Volpi','Aux. Serviços Gerais (PCD)','ADM','Núcleo Administrativo-Financeiro','M','Branco/PCD','2018-01-01','ATIVO'),('919010711','Antonieta de Barros','Controller','ADM','Núcleo Administrativo-Financeiro','F','Preta','2018-01-01','ATIVO'),('919020711','Sérgio Buarque','Chief Operating Officer','DIR','Diretoria Executiva','M','Branco','2018-01-01','ATIVO'),('919050215','Nise da Silveira','Analista Jurídico','ADM','Núcleo Administrativo-Financeiro','F','Branca','2018-01-01','ATIVO'),('919100609','Pagu','Assistente de Operações','OPS','Núcleo de Operações e Serviços','F','Branca','2018-01-01','ATIVO'),('919130113','Enedina Alves Marques','Engenheira de Dados','TI','Núcleo de Tecnologia da Informação','F','Preta','2018-01-01','ATIVO'),('919140314','Carolina Maria de Jesus','Gerente de RH','RH','Núcleo de Pessoas (RH)','F','Preta','2018-01-01','ATIVO'),('919140315','Abdias do Nascimento','Analista de Benefícios','RH','Núcleo de Pessoas (RH)','M','Preto','2018-01-01','ATIVO'),('919221026','Darcy Ribeiro','Técnico de Campo','OPS','Núcleo de Operações e Serviços','M','Branco','2018-01-01','ATIVO'),('919310618','Fernando H. Cardoso','Analista de Projetos','INOVAÇÃO','Núcleo de Inovação e Projetos','M','Branco/PCD','2018-01-01','ATIVO'),('919320215','Cacique Raoni','Analista de Infraestrutura','TI','Núcleo de Tecnologia da Informação','M','Indígena','2018-01-01','ATIVO'),('919340825','Zilda Arns','Analista de Suporte','TI','Núcleo de Tecnologia da Informação','F','Branca/PCD','2018-01-01','ATIVO'),('919341130','Luiza Erundina','Supervisora de Atendimento','OPS','Núcleo de Operações e Serviços','F','Parda','2018-01-01','ATIVO'),('919420410','Juruna','Assistente de Operações','OPS','Núcleo de Operações e Serviços','M','Indígena','2018-01-01','ATIVO'),('919790727','Marielle Franco','Desenvolvedora Sênior','TI','Núcleo de Tecnologia da Informação','F','Parda','2018-01-01','ATIVO');
/*!40000 ALTER TABLE `empregado` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-02 23:52:56
