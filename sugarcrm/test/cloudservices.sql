-- MySQL dump 10.13  Distrib 5.5.9, for osx10.6 (i386)
--
-- Host: localhost    Database: cloudservices
-- ------------------------------------------------------
-- Server version	5.5.9

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
-- Table structure for table `jobqueue`
--

DROP TABLE IF EXISTS `jobqueue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobqueue` (
  `job_id` char(36) NOT NULL,
  `cust_id` char(36) NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `result` longtext,
  `deleted` tinyint(1) DEFAULT '0',
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`job_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobqueue`
--

LOCK TABLES `jobqueue` WRITE;
/*!40000 ALTER TABLE `jobqueue` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobqueue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marketingcampaigns`
--

DROP TABLE IF EXISTS `marketingcampaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `marketingcampaigns` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `team_id` char(36) DEFAULT NULL,
  `team_set_id` char(36) DEFAULT NULL,
  `assigned_user_id` char(36) DEFAULT NULL,
  `budget` decimal(26,6) DEFAULT NULL,
  `currency_id` char(36) DEFAULT '-99',
  `start_date` date DEFAULT NULL,
  `stop_date` date DEFAULT NULL,
  `expected_cost` decimal(26,6) DEFAULT NULL,
  `actual_cost` decimal(26,6) DEFAULT NULL,
  `expected_revenue` decimal(26,6) DEFAULT NULL,
  `actual_revenue` decimal(26,6) DEFAULT NULL,
  `campaign_status` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `marketingcampaignsmod` (`date_modified`),
  KEY `idx_marketingcampaigns_tmst_id` (`team_set_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marketingcampaigns`
--

LOCK TABLES `marketingcampaigns` WRITE;
/*!40000 ALTER TABLE `marketingcampaigns` DISABLE KEYS */;
INSERT INTO `marketingcampaigns` VALUES ('3b7759f1-0da9-eae6-7df0-5295043b32f4','Once Upon a time','2013-11-26 20:27:04','2013-11-26 21:16:33','1','1','xxxxxyyyy',0,'1','1','1',NULL,'-99','2013-11-26','2013-11-27',NULL,NULL,NULL,NULL,'Started'),('3cb71f5b-1f49-62ff-f3d4-5295030450e5','Abcdefg','2013-11-26 20:26:33','2013-11-26 21:15:37','1','1','Now is the Time',0,'1','1','1',12345.000000,'-99','2013-11-26','2013-11-27',54321.000000,65432.000000,23456.000000,34567.000000,'Planning'),('6e315d95-9203-a9da-5459-52950f0232db','qwerty','2013-11-26 21:16:18','2013-11-26 21:16:18','1','1','asd asdasd',0,'1','1','1',NULL,'-99','2013-11-26','2013-11-27',NULL,NULL,NULL,NULL,'Complete');
/*!40000 ALTER TABLE `marketingcampaigns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `taskqueue`
--

DROP TABLE IF EXISTS `taskqueue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `taskqueue` (
  `job_id` char(36) NOT NULL,
  `cust_id` char(36) NOT NULL,
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `active` int(11) DEFAULT '0',
  `last` int(11) DEFAULT '0',
  `data` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taskqueue`
--

LOCK TABLES `taskqueue` WRITE;
/*!40000 ALTER TABLE `taskqueue` DISABLE KEYS */;
/*!40000 ALTER TABLE `taskqueue` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-12-08 11:49:47
