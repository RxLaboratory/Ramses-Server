SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


DROP TABLE IF EXISTS `ram_applicationfiletype`;
CREATE TABLE `ram_applicationfiletype` (
  `id` int(11) NOT NULL,
  `applicationId` int(11) NOT NULL,
  `filetypeId` int(11) NOT NULL,
  `type` enum('native','export','import') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'native',
  `latestUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `removed` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `ram_applications`;
CREATE TABLE `ram_applications` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `executableFilePath` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `removed` tinyint(4) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `comment` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `ram_assetgroups`;
CREATE TABLE `ram_assetgroups` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `projectId` int(11) NOT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `removed` tinyint(4) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `comment` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `ram_assets`;
CREATE TABLE `ram_assets` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `tags` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `assetGroupId` int(11) NOT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `removed` tinyint(4) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `comment` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `ram_colorspaces`;
CREATE TABLE `ram_colorspaces` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `removed` tinyint(1) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `comment` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `ram_filetypes`;
CREATE TABLE `ram_filetypes` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `extensions` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `previewable` tinyint(4) NOT NULL DEFAULT 0,
  `latestUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `removed` tinyint(4) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `comment` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `ram_pipefile`;
CREATE TABLE `ram_pipefile` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `projectId` int(11) NOT NULL,
  `filetypeId` int(11) DEFAULT NULL,
  `colorSpaceId` int(11) DEFAULT NULL,
  `customSettings` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `removed` tinyint(4) NOT NULL DEFAULT 0,
  `comment` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `ram_pipefilepipe`;
CREATE TABLE `ram_pipefilepipe` (
  `id` int(11) NOT NULL,
  `pipeId` int(11) NOT NULL,
  `pipeFileId` int(11) NOT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `removed` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `ram_pipes`;
CREATE TABLE `ram_pipes` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `outputStepId` int(11) NOT NULL,
  `inputStepId` int(11) NOT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `removed` tinyint(1) NOT NULL DEFAULT 0,
  `comment` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `ram_projects`;
CREATE TABLE `ram_projects` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `framerate` float NOT NULL DEFAULT 24,
  `width` int(11) NOT NULL DEFAULT 1920,
  `height` int(11) NOT NULL DEFAULT 1080,
  `aspectRatio` float NOT NULL DEFAULT 1.78,
  `deadline` date DEFAULT NULL,
  `folderPath` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'auto',
  `latestUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `removed` tinyint(4) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `comment` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `ram_projectuser`;
CREATE TABLE `ram_projectuser` (
  `id` int(11) NOT NULL,
  `projectId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `removed` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `ram_schedule`;
CREATE TABLE `ram_schedule` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `userId` int(11) NOT NULL,
  `stepId` int(11) DEFAULT NULL,
  `date` datetime NOT NULL,
  `comment` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `removed` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `ram_sequences`;
CREATE TABLE `ram_sequences` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `projectId` int(11) NOT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `removed` tinyint(4) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `comment` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `color` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#434343'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `ram_servermetadata`;
CREATE TABLE `ram_servermetadata` (
  `id` int(10) UNSIGNED NOT NULL,
  `version` varchar(45) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `ram_shotasset`;
CREATE TABLE `ram_shotasset` (
  `id` int(11) NOT NULL,
  `shotId` int(11) NOT NULL,
  `assetId` int(11) NOT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `removed` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `ram_shots`;
CREATE TABLE `ram_shots` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `sequenceId` int(11) NOT NULL,
  `duration` float NOT NULL DEFAULT 0,
  `latestUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `removed` tinyint(4) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `comment` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `ram_states`;
CREATE TABLE `ram_states` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#434343',
  `latestUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `completionRatio` tinyint(1) NOT NULL DEFAULT 50,
  `removed` tinyint(4) NOT NULL DEFAULT 0,
  `comment` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `ram_status`;
CREATE TABLE `ram_status` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `completionRatio` tinyint(1) NOT NULL DEFAULT -1,
  `userId` int(11) NOT NULL,
  `stateId` int(11) NOT NULL,
  `comment` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `version` int(11) NOT NULL DEFAULT 1,
  `timeSpent` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `difficulty` enum('veryEasy','easy','medium','hard','veryHard') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'medium',
  `estimation` decimal(5,2) NOT NULL DEFAULT -1.00,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `assignedUserId` int(11) DEFAULT NULL,
  `stepId` int(11) NOT NULL,
  `assetId` int(11) DEFAULT NULL,
  `shotId` int(11) DEFAULT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `removed` tinyint(4) NOT NULL DEFAULT 0,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `ram_stepapplication`;
CREATE TABLE `ram_stepapplication` (
  `id` int(11) NOT NULL,
  `applicationId` int(11) NOT NULL,
  `stepId` int(11) NOT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `removed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `ram_steps`;
CREATE TABLE `ram_steps` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `autoCreateAssets` tinyint(1) NOT NULL DEFAULT 0,
  `type` enum('pre','asset','shot','post') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'asset',
  `color` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#e3e3e3',
  `estimationMethod` enum('shot','second') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'shot',
  `estimationVeryEasy` decimal(5,2) NOT NULL DEFAULT 0.20,
  `estimationEasy` decimal(5,2) NOT NULL DEFAULT 0.50,
  `estimationMedium` decimal(5,2) NOT NULL DEFAULT 1.00,
  `estimationHard` decimal(5,2) NOT NULL DEFAULT 2.00,
  `estimationVeryHard` decimal(5,2) NOT NULL DEFAULT 3.00,
  `estimationMultiplyGroupId` int(11) DEFAULT NULL,
  `projectId` int(11) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `latestUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `removed` tinyint(4) NOT NULL DEFAULT 0,
  `comment` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `ram_templateassetgroups`;
CREATE TABLE `ram_templateassetgroups` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `latestUpdate` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `removed` tinyint(4) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `comment` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `ram_templatesteps`;
CREATE TABLE `ram_templatesteps` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `shortName` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `autoCreateAssets` tinyint(1) NOT NULL DEFAULT 0,
  `latestUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `type` enum('pre','asset','shot','post') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'asset',
  `color` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#e3e3e3',
  `estimationMethod` enum('shot','second') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'shot',
  `estimationVeryEasy` decimal(5,2) NOT NULL DEFAULT 0.20,
  `estimationEasy` decimal(5,2) NOT NULL DEFAULT 0.50,
  `estimationMedium` decimal(5,2) NOT NULL DEFAULT 1.00,
  `estimationHard` decimal(5,2) NOT NULL DEFAULT 2.00,
  `estimationVeryHard` decimal(5,2) NOT NULL DEFAULT 3.00,
  `removed` tinyint(4) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `comment` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `ram_users`;
CREATE TABLE `ram_users` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `folderPath` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'auto',
  `role` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'standard',
  `color` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#e3e3e3',
  `removed` tinyint(4) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `comment` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `ram_applicationfiletype`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `apptype_unique` (`applicationId`,`filetypeId`,`type`),
  ADD KEY `fk_applicationfiletype_filetype` (`filetypeId`);

ALTER TABLE `ram_applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`);

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
  ADD UNIQUE KEY `uuid_unique` (`uuid`);

ALTER TABLE `ram_filetypes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`);

ALTER TABLE `ram_pipefile`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD KEY `fk_pipefile_colorspace` (`colorSpaceId`),
  ADD KEY `fk_ram_pipefile_filetype` (`filetypeId`),
  ADD KEY `fk_pipefile_project` (`projectId`);

ALTER TABLE `ram_pipefilepipe`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `association_UNIQUE` (`pipeId`,`pipeFileId`),
  ADD KEY `fk_pipefilepipe_pipe` (`pipeId`),
  ADD KEY `fk_pipefilepipe_pipefile` (`pipeFileId`);

ALTER TABLE `ram_pipes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD UNIQUE KEY `pipes_in_out_unique` (`outputStepId`,`inputStepId`) USING BTREE,
  ADD KEY `fk_pipes_input` (`inputStepId`),
  ADD KEY `fk_pipes_output` (`outputStepId`);

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

ALTER TABLE `ram_servermetadata`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ram_shotasset`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shot_asset_unique` (`shotId`,`assetId`) USING BTREE,
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
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

ALTER TABLE `ram_templatesteps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`);

ALTER TABLE `ram_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD UNIQUE KEY `shortName` (`shortName`);


ALTER TABLE `ram_applicationfiletype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_assetgroups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_assets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_colorspaces`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_filetypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_pipefile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_pipefilepipe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_pipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_projectuser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_sequences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_servermetadata`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_shotasset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_shots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_stepapplication`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_steps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_templateassetgroups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_templatesteps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ram_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `ram_applicationfiletype`
  ADD CONSTRAINT `fk_applicationfiletype_app` FOREIGN KEY (`applicationId`) REFERENCES `ram_applications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_applicationfiletype_filetype` FOREIGN KEY (`filetypeId`) REFERENCES `ram_filetypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `ram_assetgroups`
  ADD CONSTRAINT `fk_assetgroups_projectid` FOREIGN KEY (`projectId`) REFERENCES `ram_projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `ram_assets`
  ADD CONSTRAINT `fk_assets_assgroup` FOREIGN KEY (`assetGroupId`) REFERENCES `ram_assetgroups` (`id`);

ALTER TABLE `ram_pipefile`
  ADD CONSTRAINT `fk_pipefile_colorspace` FOREIGN KEY (`colorSpaceId`) REFERENCES `ram_colorspaces` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `fk_pipefile_filetype` FOREIGN KEY (`filetypeId`) REFERENCES `ram_filetypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pipefile_project` FOREIGN KEY (`projectId`) REFERENCES `ram_projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
