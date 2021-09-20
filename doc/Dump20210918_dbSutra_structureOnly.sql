-- MySQL dump 10.13  Distrib 8.0.15, for Win64 (x86_64)
--
-- Host: localhost    Database: dbsutra
-- ------------------------------------------------------
-- Server version	8.0.15

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `chapter`
--

DROP TABLE IF EXISTS `chapter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `chapter` (
  `chpid` int(11) NOT NULL AUTO_INCREMENT,
  `ssid` int(11) NOT NULL COMMENT '所属经集',
  `chpLevel` varchar(1) DEFAULT NULL,
  `prtid` int(11) DEFAULT '0',
  `chpCode` varchar(45) DEFAULT NULL COMMENT '章节代号',
  `chpName` varchar(45) DEFAULT NULL COMMENT '章节名称',
  `isNamespace` varchar(1) DEFAULT NULL COMMENT '是否列入命名空间，如篇/品等则不需要入命名空间，而相应则需要。\n0：不需要\n1：需要',
  `chpDesc` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`chpid`)
) ENGINE=InnoDB AUTO_INCREMENT=265 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `grp`
--

DROP TABLE IF EXISTS `grp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `grp` (
  `gpid` int(11) NOT NULL AUTO_INCREMENT,
  `ssCode` varchar(45) DEFAULT NULL COMMENT '所属经集代号',
  `gpType` varchar(45) DEFAULT NULL COMMENT '此分组的类型。如：相应部中可按篇/按卷/按九部经来分组，此篇/卷/九部也可以用在经集结构的子标签上。',
  `gpName` varchar(45) DEFAULT NULL COMMENT '分组名称。只用名称，不再使用gpCode',
  `gpDesc` varchar(90) DEFAULT NULL,
  PRIMARY KEY (`gpid`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mysutra`
--

DROP TABLE IF EXISTS `mysutra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `mysutra` (
  `msid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ssid` int(11) NOT NULL COMMENT '所属經集id',
  `msNamespaceCode` varchar(45) NOT NULL COMMENT '所属章节代号，如：SN.5.123中的5.123',
  `msNamespace` varchar(45) NOT NULL COMMENT '所属章节代号，如：相应部.五蕴相应.某某品.123中的五蕴相应.某某品',
  `msDisplayCode` varchar(45) DEFAULT NULL COMMENT '對外通用的Code,如 SN.1.1',
  `msDisplayName` varchar(45) DEFAULT NULL COMMENT '单行经名，可空，如《大念住经》《象经》',
  `msGroup` varchar(45) DEFAULT NULL COMMENT '其他分类，格式如：{犍度篇|卷四|修多罗}、{有偈篇||本生}',
  `msKeyWord` varchar(90) DEFAULT NULL COMMENT '关键字',
  `msPlace` varchar(45) DEFAULT NULL,
  `msPeople` varchar(45) DEFAULT NULL,
  `msText` mediumtext COMMENT '文章内容。支持html编辑',
  `msDatetime` timestamp(4) NULL DEFAULT NULL,
  PRIMARY KEY (`msid`)
) ENGINE=InnoDB AUTO_INCREMENT=704 DEFAULT CHARSET=utf8 COMMENT='Original copy of each piece of sutra';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `orgsutra`
--

DROP TABLE IF EXISTS `orgsutra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `orgsutra` (
  `osid` int(11) NOT NULL AUTO_INCREMENT,
  `osText` text COMMENT '经文原文。支持HTML编辑',
  `msid` int(11) NOT NULL COMMENT '关联MySutra.msid',
  PRIMARY KEY (`osid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='原经文';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sutraset`
--

DROP TABLE IF EXISTS `sutraset`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `sutraset` (
  `ssid` int(11) NOT NULL AUTO_INCREMENT,
  `verCode` varchar(45) DEFAULT NULL COMMENT '经集所属藏本或版本',
  `ssCode` varchar(45) DEFAULT NULL COMMENT '经集代号',
  `ssName` varchar(45) DEFAULT NULL COMMENT '经集名称',
  `ssFullName` varchar(90) DEFAULT NULL COMMENT '经集全名，如有',
  `ssChapterLv` varchar(45) DEFAULT NULL COMMENT '主結構層次',
  `ssChapterType` varchar(45) DEFAULT NULL COMMENT '經集主結構：{篇|相應|品}',
  `ssGroupType` varchar(45) DEFAULT NULL COMMENT '本经集除了结构性章节以外的其它出现的分组单位。\\n如相应部中的{篇|九分教}等。',
  `ssDesc` varchar(90) DEFAULT NULL,
  `ssText` text,
  PRIMARY KEY (`ssid`),
  UNIQUE KEY `ssCode_UNIQUE` (`ssCode`,`verCode`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `login_UNIQUE` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `version`
--

DROP TABLE IF EXISTS `version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `version` (
  `vid` int(11) NOT NULL AUTO_INCREMENT,
  `verCode` varchar(45) DEFAULT NULL,
  `verName` varchar(45) DEFAULT NULL,
  `verFullName` varchar(45) DEFAULT NULL,
  `verDesc` varchar(45) DEFAULT NULL,
  `verText` text,
  PRIMARY KEY (`vid`),
  UNIQUE KEY `idx_version_verCode` (`verCode`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-09-18 22:34:28
