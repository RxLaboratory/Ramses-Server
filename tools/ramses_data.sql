-- MySQL dump 10.13  Distrib 8.0.25, for Linux (x86_64)
--
-- Host: localhost    Database: ramses
-- ------------------------------------------------------
-- Server version	8.0.25-0ubuntu0.20.04.1

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
INSERT INTO `ram_assetgroups` VALUES (11,'60af61d1-767a-5bd4-a8c6-066528d020f3','Props','PROP',12,'2021-03-31 11:13:50',0,0),(12,'0faf70ff-4a7b-52a6-9beb-545c35118945','Characters','CHAR',12,'2021-03-31 11:13:51',0,0),(13,'688e1cec-5e6c-5a6c-b8d5-0fedca27b99e','Backgrounds','BG',12,'2021-03-31 11:13:52',0,0),(14,'32193e8e-c999-5410-aa22-405f07444a0e','Characters','CHAR',14,'2021-04-02 16:46:56',0,0),(15,'5bc98436-a5a6-505d-96b6-99902943c730','Props','PROP',14,'2021-04-02 16:46:57',0,0),(16,'f97fece5-9567-5a78-97da-3b3ad25fa887','Sets','SET',14,'2021-04-02 16:48:05',0,0),(17,'886a8ac8-7aa9-52a0-9a98-86f04d4dad69','Characters','CHARS',16,'2021-06-23 09:24:52',0,0),(18,'63e8ea3c-b406-55ed-961b-ffc298e3f414','Props','PROPS',16,'2021-06-23 09:24:54',0,0),(19,'7c84f8c9-78d2-5e08-a451-82d62f58c8e9','Sets','SETS',16,'2021-06-23 09:24:56',0,0),(20,'aa414d2c-9aea-5ddf-bcf4-a6fd4fc45376','Backgrounds','BGS',16,'2021-06-23 18:32:36',1,0);
/*!40000 ALTER TABLE `ram_assetgroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_assets`
--

LOCK TABLES `ram_assets` WRITE;
/*!40000 ALTER TABLE `ram_assets` DISABLE KEYS */;
INSERT INTO `ram_assets` VALUES (27,'fe749770-ff85-591c-a212-2bb2a5f10173','Bâton de marche','BATON','baton,accessoire,outil',11,'2021-03-31 11:14:16',0,0),(28,'6caebaa3-47fe-55a5-b28e-4badfe54d4ad','Tristan','TRI','main character,male',14,'2021-04-02 16:48:39',0,0),(29,'a55d6bc1-a1d3-5cab-95c6-bd5880486b13','Isolde','IS','main character,female',14,'2021-04-13 16:59:30',0,0),(30,'ae3359bb-d629-5b8b-8346-46184246b7ee','Excalibur','EXC','sword,weapon',15,'2021-04-13 16:39:59',0,0),(31,'264bce9a-2da6-5b53-a08c-96aabb1a1d25','Tintagel','TIN','city,castle',16,'2021-04-13 16:40:02',0,0),(33,'7119addc-9df1-5be3-9315-02a8499b6831','Boat','BOAT','vehicle,sea',15,'2021-04-26 07:29:19',1,0),(36,'7afbdcd0-027d-506d-aaa7-21c3377cd2b6','Boat','BOAT2','vehicle',15,'2021-04-14 17:17:40',1,0),(57,'88cde35f-e828-5fda-9e7c-906454662e59','Sea','SEA','sea',16,'2021-04-25 12:05:43',0,0),(87,'2d5a14a7-189b-5bf4-8ce7-d79f98aefec1','Boat','NEW','',15,'2021-04-26 07:29:24',0,0),(92,'e1277e90-3708-565f-ad2f-c8b9015e09c5','Tristan','TRISTAN','main character,male,human',17,'2021-06-23 09:25:15',0,0),(97,'9c1ab1de-039c-58d9-b18d-9de1e068d52c','Iseult','ISEULT','main character,female,human',17,'2021-06-23 09:25:34',0,0),(102,'72a080f4-f570-5eaa-af67-7047f2c42dba','Morholt','MORHOLT','secondary character,vilain,giant,male',17,'2021-06-23 09:25:56',0,0),(107,'264280ff-8ac5-554f-866e-eb2d133b6f37','Excalibur','EXC','sword,weapon',18,'2021-06-23 09:26:17',0,0),(112,'35f887ee-b751-5296-9c69-9fa137639d02','Boat','BOAT','vehicle,sea',18,'2021-06-23 09:26:31',0,0),(117,'d145c910-a9a3-5462-8903-02d62a63be63','Tintagel','TINTAGEL','city,castle',19,'2021-06-23 09:26:51',0,0),(122,'97d3a4ed-52e0-5949-a5c9-74aaa55c0a5a','The sea','SEA','sea,water,nature',19,'2021-06-23 09:27:09',0,0),(127,'81bf3bc7-0230-5532-8f26-1918bd7cd543','The Forest','FOREST','trees,nature',19,'2021-06-23 09:27:22',0,0);
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
INSERT INTO `ram_filetypes` VALUES (17,'b1b3c25b-88d8-5591-9a19-37fcbc4015e2','After Effects Project','aep','aep,aepx',0,'2021-06-23 09:22:33',0,0),(18,'02a9a1c8-0e29-5546-b15a-c7dc0b688ab2','After Effects Template','aet','aet',0,'2021-06-23 09:22:35',0,0),(19,'0883e6ac-0bce-5e49-aa54-c2b2c2660ebb','PNG Image','png','png',1,'2021-06-23 09:22:59',0,0),(20,'661f44fa-ae6e-54d9-9b3e-e0ff7775469b','JPEG image','jpg','jpg,jpeg',1,'2021-06-23 09:22:45',0,0),(21,'a93c7dbc-39e5-5423-98cd-3ac2052f7b00','Photoshop','psd','psd,psb',0,'2021-06-23 09:23:03',0,0),(22,'225290c3-9631-57c3-b97b-48b4abe53817','openEXR Image Data','exr','exr',0,'2021-06-23 09:22:41',0,0),(23,'bb46c31e-e535-5276-aaa7-dd301d3191cc','Maya Scene (Binary)','mb','mb',0,'2021-06-23 09:22:51',0,0),(24,'eaf182c7-31ae-510d-b33a-3c167527fd4a','Maya Scene (ASCII)','ma','ma',0,'2021-06-23 09:22:49',0,0),(25,'f438d3bb-ca21-530a-909f-839f3a374aa9','Albembic Geometry','abc','abc',0,'2021-06-23 09:22:31',0,0),(26,'8d70cc65-1c5f-5c35-85ac-d609ff6c85f2','Nuke Script','nk','nk,nuke,nkple',0,'2021-06-23 09:22:52',0,0),(27,'8e6b3b2f-b8b5-56df-a5aa-665c6252714d','Object Geometry','obj','obj',0,'2021-06-23 09:22:56',0,0),(31,'b8ef5d5e-837b-566c-96ea-233524484801','Houdini Scene','hip','hip',0,'2021-06-23 09:22:43',0,0),(32,'c2773de1-f587-5299-9682-ae7c48115998','Premiere Project','prproj','prproj',0,'2021-06-23 09:23:01',0,0),(33,'a4b7c8ea-8a5a-5a5b-b9d5-ea84af150327','Final Cut XML','xml','xml',0,'2021-06-23 09:23:13',0,0),(34,'45b45049-2427-5cbe-9313-9795c8768d60','SVG Vector Graphics','svg','svg',1,'2021-06-23 09:23:10',0,0),(35,'5f8daa27-bf07-5c8b-8fb7-74c5c683e98f','Blender Scenes','blend','blend,blend1,blend2,blend3',0,'2021-06-23 09:45:41',0,0),(36,'1853e433-57f7-5e45-a9f8-f36b5d1b51f0','Krita Image','kra','kra',0,'2021-06-23 09:22:47',0,0),(37,'f2fc7184-50e4-5bd1-815b-f4a1ddb40cd6','Storyboarder Project','story','story',0,'2021-06-23 09:23:06',0,0),(38,'a7e1d408-5bc2-5634-8a21-fd666f21af1f','Open Cut-Out','oco','oco,json',0,'2021-06-23 09:23:45',0,0),(39,'88e6f76c-872b-5216-a11a-bd8278f23995','Open Cell Animation','oca','oca,json',0,'2021-06-23 09:23:58',0,0);
/*!40000 ALTER TABLE `ram_filetypes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_pipefile`
--

LOCK TABLES `ram_pipefile` WRITE;
/*!40000 ALTER TABLE `ram_pipefile` DISABLE KEYS */;
INSERT INTO `ram_pipefile` VALUES (1,'6b7e0e77-ce92-5ca9-8059-6cc698789c0f','texPipe',16,22,NULL,'2021-06-23 10:09:00',0),(2,'f58efc71-11b6-57ed-94b9-5e382f25971f','animPipe',16,25,NULL,'2021-06-23 10:09:34',0),(3,'34b78865-8a9d-5c10-89a1-be40a39df7bc','rigPipe',16,24,NULL,'2021-06-23 10:09:56',0),(4,'067e5491-c9ab-5326-816a-86fdd4cb4c1b','geoPipe',16,25,NULL,'2021-06-23 10:10:24',0),(5,'13ab34ac-dd43-50d0-9b72-a6d5bbbc03c3','vpShaPipe',16,23,NULL,'2021-06-23 10:11:34',0),(6,'6ac0ec44-e39e-5842-b169-b820507332b0','rdrShaPipe',16,23,NULL,'2021-06-23 10:12:40',0),(7,'382da4a6-4ae4-5250-bb0a-775a073b5551','rdrPipe',16,22,NULL,'2021-06-23 10:19:05',0),(8,'f3ebb684-e019-59c1-b2ae-4be9e1d93c5c','layPipe',16,23,NULL,'2021-06-23 10:24:39',0),(9,'cf00cdff-b03d-59b5-90a6-1cb880b1e6fd','editPipe',16,33,NULL,'2021-06-23 10:27:07',0);
/*!40000 ALTER TABLE `ram_pipefile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_pipefilepipe`
--

LOCK TABLES `ram_pipefilepipe` WRITE;
/*!40000 ALTER TABLE `ram_pipefilepipe` DISABLE KEYS */;
INSERT INTO `ram_pipefilepipe` VALUES (1,540,2,'2021-06-23 10:09:25',0),(2,534,3,'2021-06-23 10:09:48',0),(3,532,4,'2021-06-23 10:10:15',0),(4,533,4,'2021-06-23 10:10:33',0),(5,533,5,'2021-06-23 10:11:17',0),(6,532,5,'2021-06-23 10:11:46',0),(7,543,4,'2021-06-23 10:12:24',0),(9,542,6,'2021-06-23 10:13:55',0),(10,541,1,'2021-06-23 10:14:07',0),(11,544,7,'2021-06-23 10:18:57',0),(12,550,3,'2021-06-23 10:23:35',0),(13,546,4,'2021-06-23 10:23:40',0),(14,546,5,'2021-06-23 10:23:41',0),(15,543,5,'2021-06-23 10:23:52',0),(16,548,8,'2021-06-23 10:24:29',0),(17,549,8,'2021-06-23 10:24:44',0),(18,547,1,'2021-06-23 10:25:51',0),(19,551,7,'2021-06-23 10:26:39',0),(20,552,7,'2021-06-23 10:26:56',0),(21,553,9,'2021-06-23 10:27:01',0),(22,554,4,'2021-06-23 10:27:53',0),(23,554,5,'2021-06-23 10:27:55',0),(24,555,8,'2021-06-23 10:28:00',0),(25,557,2,'2021-06-23 10:28:44',0),(26,556,2,'2021-06-23 10:28:48',0),(27,558,4,'2021-06-23 10:29:27',0),(28,559,8,'2021-06-23 10:30:55',0);
/*!40000 ALTER TABLE `ram_pipefilepipe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_pipes`
--

LOCK TABLES `ram_pipes` WRITE;
/*!40000 ALTER TABLE `ram_pipes` DISABLE KEYS */;
INSERT INTO `ram_pipes` VALUES (530,'0e63e3f3-eeb5-5b18-9dc2-e1731d0a95a0',75,76,'2021-06-23 09:34:22',0),(531,'98cc2d78-b069-5a0b-b37e-ca9a26dfa080',76,77,'2021-06-23 09:55:33',0),(532,'06117680-6a5e-5cc0-8cf1-242e69177b8f',77,78,'2021-06-23 09:59:36',0),(533,'b8a3d1be-50b3-5631-85cb-9df7110cdae4',77,79,'2021-06-23 10:00:51',0),(534,'c1a58fa0-e191-5e29-a5f3-d0525b3ac017',79,80,'2021-06-23 10:23:32',1),(535,'e44589bb-38d2-5890-8859-aa2738bfe9e5',78,81,'2021-06-23 10:18:27',1),(540,'6e89c850-e27a-5032-9ef0-5a0a95d38d2a',80,81,'2021-06-23 10:09:22',0),(541,'1227c6ed-86a9-5e06-be18-2856e7dd53a9',78,82,'2021-06-23 10:12:10',0),(542,'fb651789-2676-5045-9ccd-3423c07fffe0',82,81,'2021-06-23 10:12:13',0),(543,'dd5d0439-4381-5d8c-a275-6a23fb119b98',77,82,'2021-06-23 10:12:20',0),(544,'56d3c6fc-5639-5500-b2ee-58c8eeee049b',81,83,'2021-06-23 10:18:49',0),(545,'09925a20-afc3-548f-961b-1b8632b67be5',75,85,'2021-06-23 10:22:50',1),(546,'4a0343af-0f52-522d-9aa1-a32ccb6c465e',77,86,'2021-06-23 10:27:38',1),(547,'cd83e3e7-9cab-5185-b264-944834036fae',85,81,'2021-06-23 10:23:24',0),(548,'77a57346-bcbd-569b-9d59-293fc7f8f9a1',86,85,'2021-06-23 10:23:26',0),(549,'8294cd66-69ea-5e21-97e5-2cadef0359d5',86,80,'2021-06-23 10:23:28',0),(550,'d19cd366-d8bd-5cb0-af7c-40df6c8d580d',79,86,'2021-06-23 10:23:30',0),(551,'0e319742-5176-5ac4-b6ff-53f093853439',83,87,'2021-06-23 10:26:23',0),(552,'872db82d-d6e3-52b0-be41-f8db8057a4f7',83,88,'2021-06-23 10:26:50',0),(553,'653f7b2a-af90-550b-ab8b-7a5946ff68a8',88,87,'2021-06-23 10:26:51',0),(554,'cfd1fa99-a42b-5158-8273-1a1b62181bf3',77,89,'2021-06-23 10:27:32',0),(555,'3d347292-5de4-5760-b401-2d7bdf413a8e',89,86,'2021-06-23 10:27:35',0),(556,'e1cd7bd3-afc0-5eb5-8cbf-8a8150875fe6',80,90,'2021-06-23 10:28:38',0),(557,'f3ea70ff-fed4-55dd-80e1-372e984e6812',90,81,'2021-06-23 10:28:40',0),(558,'4f970b4b-848d-5382-b42a-4586b25848e2',86,90,'2021-06-23 10:28:56',0),(559,'601d5aa9-00e0-5b0e-9778-879acac4c7fb',86,81,'2021-06-23 10:30:52',0);
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
INSERT INTO `ram_projects` VALUES (15,'a1d9a3ca-234e-58e8-85d2-00f5c4d9e6f4','Test Project','TEST',24,2048,858,1.78,'auto','2021-06-23 09:12:40',0,0),(16,'d2275c72-f70c-5773-a0cf-07ac1b78585d','Example Project','EXPLE',24,3996,2160,1.78,'auto','2021-06-23 09:12:54',0,0);
/*!40000 ALTER TABLE `ram_projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_sequences`
--

LOCK TABLES `ram_sequences` WRITE;
/*!40000 ALTER TABLE `ram_sequences` DISABLE KEYS */;
INSERT INTO `ram_sequences` VALUES (12,'e4a0c904-dc79-545a-810e-e3c60b84aeb1','01 - In Tintagel','SEQ01',16,'2021-06-23 09:27:46',0,0),(13,'ebc976e3-7f1c-562f-b4bf-bf455f973e23','02 - At sea','SEQ02',16,'2021-06-23 09:28:22',0,0),(14,'f2322730-f255-5c24-9987-0819216d0e70','03 - The forest','SEQ03',16,'2021-06-23 09:29:03',0,0);
/*!40000 ALTER TABLE `ram_sequences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_shots`
--

LOCK TABLES `ram_shots` WRITE;
/*!40000 ALTER TABLE `ram_shots` DISABLE KEYS */;
INSERT INTO `ram_shots` VALUES (14,'4cfe8821-1a05-5f32-bbd1-1ad27970915c','Shot 001','001',12,0,'2021-06-23 09:27:54',0,0),(15,'d066557c-8709-5d19-b147-ac9ffef4d898','Shot 002','002',12,0,'2021-06-23 09:28:05',0,1),(16,'26e0c10e-0448-535e-ae1d-40c2cb447117','Shot 003','003',12,0,'2021-06-23 09:28:10',0,2),(17,'220d7b63-badf-5e0c-8269-7dc248e15dae','Shot 004','004',13,0,'2021-06-23 09:28:34',0,0),(18,'6c5e8292-15ca-5d73-8f36-f35955aaf367','Shot 005','005',13,5,'2021-06-23 10:56:32',0,1),(19,'31cc5d69-da2d-5779-b8f9-2fef9edd77a2','Shot 006','006',13,0,'2021-06-23 09:28:46',0,2),(20,'b9944f35-70d6-5dfa-bea0-98e24de85283','Shot 007','007',14,5,'2021-06-23 10:53:05',0,0),(21,'fa2b655e-3ae8-5b98-a8c9-bb29c5e7d163','Shot 008','008',14,7,'2021-06-23 10:54:39',0,1),(22,'9cd3eafc-7f13-5cac-bff4-da15b7153243','Shot 009','009',14,0,'2021-06-23 09:29:23',0,2);
/*!40000 ALTER TABLE `ram_shots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_states`
--

LOCK TABLES `ram_states` WRITE;
/*!40000 ALTER TABLE `ram_states` DISABLE KEYS */;
INSERT INTO `ram_states` VALUES (22,'f44eeef5-34a6-5a0d-a832-370c85ac50d5','Nothing to do','NO','#242424','2021-06-23 09:19:46',0,0),(23,'f03f2e74-7535-59f2-8466-3220e0fe0bfd','To do','TODO','#55aaff','2021-06-23 09:20:02',0,0),(24,'47011dda-8eca-515a-9930-d39ad6ded67a','Work in progress','WIP','#ffff7f','2021-06-23 09:20:15',50,0),(25,'477f3a25-ff95-56d3-94ad-fae9803e6599','Waiting for approval','CHK','#ff8903','2021-06-23 09:21:55',80,0),(26,'46ecedf5-9e62-54da-805a-4849796ac6f9','Retake needed','RTK','#ff0000','2021-06-23 09:21:59',75,0),(27,'fb5070cf-19ed-5c6d-b7c4-57a491ec6ec5','Could be better','CBB','#bdff43','2021-06-23 09:21:48',90,0),(28,'0376616e-b849-50de-9116-99ac6b7d66e2','Finished','OK','#00aa00','2021-06-23 09:22:12',100,0),(29,'c1900cf7-620f-579d-babb-f858cca10236','Stand by','STB','#a8a8a8','2021-06-23 18:10:40',0,0);
/*!40000 ALTER TABLE `ram_states` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_status`
--

LOCK TABLES `ram_status` WRITE;
/*!40000 ALTER TABLE `ram_status` DISABLE KEYS */;
INSERT INTO `ram_status` VALUES (36,'122b4aa2-1ddf-51df-9f19-738c6f12b19d',0,15,23,'',1,76,122,NULL,'2021-06-23 18:02:14',0,'2021-06-23 15:52:53'),(37,'edb38747-9f86-5d9a-be37-3317e826ae47',100,15,28,'Ready! I\'ve finally finished my work on this one...',1,76,122,NULL,'2021-06-23 17:57:27',0,'2021-06-23 15:54:21'),(38,'72abcf6a-dcf4-5da9-b17e-595de5db0060',100,15,28,'',1,76,92,NULL,'2021-06-23 18:09:12',0,'2021-06-23 17:36:44'),(39,'b6f0f660-f466-54cc-ad2b-f0880669e050',0,15,23,'',1,77,127,NULL,'2021-06-23 18:10:04',0,'2021-06-23 17:36:47'),(40,'9fabe524-8284-5feb-bd50-65773bdc0630',100,15,28,'',1,76,112,NULL,'2021-06-23 18:09:27',0,'2021-06-23 17:36:50'),(41,'da203784-9727-51a8-b86d-1f74b75a7226',0,15,23,'',1,77,97,NULL,'2021-06-23 17:55:56',0,'2021-06-23 17:51:27'),(42,'e960aa5c-a63c-548b-95eb-7725eb3e18ef',81,15,24,'Work in progress...',1,77,122,NULL,'2021-06-23 17:58:36',0,'2021-06-23 17:57:42'),(43,'d1ab94df-a957-56ea-a044-07a7f7d46e5d',0,15,29,'Waiting for the model to be finished.',1,79,122,NULL,'2021-06-23 18:12:43',0,'2021-06-23 17:58:12'),(44,'1cfdd25b-9e1b-5ee6-b86c-2d9154bfb2cd',0,15,29,'',1,79,102,NULL,'2021-06-23 18:12:20',0,'2021-06-23 17:58:48'),(45,'bf15ba4f-679d-5864-95c1-498012a2839d',90,15,27,'',1,76,117,NULL,'2021-06-23 18:01:16',0,'2021-06-23 18:01:01'),(46,'79e599d7-73be-5068-ae2e-38e2f7d1e3dc',50,15,24,'',1,76,102,NULL,'2021-06-23 18:02:06',0,'2021-06-23 18:01:47'),(47,'f8e4725f-c4a4-5bba-8527-a7ee06a7bcf3',94,15,25,'',1,76,97,NULL,'2021-06-23 18:09:00',0,'2021-06-23 18:08:47'),(48,'f8a0bea0-f20d-5ab1-a190-a811924db6da',75,15,26,'',1,76,127,NULL,'2021-06-23 18:09:19',0,'2021-06-23 18:09:15'),(49,'922d4794-7d4b-580e-892c-07a1f839e955',100,15,28,'',1,76,107,NULL,'2021-06-23 18:09:24',0,'2021-06-23 18:09:21'),(50,'8735843e-d5d1-5078-9949-071f722abfaf',50,15,24,'',1,77,112,NULL,'2021-06-23 18:09:49',0,'2021-06-23 18:09:45'),(51,'8bf19195-92f5-590d-a382-2e01a521eb50',20,15,24,'',1,77,107,NULL,'2021-06-23 18:09:57',0,'2021-06-23 18:09:51'),(52,'1d8fa837-4155-517c-9243-b48f60b8aab8',0,15,29,'',1,77,102,NULL,'2021-06-23 18:11:08',0,'2021-06-23 18:10:06'),(53,'ed166be5-d871-5221-9bbd-e42b7cf3d27c',85,15,24,'',1,77,117,NULL,'2021-06-23 18:11:34',0,'2021-06-23 18:11:12'),(54,'3bf2608e-a2e7-5c40-b59e-459e87473bf6',80,15,25,'',1,77,92,NULL,'2021-06-23 18:11:49',0,'2021-06-23 18:11:43'),(55,'fb4a88ea-4fec-5452-9934-9f369745dce6',0,15,29,'',1,79,112,NULL,'2021-06-23 18:12:03',0,'2021-06-23 18:11:57'),(56,'d05753c0-8002-5a10-859a-d328ce30fcf2',0,15,29,'',1,79,107,NULL,'2021-06-23 18:12:07',0,'2021-06-23 18:12:04'),(57,'3f1664e8-2699-5f4f-b914-3e377262cdb6',0,15,29,'',1,79,127,NULL,'2021-06-23 18:12:10',0,'2021-06-23 18:12:08'),(58,'8c70912b-8695-5bd3-af6f-76779540f12f',0,15,29,'',1,79,97,NULL,'2021-06-23 18:12:14',0,'2021-06-23 18:12:11'),(59,'a84a0c4d-04ac-50a7-b3f9-20a54ced6663',0,15,29,'',1,79,117,NULL,'2021-06-23 18:12:48',0,'2021-06-23 18:12:45'),(60,'286d7d56-c98f-5db6-a460-5102c476118c',0,15,23,'',1,79,92,NULL,'2021-06-23 18:12:51',0,'2021-06-23 18:12:49');
/*!40000 ALTER TABLE `ram_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_stepapplication`
--

LOCK TABLES `ram_stepapplication` WRITE;
/*!40000 ALTER TABLE `ram_stepapplication` DISABLE KEYS */;
INSERT INTO `ram_stepapplication` VALUES (59,28,75,'2021-06-23 09:34:11',0),(60,15,76,'2021-06-23 10:13:02',0),(61,26,76,'2021-06-23 10:13:04',0),(62,16,77,'2021-06-23 10:13:12',0),(63,15,78,'2021-06-23 10:13:18',0),(64,26,78,'2021-06-23 10:13:19',0),(65,19,78,'2021-06-23 10:13:21',0),(66,20,78,'2021-06-23 10:13:23',0),(67,16,79,'2021-06-23 10:13:29',0),(68,16,80,'2021-06-23 10:13:33',0),(69,16,82,'2021-06-23 10:13:41',0),(70,16,81,'2021-06-23 10:13:45',0),(71,21,90,'2021-06-23 10:29:32',0),(72,16,86,'2021-06-23 10:29:39',0),(73,16,89,'2021-06-23 10:29:47',0),(74,26,85,'2021-06-23 10:30:03',0),(75,16,85,'2021-06-23 10:30:14',0);
/*!40000 ALTER TABLE `ram_stepapplication` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_steps`
--

LOCK TABLES `ram_steps` WRITE;
/*!40000 ALTER TABLE `ram_steps` DISABLE KEYS */;
INSERT INTO `ram_steps` VALUES (75,'c0fdf9a7-9995-507e-aa6f-1c7f58aec8f6','Storyboard','STBD',0,'pre',16,-1,'2021-06-23 14:57:46',0),(76,'ed18e685-222a-5675-9634-265db2c5d020','Character Design','CD',0,'asset',16,0,'2021-06-23 09:34:19',0),(77,'b6da1730-d163-531a-99b5-85f6f810fbc6','Modelling','MOD',0,'asset',16,0,'2021-06-23 09:55:29',0),(78,'ba59faf9-dc93-5ad8-8545-60a1f7ec91b8','Textures','TEX',0,'asset',16,0,'2021-06-23 09:59:30',0),(79,'2b03c3dc-ba55-53e5-9f2d-32b4b869a683','Rigging','RIG',0,'asset',16,0,'2021-06-23 10:00:47',0),(80,'d6148857-bbc1-5509-ad21-047c13a2098f','Animation','ANIM',0,'shot',16,-1,'2021-06-23 10:01:06',0),(81,'7418e459-1047-5768-afd8-7e3735c45256','Lighting','LIGHT',0,'shot',16,-1,'2021-06-23 10:03:45',0),(82,'af5cb3df-ca77-5f36-8296-3f311129a29a','Shading','SHADE',0,'asset',16,0,'2021-06-23 10:12:05',0),(83,'adf12ea1-fa9c-5f16-b552-ebc3b7800e13','Compositing','COMP',0,'shot',16,0,'2021-06-23 14:56:18',0),(84,'fa9b5b83-9a04-5fd7-b325-e10970c69d91','Background Design','BG',0,'shot',16,-1,'2021-06-23 10:20:15',1),(85,'e6dbf24d-52ae-5640-9abc-47e3a17e81b5','Matte Painting','MATTE',0,'shot',16,-1,'2021-06-23 10:20:17',0),(86,'5bee772f-d64a-574f-a2c5-f79f0b4799e0','Layout','LAY',0,'shot',16,-1,'2021-06-23 10:22:39',0),(87,'a4948886-e6a4-52ab-b5f0-84fd54e85fb3','Color Grading','CC',0,'post',16,-1,'2021-06-23 10:26:17',0),(88,'7714652a-9389-5750-924f-6bca7f618f29','Editing','EDIT',0,'post',16,-1,'2021-06-23 10:26:42',0),(89,'e0b87207-c96a-5275-be46-29f72e3e6d93','Set Dressing','SET',0,'asset',16,0,'2021-06-23 10:27:22',0),(90,'c8f43b20-17e8-56d0-8b58-4d703886b65d','Visual Effects','VFX',0,'shot',16,-1,'2021-06-23 10:28:16',0);
/*!40000 ALTER TABLE `ram_steps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_stepuser`
--

LOCK TABLES `ram_stepuser` WRITE;
/*!40000 ALTER TABLE `ram_stepuser` DISABLE KEYS */;
INSERT INTO `ram_stepuser` VALUES (42,77,15,'2021-06-23 20:05:01',0),(43,82,15,'2021-06-23 20:05:07',0),(44,80,15,'2021-06-23 20:09:15',0),(45,85,15,'2021-06-23 20:09:18',0);
/*!40000 ALTER TABLE `ram_stepuser` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_templateassetgroups`
--

LOCK TABLES `ram_templateassetgroups` WRITE;
/*!40000 ALTER TABLE `ram_templateassetgroups` DISABLE KEYS */;
INSERT INTO `ram_templateassetgroups` VALUES (14,'efdd4f19-c85e-5bee-820d-a9933a8f7124','Characters','CHARS','2021-06-23 09:18:50',0,0),(15,'56798e61-f890-5a6f-a793-2eef54f878a7','Props','PROPS','2021-06-23 09:18:55',0,0),(16,'9d098fbc-fa99-56e5-a2c3-f7c4cc657fa0','Backgrounds','BGS','2021-06-23 09:19:18',0,0),(17,'411994f4-0f9b-50ca-9bba-4b70f8142088','Sets','SETS','2021-06-23 09:19:09',0,0);
/*!40000 ALTER TABLE `ram_templateassetgroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_templatesteps`
--

LOCK TABLES `ram_templatesteps` WRITE;
/*!40000 ALTER TABLE `ram_templatesteps` DISABLE KEYS */;
INSERT INTO `ram_templatesteps` VALUES (23,'a4e6c8a9-3de4-550e-bf3a-6e4f060e60de','Characters','CHARS',0,'2021-06-23 09:15:01','asset',1,0),(24,'ce44a98c-5a9a-506e-92f0-553f703949f9','Props','PROPS',0,'2021-06-23 09:15:04','asset',1,0),(25,'9b1a2f24-60a4-5eee-911b-b065ddf6bd00','Sets','SETS',0,'2021-06-23 09:15:04','asset',1,0),(26,'cb9e3620-d4a3-5c58-93de-5ef6091c4d1d','New Template Step','NEW',0,'2021-06-23 09:15:28','asset',1,0),(27,'6c38f8c4-12fc-53b9-8d83-92f2c5ba1243','New Template ds','NEW',0,'2021-06-23 09:15:36','asset',1,0),(28,'bd292909-4e5d-5a00-86fe-5cecc29adb3d','Storyboard','STBD',0,'2021-06-23 09:15:52','pre',0,0),(29,'8693fc90-fd1d-5b24-9054-c21dd436d9f7','Modelling','MOD',0,'2021-06-23 09:15:59','asset',0,0),(30,'670fb522-7c81-5651-bcff-975ab7b635f7','Character Design','CD',0,'2021-06-23 09:16:08','asset',0,0),(31,'e30544df-ca15-58ef-8799-742ca2422144','Textures','TEX',0,'2021-06-23 09:16:14','asset',0,0),(32,'37ef238a-ae30-57e3-aced-cec20b8a0462','Shading','SHADE',0,'2021-06-23 09:16:23','asset',0,0),(33,'220647f5-64cd-5100-8844-f3d82ba87f57','Rigging','RIG',0,'2021-06-23 09:16:30','asset',0,0),(34,'6b97037b-53d9-511e-a022-e3c7f2d3eda3','Animation','ANIM',0,'2021-06-23 09:16:38','shot',0,0),(35,'2adf9de8-738d-5d5a-9922-9b416d8cad11','Visual Effects','VFX',0,'2021-06-23 09:16:48','shot',0,0),(36,'7b0bc56f-6ce1-5cc8-91fa-b1ed357db583','Lighting','LIGHT',0,'2021-06-23 09:16:57','shot',0,0),(37,'26bd700b-6244-5d41-8e79-0dc2e4276573','Matte Painting','MATTE',0,'2021-06-23 09:17:33','shot',0,0),(38,'cdb1d94f-c8fe-56a7-bc66-d700169aa963','Background Design','BG',0,'2021-06-23 09:17:24','shot',0,0),(39,'4f667c2c-8a2f-5dd9-aec0-29511de79bf3','Layout','LAY',0,'2021-06-23 09:17:45','shot',0,0),(40,'dc5d8e2f-0042-5185-b564-171bae1813dc','Set Dressing','SET',0,'2021-06-23 09:17:56','asset',0,0),(41,'ad88c1f6-798d-5b20-9040-df30249689a2','Compositing','COMP',0,'2021-06-23 09:18:11','asset',0,0),(42,'d5482577-30d7-5344-94c0-145e602c1a2d','Editing','EDIT',0,'2021-06-23 09:18:20','post',0,0),(43,'f7f1d1c0-5d39-5c63-ad30-ce731b810dea','Color Grading','CC',0,'2021-06-23 09:18:42','post',0,0),(44,'08a29552-8c1e-5306-b109-ddead95fc3a8','New Template Step','NEW',0,'2021-06-23 09:55:56','asset',1,0),(45,'5fa82e5a-9bfc-56a3-a8ac-ef20af63727a','New Template Step','NEW',0,'2021-06-23 10:20:40','asset',1,0),(46,'8531c637-9e16-5ffa-8061-acf9291a14bb','New Template Step','NEW',0,'2021-06-23 10:20:55','asset',1,0),(47,'aa899350-51c4-504d-96d9-010f23804e39','New Template Step','NEW',0,'2021-06-23 10:22:32','asset',1,0);
/*!40000 ALTER TABLE `ram_templatesteps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_users`
--

LOCK TABLES `ram_users` WRITE;
/*!40000 ALTER TABLE `ram_users` DISABLE KEYS */;
INSERT INTO `ram_users` VALUES (14,'bVda5hjqDNLFJia9DCmwwH2p','Ana Arce','Ana','09b4b3eeff6cc464628dd7a486068aa621bddca545307a6069b67bd75b23cf929d435860ebabe3ebe1d36ef1dc0d3a0dda5e3b097b6e7641ea09a7afba3cc074','2021-03-31 08:51:47','auto','project',0,0),(15,'dda68ab7-2364-5be8-9569-47c50e24bc14','Nico Duduf','Duduf','838f648f84f453f56aa05c98a3effeaa333ed3c5a6f78c84865a6dd50ea73be6a78c5d77a64c11b9820bed0fa9334cb6605e3a39619e566fa092c5ac107e940a','2021-03-31 08:51:33','auto','admin',0,0),(16,'a9df40db-3311-5fc5-a2a5-b4752a61fb98','John Doe','John','1488d20c50ae416d5dcbe8d9739af65e37f68fda8851ea12d992066d30b9ed3fd048ff4a80edc31594203286ffc6d78d015c313423e63a7ef1d17754b615e8a6','2021-03-31 08:50:20','auto','standard',0,0),(17,'1bd9e736-bb66-5a33-97cb-caae74b68b86','Jane Doe','Jane','23bd2f66514103fc427feac0fa1c2db998b30543170741151c5973752c188dabbb09ef89603f02e9d241787580c7ea33b54578bc8d27a405299f1bbf8a315ea5','2021-03-31 08:50:35','auto','lead',0,0),(18,'2501ebfa-ff80-5561-9ab6-c6856740fb18','Tester','TestUser','0029a4a1cba0aca765846ae48006d4d6bce31279cdaa951873ab14ba1f7c3c10a8f5241008ea5ff58dc408cc2e19f5208bf92d7239b383b3a3a363f37da5b9bf','2021-04-26 05:01:07','auto','standard',0,0),(19,'17e0d398-ef34-5a76-adb4-fe629182dfc0','Ramses Daemon','Ramses','d1e638c2a39e9f7bb51213211ef2e2ec1a424fdd3f5380c07f543c70ba183b70ce8610ae4852cbafd8f0878bd77fce800044585562917131f60c70a994072b99','2021-06-23 13:48:21','auto','standard',0,0);
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

-- Dump completed on 2021-06-23 22:14:08
