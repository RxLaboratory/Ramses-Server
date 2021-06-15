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
-- Dumping data for table `ram_pipefile`
--

LOCK TABLES `ram_pipefile` WRITE;
/*!40000 ALTER TABLE `ram_pipefile` DISABLE KEYS */;
/*!40000 ALTER TABLE `ram_pipefile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_pipefilepipe`
--

LOCK TABLES `ram_pipefilepipe` WRITE;
/*!40000 ALTER TABLE `ram_pipefilepipe` DISABLE KEYS */;
/*!40000 ALTER TABLE `ram_pipefilepipe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_pipes`
--

LOCK TABLES `ram_pipes` WRITE;
/*!40000 ALTER TABLE `ram_pipes` DISABLE KEYS */;
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
/*!40000 ALTER TABLE `ram_projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_sequences`
--

LOCK TABLES `ram_sequences` WRITE;
/*!40000 ALTER TABLE `ram_sequences` DISABLE KEYS */;
/*!40000 ALTER TABLE `ram_sequences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_shots`
--

LOCK TABLES `ram_shots` WRITE;
/*!40000 ALTER TABLE `ram_shots` DISABLE KEYS */;
/*!40000 ALTER TABLE `ram_shots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_states`
--

LOCK TABLES `ram_states` WRITE;
/*!40000 ALTER TABLE `ram_states` DISABLE KEYS */;
/*!40000 ALTER TABLE `ram_states` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_status`
--

LOCK TABLES `ram_status` WRITE;
/*!40000 ALTER TABLE `ram_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `ram_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_stepapplication`
--

LOCK TABLES `ram_stepapplication` WRITE;
/*!40000 ALTER TABLE `ram_stepapplication` DISABLE KEYS */;
/*!40000 ALTER TABLE `ram_stepapplication` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_steps`
--

LOCK TABLES `ram_steps` WRITE;
/*!40000 ALTER TABLE `ram_steps` DISABLE KEYS */;
/*!40000 ALTER TABLE `ram_steps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_stepuser`
--

LOCK TABLES `ram_stepuser` WRITE;
/*!40000 ALTER TABLE `ram_stepuser` DISABLE KEYS */;
/*!40000 ALTER TABLE `ram_stepuser` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_templateassetgroups`
--

LOCK TABLES `ram_templateassetgroups` WRITE;
/*!40000 ALTER TABLE `ram_templateassetgroups` DISABLE KEYS */;
/*!40000 ALTER TABLE `ram_templateassetgroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_templatesteps`
--

LOCK TABLES `ram_templatesteps` WRITE;
/*!40000 ALTER TABLE `ram_templatesteps` DISABLE KEYS */;
/*!40000 ALTER TABLE `ram_templatesteps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_users`
--

LOCK TABLES `ram_users` WRITE;
/*!40000 ALTER TABLE `ram_users` DISABLE KEYS */;
INSERT INTO `ram_users` VALUES (14,'bVda5hjqDNLFJia9DCmwwH2p','Ana Arce','Ana','09b4b3eeff6cc464628dd7a486068aa621bddca545307a6069b67bd75b23cf929d435860ebabe3ebe1d36ef1dc0d3a0dda5e3b097b6e7641ea09a7afba3cc074','2021-03-31 08:51:47','auto','project',0,0),(15,'dda68ab7-2364-5be8-9569-47c50e24bc14','Nico Duduf','Duduf','838f648f84f453f56aa05c98a3effeaa333ed3c5a6f78c84865a6dd50ea73be6a78c5d77a64c11b9820bed0fa9334cb6605e3a39619e566fa092c5ac107e940a','2021-03-31 08:51:33','auto','admin',0,0),(16,'a9df40db-3311-5fc5-a2a5-b4752a61fb98','John Doe','John','1488d20c50ae416d5dcbe8d9739af65e37f68fda8851ea12d992066d30b9ed3fd048ff4a80edc31594203286ffc6d78d015c313423e63a7ef1d17754b615e8a6','2021-03-31 08:50:20','auto','standard',0,0),(17,'1bd9e736-bb66-5a33-97cb-caae74b68b86','Jane Doe','Jane','23bd2f66514103fc427feac0fa1c2db998b30543170741151c5973752c188dabbb09ef89603f02e9d241787580c7ea33b54578bc8d27a405299f1bbf8a315ea5','2021-03-31 08:50:35','auto','lead',0,0),(18,'2501ebfa-ff80-5561-9ab6-c6856740fb18','Tester','TestUser','0029a4a1cba0aca765846ae48006d4d6bce31279cdaa951873ab14ba1f7c3c10a8f5241008ea5ff58dc408cc2e19f5208bf92d7239b383b3a3a363f37da5b9bf','2021-04-26 05:01:07','auto','standard',0,0);
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

-- Dump completed on 2021-06-15 15:36:36
