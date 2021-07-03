SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
SET foreign_key_checks = 0;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

DROP TABLE IF EXISTS `ram_applicationfiletype`;
DROP TABLE IF EXISTS `ram_applications`;
DROP TABLE IF EXISTS `ram_assetgroups`;
DROP TABLE IF EXISTS `ram_assets`;
DROP TABLE IF EXISTS `ram_colorspaces`;
DROP TABLE IF EXISTS `ram_filetypes`;
DROP TABLE IF EXISTS `ram_pipefile`;
DROP TABLE IF EXISTS `ram_pipefilepipe`;
DROP TABLE IF EXISTS `ram_pipes`;
DROP TABLE IF EXISTS `ram_projects`;
DROP TABLE IF EXISTS `ram_projectuser`;
DROP TABLE IF EXISTS `ram_schedule`;
DROP TABLE IF EXISTS `ram_sequences`;
DROP TABLE IF EXISTS `ram_shots`;
DROP TABLE IF EXISTS `ram_states`;
DROP TABLE IF EXISTS `ram_status`;
DROP TABLE IF EXISTS `ram_shotasset`;
DROP TABLE IF EXISTS `ram_stepapplication`;
DROP TABLE IF EXISTS `ram_steps`;
DROP TABLE IF EXISTS `ram_templateassetgroups`;
DROP TABLE IF EXISTS `ram_templatesteps`;
DROP TABLE IF EXISTS `ram_users`;

CREATE TABLE `ram_applicationfiletype` (
  `id` int NOT NULL,
  `applicationId` int NOT NULL,
  `filetypeId` int NOT NULL,
  `type` enum('native','export','import') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'native',
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `removed` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ram_applications` (
  `id` int NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `executableFilePath` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `removed` tinyint NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT '0',
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ram_assetgroups` (
  `id` int NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `projectId` int NOT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `removed` tinyint NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT '0',
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ram_assets` (
  `id` int NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `tags` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `assetGroupId` int NOT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `removed` tinyint NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT '0',
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ram_colorspaces` (
  `id` int NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `removed` tinyint(1) NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT '0',
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ram_filetypes` (
  `id` int NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `extensions` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `previewable` tinyint NOT NULL DEFAULT '0',
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `removed` tinyint NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT '0',
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ram_pipefile` (
  `id` int NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `projectId` int NOT NULL,
  `filetypeId` int DEFAULT NULL,
  `colorSpaceId` int DEFAULT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `removed` tinyint NOT NULL DEFAULT '0',
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ram_pipefilepipe` (
  `id` int NOT NULL,
  `pipeId` int NOT NULL,
  `pipeFileId` int NOT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `removed` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ram_pipes` (
  `id` int NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `outputStepId` int NOT NULL,
  `inputStepId` int NOT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `removed` tinyint(1) NOT NULL DEFAULT '0',
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ram_projects` (
  `id` int NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `framerate` float NOT NULL DEFAULT '24',
  `width` int NOT NULL DEFAULT '1920',
  `height` int NOT NULL DEFAULT '1080',
  `aspectRatio` float NOT NULL DEFAULT '1.78',
  `deadline` date DEFAULT NULL,
  `folderPath` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'auto',
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `removed` tinyint NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT '0',
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ram_projectuser` (
  `id` int NOT NULL,
  `projectId` int NOT NULL,
  `userId` int NOT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `removed` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ram_schedule` (
  `id` int NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `userId` int NOT NULL,
  `stepId` int NOT NULL,
  `date` datetime NOT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ram_sequences` (
  `id` int NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `projectId` int NOT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `removed` tinyint NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT '0',
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ram_shotasset` (
  `id` int NOT NULL,
  `shotId` int NOT NULL,
  `assetId` int NOT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ram_shots` (
  `id` int NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sequenceId` int NOT NULL,
  `duration` float NOT NULL DEFAULT '0',
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `removed` tinyint NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT '0',
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ram_states` (
  `id` int NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '#434343',
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `completionRatio` tinyint(1) NOT NULL DEFAULT '50',
  `removed` tinyint NOT NULL DEFAULT '0',
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ram_status` (
  `id` int NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `completionRatio` tinyint(1) NOT NULL DEFAULT '-1',
  `userId` int NOT NULL,
  `stateId` int NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `version` int NOT NULL DEFAULT '1',
  `timeSpent` int UNSIGNED NOT NULL DEFAULT '0',
  `difficulty` enum('veryEasy','easy','medium','hard','veryHard') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'medium',
  `estimation` decimal(5,2) NOT NULL DEFAULT '-1.00',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `assignedUserId` int DEFAULT NULL,
  `stepId` int NOT NULL,
  `assetId` int DEFAULT NULL,
  `shotId` int DEFAULT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `removed` tinyint NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ram_stepapplication` (
  `id` int NOT NULL,
  `applicationId` int NOT NULL,
  `stepId` int NOT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `removed` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ram_steps` (
  `id` int NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `autoCreateAssets` tinyint(1) NOT NULL DEFAULT '0',
  `type` enum('pre','asset','shot','post') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'asset',
  `color` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '#e3e3e3',
  `estimationMethod` enum('shot','second') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'shot',
  `estimationVeryEasy` decimal(5,2) NOT NULL DEFAULT '0.20',
  `estimationEasy` decimal(5,2) NOT NULL DEFAULT '0.50',
  `estimationMedium` decimal(5,2) NOT NULL DEFAULT '1.00',
  `estimationHard` decimal(5,2) NOT NULL DEFAULT '2.00',
  `estimationVeryHard` decimal(5,2) NOT NULL DEFAULT '3.00',
  `estimationMultiplyGroupId` int DEFAULT NULL,
  `projectId` int NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `removed` tinyint NOT NULL DEFAULT '0',
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ram_templateassetgroups` (
  `id` int NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `latestUpdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `removed` tinyint NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT '0',
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ram_templatesteps` (
  `id` int NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `shortName` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `autoCreateAssets` tinyint(1) NOT NULL DEFAULT '0',
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `type` enum('pre','asset','shot','post') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'asset',
  `color` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#e3e3e3',
  `estimationMethod` enum('shot','second') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'shot',
  `estimationVeryEasy` decimal(5,2) NOT NULL DEFAULT '0.20',
  `estimationEasy` decimal(5,2) NOT NULL DEFAULT '0.50',
  `estimationMedium` decimal(5,2) NOT NULL DEFAULT '1.00',
  `estimationHard` decimal(5,2) NOT NULL DEFAULT '2.00',
  `estimationVeryHard` decimal(5,2) NOT NULL DEFAULT '3.00',
  `removed` tinyint NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT '0',
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ram_users` (
  `id` int NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `folderPath` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'auto',
  `role` enum('admin','project','lead','standard') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'standard',
  `removed` tinyint NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT '0',
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `ram_applicationfiletype`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `apptype_unique` (`applicationId`,`filetypeId`,`type`),
  ADD KEY `fk_applicationfiletype_filetype` (`filetypeId`);

ALTER TABLE `ram_applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD UNIQUE KEY `exec_unique` (`executableFilePath`,`shortName`,`name`);

ALTER TABLE `ram_assetgroups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_assetgroups_projectid` (`projectId`);

ALTER TABLE `ram_assets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_assets_assgroup` (`assetGroupId`);

ALTER TABLE `ram_colorspaces`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`,`shortName`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`);

ALTER TABLE `ram_filetypes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD UNIQUE KEY `filetypeName` (`name`,`shortName`);

ALTER TABLE `ram_pipefile`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD KEY `fk_pipefile_colorspace` (`colorSpaceId`),
  ADD KEY `fk_ram_pipefile_filetype` (`filetypeId`);

ALTER TABLE `ram_pipefilepipe`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `association_UNIQUE` (`pipeId`,`pipeFileId`),
  ADD KEY `fk_pipefilepipe_pipe` (`pipeId`),
  ADD KEY `fk_pipefilepipe_pipefile` (`pipeFileId`);

ALTER TABLE `ram_pipes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `step_UNIQUE` (`outputStepId`,`inputStepId`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD KEY `fk_pipes_input` (`inputStepId`);

ALTER TABLE `ram_projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `shortname` (`shortName`);

ALTER TABLE `ram_projectuser`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `unique_projectuser` (`projectId`,`userId`),
  ADD KEY `fk_projectuser_userId` (`userId`),
  ADD KEY `fk_projectuser_projectId` (`projectId`);

ALTER TABLE `ram_schedule`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_usr_step_date` (`date`,`stepId`,`userId`),
  ADD UNIQUE KEY `uuid_unique` (`uuid`),
  ADD KEY `fk_schedule_user` (`userId`),
  ADD KEY `fk_schedule_step` (`stepId`);

ALTER TABLE `ram_sequences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD KEY `fk_sequences_projectid` (`projectId`);

ALTER TABLE `ram_shotasset`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_shotasset_shot` (`shotId`),
  ADD KEY `fk_shotasset_asset` (`assetId`);

ALTER TABLE `ram_shots`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `fk_shots_sequence` (`sequenceId`);

ALTER TABLE `ram_states`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

ALTER TABLE `ram_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD KEY `fk_status_user` (`userId`),
  ADD KEY `fk_status_state` (`stateId`),
  ADD KEY `fk_status_step` (`stepId`),
  ADD KEY `fk_status_asset` (`assetId`),
  ADD KEY `fk_status_shot` (`shotId`),
  ADD KEY `fk_status_assigneduser` (`assignedUserId`);

ALTER TABLE `ram_stepapplication`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `stepapp_UNIQUE` (`applicationId`,`stepId`),
  ADD KEY `fk_stepapplication_step` (`stepId`);

ALTER TABLE `ram_steps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `fk_steps_projectId` (`projectId`),
  ADD KEY `fk_estimationGroupId` (`estimationMultiplyGroupId`);

ALTER TABLE `ram_templateassetgroups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `shortName_UNIQUE` (`shortName`,`name`);

ALTER TABLE `ram_templatesteps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`);

ALTER TABLE `ram_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userName` (`shortName`),
  ADD UNIQUE KEY `uuid` (`uuid`);


ALTER TABLE `ram_applicationfiletype`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_applications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_assetgroups`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_assets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_colorspaces`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_filetypes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_pipefile`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_pipefilepipe`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_pipes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_projects`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_projectuser`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_schedule`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_sequences`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_shotasset`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_shots`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_states`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_stepapplication`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_steps`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_templateassetgroups`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_templatesteps`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;


ALTER TABLE `ram_applicationfiletype`
  ADD CONSTRAINT `fk_applicationfiletype_app` FOREIGN KEY (`applicationId`) REFERENCES `ram_applications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_applicationfiletype_filetype` FOREIGN KEY (`filetypeId`) REFERENCES `ram_filetypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `ram_assetgroups`
  ADD CONSTRAINT `fk_assetgroups_projectid` FOREIGN KEY (`projectId`) REFERENCES `ram_projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `ram_assets`
  ADD CONSTRAINT `fk_assets_assgroup` FOREIGN KEY (`assetGroupId`) REFERENCES `ram_assetgroups` (`id`);

ALTER TABLE `ram_pipefile`
  ADD CONSTRAINT `fk_pipefile_colorspace` FOREIGN KEY (`colorSpaceId`) REFERENCES `ram_colorspaces` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `fk_pipefile_filetype` FOREIGN KEY (`filetypeId`) REFERENCES `ram_filetypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `ram_pipefilepipe`
  ADD CONSTRAINT `fk_pipefilepipe_pipe` FOREIGN KEY (`pipeId`) REFERENCES `ram_pipes` (`id`),
  ADD CONSTRAINT `fk_pipefilepipe_pipefile` FOREIGN KEY (`pipeFileId`) REFERENCES `ram_pipefile` (`id`);

ALTER TABLE `ram_pipes`
  ADD CONSTRAINT `fk_pipes_input` FOREIGN KEY (`inputStepId`) REFERENCES `ram_steps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pipes_output` FOREIGN KEY (`outputStepId`) REFERENCES `ram_steps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `ram_projectuser`
  ADD CONSTRAINT `fk_projectuser_projectId` FOREIGN KEY (`projectId`) REFERENCES `ram_projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_projectuser_userId` FOREIGN KEY (`userId`) REFERENCES `ram_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `ram_schedule`
  ADD CONSTRAINT `fk_schedule_step` FOREIGN KEY (`stepId`) REFERENCES `ram_steps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_schedule_user` FOREIGN KEY (`userId`) REFERENCES `ram_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `ram_sequences`
  ADD CONSTRAINT `fk_sequences_projectid` FOREIGN KEY (`projectId`) REFERENCES `ram_projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `ram_shotasset`
  ADD CONSTRAINT `fk_shotasset_asset` FOREIGN KEY (`assetId`) REFERENCES `ram_assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_shotasset_shot` FOREIGN KEY (`shotId`) REFERENCES `ram_shots` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `ram_shots`
  ADD CONSTRAINT `fk_shots_sequence` FOREIGN KEY (`sequenceId`) REFERENCES `ram_sequences` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `ram_status`
  ADD CONSTRAINT `fk_status_asset` FOREIGN KEY (`assetId`) REFERENCES `ram_assets` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `fk_status_assigneduser` FOREIGN KEY (`assignedUserId`) REFERENCES `ram_users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_status_shot` FOREIGN KEY (`shotId`) REFERENCES `ram_shots` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_status_state` FOREIGN KEY (`stateId`) REFERENCES `ram_states` (`id`),
  ADD CONSTRAINT `fk_status_step` FOREIGN KEY (`stepId`) REFERENCES `ram_steps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_status_user` FOREIGN KEY (`userId`) REFERENCES `ram_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `ram_stepapplication`
  ADD CONSTRAINT `fk_stepapplication_app` FOREIGN KEY (`applicationId`) REFERENCES `ram_applications` (`id`),
  ADD CONSTRAINT `fk_stepapplication_step` FOREIGN KEY (`stepId`) REFERENCES `ram_steps` (`id`);

ALTER TABLE `ram_steps`
  ADD CONSTRAINT `fk_estimationGroupId` FOREIGN KEY (`estimationMultiplyGroupId`) REFERENCES `ram_assetgroups` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `fk_steps_projectId` FOREIGN KEY (`projectId`) REFERENCES `ram_projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
SET foreign_key_checks = 1;