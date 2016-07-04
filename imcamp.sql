-- MySQL dump 10.13  Distrib 5.5.49, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: tommy
-- ------------------------------------------------------
-- Server version	5.5.49-0ubuntu0.14.04.1

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
-- Table structure for table `buyorder`
--



DROP TABLE IF EXISTS `buyorder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `buyorder` (
  `TeamNUM` int(11) NOT NULL,
  `Product1` int(11) NOT NULL DEFAULT '0',
  `Product2` int(11) NOT NULL DEFAULT '0',
  `Product3` int(11) NOT NULL DEFAULT '0',
  `Product4` int(11) NOT NULL DEFAULT '0',
  `Product5` int(11) NOT NULL DEFAULT '0',
  `Product6` int(11) NOT NULL DEFAULT '0',
  `Product7` int(11) NOT NULL DEFAULT '0',
  `Product8` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`TeamNUM`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buyorder`
--

LOCK TABLES `buyorder` WRITE;
/*!40000 ALTER TABLE `buyorder` DISABLE KEYS */;
INSERT INTO `buyorder` VALUES (0,0,0,0,0,0,0,0,0),(1,0,0,0,0,0,0,0,0),(2,0,0,0,0,0,0,0,0),(3,0,0,0,0,0,0,0,0),(4,0,0,0,0,0,0,0,0),(5,0,0,0,0,0,0,0,0),(6,0,0,0,0,0,0,0,0),(7,0,0,0,0,0,0,0,0),(8,0,0,0,0,0,0,0,0),(9,0,0,0,0,0,0,0,0),(10,0,0,0,0,0,0,0,0),(11,100,0,0,0,0,0,0,0);
/*!40000 ALTER TABLE `buyorder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `buyprice`
--

DROP TABLE IF EXISTS `buyprice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `buyprice` (
  `ProductName` varchar(255) NOT NULL,
  `Price1` int(11) NOT NULL,
  `Price2` int(11) NOT NULL,
  `Price3` int(11) NOT NULL,
  `Price4` int(11) NOT NULL,
  `Price5` int(11) NOT NULL,
  `ProductNUM` int(11) DEFAULT NULL,
  `Last` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buyprice`
--

LOCK TABLES `buyprice` WRITE;
/*!40000 ALTER TABLE `buyprice` DISABLE KEYS */;
INSERT INTO `buyprice` VALUES ('肥貓CPU',10,14,20,30,45,1,1),('燒壞的主機板',9,19,28,30,32,2,1),('藍色的當機螢幕',7,16,34,40,45,3,1),('興趣使然的肥宅',10,15,25,40,56,4,1),('尊爵不凡的顯卡',50,40,35,38,45,5,1),('靠北工程師的肝',22,50,70,48,22,6,1),('惱人的鍵盤',8,15,30,25,18,7,1),('左手用的滑鼠',2,5,7,12,19,8,1);
/*!40000 ALTER TABLE `buyprice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produceorder`
--

DROP TABLE IF EXISTS `produceorder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produceorder` (
  `TeamNUM` int(11) NOT NULL,
  `Producing1` int(11) NOT NULL DEFAULT '0',
  `Producing2` int(11) NOT NULL DEFAULT '0',
  `Producing3` int(11) NOT NULL DEFAULT '0',
  `Producing4` int(11) NOT NULL DEFAULT '0',
  `Producing5` int(11) NOT NULL DEFAULT '0',
  `Producing6` int(11) NOT NULL DEFAULT '0',
  `Producing7` int(11) NOT NULL DEFAULT '0',
  `Producing8` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produceorder`
--

LOCK TABLES `produceorder` WRITE;
/*!40000 ALTER TABLE `produceorder` DISABLE KEYS */;
INSERT INTO `produceorder` VALUES (0,0,0,0,0,0,0,0,0),(1,0,0,0,0,0,0,0,0),(2,0,0,0,0,0,0,0,0),(3,0,0,0,0,0,0,0,0),(4,0,0,0,0,0,0,0,0),(5,0,0,0,0,0,0,0,0),(6,0,0,0,0,0,0,0,0),(7,0,0,0,0,0,0,0,0),(8,0,0,0,0,0,0,0,0),(9,0,0,0,0,0,0,0,0),(10,0,0,0,0,0,0,0,0),(11,0,0,0,0,0,0,0,0);
/*!40000 ALTER TABLE `produceorder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producing`
--

DROP TABLE IF EXISTS `producing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `producing` (
  `GoodsName` varchar(255) NOT NULL,
  `Product1Needed` int(11) NOT NULL,
  `Product2Needed` int(11) NOT NULL,
  `Product3Needed` int(11) NOT NULL,
  `Product4Needed` int(11) NOT NULL,
  `Product5Needed` int(11) NOT NULL,
  `Product6Needed` int(11) NOT NULL,
  `Product7Needed` int(11) NOT NULL,
  `Product8Needed` int(11) NOT NULL,
  `GoodsNUM` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producing`
--

LOCK TABLES `producing` WRITE;
/*!40000 ALTER TABLE `producing` DISABLE KEYS */;
INSERT INTO `producing` VALUES ('VR裝備',1,0,1,0,1,0,0,0,1),('平板電腦',1,1,2,0,0,0,0,0,2),('穿戴式裝置',1,1,0,0,0,1,0,0,3),('智慧型手機',1,0,1,0,1,1,0,0,4),('雲端服務平台',0,0,0,1,0,1,1,1,5),('社群交友配對網站',0,0,0,2,0,1,1,1,6),('遊戲人生PC',0,1,0,1,2,0,1,1,7),('管理人才NB',1,1,0,0,0,0,0,0,8);
/*!40000 ALTER TABLE `producing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sellorder`
--

DROP TABLE IF EXISTS `sellorder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sellorder` (
  `TeamNUM` int(11) NOT NULL,
  `Goods1` int(11) NOT NULL DEFAULT '0',
  `Goods2` int(11) NOT NULL DEFAULT '0',
  `Goods3` int(11) NOT NULL DEFAULT '0',
  `Goods4` int(11) NOT NULL DEFAULT '0',
  `Goods5` int(11) NOT NULL DEFAULT '0',
  `Goods6` int(11) NOT NULL DEFAULT '0',
  `Goods7` int(11) NOT NULL DEFAULT '0',
  `Goods8` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`TeamNUM`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sellorder`
--

LOCK TABLES `sellorder` WRITE;
/*!40000 ALTER TABLE `sellorder` DISABLE KEYS */;
INSERT INTO `sellorder` VALUES (0,0,0,0,0,0,0,0,0),(1,0,0,0,0,0,0,0,0),(2,0,0,0,0,0,0,0,0),(3,0,0,0,0,0,0,0,0),(4,0,0,0,0,0,0,0,0),(5,0,0,0,0,0,0,0,0),(6,0,0,0,0,0,0,0,0),(7,0,0,0,0,0,0,0,0),(8,0,0,0,0,0,0,0,0),(9,0,0,0,0,0,0,0,0),(10,0,0,0,0,0,0,0,0),(11,0,0,0,0,0,0,0,0);
/*!40000 ALTER TABLE `sellorder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sellprice`
--

DROP TABLE IF EXISTS `sellprice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sellprice` (
  `GoodsName` varchar(255) NOT NULL,
  `Price1` int(11) NOT NULL,
  `Price2` int(11) NOT NULL,
  `Price3` int(11) NOT NULL,
  `Price4` int(11) NOT NULL,
  `Price5` int(11) NOT NULL,
  `GoodsNUM` int(11) DEFAULT NULL,
  `Last` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sellprice`
--

LOCK TABLES `sellprice` WRITE;
/*!40000 ALTER TABLE `sellprice` DISABLE KEYS */;
INSERT INTO `sellprice` VALUES ('VR裝備',85,120,155,200,185,1,1),('平板電腦',180,170,150,120,100,2,1),('穿戴式裝置',140,120,90,75,55,3,1),('智慧型手機',220,210,195,150,110,4,1),('雲端服務平台',50,60,80,90,105,5,1),('社群交友配對網站',90,140,205,225,190,6,1),('遊戲人生PC',100,150,270,250,200,7,1),('管理人才NB',100,95,90,80,65,8,1);
/*!40000 ALTER TABLE `sellprice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team`
--

DROP TABLE IF EXISTS `team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team` (
  `TeamNUM` int(11) NOT NULL,
  `TeamName` varchar(255) NOT NULL,
  `TeamAccount` varchar(255) NOT NULL,
  `TeamPassword` varchar(255) NOT NULL,
  `MoneyCount` int(11) NOT NULL,
  `Productivity` int(11) NOT NULL,
  `BuyBUFF` int(11) NOT NULL,
  `SellBUFF` int(11) NOT NULL,
  `Admin` tinyint(1) NOT NULL,
  PRIMARY KEY (`TeamNUM`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team`
--

LOCK TABLES `team` WRITE;
/*!40000 ALTER TABLE `team` DISABLE KEYS */;
INSERT INTO `team` VALUES (0,'NTUIM','Admin','$2y$10$UHjcIkYxCy8GkZKUH4vWmOtrTevUWpwurXLcrD7zU.c8pezjNg3D2',0,0,0,0,1),(1,'é¼Žæ¬£é›†åœ˜','ntuim1','$2y$10$8tadFNQcqZerGlwJV1HyuuNaypHVXphHPbz6cF885d6ZlGzVTxSKK',10000,50,0,0,0),(2,'Skymizer','ntuim2','$2y$10$23IiJZmq.7tqE/lo7FQ4qO5bFY31RRlcq2GIdpFeXlQKpnrIlIKbu',10000,50,0,0,0),(3,'æ˜Ÿå¥é»ž','ntuim3','$2y$10$jl5/x7HC5PCYBcmpnY0zEOfalK0ZpzI/FNF4p3je5ZcWmJTB7EG3m',10000,50,0,0,0),(4,'KellyStar','ntuim4','$2y$10$CJge/E8VSUZwpgsXi7qRE.7T1BHn5kYpamtuaDjxVtFX5slu7iyES',10000,50,0,0,0),(5,'å®é”é›»çˆ†ä½ ','ntuim5','$2y$10$n5PxFf4IiTms8Kl5VB8pNusNQRu5Z3teg5EHy96c3c0Twyw98tNYG',10000,50,0,0,0),(6,'Airc&c','ntuim6','$2y$10$NM2H5YYAmzaYk0xlgKA1LOeT4WT2R3h9DqyEdPQZBE8yXJ464G6GG',10000,50,0,0,0),(7,'æ˜Ÿå…‰å±±å²³','ntuim7','$2y$10$WozhDnT8C9aRXUCxzHkyle2AAr.8dWUG3ReSb2IQSGyJKKC1QYKCS',10000,50,0,0,0),(8,'å¸å¡é›†åœ˜','ntuim8','$2y$10$8MOcshu3PolhxPqI6vVHn.d2ZwYklgo9js7Qbxn1RJHA7AJ5WI2ta',10000,50,0,0,0),(9,'æ…•è°·æ…•é­š','ntuim9','$2y$10$eWidIspCJBVBLA8xtdJ0YuvruoBqAs7XmJ1PGUHHXDP./grvTwnBa',10000,50,0,0,0),(10,'Rsus','ntuim10','$2y$10$yEMoFMEa2H.5kKl3s.SPJOwyr9MQrLb.ujXt2KpEBDPJwOFiyXFwO',10000,50,0,0,0),(11,'test','test','$2y$10$7GCljZLwYcfo4RJEUfI/NuH7I9TmdBQSo4BzwShgQTi9ojhp2yDL6',776241,50000,0,0,0);
/*!40000 ALTER TABLE `team` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teambuy`
--

DROP TABLE IF EXISTS `teambuy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teambuy` (
  `TeamNUM` int(11) NOT NULL,
  `Product1` int(11) NOT NULL DEFAULT '0',
  `Product2` int(11) NOT NULL DEFAULT '0',
  `Product3` int(11) NOT NULL DEFAULT '0',
  `Product4` int(11) NOT NULL DEFAULT '0',
  `Product5` int(11) NOT NULL DEFAULT '0',
  `Product6` int(11) DEFAULT '0',
  `Product7` int(11) NOT NULL DEFAULT '0',
  `Product8` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`TeamNUM`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teambuy`
--

LOCK TABLES `teambuy` WRITE;
/*!40000 ALTER TABLE `teambuy` DISABLE KEYS */;
INSERT INTO `teambuy` VALUES (0,0,0,0,0,0,0,0,0),(1,0,0,0,0,0,0,0,0),(2,0,0,0,0,0,0,0,0),(3,0,0,0,0,0,0,0,0),(4,0,0,0,0,0,0,0,0),(5,0,0,0,0,0,0,0,0),(6,0,0,0,0,0,0,0,0),(7,0,0,0,0,0,0,0,0),(8,0,0,0,0,0,0,0,0),(9,0,0,0,0,0,0,0,0),(10,0,0,0,0,0,0,0,0),(11,2162421,2919,11916,2944,2080,4131,3097,6783);
/*!40000 ALTER TABLE `teambuy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teamsell`
--

DROP TABLE IF EXISTS `teamsell`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teamsell` (
  `TeamNUM` int(11) NOT NULL,
  `Goods1` int(11) NOT NULL DEFAULT '0',
  `Goods2` int(11) NOT NULL DEFAULT '0',
  `Goods3` int(11) NOT NULL DEFAULT '0',
  `Goods4` int(11) NOT NULL DEFAULT '0',
  `Goods5` int(11) NOT NULL DEFAULT '0',
  `Goods6` int(11) NOT NULL DEFAULT '0',
  `Goods7` int(11) NOT NULL DEFAULT '0',
  `Goods8` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`TeamNUM`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teamsell`
--

LOCK TABLES `teamsell` WRITE;
/*!40000 ALTER TABLE `teamsell` DISABLE KEYS */;
INSERT INTO `teamsell` VALUES (0,0,0,0,0,0,0,0,0),(1,0,0,0,0,0,0,0,0),(2,0,0,0,0,0,0,0,0),(3,0,0,0,0,0,0,0,0),(4,0,0,0,0,0,0,0,0),(5,0,0,0,0,0,0,0,0),(6,0,0,0,0,0,0,0,0),(7,0,0,0,0,0,0,0,0),(8,0,0,0,0,0,0,0,0),(9,0,0,0,0,0,0,0,0),(10,0,0,0,0,0,0,0,0),(11,0,0,0,0,25,3,700,329);
/*!40000 ALTER TABLE `teamsell` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-07-04  9:56:36
