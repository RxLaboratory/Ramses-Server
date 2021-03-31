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
INSERT INTO `ram_applicationfiletype` VALUES (1,14,17,'native','2021-03-31 11:04:12',0),(2,14,18,'native','2021-03-31 11:04:14',0),(3,14,19,'import','2021-03-31 11:04:16',0),(6,14,20,'import','2021-03-31 11:04:20',0),(7,14,19,'export','2021-03-31 11:04:23',0),(8,14,20,'export','2021-03-31 11:04:24',0),(9,15,19,'import','2021-03-31 11:04:59',0),(10,15,20,'import','2021-03-31 11:05:00',0),(11,15,19,'export','2021-03-31 11:05:02',0),(12,15,20,'export','2021-03-31 11:05:03',0),(13,15,21,'native','2021-03-31 11:05:27',0),(14,14,21,'import','2021-03-31 11:05:31',0),(15,14,21,'export','2021-03-31 11:05:33',0);
/*!40000 ALTER TABLE `ram_applicationfiletype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_applications`
--

LOCK TABLES `ram_applications` WRITE;
/*!40000 ALTER TABLE `ram_applications` DISABLE KEYS */;
INSERT INTO `ram_applications` VALUES (14,'13b6c954-2738-5d30-a2f1-80f34753fcfe','Adobe After Effects','Ae','','2021-03-31 11:04:48',0),(15,'af92e804-db38-5692-9a76-5aa35edb3b39','Adobe Photoshop','Ps','','2021-03-31 11:04:54',0);
/*!40000 ALTER TABLE `ram_applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_assetgroups`
--

LOCK TABLES `ram_assetgroups` WRITE;
/*!40000 ALTER TABLE `ram_assetgroups` DISABLE KEYS */;
INSERT INTO `ram_assetgroups` VALUES (11,'60af61d1-767a-5bd4-a8c6-066528d020f3','Props','PROP',12,'2021-03-31 11:13:50',0),(12,'0faf70ff-4a7b-52a6-9beb-545c35118945','Characters','CHAR',12,'2021-03-31 11:13:51',0),(13,'688e1cec-5e6c-5a6c-b8d5-0fedca27b99e','Backgrounds','BG',12,'2021-03-31 11:13:52',0);
/*!40000 ALTER TABLE `ram_assetgroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_assets`
--

LOCK TABLES `ram_assets` WRITE;
/*!40000 ALTER TABLE `ram_assets` DISABLE KEYS */;
INSERT INTO `ram_assets` VALUES (27,'fe749770-ff85-591c-a212-2bb2a5f10173','Bâton de marche','BATON','baton,accessoire,outil',11,'2021-03-31 11:14:16',0);
/*!40000 ALTER TABLE `ram_assets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_assetstatuses`
--

LOCK TABLES `ram_assetstatuses` WRITE;
/*!40000 ALTER TABLE `ram_assetstatuses` DISABLE KEYS */;
/*!40000 ALTER TABLE `ram_assetstatuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_filetypes`
--

LOCK TABLES `ram_filetypes` WRITE;
/*!40000 ALTER TABLE `ram_filetypes` DISABLE KEYS */;
INSERT INTO `ram_filetypes` VALUES (17,'b1b3c25b-88d8-5591-9a19-37fcbc4015e2','After Effects Project','.aep','aep,aepx',0,'2021-03-31 11:03:19',0),(18,'02a9a1c8-0e29-5546-b15a-c7dc0b688ab2','After Effects Template','.aet','aet',0,'2021-03-31 11:03:34',0),(19,'0883e6ac-0bce-5e49-aa54-c2b2c2660ebb','PNG Image','.png','png',1,'2021-03-31 11:03:45',0),(20,'661f44fa-ae6e-54d9-9b3e-e0ff7775469b','JPEG image','.jpg','jpg,jpeg',1,'2021-03-31 11:03:58',0),(21,'a93c7dbc-39e5-5423-98cd-3ac2052f7b00','Photoshop','.psd','psd,psb',0,'2021-03-31 11:05:22',0);
/*!40000 ALTER TABLE `ram_filetypes` ENABLE KEYS */;
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
INSERT INTO `ram_projects` VALUES (12,'02cfde73-48a7-5080-838e-cee25736ee47','L\'insouciance des libellules','LIDL','auto','2021-03-31 10:51:59',0),(13,'c2abbe47-d9d0-512d-acdb-1b98158363fc','Mythomen','MYTHO','auto','2021-03-31 10:52:13',0);
/*!40000 ALTER TABLE `ram_projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_projectshot`
--

LOCK TABLES `ram_projectshot` WRITE;
/*!40000 ALTER TABLE `ram_projectshot` DISABLE KEYS */;
/*!40000 ALTER TABLE `ram_projectshot` ENABLE KEYS */;
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
INSERT INTO `ram_states` VALUES (14,'efea0416-1456-515c-9a4e-5ce6e7d2000e','OK','OK','%2355aa00','2021-03-31 11:02:01',100,0),(15,'05a13d0e-a2f8-5e0b-864e-c8cfc8ce22a2','Work in progress','WIP','%23ffff7f','2021-03-31 11:00:09',50,0),(16,'173c2dc8-916a-5fe3-989a-54e420c46fca','Waiting for approval','CHK','%23ff5500','2021-03-31 11:00:44',75,0),(17,'1d1d9f12-79cd-5f29-8ad5-97ed8b7a2ea8','To do','TODO','%2355ffff','2021-03-31 11:00:59',0,0),(18,'04fb9195-ddb0-5ff7-9b17-e757085bd7f8','Stand by','STB','%23434343','2021-03-31 11:01:21',0,0),(19,'0be03056-b5db-5945-8117-63bfbff9a574','Could be better','CBB','%2355ff7f','2021-03-31 11:01:53',90,0),(20,'47325dd4-36e3-5b50-b419-00efc97c9e83','Retake','RTK','%23da0000','2021-03-31 11:02:48',75,0);
/*!40000 ALTER TABLE `ram_states` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_stepapplication`
--

LOCK TABLES `ram_stepapplication` WRITE;
/*!40000 ALTER TABLE `ram_stepapplication` DISABLE KEYS */;
INSERT INTO `ram_stepapplication` VALUES (1,15,24,'2021-03-31 11:13:10',0),(2,14,25,'2021-03-31 11:13:21',0);
/*!40000 ALTER TABLE `ram_stepapplication` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_steps`
--

LOCK TABLES `ram_steps` WRITE;
/*!40000 ALTER TABLE `ram_steps` DISABLE KEYS */;
INSERT INTO `ram_steps` VALUES (24,'27858b5b-d7d4-5f50-8f15-b156162a7d72','Character Design','CD',0,'asset',12,0,'2021-03-31 11:05:56',0),(25,'25b7fce6-56c8-598b-9530-7a4e913be34c','Rigging','RIG',0,'asset',12,0,'2021-03-31 11:13:16',0);
/*!40000 ALTER TABLE `ram_steps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_stepuser`
--

LOCK TABLES `ram_stepuser` WRITE;
/*!40000 ALTER TABLE `ram_stepuser` DISABLE KEYS */;
INSERT INTO `ram_stepuser` VALUES (27,24,16,'2021-03-31 11:06:14',0),(28,24,17,'2021-03-31 11:06:15',0),(29,24,15,'2021-03-31 11:06:16',0),(30,25,14,'2021-03-31 11:13:22',0),(31,25,15,'2021-03-31 11:13:23',0);
/*!40000 ALTER TABLE `ram_stepuser` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_templateassetgroups`
--

LOCK TABLES `ram_templateassetgroups` WRITE;
/*!40000 ALTER TABLE `ram_templateassetgroups` DISABLE KEYS */;
INSERT INTO `ram_templateassetgroups` VALUES (10,'954204b1-1950-5be7-9ffd-74ab4640b794','Props','PROP','2021-03-31 10:57:16',0),(11,'39ca4b52-6774-5c5d-a8df-aea6921cea50','Characters','CHAR','2021-03-31 10:57:25',0),(12,'aca51d86-357a-5564-a182-1f13b91bbb7d','Sets','SET','2021-03-31 10:57:33',0),(13,'62da98ee-d983-532f-bb7b-517402b66248','Backgrounds','BG','2021-03-31 10:57:58',0);
/*!40000 ALTER TABLE `ram_templateassetgroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_templatesteps`
--

LOCK TABLES `ram_templatesteps` WRITE;
/*!40000 ALTER TABLE `ram_templatesteps` DISABLE KEYS */;
INSERT INTO `ram_templatesteps` VALUES (7,'a29e2c57-ddc1-5c97-91b5-69b02da969d5','Character Design','CD',0,'2021-03-31 10:52:31','asset',0),(8,'eb43cace-3cc8-5ae4-a4f7-b80e1c9144ec','Storyboard','SB',0,'2021-03-31 10:52:47','pre',0),(9,'864110b2-7c1d-504a-8246-d0e0b55777d9','Edit','EDIT',0,'2021-03-31 10:52:55','post',0),(10,'e16703e5-7458-5961-bf73-8b19411bf93e','Rigging','RIG',0,'2021-03-31 10:53:04','asset',0),(11,'3a9e7bb8-bda7-51f8-96da-939518367db6','Lighting','LIGHT',0,'2021-03-31 10:53:49','shot',0),(12,'2b89be14-8019-599c-90de-8b3742d75948','Texture','TEX',0,'2021-03-31 10:53:58','asset',0),(13,'970751c5-2c2b-556f-8976-393eb3d59f12','Modeling','MOD',0,'2021-03-31 10:54:25','asset',0),(14,'7b0388b7-9035-5a7d-b78c-fddf7c048432','Shading','SHADE',0,'2021-03-31 10:54:41','asset',0),(15,'d36a8656-f51c-588f-a0c5-b03b0da1040c','Visual Effects','VFX',0,'2021-03-31 10:55:03','shot',0),(16,'6467b134-d6b4-5fdc-822e-4eb773333d15','Rendering','RENDER',0,'2021-03-31 10:55:15','shot',0),(17,'5ab4bda6-2dec-58c9-b5dd-197fcd5ea038','Color Grading','COLO',0,'2021-03-31 10:55:28','post',0),(18,'9f051087-cf36-5537-a2be-beae18b103c7','Compositing','COMP',0,'2021-03-31 10:55:40','shot',0),(19,'2d21f60e-6f7f-56a4-83d3-d478f84187b4','Layout','LAY',0,'2021-03-31 10:55:58','asset',0),(20,'b8b25b0f-cd5e-52f5-99cb-6585082be148','Matte Painting','MATTE',0,'2021-03-31 10:56:19','asset',0),(21,'cca7cf4e-09a3-5a75-80b7-019a0c28be36','Set Design','SD',0,'2021-03-31 10:57:47','asset',0);
/*!40000 ALTER TABLE `ram_templatesteps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_users`
--

LOCK TABLES `ram_users` WRITE;
/*!40000 ALTER TABLE `ram_users` DISABLE KEYS */;
INSERT INTO `ram_users` VALUES (14,'bVda5hjqDNLFJia9DCmwwH2p','Ana Arce','Ana','09b4b3eeff6cc464628dd7a486068aa621bddca545307a6069b67bd75b23cf929d435860ebabe3ebe1d36ef1dc0d3a0dda5e3b097b6e7641ea09a7afba3cc074','2021-03-31 10:51:47','auto','project',0),(15,'dda68ab7-2364-5be8-9569-47c50e24bc14','Nico Duduf','Duduf','838f648f84f453f56aa05c98a3effeaa333ed3c5a6f78c84865a6dd50ea73be6a78c5d77a64c11b9820bed0fa9334cb6605e3a39619e566fa092c5ac107e940a','2021-03-31 10:51:33','auto','admin',0),(16,'a9df40db-3311-5fc5-a2a5-b4752a61fb98','John Doe','John','1488d20c50ae416d5dcbe8d9739af65e37f68fda8851ea12d992066d30b9ed3fd048ff4a80edc31594203286ffc6d78d015c313423e63a7ef1d17754b615e8a6','2021-03-31 10:50:20','auto','standard',0),(17,'1bd9e736-bb66-5a33-97cb-caae74b68b86','Jane Doe','Jane','23bd2f66514103fc427feac0fa1c2db998b30543170741151c5973752c188dabbb09ef89603f02e9d241787580c7ea33b54578bc8d27a405299f1bbf8a315ea5','2021-03-31 10:50:35','auto','lead',0);
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

-- Dump completed on 2021-03-31 13:22:43
