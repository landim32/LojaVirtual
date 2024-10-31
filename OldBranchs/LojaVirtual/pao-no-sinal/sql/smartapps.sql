-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: www.imobsync.com.br    Database: smartapp
-- ------------------------------------------------------
-- Server version	5.7.21-0ubuntu0.16.04.1

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
-- Table structure for table `bairro`
--

DROP TABLE IF EXISTS `bairro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bairro` (
  `id_bairro` int(11) NOT NULL AUTO_INCREMENT,
  `id_cidade` int(11) NOT NULL,
  `nome` varchar(60) NOT NULL,
  `valor_frete` double DEFAULT NULL,
  PRIMARY KEY (`id_bairro`),
  KEY `fk_bairro_cidade_idx` (`id_cidade`),
  CONSTRAINT `fk_bairro_cidade` FOREIGN KEY (`id_cidade`) REFERENCES `cidade` (`id_cidade`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bairro`
--

LOCK TABLES `bairro` WRITE;
/*!40000 ALTER TABLE `bairro` DISABLE KEYS */;
INSERT INTO `bairro` VALUES (1,1,'Centro',11.9),(2,2,'Toda Cidade',2);
/*!40000 ALTER TABLE `bairro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cidade`
--

DROP TABLE IF EXISTS `cidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cidade` (
  `id_cidade` int(11) NOT NULL AUTO_INCREMENT,
  `uf` char(2) NOT NULL,
  `nome` varchar(60) NOT NULL,
  PRIMARY KEY (`id_cidade`),
  KEY `fk_cidade_uf_idx` (`uf`),
  CONSTRAINT `fk_cidade_uf` FOREIGN KEY (`uf`) REFERENCES `uf` (`uf`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cidade`
--

LOCK TABLES `cidade` WRITE;
/*!40000 ALTER TABLE `cidade` DISABLE KEYS */;
INSERT INTO `cidade` VALUES (1,'ES','Vila Velha'),(2,'ES','Pedro Canario'),(3,'ES','SÃO MATEUS');
/*!40000 ALTER TABLE `cidade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupo`
--

DROP TABLE IF EXISTS `grupo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grupo` (
  `id_grupo` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) NOT NULL,
  PRIMARY KEY (`id_grupo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupo`
--

LOCK TABLES `grupo` WRITE;
/*!40000 ALTER TABLE `grupo` DISABLE KEYS */;
INSERT INTO `grupo` VALUES (1,'Administrador');
/*!40000 ALTER TABLE `grupo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupo_permissao`
--

DROP TABLE IF EXISTS `grupo_permissao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grupo_permissao` (
  `id_grupo` int(11) NOT NULL,
  `slug` varchar(60) NOT NULL,
  PRIMARY KEY (`id_grupo`,`slug`),
  KEY `fk_grupo_permissao_permissao_idx` (`slug`),
  CONSTRAINT `fk_grupo_permissao_grupo` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id_grupo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_grupo_permissao_permissao` FOREIGN KEY (`slug`) REFERENCES `permissao` (`slug`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupo_permissao`
--

LOCK TABLES `grupo_permissao` WRITE;
/*!40000 ALTER TABLE `grupo_permissao` DISABLE KEYS */;
INSERT INTO `grupo_permissao` VALUES (1,'admin'),(1,'gerenciar-grupo'),(1,'gerenciar-permissoes');
/*!40000 ALTER TABLE `grupo_permissao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loja`
--

DROP TABLE IF EXISTS `loja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loja` (
  `id_loja` int(11) NOT NULL AUTO_INCREMENT,
  `cod_situacao` int(11) NOT NULL DEFAULT '1',
  `slug` varchar(60) NOT NULL,
  `nome` varchar(60) NOT NULL,
  `foto` varchar(64) DEFAULT NULL,
  `email` varchar(300) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `logradouro` varchar(60) DEFAULT NULL,
  `complemento` varchar(40) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `bairro` varchar(60) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `uf` char(2) DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `cod_gateway` varchar(15) DEFAULT NULL,
  `usa_retirar` tinyint(4) NOT NULL DEFAULT '0',
  `usa_entregar` tinyint(4) NOT NULL DEFAULT '0',
  `usa_gateway` tinyint(4) NOT NULL DEFAULT '0',
  `aceita_credito_online` tinyint(4) NOT NULL DEFAULT '0',
  `aceita_debito_online` tinyint(4) NOT NULL DEFAULT '0',
  `aceita_boleto` tinyint(4) NOT NULL DEFAULT '0',
  `aceita_dinheiro` tinyint(4) NOT NULL DEFAULT '0',
  `descricao` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_loja`),
  UNIQUE KEY `slug_UNIQUE` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loja`
--

LOCK TABLES `loja` WRITE;
/*!40000 ALTER TABLE `loja` DISABLE KEYS */;
INSERT INTO `loja` VALUES (1,1,'loja1','Loja Teste 1','','rodrigo@emagine.com.br',NULL,NULL,NULL,NULL,NULL,NULL,NULL,-18.2977,-39.957,NULL,0,0,0,0,0,0,0,'Aqui vai o endereço completo da loja'),(4,1,'loja2','Loja Teste 2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,-18.722,-39.847,NULL,0,0,0,0,0,0,0,'Aqui vai o endereço completo da loja');
/*!40000 ALTER TABLE `loja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loja_opcao`
--

DROP TABLE IF EXISTS `loja_opcao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loja_opcao` (
  `id_loja` int(11) NOT NULL,
  `chave` varchar(20) NOT NULL,
  `valor` varchar(80) NOT NULL,
  PRIMARY KEY (`id_loja`,`chave`),
  CONSTRAINT `fk_loja_loja_opcao` FOREIGN KEY (`id_loja`) REFERENCES `loja` (`id_loja`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loja_opcao`
--

LOCK TABLES `loja_opcao` WRITE;
/*!40000 ALTER TABLE `loja_opcao` DISABLE KEYS */;
/*!40000 ALTER TABLE `loja_opcao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loja_usuario`
--

DROP TABLE IF EXISTS `loja_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loja_usuario` (
  `id_loja` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_loja`,`id_usuario`),
  KEY `fk_loja_usuario_usuario_idx` (`id_usuario`),
  CONSTRAINT `fk_loja_usuario` FOREIGN KEY (`id_loja`) REFERENCES `loja` (`id_loja`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_loja_usuario_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loja_usuario`
--

LOCK TABLES `loja_usuario` WRITE;
/*!40000 ALTER TABLE `loja_usuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `loja_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pagamento`
--

DROP TABLE IF EXISTS `pagamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pagamento` (
  `id_pagamento` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `data_inclusao` datetime NOT NULL,
  `ultima_alteracao` datetime NOT NULL,
  `data_vencimento` datetime NOT NULL,
  `data_pagamento` datetime DEFAULT NULL,
  `valor_desconto` double NOT NULL DEFAULT '0',
  `valor_juro` double NOT NULL DEFAULT '0',
  `valor_multa` double NOT NULL DEFAULT '0',
  `troco_para` double NOT NULL DEFAULT '0',
  `cod_situacao` tinyint(4) NOT NULL DEFAULT '1',
  `observacao` varchar(300) DEFAULT NULL,
  `cielo_id` varchar(36) DEFAULT NULL,
  `cielo_status` int(11) DEFAULT NULL,
  `cielo_mensagem` varchar(300) DEFAULT NULL,
  `cod_tipo` int(11) DEFAULT NULL,
  `cpf` varchar(15) DEFAULT NULL,
  `logradouro` varchar(60) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `bairro` varchar(60) DEFAULT NULL,
  `cidade` varchar(60) DEFAULT NULL,
  `uf` char(2) DEFAULT NULL,
  `boleto_url` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_pagamento`),
  KEY `fk_pagamento_usuario_idx` (`id_usuario`),
  CONSTRAINT `fk_pagamento_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagamento`
--

LOCK TABLES `pagamento` WRITE;
/*!40000 ALTER TABLE `pagamento` DISABLE KEYS */;
INSERT INTO `pagamento` VALUES (87,1,'2018-04-04 14:13:49','2018-04-04 14:13:53','0001-01-01 00:00:00','2018-04-04 11:13:53',0,0,0,0,2,NULL,NULL,NULL,'Operação realizada com sucesso.',1,'89639766100','Quadra SQN 411 Bloco L','355','70866120','Asa Norte','Brasília','DF',NULL);
/*!40000 ALTER TABLE `pagamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pagamento_cartao`
--

DROP TABLE IF EXISTS `pagamento_cartao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pagamento_cartao` (
  `id_cartao` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `bandeira` tinyint(4) NOT NULL,
  `nome` varchar(20) NOT NULL,
  `token` varchar(64) NOT NULL,
  `cvv` varchar(5) NOT NULL,
  PRIMARY KEY (`id_cartao`),
  KEY `fk_pagamento_cartao_usuario_idx` (`id_usuario`),
  CONSTRAINT `fk_pagamento_cartao_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagamento_cartao`
--

LOCK TABLES `pagamento_cartao` WRITE;
/*!40000 ALTER TABLE `pagamento_cartao` DISABLE KEYS */;
INSERT INTO `pagamento_cartao` VALUES (7,1,1,'0001','f15641ae-38fd-4603-9337-0b291c2c5f8a','123');
/*!40000 ALTER TABLE `pagamento_cartao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pagamento_item`
--

DROP TABLE IF EXISTS `pagamento_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pagamento_item` (
  `id_item` int(11) NOT NULL AUTO_INCREMENT,
  `id_pagamento` int(11) NOT NULL,
  `descricao` varchar(150) NOT NULL,
  `valor` double NOT NULL,
  `quantidade` int(11) NOT NULL,
  PRIMARY KEY (`id_item`),
  KEY `fk_pagamento_item_idx` (`id_pagamento`),
  CONSTRAINT `fk_pagamento_item` FOREIGN KEY (`id_pagamento`) REFERENCES `pagamento` (`id_pagamento`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=154 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagamento_item`
--

LOCK TABLES `pagamento_item` WRITE;
/*!40000 ALTER TABLE `pagamento_item` DISABLE KEYS */;
INSERT INTO `pagamento_item` VALUES (152,87,'Arroz Tio João',13,4),(153,87,'Macarão Dona Benta',2,4);
/*!40000 ALTER TABLE `pagamento_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pagamento_opcao`
--

DROP TABLE IF EXISTS `pagamento_opcao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pagamento_opcao` (
  `id_pagamento` int(11) NOT NULL,
  `chave` varchar(50) NOT NULL,
  `valor` varchar(150) NOT NULL,
  PRIMARY KEY (`id_pagamento`,`chave`),
  CONSTRAINT `fk_pagamento_opcao_pagamento` FOREIGN KEY (`id_pagamento`) REFERENCES `pagamento` (`id_pagamento`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagamento_opcao`
--

LOCK TABLES `pagamento_opcao` WRITE;
/*!40000 ALTER TABLE `pagamento_opcao` DISABLE KEYS */;
INSERT INTO `pagamento_opcao` VALUES (87,'CIELO_ID','2f1d6cf2-8a2a-4080-917e-b893bc4bf8fa'),(87,'CIELO_RETURN_CODE','4');
/*!40000 ALTER TABLE `pagamento_opcao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pais`
--

DROP TABLE IF EXISTS `pais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pais` (
  `id_pais` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) NOT NULL,
  PRIMARY KEY (`id_pais`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pais`
--

LOCK TABLES `pais` WRITE;
/*!40000 ALTER TABLE `pais` DISABLE KEYS */;
INSERT INTO `pais` VALUES (1,'Brasil');
/*!40000 ALTER TABLE `pais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedido`
--

DROP TABLE IF EXISTS `pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pedido` (
  `id_pedido` int(11) NOT NULL AUTO_INCREMENT,
  `id_loja` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_pagamento` int(11) DEFAULT NULL,
  `data_inclusao` datetime NOT NULL,
  `ultima_alteracao` datetime NOT NULL,
  `valor_frete` double DEFAULT NULL,
  `troco_para` double DEFAULT NULL,
  `cod_entrega` tinyint(4) NOT NULL DEFAULT '1',
  `cod_pagamento` int(11) NOT NULL DEFAULT '1',
  `cod_situacao` int(11) NOT NULL DEFAULT '1',
  `cep` varchar(10) DEFAULT NULL,
  `logradouro` varchar(60) NOT NULL,
  `complemento` varchar(60) DEFAULT NULL,
  `numero` varchar(10) NOT NULL,
  `bairro` varchar(60) NOT NULL,
  `cidade` varchar(60) NOT NULL,
  `uf` char(2) NOT NULL,
  `observacao` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id_pedido`),
  KEY `fk_pedido_usuario_idx` (`id_usuario`),
  KEY `fk_pedido_loja_idx` (`id_loja`),
  CONSTRAINT `fk_pedido_loja` FOREIGN KEY (`id_loja`) REFERENCES `loja` (`id_loja`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_pedido_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido`
--

LOCK TABLES `pedido` WRITE;
/*!40000 ALTER TABLE `pedido` DISABLE KEYS */;
INSERT INTO `pedido` VALUES (37,1,1,87,'2018-04-04 14:13:54','2018-04-04 14:13:54',0,0,1,2,6,'70866120','Quadra SQN 411 Bloco L','','355','Asa Norte','Brasília','DF',NULL);
/*!40000 ALTER TABLE `pedido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedido_item`
--

DROP TABLE IF EXISTS `pedido_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pedido_item` (
  `id_pedido` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_pedido`,`id_produto`),
  KEY `fk_pedido_produto_idx` (`id_produto`),
  CONSTRAINT `fk_pedido_item` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_pedido_produto` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido_item`
--

LOCK TABLES `pedido_item` WRITE;
/*!40000 ALTER TABLE `pedido_item` DISABLE KEYS */;
INSERT INTO `pedido_item` VALUES (37,1,4),(37,4,4);
/*!40000 ALTER TABLE `pedido_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissao`
--

DROP TABLE IF EXISTS `permissao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissao` (
  `slug` varchar(60) NOT NULL,
  `nome` varchar(120) NOT NULL,
  PRIMARY KEY (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissao`
--

LOCK TABLES `permissao` WRITE;
/*!40000 ALTER TABLE `permissao` DISABLE KEYS */;
INSERT INTO `permissao` VALUES ('admin','Administrador'),('gerenciar-grupo','Gerenciar Grupos'),('gerenciar-permissoes','Gerenciar Permissões');
/*!40000 ALTER TABLE `permissao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produto`
--

DROP TABLE IF EXISTS `produto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produto` (
  `id_produto` int(11) NOT NULL AUTO_INCREMENT,
  `id_loja` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `destaque` tinyint(1) NOT NULL DEFAULT '0',
  `valor` double DEFAULT NULL,
  `valor_promocao` double DEFAULT NULL,
  `peso` double DEFAULT NULL,
  `quantidade` int(11) NOT NULL DEFAULT '0',
  `cod_situacao` int(11) NOT NULL DEFAULT '1',
  `quantidade_vendido` int(11) NOT NULL DEFAULT '0',
  `codigo` varchar(20) DEFAULT NULL,
  `slug` varchar(60) NOT NULL,
  `nome` varchar(60) NOT NULL,
  `foto` varchar(60) DEFAULT NULL,
  `descricao` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`id_produto`),
  KEY `fk_categoria_idx` (`id_categoria`),
  KEY `fk_usuario_idx` (`id_usuario`),
  KEY `fk_loja_idx` (`id_loja`),
  CONSTRAINT `fk_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `produto_categoria` (`id_categoria`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_loja` FOREIGN KEY (`id_loja`) REFERENCES `loja` (`id_loja`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produto`
--

LOCK TABLES `produto` WRITE;
/*!40000 ALTER TABLE `produto` DISABLE KEYS */;
INSERT INTO `produto` VALUES (1,1,1,3,1,13,0,5,10,1,0,'','arroz-tio-joao','Arroz Tio João','3c7636e6ccb6350c.png','1kg'),(2,1,1,3,1,12,9.9,1,10,1,0,'','batata-bem-batata-crincke-105kg','Batata Bem','372645bc8d6a1d49.jpg','Batata Crincke 1.05KG'),(3,1,1,6,1,21.3,19.9,0,10,1,0,'','vinho-toro-centenario-malbec-750ml','VINHO TORO','176761db9f803294.jpg','CENTENÁRIO MALBEC 750ML'),(4,1,1,2,1,2.5,2,1,100,1,0,'','mac-dona-benta-espaquete','Macarão Dona Benta','560bb38b4c309772.png','Espaquete'),(5,4,1,9,1,2.5,1.5,1,150,1,0,'','macarao--dona-benta-espaguete','Macarão Dona Benta','60e8e34e182b31f6.png','Espaquete');
/*!40000 ALTER TABLE `produto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produto_categoria`
--

DROP TABLE IF EXISTS `produto_categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produto_categoria` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `id_loja` int(11) NOT NULL,
  `id_pai` int(11) DEFAULT NULL,
  `nome` varchar(80) NOT NULL,
  `nome_completo` varchar(260) NOT NULL,
  `slug` varchar(260) NOT NULL,
  `foto` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id_categoria`),
  KEY `fk_pai_idx` (`id_pai`),
  KEY `fk_loja_idx` (`id_loja`),
  CONSTRAINT `fk_categoria_loja` FOREIGN KEY (`id_loja`) REFERENCES `loja` (`id_loja`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_pai` FOREIGN KEY (`id_pai`) REFERENCES `produto_categoria` (`id_categoria`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produto_categoria`
--

LOCK TABLES `produto_categoria` WRITE;
/*!40000 ALTER TABLE `produto_categoria` DISABLE KEYS */;
INSERT INTO `produto_categoria` VALUES (2,1,NULL,'Alimentos','/Alimentos','alimentos','614a7909a1799abf.JPG'),(3,1,2,'Arroz','/Alimentos/Arroz','alimentos-arroz',NULL),(4,1,2,'Feijão','/Alimentos/Feijão','alimentos-feijao',NULL),(6,1,7,'Vinhos','/Bebidas/Vinhos','bebidas-vinhos',NULL),(7,1,NULL,'Bebidas','/Bebidas','bebidas',NULL),(9,4,NULL,'Alimentos','/Alimentos','alimentos',NULL);
/*!40000 ALTER TABLE `produto_categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uf`
--

DROP TABLE IF EXISTS `uf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uf` (
  `uf` char(2) NOT NULL,
  `id_pais` int(11) NOT NULL,
  `nome` varchar(60) NOT NULL,
  PRIMARY KEY (`uf`),
  KEY `fk_uf_pais_idx` (`id_pais`),
  CONSTRAINT `fk_uf_pais` FOREIGN KEY (`id_pais`) REFERENCES `pais` (`id_pais`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uf`
--

LOCK TABLES `uf` WRITE;
/*!40000 ALTER TABLE `uf` DISABLE KEYS */;
INSERT INTO `uf` VALUES ('BA',1,'BAHIA'),('ES',1,'ESPIRITO SANTO');
/*!40000 ALTER TABLE `uf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `cod_tipo` int(11) DEFAULT NULL,
  `data_inclusao` datetime NOT NULL,
  `ultima_alteracao` datetime NOT NULL,
  `ultimo_login` datetime DEFAULT NULL,
  `email` varchar(160) NOT NULL,
  `slug` varchar(60) NOT NULL,
  `nome` varchar(60) NOT NULL,
  `senha` varchar(30) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `foto` mediumtext,
  `numero` varchar(10) DEFAULT NULL,
  `cod_situacao` int(11) NOT NULL DEFAULT '1',
  `cpf_cnpj` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `slug_UNIQUE` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,NULL,'2017-07-15 21:57:34','2018-03-31 13:49:03',NULL,'rodrigo@emagine.com.br','rodrigo','Rodrigo Landim','pikpro6','6212344545','09d6146a47e60f5c74ec15041da069d2.png','23',1,'89639766100'),(2,NULL,'2018-04-02 21:12:34','2018-04-02 21:16:07','2018-04-02 21:12:34','janayna.viasoft@gmail.com','janayna-rodrigues','Janayna Rodrigues ','caio9630','81973051546',NULL,NULL,1,'11368718450'),(3,NULL,'2018-04-02 21:15:08','2018-04-02 21:15:08','2018-04-02 21:15:08','janayna.viasoft@gmail.com','janayna-rodrigues-2','Janayna Rodrigues ','caio9630','81973051546',NULL,NULL,2,'11368718450');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_endereco`
--

DROP TABLE IF EXISTS `usuario_endereco`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_endereco` (
  `id_endereco` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `logradouro` varchar(60) DEFAULT NULL,
  `complemento` varchar(40) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `uf` char(2) DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  PRIMARY KEY (`id_endereco`),
  KEY `fk_usuario_endereco_usuario_idx` (`id_usuario`),
  CONSTRAINT `fk_usuario_endereco_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_endereco`
--

LOCK TABLES `usuario_endereco` WRITE;
/*!40000 ALTER TABLE `usuario_endereco` DISABLE KEYS */;
INSERT INTO `usuario_endereco` VALUES (24,1,'70866120','Quadra SQN 411 Bloco L','','355','Asa Norte','Brasília','DF',-15.7528,-47.8817),(26,3,'55024070','Rua Valdomiro Silveira','Escritório ','136','Indianópolis','Caruaru','PE',-8.28816,-35.9601),(27,3,'55024070','Rua Valdomiro Silveira','Casa','136','Indianópolis','Caruaru','PE',-8.28816,-35.9601),(28,2,'55024070','Rua Valdomiro Silveira','Escritório ','136','Indianópolis','Caruaru','PE',-8.28816,-35.9601);
/*!40000 ALTER TABLE `usuario_endereco` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_grupo`
--

DROP TABLE IF EXISTS `usuario_grupo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_grupo` (
  `id_usuario` int(11) NOT NULL,
  `id_grupo` int(11) NOT NULL,
  PRIMARY KEY (`id_usuario`,`id_grupo`),
  KEY `fk_usuario_grupo_grupo_idx` (`id_grupo`),
  CONSTRAINT `fk_usuario_grupo_grupo` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id_grupo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_grupo_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_grupo`
--

LOCK TABLES `usuario_grupo` WRITE;
/*!40000 ALTER TABLE `usuario_grupo` DISABLE KEYS */;
INSERT INTO `usuario_grupo` VALUES (1,1);
/*!40000 ALTER TABLE `usuario_grupo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_preferencia`
--

DROP TABLE IF EXISTS `usuario_preferencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_preferencia` (
  `id_usuario` int(11) NOT NULL,
  `chave` varchar(30) NOT NULL,
  `valor` varchar(300) NOT NULL,
  PRIMARY KEY (`id_usuario`,`chave`),
  CONSTRAINT `fk_usuario_preferencia_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_preferencia`
--

LOCK TABLES `usuario_preferencia` WRITE;
/*!40000 ALTER TABLE `usuario_preferencia` DISABLE KEYS */;
INSERT INTO `usuario_preferencia` VALUES (2,'TOKEN_VALIDACAO','4a2a578b3a48bd1a4e3d110bd097b79e'),(3,'TOKEN_VALIDACAO','9d4b735e4997c5148d60eb978051f0db');
/*!40000 ALTER TABLE `usuario_preferencia` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-04-04 13:04:42
