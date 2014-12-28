-- MySQL dump 10.13  Distrib 5.5.37, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: test
-- ------------------------------------------------------
-- Server version	5.5.37-0ubuntu0.13.10.1-log

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
-- Table structure for table `Filtro`
--

DROP TABLE IF EXISTS `Filtro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Filtro` (
  `idFiltro` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idFiltro`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Filtro`
--

LOCK TABLES `Filtro` WRITE;
/*!40000 ALTER TABLE `Filtro` DISABLE KEYS */;
INSERT INTO `Filtro` VALUES (1,'UM'),(2,'DOIS'),(3,'TRES'),(4,'QUATRO'),(5,'CINCO');
/*!40000 ALTER TABLE `Filtro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testTable`
--

DROP TABLE IF EXISTS `testTable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `testTable` (
  `idtestTable` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) DEFAULT NULL,
  `valor` varchar(45) DEFAULT NULL,
  `filtro1` varchar(45) DEFAULT NULL,
  `filtro2` varchar(45) DEFAULT NULL,
  `boolfield` varchar(1) DEFAULT NULL,
  `intField` int(11) DEFAULT NULL,
  `moneyField` decimal(10,2) DEFAULT NULL,
  `dateField` date DEFAULT NULL,
  `datetimeField` datetime DEFAULT NULL,
  `precisionField` decimal(10,3) DEFAULT NULL,
  `largeField` text,
  `triggerVal` varchar(45) DEFAULT NULL,
  `defaultVal` varchar(45) DEFAULT 'padrao',
  PRIMARY KEY (`idtestTable`),
  UNIQUE KEY `FiltroUnico` (`moneyField`,`filtro1`,`filtro2`),
  UNIQUE KEY `campoUnico` (`nome`),
  KEY `FiltroPadrao` (`filtro1`),
  KEY `FiltroAvancado` (`filtro2`,`filtro1`)
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testTable`
--

LOCK TABLES `testTable` WRITE;
/*!40000 ALTER TABLE `testTable` DISABLE KEYS */;
INSERT INTO `testTable` VALUES (1,'linha 1 2','12','1','2','1',665,10.23,'2014-01-02','2012-12-08 12:30:00',3.000,'teste de campos text, renderizado em textArea',NULL,'padrao'),(2,'linha 1 1','11','1','1',NULL,333,NULL,NULL,NULL,NULL,'mais um teste de campos grandes oara ver como se comporta esse menino bandido que é o PDO.',NULL,'padrao'),(3,'linha 4 2','42','4','2','1',444,11.00,NULL,NULL,NULL,NULL,NULL,'padrao'),(4,'lnha 1 3','13','1','3',NULL,666,1.00,NULL,NULL,NULL,NULL,NULL,'padrao');
/*!40000 ALTER TABLE `testTable` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-12-11 23:38:45
