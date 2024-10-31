-- MySQL dump 10.16  Distrib 10.1.25-MariaDB, for osx10.6 (i386)
--
-- Host: localhost    Database: emagine_loja
-- ------------------------------------------------------
-- Server version	10.1.25-MariaDB

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bairro`
--

LOCK TABLES `bairro` WRITE;
/*!40000 ALTER TABLE `bairro` DISABLE KEYS */;
INSERT INTO `bairro` VALUES (1,1,'Centro',11.9);
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cidade`
--

LOCK TABLES `cidade` WRITE;
/*!40000 ALTER TABLE `cidade` DISABLE KEYS */;
INSERT INTO `cidade` VALUES (1,'ES','Vila Velha');
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
  `foto` varchar(64) DEFAULT NULL,
  `email` varchar(300) DEFAULT NULL,
  `nome` varchar(60) NOT NULL,
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
INSERT INTO `loja` VALUES (1,1,'emagine-marketplace','1eeb183edbd26b75.png','rodrigo@emagine.com.br','Emagine MarketPlace','Aqui vem o endereço completo do imóvel'),(4,1,'rio-grande',NULL,'rodrigo@emagine.com.br','Rio Grande','Aqui vem o endereço completo do imóvel');
/*!40000 ALTER TABLE `loja` ENABLE KEYS */;
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
INSERT INTO `loja_usuario` VALUES (1,1),(1,3);
/*!40000 ALTER TABLE `loja_usuario` ENABLE KEYS */;
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
  `valor_frete` double DEFAULT NULL,
  `troco_para` double DEFAULT NULL,
  `cod_pagamento` int(11) NOT NULL DEFAULT '1',
  `cod_situacao` int(11) NOT NULL DEFAULT '1',
  `cep` varchar(10) DEFAULT NULL,
  `logradouro` varchar(60) NOT NULL,
  `complemento` varchar(60) DEFAULT NULL,
  `numero` varchar(10) NOT NULL,
  `bairro` varchar(60) NOT NULL,
  `cidade` varchar(60) NOT NULL,
  `uf` char(2) NOT NULL,
  `observacao` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_pedido`),
  KEY `fk_pedido_usuario_idx` (`id_usuario`),
  KEY `fk_pedido_loja_idx` (`id_loja`),
  CONSTRAINT `fk_pedido_loja` FOREIGN KEY (`id_loja`) REFERENCES `loja` (`id_loja`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_pedido_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido`
--

LOCK TABLES `pedido` WRITE;
/*!40000 ALTER TABLE `pedido` DISABLE KEYS */;
INSERT INTO `pedido` VALUES (9,1,1,11.9,NULL,2,5,'','teste','','23','Centro','Vila Velha','ES',NULL),(13,1,1,11.9,NULL,1,3,'','teste','','23','Centro','Vila Velha','ES',NULL),(14,1,1,11.9,NULL,2,3,'','teste','','23','Centro','Vila Velha','ES',NULL),(15,1,1,11.9,NULL,1,1,'','teste','','23','Centro','Vila Velha','ES',NULL),(29,1,1,11.9,0,1,1,'','teste','','23','Centro','Vila Velha','ES',''),(30,1,1,11.9,0,1,1,'','teste','','23','Centro','Vila Velha','ES',''),(31,1,1,11.9,100,1,1,'','teste','','23','Centro','Vila Velha','ES',''),(32,1,1,11.9,50,1,1,'','teste','','23','Centro','Vila Velha','ES',''),(33,1,1,11.9,50,1,1,'','teste','','23','Centro','Vila Velha','ES','teste'),(34,1,1,11.9,0,1,1,'','teste','','23','Centro','Vila Velha','ES',''),(35,1,1,11.9,30,1,1,'','teste','','23','Centro','Vila Velha','ES',''),(36,1,1,11.9,30,1,1,'','teste','','23','Centro','Vila Velha','ES',''),(37,1,1,11.9,50,1,1,'','teste','','23','Centro','Vila Velha','ES','teste'),(38,1,1,11.9,100,1,1,'','teste','','23','Centro','Vila Velha','ES','teste'),(39,1,1,11.9,100,1,1,'','teste','','23','Centro','Vila Velha','ES','teste'),(40,1,1,11.9,100,1,1,'','teste','','23','Centro','Vila Velha','ES','teste');
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
INSERT INTO `pedido_item` VALUES (9,1,5),(9,2,6),(9,3,3),(13,1,1),(13,2,1),(13,3,1),(14,1,2),(14,3,5),(15,1,1),(15,2,6),(15,3,3),(29,2,4),(29,3,2),(30,2,4),(30,3,2),(31,1,2),(31,2,2),(31,3,2),(32,2,1),(32,3,1),(33,2,1),(33,3,1),(34,1,1),(34,2,1),(35,1,1),(35,2,1),(36,2,1),(36,3,1),(37,2,1),(37,3,1),(38,2,1),(38,3,1),(39,2,1),(39,3,2),(40,2,1),(40,3,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produto`
--

LOCK TABLES `produto` WRITE;
/*!40000 ALTER TABLE `produto` DISABLE KEYS */;
INSERT INTO `produto` VALUES (1,1,1,3,1,13,0,5,10,1,0,'','arroz-tio-joao','Arroz Tio João','48e4f96f40d6aad5.JPG',NULL),(2,1,1,3,1,12,9.9,1,10,1,0,'','batata-bem-batata-crincke-105kg','Batata Bem Batata Crincke 1.05KG','88ba662fabf605f2.jpg',NULL),(3,1,1,6,1,21.3,19.9,0,10,1,0,'','vinho-toro-centenario-malbec-750ml','VINHO TORO CENTENÁRIO MALBEC 750ML','90f520587a3aae7c.jpg',NULL);
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
  UNIQUE KEY `slug_UNIQUE` (`slug`),
  KEY `fk_pai_idx` (`id_pai`),
  KEY `fk_loja_idx` (`id_loja`),
  CONSTRAINT `fk_categoria_loja` FOREIGN KEY (`id_loja`) REFERENCES `loja` (`id_loja`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_pai` FOREIGN KEY (`id_pai`) REFERENCES `produto_categoria` (`id_categoria`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produto_categoria`
--

LOCK TABLES `produto_categoria` WRITE;
/*!40000 ALTER TABLE `produto_categoria` DISABLE KEYS */;
INSERT INTO `produto_categoria` VALUES (2,1,NULL,'Alimentos','/Alimentos','alimentos','614a7909a1799abf.JPG'),(3,1,2,'Arroz','/Alimentos/Arroz','alimentos-arroz',NULL),(4,1,2,'Feijão','/Alimentos/Feijão','alimentos-feijao',NULL),(6,1,7,'Vinhos','/Bebidas/Vinhos','bebidas-vinhos',NULL),(7,1,NULL,'Bebidas','/Bebidas','bebidas',NULL);
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
INSERT INTO `uf` VALUES ('ES',1,'Espirito Santo');
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
  `logradouro` varchar(60) DEFAULT NULL,
  `complemento` varchar(60) DEFAULT NULL,
  `foto` mediumtext,
  `numero` varchar(10) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `bairro` varchar(60) DEFAULT NULL,
  `cidade` varchar(60) DEFAULT NULL,
  `uf` char(2) DEFAULT NULL,
  `cod_situacao` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `slug_UNIQUE` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,NULL,'2017-07-15 21:57:34','2017-12-02 14:29:48',NULL,'rodrigo@emagine.com.br','rodrigo','Rodrigo Landim','pikpro6','6212344545','teste','','cc1f78e05dde738ddb19dee3c4227773.png','23','','Centro','Vila Velha','ES',1),(2,NULL,'2017-11-19 15:53:24','2017-11-19 15:53:24','2017-11-19 15:53:24','dfgdfg','dfdfgfdg','dfdfgfdg','1234','3424234','xxvxsdf','','data:image/png;base64,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','','','Centro','Vila Velha','ES',1),(3,NULL,'2017-12-01 15:29:05','2017-12-01 15:38:03','2017-12-01 15:29:05','marcos.thx1138@gmail.com','marcos-paulo','Marcos Paulo','pikpro6','','','','data:image/png;base64,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','','','','','',1);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
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
INSERT INTO `usuario_preferencia` VALUES (1,'TOKEN_VALIDACAO','34898fab3b67122f567cd77813465f09'),(3,'TOKEN_VALIDACAO','cb237bcaa90a2a9c721215240114049c');
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

-- Dump completed on 2017-12-05 17:58:53
