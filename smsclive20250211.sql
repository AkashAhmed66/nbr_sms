-- MySQL dump 10.13  Distrib 8.0.41, for Linux (x86_64)
--
-- Host: localhost    Database: smsc
-- ------------------------------------------------------
-- Server version	8.0.41-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `alert`
--

DROP TABLE IF EXISTS `alert`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `alert` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alert_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Queued','Sent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alert`
--

LOCK TABLES `alert` WRITE;
/*!40000 ALTER TABLE `alert` DISABLE KEYS */;
/*!40000 ALTER TABLE `alert` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `buy_bundles`
--

DROP TABLE IF EXISTS `buy_bundles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `buy_bundles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `masking_balance` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `non_masking_balance` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `email_balance` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `masking_rate` decimal(30,3) DEFAULT '0.000',
  `non_masking_rate` decimal(30,3) DEFAULT '0.000',
  `email_rate` decimal(30,3) DEFAULT '0.000',
  `total_price` decimal(30,3) DEFAULT '0.000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buy_bundles`
--

LOCK TABLES `buy_bundles` WRITE;
/*!40000 ALTER TABLE `buy_bundles` DISABLE KEYS */;
/*!40000 ALTER TABLE `buy_bundles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campaigns`
--

DROP TABLE IF EXISTS `campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `campaigns` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `orderid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source` enum('WEB','API') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'WEB',
  `mobile_no_column` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `json_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `senderID` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `group_id` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pages` int DEFAULT '0',
  `status` enum('Draft','Queue','Sending','Sent','Failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `units` decimal(10,2) NOT NULL DEFAULT '0.00',
  `sentFrom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Panel',
  `is_mms` int NOT NULL DEFAULT '0',
  `sms_count` int NOT NULL DEFAULT '0',
  `is_unicode` int NOT NULL DEFAULT '0',
  `IP` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Unknown',
  `gateway_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_type` enum('sendSms','groupSms','fileSms','DynamicSms','campaignSms') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `scheduleDateTime` datetime DEFAULT NULL,
  `search_param` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `error` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` int DEFAULT NULL,
  `blocked_status` enum('1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '1 = blocked and 2 = unblocked',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `content_type` enum('Text','Flash') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Text',
  `campaign_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_from` enum('WEB','API') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `sms_queued` int DEFAULT NULL,
  `sms_processing` int DEFAULT NULL,
  `sms_sent` int DEFAULT NULL,
  `sms_delivered` int DEFAULT NULL,
  `sms_failed` int DEFAULT NULL,
  `sms_blocked` int DEFAULT NULL,
  `is_complete` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `sms_type` (`sms_type`),
  KEY `sms_type_status` (`sms_type`,`status`),
  KEY `sms_type_status_scheduleDateTime` (`sms_type`,`status`,`scheduleDateTime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaigns`
--

LOCK TABLES `campaigns` WRITE;
/*!40000 ALTER TABLE `campaigns` DISABLE KEYS */;
/*!40000 ALTER TABLE `campaigns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `channel`
--

DROP TABLE IF EXISTS `channel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `channel` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_provider` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `channel_type` enum('MAP','SMPP','HTTP') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` enum('POST','GET') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `content_type` enum('ARRAY','JSON') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_parameter` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `balance_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `balance_parameter` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `port` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_mask` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tps` int NOT NULL DEFAULT '0',
  `created_by` int NOT NULL,
  `updated_by` int DEFAULT NULL,
  `status` enum('PENDING','ACTIVE','INACTIVE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `channel`
--

LOCK TABLES `channel` WRITE;
/*!40000 ALTER TABLE `channel` DISABLE KEYS */;
INSERT INTO `channel` VALUES (1,'gdgd','dfgdf','MAP',NULL,'https://www.nucn.edu.bd/website/',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,3,NULL,'PENDING','2024-10-23 15:47:37','2024-10-23 15:47:37');
/*!40000 ALTER TABLE `channel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_submits`
--

DROP TABLE IF EXISTS `contact_submits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_submits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_submits`
--

LOCK TABLES `contact_submits` WRITE;
/*!40000 ALTER TABLE `contact_submits` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_submits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int DEFAULT NULL,
  `name_en` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_bn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profession` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('Male','Female','Other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `division` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `upazilla` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blood_group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int unsigned NOT NULL,
  `status` enum('Inactive','Active') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `subscribed` tinyint(1) NOT NULL DEFAULT '1',
  `remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unsubscribe_date` datetime DEFAULT NULL,
  `reseller_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
INSERT INTO `contacts` VALUES (11,5,'Test Name',NULL,'01629333222','test@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'Active',1,NULL,NULL,NULL,'2024-11-21 10:59:16','2024-11-21 10:59:16'),(12,5,'Mizanur rahaman',NULL,'01734183130','engrmukul@hotmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'Active',1,NULL,NULL,NULL,'2024-11-30 05:16:23','2024-11-30 05:16:23'),(13,5,'Mizanur rahaman',NULL,'01734183131','engrmukul@hotmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'Active',1,NULL,NULL,NULL,'2024-11-30 05:16:43','2024-11-30 05:16:43');
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `country` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `iso` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nickname` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `iso3` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numcode` smallint DEFAULT NULL,
  `phonecode` smallint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `country`
--

LOCK TABLES `country` WRITE;
/*!40000 ALTER TABLE `country` DISABLE KEYS */;
INSERT INTO `country` VALUES (4,'2','rrrr','rr','3',3,3,'2024-09-18 11:53:31','2024-09-18 21:21:04'),(5,'11','Indoneshia','ND','22',22,22,'2024-09-18 21:15:13','2024-09-18 21:15:13'),(7,'BB','werwerr','rr','33',33,33,'2024-09-18 21:16:33','2024-09-18 21:16:33'),(8,'44','qweqweqwe','ee','44',44,22,'2024-09-18 21:17:28','2024-09-18 21:17:28'),(9,'33','weqweqeq','ee','44',44,33,'2024-09-18 21:18:58','2024-09-18 21:18:58'),(10,'44','Mubaktaqi Chand','rrr','3',44,2,'2024-09-18 21:19:32','2024-09-18 21:19:32'),(11,'BB','Sombor','rr','33',44,33,'2024-09-18 21:20:06','2024-09-18 21:20:06');
/*!40000 ALTER TABLE `country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `deposit_history`
--

DROP TABLE IF EXISTS `deposit_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `deposit_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned DEFAULT NULL,
  `reseller_id` int unsigned DEFAULT NULL,
  `deposit_by` bigint NOT NULL,
  `deposit_amount` decimal(30,6) DEFAULT '0.000000',
  `status` enum('Pending','Approved') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expire_date` datetime DEFAULT NULL,
  `dr_cr` enum('DR','CR') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1943 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deposit_history`
--

LOCK TABLES `deposit_history` WRITE;
/*!40000 ALTER TABLE `deposit_history` DISABLE KEYS */;
INSERT INTO `deposit_history` VALUES (1906,NULL,80,3,2000.000000,'Approved','Prepaid','2024-04-13 17:52:05','2024-04-13 11:51:44','2024-04-13 11:52:05','2024-05-11 17:51:00','CR'),(1907,NULL,80,3,2000.000000,'Approved','Prepaid','2024-04-13 22:45:02','2024-04-13 16:44:40','2024-04-13 16:45:02','2024-05-30 22:44:00','CR'),(1908,NULL,134,3,3000.000000,'Approved','Prepaid','2024-04-15 14:07:38','2024-04-15 08:07:29','2024-04-15 08:07:38','2024-08-30 14:07:00','CR'),(1909,NULL,80,3,2000.000000,'Approved','Prepaid','2024-04-15 20:12:20','2024-04-15 14:12:12','2024-04-15 14:12:20','2024-06-30 20:12:00','CR'),(1910,NULL,80,3,2500.000000,'Approved','Prepaid','2024-04-16 12:58:07','2024-04-16 06:57:58','2024-04-16 06:58:07','2024-04-16 12:57:00','CR'),(1911,624,NULL,3,12675.000000,'Approved','Prepaid','2024-04-16 18:01:22','2024-04-16 12:01:14','2024-04-16 12:01:22','2024-06-30 18:01:00','CR'),(1912,NULL,80,3,2000.000000,'Approved','Prepaid','2024-04-16 22:35:31','2024-04-16 16:35:23','2024-04-16 16:35:31','2024-07-01 22:35:00','CR'),(1913,NULL,102,3,15000.000000,'Approved','Prepaid','2024-04-18 11:41:07','2024-04-18 05:41:04','2024-04-18 05:41:07','2024-09-27 11:41:00','CR'),(1914,NULL,80,3,2000.000000,'Approved','Prepaid','2024-04-18 12:51:07','2024-04-18 06:51:00','2024-04-18 06:51:07','2024-06-30 12:50:00','CR'),(1915,853,NULL,505,200.000000,'Pending',NULL,NULL,'2024-04-18 08:28:44','2024-04-18 08:28:44','2024-04-18 14:28:00','DR'),(1916,854,NULL,505,200.000000,'Pending',NULL,NULL,'2024-04-18 08:29:37','2024-04-18 08:29:37','2024-06-30 14:29:00','DR'),(1917,855,NULL,505,500.000000,'Pending',NULL,NULL,'2024-04-18 08:30:06','2024-04-18 08:30:06','2024-06-30 14:29:00','DR'),(1918,856,NULL,505,200.000000,'Pending',NULL,NULL,'2024-04-18 08:30:24','2024-04-18 08:30:24','2024-06-30 14:30:00','DR'),(1919,857,NULL,505,200.000000,'Pending',NULL,NULL,'2024-04-18 08:30:39','2024-04-18 08:30:39','2024-06-30 14:30:00','DR'),(1920,858,NULL,505,200.000000,'Pending',NULL,NULL,'2024-04-18 08:30:56','2024-04-18 08:30:56','2024-06-30 14:30:00','DR'),(1921,859,NULL,505,500.000000,'Pending',NULL,NULL,'2024-04-18 08:31:14','2024-04-18 08:31:14','2024-06-30 14:31:00','DR'),(1922,860,NULL,505,200.000000,'Pending',NULL,NULL,'2024-04-18 08:31:29','2024-04-18 08:31:29','2024-06-30 14:31:00','DR'),(1923,NULL,164,3,13900.000000,'Approved','Prepaid','2024-04-18 14:32:28','2024-04-18 08:32:25','2024-04-18 08:32:28','2024-08-21 14:32:00','CR'),(1924,954,NULL,579,73200.000000,'Approved','Valid till 31st October 2024','2024-04-18 14:35:11','2024-04-18 08:34:49','2024-04-18 08:35:11','2024-10-31 14:34:00','CR'),(1925,951,NULL,675,3000.000000,'Approved','DBBL','2024-04-18 14:38:26','2024-04-18 08:37:16','2024-04-18 08:38:26','2024-04-18 14:37:00','CR'),(1926,NULL,80,3,5000.000000,'Approved','Prepaid','2024-04-19 12:26:22','2024-04-19 06:24:33','2024-04-19 06:26:22','2024-05-31 12:24:00','CR'),(1927,312,NULL,3,500.000000,'Approved','Prepaid','2024-04-20 09:46:33','2024-04-20 03:46:25','2024-04-20 03:46:33','2024-06-27 09:46:00','CR'),(1928,NULL,80,3,2500.000000,'Approved','Prepaid','2024-04-20 13:24:13','2024-04-20 07:23:57','2024-04-20 07:24:13','2024-07-31 13:23:00','CR'),(1929,NULL,98,3,5000.000000,'Approved','Prepaid','2024-04-20 14:34:48','2024-04-20 08:34:40','2024-04-20 08:34:48','2024-05-29 14:34:00','CR'),(1930,971,NULL,432,5000.000000,'Approved','5000','2024-04-20 16:16:09','2024-04-20 10:09:24','2024-04-20 10:16:09','2024-12-31 16:09:00','CR'),(1931,972,NULL,675,520.000000,'Approved','balance transfer','2024-04-20 18:34:06','2024-04-20 12:07:19','2024-04-20 12:34:06','2024-04-20 18:07:00','CR'),(1932,NULL,80,3,2000.000000,'Approved','Prepaid','2024-04-20 21:48:22','2024-04-20 15:48:15','2024-04-20 15:48:22','2024-07-06 21:48:00','CR'),(1933,973,NULL,432,2450.000000,'Approved','Bkash','2024-04-21 11:50:15','2024-04-21 05:24:53','2024-04-21 05:50:15','2025-04-01 11:24:00','CR'),(1934,974,NULL,672,100.000000,'Approved','prepaid','2024-04-21 12:17:44','2024-04-21 06:15:21','2024-04-21 06:17:44','2026-08-19 12:15:00','CR'),(1935,NULL,80,3,2000.000000,'Approved','Prepaid','2024-04-22 10:36:05','2024-04-22 04:35:57','2024-04-22 04:36:05','2024-08-25 10:35:00','CR'),(1936,545,NULL,3,1500.000000,'Approved','Prepaid','2024-04-23 11:59:48','2024-04-23 05:59:45','2024-04-23 05:59:48','2024-09-12 11:59:00','CR'),(1937,NULL,98,3,260.000000,'Approved','Prepaid','2024-04-23 12:41:54','2024-04-23 06:41:51','2024-04-23 06:41:54','2024-09-20 12:41:00','CR'),(1938,681,NULL,3,200.000000,'Approved','Prepaid','2024-04-23 15:44:19','2024-04-23 09:44:14','2024-04-23 09:44:19','2024-09-12 15:43:00','CR'),(1939,NULL,102,3,15000.000000,'Approved','Prepaid','2024-04-23 16:24:31','2024-04-23 10:24:27','2024-04-23 10:24:31','2024-09-12 16:24:00','CR'),(1940,976,NULL,3,10.000000,'Approved','prepaid','2024-04-23 16:26:55','2024-04-23 10:26:50','2024-04-23 10:26:55','2025-01-11 16:26:00','CR'),(1941,NULL,80,3,2000.000000,'Approved','Prepaid','2024-04-23 17:45:00','2024-04-23 11:44:57','2024-04-23 11:45:00','2024-10-03 17:44:00','CR'),(1942,NULL,80,3,2000.000000,'Approved','Prepaid','2024-04-23 21:19:25','2024-04-23 15:19:11','2024-04-23 15:19:25','2024-07-31 21:19:00','CR');
/*!40000 ALTER TABLE `deposit_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dnds`
--

DROP TABLE IF EXISTS `dnds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dnds` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int unsigned NOT NULL,
  `status` enum('Inactive','Active') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dnds`
--

LOCK TABLES `dnds` WRITE;
/*!40000 ALTER TABLE `dnds` DISABLE KEYS */;
INSERT INTO `dnds` VALUES (3,'01712488600',3,'Active','2024-09-19 00:27:18','2024-09-19 00:27:25'),(4,'sdfsf',3,'Active','2024-09-19 12:30:10','2024-09-19 12:30:10'),(6,'ISO100001',991,'Active','2024-11-23 23:04:06','2024-11-23 23:04:06');
/*!40000 ALTER TABLE `dnds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `domains`
--

DROP TABLE IF EXISTS `domains`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `domains` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reseller_id` int unsigned DEFAULT NULL,
  `domain` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_email` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Active','Inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `domains`
--

LOCK TABLES `domains` WRITE;
/*!40000 ALTER TABLE `domains` DISABLE KEYS */;
/*!40000 ALTER TABLE `domains` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_logs`
--

DROP TABLE IF EXISTS `email_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `domain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `write_time` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Queue','Sent','Delivered','Opened','Failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Sent',
  `remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `response` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_reports` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opened` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_logs`
--

LOCK TABLES `email_logs` WRITE;
/*!40000 ALTER TABLE `email_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `email_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_routes`
--

DROP TABLE IF EXISTS `email_routes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_routes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned DEFAULT NULL,
  `source_domain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_service_provider_id` int unsigned NOT NULL,
  `cost` decimal(10,6) DEFAULT '0.000000',
  `success_rate` decimal(4,2) DEFAULT '0.00',
  `status` enum('Active','Inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_by` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_routes`
--

LOCK TABLES `email_routes` WRITE;
/*!40000 ALTER TABLE `email_routes` DISABLE KEYS */;
/*!40000 ALTER TABLE `email_routes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_service_providers`
--

DROP TABLE IF EXISTS `email_service_providers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_service_providers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_provider` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_type` enum('SMTP','HTTP','SDK') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secret_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `port` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tls` enum('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tps` int NOT NULL DEFAULT '0',
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `status` enum('PENDING','ACTIVE','INACTIVE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_service_providers`
--

LOCK TABLES `email_service_providers` WRITE;
/*!40000 ALTER TABLE `email_service_providers` DISABLE KEYS */;
/*!40000 ALTER TABLE `email_service_providers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_templates`
--

DROP TABLE IF EXISTS `email_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int NOT NULL,
  `status` enum('Active','Inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_templates`
--

LOCK TABLES `email_templates` WRITE;
/*!40000 ALTER TABLE `email_templates` DISABLE KEYS */;
/*!40000 ALTER TABLE `email_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
INSERT INTO `failed_jobs` VALUES (1,'104b6109-b142-4c06-80e7-bd44452c5108','database','default','{\"uuid\":\"104b6109-b142-4c06-80e7-bd44452c5108\",\"displayName\":\"Maatwebsite\\\\Excel\\\\Jobs\\\\ReadChunk\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":120,\"retryUntil\":null,\"data\":{\"commandName\":\"Maatwebsite\\\\Excel\\\\Jobs\\\\ReadChunk\",\"command\":\"O:32:\\\"Maatwebsite\\\\Excel\\\\Jobs\\\\ReadChunk\\\":23:{s:7:\\\"timeout\\\";i:120;s:5:\\\"tries\\\";N;s:13:\\\"maxExceptions\\\";N;s:7:\\\"backoff\\\";N;s:5:\\\"queue\\\";N;s:10:\\\"connection\\\";N;s:40:\\\"\\u0000Maatwebsite\\\\Excel\\\\Jobs\\\\ReadChunk\\u0000import\\\";O:25:\\\"App\\\\Imports\\\\ContactImport\\\":3:{s:13:\\\"\\u0000*\\u0000attributes\\\";a:3:{s:7:\\\"user_id\\\";i:414;s:8:\\\"group_id\\\";s:3:\\\"724\\\";s:11:\\\"reseller_id\\\";i:80;}s:7:\\\"timeout\\\";i:120;s:9:\\\"\\u0000*\\u0000output\\\";N;}s:40:\\\"\\u0000Maatwebsite\\\\Excel\\\\Jobs\\\\ReadChunk\\u0000reader\\\";O:36:\\\"PhpOffice\\\\PhpSpreadsheet\\\\Reader\\\\Xlsx\\\":11:{s:15:\\\"\\u0000*\\u0000readDataOnly\\\";b:1;s:17:\\\"\\u0000*\\u0000readEmptyCells\\\";b:1;s:16:\\\"\\u0000*\\u0000includeCharts\\\";b:0;s:17:\\\"\\u0000*\\u0000loadSheetsOnly\\\";N;s:13:\\\"\\u0000*\\u0000readFilter\\\";O:49:\\\"PhpOffice\\\\PhpSpreadsheet\\\\Reader\\\\DefaultReadFilter\\\":0:{}s:13:\\\"\\u0000*\\u0000fileHandle\\\";N;s:18:\\\"\\u0000*\\u0000securityScanner\\\";O:51:\\\"PhpOffice\\\\PhpSpreadsheet\\\\Reader\\\\Security\\\\XmlScanner\\\":2:{s:60:\\\"\\u0000PhpOffice\\\\PhpSpreadsheet\\\\Reader\\\\Security\\\\XmlScanner\\u0000pattern\\\";s:9:\\\"<!DOCTYPE\\\";s:61:\\\"\\u0000PhpOffice\\\\PhpSpreadsheet\\\\Reader\\\\Security\\\\XmlScanner\\u0000callback\\\";N;}s:53:\\\"\\u0000PhpOffice\\\\PhpSpreadsheet\\\\Reader\\\\Xlsx\\u0000referenceHelper\\\";O:40:\\\"PhpOffice\\\\PhpSpreadsheet\\\\ReferenceHelper\\\":1:{s:61:\\\"\\u0000PhpOffice\\\\PhpSpreadsheet\\\\ReferenceHelper\\u0000cellReferenceHelper\\\";N;}s:41:\\\"\\u0000PhpOffice\\\\PhpSpreadsheet\\\\Reader\\\\Xlsx\\u0000zip\\\";O:10:\\\"ZipArchive\\\":6:{s:6:\\\"lastId\\\";i:-1;s:6:\\\"status\\\";i:0;s:9:\\\"statusSys\\\";i:0;s:8:\\\"numFiles\\\";i:0;s:8:\\\"filename\\\";s:0:\\\"\\\";s:7:\\\"comment\\\";s:0:\\\"\\\";}s:49:\\\"\\u0000PhpOffice\\\\PhpSpreadsheet\\\\Reader\\\\Xlsx\\u0000styleReader\\\";N;s:52:\\\"\\u0000PhpOffice\\\\PhpSpreadsheet\\\\Reader\\\\Xlsx\\u0000sharedFormulae\\\";a:0:{}}s:47:\\\"\\u0000Maatwebsite\\\\Excel\\\\Jobs\\\\ReadChunk\\u0000temporaryFile\\\";O:42:\\\"Maatwebsite\\\\Excel\\\\Files\\\\LocalTemporaryFile\\\":1:{s:52:\\\"\\u0000Maatwebsite\\\\Excel\\\\Files\\\\LocalTemporaryFile\\u0000filePath\\\";s:107:\\\"\\/var\\/www\\/html\\/sms-web\\/system\\/storage\\/framework\\/laravel-excel\\/laravel-excel-D224Kep7ok1hmCFYn3IdQhyeQcyrCbKP\\\";}s:43:\\\"\\u0000Maatwebsite\\\\Excel\\\\Jobs\\\\ReadChunk\\u0000sheetName\\\";s:6:\\\"Sheet1\\\";s:45:\\\"\\u0000Maatwebsite\\\\Excel\\\\Jobs\\\\ReadChunk\\u0000sheetImport\\\";r:8;s:42:\\\"\\u0000Maatwebsite\\\\Excel\\\\Jobs\\\\ReadChunk\\u0000startRow\\\";i:2;s:43:\\\"\\u0000Maatwebsite\\\\Excel\\\\Jobs\\\\ReadChunk\\u0000chunkSize\\\";i:1000;s:42:\\\"\\u0000Maatwebsite\\\\Excel\\\\Jobs\\\\ReadChunk\\u0000uniqueId\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:1:{i:0;s:2328:\\\"O:37:\\\"Maatwebsite\\\\Excel\\\\Jobs\\\\AfterImportJob\\\":15:{s:45:\\\"\\u0000Maatwebsite\\\\Excel\\\\Jobs\\\\AfterImportJob\\u0000import\\\";O:25:\\\"App\\\\Imports\\\\ContactImport\\\":3:{s:13:\\\"\\u0000*\\u0000attributes\\\";a:3:{s:7:\\\"user_id\\\";i:414;s:8:\\\"group_id\\\";s:3:\\\"724\\\";s:11:\\\"reseller_id\\\";i:80;}s:7:\\\"timeout\\\";i:120;s:9:\\\"\\u0000*\\u0000output\\\";N;}s:45:\\\"\\u0000Maatwebsite\\\\Excel\\\\Jobs\\\\AfterImportJob\\u0000reader\\\";O:24:\\\"Maatwebsite\\\\Excel\\\\Reader\\\":5:{s:14:\\\"\\u0000*\\u0000spreadsheet\\\";N;s:15:\\\"\\u0000*\\u0000sheetImports\\\";a:0:{}s:14:\\\"\\u0000*\\u0000currentFile\\\";O:42:\\\"Maatwebsite\\\\Excel\\\\Files\\\\LocalTemporaryFile\\\":1:{s:52:\\\"\\u0000Maatwebsite\\\\Excel\\\\Files\\\\LocalTemporaryFile\\u0000filePath\\\";s:107:\\\"\\/var\\/www\\/html\\/sms-web\\/system\\/storage\\/framework\\/laravel-excel\\/laravel-excel-D224Kep7ok1hmCFYn3IdQhyeQcyrCbKP\\\";}s:23:\\\"\\u0000*\\u0000temporaryFileFactory\\\";O:44:\\\"Maatwebsite\\\\Excel\\\\Files\\\\TemporaryFileFactory\\\":2:{s:59:\\\"\\u0000Maatwebsite\\\\Excel\\\\Files\\\\TemporaryFileFactory\\u0000temporaryPath\\\";s:60:\\\"\\/var\\/www\\/html\\/sms-web\\/system\\/storage\\/framework\\/laravel-excel\\\";s:59:\\\"\\u0000Maatwebsite\\\\Excel\\\\Files\\\\TemporaryFileFactory\\u0000temporaryDisk\\\";N;}s:9:\\\"\\u0000*\\u0000reader\\\";O:36:\\\"PhpOffice\\\\PhpSpreadsheet\\\\Reader\\\\Xlsx\\\":11:{s:15:\\\"\\u0000*\\u0000readDataOnly\\\";b:1;s:17:\\\"\\u0000*\\u0000readEmptyCells\\\";b:1;s:16:\\\"\\u0000*\\u0000includeCharts\\\";b:0;s:17:\\\"\\u0000*\\u0000loadSheetsOnly\\\";N;s:13:\\\"\\u0000*\\u0000readFilter\\\";O:49:\\\"PhpOffice\\\\PhpSpreadsheet\\\\Reader\\\\DefaultReadFilter\\\":0:{}s:13:\\\"\\u0000*\\u0000fileHandle\\\";N;s:18:\\\"\\u0000*\\u0000securityScanner\\\";O:51:\\\"PhpOffice\\\\PhpSpreadsheet\\\\Reader\\\\Security\\\\XmlScanner\\\":2:{s:60:\\\"\\u0000PhpOffice\\\\PhpSpreadsheet\\\\Reader\\\\Security\\\\XmlScanner\\u0000pattern\\\";s:9:\\\"<!DOCTYPE\\\";s:61:\\\"\\u0000PhpOffice\\\\PhpSpreadsheet\\\\Reader\\\\Security\\\\XmlScanner\\u0000callback\\\";N;}s:53:\\\"\\u0000PhpOffice\\\\PhpSpreadsheet\\\\Reader\\\\Xlsx\\u0000referenceHelper\\\";O:40:\\\"PhpOffice\\\\PhpSpreadsheet\\\\ReferenceHelper\\\":1:{s:61:\\\"\\u0000PhpOffice\\\\PhpSpreadsheet\\\\ReferenceHelper\\u0000cellReferenceHelper\\\";N;}s:41:\\\"\\u0000PhpOffice\\\\PhpSpreadsheet\\\\Reader\\\\Xlsx\\u0000zip\\\";O:10:\\\"ZipArchive\\\":6:{s:6:\\\"lastId\\\";i:-1;s:6:\\\"status\\\";i:0;s:9:\\\"statusSys\\\";i:0;s:8:\\\"numFiles\\\";i:0;s:8:\\\"filename\\\";s:0:\\\"\\\";s:7:\\\"comment\\\";s:0:\\\"\\\";}s:49:\\\"\\u0000PhpOffice\\\\PhpSpreadsheet\\\\Reader\\\\Xlsx\\u0000styleReader\\\";N;s:52:\\\"\\u0000PhpOffice\\\\PhpSpreadsheet\\\\Reader\\\\Xlsx\\u0000sharedFormulae\\\";a:0:{}}}s:52:\\\"\\u0000Maatwebsite\\\\Excel\\\\Jobs\\\\AfterImportJob\\u0000dependencyIds\\\";a:0:{}s:47:\\\"\\u0000Maatwebsite\\\\Excel\\\\Jobs\\\\AfterImportJob\\u0000interval\\\";i:60;s:9:\\\"\\u0000*\\u0000events\\\";a:0:{}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\\\";}s:9:\\\"\\u0000*\\u0000events\\\";a:0:{}s:3:\\\"job\\\";N;}\"}}','Maatwebsite\\Excel\\Validators\\ValidationException: The given data was invalid. in /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Validators/RowValidator.php:68\nStack trace:\n#0 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Imports/ModelManager.php(202): Maatwebsite\\Excel\\Validators\\RowValidator->validate()\n#1 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Imports/ModelManager.php(75): Maatwebsite\\Excel\\Imports\\ModelManager->validateRows()\n#2 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Imports/ModelImporter.php(114): Maatwebsite\\Excel\\Imports\\ModelManager->flush()\n#3 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Imports/ModelImporter.php(108): Maatwebsite\\Excel\\Imports\\ModelImporter->flush()\n#4 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Sheet.php(258): Maatwebsite\\Excel\\Imports\\ModelImporter->import()\n#5 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Jobs/ReadChunk.php(213): Maatwebsite\\Excel\\Sheet->import()\n#6 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Concerns/ManagesTransactions.php(29): Maatwebsite\\Excel\\Jobs\\ReadChunk->Maatwebsite\\Excel\\Jobs\\{closure}()\n#7 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Transactions/DbTransactionHandler.php(30): Illuminate\\Database\\Connection->transaction()\n#8 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Jobs/ReadChunk.php(221): Maatwebsite\\Excel\\Transactions\\DbTransactionHandler->__invoke()\n#9 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Maatwebsite\\Excel\\Jobs\\ReadChunk->handle()\n#10 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#11 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure()\n#12 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#13 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/Container.php(653): Illuminate\\Container\\BoundMethod::call()\n#14 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(128): Illuminate\\Container\\Container->call()\n#15 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#16 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#17 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then()\n#18 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(120): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#19 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#20 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#21 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(122): Illuminate\\Pipeline\\Pipeline->then()\n#22 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#23 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(98): Illuminate\\Queue\\CallQueuedHandler->call()\n#24 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(428): Illuminate\\Queue\\Jobs\\Job->fire()\n#25 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(378): Illuminate\\Queue\\Worker->process()\n#26 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(172): Illuminate\\Queue\\Worker->runJob()\n#27 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(117): Illuminate\\Queue\\Worker->daemon()\n#28 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(101): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#29 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#30 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#31 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure()\n#32 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#33 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/Container.php(653): Illuminate\\Container\\BoundMethod::call()\n#34 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Console/Command.php(136): Illuminate\\Container\\Container->call()\n#35 /var/www/html/sms-web/system/vendor/symfony/console/Command/Command.php(298): Illuminate\\Console\\Command->execute()\n#36 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Console/Command.php(121): Symfony\\Component\\Console\\Command\\Command->run()\n#37 /var/www/html/sms-web/system/vendor/symfony/console/Application.php(1040): Illuminate\\Console\\Command->run()\n#38 /var/www/html/sms-web/system/vendor/symfony/console/Application.php(301): Symfony\\Component\\Console\\Application->doRunCommand()\n#39 /var/www/html/sms-web/system/vendor/symfony/console/Application.php(171): Symfony\\Component\\Console\\Application->doRun()\n#40 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Console/Application.php(94): Symfony\\Component\\Console\\Application->run()\n#41 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(129): Illuminate\\Console\\Application->run()\n#42 /var/www/html/sms-web/system/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle()\n#43 {main}','2024-04-25 17:35:58'),(2,'e4b77d0f-b3e0-4005-adb6-0806706be3fe','database','default','{\"uuid\":\"e4b77d0f-b3e0-4005-adb6-0806706be3fe\",\"displayName\":\"App\\\\Jobs\\\\ExportLargeData\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ExportLargeData\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\ExportLargeData\\\":11:{s:7:\\\"batchId\\\";N;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}','Illuminate\\Queue\\MaxAttemptsExceededException: App\\Jobs\\ExportLargeData has been attempted too many times or run too long. The job may have previously timed out. in /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Worker.php:750\nStack trace:\n#0 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(213): Illuminate\\Queue\\Worker->maxAttemptsExceededException()\n#1 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Connection.php(375): Illuminate\\Queue\\Worker->Illuminate\\Queue\\{closure}()\n#2 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Connection.php(705): Illuminate\\Database\\Connection->Illuminate\\Database\\{closure}()\n#3 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Connection.php(672): Illuminate\\Database\\Connection->runQueryCallback()\n#4 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Connection.php(376): Illuminate\\Database\\Connection->run()\n#5 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Query/Builder.php(2414): Illuminate\\Database\\Connection->select()\n#6 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Query/Builder.php(2402): Illuminate\\Database\\Query\\Builder->runSelect()\n#7 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Query/Builder.php(2936): Illuminate\\Database\\Query\\Builder->Illuminate\\Database\\Query\\{closure}()\n#8 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Query/Builder.php(2403): Illuminate\\Database\\Query\\Builder->onceWithColumns()\n#9 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(625): Illuminate\\Database\\Query\\Builder->get()\n#10 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(609): Illuminate\\Database\\Eloquent\\Builder->getModels()\n#11 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Concerns/BuildsQueries.php(40): Illuminate\\Database\\Eloquent\\Builder->get()\n#12 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Sheet.php(476): Illuminate\\Database\\Eloquent\\Builder->chunk()\n#13 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Sheet.php(212): Maatwebsite\\Excel\\Sheet->fromQuery()\n#14 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Writer.php(72): Maatwebsite\\Excel\\Sheet->export()\n#15 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Excel.php(203): Maatwebsite\\Excel\\Writer->export()\n#16 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Excel.php(107): Maatwebsite\\Excel\\Excel->export()\n#17 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Support/Facades/Facade.php(261): Maatwebsite\\Excel\\Excel->store()\n#18 /var/www/html/sms-web/system/app/Jobs/ExportLargeData.php(19): Illuminate\\Support\\Facades\\Facade::__callStatic()\n#19 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\ExportLargeData->handle()\n#20 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#21 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure()\n#22 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#23 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/Container.php(653): Illuminate\\Container\\BoundMethod::call()\n#24 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(128): Illuminate\\Container\\Container->call()\n#25 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#26 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#27 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then()\n#28 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(120): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#29 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#30 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#31 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(122): Illuminate\\Pipeline\\Pipeline->then()\n#32 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#33 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(98): Illuminate\\Queue\\CallQueuedHandler->call()\n#34 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(428): Illuminate\\Queue\\Jobs\\Job->fire()\n#35 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(378): Illuminate\\Queue\\Worker->process()\n#36 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(172): Illuminate\\Queue\\Worker->runJob()\n#37 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(117): Illuminate\\Queue\\Worker->daemon()\n#38 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(101): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#39 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#40 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#41 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure()\n#42 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#43 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/Container.php(653): Illuminate\\Container\\BoundMethod::call()\n#44 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Console/Command.php(136): Illuminate\\Container\\Container->call()\n#45 /var/www/html/sms-web/system/vendor/symfony/console/Command/Command.php(298): Illuminate\\Console\\Command->execute()\n#46 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Console/Command.php(121): Symfony\\Component\\Console\\Command\\Command->run()\n#47 /var/www/html/sms-web/system/vendor/symfony/console/Application.php(1040): Illuminate\\Console\\Command->run()\n#48 /var/www/html/sms-web/system/vendor/symfony/console/Application.php(301): Symfony\\Component\\Console\\Application->doRunCommand()\n#49 /var/www/html/sms-web/system/vendor/symfony/console/Application.php(171): Symfony\\Component\\Console\\Application->doRun()\n#50 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Console/Application.php(94): Symfony\\Component\\Console\\Application->run()\n#51 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(129): Illuminate\\Console\\Application->run()\n#52 /var/www/html/sms-web/system/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle()\n#53 {main}','2024-04-25 18:38:28'),(3,'e973c0a8-c7a5-418d-9e25-2b7856ec8153','database','default','{\"uuid\":\"e973c0a8-c7a5-418d-9e25-2b7856ec8153\",\"displayName\":\"App\\\\Jobs\\\\ExportLargeData\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ExportLargeData\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\ExportLargeData\\\":11:{s:7:\\\"batchId\\\";N;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}','Illuminate\\Queue\\MaxAttemptsExceededException: App\\Jobs\\ExportLargeData has been attempted too many times or run too long. The job may have previously timed out. in /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Worker.php:750\nStack trace:\n#0 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(213): Illuminate\\Queue\\Worker->maxAttemptsExceededException()\n#1 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Connection.php(375): Illuminate\\Queue\\Worker->Illuminate\\Queue\\{closure}()\n#2 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Connection.php(705): Illuminate\\Database\\Connection->Illuminate\\Database\\{closure}()\n#3 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Connection.php(672): Illuminate\\Database\\Connection->runQueryCallback()\n#4 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Connection.php(376): Illuminate\\Database\\Connection->run()\n#5 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Query/Builder.php(2414): Illuminate\\Database\\Connection->select()\n#6 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Query/Builder.php(2402): Illuminate\\Database\\Query\\Builder->runSelect()\n#7 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Query/Builder.php(2936): Illuminate\\Database\\Query\\Builder->Illuminate\\Database\\Query\\{closure}()\n#8 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Query/Builder.php(2403): Illuminate\\Database\\Query\\Builder->onceWithColumns()\n#9 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(625): Illuminate\\Database\\Query\\Builder->get()\n#10 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(609): Illuminate\\Database\\Eloquent\\Builder->getModels()\n#11 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Concerns/BuildsQueries.php(40): Illuminate\\Database\\Eloquent\\Builder->get()\n#12 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Sheet.php(476): Illuminate\\Database\\Eloquent\\Builder->chunk()\n#13 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Sheet.php(212): Maatwebsite\\Excel\\Sheet->fromQuery()\n#14 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Writer.php(72): Maatwebsite\\Excel\\Sheet->export()\n#15 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Excel.php(203): Maatwebsite\\Excel\\Writer->export()\n#16 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Excel.php(107): Maatwebsite\\Excel\\Excel->export()\n#17 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Support/Facades/Facade.php(261): Maatwebsite\\Excel\\Excel->store()\n#18 /var/www/html/sms-web/system/app/Jobs/ExportLargeData.php(19): Illuminate\\Support\\Facades\\Facade::__callStatic()\n#19 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\ExportLargeData->handle()\n#20 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#21 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure()\n#22 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#23 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/Container.php(653): Illuminate\\Container\\BoundMethod::call()\n#24 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(128): Illuminate\\Container\\Container->call()\n#25 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#26 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#27 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then()\n#28 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(120): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#29 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#30 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#31 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(122): Illuminate\\Pipeline\\Pipeline->then()\n#32 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#33 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(98): Illuminate\\Queue\\CallQueuedHandler->call()\n#34 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(428): Illuminate\\Queue\\Jobs\\Job->fire()\n#35 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(378): Illuminate\\Queue\\Worker->process()\n#36 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(172): Illuminate\\Queue\\Worker->runJob()\n#37 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(117): Illuminate\\Queue\\Worker->daemon()\n#38 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(101): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#39 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#40 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#41 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure()\n#42 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#43 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/Container.php(653): Illuminate\\Container\\BoundMethod::call()\n#44 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Console/Command.php(136): Illuminate\\Container\\Container->call()\n#45 /var/www/html/sms-web/system/vendor/symfony/console/Command/Command.php(298): Illuminate\\Console\\Command->execute()\n#46 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Console/Command.php(121): Symfony\\Component\\Console\\Command\\Command->run()\n#47 /var/www/html/sms-web/system/vendor/symfony/console/Application.php(1040): Illuminate\\Console\\Command->run()\n#48 /var/www/html/sms-web/system/vendor/symfony/console/Application.php(301): Symfony\\Component\\Console\\Application->doRunCommand()\n#49 /var/www/html/sms-web/system/vendor/symfony/console/Application.php(171): Symfony\\Component\\Console\\Application->doRun()\n#50 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Console/Application.php(94): Symfony\\Component\\Console\\Application->run()\n#51 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(129): Illuminate\\Console\\Application->run()\n#52 /var/www/html/sms-web/system/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle()\n#53 {main}','2024-04-25 18:44:21'),(4,'36202b7c-539e-4792-80cd-8421a2432ea1','database','default','{\"uuid\":\"36202b7c-539e-4792-80cd-8421a2432ea1\",\"displayName\":\"App\\\\Jobs\\\\ExportLargeData\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ExportLargeData\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\ExportLargeData\\\":11:{s:7:\\\"batchId\\\";N;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}','Illuminate\\Queue\\MaxAttemptsExceededException: App\\Jobs\\ExportLargeData has been attempted too many times or run too long. The job may have previously timed out. in /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Worker.php:750\nStack trace:\n#0 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(213): Illuminate\\Queue\\Worker->maxAttemptsExceededException()\n#1 /var/www/html/sms-web/system/vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Shared/StringHelper.php(495): Illuminate\\Queue\\Worker->Illuminate\\Queue\\{closure}()\n#2 /var/www/html/sms-web/system/vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Cell/Cell.php(205): PhpOffice\\PhpSpreadsheet\\Shared\\StringHelper::strToLower()\n#3 /var/www/html/sms-web/system/vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Cell/Cell.php(310): PhpOffice\\PhpSpreadsheet\\Cell\\Cell::updateIfCellIsTableHeader()\n#4 /var/www/html/sms-web/system/vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Cell/DefaultValueBinder.php(35): PhpOffice\\PhpSpreadsheet\\Cell\\Cell->setValueExplicit()\n#5 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/DefaultValueBinder.php(21): PhpOffice\\PhpSpreadsheet\\Cell\\DefaultValueBinder->bindValue()\n#6 /var/www/html/sms-web/system/vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Cell/Cell.php(237): Maatwebsite\\Excel\\DefaultValueBinder->bindValue()\n#7 /var/www/html/sms-web/system/vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Worksheet/Worksheet.php(3019): PhpOffice\\PhpSpreadsheet\\Cell\\Cell->setValue()\n#8 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Sheet.php(557): PhpOffice\\PhpSpreadsheet\\Worksheet\\Worksheet->fromArray()\n#9 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Sheet.php(674): Maatwebsite\\Excel\\Sheet->append()\n#10 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Collections/Traits/EnumeratesValues.php(245): Maatwebsite\\Excel\\Sheet->Maatwebsite\\Excel\\{closure}()\n#11 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Sheet.php(676): Illuminate\\Support\\Collection->each()\n#12 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Sheet.php(475): Maatwebsite\\Excel\\Sheet->appendRows()\n#13 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Concerns/BuildsQueries.php(51): Maatwebsite\\Excel\\Sheet->Maatwebsite\\Excel\\{closure}()\n#14 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Sheet.php(476): Illuminate\\Database\\Eloquent\\Builder->chunk()\n#15 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Sheet.php(212): Maatwebsite\\Excel\\Sheet->fromQuery()\n#16 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Writer.php(72): Maatwebsite\\Excel\\Sheet->export()\n#17 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Excel.php(203): Maatwebsite\\Excel\\Writer->export()\n#18 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Excel.php(107): Maatwebsite\\Excel\\Excel->export()\n#19 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Support/Facades/Facade.php(261): Maatwebsite\\Excel\\Excel->store()\n#20 /var/www/html/sms-web/system/app/Jobs/ExportLargeData.php(19): Illuminate\\Support\\Facades\\Facade::__callStatic()\n#21 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\ExportLargeData->handle()\n#22 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#23 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure()\n#24 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#25 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/Container.php(653): Illuminate\\Container\\BoundMethod::call()\n#26 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(128): Illuminate\\Container\\Container->call()\n#27 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#28 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#29 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then()\n#30 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(120): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#31 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#32 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#33 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(122): Illuminate\\Pipeline\\Pipeline->then()\n#34 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#35 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(98): Illuminate\\Queue\\CallQueuedHandler->call()\n#36 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(428): Illuminate\\Queue\\Jobs\\Job->fire()\n#37 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(378): Illuminate\\Queue\\Worker->process()\n#38 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(172): Illuminate\\Queue\\Worker->runJob()\n#39 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(117): Illuminate\\Queue\\Worker->daemon()\n#40 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(101): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#41 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#42 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#43 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure()\n#44 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#45 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/Container.php(653): Illuminate\\Container\\BoundMethod::call()\n#46 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Console/Command.php(136): Illuminate\\Container\\Container->call()\n#47 /var/www/html/sms-web/system/vendor/symfony/console/Command/Command.php(298): Illuminate\\Console\\Command->execute()\n#48 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Console/Command.php(121): Symfony\\Component\\Console\\Command\\Command->run()\n#49 /var/www/html/sms-web/system/vendor/symfony/console/Application.php(1040): Illuminate\\Console\\Command->run()\n#50 /var/www/html/sms-web/system/vendor/symfony/console/Application.php(301): Symfony\\Component\\Console\\Application->doRunCommand()\n#51 /var/www/html/sms-web/system/vendor/symfony/console/Application.php(171): Symfony\\Component\\Console\\Application->doRun()\n#52 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Console/Application.php(94): Symfony\\Component\\Console\\Application->run()\n#53 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(129): Illuminate\\Console\\Application->run()\n#54 /var/www/html/sms-web/system/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle()\n#55 {main}','2024-04-25 18:48:53'),(5,'dd0f9328-50dd-41bd-bae1-5b219ea56d90','database','default','{\"uuid\":\"dd0f9328-50dd-41bd-bae1-5b219ea56d90\",\"displayName\":\"App\\\\Jobs\\\\ExportLargeData\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ExportLargeData\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\ExportLargeData\\\":11:{s:7:\\\"batchId\\\";N;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}','Illuminate\\Queue\\MaxAttemptsExceededException: App\\Jobs\\ExportLargeData has been attempted too many times or run too long. The job may have previously timed out. in /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Worker.php:750\nStack trace:\n#0 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(213): Illuminate\\Queue\\Worker->maxAttemptsExceededException()\n#1 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Connection.php(375): Illuminate\\Queue\\Worker->Illuminate\\Queue\\{closure}()\n#2 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Connection.php(705): Illuminate\\Database\\Connection->Illuminate\\Database\\{closure}()\n#3 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Connection.php(672): Illuminate\\Database\\Connection->runQueryCallback()\n#4 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Connection.php(376): Illuminate\\Database\\Connection->run()\n#5 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Query/Builder.php(2414): Illuminate\\Database\\Connection->select()\n#6 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Query/Builder.php(2402): Illuminate\\Database\\Query\\Builder->runSelect()\n#7 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Query/Builder.php(2936): Illuminate\\Database\\Query\\Builder->Illuminate\\Database\\Query\\{closure}()\n#8 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Query/Builder.php(2403): Illuminate\\Database\\Query\\Builder->onceWithColumns()\n#9 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(625): Illuminate\\Database\\Query\\Builder->get()\n#10 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(609): Illuminate\\Database\\Eloquent\\Builder->getModels()\n#11 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Database/Concerns/BuildsQueries.php(40): Illuminate\\Database\\Eloquent\\Builder->get()\n#12 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Sheet.php(476): Illuminate\\Database\\Eloquent\\Builder->chunk()\n#13 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Sheet.php(212): Maatwebsite\\Excel\\Sheet->fromQuery()\n#14 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Writer.php(72): Maatwebsite\\Excel\\Sheet->export()\n#15 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Excel.php(203): Maatwebsite\\Excel\\Writer->export()\n#16 /var/www/html/sms-web/system/vendor/maatwebsite/excel/src/Excel.php(107): Maatwebsite\\Excel\\Excel->export()\n#17 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Support/Facades/Facade.php(261): Maatwebsite\\Excel\\Excel->store()\n#18 /var/www/html/sms-web/system/app/Jobs/ExportLargeData.php(19): Illuminate\\Support\\Facades\\Facade::__callStatic()\n#19 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\ExportLargeData->handle()\n#20 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#21 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure()\n#22 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#23 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/Container.php(653): Illuminate\\Container\\BoundMethod::call()\n#24 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(128): Illuminate\\Container\\Container->call()\n#25 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#26 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#27 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then()\n#28 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(120): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#29 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#30 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#31 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(122): Illuminate\\Pipeline\\Pipeline->then()\n#32 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#33 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(98): Illuminate\\Queue\\CallQueuedHandler->call()\n#34 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(428): Illuminate\\Queue\\Jobs\\Job->fire()\n#35 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(378): Illuminate\\Queue\\Worker->process()\n#36 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(172): Illuminate\\Queue\\Worker->runJob()\n#37 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(117): Illuminate\\Queue\\Worker->daemon()\n#38 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(101): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#39 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#40 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#41 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure()\n#42 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#43 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Container/Container.php(653): Illuminate\\Container\\BoundMethod::call()\n#44 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Console/Command.php(136): Illuminate\\Container\\Container->call()\n#45 /var/www/html/sms-web/system/vendor/symfony/console/Command/Command.php(298): Illuminate\\Console\\Command->execute()\n#46 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Console/Command.php(121): Symfony\\Component\\Console\\Command\\Command->run()\n#47 /var/www/html/sms-web/system/vendor/symfony/console/Application.php(1040): Illuminate\\Console\\Command->run()\n#48 /var/www/html/sms-web/system/vendor/symfony/console/Application.php(301): Symfony\\Component\\Console\\Application->doRunCommand()\n#49 /var/www/html/sms-web/system/vendor/symfony/console/Application.php(171): Symfony\\Component\\Console\\Application->doRun()\n#50 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Console/Application.php(94): Symfony\\Component\\Console\\Application->run()\n#51 /var/www/html/sms-web/system/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(129): Illuminate\\Console\\Application->run()\n#52 /var/www/html/sms-web/system/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle()\n#53 {main}','2024-04-25 18:52:03');
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group`
--

DROP TABLE IF EXISTS `group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `group` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('Public','Private') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Public',
  `status` enum('Inactive','Active') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `reseller_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group`
--

LOCK TABLES `group` WRITE;
/*!40000 ALTER TABLE `group` DISABLE KEYS */;
INSERT INTO `group` VALUES (1,3,'Rasel222','Public','Active',1,'2024-09-19 00:18:23','2024-09-19 12:34:41'),(2,3,'Somapon','Public','Active',6,'2024-09-19 00:20:39','2024-09-19 00:21:46'),(4,3,'rwerwr','Public','Active',1,'2024-09-19 12:34:30','2024-09-19 12:34:30'),(5,3,'Test','Public','Active',3,'2024-11-21 08:07:49','2024-11-21 08:07:49'),(6,3,'hfhf','Public','Active',3,'2024-11-21 10:47:44','2024-11-21 10:47:44'),(7,3,'sfsdfsdfsdf','Public','Active',NULL,'2024-11-21 10:47:59','2024-11-21 10:47:59'),(8,991,'Mizanur rahaman','Public','Active',18,'2024-11-23 23:12:47','2024-11-23 23:12:47');
/*!40000 ALTER TABLE `group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inbox`
--

DROP TABLE IF EXISTS `inbox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inbox` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sender` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `smscount` int DEFAULT NULL,
  `part_no` int DEFAULT NULL,
  `total_parts` int DEFAULT NULL,
  `reference_no` int DEFAULT NULL,
  `read` tinyint DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inbox`
--

LOCK TABLES `inbox` WRITE;
/*!40000 ALTER TABLE `inbox` DISABLE KEYS */;
INSERT INTO `inbox` VALUES (1,'8801569174577','470009612442082','SYMPHONYF B70 352147304214144;352147304214151:470045011606462:0:0',0,0,0,0,0,'2025-02-03 17:54:06','2025-02-03 17:54:06');
/*!40000 ALTER TABLE `inbox` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `reseller_id` int DEFAULT NULL,
  `invoice_from` date NOT NULL,
  `invoice_to` date NOT NULL,
  `invoice_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (5,'export','{\"uuid\":\"ae384604-a20e-465d-9982-4b87006bdbb4\",\"displayName\":\"App\\\\Jobs\\\\ExportLargeData\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ExportLargeData\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\ExportLargeData\\\":11:{s:7:\\\"batchId\\\";N;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:6:\\\"export\\\";s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}',0,NULL,1714070750,1714070750),(6,'export','{\"uuid\":\"ef109942-1ac5-46b9-adf2-c571564a5d68\",\"displayName\":\"App\\\\Jobs\\\\ExportLargeData\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ExportLargeData\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\ExportLargeData\\\":11:{s:7:\\\"batchId\\\";N;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:6:\\\"export\\\";s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}',0,NULL,1714070775,1714070775),(9,'default','{\"uuid\":\"1418e73f-6e9a-403f-8289-e8115c861071\",\"displayName\":\"App\\\\Jobs\\\\ExportLargeData\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ExportLargeData\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\ExportLargeData\\\":11:{s:7:\\\"batchId\\\";N;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}',0,NULL,1714418936,1714418936),(10,'default','{\"uuid\":\"b2e08f70-cb39-4706-ab93-f3956c9a6c73\",\"displayName\":\"App\\\\Jobs\\\\ExportLargeData\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ExportLargeData\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\ExportLargeData\\\":11:{s:7:\\\"batchId\\\";N;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}',0,NULL,1714418948,1714418948),(11,'default','{\"uuid\":\"0258f906-c195-41f0-a76f-2682ccde09f2\",\"displayName\":\"App\\\\Jobs\\\\ExportLargeData\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ExportLargeData\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\ExportLargeData\\\":11:{s:7:\\\"batchId\\\";N;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}',0,NULL,1714418952,1714418952),(12,'default','{\"uuid\":\"1fbde67d-3348-4630-b1c5-7eb324c8e250\",\"displayName\":\"App\\\\Jobs\\\\ExportLargeData\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ExportLargeData\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\ExportLargeData\\\":11:{s:7:\\\"batchId\\\";N;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}',0,NULL,1714856551,1714856551);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `keywords`
--

DROP TABLE IF EXISTS `keywords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `keywords` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `keywords` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Inactive','Active') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `keywords`
--

LOCK TABLES `keywords` WRITE;
/*!40000 ALTER TABLE `keywords` DISABLE KEYS */;
INSERT INTO `keywords` VALUES (3,3,'sdsdad','tag1,tag2,tag3','Active','2024-10-23 14:55:30','2024-10-23 14:55:30'),(4,3,'hggjg','cc,ddd','Active','2024-10-24 14:35:15','2024-10-24 14:35:15'),(5,3,'dgfg','ff,fff,ggg,dddd,dfdssssdfvv','Active','2024-10-24 14:37:00','2024-10-24 14:48:46');
/*!40000 ALTER TABLE `keywords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `logs` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `type` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `outbox_id` bigint DEFAULT NULL,
  `sentmessage_id` bigint DEFAULT NULL,
  `mobile_no` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int NOT NULL DEFAULT '0',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active_route` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_no` int NOT NULL,
  `status` enum('Active','Inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `user_group_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (1,0,'Menus',NULL,'menu',2,'Active','1','feather icon-box',NULL,'2022-06-12 09:40:06'),(2,1,'Menu List','menu.list','menu',1,'Active','1',NULL,NULL,'2021-01-24 12:15:23'),(3,1,'Menu Add','menu.add','menu',2,'Active','1,2','feather icon-box',NULL,'2021-01-02 12:48:16'),(4,0,'Dashboard','dashboard','dashboard',1,'Active','1,2,3,4','feather icon-home','2021-01-01 12:58:07','2021-01-19 16:40:14'),(5,0,'Setting','setting','setting',13,'Active','1,2','feather icon-settings','2021-01-02 13:23:22','2022-12-02 06:43:05'),(6,0,'Resellers',NULL,'reseller',7,'Active','1,2','feather icon-user-plus','2021-01-02 17:31:16','2022-06-22 19:36:50'),(7,6,'Reseller List','reseller.list','reseller',1,'Active','1,2',NULL,'2021-01-02 17:39:00','2022-06-21 22:46:44'),(8,6,'New Reseller','reseller.add','reseller',2,'Active','1,2',NULL,'2021-01-02 17:54:03','2022-06-27 18:06:35'),(9,0,'User Groups',NULL,'usergroup',9,'Active','1','feather icon-user-check','2021-01-02 19:30:32','2021-01-17 22:48:13'),(10,9,'User Group List','usergroup.list','usergroup',1,'Active','1,2',NULL,'2021-01-02 19:31:41','2021-01-03 11:57:01'),(11,9,'New User Group','usergroup.add','usergroup',2,'Active','1,2',NULL,'2021-01-02 20:41:10','2022-06-27 18:07:04'),(12,0,'Users',NULL,'user',8,'Active','1,2,3','feather icon-users','2021-01-03 17:13:59','2021-01-17 22:46:13'),(13,12,'User List','user.list','user',1,'Active','1,2,3','feather icon-phone','2021-01-04 12:05:33','2021-01-24 12:14:57'),(14,12,'New User','user.add','user',2,'Active','1,2,3',NULL,'2021-01-04 12:06:12','2022-06-27 18:07:28'),(15,0,'Rates',NULL,'rate',10,'Active','1,2,3','feather icon-trending-up','2021-01-14 13:28:56','2022-06-21 13:59:26'),(16,15,'Rate List','rate.list','rate',1,'Active','1,2,3',NULL,'2021-01-14 13:29:43','2022-06-22 19:37:32'),(17,15,'New Rate','rate.add','rate',2,'Active','1,2,3',NULL,'2021-01-14 13:30:23','2022-06-27 18:07:43'),(18,0,'Sms Config',NULL,'smsconfig',3,'Active','1','feather icon-package','2021-01-17 22:36:07','2021-01-17 22:36:07'),(19,0,'Phone Book',NULL,'phonebook',4,'Active','1,2,3,4','feather icon-phone','2021-01-17 22:38:45','2021-01-17 22:38:45'),(20,0,'Messaging',NULL,'message',5,'Active','1,2,3,4','feather icon-message-square','2021-01-17 22:39:55','2021-01-17 22:40:17'),(21,0,'All Report',NULL,'report',6,'Active','1,2,3,4','feather icon-file-text','2021-01-17 22:41:40','2021-01-17 22:41:40'),(22,0,'SMS Template','template.list','template',11,'Active','1,2,3,4','feather icon-file','2021-01-17 22:43:49','2021-01-21 12:25:39'),(23,0,'Countries','country.list','country',10,'Active','1,2','feather icon-flag','2021-01-18 14:01:17','2022-06-12 09:32:49'),(24,18,'Operators','smsconfig.operator.list','smsconfig.operator',2,'Active','1',NULL,'2021-01-18 15:24:13','2021-01-20 17:13:16'),(25,18,'Service Provider','smsconfig.serviceprovider.list','smsconfig.serviceprovider',3,'Active','1',NULL,'2021-01-18 17:14:27','2021-01-20 17:13:23'),(26,18,'Routes','smsconfig.route.list','smsconfig.route',4,'Active','1',NULL,'2021-01-19 11:25:07','2021-01-20 17:13:29'),(27,0,'Config',NULL,'config',12,'Active','1,2,3,4','feather icon-globe','2021-01-19 15:33:51','2022-06-12 09:44:30'),(28,19,'Groups','phonebook.group.list','phonebook.group',1,'Active','1,2,3,4',NULL,'2021-01-19 16:52:58','2021-01-20 17:25:27'),(29,19,'Contacts','phonebook.contact.list','phonebook.contact',2,'Active','1,2,3,4',NULL,'2021-01-19 19:46:52','2021-01-20 17:25:07'),(30,27,'Sender ID','config.senderid.list','config.senderid',1,'Active','1,2,3,4',NULL,'2021-01-20 18:22:44','2022-08-09 17:34:39'),(32,20,'Campaign List','message.list','message',4,'Inactive','1,2,3,4',NULL,'2021-01-21 15:49:06','2022-09-26 13:54:39'),(33,20,'Send Non Masking SMS','message.add','message',1,'Active','1,2,3,4',NULL,'2021-01-22 12:57:25','2021-01-25 18:16:28'),(34,35,'Wallet (Users)','balance.wallet.user.list','balance',1,'Active','1,2,3,4',NULL,'2021-01-24 14:19:19','2022-08-09 18:50:36'),(35,0,'Transactions',NULL,'balance',3,'Active','1,2,3,4','feather icon-credit-card','2021-01-24 19:58:03','2022-08-09 18:51:12'),(36,35,'Reseller Balance Transfer List','balance.transfer.list.reseller','balance',5,'Active','1,2',NULL,'2021-01-24 20:05:36','2022-06-27 18:10:05'),(37,35,'Add User Balance','balance.add.user','balance',3,'Active','1,2,3',NULL,'2021-01-24 20:07:00','2022-06-27 18:08:39'),(38,35,'Wallet(Resellers)','balance.wallet.reseller.list','balance',4,'Active','1,2',NULL,'2021-01-24 20:12:11','2022-06-27 18:09:31'),(39,35,'Add Reseller Balance','balance.add.reseller','balance',6,'Active','1,2',NULL,'2021-01-24 20:15:16','2022-06-27 18:08:51'),(40,35,'User Balance Transfer List','balance.transfer.list.user','balance',2,'Active','1,2,3,4',NULL,'2021-01-24 23:23:20','2022-08-09 18:50:42'),(41,20,'Send Masking SMS','message.add.masking','message',2,'Inactive','1,2,3,4',NULL,'2021-01-25 18:17:03','2022-06-26 22:26:40'),(42,20,'Dynamic Massage','message.add.dynamic','message',3,'Active','1,2,3,4',NULL,'2021-01-28 11:05:46','2021-01-28 11:05:58'),(43,27,'Developers','config.create.api','config',3,'Active','1,2,3,4',NULL,'2021-01-28 11:05:46','2022-06-27 18:11:03'),(45,21,'All Outbox','report.list','report',0,'Inactive','1,2,3,4',NULL,'2022-06-13 22:53:06','2022-09-27 20:11:02'),(48,21,'Two Days Failed SMS','report.failed.list','report',1,'Active','1,2,3,4',NULL,'2022-06-13 22:53:25','2022-11-21 18:33:59'),(51,21,'Summary','report.sms.count','report',2,'Inactive','1,2,3,4',NULL,'2022-06-13 22:53:56','2022-09-27 13:40:55'),(54,0,'Black Listed Keywords','keywords.index','keywords',28,'Active','1,2','feather icon-box','2022-06-28 11:23:44','2022-08-21 19:30:10'),(57,12,'Inactive users','user.list.inactive','user',2,'Active','1,2,3',NULL,'2022-06-28 13:53:42','2022-06-28 13:53:42'),(60,21,'View Today\'s DLR','report.viewTodayDLR','report',4,'Inactive','1,2,3,4',NULL,'2022-07-30 17:54:26','2022-09-21 18:55:40'),(63,21,'Two Days Report Details','report.viewDetails','report',5,'Active','1,2,3,4',NULL,'2022-07-30 17:54:50','2022-11-21 18:34:28'),(66,21,'View Archived DLR','report.viewArchivedDLR','report',6,'Inactive','1,2,3,4',NULL,'2022-07-30 17:55:05','2022-09-21 18:57:27'),(69,21,'Campaign Today\'s DLR','report.campaignTodayDLR','report',7,'Inactive','1,2,3,4',NULL,'2022-07-30 18:56:47','2022-09-21 18:57:32'),(72,21,'Campaign Archived DLR','report.campaignArchivedDLR','report',8,'Inactive','1,2,3,4',NULL,'2022-07-30 18:57:08','2022-09-21 18:57:37'),(75,21,'Summary Report','report.statisticsSummeryLogs','report',9,'Active','1,2,3,4',NULL,'2022-08-07 22:13:28','2022-09-28 13:27:15'),(78,21,'Statistics Details Logs','report.statisticsDetailsLogs','report',10,'Inactive','1,2,3,4',NULL,'2022-08-07 22:13:57','2022-09-26 16:31:17'),(81,21,'Day Wise Log','report.statisticsDayWiseLogs','report',11,'Active','1,2,3,4',NULL,'2022-08-07 22:14:20','2022-09-26 16:29:41'),(84,21,'Total SMS Sent','report.statisticsApiSmsPurpose','report',12,'Active','1,2,3,4',NULL,'2022-08-07 22:14:42','2022-09-27 13:58:25'),(87,21,'Scheduled SMS','report.scheduledSms','report',13,'Inactive','1,2,3,4',NULL,'2022-08-07 22:15:02','2022-09-26 16:27:27'),(90,21,'Transaction','report.transactions','report',14,'Inactive','1,2,3,4',NULL,'2022-08-07 22:15:30','2022-09-26 16:27:23'),(91,20,'Inbox','message.inbox','message',5,'Active','1,2,3,4',NULL,'2022-09-15 16:13:42','2022-09-16 12:57:32'),(92,0,'Campaign',NULL,'campaign',5,'Active','1,2,3,4','feather icon-message-square','2022-09-25 19:50:00','2022-09-25 19:52:05'),(93,92,'Schedule','campaign.schedule.list','campaign',1,'Active','1,2,3,4',NULL,'2022-09-25 19:50:32','2022-09-25 19:50:32'),(94,92,'Last Two days','campaign.running.list','campaign',2,'Active','1,2,3,4',NULL,'2022-09-25 19:50:54','2022-11-14 05:17:42'),(95,92,'Archived','campaign.archive.list','campaign',3,'Active','1,2,3,4',NULL,'2022-09-25 19:51:13','2022-09-25 19:51:13'),(96,21,'Failed SMS Archived','report.failed.archived','report',3,'Active','1,2,3,4',NULL,'2022-10-18 15:10:52','2022-10-18 15:10:52'),(97,21,'Report Details Archived','report.viewDetailsArchived','report',5,'Active','1,2,3,4',NULL,'2022-10-18 15:11:41','2022-10-18 15:11:41'),(98,19,'DND','phonebook.dnd.list','phonebook',3,'Active','1,2,3,4',NULL,'2022-11-20 18:43:40','2022-11-20 18:43:40');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=181 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (3,'2020_12_27_052546_create_resellers_table',1),(6,'2020_12_27_135701_create_user_group_table',1),(9,'2020_12_27_135710_create_user_table',1),(12,'2020_12_27_135715_create_password_resets_table',1),(15,'2020_12_28_092024_create_rates_table',1),(18,'2020_12_28_092126_create_routes_table',1),(21,'2020_12_28_092153_create_sentmessages_table',1),(24,'2020_12_28_092422_create_channel_table',1),(27,'2020_12_28_092457_create_country_table',1),(30,'2020_12_28_092537_create_deposit_history_table',1),(33,'2020_12_28_092615_create_group_table',1),(36,'2020_12_28_092643_create_contacts_table',1),(39,'2020_12_28_092701_create_menus_table',1),(42,'2020_12_28_092736_create_operator_table',1),(45,'2020_12_28_092808_create_outbox_table',1),(48,'2020_12_28_092943_create_senderid_table',1),(51,'2020_12_28_093023_create_template_table',1),(54,'2020_12_28_093109_create_user_wallet_table',1),(57,'2020_12_31_070634_create_configs_table',1),(60,'2021_04_21_081801_create_contact_submits_table',1),(63,'2021_06_28_054409_create_sent_emails_table',1),(66,'2021_06_28_061051_create_domains_table',1),(69,'2021_07_01_164935_create_buy_bundles_table',1),(72,'2021_07_12_002710_create_alert_table',1),(75,'2021_07_18_010305_rename_config_table',1),(78,'2021_07_18_112442_alter_table_sent_emails_change_email_type',1),(81,'2021_07_26_053217_create_email_service_providers_table',1),(84,'2021_07_26_053416_create_email_routes_table',1),(87,'2021_08_07_100229_alter_table_sent_emails',1),(90,'2021_08_07_100912_create_email_logs_table',1),(93,'2021_08_07_101959_create_email_templates_table',1),(96,'2021_08_09_045140_add_status_remarks_to_email_logs_table',1),(99,'2021_08_10_065241_alter_column_type_user_wallet',1),(102,'2021_08_10_071052_add_batch_to_email_log',1),(105,'2021_08_12_035913_alter_table_email_service_providers_change_provider_type',1),(108,'2021_08_23_085246_add_subscriber_info',1),(111,'2021_09_14_041722_create_invoices_table',1),(114,'2021_10_28_060358_alter_table_sent_emails_change_status',1),(117,'2021_11_03_124240_alter_sent_mail_update_search_param_length',1),(120,'2021_11_03_124306_alter_sent_messages_update_search_param_length',1),(123,'2021_11_14_063008_alter_table_sentmessages_change_status',1),(126,'2022_05_08_092615_create_keyword_table',1),(129,'2022_05_12_152146_create_failed_jobs_table',1),(132,'2022_05_13_140037_create_job_batches_table',1),(135,'2022_05_13_140102_create_jobs_table',1),(138,'2022_05_31_143512_add_error_code_and_error_message_to_outbox_table',1),(141,'2022_06_23_065241_alter_column_expire_date_user_wallet',2),(144,'2022_06_23_065242_alter_column_expire_date_depositHistory',2),(147,'2022_06_25_065241_alter_column_assigned_user_id_senderid',3),(150,'2022_06_26_065241_alter_column_to_sentmessages',4),(153,'2022_08_08_065241_alter_column_to_outbox',5),(156,'2022_08_08_065241_alter_column_to_sentmessages',5),(157,'2022_08_15_092808_create_inbox_table',6),(159,'2022_09_01_065241_alter_column_to_outbox',6),(161,'2022_09_01_065241_alter_column_to_campaign_table',7),(163,'2022_09_01_092808_create_outbox_history_table',1),(165,'2022_09_01_093241_alter_is_complete_to_campaign_table',8),(166,'2022_09_05_093241_alter_name_en_null_to_contacts_table',9),(167,'2022_09_12_093241_alter_add_is_pause_column_sentMessages_table',10),(168,'2022_09_17_110720_add_dr_cr_to_deposit_history_table',11),(169,'2022_09_20_130319_add_total_recipient_total_cost_to_sentmessages_table',12),(170,'2022_10_01_161402_add_push_pull_url_to_user_table',13),(171,'2022_10_01_180253_create_sentmessage_status_table',13),(172,'2022_10_16_072153_add_dlr_url_to_user_table',13),(173,'2022_11_19_143634_create_dnds_table',14),(174,'2022_11_19_144103_add_is_dnd_applicable_to_sentmessages_table',14),(175,'2023_07_24_191201_add_iptsp_field_to_sentmessages_table',15),(176,'2023_07_29_184230_add_sms_uniq_id_to_outbox',15),(177,'2023_08_05_173506_add_is_promotional_to_sentmessages_table',15),(178,'2023_08_31_182256_alter_source_column_to_sentmessages_table',16),(179,'2023_09_10_150128_alter_status_column_to_sentmessages_table',17),(180,'0001_01_01_000001_create_cache_table',18);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `operator`
--

DROP TABLE IF EXISTS `operator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `operator` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` int NOT NULL,
  `ton` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `npi` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `operator`
--

LOCK TABLES `operator` WRITE;
/*!40000 ALTER TABLE `operator` DISABLE KEYS */;
INSERT INTO `operator` VALUES (3,'CCCCCCC','dfsdf','44',6,'5','5','2024-09-15 01:47:50','2024-09-15 01:49:01'),(4,'xcvxv','vvv','GP',6,'6','6','2024-09-15 01:48:37','2024-09-15 01:48:37'),(5,'qweqw','eee','ee',4,'5','5','2024-09-18 22:36:22','2024-09-18 22:36:22'),(6,'asdada','dd','d',5,'5','5','2024-09-18 22:37:18','2024-09-18 22:37:18'),(7,'asdasdads','dsfsdfsdf','ff',4,'5','5','2024-10-18 08:47:39','2024-10-18 08:47:39');
/*!40000 ALTER TABLE `operator` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `outbox`
--

DROP TABLE IF EXISTS `outbox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `outbox` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `srcmn` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mask` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destmn` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `country_code` int DEFAULT NULL,
  `operator_prefix` int DEFAULT NULL,
  `status` enum('Failed','Delivered','Sent','Processing','Queue','Hold') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Queue',
  `write_time` datetime DEFAULT NULL,
  `sent_time` datetime DEFAULT NULL,
  `ton` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `npi` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message_type` enum('text','binary','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'text',
  `is_unicode` tinyint NOT NULL,
  `smscount` int DEFAULT NULL,
  `esm_class` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_coding` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` int DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL,
  `schedule_time` datetime DEFAULT NULL,
  `retry_count` int DEFAULT NULL,
  `user_id` bigint NOT NULL,
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `uuid` varbinary(16) DEFAULT NULL,
  `priority` int DEFAULT '0',
  `blocked_status` enum('1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '1 = blocked and 2 = unblocked',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `error_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `error_message` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_cost` decimal(10,6) DEFAULT NULL,
  `sms_uniq_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`,`user_id`),
  UNIQUE KEY `outbox_uuid_unique` (`uuid`),
  KEY `status_write_time_schedule_time` (`status`,`write_time`,`schedule_time`),
  KEY `user_id` (`user_id`),
  KEY `reference_id` (`reference_id`),
  KEY `created_at` (`created_at`),
  KEY `smscount` (`smscount`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `outbox`
--

LOCK TABLES `outbox` WRITE;
/*!40000 ALTER TABLE `outbox` DISABLE KEYS */;
INSERT INTO `outbox` VALUES (1,'2222222222222','2222222222222','23132132323','sdfsfsfsdf',NULL,0,'Queue','2024-10-25 00:32:00',NULL,'5','1','text',0,0,'','',1,'2024-10-25 00:32:00',NULL,0,3,'',_binary '\Ôüä%@l£aª±\«4/',9,NULL,'2024-10-24 18:32:03','2024-10-24 18:32:03',NULL,NULL,0.000000,'RIB 20241025123203-23132132323-0000000001'),(2,'2222222222222','2222222222222','01799855302','dfsdfsdfsdfsdfs',NULL,17,'Queue','2024-10-25 01:37:25',NULL,'5','1','text',0,1,'','',7,'2024-10-25 01:37:25',NULL,0,3,'',_binary 'àèÇ2¿iGØáìà\‚#\À6è',9,NULL,'2024-10-24 19:37:25','2024-10-24 19:37:25',NULL,NULL,0.000000,'RIB 20241025013725-01799855302-0000000001'),(3,'2222222222222','2222222222222','01712488651','dfsdfsdfsdfsdfs',NULL,0,'Queue','2024-10-25 01:37:25',NULL,'5','1','text',0,1,'','',7,'2024-10-25 01:37:25',NULL,0,3,'',_binary 'Ω|∫\·KMæ\‹\'ﬁã\’»à',9,NULL,'2024-10-24 19:37:25','2024-10-24 19:37:25',NULL,NULL,0.000000,'RIB 20241025013725-01712488651-0000000001'),(4,'2222222222222','2222222222222','2342342342','dfsdfsdfsdfsdfs',NULL,0,'Queue','2024-10-25 01:37:25',NULL,'5','1','text',0,1,'','',7,'2024-10-25 01:37:25',NULL,0,3,'',_binary 'ä2\«\”>\ıK\ÚØ>6\≈2®D',9,NULL,'2024-10-24 19:37:25','2024-10-24 19:37:25',NULL,NULL,0.000000,'RIB 20241025013725-2342342342-0000000001'),(5,'2222222222222','2222222222222','01712488651','dfsdfsdfsdfsdfs',NULL,0,'Queue','2024-10-25 01:37:25',NULL,'5','1','text',0,1,'','',7,'2024-10-25 01:37:25',NULL,0,3,'',_binary '\‹\„˘frDﬁΩ•\◊o\Õ˝\r\‰',9,NULL,'2024-10-24 19:37:25','2024-10-24 19:37:25',NULL,NULL,0.000000,'RIB 20241025013725-01712488651-0000000001'),(6,'2222222222222','2222222222222','345345345353535','sdfsfsfsdf',NULL,0,'Queue','2024-10-25 03:01:09',NULL,'5','1','text',0,0,'','',10,'2024-10-25 03:01:09',NULL,0,3,'',_binary 'âÄlDµà\0¥w˘',9,NULL,'2024-10-24 21:01:09','2024-10-24 21:01:09',NULL,NULL,0.000000,'RIB 20241025030109-345345345353535-0000000001'),(7,'2222222222222','2222222222222','3223434424','sdfsfsfsdf',NULL,0,'Queue','2024-10-25 03:15:11',NULL,'5','1','text',0,0,'','',13,'2024-10-25 03:15:11',NULL,0,3,'',_binary 'O\ËJ\Ì£F#¶{W\Ë\‘A',9,NULL,'2024-10-24 21:15:11','2024-10-24 21:15:11',NULL,NULL,0.000000,'RIB 20241025031511-3223434424-0000000001'),(8,'2222222222222','2222222222222','345434534','sdfsfsfsdf',NULL,0,'Queue','2024-10-25 03:15:58',NULL,'5','1','text',0,0,'','',14,'2024-10-25 03:15:58',NULL,0,3,'',_binary 'U\÷-8V\ÈK«ò\Úeæ¥&[',9,NULL,'2024-10-24 21:15:58','2024-10-24 21:15:58',NULL,NULL,0.000000,'RIB 20241025031558-345434534-0000000001'),(9,'2222222222222','2222222222222','3453453535534','sdfsfsfsdf',NULL,0,'Queue','2024-10-25 03:19:19',NULL,'5','1','text',0,0,'','',16,'2024-10-25 03:19:19',NULL,0,3,'',_binary 'µ-˝Ü∑aM\Úì3ë~BøªÄ',9,NULL,'2024-10-24 21:19:19','2024-10-24 21:19:19',NULL,NULL,0.000000,'RIB 20241025031919-3453453535534-0000000001'),(10,'2222222222222','2222222222222','353453553453','sdfsfsfsdf',NULL,0,'Queue','2024-10-25 03:20:17',NULL,'5','1','text',0,0,'','',17,'2024-10-25 03:20:17',NULL,0,3,'',_binary 'á{]¡PG√ÜtV∂\Zƒò',9,NULL,'2024-10-24 21:20:17','2024-10-24 21:20:17',NULL,NULL,0.000000,'RIB 20241025032017-353453553453-0000000001'),(11,'2222222222222','2222222222222','34534234234242','sdfsfsfsdf',NULL,0,'Queue','2024-10-25 03:21:04',NULL,'5','1','text',0,0,'','',18,'2024-10-25 03:21:04',NULL,0,3,'',_binary 'æ\Âc8i£KÎôìøá2HÇA',9,NULL,'2024-10-24 21:21:04','2024-10-24 21:21:04',NULL,NULL,0.000000,'RIB 20241025032104-34534234234242-0000000001'),(12,'2222222222222','2222222222222','01903341643','sdfsfsfsdf',NULL,19,'Queue','2024-11-11 01:07:53',NULL,'5','1','text',0,1,'','',21,'2024-11-11 01:07:53',NULL,0,3,'',_binary '\Ê0ù\ÁV[@\‰£\˜›¶]¿˛9',9,NULL,'2024-11-10 19:07:54','2024-11-10 19:07:54',NULL,NULL,0.000000,'RIB 20241111010754-01903341643-0000000001'),(13,'2222222222222','2222222222222','01903341643','sdfsfsfsdf',NULL,19,'Queue','2024-11-11 01:09:24',NULL,'5','1','text',0,2,'','',22,'2024-11-11 01:09:24',NULL,0,3,'',_binary '≥oûãyG3ñocí ¸Ce',9,NULL,'2024-11-10 19:09:24','2024-11-10 19:09:24',NULL,NULL,0.000000,'RIB 20241111010924-01903341643-0000000001'),(14,'2222222222222','2222222222222','01734183130','sdfsfsfsdf',NULL,17,'Queue','2024-11-11 01:09:24',NULL,'5','1','text',0,2,'','',22,'2024-11-11 01:09:24',NULL,0,3,'',_binary '5@8-Y¥Fﬂêòp!ô\Íj\Ë',9,NULL,'2024-11-10 19:09:24','2024-11-10 19:09:24',NULL,NULL,0.000000,'RIB 20241111010924-01734183130-0000000001'),(15,'2222222222222','2222222222222','01903341643','sdfsfsfsdf',NULL,19,'Queue','2024-11-11 01:10:08',NULL,'5','1','text',0,2,'','',23,'2024-11-11 01:10:08',NULL,0,3,'',_binary '˙ê\‡YWK{â\n,¢IU0\—',9,NULL,'2024-11-10 19:10:08','2024-11-10 19:10:08',NULL,NULL,0.000000,'RIB 20241111011008-01903341643-0000000001'),(16,'2222222222222','2222222222222','01734183130','sdfsfsfsdf',NULL,17,'Queue','2024-11-11 01:10:08',NULL,'5','1','text',0,2,'','',23,'2024-11-11 01:10:08',NULL,0,3,'',_binary 'w6-EFwJà™r£\≈\ﬁc',9,NULL,'2024-11-10 19:10:08','2024-11-10 19:10:08',NULL,NULL,0.000000,'RIB 20241111011008-01734183130-0000000001'),(17,'2222222222222','2222222222222','01903341643','sdfsfsfsdf',NULL,19,'Queue','2024-11-11 01:10:43',NULL,'5','1','text',0,2,'','',24,'2024-11-11 01:10:43',NULL,0,3,'',_binary '¨\‡EGNÄEåd&\ÏÅBˇù',9,NULL,'2024-11-10 19:10:43','2024-11-10 19:10:43',NULL,NULL,0.000000,'RIB 20241111011043-01903341643-0000000001'),(18,'2222222222222','2222222222222','01734183130','sdfsfsfsdf',NULL,17,'Queue','2024-11-11 01:10:43',NULL,'5','1','text',0,2,'','',24,'2024-11-11 01:10:43',NULL,0,3,'',_binary 'GlÎêç8Nn§^21t¯o£',9,NULL,'2024-11-10 19:10:43','2024-11-10 19:10:43',NULL,NULL,0.000000,'RIB 20241111011043-01734183130-0000000001'),(19,'2222222222222','2222222222222','01903341643','sdfsfsfsdf',NULL,19,'Queue','2024-11-11 01:11:05',NULL,'5','1','text',0,2,'','',25,'2024-11-11 01:11:05',NULL,0,3,'',_binary 'pi\ \«UK8òΩÉ\‚Q\÷HX',9,NULL,'2024-11-10 19:11:05','2024-11-10 19:11:05',NULL,NULL,0.000000,'RIB 20241111011105-01903341643-0000000001'),(20,'2222222222222','2222222222222','01734183130','sdfsfsfsdf',NULL,17,'Queue','2024-11-11 01:11:05',NULL,'5','1','text',0,2,'','',25,'2024-11-11 01:11:05',NULL,0,3,'',_binary '¢ﬂß§íE≥àpBSªí\¬',9,NULL,'2024-11-10 19:11:05','2024-11-10 19:11:05',NULL,NULL,0.000000,'RIB 20241111011105-01734183130-0000000001'),(21,'2222222222222','2222222222222','01903341643','sdfsfsfsdf',NULL,19,'Queue','2024-11-11 01:11:16',NULL,'5','1','text',0,2,'','',26,'2024-11-11 01:11:16',NULL,0,3,'',_binary 'ë\"I\≈Há©A}B2',9,NULL,'2024-11-10 19:11:16','2024-11-10 19:11:16',NULL,NULL,0.000000,'RIB 20241111011116-01903341643-0000000001'),(22,'2222222222222','2222222222222','01734183130','sdfsfsfsdf',NULL,17,'Queue','2024-11-11 01:11:16',NULL,'5','1','text',0,2,'','',26,'2024-11-11 01:11:16',NULL,0,3,'',_binary '¯\⁄ÕÜ\Ã\ŸCæà)™[\—2ø',9,NULL,'2024-11-10 19:11:16','2024-11-10 19:11:16',NULL,NULL,0.000000,'RIB 20241111011116-01734183130-0000000001'),(23,'2222222222222','2222222222222','01903341643','sdfsfsfsdf',NULL,19,'Queue','2024-11-11 01:11:40',NULL,'5','1','text',0,2,'','',27,'2024-11-11 01:11:40',NULL,0,3,'',_binary 'EÔ≠Ç˙,A4¢\ı\ˆˇW2{',9,NULL,'2024-11-10 19:11:40','2024-11-10 19:11:40',NULL,NULL,0.000000,'RIB 20241111011140-01903341643-0000000001'),(24,'2222222222222','2222222222222','01734183130','sdfsfsfsdf',NULL,17,'Queue','2024-11-11 01:11:40',NULL,'5','1','text',0,2,'','',27,'2024-11-11 01:11:40',NULL,0,3,'',_binary ',ÉΩtêgKhã\ÈKù<OQ',9,NULL,'2024-11-10 19:11:40','2024-11-10 19:11:40',NULL,NULL,0.000000,'RIB 20241111011140-01734183130-0000000001'),(25,'2222222222222','2222222222222','01734183130','sdfsfsfsdf',NULL,17,'Queue','2024-11-11 01:13:22',NULL,'5','1','text',0,2,'','',28,'2024-11-11 01:13:22',NULL,0,3,'',_binary '∏Fõ¨í\ËA©´ãµR\Â:U',9,NULL,'2024-11-10 19:13:22','2024-11-10 19:13:22',NULL,NULL,0.000000,'RIB 20241111011322-01734183130-0000000001'),(26,'2222222222222','2222222222222','01903341643','sdfsfsfsdf',NULL,19,'Queue','2024-11-11 01:13:22',NULL,'5','1','text',0,2,'','',28,'2024-11-11 01:13:22',NULL,0,3,'',_binary 'õ4\Ì&±˘N(Ø/ï.tn\Ù',9,NULL,'2024-11-10 19:13:22','2024-11-10 19:13:22',NULL,NULL,0.000000,'RIB 20241111011322-01903341643-0000000001'),(27,'16555','16555','01629334432','This regular test message',NULL,18,'Queue','2024-11-21 17:00:14',NULL,'5','1','text',0,2,'','',61,'2024-11-21 17:00:14',NULL,0,3,'',_binary 'Ø-˚™J`à_\Õ\ÃÿÄ\’\€',9,NULL,'2024-11-21 17:00:14','2024-11-21 17:00:14',NULL,NULL,0.000000,'RIB 20241121050014-01629334432-0000000001'),(28,'16555','16555','01734183130','This regular test message',NULL,17,'Queue','2024-11-21 17:00:14',NULL,'5','1','text',0,2,'','',61,'2024-11-21 17:00:14',NULL,0,3,'',_binary '\€M\·2êßGåªog¡?\Ûy',9,NULL,'2024-11-21 17:00:14','2024-11-21 17:00:14',NULL,NULL,0.000000,'RIB 20241121050014-01734183130-0000000001'),(29,'16555','16555','8801629334432','Test SMS',NULL,18,'Queue','2024-11-21 18:09:26',NULL,'5','1','text',0,1,'','',62,'2024-11-21 18:09:26',NULL,0,3,'',_binary '\0v\—{+\“E-óG§DrΩ˝6',9,NULL,'2024-11-21 18:09:26','2024-11-21 18:09:26',NULL,NULL,0.000000,'RIB 20241121060926-8801629334432-0000000001'),(30,NULL,NULL,'8801629334432','Test sms',NULL,18,'Queue','2024-11-22 12:18:20',NULL,'5','1','text',0,1,'','',63,'2024-11-22 12:18:20',NULL,0,3,'',_binary 'æpÄ\Î…âBª∞<ä\Ë#˘ôë',9,NULL,'2024-11-22 12:18:20','2024-11-22 12:18:20',NULL,NULL,0.000000,'RIB 20241122121820-8801629334432-0000000001'),(31,'2222222222222','2222222222222','01734183130','Test sms',NULL,17,'Queue','2024-11-30 16:06:25',NULL,'5','1','text',0,1,'','',64,'2024-11-30 16:06:25',NULL,0,3,'',_binary '\ÁÑ!πä\ŒI∞ø|\MÜ\√',9,NULL,'2024-11-30 10:06:25','2024-11-30 10:06:25',NULL,NULL,0.000000,'RIB 20241130040625-01734183130-0000000001'),(32,'2222222222222','2222222222222','01734183130','Test sms',NULL,17,'Queue','2024-11-30 16:11:00',NULL,'5','1','text',0,1,'','',65,'2024-11-30 16:11:00',NULL,0,3,'',_binary '<7Ú∞ìéM2§i˘\≈T\≈\Ìµ',9,NULL,'2024-11-30 10:11:00','2024-11-30 10:11:00',NULL,NULL,0.000000,'RIB 20241130041101-01734183130-0000000001'),(33,'2222222222222','2222222222222','01734183130','Test sms',NULL,17,'Queue','2024-11-30 16:14:45',NULL,'5','1','text',0,1,'','',67,'2024-11-30 16:14:45',NULL,0,3,'',_binary '\ÒtU\”qM›óU¡ÆE\·Ñ\Œ',9,NULL,'2024-11-30 10:14:45','2024-11-30 10:14:45',NULL,NULL,0.000000,'RIB 20241130041445-01734183130-0000000001'),(34,'2222222222222','2222222222222','01734183130','AsASAsaS',NULL,17,'Queue','2024-11-30 16:19:54',NULL,'5','1','text',0,1,'','',68,'2024-11-30 16:19:54',NULL,0,3,'',_binary '\‘\uT\ˆLøùÖG:ê1Æ',9,NULL,'2024-11-30 10:19:54','2024-11-30 10:19:54',NULL,NULL,0.000000,'RIB 20241130041954-01734183130-0000000001'),(35,'2222222222222','2222222222222','01734183130','Test sms',NULL,17,'Queue','2024-11-30 16:35:32',NULL,'5','1','text',0,1,'','',69,'2024-11-30 16:35:32',NULL,0,3,'',_binary 'Æ^\03@nöe.\›uAáâ',9,NULL,'2024-11-30 10:35:32','2024-11-30 10:35:32',NULL,NULL,0.360000,'RIB 20241130043532-01734183130-0000000001'),(36,'2222222222222','2222222222222','000000000','AsASAsaS',NULL,0,'Queue','2024-11-30 16:35:57',NULL,'5','1','text',0,0,'','',70,'2024-11-30 16:35:57',NULL,0,3,'',_binary '\’\ˆx@RëBî®m◊Ø',9,NULL,'2024-11-30 10:35:57','2024-11-30 10:35:57',NULL,NULL,0.360000,'RIB 20241130043557-000000000-0000000001'),(37,'2222222222222','2222222222222','2112122','Test sms',NULL,0,'Queue','2024-11-30 17:07:42',NULL,'5','1','text',0,0,'','',71,'2024-11-30 17:07:42',NULL,0,3,'',_binary 'pc\¬Z ÉIõ°B˘èPâ\€',9,NULL,'2024-11-30 11:07:42','2024-11-30 11:07:42',NULL,NULL,0.360000,'RIB 20241130050742-2112122-0000000001'),(38,'2222222222222','2222222222222','','Test sms',NULL,0,'Queue','2024-11-30 17:15:21',NULL,'5','1','text',0,0,'','',72,'2024-11-30 17:15:21',NULL,0,3,'',_binary 'é\‚Eå1O~å/\”\0D\«',9,NULL,'2024-11-30 11:15:21','2024-11-30 11:15:21',NULL,NULL,0.360000,'RIB 20241130051521--0000000001'),(39,'2222222222222','2222222222222','01629333222','AsASAsaS',NULL,18,'Queue','2024-11-30 17:17:32',NULL,'5','1','text',0,1,'','',73,'2024-11-30 17:17:32',NULL,0,3,'',_binary 'ºY\Ùs6EAìøØ˛bl',9,NULL,'2024-11-30 11:17:32','2024-11-30 11:17:32',NULL,NULL,0.360000,'RIB 20241130051732-01629333222-0000000001'),(40,'2222222222222','2222222222222','01734183130','AsASAsaS',NULL,0,'Queue','2024-11-30 17:17:32',NULL,'5','1','text',0,1,'','',73,'2024-11-30 17:17:32',NULL,0,3,'',_binary '—Ω∏˛JëMã\ÿQ†\Ó',9,NULL,'2024-11-30 11:17:32','2024-11-30 11:17:32',NULL,NULL,0.360000,'RIB 20241130051732-01734183130-0000000001'),(41,'2222222222222','2222222222222','01734183131','AsASAsaS',NULL,0,'Queue','2024-11-30 17:17:32',NULL,'5','1','text',0,1,'','',73,'2024-11-30 17:17:32',NULL,0,3,'',_binary '\◊\”rëU¶E˛∂\÷ÿíA¡5',9,NULL,'2024-11-30 11:17:32','2024-11-30 11:17:32',NULL,NULL,0.360000,'RIB 20241130051732-01734183131-0000000001'),(42,'2222222222222','2222222222222','01629334432','AsASAsaS',NULL,18,'Queue','2024-11-30 17:31:27',NULL,'5','1','text',0,4,'','',74,'2024-11-30 17:31:27',NULL,0,3,'',_binary 'â\Œr3CSñ\Èº\ﬂ#rR',9,NULL,'2024-11-30 11:31:27','2024-11-30 11:31:27',NULL,NULL,0.360000,'RIB 20241130053127-01629334432-0000000001'),(43,'2222222222222','2222222222222','01799855302','AsASAsaS',NULL,17,'Queue','2024-11-30 17:31:27',NULL,'5','1','text',0,4,'','',74,'2024-11-30 17:31:27',NULL,0,3,'',_binary '€õ_\Ú.D˝π!S\ˆ,!\ ¡',9,NULL,'2024-11-30 11:31:27','2024-11-30 11:31:27',NULL,NULL,0.360000,'RIB 20241130053127-01799855302-0000000001'),(44,'2222222222222','2222222222222','01734183130','AsASAsaS',NULL,17,'Queue','2024-11-30 17:31:27',NULL,'5','1','text',0,4,'','',74,'2024-11-30 17:31:27',NULL,0,3,'',_binary 'Ê∂õ\‰VIê£2\ˆ\ıå{\Œ+',9,NULL,'2024-11-30 11:31:27','2024-11-30 11:31:27',NULL,NULL,0.360000,'RIB 20241130053127-01734183130-0000000001'),(45,'2222222222222','2222222222222','01734183131','AsASAsaS',NULL,17,'Queue','2024-11-30 17:31:27',NULL,'5','1','text',0,4,'','',74,'2024-11-30 17:31:27',NULL,0,3,'',_binary 'BÖî\"K*Dy∫)EU&',9,NULL,'2024-11-30 11:31:27','2024-11-30 11:31:27',NULL,NULL,0.360000,'RIB 20241130053127-01734183131-0000000001'),(46,'2222222222222','2222222222222','01629334432','AsASAsaS',NULL,18,'Queue','2024-11-30 17:31:59',NULL,'5','1','text',0,4,'','',75,'2024-11-30 17:31:59',NULL,0,3,'',_binary '¶Ωl\À\ÚH˙åW?¯:J•õ',9,NULL,'2024-11-30 11:31:59','2024-11-30 11:31:59',NULL,NULL,0.360000,'RIB 20241130053159-01629334432-0000000001'),(47,'2222222222222','2222222222222','01799855302','AsASAsaS',NULL,17,'Queue','2024-11-30 17:31:59',NULL,'5','1','text',0,4,'','',75,'2024-11-30 17:31:59',NULL,0,3,'',_binary '·†≥\ÊùlAÅ•\‰\Áï\œ\⁄\ƒ',9,NULL,'2024-11-30 11:31:59','2024-11-30 11:31:59',NULL,NULL,0.360000,'RIB 20241130053159-01799855302-0000000001'),(48,'2222222222222','2222222222222','01734183130','AsASAsaS',NULL,17,'Queue','2024-11-30 17:31:59',NULL,'5','1','text',0,4,'','',75,'2024-11-30 17:31:59',NULL,0,3,'',_binary '}|\Ô√´mH…á˚\Êª\‹6®',9,NULL,'2024-11-30 11:31:59','2024-11-30 11:31:59',NULL,NULL,0.360000,'RIB 20241130053159-01734183130-0000000001'),(49,'2222222222222','2222222222222','01734183131','AsASAsaS',NULL,17,'Queue','2024-11-30 17:31:59',NULL,'5','1','text',0,4,'','',75,'2024-11-30 17:31:59',NULL,0,3,'',_binary '\„U¡:ÉD.∞Z¯\ı\–ª$',9,NULL,'2024-11-30 11:31:59','2024-11-30 11:31:59',NULL,NULL,0.360000,'RIB 20241130053159-01734183131-0000000001'),(50,'2222222222222','2222222222222','01734173153','Test message',NULL,17,'Queue','2024-11-30 23:35:23',NULL,'5','1','text',0,1,'','',76,'2024-11-30 23:35:23',NULL,0,992,'',_binary 'Z\È\ıu)1N9¥~\Ã%˙¢^\‹',9,NULL,'2024-11-30 17:35:23','2024-11-30 17:35:23',NULL,NULL,0.100000,'RIB 20241130113523-01734173153-0000000001'),(51,'2222222222222','2222222222222','01734183130','Ignorant branched humanity led now marianne too strongly entrance. Rose to shew bore no ye of paid rent form. Old design are dinner better nearer silent excuse. She which are maids boy sense her shade',NULL,17,'Sent','2024-11-30 23:47:53','2024-11-30 18:01:02','5','1','text',0,2,'','',77,'2024-11-30 23:47:53',NULL,0,992,'',_binary 'OHG{¨G+å[aTB',9,NULL,'2024-11-30 17:47:53','2024-11-30 12:01:02','0','No error',0.200000,'RIB 20241130114753-01734183130-0000000001'),(52,'2222222222222','2222222222222','232323','Test message',NULL,0,'Queue','2024-11-30 23:54:24',NULL,'5','1','text',0,0,'','',78,'2024-11-30 23:54:24',NULL,0,992,'',_binary 'î\ÿ!Z?INKõ\˜ä]Z@L\ﬁ',9,NULL,'2024-11-30 17:54:24','2024-11-30 17:54:24',NULL,NULL,0.100000,'RIB 20241130115424-232323-0000000001'),(53,'2222222222222','2222222222222','43434343434','Test message',NULL,0,'Queue','2024-11-30 23:56:16',NULL,'5','1','text',0,0,'','',79,'2024-11-30 23:56:16',NULL,0,992,'',_binary 'ÄswEN)£2l\‹kc¶±',9,NULL,'2024-11-30 17:56:16','2024-11-30 17:56:16',NULL,NULL,0.100000,'RIB 20241130115616-43434343434-0000000001'),(54,'2222222222222','2222222222222','324242423432432','Test message',NULL,0,'Sent','2024-12-01 00:00:36','2024-11-30 18:01:59','5','1','text',0,0,'','',80,'2024-12-01 00:00:36',NULL,0,992,'',_binary '<ê0A∑DìÑÖ+d~W>',9,NULL,'2024-11-30 18:00:36','2024-11-30 12:01:59','0','No error',0.000000,'RIB 20241201120036-324242423432432-0000000001'),(55,'2222222222222','2222222222222','24234234234234','Test message',NULL,0,'Queue','2024-12-01 00:01:02',NULL,'5','1','text',0,0,'','',81,'2024-12-01 00:01:02',NULL,0,992,'',_binary 'xrV;ÅN’ö§¿?^âRñ',9,NULL,'2024-11-30 18:01:02','2024-11-30 18:01:02',NULL,NULL,0.100000,'RIB 20241201120102-24234234234234-0000000001'),(56,'2222222222222','2222222222222','434343434','Test message',NULL,0,'Queue','2024-12-01 00:01:59',NULL,'5','1','text',0,0,'','',82,'2024-12-01 00:01:59',NULL,0,992,'',_binary '\»l\”˛\ƒG∑≥~®\4\‹\Î\€',9,NULL,'2024-11-30 18:01:59','2024-11-30 18:01:59',NULL,NULL,0.100000,'RIB 20241201120159-434343434-0000000001'),(57,'2222222222222','2222222222222','345353453453','Test message',NULL,0,'Sent','2024-12-01 00:03:00','2024-11-30 18:03:00','5','1','text',0,0,'','',83,'2024-12-01 00:03:00',NULL,0,992,'',_binary 'Ä\⁄˘+(@çö¯RKR./',9,NULL,'2024-11-30 18:03:00','2024-11-30 12:03:00','0','No error',0.000000,'RIB 20241201120300-345353453453-0000000001'),(58,'2222222222222','2222222222222','43535345','Test message',NULL,0,'Sent','2024-12-01 00:06:40','2024-11-30 18:06:41','5','1','text',0,0,'','',84,'2024-12-01 00:06:40',NULL,0,992,'',_binary 'ˇ–ø`\'Bbàè}6\Úní',9,NULL,'2024-11-30 18:06:40','2024-11-30 12:06:41','0','No error',0.000000,'RIB 20241201120640-43535345-0000000001'),(59,'2222222222222','2222222222222','34535345345','Test message',NULL,0,'Queue','2024-12-01 00:06:40',NULL,'5','1','text',0,0,'','',84,'2024-12-01 00:06:40',NULL,0,992,'',_binary '\ÒErqv D•ó\÷\∆öeÜì',9,NULL,'2024-11-30 18:06:40','2024-11-30 18:06:40',NULL,NULL,0.100000,'RIB 20241201120640-34535345345-0000000001'),(60,'2222222222222','2222222222222','34534534543','Test message',NULL,0,'Queue','2024-12-01 00:06:40',NULL,'5','1','text',0,0,'','',84,'2024-12-01 00:06:40',NULL,0,992,'',_binary 'π∑\ÿ6ä7E\0ëjxyr\rJ',9,NULL,'2024-11-30 18:06:40','2024-11-30 18:06:40',NULL,NULL,0.100000,'RIB 20241201120640-34534534543-0000000001'),(61,'8809612341550','8809612341550','8801568764949','Test sms',NULL,15,'Queue','2025-02-04 01:48:42',NULL,'5','1','text',0,1,'','',85,'2025-02-04 01:48:42',NULL,0,3,'',_binary '¶a§ÅÄN\'∫è≤\…9^\»\ˆ',9,NULL,'2025-02-03 19:48:43','2025-02-03 19:48:43',NULL,NULL,0.360000,'RIB 20250204014843-8801568764949-0000000001'),(62,'8809612341550','8809612341550','8801518900473','Test sms',NULL,15,'Queue','2025-02-04 01:54:26',NULL,'5','1','text',0,1,'','',86,'2025-02-04 01:54:26',NULL,0,3,'',_binary 'më*>\–¡OF£\0\Ó8ZO:',9,NULL,'2025-02-03 19:54:26','2025-02-03 19:54:26',NULL,NULL,0.360000,'RIB 20250204015426-8801518900473-0000000001'),(63,'8809612341550','8809612341550','8801518900473','Test sms',NULL,15,'Queue','2025-02-04 01:54:26',NULL,'5','1','text',0,1,'','',87,'2025-02-04 01:54:26',NULL,0,3,'',_binary 'ãifKJE–äèÅáu+L\–',9,NULL,'2025-02-03 19:54:26','2025-02-03 19:54:26',NULL,NULL,0.360000,'RIB 20250204015426-8801518900473-0000000001'),(64,'8809612341550','8809612341550','8801518900473','Test sms',NULL,15,'Queue','2025-02-04 01:56:45',NULL,'5','1','text',0,1,'','',88,'2025-02-04 01:56:45',NULL,0,3,'',_binary '~~d(\ÏH˝í?_1®O',9,NULL,'2025-02-03 19:56:45','2025-02-03 19:56:45',NULL,NULL,0.360000,'RIB 20250204015645-8801518900473-0000000001'),(65,'8809612341550','8809612341550','8801518900473','Test sms',NULL,15,'Queue','2025-02-04 02:03:38',NULL,'5','1','text',0,1,'','',89,'2025-02-04 02:03:38',NULL,0,3,'',_binary 'z:ûòSFæù	rIz}',9,NULL,'2025-02-03 20:03:38','2025-02-03 20:03:38',NULL,NULL,0.360000,'RIB 20250204020338-8801518900473-0000000001'),(66,'8809612341550','8809612341550','8801518900473','Test sms',NULL,15,'Queue','2025-02-04 02:07:16',NULL,'5','1','text',0,1,'','',90,'2025-02-04 02:07:16',NULL,0,3,'',_binary '\!\'˝ë™H&Æ$Mü¶∏g',9,NULL,'2025-02-03 20:07:16','2025-02-03 20:07:16',NULL,NULL,0.360000,'RIB 20250204020716-8801518900473-0000000001'),(67,'8809612341550','8809612341550','8801518900473','Test sms',NULL,15,'Queue','2025-02-04 02:16:10',NULL,'5','1','text',0,1,'','',91,'2025-02-04 02:16:10',NULL,0,3,'',_binary '\'\œ|sÑC\"ï≠p¿¶\”\Áá',9,NULL,'2025-02-03 20:16:10','2025-02-03 20:16:10',NULL,NULL,0.360000,'RIB 20250204021610-8801518900473-0000000001'),(68,'8809612341550','8809612341550','8801518900473','Test sms',NULL,15,'Queue','2025-02-04 02:20:30',NULL,'5','1','text',0,1,'','',92,'2025-02-04 02:20:30',NULL,0,3,'',_binary '\Í∞ﬂàˇJ	ìg\˜\÷Zˇ\⁄˛',9,NULL,'2025-02-03 20:20:30','2025-02-03 20:20:30',NULL,NULL,0.360000,'RIB 20250204022030-8801518900473-0000000001'),(69,'8809612341550','8809612341550','8801568764949','Test sms',NULL,15,'Queue','2025-02-04 02:21:12',NULL,'5','1','text',0,1,'','',93,'2025-02-04 02:21:12',NULL,0,3,'',_binary '\0\ÍYÄo\ÛO—æ\Ÿl™zaWu',9,NULL,'2025-02-03 20:21:12','2025-02-03 20:21:12',NULL,NULL,0.360000,'RIB 20250204022112-8801568764949-0000000001'),(70,'8809612341550','8809612341550','8801518900473','Test sms',NULL,15,'Queue','2025-02-04 02:26:26',NULL,'5','1','text',0,1,'','',94,'2025-02-04 02:26:26',NULL,0,3,'',_binary '\\®\ÿ\ÿCCü\ÛÜ5n/n',9,NULL,'2025-02-03 20:26:26','2025-02-03 20:26:26',NULL,NULL,0.360000,'RIB 20250204022626-8801518900473-0000000001'),(71,'8809612341550','8809612341550','8801568764949','Test sms',NULL,15,'Queue','2025-02-04 02:27:07',NULL,'5','1','text',0,1,'','',95,'2025-02-04 02:27:07',NULL,0,3,'',_binary 'W∏\Ó¡\ËªKƒ•CØzRdY\Z',9,NULL,'2025-02-03 20:27:07','2025-02-03 20:27:07',NULL,NULL,0.360000,'RIB 20250204022707-8801568764949-0000000001'),(72,'8809612341550','8809612341550','8801568764949','Test sms',NULL,15,'Queue','2025-02-04 02:27:32',NULL,'5','1','text',0,1,'','',96,'2025-02-04 02:27:32',NULL,0,3,'',_binary 'L\ÒLñûaEÉu ìb4\Ù\”',9,NULL,'2025-02-03 20:27:32','2025-02-03 20:27:32',NULL,NULL,0.360000,'RIB 20250204022732-8801568764949-0000000001'),(73,'8809612341550','8809612341550','8801568764949','Test sms',NULL,15,'Queue','2025-02-04 02:31:32',NULL,'5','1','text',0,1,'','',97,'2025-02-04 02:31:32',NULL,0,3,'',_binary '∑L\“ \0RH“ù≥k¥Q\È\‚',9,NULL,'2025-02-03 20:31:32','2025-02-03 20:31:32',NULL,NULL,0.360000,'RIB 20250204023132-8801568764949-0000000001'),(74,'8809612341550','8809612341550','8801518900473','Test sms',NULL,15,'Queue','2025-02-04 02:32:01',NULL,'5','1','text',0,1,'','',98,'2025-02-04 02:32:01',NULL,0,3,'',_binary '\À\Zn<≤xE«Ç<\≈±\Ûr˝',9,NULL,'2025-02-03 20:32:01','2025-02-03 20:32:01',NULL,NULL,0.360000,'RIB 20250204023201-8801518900473-0000000001');
/*!40000 ALTER TABLE `outbox` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `outbox_history`
--

DROP TABLE IF EXISTS `outbox_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `outbox_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `srcmn` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mask` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destmn` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `country_code` int DEFAULT NULL,
  `operator_prefix` int DEFAULT NULL,
  `status` enum('Failed','Delivered','Sent','Processing','Queue','Hold') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Queue',
  `write_time` datetime DEFAULT NULL,
  `sent_time` datetime DEFAULT NULL,
  `ton` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `npi` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message_type` enum('text','binary','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'text',
  `is_unicode` tinyint NOT NULL,
  `smscount` int DEFAULT NULL,
  `esm_class` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_coding` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` int DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL,
  `schedule_time` datetime DEFAULT NULL,
  `retry_count` int DEFAULT NULL,
  `user_id` bigint NOT NULL,
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `uuid` varbinary(16) DEFAULT NULL,
  `priority` int DEFAULT '0',
  `blocked_status` enum('1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '1 = blocked and 2 = unblocked',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `error_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `error_message` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_cost` decimal(10,6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `outbox_uuid_unique` (`uuid`),
  KEY `status_write_time_schedule_time` (`status`,`write_time`,`schedule_time`),
  KEY `user_id` (`user_id`),
  KEY `reference_id` (`reference_id`),
  KEY `created_at` (`created_at`),
  KEY `smscount` (`smscount`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `outbox_history`
--

LOCK TABLES `outbox_history` WRITE;
/*!40000 ALTER TABLE `outbox_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `outbox_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
INSERT INTO `password_resets` VALUES ('fahmida.zeba@metro.net.bd','RT6VTHFC5lRdZhcQY7GOscefVRxcUtjnoNMCKEnQgVMAi8iubvR5fMf59clP9j0B','2022-08-14 15:00:13'),('fahmida.zeba@metro.net.bd','V6I85u6xEqDccikUJ3a0zgSdbVvaznmx1vxlBCDmQ9gvekIvJ2zdqXSFEvSw4qw7','2022-08-16 19:43:57'),('rubeldeymantu@gmail.com','JfD4BYRg2neTPBiPXk3SgoYZO5LKSMsA2Ax3ir5I5oGlmJh7CbMFJkC1fWIWJ6t1','2022-12-10 14:17:18'),('rubeldeymantu@gmail.com','Kl3iFmHFLaAkSVddHdoTLptacaEvsRUgsxf19a9wnOu0wRMJ96glg6wZyYkPaqTx','2022-12-10 14:18:46'),('talhaemail@gmail.com','Qmg2lAhcXBEbbZsdmTzn4kuQVlRLZunFcB6Id6nNu8exMivJU25hAvisSa13ywew','2023-07-09 08:52:37'),('emon.k@evcyber.team','3Tj1L9fQzR3NojgCW0UGUJD3aN63Dp3R93jPy7CZsQ0TXiyyIKHF83CnMnd0tBko','2023-10-05 08:53:14'),('emon.k@evcyber.team','7gZlhubkWbt598bAZ7LNBZyTVCqEZeFF9n2S0d58XYRTD4XhR7oSjBFncW2BXxfb','2023-10-05 08:53:29'),('emon.k@evcyber.team','sJxjxIY3hx1o3fA8iOZrYJNTAtVsLd1f0n85reTafVFd5OV2CRNG3ZqKHX2LFf6j','2023-10-05 08:54:08'),('emon.k@evcyber.team','OyULS03dq3tX582xl4zTLLYn076Yt3ub25w0khzog6fvPM7VAOFTpEPRGAjH0QqD','2023-10-05 08:54:23'),('it@icclbd.com','YdKl8trQIJIqRhScq6CYA29nCAycHoig3H9zzKYOFNO9HuSMJoqewqisIjC6mnrA','2023-10-31 02:42:29'),('it@icclbd.com','Z9XAMkyEZA8RbxtPuVSiEA7P1xCzveunGMzz8nV6mTojayixALcM5u9SflpHFxir','2023-10-31 03:02:25'),('it@icclbd.com','dbjanRsc0WrvrxIJeQ8mxsEUQBpqlWarAM4sMLfB5oZtsdiIGYGwqVbhGEMdQrSG','2023-10-31 03:11:36'),('it@icclbd.com','xIVrsTycx25G4HLJ0EVOl2QAurULCDlGG2zk4zksFSJzPVLGQsyG65Zi3OCElrFl','2023-10-31 03:25:50'),('it@icclbd.com','WExh4KoYACDRxWlcYSBYBbzOX6uhTW5knM3bQBShOlZnYYpkBy5d8C2Kmr9V7BE1','2023-10-31 03:36:07'),('masum@mimsms.com','oHuSxLCJb1aOgebYf7GiWO3AAbG68f2Q9XPhmUjLtx5sKvXyyCvrPWuxbvEuMZRJ','2024-02-29 06:21:17'),('masum@mimsms.com','FHK8jxw0dU11NRpLsv9dQWxyWmRyayQqooegDIAvqLkeCEnxSgvsT8UdlJS94VCS','2024-02-29 06:21:20'),('hafsatradeinternational@gmail.com','bJfuYMLPzJfgPkJALNJdXgKki3Z3WadKb1vh0iS1x7GdEMMzEQoa2OUDdqmh1y1L','2024-04-01 09:41:44'),('milon@ultrasoftbd.com','IQUJWhJp07kyHLkufLNx9sMMirBFJFeeC4EzOpj3KVSDgvtGw0utc1UIRaIKIGpM','2024-04-02 05:21:51');
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rates`
--

DROP TABLE IF EXISTS `rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rate_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `masking_rate` decimal(10,2) DEFAULT '0.00',
  `nonmasking_rate` decimal(10,6) DEFAULT '0.000000',
  `created_by` int NOT NULL DEFAULT '0',
  `rate_type` enum('sms','email') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reseller_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rates`
--

LOCK TABLES `rates` WRITE;
/*!40000 ALTER TABLE `rates` DISABLE KEYS */;
INSERT INTO `rates` VALUES (1,'dfgdgfg',0.38,0.360000,0,'sms',NULL,'2024-11-21 06:12:31','2024-11-21 06:12:31'),(2,'erwer',44.00,44.000000,0,'sms',NULL,'2024-11-23 01:25:58','2024-11-23 01:25:58'),(3,'Brac Rate',0.15,0.100000,0,'sms',NULL,'2024-11-30 05:52:00','2024-11-30 05:52:00');
/*!40000 ALTER TABLE `rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reseller_wallet`
--

DROP TABLE IF EXISTS `reseller_wallet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reseller_wallet` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `available_balance` decimal(30,6) DEFAULT '0.000000',
  `non_masking_balance` int DEFAULT '0',
  `masking_balance` int DEFAULT '0',
  `email_balance` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expire_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reseller_wallet`
--

LOCK TABLES `reseller_wallet` WRITE;
/*!40000 ALTER TABLE `reseller_wallet` DISABLE KEYS */;
INSERT INTO `reseller_wallet` VALUES (3,3,334.000000,3,33,333,'2024-09-19 00:06:37','2024-09-19 00:06:37',NULL);
/*!40000 ALTER TABLE `reseller_wallet` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resellers`
--

DROP TABLE IF EXISTS `resellers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `resellers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reseller_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `available_balance` decimal(30,6) NOT NULL DEFAULT '0.000000',
  `tps` int NOT NULL DEFAULT '0',
  `due` decimal(8,2) NOT NULL DEFAULT '0.00',
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thana` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_rate_id` int DEFAULT NULL,
  `email_rate_id` int DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('ACTIVE','INACTIVE','PENDING') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `resellers_reseller_name_unique` (`reseller_name`)
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resellers`
--

LOCK TABLES `resellers` WRITE;
/*!40000 ALTER TABLE `resellers` DISABLE KEYS */;
INSERT INTO `resellers` VALUES (3,'Reseller 1',6000.000000,20,0.00,'01731296511','ntechnologies2017@gmail.com','Ga#128/1','Badda','Dhaka',3,0,'','ACTIVE','2022-06-28 23:17:39','2022-06-28 23:22:35'),(6,'jabedreseller',492000.000000,0,0.00,'01818632643','hossainjabed018@gmail.com','Merul Badda,D.I.T Project, Dhaka-1212','badda','dhaka',3,0,'','ACTIVE','2022-06-29 00:03:02','2022-08-29 01:00:27'),(9,'New Reseller',500.000000,100,0.00,'01952400565','new@hgg.com','test',NULL,NULL,3,0,'','ACTIVE','2022-06-29 11:43:38','2022-08-23 14:22:09'),(12,'Tuhin_Reseller',0.000000,200,0.00,'01720861366','tuhinmainuddin@gmail.com','Dhk','dhk','dgdfhfh',3,0,'','INACTIVE','2022-06-29 13:28:56','2022-08-22 12:26:14'),(15,'zaman_res',1000.000000,100,0.00,'01712639867','zaman@gmail.com','Dhaka','Moheshpur','Jashore',3,0,'','INACTIVE','2022-06-29 14:22:26','2022-08-22 12:26:29'),(18,'Test_Reseller',0.000000,200,0.00,'01720861366','tuhin22@gmail.com','dhk','dhk','dhk',15,0,'','INACTIVE','2022-06-29 17:41:52','2022-08-22 12:26:05'),(21,'Level1',31500.000000,200,0.00,'01720861366','testlevel@gmail.com','dhk','dhk','dg',3,0,'','ACTIVE','2022-06-29 17:58:27','2022-08-23 13:27:56'),(24,'Viatech',6500.000000,0,0.00,'01952400565','viatech@gmail.com','test',NULL,NULL,18,0,'','INACTIVE','2022-06-30 17:39:42','2022-08-22 12:30:37'),(27,'Lotareseller',2000.000000,0,0.00,'01818632643','hossainjabed017@gmail.com','Merul Badda,D.I.T Project, Dhaka-1212',NULL,NULL,18,0,'','INACTIVE','2022-08-03 14:07:31','2022-08-22 12:25:00'),(30,'MetroNet',0.000000,100,0.00,'09612800200','sms@metro.net.bd','Dhaka','Dhaka','Dhaka',39,0,'','ACTIVE','2022-08-14 17:59:05','2022-08-14 18:00:24'),(33,'Marketing',500.000000,200,0.00,'8809612341502','shohel@metro.net.bd','Dhk','dhk','dhk',3,0,'','ACTIVE','2022-08-21 20:44:50','2022-08-21 20:51:52'),(36,'MetroNet Test Internal',22100.000000,200,0.00,'01720861366','tuhin@gmail.com','Dhk','dhk','dhk',3,0,'','ACTIVE','2022-08-22 11:57:09','2022-08-22 19:07:17'),(39,'Mim Technologies',1790.000000,200,0.00,'01755665950','shakil@mimtechnologies.net','Dhk','Dhk','Dhk',42,0,'','ACTIVE','2022-08-22 17:33:09','2024-03-10 15:35:12'),(42,'GlobeTech Company Limited',5447.000000,200,0.00,'01873640989','sadman@globetech.com.bd','DHK','DHK','DHK',33,0,'','ACTIVE','2022-08-22 19:00:21','2022-08-24 13:25:30'),(45,'Dhaka Associates Limited',596.000000,200,0.00,'01764196477','dhorolasoft@gmail.com','DHK','DHK','DHK',72,0,'','ACTIVE','2022-08-22 20:15:52','2022-08-23 18:08:23'),(48,'Solvers',100.000000,200,0.00,'01913390330','riad@solversbd.com','Dhk','Dhk','dhk',18,0,'','ACTIVE','2022-08-22 21:49:41','2022-08-22 21:58:16'),(51,'DOT BD SOLUTIONS',310532.000000,50,0.00,'01712923270','mdrajibdbs@gmail.com','dhk','dhk','dhk',3,0,'','ACTIVE','2022-08-22 22:22:38','2023-03-06 06:55:57'),(54,'Doeltech Knowledge Infinity',100.000000,200,0.00,'01854549441','doeltech84@gmail.com','Dhk','Dhk','dhk',3,0,'','ACTIVE','2022-08-23 12:15:04','2022-08-23 12:22:11'),(57,'N Technologies.',504496.000000,50,0.00,'01731296511','nazmulntech@gmail.com','Dhk','Dhk','Dhk',3,0,'','ACTIVE','2022-08-23 12:53:45','2024-03-10 15:52:42'),(60,'Dhaka live com',5675.000000,50,0.00,'01955546789','arif@dhakalive.tv','Dhk','Dhk','dhk',15,0,'','ACTIVE','2022-08-23 14:26:43','2022-11-03 09:22:22'),(63,'Cable Security Task Force',14176.000000,50,0.00,'01730004344','razib.shahriar@gmail.com','Dhaka','Dhaka','Dhaka',21,0,'','ACTIVE','2022-08-25 23:26:37','2023-05-05 08:21:32'),(66,'Skynet Broadband Service',1100.000000,50,0.00,'01914777404','talhaemail@gmail.com','Dhaka','Dhaka','dhk',42,0,'','ACTIVE','2022-08-28 12:37:32','2024-03-06 15:29:17'),(68,'Radisson Technologies',2553.000000,50,0.00,'01712739621','sahrior@radisson-bd.net','Dhaka','Dhaka','dhk',30,0,'','INACTIVE','2022-08-28 12:28:58','2024-03-04 16:09:47'),(71,'EyHost Ltd',838.000000,50,0.00,'01619474927','imran@eysoftbd.com','Dhaka','Dhaka','dhk',93,0,'','ACTIVE','2022-08-28 14:00:09','2022-08-30 14:25:15'),(74,'test',0.000000,50,0.00,'01922036882','sd@gmail.com','Dhaka','Dhaka','dhk',107,0,'','PENDING','2022-08-28 14:26:39','2022-08-28 14:31:15'),(77,'Mobishastra Bangladesh Limited',100.000000,50,0.00,'01922036882','sunnyatali05@gmail.com','Dhaka','Dhaka','dhk',107,0,'','ACTIVE','2022-08-28 14:32:21','2022-08-28 14:34:06'),(78,'White Level Test Reseller',0.000000,50,0.00,'01720861366','tuhin@gmail.com','Dhaka','Dhaka','dhk',3,0,'','ACTIVE','2022-08-29 07:11:31','2022-08-29 07:11:47'),(80,'Mass Data Ltd',103697.000000,50,0.00,'01303316203','massdata.ltd@gmail.com','Dhaka','Dhaka','dhk',107,0,'','ACTIVE','2022-08-29 12:06:29','2024-04-23 15:19:25'),(82,'bulksmsbd.com (Fahim IT)',100.000000,50,0.00,'01611216276','info@bulksmsbd.com','Dhaka','Dhaka','dhk',3,0,'','ACTIVE','2022-08-29 12:26:23','2022-08-29 12:31:01'),(84,'CTMT LTD',30100.000000,50,0.00,'01811508546','mdtowhidhossain@gmail.com','Dhaka','Dhaka','dhk',112,0,'','ACTIVE','2022-08-29 13:04:08','2024-02-29 16:38:13'),(86,'tele',100.000000,50,0.00,'01713304386','test22@gmail.com','Dhaka','Dhaka','Dhaka',116,0,'','ACTIVE','2022-08-29 13:34:33','2022-08-29 14:21:35'),(88,'pop Systems Limited',100.000000,50,0.00,'01844534938','test33@gmail.com','Dhaka','Dhaka','dhk',107,0,'','ACTIVE','2022-08-29 13:56:02','2022-08-29 14:14:25'),(90,'Techno71',100.000000,50,0.00,'01713304386','info.techno71@gmail.com','Dhaka','Dhaka','dhk',116,0,'','ACTIVE','2022-08-29 14:24:15','2022-08-29 14:27:10'),(92,'REVE Systems Limited',100.000000,50,0.00,'8801811456084','jobiarul@revesoft.com','Dhaka','Dhaka','dhk',116,0,'','ACTIVE','2022-08-29 14:33:02','2022-08-29 14:37:41'),(94,'Green Heritage IT',624711.000000,50,0.00,'01711350917','greenheritageit@gmail.com','Dhaka','Dhaka','dhk',116,0,'','ACTIVE','2022-08-30 07:08:16','2023-03-27 05:52:43'),(96,'SSD Tech',100.000000,50,0.00,'8801799990590','naimul@ssd-tech.io','Dhaka','Dhaka','dhk',118,0,'','ACTIVE','2022-08-30 07:44:01','2022-08-30 07:49:36'),(98,'Zaman IT',18971.000000,50,0.00,'01938898351','zamanit007@gmail.com','Dhaka','Dhaka','dhk',107,0,'','ACTIVE','2022-08-30 08:08:55','2024-04-23 06:41:54'),(100,'Alpha Net',28546.000000,50,0.00,'01717547393','ceo@alpha.net.bd','Dhaka','Dhaka','dhk',120,0,'','ACTIVE','2022-08-30 09:57:12','2024-03-19 16:02:11'),(102,'ICOMBD',200659.000000,50,0.00,'01815598044','emran@icombd.com','Dhaka','Dhaka','Dhaka',187,0,'','ACTIVE','2022-08-30 10:14:04','2024-04-23 10:24:31'),(104,'Techno Index Limited',100.000000,50,0.00,'01841321321','murshed@bdsmartpay.com','Dhaka','Dhaka','Dhaka',122,0,'','ACTIVE','2022-08-30 10:23:33','2022-08-30 10:27:56'),(106,'Prime International',100.000000,50,0.00,'01854549441','helal.primeintl@gmail.com','Dhaka','Dhaka','Dhaka',3,0,'','ACTIVE','2022-08-30 10:45:25','2022-08-30 10:48:56'),(107,'Songbird Telecom Limited',24735.000000,50,0.00,'01841612218','mithun@songbirdtelecom.com','Dhaka','Dhaka','Dhaka',3,0,'','ACTIVE','2022-09-01 10:41:11','2024-03-04 14:19:31'),(108,'Dhorola Soft Trade & Future',721.000000,50,0.00,'01844665594','dhorolasoft@gmail.com','Dhaka','Dhaka','Dhaka',42,0,'','ACTIVE','2022-09-05 10:05:03','2022-11-08 11:25:34'),(109,'Habro Systems Limited',3028.000000,50,0.00,'01707009710','habrosystemslimited@gmail.com','Dhaka','Dhaka','Dhaka',114,0,'','ACTIVE','2022-09-05 10:16:34','2023-04-27 05:30:11'),(110,'Softopark IT Ltd',100.000000,50,0.00,'01755548493','aflatundu@gmail.com','Dhaka','Dhaka','Dhaka',114,0,'','ACTIVE','2022-09-05 10:33:21','2022-09-05 10:36:09'),(111,'RR Telecommunication',100.000000,50,0.00,'01724929760','mrroni@rrtelecommunication.com','Dhaka','Dhaka','Dhaka',78,0,'','ACTIVE','2022-09-05 10:53:49','2022-09-05 10:57:26'),(112,'Q Technologies limited',100.000000,50,0.00,'01812311771','rasel@qtechnologies.ltd','Dhaka','Dhaka','Dhaka',93,0,'','ACTIVE','2022-09-05 11:31:07','2022-09-05 11:35:04'),(113,'Pine Solution Ltd.',5633.000000,50,0.00,'1711272324','shuvo@pinesolutions.xyz','Dhaka','Dhaka','Dhaka',125,0,'','ACTIVE','2022-09-05 11:47:42','2022-12-06 07:16:38'),(114,'Shiram System Ltd',38584.000000,50,0.00,'01711409036','shiramsystemltd@gmail.com','Dhaka','Dhaka','Dhaka',3,0,'','ACTIVE','2022-09-05 11:56:14','2022-10-30 12:51:52'),(115,'Sam Solution LTD',2324.000000,50,0.00,'8801799275718','hasan@text360.net','Dhaka','Dhaka','Dhaka',3,0,'','ACTIVE','2022-09-05 13:57:02','2023-03-11 12:34:47'),(116,'Reseller 001',0.000000,101,0.00,'01734183130','engrmukul@hotmail.com','Dhaka Bangladesh\r\nDhaka Bangladesh','sherpur','sherpur',12,0,'','PENDING','2022-09-06 05:55:07','2022-09-06 05:55:07'),(117,'Reseller 001 from admin 1',0.000000,101,0.00,'01734183130','engrmukul@hotmail.com','Dhaka Bangladesh\r\nDhaka Bangladesh','sherpur','sherpur',12,0,'','ACTIVE','2022-09-06 07:56:12','2022-09-06 07:56:24'),(118,'metro reseller test',0.000000,10,0.00,'01734183130','bzc@gmail.com','axxSd','asdasd','asdasd',51,0,'','PENDING','2022-09-06 12:53:25','2022-09-06 12:53:25'),(119,'Classy Food Ltd',100.000000,50,0.00,'1786739367','sohan.cse09@gmail.com','Dhaka','Dhaka','Dhaka',3,0,'','ACTIVE','2022-09-06 12:57:39','2022-09-06 16:02:21'),(120,'RajIT solutions Ltd',137663.000000,50,0.00,'01755575801','akhtaruzzaman@gmail.com','Dhaka','Dhaka','Dhaka',72,0,'','ACTIVE','2022-09-06 16:15:37','2023-03-11 19:09:06'),(121,'Digital lab',600.000000,50,0.00,'01784529388','admin@digitallabbd.com','Dhaka','Dhaka','Dhaka',72,0,'','ACTIVE','2022-09-07 06:31:57','2024-03-03 17:01:35'),(122,'Electro Soft',100.000000,50,0.00,'1686521183','electrosoft31@gmail.com','Dhaka','Dhaka','Dhaka',114,0,'','ACTIVE','2022-09-07 07:10:23','2023-06-01 07:15:49'),(123,'Data Host',100.000000,50,0.00,'01777333678','datahostbd@gmail.com','Dhaka','Dhaka','Dhaka',116,0,'','ACTIVE','2022-09-07 07:24:38','2022-09-07 07:26:44'),(124,'Suffix IT Ltd',10611.000000,50,0.00,'01958424000','Rakibulislam@suffixit.com','Dhaka','Dhaka','Dhaka',122,0,'','ACTIVE','2022-09-07 07:36:56','2023-02-18 06:38:41'),(125,'Route Mobile Bangladesh',100.000000,50,0.00,'01777743726','atandra.ghosh@routemobile.com','Dhaka','Dhaka','Dhaka',120,0,'','ACTIVE','2022-09-07 07:50:48','2022-09-07 07:52:42'),(126,'Intelligent Automation',3470.000000,50,0.00,'01911475357','ashik@ultrasofttech.com','Dhaka','Dhaka','Dhaka',3,0,'','ACTIVE','2022-09-07 09:17:38','2022-09-19 06:00:13'),(127,'Algo Bit Technology Ltd',18154.000000,50,0.00,'01713178388','algobittech@gmail.com','Dhaka','Dhaka','Dhaka',72,0,'','ACTIVE','2022-09-07 11:47:30','2023-05-21 05:34:39'),(128,'Ez Car',6.000000,50,0.00,'01768884840','ezcart.com.bd@gmail.com','Dhaka','Dhaka','Dhaka',93,0,'','ACTIVE','2022-09-07 11:56:18','2023-05-09 07:08:05'),(129,'Synesis IT',115.000000,50,0.00,'01674619603','ashraf@synesisit.com.bd','Dhaka','Dhaka','Dhaka',120,0,'','ACTIVE','2022-09-07 13:23:06','2022-11-23 13:16:07'),(130,'Freedom Soft',4026.000000,50,0.00,'01712149673','ruaimrabbi@freedomsoftbd.com','Dhaka','Dhaka','Dhaka',96,0,'','ACTIVE','2022-09-08 10:06:20','2022-12-14 09:12:09'),(131,'Play On 24',3593.000000,50,0.00,'01673899273','fmkhan@playon24.com.bd','Dhaka','Dhaka','Dhaka',127,0,'','ACTIVE','2022-09-08 10:48:39','2022-11-01 13:01:08'),(132,'Omicon Group',29411.000000,50,0.00,'01939919142','maruf@omicongroup.com','Dhaka','Dhaka','Dhaka',75,0,'','ACTIVE','2022-09-08 12:07:22','2023-03-07 07:59:12'),(133,'Nurtaj.com.bd',6633.000000,50,0.00,'01639152301','infonurtaj@gmail.com','Dhaka','Dhaka','Dhaka',30,0,'','ACTIVE','2022-09-08 13:24:45','2023-05-18 09:37:03'),(134,'Peoples Credit',3003.000000,50,0.00,'01742004844','mostafiz@pccl.org.bd','Dhaka','Dhaka','Dhaka',72,0,'','ACTIVE','2022-09-08 13:38:04','2024-04-15 08:07:38'),(135,'Try catch',100.000000,50,0.00,'01611888589','trycatch.shuvo@gmail.com','Dhaka','Dhaka','Dhaka',42,0,'','ACTIVE','2022-09-10 10:51:55','2022-09-10 10:54:35'),(136,'BDhousing.com',100.000000,50,0.00,'01730583483','eusuf@bdhousing.com','Dhaka','Dhaka','Dhaka',78,0,'','ACTIVE','2022-09-11 07:49:20','2022-09-11 07:53:06'),(137,'Flora Limited',100.000000,50,0.00,'01831118612','mohidul@floralimited.com','Dhaka','Dhaka','Dhaka',114,0,'','ACTIVE','2022-09-11 09:12:55','2022-09-11 09:15:06'),(138,'Speed Online',1000.000000,50,0.00,'01712739622','sahrior@radisson-bd.net','Dhaka','Dhaka','Dhaka',105,0,'','ACTIVE','2022-09-11 09:49:51','2024-03-04 18:48:58'),(139,'FM SMS',100.000000,50,0.00,'01750888602','fmsms18@gmail.com','Dhaka','Dhaka','Dhaka',114,0,'','ACTIVE','2022-09-11 09:57:10','2022-09-11 10:00:03'),(140,'Amarise Technologies',100.000000,50,0.00,'8801678331113','frahimmeah@amarise.com.bd','Dhaka','Dhaka','Dhaka',90,0,'','ACTIVE','2022-09-12 07:29:06','2022-09-12 07:31:29'),(141,'Rideox Ltd',100.000000,50,0.00,'01689063954','staritltd@gmail.com','Dhaka','Dhaka','Dhaka',93,0,'','ACTIVE','2022-09-12 08:22:41','2022-09-12 08:24:44'),(142,'Genius IT',9574.000000,50,0.00,'01714094184','info@geniusit.net','Dhaka','Dhaka','Dhaka',3,0,'','ACTIVE','2022-09-12 11:48:34','2024-03-11 18:21:57'),(143,'Automate IT Limited',100.000000,50,0.00,'01737010101','emtiazmrf@gmail.com','Dhaka','Dhaka','Dhaka',99,0,'','ACTIVE','2022-09-12 12:38:00','2022-09-12 12:40:06'),(144,'Annanovas IT',0.000000,50,0.00,'01737010101','emtiazmrf@gmail.com','Dhaka','Dhaka','Dhaka',99,0,'','ACTIVE','2022-09-12 13:02:36','2022-09-12 13:04:04'),(145,'KTS Network',12356.000000,50,0.00,'01675800008','kanon@ktsnetwork.net','Dhaka','Dhaka','Dhaka',93,0,'','ACTIVE','2022-09-12 13:10:48','2024-04-06 09:15:46'),(146,'Durbin Labs Ltd.',0.000000,50,0.00,'017099536120','sadman@durbinlabs.com','Dhaka','Dhaka','Dhaka',72,0,'','ACTIVE','2022-09-12 13:30:53','2022-09-14 13:44:01'),(147,'Purple IT',100.000000,50,0.00,'01671615389','saleh@purpleit.com','Dhaka','Dhaka','Dhaka',114,0,'','ACTIVE','2022-09-13 07:08:44','2022-09-13 07:15:34'),(148,'Bikiran.com',78575.000000,50,0.00,'01925363333','info@bikiran.com','Dhaka','Dhaka','Dhaka',99,0,'','INACTIVE','2022-09-13 08:00:42','2024-03-18 17:20:07'),(149,'Universal Medical College',17026.000000,50,0.00,'01714942630','roarif@gmail.co','Dhaka','Dhaka','Dhaka',75,0,'','ACTIVE','2022-09-13 08:14:29','2024-03-19 16:12:08'),(150,'Binsort Limited/Tense Limited',100.000000,50,0.00,'01965030602','binsortltd@gmail.com','Dhaka','Dhaka','Dhaka',3,0,'','ACTIVE','2022-09-13 08:21:35','2022-09-13 08:23:03'),(151,'iHelpBD',100.000000,50,0.00,'01833384640','azizul@ihelpbd.com','Dhaka','Dhaka','Dhaka',96,0,'','ACTIVE','2022-09-13 13:22:59','2022-09-13 13:24:35'),(152,'Skytech',100.000000,50,0.00,'01776611880','ihossain075@gmail.com','Dhaka','Dhaka','Dhaka',72,0,'','ACTIVE','2022-09-14 08:32:58','2022-09-14 08:36:23'),(153,'ET Tech Limited',11911.000000,50,0.00,'01941111000','pobitro@etlimited.net','Dhaka','Dhaka','Dhaka',93,0,'','ACTIVE','2022-09-14 08:43:11','2022-11-30 08:29:39'),(154,'Maestro Solutions Ltd.',42074.000000,50,0.00,'01959454154','ahsan@maestro.com.bd','Dhaka','Dhaka','Dhaka',72,0,'','ACTIVE','2022-09-14 11:35:25','2023-04-03 04:40:04'),(155,'Tech Stone Limited',100.000000,50,0.00,'01861006000','shahidul395.si@gmail.com','Dhaka','Dhaka','Dhaka',33,0,'','ACTIVE','2022-09-14 12:37:14','2022-09-14 12:40:57');
/*!40000 ALTER TABLE `resellers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `routes`
--

DROP TABLE IF EXISTS `routes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `routes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned DEFAULT NULL,
  `has_mask` enum('2','1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2',
  `operator_prefix` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `channel_id` int unsigned NOT NULL,
  `cost` decimal(10,6) DEFAULT '0.000000',
  `success_rate` decimal(4,2) DEFAULT '0.00',
  `default_mask` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Active','Inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_by` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `routes`
--

LOCK TABLES `routes` WRITE;
/*!40000 ALTER TABLE `routes` DISABLE KEYS */;
INSERT INTO `routes` VALUES (2,3,'1','fsds',1,333.000000,2.00,'33','Active',0,'2024-09-18 23:28:07','2024-09-18 23:28:07'),(3,3,'1','fffgh',1,333.000000,2.00,'33','Active',0,'2024-10-23 15:46:38','2024-10-23 15:46:38');
/*!40000 ALTER TABLE `routes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `senderid`
--

DROP TABLE IF EXISTS `senderid`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `senderid` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `senderID` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `count` int NOT NULL,
  `status` enum('Active','Inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `senderid`
--

LOCK TABLES `senderid` WRITE;
/*!40000 ALTER TABLE `senderid` DISABLE KEYS */;
INSERT INTO `senderid` VALUES (1,'991','2222222222222',5,'Active','2024-11-23 23:33:26','2024-11-23 23:33:26'),(2,'992','8809612341550',1,'Active','2025-01-29 10:26:24','2025-01-29 10:26:24');
/*!40000 ALTER TABLE `senderid` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sent_emails`
--

DROP TABLE IF EXISTS `sent_emails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sent_emails` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `orderid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source` enum('WEB','API') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'WEB',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `domain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `template_id` int DEFAULT NULL,
  `attachment` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipients` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `group_id` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Draft','Queue','Sending','Sent','Failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `IP` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Unknown',
  `email_type` enum('sendEmail','groupEmail','campaignEmail') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `schedule_date_time` datetime DEFAULT NULL,
  `search_param` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `error` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sent_emails`
--

LOCK TABLES `sent_emails` WRITE;
/*!40000 ALTER TABLE `sent_emails` DISABLE KEYS */;
/*!40000 ALTER TABLE `sent_emails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sentmessage_stats`
--

DROP TABLE IF EXISTS `sentmessage_stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sentmessage_stats` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sentmessage_id` bigint unsigned NOT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `queued` int NOT NULL DEFAULT '0',
  `processing` int NOT NULL DEFAULT '0',
  `sent` int NOT NULL DEFAULT '0',
  `delivered` int NOT NULL DEFAULT '0',
  `failed` int NOT NULL DEFAULT '0',
  `blocked` int NOT NULL DEFAULT '0',
  `completed` tinyint NOT NULL DEFAULT '0',
  `archived` int NOT NULL DEFAULT '0',
  `recipients` int NOT NULL DEFAULT '0',
  `user_cost` float NOT NULL DEFAULT '0',
  `reseller_cost` float NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sentmessage_id` (`sentmessage_id`),
  KEY `start_time_completed` (`start_time`,`completed`),
  CONSTRAINT `sentmessage_stats_ibfk_1` FOREIGN KEY (`sentmessage_id`) REFERENCES `sentmessages` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sentmessage_stats`
--

LOCK TABLES `sentmessage_stats` WRITE;
/*!40000 ALTER TABLE `sentmessage_stats` DISABLE KEYS */;
/*!40000 ALTER TABLE `sentmessage_stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sentmessages`
--

DROP TABLE IF EXISTS `sentmessages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sentmessages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `orderid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source` enum('WEB','API','IPTSP') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_no_column` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `json_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `senderID` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `group_id` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pages` int DEFAULT '0',
  `status` enum('Draft','Queue','Sending','Sent','Failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `units` decimal(10,2) NOT NULL DEFAULT '0.00',
  `sentFrom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Panel',
  `is_mms` int NOT NULL DEFAULT '0',
  `sms_count` int NOT NULL DEFAULT '0',
  `is_unicode` int NOT NULL DEFAULT '0',
  `IP` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Unknown',
  `gateway_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_type` enum('sendSms','groupSms','fileSms','DynamicSms','campaignSms') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `scheduleDateTime` datetime DEFAULT NULL,
  `search_param` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `error` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` int DEFAULT NULL,
  `blocked_status` enum('1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '1 = blocked and 2 = unblocked',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `content_type` enum('Text','Flash') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Text',
  `campaign_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_from` enum('WEB','API') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `sms_queued` int DEFAULT NULL,
  `sms_processing` int DEFAULT NULL,
  `sms_sent` int DEFAULT NULL,
  `sms_delivered` int DEFAULT NULL,
  `sms_failed` int DEFAULT NULL,
  `sms_blocked` int DEFAULT NULL,
  `is_complete` int DEFAULT NULL,
  `is_pause` enum('Active','Inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Inactive',
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  `total_recipient` int DEFAULT NULL,
  `total_cost` double(8,2) DEFAULT NULL,
  `is_dnd_applicable` enum('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'No',
  `client_transaction_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rn_code` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `long_sms` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_long_sms` tinyint(1) DEFAULT '0',
  `unicode` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_coding` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_flash` tinyint(1) DEFAULT '0',
  `flash` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_promotional` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_file_processed` tinyint DEFAULT NULL,
  `campaign_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `sms_type` (`sms_type`),
  KEY `sms_type_status` (`sms_type`,`status`),
  KEY `sms_type_status_scheduleDateTime` (`sms_type`,`status`,`scheduleDateTime`),
  KEY `user_id` (`user_id`),
  KEY `created_at` (`created_at`),
  KEY `source` (`source`),
  KEY `orderid` (`orderid`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sentmessages`
--

LOCK TABLES `sentmessages` WRITE;
/*!40000 ALTER TABLE `sentmessages` DISABLE KEYS */;
INSERT INTO `sentmessages` VALUES (1,3,'31729794720476607','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','23132132323',NULL,'2024-10-24 18:32:00',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-10-24 12:32:00','2024-10-24 12:32:00','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(2,3,'31729794947012073','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','54654656546556465465466',NULL,'2024-10-24 18:35:47',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-10-24 12:35:47','2024-10-24 12:35:47','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2024-10-25 01:01:00',0,'Draft',0.00,'Panel',0,0,0,'Unknown',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-10-24 13:01:00','2024-10-24 13:01:00','Text',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,NULL,NULL,'No',NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2024-10-25 01:05:39',0,'Draft',0.00,'Panel',0,0,0,'Unknown',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-10-24 13:05:39','2024-10-24 13:05:39','Text',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,NULL,NULL,'No',NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(5,3,'31729798376091290','WEB',NULL,'dsfsdfsfsdfsdf',NULL,'2222222222222','01799855302, 01712488651, 2342342342, 01712488651',NULL,'2024-10-24 19:32:56',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'groupSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-10-24 13:32:56','2024-10-24 13:32:56','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,4,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(6,3,'31729798523870927','WEB',NULL,'dfgdfgdgdgdg',NULL,'2222222222222','01799855302, 01712488651, 2342342342, 01712488651',NULL,'2024-10-24 19:35:23',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'groupSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-10-24 13:35:23','2024-10-24 13:35:23','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,4,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(7,3,'31729798645873289','WEB',NULL,'dfsdfsdfsdfsdfs',NULL,'2222222222222','01799855302, 01712488651, 2342342342, 01712488651',NULL,'2024-10-24 19:37:25',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'groupSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-10-24 13:37:25','2024-10-24 13:37:25','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,4,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(8,3,'31729803484605937','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','46645555555567897979',NULL,'2024-10-24 20:58:04',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-10-24 14:58:04','2024-10-24 14:58:04','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(9,3,'31729803557846462','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','345345345353453534535534',NULL,'2024-10-24 20:59:17',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-10-24 14:59:17','2024-10-24 14:59:17','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(10,3,'31729803669035784','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','345345345353535',NULL,'2024-10-24 21:01:09',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-10-24 15:01:09','2024-10-24 15:01:09','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(11,3,'31729803800212198','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','23423423424242424424',NULL,'2024-10-24 21:03:20',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-10-24 15:03:20','2024-10-24 15:03:20','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(12,3,'31729803911271266','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','33534553453453535',NULL,'2024-10-24 21:05:11',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-10-24 15:05:11','2024-10-24 15:05:11','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(13,3,'31729804511270363','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','3223434424',NULL,'2024-10-24 21:15:11',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-10-24 15:15:11','2024-10-24 15:15:11','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(14,3,'31729804558054507','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','345434534',NULL,'2024-10-24 21:15:58',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-10-24 15:15:58','2024-10-24 15:15:58','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(15,3,'31729804606322486','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','4354645664646464',NULL,'2024-10-24 21:16:46',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-10-24 15:16:46','2024-10-24 15:16:46','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(16,3,'31729804759369774','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','3453453535534',NULL,'2024-10-24 21:19:19',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-10-24 15:19:19','2024-10-24 15:19:19','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(17,3,'31729804817074846','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','353453553453',NULL,'2024-10-24 21:20:17',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-10-24 15:20:17','2024-10-24 15:20:17','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(18,3,'31729804864315824','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','34534234234242',NULL,'2024-10-24 21:21:04',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-10-24 15:21:04','2024-10-24 15:21:04','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(19,3,'31729804942168597','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','435345345354',NULL,'2024-10-24 21:22:22',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-10-24 15:22:22','2024-10-24 15:22:22','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(20,3,'31729804965338932','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','45345543534454645',NULL,'2024-10-24 21:22:45',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-10-24 15:22:45','2024-10-24 15:22:45','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(21,3,'31731265673051886','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','01903341643',NULL,'2024-11-10 19:07:53',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-10 13:07:53','2024-11-10 13:07:53','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(22,3,'31731265764324178','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','01903341643,01734183130',NULL,'2024-11-10 19:09:24',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-10 13:09:24','2024-11-10 13:09:24','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(23,3,'31731265808467972','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','01903341643,01734183130',NULL,'2024-11-10 19:10:08',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-10 13:10:08','2024-11-10 13:10:08','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(24,3,'31731265843304157','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','01903341643,01734183130',NULL,'2024-11-10 19:10:43',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-10 13:10:43','2024-11-10 13:10:43','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(25,3,'31731265865251662','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','01903341643,01734183130',NULL,'2024-11-10 19:11:05',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-10 13:11:05','2024-11-10 13:11:05','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(26,3,'31731265876041056','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','01903341643,01734183130',NULL,'2024-11-10 19:11:16',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-10 13:11:16','2024-11-10 13:11:16','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(27,3,'31731265900188265','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','01903341643,01734183130',NULL,'2024-11-10 19:11:40',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-10 13:11:40','2024-11-10 13:11:40','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(28,3,'31731266002468184','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','01734183130,01903341643',NULL,'2024-11-10 19:13:22',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-10 13:13:22','2024-11-10 13:13:22','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(29,3,'31731266068060964','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','01734183130,01903341643',NULL,'2024-11-10 19:14:28',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-10 13:14:28','2024-11-10 13:14:28','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(30,3,'31731266086837945','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','01734183130,01903341643',NULL,'2024-11-10 19:14:46',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-10 13:14:46','2024-11-10 13:14:46','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(31,3,'31731266116172335','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','01734183130,01903341643',NULL,'2024-11-10 19:15:16',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-10 13:15:16','2024-11-10 13:15:16','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(32,3,'31731266207370860','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','01734183130,01903341643',NULL,'2024-11-10 19:16:47',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-10 13:16:47','2024-11-10 13:16:47','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(33,3,'31731266268771737','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','01903341643,01903341643',NULL,'2024-11-10 19:17:48',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-10 13:17:48','2024-11-10 13:17:48','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(34,3,'31731266293800319','WEB',NULL,'sdfsfsfsdf',NULL,'2222222222222','01903341643,01903341643',NULL,'2024-11-10 19:18:13',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-10 13:18:13','2024-11-10 13:18:13','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(35,3,'31731352048192516','WEB',NULL,'Test sms',NULL,'2222222222222','8801629334432',NULL,'2024-11-11 19:07:28',0,'Queue',0.00,'Panel',0,0,2,'202.134.9.151',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-11 19:07:28','2024-11-11 19:07:28','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(36,3,'31731388226596953','WEB',NULL,'Test sms',NULL,'2222222222222','8801629334432',NULL,'2024-11-12 05:10:26',0,'Queue',0.00,'Panel',0,0,2,'202.164.212.77',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-12 05:10:26','2024-11-12 05:10:26','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(37,3,'31731398130831701','WEB',NULL,'Hello Mukul vai. How arw you',NULL,'16555','01734183130,01903341643',NULL,'2024-11-12 07:55:30',0,'Queue',0.00,'Panel',0,0,2,'103.35.168.93',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-12 07:55:30','2024-11-12 07:55:30','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(38,3,'31731398221330846','WEB',NULL,'Assalamualaikum',NULL,'16555','01734183130,01903341643',NULL,'2024-11-12 07:57:01',0,'Queue',0.00,'Panel',0,0,2,'103.35.168.93',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-12 07:57:01','2024-11-12 07:57:01','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(39,3,'31731399107282804','WEB',NULL,'Test sms NBR',NULL,'16555','8801629334432',NULL,'2024-11-12 08:11:47',0,'Queue',0.00,'Panel',0,0,2,'202.164.212.77',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-12 08:11:47','2024-11-12 08:11:47','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(40,3,'31731400769358620','WEB',NULL,'Vai this mesage from reve portal',NULL,'16555','01734183130',NULL,'2024-11-12 08:39:29',0,'Queue',0.00,'Panel',0,0,2,'103.35.168.93',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-12 08:39:29','2024-11-12 08:39:29','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(41,3,'31731400839180787','WEB',NULL,'Hi babu',NULL,'16555','01799855302',NULL,'2024-11-12 08:40:39',0,'Queue',0.00,'Panel',0,0,2,'103.35.168.93',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-12 08:40:39','2024-11-12 08:40:39','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(42,3,'31731401208299976','WEB',NULL,'Babua',NULL,'16555','01799855302',NULL,'2024-11-12 08:46:48',0,'Queue',0.00,'Panel',0,0,2,'103.35.168.93',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-12 08:46:48','2024-11-12 08:46:48','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(43,3,'31731403040110565','WEB',NULL,'Assalamualaikum everyone, Kemon achen apnara.',NULL,'16555','01799855302,01734183130',NULL,'2024-11-12 09:17:20',0,'Queue',0.00,'Panel',0,0,2,'103.35.168.93',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-12 09:17:20','2024-11-12 09:17:20','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(44,3,'31731403061311348','WEB',NULL,'Test sms',NULL,'16555','8801629334432',NULL,'2024-11-12 09:17:41',0,'Queue',0.00,'Panel',0,0,2,'202.164.212.77',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-12 09:17:41','2024-11-12 09:17:41','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(45,3,'31731414164388296','WEB',NULL,'Test',NULL,NULL,'8801629334432',NULL,'2024-11-12 12:22:44',0,'Queue',0.00,'Panel',0,0,2,'202.164.212.77',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-12 12:22:44','2024-11-12 12:22:44','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(46,3,'31731414405704370','WEB',NULL,'Test sms',NULL,'16555','8801629334432',NULL,'2024-11-12 12:26:45',0,'Queue',0.00,'Panel',0,0,2,'202.164.212.77',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-12 12:26:45','2024-11-12 12:26:45','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(47,3,'31731414889334929','WEB',NULL,'Test',NULL,'16555','8801629334432',NULL,'2024-11-12 12:34:49',0,'Queue',0.00,'Panel',0,0,2,'202.164.212.77',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-12 12:34:49','2024-11-12 12:34:49','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(48,3,'31731434575606735','WEB',NULL,'Hello',NULL,NULL,'01734183130',NULL,'2024-11-12 18:02:55',0,'Queue',0.00,'Panel',0,0,2,'103.152.213.53',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-12 18:02:55','2024-11-12 18:02:55','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(49,3,'31731434797135037','WEB',NULL,'hello!',NULL,NULL,'01734183130',NULL,'2024-11-12 18:06:37',0,'Queue',0.00,'Panel',0,0,2,'103.152.213.53',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-12 18:06:37','2024-11-12 18:06:37','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(50,3,'31731472728683660','WEB',NULL,'Good morning',NULL,'16555','01734183130,01903341643',NULL,'2024-11-13 04:38:48',0,'Queue',0.00,'Panel',0,0,2,'103.35.168.93',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-13 04:38:48','2024-11-13 04:38:48','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(51,3,'31731474060818463','WEB',NULL,'Good morning again',NULL,'16555','01734183130,01903341643',NULL,'2024-11-13 05:01:00',0,'Queue',0.00,'Panel',0,0,2,'103.35.168.93',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-13 05:01:00','2024-11-13 05:01:00','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(52,3,'31731474146547232','WEB',NULL,'Message pawar jonno Thanks',NULL,'16555','01734183130,01799855302',NULL,'2024-11-13 05:02:26',0,'Queue',0.00,'Panel',0,0,2,'103.35.168.93',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-13 05:02:26','2024-11-13 05:02:26','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(53,3,'31731474213369574','WEB',NULL,'Test sms',NULL,'16555','8801629334432',NULL,'2024-11-13 05:03:33',0,'Queue',0.00,'Panel',0,0,2,'202.164.212.77',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-13 05:03:33','2024-11-13 05:03:33','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(54,3,'31731474242067475','WEB',NULL,'Test sms',NULL,'16555','8801760391996',NULL,'2024-11-13 05:04:02',0,'Queue',0.00,'Panel',0,0,2,'202.164.212.77',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-13 05:04:02','2024-11-13 05:04:02','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(55,3,'31731485271197615','WEB',NULL,'Test sms',NULL,'16555','8801629334432',NULL,'2024-11-13 08:07:51',0,'Queue',0.00,'Panel',0,0,2,'202.164.212.77',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-13 08:07:51','2024-11-13 08:07:51','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(56,3,'31731586172333023','WEB',NULL,'Test sms for NRB',NULL,'16555','8801629334432',NULL,'2024-11-14 12:09:32',0,'Queue',0.00,'Panel',0,0,2,'202.164.212.77',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-14 12:09:32','2024-11-14 12:09:32','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(57,3,'31731586244912032','WEB',NULL,'Test sms NRB GP',NULL,'16555','8801760391996',NULL,'2024-11-14 12:10:44',0,'Queue',0.00,'Panel',0,0,2,'202.164.212.77',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-14 12:10:44','2024-11-14 12:10:44','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(58,3,'31731586269336342','WEB',NULL,'Test sms BL',NULL,'16555','8801980093983',NULL,'2024-11-14 12:11:09',0,'Queue',0.00,'Panel',0,0,2,'202.164.212.77',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-14 12:11:09','2024-11-14 12:11:09','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(59,3,'31731904503830572','WEB',NULL,'Test sms',NULL,'16555','8801629334432',NULL,'2024-11-18 04:35:03',0,'Queue',0.00,'Panel',0,0,2,'202.134.10.133',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-18 04:35:03','2024-11-18 04:35:03','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(60,3,'31731926768097574','WEB',NULL,'Test sms',NULL,'16555','8801629334432,8801760391996',NULL,'2024-11-18 10:46:08',0,'Queue',0.00,'Panel',0,0,2,'202.164.212.77',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-18 10:46:08','2024-11-18 10:46:08','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(61,3,'31732186814170084','WEB',NULL,'This regular test message',NULL,'16555','01629334432,01734183130',NULL,'2024-11-21 11:00:14',0,'Queue',0.00,'Panel',0,0,2,'103.35.168.92',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-21 11:00:14','2024-11-21 11:00:14','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(62,3,'31732190966725039','WEB',NULL,'Test SMS',NULL,'16555','8801629334432',NULL,'2024-11-21 12:09:26',0,'Queue',0.00,'Panel',0,0,2,'202.164.212.77',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-21 12:09:26','2024-11-21 12:09:26','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(63,3,'31732256300000738','WEB',NULL,'Test sms',NULL,NULL,'8801629334432',NULL,'2024-11-22 06:18:20',0,'Queue',0.00,'Panel',0,0,2,'118.179.104.170',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-22 06:18:20','2024-11-22 06:18:20','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(64,3,'31732961185335582','WEB',NULL,'Test sms',NULL,'2222222222222','01734183130',NULL,'2024-11-30 10:06:25',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-30 04:06:25','2024-11-30 04:06:25','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(65,3,'31732961460991116','WEB',NULL,'Test sms',NULL,'2222222222222','01734183130',NULL,'2024-11-30 10:11:00',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-30 04:11:00','2024-11-30 04:11:00','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(66,3,'31732961521205008','WEB',NULL,'Test sms',NULL,'2222222222222','01734183130',NULL,'2024-11-30 10:12:01',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-30 04:12:01','2024-11-30 04:12:01','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(67,3,'31732961685020150','WEB',NULL,'Test sms',NULL,'2222222222222','01734183130',NULL,'2024-11-30 10:14:45',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-30 04:14:45','2024-11-30 04:14:45','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(68,3,'31732961994148591','WEB',NULL,'AsASAsaS',NULL,'2222222222222','01734183130',NULL,'2024-11-30 10:19:54',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-30 04:19:54','2024-11-30 04:19:54','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(69,3,'31732962932441240','WEB',NULL,'Test sms',NULL,'2222222222222','01734183130',NULL,'2024-11-30 10:35:32',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-30 04:35:32','2024-11-30 04:35:32','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(70,3,'31732962957252282','WEB',NULL,'AsASAsaS',NULL,'2222222222222','000000000',NULL,'2024-11-30 10:35:57',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-30 04:35:57','2024-11-30 04:35:57','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(71,3,'31732964862189116','WEB',NULL,'Test sms',NULL,'2222222222222','2112122',NULL,'2024-11-30 11:07:42',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-30 05:07:42','2024-11-30 05:07:42','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(72,3,'31732965321150061','WEB',NULL,'Test sms',NULL,'2222222222222','',NULL,'2024-11-30 11:15:21',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'groupSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-30 05:15:21','2024-11-30 05:15:21','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(73,3,'31732965452416675','WEB',NULL,'AsASAsaS',NULL,'2222222222222','01629333222, 01734183130, 01734183131',NULL,'2024-11-30 11:17:32',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'groupSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-30 05:17:32','2024-11-30 05:17:32','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,3,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(74,3,'31732966287937086','WEB',NULL,'AsASAsaS',NULL,'2222222222222','01629334432,01799855302,01734183130,01734183131',NULL,'2024-11-30 11:31:27',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'groupSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-30 05:31:27','2024-11-30 05:31:27','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,4,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(75,3,'31732966319315793','WEB',NULL,'AsASAsaS',NULL,'2222222222222','01629334432,01799855302,01734183130,01734183131',NULL,'2024-11-30 11:31:59',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'groupSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-30 05:31:59','2024-11-30 05:31:59','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,4,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(76,992,'9921732988123785830','WEB',NULL,'Test message',NULL,'2222222222222','01734173153',NULL,'2024-11-30 17:35:23',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-30 11:35:23','2024-11-30 11:35:23','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(77,992,'9921732988873598866','WEB',NULL,'Ignorant branched humanity led now marianne too strongly entrance. Rose to shew bore no ye of paid rent form. Old design are dinner better nearer silent excuse. She which are maids boy sense her shade',NULL,'2222222222222','01734183130',NULL,'2024-11-30 17:47:53',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-30 11:47:53','2024-11-30 11:47:53','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(78,992,'9921732989264048414','WEB',NULL,'Test message',NULL,'2222222222222','232323',NULL,'2024-11-30 17:54:24',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-30 11:54:24','2024-11-30 11:54:24','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(79,992,'9921732989376465839','WEB',NULL,'Test message',NULL,'2222222222222','43434343434',NULL,'2024-11-30 17:56:16',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-30 11:56:16','2024-11-30 11:56:16','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(80,992,'9921732989636881614','WEB',NULL,'Test message',NULL,'2222222222222','324242423432432',NULL,'2024-11-30 18:00:36',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-30 12:00:36','2024-11-30 12:00:36','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(81,992,'9921732989662131634','WEB',NULL,'Test message',NULL,'2222222222222','24234234234234',NULL,'2024-11-30 18:01:02',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-30 12:01:02','2024-11-30 12:01:02','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(82,992,'9921732989719128765','WEB',NULL,'Test message',NULL,'2222222222222','434343434',NULL,'2024-11-30 18:01:59',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-30 12:01:59','2024-11-30 12:01:59','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(83,992,'9921732989780184217','WEB',NULL,'Test message',NULL,'2222222222222','345353453453',NULL,'2024-11-30 18:03:00',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-30 12:03:00','2024-11-30 12:03:00','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(84,992,'9921732990000892882','WEB',NULL,'Test message',NULL,'2222222222222','43535345,34535345345,34534534543',NULL,'2024-11-30 18:06:40',0,'Queue',0.00,'Panel',0,0,2,'127.0.0.1',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2024-11-30 12:06:40','2024-11-30 12:06:40','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(85,3,'31738612122463907','WEB',NULL,'Test sms',NULL,'8809612341550','8801568764949',NULL,'2025-02-04 01:48:42',0,'Queue',0.00,'Panel',0,0,2,'37.111.206.97',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2025-02-03 19:48:42','2025-02-03 19:48:42','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(86,3,'31738612466053091','WEB',NULL,'Test sms',NULL,'8809612341550','8801518900473',NULL,'2025-02-04 01:54:26',0,'Queue',0.00,'Panel',0,0,2,'37.111.206.97',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2025-02-03 19:54:26','2025-02-03 19:54:26','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(87,3,'31738612466707447','WEB',NULL,'Test sms',NULL,'8809612341550','8801518900473',NULL,'2025-02-04 01:54:26',0,'Queue',0.00,'Panel',0,0,2,'37.111.206.97',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2025-02-03 19:54:26','2025-02-03 19:54:26','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(88,3,'31738612605304715','WEB',NULL,'Test sms',NULL,'8809612341550','8801518900473',NULL,'2025-02-04 01:56:45',0,'Queue',0.00,'Panel',0,0,2,'37.111.206.97',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2025-02-03 19:56:45','2025-02-03 19:56:45','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(89,3,'31738613018456838','WEB',NULL,'Test sms',NULL,'8809612341550','8801518900473',NULL,'2025-02-04 02:03:38',0,'Queue',0.00,'Panel',0,0,2,'37.111.206.97',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2025-02-03 20:03:38','2025-02-03 20:03:38','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(90,3,'31738613236224122','WEB',NULL,'Test sms',NULL,'8809612341550','8801518900473',NULL,'2025-02-04 02:07:16',0,'Queue',0.00,'Panel',0,0,2,'37.111.206.97',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2025-02-03 20:07:16','2025-02-03 20:07:16','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(91,3,'31738613770819645','WEB',NULL,'Test sms',NULL,'8809612341550','8801518900473',NULL,'2025-02-04 02:16:10',0,'Queue',0.00,'Panel',0,0,2,'37.111.206.97',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2025-02-03 20:16:10','2025-02-03 20:16:10','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(92,3,'31738614030424956','WEB',NULL,'Test sms',NULL,'8809612341550','8801518900473',NULL,'2025-02-04 02:20:30',0,'Queue',0.00,'Panel',0,0,2,'37.111.206.97',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2025-02-03 20:20:30','2025-02-03 20:20:30','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(93,3,'31738614072223978','WEB',NULL,'Test sms',NULL,'8809612341550','8801568764949',NULL,'2025-02-04 02:21:12',0,'Queue',0.00,'Panel',0,0,2,'37.111.206.97',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2025-02-03 20:21:12','2025-02-03 20:21:12','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(94,3,'31738614386279244','WEB',NULL,'Test sms',NULL,'8809612341550','8801518900473',NULL,'2025-02-04 02:26:26',0,'Queue',0.00,'Panel',0,0,2,'37.111.206.97',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2025-02-03 20:26:26','2025-02-03 20:26:26','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(95,3,'31738614427789598','WEB',NULL,'Test sms',NULL,'8809612341550','8801568764949',NULL,'2025-02-04 02:27:07',0,'Queue',0.00,'Panel',0,0,2,'37.111.206.97',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2025-02-03 20:27:07','2025-02-03 20:27:07','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(96,3,'31738614452365027','WEB',NULL,'Test sms',NULL,'8809612341550','8801568764949',NULL,'2025-02-04 02:27:32',0,'Queue',0.00,'Panel',0,0,2,'37.111.206.97',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2025-02-03 20:27:32','2025-02-03 20:27:32','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(97,3,'31738614692226547','WEB',NULL,'Test sms',NULL,'8809612341550','8801568764949',NULL,'2025-02-04 02:31:32',0,'Queue',0.00,'Panel',0,0,2,'37.111.206.97',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2025-02-03 20:31:32','2025-02-03 20:31:32','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL),(98,3,'31738614721140079','WEB',NULL,'Test sms',NULL,'8809612341550','8801518900473',NULL,'2025-02-04 02:32:01',0,'Queue',0.00,'Panel',0,0,2,'37.111.206.97',NULL,'sendSms',NULL,NULL,NULL,NULL,NULL,NULL,'2025-02-03 20:32:01','2025-02-03 20:32:01','Text','WEB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inactive',0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `sentmessages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_last_activity_index` (`last_activity`),
  KEY `sessions_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('2nm7MUwvsza0mNLqRcBD0ua3V55dwCPziY7Xspzv',NULL,'71.6.199.23','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWkdZQ0hxbjJ0WU5NeGhXeThVdkF3MThnaWgzRTI0Z0FiTWIzV2FOOSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly8xMTYuMTkzLjIyMi4xOTQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1739191635),('4gCSkHVa4T6ELAhSm8uPKE1GJd5d5znfgJaD0ol4',NULL,'79.137.7.67','Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:130.0) Gecko/20100101 Firefox/130.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUFBUN1hsNHdmMW5qZHFVNjhueVlIMVc4TjhUOFhmSmQwamZ5YmRrbSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMTYuMTkzLjIyMi4xOTQ6ODE4MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1739150857),('7fJDB12whsutmk0bTxIxWjgffdXimIFt6r4CBTa1',NULL,'172.168.41.58','Mozilla/5.0 zgrab/0.x','YTozOntzOjY6Il90b2tlbiI7czo0MDoiV1JrMTRZYWtaWGJPZVJndnRQRnVadEg5UEVTdzZzQlFwdFRwWnBQMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMTYuMTkzLjIyMi4xOTQ6ODE4MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1739169698),('87XjhintJTL9yZeqHyUEckJMDCuj5osCSrmV0qKQ',NULL,'185.242.226.50','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.190 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUG1tWXQwOTRwQkQ3OEdNTVVzQlBqMEVXZjlLNXEycW5yZGo1U01ZMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMTYuMTkzLjIyMi4xOTg6NzAwNyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1739250374),('9zM4ATscCSBdJIXeDcm7ToN9HrHECd4T8gMQt7eZ',NULL,'185.242.226.50','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.190 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNjk2c1o1QzNhU0Y3WlMyQ0hjcHQ3UndSQk1oYnhvZVl1ZWZ5VGc5bSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMTYuMTkzLjIyMi4xOTg6NzAwNy9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1739250374),('B34HYyfMGovMg1OJVoi0o1sTzuQkBYWjR08sTxgx',NULL,'172.245.40.162','Mozilla/5.0 (compatible; SnoopSecInspect/1.1; +https://snoopsec.us.to/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSVdhbm11dDFTWXRVMjE1TlQ5NlVaMlpKdW5HZDY3TzhNTGVHQ3BzayI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMTYuMTkzLjIyMi4xOTQ6ODE4MS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1739196739),('diKOuWNpzsRrMMnS6plaLJ1z8Ni3qPyjWDOWf92H',NULL,'172.245.40.162','Mozilla/5.0 (compatible; SnoopSecInspect/1.1; +https://snoopsec.us.to/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOTBLcUNaRlkzY2lISU5QMUxSdzdhRVZqbDZSWkVQNnhYWkVRcFBUTCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMTYuMTkzLjIyMi4xOTQ6ODE4MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1739196739),('HJSIWKZUWNyUcTBkbNVxoS7TTdF4VFvAb89aDwXy',NULL,'5.135.238.157','Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:130.0) Gecko/20100101 Firefox/130.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWEZMVWc3emkxV1NzaXZMcUh6ZWVxSFZSUWtJWkVGazhOU3lVRWlKcSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMTYuMTkzLjIyMi4xOTQ6ODE4MS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1739151205),('ilJoY5m3JLXdMQvPjaBWsTGoCirxDpHy0FnkmufY',NULL,'79.137.7.68','Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:130.0) Gecko/20100101 Firefox/130.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSWNEWlIyU0ZNa0FDTUlIV2tQclpyOUJIbVY4WG94YkE3WWtGTW9MRiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMTYuMTkzLjIyMi4xOTQ6ODE4MS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1739151564),('ivrFAZSTmDAaxlv9GT5BPHIDAlW34ffOJJ0ssJAN',NULL,'3.137.180.188','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWkQwR1ljU1dLa3hkNHdDNlNKczdzWmswTzJSejZ4R2ZtNmZvdUFpciI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMTYuMTkzLjIyMi4xOTQ6ODE4MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1739155558),('LmTJQbbYiQKhEyhhUSjyEypyhH0Vgl2nQaFqgG9F',NULL,'3.137.180.188','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoialVPZmZFOG1Yb2txMlc1VUdOSDJiNk5lb1BZVElTVkpZUG9CNWFHQSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMTYuMTkzLjIyMi4xOTQ6ODE4MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1739155557),('sgQ9BN7PYr94EjOVvwJ4aYxIeCncXW6ZnEpHXfVi',NULL,'3.137.180.188','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNTZTUEZUZFpScTRYc080cWprNFhaUjJROWtjNTRMRENDSk5CVlhOVyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMTYuMTkzLjIyMi4xOTQ6ODE4MS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1739155558),('SSrhWQRw1GCKGYXzKpkVCwwZkjyVuodvRAIDc0a4',3,'103.152.213.53','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMzJ0M2NBcmJuRUEwODl1S0trSGFzUXZsbFAweFdLT0VtZVIyT0tsNiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MztzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNzoiaHR0cDovLzExNi4xOTMuMjIyLjE5ODo3MDA3L2Rhc2hib2FyZCI7fX0=',1739289911),('um9BnJ9VfNBNfNrwZm6raN2fHFsmCiPNiEanQCly',NULL,'162.142.125.213','Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMmltTENUaExUVFJ1T3F4U0FsVW9nSFZGY3FGSDR0VE1JUmpXSlk1QiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMTYuMTkzLjIyMi4xOTg6NzAwNyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1739245518),('vkWOUDX1c5cttw4EyzgvZMoBE2bU1qBNLvN2FmFK',NULL,'34.76.203.56','python-requests/2.32.3','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMW9HekNSN3hqZTYzb0NjTzVqTkhtMXhRUDJweHRSZmtoQzEyZHhWeSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMTYuMTkzLjIyMi4xOTQ6ODE4MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1739286207),('vNunhRZrDYsn6K68tUhaNLv57PIzr9zecU0tHu5w',NULL,'162.142.125.213','Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZjlmMmh4M0dwSkc3SGdOeW5RZXh3dUk4ZmF1UDBpcGZHN0lDdTBJcSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMTYuMTkzLjIyMi4xOTg6NzAwNy9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1739245531),('vw5SbzmDi2mLgs9piWDNy5Q0hDPtB2RrbSbVrI7R',NULL,'162.142.125.213','Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRFE3bWJaTU1FN3pIYURmVGwyZGNqbDQxdWRTOEhFS0NiN2g0ZTJWcyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMTYuMTkzLjIyMi4xOTg6NzAwNyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1739245544);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_configs`
--

DROP TABLE IF EXISTS `site_configs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `site_configs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thana` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reseller_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=254 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_configs`
--

LOCK TABLES `site_configs` WRITE;
/*!40000 ALTER TABLE `site_configs` DISABLE KEYS */;
INSERT INTO `site_configs` VALUES (223,'GOTMYHOST',NULL,'8801735772014','nasimhridoyp@gmail.com','Dhaka','Dhaka','Dhaka','bulksms.xensms.com',165,'2022-09-26 10:27:42','2022-12-17 06:29:27'),(224,'Brother IT',NULL,'01995390400','brotherit2013@gmail.com','Dhaka','Dhaka','Dhaka',NULL,166,'2022-09-27 07:33:29','2022-09-27 07:33:29'),(225,'IT Bondhu Limited',NULL,'01938001000','admin@itbondhu.com','Dhaka','Dhaka','Dhaka',NULL,167,'2022-09-28 09:55:28','2022-09-28 09:55:28'),(226,'CGIT','cgit.png','01814030844','smile@cgit.pro','Dhaka','Dhaka','Dhaka','app.smsbd.pro',168,'2022-09-29 07:40:27','2024-03-01 09:46:34'),(227,'Turtle Solutions ltd',NULL,'01777514059','rifat@turtlesolutionsltd.com','Dhaka','Dhaka','Dhaka',NULL,169,'2022-10-11 11:17:45','2022-10-11 11:17:45'),(228,'Checkpoint Systems',NULL,'01755579999','Mohsin.Hasan@checkpt.com','Dhaka','Dhaka','Dhaka',NULL,170,'2022-10-11 12:10:48','2022-10-11 12:10:48'),(229,'Phoenix Software',NULL,'01743056775','ps@gmail.com','Dhaka','Dhaka','Dhaka',NULL,171,'2022-10-12 07:19:41','2022-10-12 07:19:41'),(230,'Slash Digital',NULL,'1797356794','Farhad@slashdigital.com.bd','Dhaka','Dhaka','Dhaka',NULL,172,'2022-10-12 09:18:03','2022-10-12 09:18:03'),(231,'Zelthost Network',NULL,'01922025695','zelthost@gmail.com','Dhaka','Dhaka','Dhaka',NULL,173,'2022-10-12 09:27:51','2022-10-12 09:27:51'),(232,'QUICK SMS BD',NULL,'01843885002','support@quicksms.xyz','Dhaka','Dhaka','Dhaka',NULL,174,'2022-10-12 11:28:45','2022-10-12 11:28:45'),(233,'Kutumbita Bangladesh ltd',NULL,'01788887386','alvi@kutumbita.com','Dhaka','Dhaka','Dhaka',NULL,175,'2022-10-13 06:10:27','2022-10-13 06:10:27'),(234,'Etech Systems Ltd',NULL,'01777333678','info@etechbd.com','Dhaka','Dhaka','Dhaka',NULL,176,'2022-10-13 07:08:41','2022-10-13 07:08:41'),(235,'SKY Trip',NULL,'01712501941','skytrip.com@gmail.com','Dhaka','Dhaka','Dhaka',NULL,177,'2022-10-13 09:35:49','2022-10-13 09:35:49'),(236,'Touch and Solve',NULL,'01913651485','ceo@touchandsolve.com','Dhaka','Dhaka','Dhaka',NULL,178,'2022-10-13 09:41:41','2022-10-13 09:41:41'),(237,'Babsa Bazar',NULL,'01917778898','sajib.mallik809@gmail.com','Dhaka','Dhaka','Dhaka',NULL,179,'2022-10-13 09:49:06','2022-10-13 09:49:06'),(238,'Galax BD',NULL,'01841730367','shahedsaad@gmail.com','Dhaka','Dhaka','Dhaka',NULL,180,'2022-10-13 09:54:40','2022-10-13 09:54:40'),(239,'Jamal Software Technology',NULL,'01913625970','jamaluddinpf@gmail.com','Dhaka','Dhaka','Dhaka',NULL,181,'2022-10-13 11:59:17','2022-10-13 11:59:17'),(240,'Samrat ICT Ltd.',NULL,'01914037014','samratict@gmail.com','Dhaka','Dhaka','Dhaka',NULL,182,'2022-10-16 11:22:31','2022-10-16 11:22:31'),(241,'BRAC University',NULL,'01975404060','aminul@bracu.ac.bd','Dhaka','Dhaka','Dhaka',NULL,183,'2022-10-16 12:09:54','2022-10-16 12:09:54'),(242,'Topu',NULL,'8801629334432','subbirchowdhury27@gmail.com','196, Munshibari Sharak, North Ibrahimpur\r\nKafrul, Mirpur, Dhaka 1206','DHK','DHK',NULL,184,'2022-10-17 09:30:51','2022-10-17 09:30:51'),(243,'Alinaz Graphic Center',NULL,'01817142579','alinazbd@yahoo.com','Dhaka','Dhaka','Dhaka',NULL,185,'2022-10-17 09:59:55','2022-10-17 09:59:55'),(244,'UltraSoft Technologies Limited',NULL,'01841655508','miloncse014@gmail.com','Dhaka','Dhaka','Dhaka','http://sms.ultrasoftbd.com/',186,'2022-10-17 10:24:42','2024-03-22 17:58:01'),(245,'Green Web Bangladesh',NULL,'01623505818','greenweb.com.bd@gmail.com','Dhaka','Dhaka','Dhaka',NULL,187,'2022-11-06 10:02:34','2022-11-06 10:02:34'),(246,'FIFOTech',NULL,'01927666222','uzzal@fifo-tech.com','Dhaka','Dhaka','Dhaka',NULL,188,'2022-11-08 05:45:03','2022-11-08 05:45:03'),(247,'Rishan Technology',NULL,'01919306154','ceo@rishantech.biz','Dhaka','Dhaka','Dhaka',NULL,189,'2022-11-09 12:26:03','2022-11-09 12:26:03'),(248,'SSL Wireless',NULL,'8801675485672','firoz.hossain@sslwireless.com','Dhaka','Dhaka','Dhaka',NULL,190,'2022-11-13 10:41:34','2022-11-13 10:41:34'),(249,'aamra Infotainment ltd',NULL,'01716148116','abdullah.mahmud@aamra.com.bd','Dhaka','Dhaka','Dhaka',NULL,191,'2022-11-14 11:42:33','2022-11-14 11:42:33'),(250,'MetroAbir',NULL,'01629334432','abir\"metro.net.bd','Dhaka','Dhaka','Dhaka',NULL,192,'2023-06-19 12:38:02','2023-06-19 12:38:02'),(251,'Test Reseller',NULL,'8801629334432','sabbirtopu27@gmail.com','Dhaka','Dhaka','Dhaka','https://portal2.mdlsms.com',193,'2023-12-27 18:03:01','2023-12-27 18:03:01'),(252,'Mim_SMS',NULL,'880','mimsms@gmail.com','dhaka','Dhaka','Dhaka',NULL,194,'2024-03-10 14:30:25','2024-03-10 14:30:25'),(253,'Web Techno Soft Ltd.',NULL,'01575099959','ashraf.jahan@webtechnosoft.com','Dhaka','Dhaka','Dhaka',NULL,195,'2024-03-17 08:52:13','2024-03-17 08:52:13');
/*!40000 ALTER TABLE `site_configs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sms_records`
--

DROP TABLE IF EXISTS `sms_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sms_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sms_records`
--

LOCK TABLES `sms_records` WRITE;
/*!40000 ALTER TABLE `sms_records` DISABLE KEYS */;
INSERT INTO `sms_records` VALUES (1,'0','ACCEPTD','415462843','2024-11-13 05:01:01','2024-11-13 05:01:01'),(2,'0','ACCEPTD','415462844','2024-11-13 05:01:01','2024-11-13 05:01:01'),(3,'0','ACCEPTD','415463594','2024-11-13 05:02:26','2024-11-13 05:02:26'),(4,'0','ACCEPTD','415463595','2024-11-13 05:02:26','2024-11-13 05:02:26'),(5,'0','ACCEPTD','415463961','2024-11-13 05:03:33','2024-11-13 05:03:33'),(6,'0','ACCEPTD','415464125','2024-11-13 05:04:02','2024-11-13 05:04:02'),(7,'0','ACCEPTD','415556195','2024-11-13 08:07:51','2024-11-13 08:07:51'),(8,'0','ACCEPTD','416615596','2024-11-14 12:09:32','2024-11-14 12:09:32'),(9,'0','ACCEPTD','416623907','2024-11-14 12:10:44','2024-11-14 12:10:44'),(10,'0','ACCEPTD','416627978','2024-11-14 12:11:09','2024-11-14 12:11:09'),(11,'0','ACCEPTD','419516463','2024-11-18 04:35:03','2024-11-18 04:35:03'),(12,'0','ACCEPTD','419726485','2024-11-18 10:46:08','2024-11-18 10:46:08'),(13,'0','ACCEPTD','419726486','2024-11-18 10:46:08','2024-11-18 10:46:08'),(14,'0','ACCEPTD','422524820','2024-11-21 17:00:14','2024-11-21 17:00:14'),(15,'0','ACCEPTD','422524821','2024-11-21 17:00:14','2024-11-21 17:00:14'),(16,'0','ACCEPTD','422704873','2024-11-21 18:09:26','2024-11-21 18:09:26');
/*!40000 ALTER TABLE `sms_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `template`
--

DROP TABLE IF EXISTS `template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `template` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int NOT NULL,
  `status` enum('Active','Inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `template`
--

LOCK TABLES `template` WRITE;
/*!40000 ALTER TABLE `template` DISABLE KEYS */;
INSERT INTO `template` VALUES (1,'sdfsdf','sdfsfsfsdf',3,'Active','2024-09-19 00:37:05','2024-09-19 00:37:05'),(2,'Test','Test sms',3,'Active','2024-11-19 10:23:32','2024-11-19 10:23:32'),(3,'saSAsa','AsASAsaS',991,'Active','2024-11-23 23:15:17','2024-11-23 23:15:17'),(4,'test template','Test message',992,'Active','2024-11-30 11:32:05','2024-11-30 11:32:05');
/*!40000 ALTER TABLE `template` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `amount` int NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_user_id_foreign` (`user_id`),
  CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_group`
--

DROP TABLE IF EXISTS `user_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_group` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_group`
--

LOCK TABLES `user_group` WRITE;
/*!40000 ALTER TABLE `user_group` DISABLE KEYS */;
INSERT INTO `user_group` VALUES (1,'Superadmin','Access over every options','Active','0000-00-00 00:00:00','0000-00-00 00:00:00'),(2,'Admin','Access Full Option Without Few','Active','0000-00-00 00:00:00','0000-00-00 00:00:00'),(4,'Customer','Customer','Active','0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `user_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_wallet`
--

DROP TABLE IF EXISTS `user_wallet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_wallet` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `balance` int DEFAULT '0',
  `balance_type` enum('Debit','Credit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Debit',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expire_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_wallet`
--

LOCK TABLES `user_wallet` WRITE;
/*!40000 ALTER TABLE `user_wallet` DISABLE KEYS */;
INSERT INTO `user_wallet` VALUES (2,989,20,'Debit','2024-11-23 01:55:14','2024-11-23 01:55:14',NULL),(3,989,20,'Credit','2024-11-23 01:56:18','2024-11-23 01:56:18',NULL),(4,989,20,'Credit','2024-11-23 02:04:43','2024-11-23 02:04:43',NULL),(5,991,10000,'Credit','2024-11-24 01:08:20','2024-11-24 01:08:20',NULL),(6,991,500,'Debit','2024-11-24 01:18:54','2024-11-24 01:18:54',NULL),(7,991,50,'Debit','2024-11-24 01:22:33','2024-11-24 01:22:33',NULL),(8,991,23,'Debit','2024-11-24 01:22:51','2024-11-24 01:22:51',NULL),(9,992,10,'Debit','2024-11-30 05:52:40','2024-11-30 05:52:40',NULL),(10,992,10,'Debit','2024-11-30 07:38:44','2024-11-30 07:38:44',NULL);
/*!40000 ALTER TABLE `user_wallet` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_user_group` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login_time` datetime DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE','PENDING') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tps` int NOT NULL DEFAULT '0',
  `dipping` enum('Active','Inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Inactive',
  `created_by` int NOT NULL DEFAULT '0',
  `APIKEY` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_type` enum('prepaid','postpaid') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'prepaid',
  `mrc_otc` decimal(30,6) NOT NULL DEFAULT '0.000000',
  `duration_validity` int NOT NULL DEFAULT '30',
  `bill_start` int NOT NULL DEFAULT '0',
  `reseller_id` int unsigned DEFAULT NULL,
  `assign_user_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_rate_id` int DEFAULT NULL,
  `email_rate_id` int DEFAULT NULL,
  `available_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `push_pull_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dlr_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_username_unique` (`username`),
  UNIQUE KEY `user_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=996 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (3,1,'Super Admin','superadmin','superadmin@example.com',NULL,'$2y$12$ZsKvu/EYvoJLVJxsoqWoGeqr/p003Y3EdwpmOKYd3S2pRbY7FH1wO','Nakla','01712377506',NULL,'ACTIVE',NULL,0,'Active',0,'2y10RgWRjG7QKLAxqXp31FomeOWwsXBnbK77OuPdOUoaIxLxdrIrI06y4','prepaid',0.000000,30,0,NULL,NULL,1,NULL,10510.00,'Wz8e3u9E5M9sMNGzFVGUWfkURQybH7IjTQt7NDDhCxQkPO3X8GCXA60OTQYF',NULL,'2024-11-30 05:52:40',NULL,NULL),(989,4,'Md. Mubaktaqi Raham Chand','sombor','sombororbund@gmail.com',NULL,'$2y$10$1uIuW0WWZvdJC1cAa2gLg.M6kcyB.Enjht/O3qpuLAqNQeI3BfgYO','Kushtia','01712488651',NULL,'ACTIVE',NULL,2,'Inactive',0,NULL,'prepaid',0.000000,30,0,NULL,NULL,1,NULL,10000.00,NULL,'2024-11-22 23:24:00','2024-11-23 04:55:11',NULL,NULL),(992,4,'Brac Bank','bracbank','bracbank@gmail.com',NULL,'123456','Dhaka Bangladesh','258963256',NULL,'ACTIVE',NULL,10,'Inactive',0,NULL,'prepaid',0.000000,30,0,NULL,NULL,3,NULL,9.40,NULL,'2024-11-30 05:51:26','2025-01-29 10:26:52',NULL,NULL),(994,4,'Mizanur rahaman','mizan','engrmukul@hotmail.com',NULL,'$2y$12$Fd7IF94C2rgjDrPyrB2crO1k3s9MAt5Qa6/wJDcrnU6A55aqgzyc6','Dhaka Bangladesh','01734183130',NULL,'ACTIVE',NULL,101,'Inactive',0,NULL,'prepaid',0.000000,30,0,NULL,NULL,3,NULL,0.00,NULL,'2024-11-30 06:01:58','2024-11-30 06:01:58',NULL,NULL),(995,4,'Mizanur rahaman 3','mizan123','engrmukul123@hotmail.com',NULL,'$2y$12$RjXCKRDa2hoASJliB0tN3.RIngCuT66FD/kR8hm5xl84.8eabjJJ6','Dhaka Bangladesh','01734183130',NULL,'ACTIVE',NULL,101,'Inactive',0,NULL,'prepaid',0.000000,30,0,NULL,NULL,3,NULL,0.00,NULL,'2024-11-30 06:03:57','2024-11-30 06:03:57',NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-02-11 22:06:55
