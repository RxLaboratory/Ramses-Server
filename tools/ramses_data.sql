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
-- Dumping data for table `ram_assetgroupasset`
--

LOCK TABLES `ram_assetgroupasset` WRITE;
/*!40000 ALTER TABLE `ram_assetgroupasset` DISABLE KEYS */;
/*!40000 ALTER TABLE `ram_assetgroupasset` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_assetgroups`
--

LOCK TABLES `ram_assetgroups` WRITE;
/*!40000 ALTER TABLE `ram_assetgroups` DISABLE KEYS */;
/*!40000 ALTER TABLE `ram_assetgroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_assets`
--

LOCK TABLES `ram_assets` WRITE;
/*!40000 ALTER TABLE `ram_assets` DISABLE KEYS */;
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
INSERT INTO `ram_projects` VALUES (7,'e08e7a9e-e30d-56ce-8922-5ccf41bd1c65','Mythomen','MYTHO','auto','2021-02-18 14:24:58'),(11,'11986390-13dc-518e-ba20-54f85d3208d2','L\'insouciance des libellules','LIDL','auto','2021-02-17 12:30:31');
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
INSERT INTO `ram_states` VALUES (2,'952bd5b9-8c86-5bf6-8195-e81f6a000247','Work in progress','WIP','#aaaa00','2021-02-17 18:12:33',50),(11,'5261f4cb-e40f-5595-9d42-284fbffdef3d','Stand by','STB','#232323','2021-02-17 18:08:35',0),(12,'6382ad1a-99a7-5fd4-bd31-1bc45a62cda1','Finished','OK','#00aa00','2021-02-17 14:27:21',100),(13,'6ed72e24-2182-51b8-82b1-90a865241131','To do','TODO','#00aaff','2021-02-17 14:27:21',0);
/*!40000 ALTER TABLE `ram_states` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_steps`
--

LOCK TABLES `ram_steps` WRITE;
/*!40000 ALTER TABLE `ram_steps` DISABLE KEYS */;
INSERT INTO `ram_steps` VALUES (5,'df1712ec-821d-5326-a314-e41f4676c993','Animation','ANIM',0,'shot',11,1,'2021-02-22 11:07:39'),(6,'7a8e5e7f-4de9-5237-8576-0f9e7208a6d8','Rigging','RIG',0,'asset',11,2,'2021-02-22 11:07:37'),(8,'9105980b-0660-5835-b4df-3692f2d42376','Modeling','MOD',0,'asset',11,0,'2021-02-22 11:07:39'),(9,'0e0d438e-2f8b-5de6-9e96-af88dba1d506','Textures','TEX',0,'asset',11,3,'2021-02-22 11:07:37'),(11,'584dfe85-9d3c-5a2d-86d7-16680d06aead','Character Design','CD',0,'asset',7,2,'2021-02-22 10:34:59'),(12,'d49e9f41-910b-54ab-9289-4ddcf06fef9e','Editing','EDIT',0,'post',7,1,'2021-02-22 10:35:00'),(13,'4e8f5a2e-aea6-584e-b9ae-a5831ec4675b','Storyboard','STORY',0,'pre',7,0,'2021-02-22 10:35:00'),(14,'21e75653-b7ac-5276-928d-5a6635fad276','Compositing','COMP',0,'shot',7,3,'2021-02-22 10:11:07'),(21,'7db9d290-6109-52dc-9dda-4442017e916d','Animation','ANIM',0,'shot',7,4,'2021-02-22 10:34:59');
/*!40000 ALTER TABLE `ram_steps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_stepuser`
--

LOCK TABLES `ram_stepuser` WRITE;
/*!40000 ALTER TABLE `ram_stepuser` DISABLE KEYS */;
INSERT INTO `ram_stepuser` VALUES (2,8,1,'2021-02-22 15:30:24'),(3,8,5,'2021-02-22 15:30:36'),(7,5,1,'2021-02-22 15:31:56'),(9,9,1,'2021-02-22 15:33:59'),(10,5,5,'2021-02-22 15:34:04'),(13,9,5,'2021-02-22 15:40:00'),(14,5,12,'2021-02-22 15:51:37'),(17,6,12,'2021-02-22 17:11:28'),(18,6,13,'2021-02-22 17:11:29');
/*!40000 ALTER TABLE `ram_stepuser` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_templatesteps`
--

LOCK TABLES `ram_templatesteps` WRITE;
/*!40000 ALTER TABLE `ram_templatesteps` DISABLE KEYS */;
INSERT INTO `ram_templatesteps` VALUES (1,'dcb57b02-a32a-5736-b758-a7b41926d822','Character Design','CD',0,'2021-02-17 12:28:02','asset'),(2,'9879f545-3edc-514a-bf46-b8e1b1baebda','Storyboard','STORY',0,'2021-02-17 12:28:18','pre'),(3,'c8377eda-384b-5540-9d3f-71171fc2a49c','Modeling','MOD',0,'2021-02-17 12:36:55','asset'),(4,'e092b191-fa7b-58d1-be8f-284abec57d39','Animation','ANIM',0,'2021-02-17 13:20:18','shot'),(5,'f89c16b4-ccac-52cf-aeef-eb7a04d5d765','Editing','EDIT',0,'2021-02-17 13:27:11','post');
/*!40000 ALTER TABLE `ram_templatesteps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ram_users`
--

LOCK TABLES `ram_users` WRITE;
/*!40000 ALTER TABLE `ram_users` DISABLE KEYS */;
INSERT INTO `ram_users` VALUES (1,'2d7d7e01-671c-11e7-a78f-4ccc6a288527','Nicolas Dufresne','Duduf','499729131d2f160d3f0ee99b01f25c4a7c735fba675d44a3909b2145aaace90f9dac5675d291f642dc34f96c9963f4c55d5b0fc6122f85904f66c038b5ccff08','2021-02-18 13:28:29','auto','admin'),(5,'ac19a485-3115-5578-b1f1-c5c386f1092b','Ana Arce','Ana','69eac4ab802f709012f5c58d193165a7722aed955ccdbb1c90cf194362481a103711fd3fbec05bcf14f7cd45826e8d49cfa94cf93a039c6e0e8757944e537a13','2021-02-22 11:03:02','auto','project'),(12,'51de0082-cf7d-5d6a-ae3e-37ef864e2d74','Jane Doe','Jane','b7906e6d72018423a530b9eb040dd5858a4b9ca1adaa68e1aeb95e4b1a024a4520cf146bfba9bbafa588053536cf557e4f0dbd7d95fe6dd660cb5f077fadb3a6','2021-02-22 11:00:06','auto','lead'),(13,'b6ae72a7-959f-55ce-8cf6-2f0424643fa6','John Doe','John','4567eda55ed3455a62ff4b31193f3f63bb665ce02cdbae14ab930e74ae6c11cc225d482989df7fe7a76a558924d70394cc4533f9744ea2934c334603b1d90b54','2021-02-22 15:57:48','auto','standard');
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

-- Dump completed on 2021-03-04 11:29:26
