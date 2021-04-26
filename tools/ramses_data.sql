-- MySQL dump 10.13  Distrib 8.0.23, for Linux (x86_64)
--
-- Host: localhost    Database: ramses
-- ------------------------------------------------------
-- Server version	8.0.23-0ubuntu0.20.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `ram_applicationfiletype`
--

LOCK TABLES `ram_applicationfiletype` WRITE;
/*!40000 ALTER TABLE `ram_applicationfiletype` DISABLE KEYS */;
INSERT INTO `ram_applicationfiletype` VALUES (1,14,17,'native','2021-03-31 11:04:12',0),(2,14,18,'native','2021-03-31 11:04:14',0),(3,14,19,'import','2021-03-31 11:04:16',0),(6,14,20,'import','2021-03-31 11:04:20',0),(7,14,19,'export','2021-03-31 11:04:23',0),(8,14,20,'export','2021-03-31 11:04:24',0),(9,15,19,'import','2021-03-31 11:04:59',0),(10,15,20,'import','2021-03-31 11:05:00',0),(11,15,19,'export','2021-03-31 11:05:02',0),(12,15,20,'export','2021-03-31 11:05:03',0),(13,15,21,'native','2021-03-31 11:05:27',0),(14,14,21,'import','2021-03-31 11:05:31',0),(15,14,21,'export','2021-03-31 11:05:33',0),(16,15,22,'import','2021-04-02 14:47:49',0),(17,15,22,'export','2021-04-02 14:47:50',0),(18,14,22,'import','2021-04-02 14:47:53',0),(19,14,22,'export','2021-04-02 14:47:54',0),(20,16,20,'import','2021-04-02 14:48:12',0),(21,16,19,'import','2021-04-02 14:48:13',0),(22,16,22,'import','2021-04-02 14:48:14',0),(23,16,20,'export','2021-04-02 14:48:16',0),(24,16,19,'export','2021-04-02 14:48:17',0),(25,16,22,'export','2021-04-02 14:48:19',0),(26,16,25,'import','2021-04-02 14:52:45',0),(27,16,25,'export','2021-04-02 14:52:48',0),(28,16,23,'native','2021-04-02 14:52:51',0),(29,16,24,'native','2021-04-02 14:52:53',0),(30,17,26,'native','2021-04-02 14:54:36',0),(31,17,20,'import','2021-04-02 14:54:39',0),(32,17,19,'import','2021-04-02 14:54:40',0),(33,17,22,'import','2021-04-02 14:54:42',0),(34,17,20,'export','2021-04-02 14:54:48',0),(35,17,19,'export','2021-04-02 14:54:49',0),(36,17,22,'export','2021-04-02 14:54:51',0),(37,18,19,'import','2021-04-02 14:58:30',0),(38,18,20,'import','2021-04-02 14:58:32',0),(39,18,22,'import','2021-04-02 14:58:34',0),(40,18,22,'export','2021-04-02 14:58:36',0),(41,18,19,'export','2021-04-02 14:58:38',0),(42,18,20,'export','2021-04-02 14:58:39',0),(43,19,20,'import','2021-04-02 14:59:13',0),(44,19,19,'import','2021-04-02 14:59:14',0),(45,19,22,'import','2021-04-02 14:59:16',0),(46,19,20,'export','2021-04-02 14:59:18',0),(47,19,22,'export','2021-04-02 14:59:19',0),(48,19,19,'export','2021-04-02 14:59:20',0),(49,20,19,'import','2021-04-02 14:59:43',0),(50,20,20,'import','2021-04-02 14:59:44',0),(51,20,22,'import','2021-04-02 14:59:46',0),(52,20,20,'export','2021-04-02 14:59:47',0),(53,20,19,'export','2021-04-02 14:59:49',0),(54,20,22,'export','2021-04-02 14:59:50',0),(56,18,27,'import','2021-04-02 15:04:37',0),(57,16,27,'import','2021-04-02 15:04:45',0),(58,16,27,'export','2021-04-02 15:04:47',0),(59,20,27,'import','2021-04-02 15:04:57',0),(60,19,27,'import','2021-04-02 15:05:00',0),(61,21,22,'import','2021-04-02 15:21:32',0),(62,21,25,'import','2021-04-02 15:21:36',0),(63,21,27,'import','2021-04-02 15:21:38',0),(64,21,25,'export','2021-04-02 15:21:45',0),(65,21,27,'export','2021-04-02 15:21:46',0),(66,21,31,'native','2021-04-02 15:26:05',0),(67,22,20,'import','2021-04-02 15:38:47',0),(68,22,19,'import','2021-04-02 15:38:48',0),(69,22,22,'import','2021-04-02 15:38:50',0),(70,22,20,'export','2021-04-02 15:38:52',0),(71,22,19,'export','2021-04-02 15:38:53',0),(72,22,22,'export','2021-04-02 15:38:56',0),(73,23,33,'import','2021-04-02 15:40:27',0),(74,23,32,'native','2021-04-02 15:40:32',0),(75,22,33,'export','2021-04-02 15:40:34',0),(76,23,33,'export','2021-04-02 15:40:37',0),(77,22,33,'import','2021-04-02 15:40:40',0),(78,24,33,'import','2021-04-02 15:41:03',0),(79,24,22,'import','2021-04-02 15:41:06',0),(80,25,35,'native','2021-04-02 16:44:00',0),(81,25,25,'import','2021-04-02 16:44:12',0),(82,25,22,'import','2021-04-02 16:44:14',0),(83,25,20,'import','2021-04-02 16:44:16',0),(84,25,19,'import','2021-04-02 16:44:18',0),(85,25,34,'import','2021-04-02 16:44:23',0),(86,25,36,'import','2021-04-02 16:44:26',0),(87,25,20,'export','2021-04-02 16:44:32',0),(88,25,25,'export','2021-04-02 16:44:36',0),(89,25,27,'export','2021-04-02 16:44:38',0),(90,25,19,'export','2021-04-02 16:44:40',0),(91,25,22,'export','2021-04-02 16:44:44',0),(92,25,27,'import','2021-04-02 16:44:46',0),(93,26,36,'native','2021-04-02 16:45:10',0),(94,26,21,'import','2021-04-02 16:45:17',0),(95,26,34,'import','2021-04-02 16:45:20',0),(96,26,22,'import','2021-04-02 16:45:22',0),(97,26,19,'import','2021-04-02 16:45:24',0),(98,26,20,'import','2021-04-02 16:45:25',0),(99,26,21,'export','2021-04-02 16:45:40',0),(100,26,19,'export','2021-04-02 16:45:42',0),(101,26,20,'export','2021-04-02 16:45:44',0),(102,26,22,'export','2021-04-02 16:45:46',0),(103,27,34,'native','2021-04-02 16:46:10',0),(104,27,20,'import','2021-04-02 16:46:15',0),(105,27,19,'import','2021-04-02 16:46:16',0),(106,27,19,'export','2021-04-02 16:46:19',0),(107,28,37,'native','2021-04-02 16:56:04',0),(108,28,19,'import','2021-04-02 16:56:07',0),(109,28,20,'import','2021-04-02 16:56:08',0),(110,28,21,'import','2021-04-02 16:56:10',0),(111,28,33,'export','2021-04-02 16:56:16',0),(112,28,19,'export','2021-04-02 16:56:22',0),(113,28,20,'export','2021-04-02 16:56:23',0),(114,23,22,'import','2021-04-02 17:07:20',0),(115,23,20,'import','2021-04-02 17:07:22',0),(116,23,19,'import','2021-04-02 17:07:24',0);
/*!40000 ALTER TABLE `ram_applicationfiletype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_applications`
--

LOCK TABLES `ram_applications` WRITE;
/*!40000 ALTER TABLE `ram_applications` DISABLE KEYS */;
INSERT INTO `ram_applications` VALUES (14,'13b6c954-2738-5d30-a2f1-80f34753fcfe','Adobe After Effects','Ae','','2021-03-31 11:04:48',0,0),(15,'af92e804-db38-5692-9a76-5aa35edb3b39','Adobe Photoshop','Ps','','2021-03-31 11:04:54',0,0),(16,'0acd35c3-7955-583d-b998-e9ce4b6e1d27','Autodesk Maya','Maya','','2021-04-02 14:52:40',0,0),(17,'f99216b2-a162-5f13-bcf3-d27d14807815','The Foundry Nuke','Nuke','','2021-04-02 14:53:08',0,0),(18,'ea7ea53e-6df7-52d6-80d1-416db82e7767','The Foundry Mari','Mary','','2021-04-02 14:58:48',0,0),(19,'f7c8f5dc-bfbc-57a5-8e52-8815befda601','Adobe Substance Designer','SubstanceD','','2021-04-02 14:59:09',0,0),(20,'2ff0c757-526a-53e1-8704-d11f84960311','Adobe Substance Painter','SubstanceP','','2021-04-02 14:59:40',0,0),(21,'f53cf8ac-f452-5979-96bb-598ceea94256','SideFX Houdini','Houdini','','2021-04-02 15:25:41',0,0),(22,'bcda2933-8dfa-5689-bf2c-d4d07c16ea95','Avid Media Composer','MediaComp','','2021-04-02 15:39:21',0,0),(23,'4cc555dd-658a-56f9-917b-d074cad09aa2','Adobe Premiere Pro','Pr','','2021-04-02 15:39:51',0,0),(24,'6f00dfc5-3f43-5200-baa5-99ab44cafd70','Blackmagicdesign DaVinci Resolve','Resolve','','2021-04-02 15:42:06',0,0),(25,'253f1c60-3eb0-5965-9016-631973483cc9','Blender','Blen','/snap/blender/current/blender','2021-04-02 16:43:53',0,0),(26,'6a96f85f-cd7e-5f9e-8cf0-42eb458a5286','Krita','Krita','','2021-04-02 16:45:05',0,0),(27,'c16351f3-f19d-557d-adef-f7a75f5509a3','Inkscape','Inkscape','','2021-04-02 16:46:24',0,0),(28,'3322817b-84a2-54c8-8d19-494bba1065af','Wonderunit Storyboarder','Stboarder','','2021-04-02 16:56:01',0,0);
/*!40000 ALTER TABLE `ram_applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_assetgroups`
--

LOCK TABLES `ram_assetgroups` WRITE;
/*!40000 ALTER TABLE `ram_assetgroups` DISABLE KEYS */;
INSERT INTO `ram_assetgroups` VALUES (11,'60af61d1-767a-5bd4-a8c6-066528d020f3','Props','PROP',12,'2021-03-31 11:13:50',0,0),(12,'0faf70ff-4a7b-52a6-9beb-545c35118945','Characters','CHAR',12,'2021-03-31 11:13:51',0,0),(13,'688e1cec-5e6c-5a6c-b8d5-0fedca27b99e','Backgrounds','BG',12,'2021-03-31 11:13:52',0,0),(14,'32193e8e-c999-5410-aa22-405f07444a0e','Characters','CHAR',14,'2021-04-02 16:46:56',0,0),(15,'5bc98436-a5a6-505d-96b6-99902943c730','Props','PROP',14,'2021-04-02 16:46:57',0,0),(16,'f97fece5-9567-5a78-97da-3b3ad25fa887','Sets','SET',14,'2021-04-02 16:48:05',0,0);
/*!40000 ALTER TABLE `ram_assetgroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_assets`
--

LOCK TABLES `ram_assets` WRITE;
/*!40000 ALTER TABLE `ram_assets` DISABLE KEYS */;
INSERT INTO `ram_assets` VALUES (27,'fe749770-ff85-591c-a212-2bb2a5f10173','Bâton de marche','BATON','baton,accessoire,outil',11,'2021-03-31 11:14:16',0,0),(28,'6caebaa3-47fe-55a5-b28e-4badfe54d4ad','Tristan','TRI','main character,male',14,'2021-04-02 16:48:39',0,0),(29,'a55d6bc1-a1d3-5cab-95c6-bd5880486b13','Isolde','IS','main character,female',14,'2021-04-13 16:59:30',0,0),(30,'ae3359bb-d629-5b8b-8346-46184246b7ee','Excalibur','EXC','sword,weapon',15,'2021-04-13 16:39:59',0,0),(31,'264bce9a-2da6-5b53-a08c-96aabb1a1d25','Tintagel','TIN','city,castle',16,'2021-04-13 16:40:02',0,0),(33,'7119addc-9df1-5be3-9315-02a8499b6831','Boat','BOAT','vehicle,sea',15,'2021-04-26 07:29:19',1,0),(36,'7afbdcd0-027d-506d-aaa7-21c3377cd2b6','Boat','BOAT2','vehicle',15,'2021-04-14 17:17:40',1,0),(57,'88cde35f-e828-5fda-9e7c-906454662e59','Sea','SEA','sea',16,'2021-04-25 12:05:43',0,0),(87,'2d5a14a7-189b-5bf4-8ce7-d79f98aefec1','Boat','NEW','',15,'2021-04-26 07:29:24',0,0);
/*!40000 ALTER TABLE `ram_assets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_colorspaces`
--

LOCK TABLES `ram_colorspaces` WRITE;
/*!40000 ALTER TABLE `ram_colorspaces` DISABLE KEYS */;
/*!40000 ALTER TABLE `ram_colorspaces` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_filetypes`
--

LOCK TABLES `ram_filetypes` WRITE;
/*!40000 ALTER TABLE `ram_filetypes` DISABLE KEYS */;
INSERT INTO `ram_filetypes` VALUES (17,'b1b3c25b-88d8-5591-9a19-37fcbc4015e2','After Effects Project','.aep','aep,aepx',0,'2021-03-31 11:03:19',0,0),(18,'02a9a1c8-0e29-5546-b15a-c7dc0b688ab2','After Effects Template','.aet','aet',0,'2021-03-31 11:03:34',0,0),(19,'0883e6ac-0bce-5e49-aa54-c2b2c2660ebb','PNG Image','.png','png',1,'2021-03-31 11:03:45',0,0),(20,'661f44fa-ae6e-54d9-9b3e-e0ff7775469b','JPEG image','.jpg','jpg,jpeg',1,'2021-03-31 11:03:58',0,0),(21,'a93c7dbc-39e5-5423-98cd-3ac2052f7b00','Photoshop','.psd','psd,psb',0,'2021-03-31 11:05:22',0,0),(22,'225290c3-9631-57c3-b97b-48b4abe53817','openEXR Image Data','.exr','exr',0,'2021-04-02 14:47:35',0,0),(23,'bb46c31e-e535-5276-aaa7-dd301d3191cc','Maya Scene (Binary)','.mb','mb',0,'2021-04-02 14:48:39',0,0),(24,'eaf182c7-31ae-510d-b33a-3c167527fd4a','Maya Scene (ASCII)','.ma','ma',0,'2021-04-02 14:48:54',0,0),(25,'f438d3bb-ca21-530a-909f-839f3a374aa9','Albembic Geometry','.abc','abc',0,'2021-04-02 14:52:33',0,0),(26,'8d70cc65-1c5f-5c35-85ac-d609ff6c85f2','Nuke Script','.nk','nk,nuke,nkple',0,'2021-04-02 14:54:30',0,0),(27,'8e6b3b2f-b8b5-56df-a5aa-665c6252714d','Object Geometry','.obj','obj',0,'2021-04-02 15:04:25',0,0),(31,'b8ef5d5e-837b-566c-96ea-233524484801','Houdini Scene','.hip','hip',0,'2021-04-02 15:25:57',0,0),(32,'c2773de1-f587-5299-9682-ae7c48115998','Premiere Project','.prproj','prproj',0,'2021-04-02 15:40:06',0,0),(33,'a4b7c8ea-8a5a-5a5b-b9d5-ea84af150327','Final Cut XML','.xml','xml',0,'2021-04-02 15:40:21',0,0),(34,'45b45049-2427-5cbe-9313-9795c8768d60','SVG Vector Graphics','.svg','svg',1,'2021-04-02 16:42:19',0,0),(35,'5f8daa27-bf07-5c8b-8fb7-74c5c683e98f','Blender Scenes','.blend','blend,blend1,blend2,blend3',0,'2021-04-02 16:44:06',0,0),(36,'1853e433-57f7-5e45-a9f8-f36b5d1b51f0','Krita Image','.kra','kra',0,'2021-04-02 16:42:52',0,0),(37,'f2fc7184-50e4-5bd1-815b-f4a1ddb40cd6','Storyboarder Project','.story','story',0,'2021-04-02 16:55:39',0,0);
/*!40000 ALTER TABLE `ram_filetypes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_pipes`
--

LOCK TABLES `ram_pipes` WRITE;
/*!40000 ALTER TABLE `ram_pipes` DISABLE KEYS */;
INSERT INTO `ram_pipes` VALUES (1,'5b924e3b-27b0-5d2e-8e80-ae9d7630d133',50,47,20,NULL,'2021-04-24 10:29:23',0),(2,'a6fd790d-4951-5d2c-b61b-40a635571d5a',50,37,20,NULL,'2021-04-15 17:55:35',1),(3,'65f18f45-4e15-57a9-a326-f401a96872e9',37,40,20,NULL,'2021-04-15 17:55:35',1),(4,'9824bf6b-bbdc-56d6-9540-0455c6d00f85',47,40,20,NULL,'2021-04-15 17:55:35',1),(5,'8371dad2-b831-556f-a81a-2bc45da7e2dd',40,44,27,NULL,'2021-04-15 17:55:35',1),(6,'85d06967-6976-5277-8006-96b3a7f4bcba',40,38,23,NULL,'2021-04-24 10:30:33',0),(7,'e10289cb-cc73-57ae-8739-ebd5520045ff',40,25,23,NULL,'2021-04-24 10:29:36',0),(8,'2bc7bc6e-c244-51d1-9523-82b1266e9bcf',25,39,23,NULL,'2021-04-24 10:29:27',0),(9,'c2e53111-c189-59fb-b4bb-cd932efe351a',38,39,23,NULL,'2021-04-24 10:30:35',0),(10,'1ce482ba-7731-51c5-812b-51d85ac8c094',39,43,25,NULL,'2021-04-24 10:29:11',0),(11,'57b87824-6919-5f64-9360-d8776ac8923e',43,41,25,NULL,'2021-04-24 10:29:53',0),(12,'00352282-a2d8-5e68-834d-e52d04b3139e',44,45,22,NULL,'2021-04-24 10:29:16',0),(13,'e9eb88c0-73ca-5323-a1de-28224cfe82dd',45,41,23,NULL,'2021-04-24 10:30:19',0),(14,'aa3683f4-876e-5cb4-bd0f-cef56c92f8ad',40,46,NULL,NULL,'2021-04-01 16:20:30',1),(15,'d2d8b9ec-7f40-5280-982d-fe545c0232a3',46,41,22,NULL,'2021-04-15 17:55:35',1),(16,'8c0cc6e9-724f-5f73-a941-9a09ebb3a328',41,42,23,NULL,'2021-04-24 10:29:54',0),(17,'eb592fe6-9382-5bb4-8055-1e1684d6f64d',42,36,22,NULL,'2021-04-15 17:55:35',1),(18,'adb79a1c-1490-5319-840b-bb6542b13147',36,48,22,NULL,'2021-04-15 17:55:35',1),(19,'0536500e-a064-542d-9fbb-c90a9914e347',48,49,33,NULL,'2021-04-15 17:55:35',1),(20,'d980d9ed-d40e-5cc2-b1dc-57ec44569f3f',47,46,21,NULL,'2021-04-24 10:29:47',0),(21,'40f03514-fcc6-54f0-a8b0-46bd0ce219b7',51,47,NULL,NULL,'2021-04-01 16:58:04',1),(22,'3d3c781f-6372-544e-834b-b33a30cc9c6c',52,47,NULL,NULL,'2021-04-01 16:59:01',1),(23,'711d427d-7796-5162-a773-f5ab4a1e8f26',48,36,NULL,NULL,'2021-04-01 17:01:29',1),(24,'f918fab8-b620-5578-820b-6235cf147d9b',53,47,NULL,NULL,'2021-04-01 17:25:55',1),(25,'4d26955e-e213-57d2-8511-2eebdd824f02',39,41,23,NULL,'2021-04-15 17:55:35',1),(26,'161a8848-c5a1-5816-96bc-4739765acfa4',44,43,NULL,NULL,'2021-04-24 10:28:22',1),(31,'c49f2bcf-0817-5638-8961-98f47bbc9aa0',36,49,22,NULL,'2021-04-15 17:55:35',1),(35,'cd598cef-a197-54c8-a638-077198a37bce',64,55,20,NULL,'2021-04-22 18:30:21',0),(37,'7cf73848-605b-5002-a1d3-efc90ed98767',64,65,20,NULL,'2021-04-22 18:30:19',0),(38,'8176ef26-c5d0-59d9-9d41-20dc2b3a8e52',55,62,20,NULL,'2021-04-22 18:30:23',0),(39,'6c8ab300-fd28-5670-a9dc-dd24e779aa73',65,62,20,NULL,'2021-04-22 14:43:01',0),(40,'11d77b01-ad36-5603-9937-05ddb19d29c2',62,61,NULL,NULL,'2021-04-02 16:59:56',1),(41,'9567b7af-96b9-51ab-b495-e3eeb6b0fff8',65,61,20,NULL,'2021-04-22 14:43:00',0),(42,'02e7669e-1ba9-554d-abfd-efed29feb02d',62,59,35,NULL,'2021-04-22 15:09:30',0),(43,'27f73793-4151-5188-b7a0-f833cfb11f8c',59,61,19,NULL,'2021-04-22 13:48:57',1),(45,'2a6e0838-bb25-5bbd-b4dc-e409596b8635',62,63,NULL,NULL,'2021-04-24 20:06:53',0),(46,'2707bab9-dcfd-53e6-b43b-c67e18a41f3e',63,54,NULL,NULL,'2021-04-24 20:06:53',0),(47,'844df1c1-2c50-5afe-9e1d-023074af1362',62,67,35,NULL,'2021-04-22 18:32:06',0),(49,'8fac3707-aa64-5c5c-ba0d-dd1b485ac751',67,66,22,NULL,'2021-04-22 18:32:18',0),(50,'df7dacbb-a419-563f-9db5-84c27ac7d8ba',62,66,NULL,NULL,'2021-04-02 17:04:08',1),(51,'b92d1e2c-e3e1-50d0-865e-595caaf8a737',54,60,35,NULL,'2021-04-22 18:31:05',0),(52,'809d5c66-6ee3-5a76-a687-f0fb2b80ee4d',66,60,35,NULL,'2021-04-22 18:31:06',0),(53,'e9aecb55-cf71-5439-8f11-5a4065cf7ed3',59,54,35,NULL,'2021-04-22 18:30:39',0),(54,'5e43308f-bacd-5aa1-aa19-93e9e7d59867',61,60,22,NULL,'2021-04-22 18:31:03',0),(55,'f352c5ec-5234-5d14-88e4-72df8cb0c9f9',60,57,35,NULL,'2021-04-22 18:31:10',0),(56,'63734386-e37c-5641-ad80-2256c3cb828b',57,58,22,NULL,'2021-04-22 18:31:12',0),(59,'6cdd3c7e-5389-5c17-8fb3-748b473ef9f3',57,56,22,NULL,'2021-04-02 17:08:20',1),(429,'db9afaf1-41bb-545d-970d-0e1dc546efbc',59,60,35,NULL,'2021-04-22 18:30:36',0),(502,'a64855a5-65ae-5a34-891b-d851817d99bf',55,67,20,NULL,'2021-04-22 18:31:56',0),(512,'d2c4efba-1ca5-5d98-b71a-ed86e2004db3',69,70,NULL,NULL,'2021-04-24 10:25:48',1),(513,'baa105fc-2ac5-5a15-8560-0ae658d8e108',70,71,NULL,NULL,'2021-04-24 10:25:48',1),(514,'cf9ece7a-b3a0-501d-8f13-eb9226567ccd',69,72,20,NULL,'2021-04-24 10:26:35',0),(515,'cf9a09f8-8907-5481-8f7f-3325fd8f15a5',72,73,25,NULL,'2021-04-24 10:26:50',0),(516,'c0d7ad52-e5d2-5ee8-b293-816b455c01cf',69,74,NULL,NULL,'2021-04-24 10:27:06',0),(517,'5c3ef489-c783-56a4-9d4e-cb3f809aae56',74,73,22,NULL,'2021-04-24 10:27:21',0),(522,'962576dc-205d-5f16-a6a1-24eb5a16809e',43,42,NULL,NULL,'2021-04-24 10:29:56',1);
/*!40000 ALTER TABLE `ram_pipes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_projectassetgroup`
--

LOCK TABLES `ram_projectassetgroup` WRITE;
/*!40000 ALTER TABLE `ram_projectassetgroup` DISABLE KEYS */;
/*!40000 ALTER TABLE `ram_projectassetgroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_projects`
--

LOCK TABLES `ram_projects` WRITE;
/*!40000 ALTER TABLE `ram_projects` DISABLE KEYS */;
INSERT INTO `ram_projects` VALUES (12,'02cfde73-48a7-5080-838e-cee25736ee47','Maya Production Example','MayaProd',24,1920,1080,1.78,'auto','2021-04-15 17:31:21',0,0),(13,'c2abbe47-d9d0-512d-acdb-1b98158363fc','Mythomen','MYTHO',25,1998,1080,1.78,'auto','2021-04-15 17:38:05',0,0),(14,'1973ecce-f03e-5944-bc9e-1a445c4b2ec8','Free Prod Example','FPE',60,2048,858,1.78,'auto','2021-04-15 17:38:12',0,0);
/*!40000 ALTER TABLE `ram_projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_sequences`
--

LOCK TABLES `ram_sequences` WRITE;
/*!40000 ALTER TABLE `ram_sequences` DISABLE KEYS */;
INSERT INTO `ram_sequences` VALUES (8,'a687c0c7-02b0-569a-ab30-46037661ff6a','Sequence 01 - Tintagel','SEQ01',14,'2021-04-05 14:24:47',0,0),(9,'8cd4f9c2-380f-5de5-b5df-1200f650e466','Sequence 02 - At the sea','SEQ02',14,'2021-04-05 14:24:47',0,0),(10,'dc89dfd7-c141-5009-8582-9501808ba5c5','Film','FILM',14,'2021-04-02 16:51:56',1,0),(11,'16883a82-2baa-5543-ac9e-2662defadde9','Plan Tests','TESTS',14,'2021-04-02 16:51:58',1,0);
/*!40000 ALTER TABLE `ram_sequences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_shots`
--

LOCK TABLES `ram_shots` WRITE;
/*!40000 ALTER TABLE `ram_shots` DISABLE KEYS */;
INSERT INTO `ram_shots` VALUES (1,'0714f0f2-d08d-5e58-88be-673b213f30ce','Shot 001','001',8,13.5,'2021-04-14 13:32:30',0,0),(2,'c1afd843-8204-5d26-8ebe-53ab2fd63cc1','Shot 002','002',8,0,'2021-04-14 13:38:47',0,1),(3,'610cecf7-9384-5449-b530-15d1ae152ec6','Shot 003','003',8,0,'2021-04-14 13:38:47',0,2),(4,'93a588b4-59ca-56a2-8e32-d39ffab64d62','0043','NEW',8,0,'2021-04-13 12:55:16',1,-1),(5,'096fb973-2fb6-542f-9c03-6f8f10c54e2e','Shot 004','004',9,0,'2021-04-14 11:11:03',1,-1),(6,'be8a7f67-7015-50fa-85fa-e84346cdd699','Shot 004','004',9,0,'2021-04-14 17:18:51',0,0),(7,'5e52a9b6-a40c-5047-8cad-aa8fb2626479','Shot 005','005',9,0,'2021-04-25 12:15:13',1,-1),(8,'524b367f-82e0-5293-b33a-903e85d54f30','Shot 006','006',9,5,'2021-04-14 17:17:53',1,-1),(9,'c8dda9ff-219f-5844-9c09-05119e650e58','Shot 007','007',9,0,'2021-04-25 12:35:19',0,3),(10,'dc4b68fb-6556-5e61-b9ea-e9d733daa07b','Shot 006','006',9,0,'2021-04-25 12:15:12',1,-1),(11,'aa64120f-729b-5771-8a87-290627622a13','Shot 008','008',9,0,'2021-04-25 12:15:07',1,-1),(12,'9864f0e9-c430-5693-b9cf-c0239f82ce2a','Shot 005','005',9,0,'2021-04-25 12:34:55',0,1),(13,'75f4ae0c-5722-55dc-87b4-b209c474d6ae','Shot 006','006',9,0,'2021-04-25 12:35:19',0,2);
/*!40000 ALTER TABLE `ram_shots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_states`
--

LOCK TABLES `ram_states` WRITE;
/*!40000 ALTER TABLE `ram_states` DISABLE KEYS */;
INSERT INTO `ram_states` VALUES (14,'efea0416-1456-515c-9a4e-5ce6e7d2000e','OK','OK','#00aa00','2021-04-02 16:32:12',100,0),(15,'05a13d0e-a2f8-5e0b-864e-c8cfc8ce22a2','Work in progress','WIP','#ffff7f','2021-04-02 16:32:48',50,0),(16,'173c2dc8-916a-5fe3-989a-54e420c46fca','Waiting for approval','CHK','#ff5500','2021-04-02 16:31:59',75,0),(17,'1d1d9f12-79cd-5f29-8ad5-97ed8b7a2ea8','To do','TODO','#55aaff','2021-04-02 16:32:42',0,0),(18,'04fb9195-ddb0-5ff7-9b17-e757085bd7f8','Stand by','STB','#939393','2021-04-25 11:10:33',0,0),(19,'0be03056-b5db-5945-8117-63bfbff9a574','Could be better','CBB','#55ff7f','2021-04-02 16:31:40',90,0),(20,'47325dd4-36e3-5b50-b419-00efc97c9e83','Retake','RTK','#aa007f','2021-04-02 16:32:25',75,0),(21,'7949a9e3-b9d2-5a24-a454-296df4e43ff6','Nothing to do','NO','#434343','2021-04-25 11:09:29',0,0);
/*!40000 ALTER TABLE `ram_states` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_status`
--

LOCK TABLES `ram_status` WRITE;
/*!40000 ALTER TABLE `ram_status` DISABLE KEYS */;
INSERT INTO `ram_status` VALUES (1,'775aac58-560f-5bf5-8353-406a19c141d1',20,15,17,'Let\'s do this !',1,54,31,NULL,'2021-04-24 10:23:12',0,'2021-04-26 07:43:41'),(2,'dfbb339d-6333-5bbe-9c4f-8e74e103bae8',75,15,16,'Check',1,54,31,NULL,'2021-04-24 10:23:22',0,'2021-04-26 07:43:41'),(3,'5fc55d50-da02-5b14-85e3-54796785de0e',50,15,20,'Retake !',1,54,31,NULL,'2021-04-24 18:00:53',0,'2021-04-26 07:43:41'),(4,'09e3bf8f-e806-5238-8bff-f051c2b390a7',87,15,16,'Check again...',1,54,31,NULL,'2021-04-24 18:01:07',0,'2021-04-26 07:43:41'),(5,'29083ed8-3626-5393-8858-9991887684f9',90,15,19,'Hum, that could be better...',1,54,31,NULL,'2021-04-24 18:01:16',0,'2021-04-26 07:43:41'),(6,'f29bea1c-669b-534e-92ca-d747688313c7',100,15,14,'Now it\'s perfect!',1,54,31,NULL,'2021-04-24 18:01:27',0,'2021-04-26 07:43:41'),(7,'a655a3ea-8916-5343-86de-895b74228091',72,15,17,'Last minute change!',2,54,31,NULL,'2021-04-24 18:30:34',0,'2021-04-26 07:43:41'),(8,'671b42ec-babb-5f29-a6ae-bdeba1e35c74',100,15,14,'Def-Final-Valid-OK',3,54,31,NULL,'2021-04-24 18:30:50',0,'2021-04-26 07:43:41'),(11,'cd110f8d-c1d3-51d7-ace4-0126389e2fed',75,15,20,'long single-line long single-line long single-line long single-line long single-line long single-line long single-line long single-line long single-line long single-line long single-line long single-line ',1,54,57,NULL,'2021-04-25 09:48:15',0,'2021-04-26 07:43:41'),(12,'761387a6-3017-51eb-ac1c-f243577355fe',100,15,14,'small',1,54,57,NULL,'2021-04-25 09:48:45',0,'2021-04-26 07:43:41'),(14,'55e7957b-daf4-5c60-a623-e3405cac19a0',0,15,18,'Waiting for previous step',1,55,57,NULL,'2021-04-25 10:56:08',0,'2021-04-26 07:43:41'),(15,'6a12df8e-2f98-566d-b2ca-63ac808ad301',0,15,17,'Let\'s start working on this!',1,55,57,NULL,'2021-04-25 10:56:20',0,'2021-04-26 07:43:41'),(16,'00e497b0-d6fb-5f64-b1ef-fcd15ffa9777',33,15,15,'Modeling...',1,55,57,NULL,'2021-04-25 10:56:37',0,'2021-04-26 07:43:41'),(17,'9a219eb9-c376-569c-9a5a-0fac72174794',66,15,15,'Still modeling...',1,55,57,NULL,'2021-04-25 10:56:48',0,'2021-04-26 07:43:41'),(18,'965dcbae-2260-5548-bf65-44b865046db3',75,15,16,'Can you have a look?',1,55,57,NULL,'2021-04-25 10:57:03',0,'2021-04-26 07:43:41'),(19,'23e0f4e8-f487-522e-94c5-21f9079d4496',55,15,20,'Nope, fix that!',1,55,57,NULL,'2021-04-25 10:57:20',0,'2021-04-26 07:43:41'),(20,'969089f1-769a-5fb2-af4c-a31380d33909',87,15,16,'Check again!',1,55,57,NULL,'2021-04-25 10:57:40',0,'2021-04-26 07:43:41'),(21,'fe9eb39f-bd1f-5a3a-9866-d88ec70367ca',90,15,19,'That could be improved later',1,55,57,NULL,'2021-04-25 10:57:59',0,'2021-04-26 07:43:41'),(22,'e13f5202-ea58-5ebe-ae06-1a4bbbe38932',100,15,14,'Now it\'s perfect!',2,55,57,NULL,'2021-04-25 10:58:14',0,'2021-04-26 07:43:41'),(23,'dcebd4bc-b3f9-5c75-988c-c44c7567d3c9',88,15,20,'Last minute change!',3,55,57,NULL,'2021-04-25 10:58:29',0,'2021-04-26 07:43:41'),(24,'d7e95309-5bf5-5b93-bd5f-258d7cf9f776',100,15,14,'Final-Valid-Def-OK-trueFinal...',4,55,57,NULL,'2021-04-25 10:58:58',0,'2021-04-26 07:43:41'),(25,'9ea07fb8-faf3-5446-b07f-4195c874d7ce',0,15,18,'Waiting for previous step',1,59,57,NULL,'2021-04-25 11:07:42',0,'2021-04-26 07:43:41'),(26,'aab087f7-2072-5614-b135-29a382917ea4',0,15,21,'Nothing to do for this asset',1,61,57,NULL,'2021-04-25 11:09:54',0,'2021-04-26 07:43:41'),(27,'39973d57-2018-545c-8e8a-92c6714de52c',0,15,18,'Waiting...',1,62,57,NULL,'2021-04-25 11:10:08',0,'2021-04-26 07:43:41'),(28,'d73ecce3-ce84-56c6-878e-2447811b7845',50,15,15,'',1,59,57,NULL,'2021-04-25 11:21:42',0,'2021-04-26 07:43:41'),(29,'a37a0b93-2509-5b95-a10d-0ef808738dd5',70,15,15,'Working...',1,59,57,NULL,'2021-04-25 11:21:54',0,'2021-04-26 07:43:41'),(30,'5b847375-80a3-5dd8-aa2e-803abecbd770',0,15,17,'Let\'s shade this',1,66,57,NULL,'2021-04-25 11:55:42',0,'2021-04-26 07:43:41'),(31,'58c8fd73-daaa-5c5d-82f8-9d58ed941d84',0,15,17,'',1,67,57,NULL,'2021-04-25 11:55:51',0,'2021-04-26 07:43:41'),(32,'a7e9c9ae-0370-552b-b173-68d4a8504bc7',0,15,17,'',1,65,57,NULL,'2021-04-25 11:55:56',0,'2021-04-26 07:43:41'),(33,'1c73bb07-e345-5d01-8a6f-9260ff0c42a8',0,15,18,'',1,63,57,NULL,'2021-04-25 11:56:00',0,'2021-04-26 07:43:41'),(34,'30847c2a-ab0d-5612-96fb-43a98c884591',0,15,18,'',1,55,87,NULL,'2021-04-26 07:29:33',0,'2021-04-26 07:43:41'),(35,'3e0bc703-37fa-5040-98e8-3c83f70b2ca4',0,15,18,'',1,55,57,NULL,'2021-04-26 08:12:27',0,'2021-04-26 08:12:27');
/*!40000 ALTER TABLE `ram_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_stepapplication`
--

LOCK TABLES `ram_stepapplication` WRITE;
/*!40000 ALTER TABLE `ram_stepapplication` DISABLE KEYS */;
INSERT INTO `ram_stepapplication` VALUES (1,15,24,'2021-03-31 11:13:10',0),(16,15,50,'2021-04-02 14:55:02',0),(17,15,37,'2021-04-02 14:56:26',0),(18,15,47,'2021-04-02 14:56:30',0),(19,16,40,'2021-04-02 14:56:55',0),(20,15,46,'2021-04-02 14:57:13',0),(22,16,25,'2021-04-02 14:58:06',0),(23,15,44,'2021-04-02 14:58:11',0),(24,18,44,'2021-04-02 14:59:58',0),(25,19,44,'2021-04-02 14:59:59',0),(26,20,44,'2021-04-02 15:00:01',0),(27,16,45,'2021-04-02 15:08:37',0),(28,16,39,'2021-04-02 15:08:59',0),(29,16,41,'2021-04-02 15:09:17',0),(30,21,43,'2021-04-02 15:26:12',0),(31,16,42,'2021-04-02 15:26:40',0),(32,17,36,'2021-04-02 15:27:19',0),(33,22,48,'2021-04-02 15:39:28',0),(34,24,49,'2021-04-02 15:42:15',0),(35,25,54,'2021-04-02 16:52:30',0),(36,28,64,'2021-04-02 16:56:32',0),(37,26,64,'2021-04-02 16:56:36',0),(38,26,55,'2021-04-02 16:56:43',0),(39,26,65,'2021-04-02 16:59:05',0),(40,25,62,'2021-04-02 16:59:34',0),(41,25,59,'2021-04-02 17:00:35',0),(42,26,61,'2021-04-02 17:01:05',0),(43,25,63,'2021-04-02 17:01:52',0),(44,26,67,'2021-04-02 17:02:34',0),(45,25,67,'2021-04-02 17:03:03',0),(46,25,66,'2021-04-02 17:03:56',0),(47,25,60,'2021-04-02 17:05:30',0),(48,25,57,'2021-04-02 17:06:14',0),(49,23,58,'2021-04-02 17:06:43',0),(50,25,56,'2021-04-02 17:08:02',0),(52,27,67,'2021-04-14 18:01:32',0),(53,15,69,'2021-04-24 10:26:21',0),(54,16,72,'2021-04-24 10:26:29',0),(55,16,73,'2021-04-24 10:26:44',0),(56,19,74,'2021-04-24 10:27:18',0),(57,16,38,'2021-04-24 10:30:49',0),(58,15,63,'2021-04-24 20:06:53',0);
/*!40000 ALTER TABLE `ram_stepapplication` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_steps`
--

LOCK TABLES `ram_steps` WRITE;
/*!40000 ALTER TABLE `ram_steps` DISABLE KEYS */;
INSERT INTO `ram_steps` VALUES (24,'27858b5b-d7d4-5f50-8f15-b156162a7d72','Character Design','CD',0,'asset',12,0,'2021-03-31 17:17:10',1),(25,'25b7fce6-56c8-598b-9530-7a4e913be34c','Rigging','RIG',0,'asset',12,5,'2021-04-02 16:12:32',0),(31,'1fc74cbf-ea59-5a0e-8f7e-d99106407466','Step','NEW',0,'asset',12,0,'2021-03-31 16:59:05',1),(32,'72c68fd4-cac3-596d-8c3c-164b82be5971','Sound Recording','SR',0,'pre',12,0,'2021-03-31 17:00:00',1),(33,'eb7ce93e-88c8-5b1a-892e-a3fe89e4dc7f','Character Design','CD',0,'asset',12,0,'2021-03-31 17:17:00',1),(34,'49f6eff5-5a0b-57a2-b448-eae5f79ddbf0','Step','NEW',0,'asset',12,0,'2021-03-31 17:17:14',1),(35,'199bde76-f496-5c56-acb0-1559b34c9a4c','Character Design','CD',0,'asset',12,0,'2021-03-31 17:17:25',1),(36,'5bf4390e-fc43-569a-be25-f1db2bea07fc','Compositing','COMP',0,'shot',12,12,'2021-04-01 16:24:09',0),(37,'e79bbab5-202e-5d07-a338-b4a2bcae2755','Character Design','CD',0,'asset',12,14,'2021-04-01 16:24:09',0),(38,'07b81f8a-b0c0-5adf-8822-e91a6b604744','Layout','LAY',0,'asset',12,10,'2021-04-01 16:24:08',0),(39,'cce489f7-1a7e-5af5-be31-c97c3d77a0b1','Animation','ANIM',0,'shot',12,15,'2021-04-01 16:24:08',0),(40,'6590f86a-4f41-5011-9c2e-d7b1967648af','Modeling','MOD',0,'asset',12,7,'2021-04-02 15:33:06',0),(41,'15c538d9-d16a-5c19-bd0c-63da98088285','Lighting','LIGHT',0,'shot',12,9,'2021-04-01 16:24:08',0),(42,'9af82aee-a0eb-5316-80f9-a20eed1642f9','Rendering','RENDER',0,'shot',12,6,'2021-04-01 16:24:08',0),(43,'e81244cf-b463-5beb-87c7-9ee62236e395','Visual Effects','VFX',0,'shot',12,1,'2021-04-02 14:55:17',0),(44,'f2ebbe0b-1b25-5381-8082-e947b68a705c','Texture','TEX',0,'asset',12,2,'2021-04-02 14:55:17',0),(45,'114123b3-7c61-56fe-9dfa-0484461ae4f3','Shading','SHADE',0,'asset',12,3,'2021-04-02 14:55:16',0),(46,'f671701c-bb05-5331-b7f1-4d41744c1a09','Matte Painting','MATTE',0,'asset',12,8,'2021-04-01 16:24:08',0),(47,'9c5c69e1-2bef-5829-8a7f-e2fe81d7064a','Set Design','SD',0,'asset',12,4,'2021-04-02 14:55:16',0),(48,'9043c1f2-4608-5ee0-8440-b21d738d9755','Edit','EDIT',0,'post',12,11,'2021-04-01 16:24:08',0),(49,'061169d2-f063-5018-8c0c-4f04e8273f33','Color Grading','COLO',0,'post',12,13,'2021-04-01 16:24:09',0),(50,'1817f69c-896c-5a2e-ab69-6092d47cacc0','Storyboard','SB',0,'pre',12,0,'2021-04-02 14:55:17',0),(51,'7fba19ee-0165-5003-a244-0f4d49dedd93','Step','NEW',0,'asset',12,0,'2021-04-01 16:58:06',1),(52,'53502d50-2f6e-5315-b26c-4f5cbe5d246a','Step','NEW',0,'asset',12,0,'2021-04-01 16:59:01',1),(53,'2160f626-e8c9-592b-80d1-e136b085a923','Step','NEW',0,'asset',12,0,'2021-04-01 17:25:55',1),(54,'045ab4a8-2ccd-5583-92b7-7d5b0c80885d','Animation','ANIM',0,'shot',14,12,'2021-04-14 16:09:10',0),(55,'d515f0e5-6b8a-5136-b1de-f9a09205570d','Character Design','CD',0,'asset',14,11,'2021-04-14 16:09:10',0),(56,'5912a7ef-555d-5669-ad16-ea414a576830','Color Grading','COLO',0,'post',14,0,'2021-04-02 17:08:20',1),(57,'b429b911-6ad2-5dec-b226-e07815bf406a','Compositing','COMP',0,'shot',14,10,'2021-04-14 16:09:10',0),(58,'af184664-753d-56ca-8563-9a74dc19c95a','Edit','EDIT',0,'post',14,9,'2021-04-14 16:09:10',0),(59,'9cef6617-41de-50ee-90a4-5ba0bfb0076d','Layout','LAY',0,'asset',14,8,'2021-04-14 16:09:10',0),(60,'fe529912-db1e-5a39-88ce-bf4583ece1ef','Lighting','LIGHT',0,'shot',14,7,'2021-04-14 16:09:10',0),(61,'c1421536-e82c-5bbc-ab5b-f0301503d8b3','Matte Painting','MATTE',0,'asset',14,6,'2021-04-14 16:09:10',0),(62,'4919b042-7581-54a8-a47d-246f7eddba05','Modeling','MOD',0,'asset',14,5,'2021-04-14 16:09:10',0),(63,'388629cd-cc37-58a6-b90a-6b7814e24f08','Rigging','RIG',0,'asset',14,4,'2021-04-14 16:09:10',0),(64,'e5ae9faa-4424-5e4d-8a0e-be5d6deb4905','Storyboard','SB',0,'pre',14,3,'2021-04-14 16:09:10',0),(65,'cd110ba5-2d04-597e-8e11-1a44c06801d9','Set Design','SD',0,'asset',14,2,'2021-04-14 16:09:10',0),(66,'ee449f84-fd83-58c8-8995-fc639fc4c3e2','Shading','SHADE',0,'asset',14,1,'2021-04-14 16:09:10',0),(67,'35ae5718-2893-50a1-a324-7c4c8cf1de85','Texture','TEX',0,'asset',14,0,'2021-04-02 16:54:24',0),(68,'e5b84224-8c75-5e49-8709-2d6fb5a72dc7','Animation','ANIM',0,'shot',13,0,'2021-04-24 10:24:45',1),(69,'e262fa73-157a-5e32-bc2f-8c0c0f7256d5','Character Design','CD',0,'pre',13,0,'2021-04-24 10:25:18',0),(70,'fb92ec28-633b-57a4-b345-f69cd956490a','Animation','ANIM',0,'shot',13,0,'2021-04-24 10:25:48',1),(71,'24005ddb-e873-5cd7-9800-65f4c6eb1732','Lighting','LIGHT',0,'shot',13,0,'2021-04-24 10:25:48',1),(72,'c9b796f5-8e6c-5d3f-a983-87ff70b9cb32','Animation','ANIM',0,'shot',13,0,'2021-04-24 10:26:24',0),(73,'939e185a-f253-5526-97db-8beedaeb8af0','Lighting','LIGHT',0,'shot',13,0,'2021-04-24 10:26:39',0),(74,'056cb547-25d0-52bb-b279-8e9da9e4d551','Shading','SHADE',0,'asset',13,0,'2021-04-24 10:27:03',0);
/*!40000 ALTER TABLE `ram_steps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_stepuser`
--

LOCK TABLES `ram_stepuser` WRITE;
/*!40000 ALTER TABLE `ram_stepuser` DISABLE KEYS */;
INSERT INTO `ram_stepuser` VALUES (27,24,16,'2021-03-31 11:06:14',0),(28,24,17,'2021-03-31 11:06:15',0),(29,24,15,'2021-03-31 11:06:16',0),(30,25,14,'2021-03-31 11:13:22',0),(31,25,15,'2021-03-31 11:13:23',0),(32,43,14,'2021-03-31 18:42:50',0),(33,43,16,'2021-03-31 18:42:51',0),(34,39,15,'2021-03-31 18:42:55',0),(35,54,15,'2021-04-14 15:02:14',0),(36,65,14,'2021-04-14 17:56:19',0),(37,55,15,'2021-04-14 17:56:41',0),(38,55,16,'2021-04-14 17:56:51',0),(39,63,14,'2021-04-24 20:07:03',0),(40,63,16,'2021-04-24 20:07:04',0),(41,63,15,'2021-04-24 20:07:06',0);
/*!40000 ALTER TABLE `ram_stepuser` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_templateassetgroups`
--

LOCK TABLES `ram_templateassetgroups` WRITE;
/*!40000 ALTER TABLE `ram_templateassetgroups` DISABLE KEYS */;
INSERT INTO `ram_templateassetgroups` VALUES (10,'954204b1-1950-5be7-9ffd-74ab4640b794','Props','PROP','2021-03-31 10:57:16',0,0),(11,'39ca4b52-6774-5c5d-a8df-aea6921cea50','Characters','CHAR','2021-03-31 10:57:25',0,0),(12,'aca51d86-357a-5564-a182-1f13b91bbb7d','Sets','SET','2021-03-31 10:57:33',0,0),(13,'62da98ee-d983-532f-bb7b-517402b66248','Backgrounds','BG','2021-03-31 10:57:58',0,0);
/*!40000 ALTER TABLE `ram_templateassetgroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_templatesteps`
--

LOCK TABLES `ram_templatesteps` WRITE;
/*!40000 ALTER TABLE `ram_templatesteps` DISABLE KEYS */;
INSERT INTO `ram_templatesteps` VALUES (7,'a29e2c57-ddc1-5c97-91b5-69b02da969d5','Character Design','CD',0,'2021-03-31 10:52:31','asset',0,0),(8,'eb43cace-3cc8-5ae4-a4f7-b80e1c9144ec','Storyboard','SB',0,'2021-03-31 10:52:47','pre',0,0),(9,'864110b2-7c1d-504a-8246-d0e0b55777d9','Edit','EDIT',0,'2021-03-31 10:52:55','post',0,0),(10,'e16703e5-7458-5961-bf73-8b19411bf93e','Rigging','RIG',0,'2021-03-31 10:53:04','asset',0,0),(11,'3a9e7bb8-bda7-51f8-96da-939518367db6','Lighting','LIGHT',0,'2021-03-31 10:53:49','shot',0,0),(12,'2b89be14-8019-599c-90de-8b3742d75948','Texture','TEX',0,'2021-03-31 10:53:58','asset',0,0),(13,'970751c5-2c2b-556f-8976-393eb3d59f12','Modeling','MOD',0,'2021-03-31 10:54:25','asset',0,0),(14,'7b0388b7-9035-5a7d-b78c-fddf7c048432','Shading','SHADE',0,'2021-03-31 10:54:41','asset',0,0),(15,'d36a8656-f51c-588f-a0c5-b03b0da1040c','Visual Effects','VFX',0,'2021-03-31 10:55:03','shot',0,0),(16,'6467b134-d6b4-5fdc-822e-4eb773333d15','Rendering','RENDER',0,'2021-03-31 10:55:15','shot',0,0),(17,'5ab4bda6-2dec-58c9-b5dd-197fcd5ea038','Color Grading','COLO',0,'2021-03-31 17:10:29','post',0,0),(18,'9f051087-cf36-5537-a2be-beae18b103c7','Compositing','COMP',0,'2021-03-31 10:55:40','shot',0,0),(19,'2d21f60e-6f7f-56a4-83d3-d478f84187b4','Layout','LAY',0,'2021-03-31 10:55:58','asset',0,0),(20,'b8b25b0f-cd5e-52f5-99cb-6585082be148','Matte Painting','MATTE',0,'2021-03-31 10:56:19','asset',0,0),(21,'cca7cf4e-09a3-5a75-80b7-019a0c28be36','Set Design','SD',0,'2021-03-31 10:57:47','asset',0,0),(22,'2a4620e7-d88a-5ba3-b382-cd3284c757f5','Animation','ANIM',0,'2021-03-31 17:17:50','shot',0,0);
/*!40000 ALTER TABLE `ram_templatesteps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_users`
--

LOCK TABLES `ram_users` WRITE;
/*!40000 ALTER TABLE `ram_users` DISABLE KEYS */;
INSERT INTO `ram_users` VALUES (14,'bVda5hjqDNLFJia9DCmwwH2p','Ana Arce','Ana','09b4b3eeff6cc464628dd7a486068aa621bddca545307a6069b67bd75b23cf929d435860ebabe3ebe1d36ef1dc0d3a0dda5e3b097b6e7641ea09a7afba3cc074','2021-03-31 10:51:47','auto','project',0,0),(15,'dda68ab7-2364-5be8-9569-47c50e24bc14','Nico Duduf','Duduf','838f648f84f453f56aa05c98a3effeaa333ed3c5a6f78c84865a6dd50ea73be6a78c5d77a64c11b9820bed0fa9334cb6605e3a39619e566fa092c5ac107e940a','2021-03-31 10:51:33','auto','admin',0,0),(16,'a9df40db-3311-5fc5-a2a5-b4752a61fb98','John Doe','John','1488d20c50ae416d5dcbe8d9739af65e37f68fda8851ea12d992066d30b9ed3fd048ff4a80edc31594203286ffc6d78d015c313423e63a7ef1d17754b615e8a6','2021-03-31 10:50:20','auto','standard',0,0),(17,'1bd9e736-bb66-5a33-97cb-caae74b68b86','Jane Doe','Jane','23bd2f66514103fc427feac0fa1c2db998b30543170741151c5973752c188dabbb09ef89603f02e9d241787580c7ea33b54578bc8d27a405299f1bbf8a315ea5','2021-03-31 10:50:35','auto','lead',0,0),(18,'2501ebfa-ff80-5561-9ab6-c6856740fb18','Tester','TestUser','0029a4a1cba0aca765846ae48006d4d6bce31279cdaa951873ab14ba1f7c3c10a8f5241008ea5ff58dc408cc2e19f5208bf92d7239b383b3a3a363f37da5b9bf','2021-04-26 07:01:07','auto','standard',0,0);
/*!40000 ALTER TABLE `ram_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-04-26 10:41:10
