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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `matricula` varchar(10) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cargo` varchar(60) DEFAULT NULL,
  `lotacao` varchar(60) DEFAULT NULL,
  `genero` varchar(10) DEFAULT NULL,
  `etnia` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `matricula` (`matricula`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empregado`
--

LOCK TABLES `empregado` WRITE;
/*!40000 ALTER TABLE `empregado` DISABLE KEYS */;
INSERT INTO `empregado` VALUES (1,'916800101','Dandara dos Palmares','Chief Executive Officer','Diretoria Executiva','F','Preta'),(2,'919020711','Sérgio Buarque','Chief Operating Officer','Diretoria Executiva','M','Branco'),(3,'919010711','Antonieta de Barros','Controller','ADM','F','Preta'),(4,'918720805','Oswaldo Cruz','Analista Financeiro Sênior','ADM','M','Branco'),(5,'918120101','Luiza Mahin','Analista Fiscal','ADM','F','Preta'),(6,'918650505','Cândido Mariano','Analista de Compras','ADM','M','Pardo/índio'),(7,'919050215','Nise da Silveira','Analista Jurídico','ADM','F','Branca'),(8,'918300621','Luiz Gama','Assistente Administrativo','ADM','M','Preto'),(9,'918650101','Iracema','Recepcionista (Staff)','ADM','F','Indígena'),(10,'918960414','Alfredo Volpi','Aux. Serviços Gerais (PCD)','ADM','M','Branco/PCD'),(11,'919140314','Carolina Maria de Jesus','Gerente de RH','RH','F','Preta'),(12,'918730720','Santos Dumont','Analista de DP','RH','M','Branco'),(13,'917920627','Maria Quitéria','Analista de Desenvolvimento','RH','F','Branca'),(14,'919140315','Abdias do Nascimento','Analista de Benefícios','RH','M','Preto'),(15,'917600211','Bárbara de Alencar','Coordenadora de TI','TI','F','Branca'),(16,'918390621','Machado de Assis','Arquiteto de Sistemas','TI','M','Pardo/PCD'),(17,'919130113','Enedina Alves Marques','Engenheira de Dados','TI','F','Preta'),(18,'918870305','Heitor Villa-Lobos','Desenvolvedor Sênior','TI','M','Branco'),(19,'919790727','Marielle Franco','Desenvolvedora Sênior','TI','F','Parda'),(20,'918510728','Manuel Querino','Analista de Segurança','TI','M','Preto'),(21,'919320215','Cacique Raoni','Analista de Infraestrutura','TI','M','Indígena'),(22,'918650102','Ceci','DevOps','TI','F','Indígena'),(23,'917070101','Antonil','Desenvolvedor Júnior','TI','M','Branco'),(24,'918471017','Chiquinha Gonzaga','Desenvolvedora Júnior','TI','F','Branca'),(25,'918810513','Lima Barreto','Analista de Suporte','TI','M','Pardo'),(26,'919340825','Zilda Arns','Analista de Suporte','TI','F','Branca/PCD'),(27,'916550101','Zumbi dos Palmares','Gerente de Operações','OPS','M','Preto'),(28,'918210830','Anita Garibaldi','Analista de Contratos','OPS','F','Branca'),(29,'917510101','Esperança Garcia','Analista de Serviços','OPS','F','Preta'),(30,'918490819','Joaquim Nabuco','Analista de Projetos','OPS','M','Branco'),(31,'919341130','Luiza Erundina','Supervisora de Atendimento','OPS','F','Parda'),(32,'918531009','José do Patrocínio','Supervisor de Atendimento','OPS','M','Preto'),(33,'919221026','Darcy Ribeiro','Técnico de Campo','OPS','M','Branco'),(34,'918650103','Dandara do Sertão','Técnica de Campo','OPS','F','Parda/PCD'),(35,'919420410','Juruna','Assistente de Operações','OPS','M','Indígena'),(36,'919100609','Pagu','Assistente de Operações','OPS','F','Branca'),(37,'918380113','André Rebouças','Gerente de Inovação','INOVAÇÃO','M','Preto'),(38,'918940802','Bertha Lutz','Cientista de Dados','INOVAÇÃO','F','Branca'),(39,'917000101','Tereza de Benguela','Analista de Transformação','INOVAÇÃO','F','Preta'),(40,'919310618','Fernando H. Cardoso','Analista de Projetos','INOVAÇÃO','M','Branco/PCD');
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

-- Dump completed on 2025-06-01 15:31:03
