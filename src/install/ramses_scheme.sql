-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Mer 12 Juillet 2017 à 16:07
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `ram`
--

-- --------------------------------------------------------

--
-- Structure de la table `assets`
--

CREATE TABLE `ram_assets` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `stageId` int(11) NOT NULL,
  `statusId` int(11) DEFAULT NULL,
  `projectId` int(11) DEFAULT NULL,
  `comment` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `assetstatuses`
--

CREATE TABLE `ram_assetstatuses` (
  `id` int(11) NOT NULL,
  `assetId` int(11) NOT NULL,
  `shotId` int(11) NOT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `projects`
--

CREATE TABLE `ram_projects` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `projectshot`
--

CREATE TABLE `ram_projectshot` (
  `id` int(11) NOT NULL,
  `shotId` int(11) NOT NULL,
  `projectId` int(11) NOT NULL,
  `shotOrder` int(11) NOT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `projectstage`
--

CREATE TABLE `ram_projectstage` (
  `id` int(11) NOT NULL,
  `stageId` int(11) NOT NULL,
  `projectId` int(11) NOT NULL,
  `stageOrder` int(11) NOT NULL DEFAULT '0',
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `shots`
--

CREATE TABLE `ram_shots` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `duration` float NOT NULL DEFAULT '0',
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `stages`
--

CREATE TABLE `ram_stages` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `autoCreateAssets` tinyint(1) NOT NULL DEFAULT '0',
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `status`
--

CREATE TABLE `ram_status` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shortName` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `status`
--

INSERT INTO `ram_status` (`id`, `uuid`, `name`, `shortName`, `color`, `description`, `latestUpdate`) VALUES
(11, '5261f4cb-e40f-5595-9d42-284fbffdef3d', 'Stand by', 'STB', '#6d6d6d', '', '2017-07-11 15:07:35'),
(12, '6382ad1a-99a7-5fd4-bd31-1bc45a62cda1', 'Finished!', 'OK', '#00aa00', '', '2017-07-11 15:07:57'),
(13, '6ed72e24-2182-51b8-82b1-90a865241131', 'To do', 'TODO', '#00aaff', '', '2017-07-11 15:08:10');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `ram_users` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `firstName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `userName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `latestUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `users`
--

INSERT INTO `ram_users` (`id`, `uuid`, `firstName`, `lastName`, `userName`, `password`, `latestUpdate`) VALUES
(1, '2d7d7e01-671c-11e7-a78f-4ccc6a288527', '', '', 'Admin', 'e7b3f04140f24f2d9b2e04410e483c147c785df5f83bf5b9200adfe8c7811c271e3365e0dcf1e976bafbbd33a554ffc365b1760f9bc028aa918314c5fea9a767', '2017-07-12 16:07:06');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `assets`
--
ALTER TABLE `ram_assets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD UNIQUE KEY `assetName` (`name`,`shortName`,`projectId`),
  ADD KEY `statusId` (`statusId`),
  ADD KEY `stageId` (`stageId`),
  ADD KEY `projectId` (`projectId`);

--
-- Index pour la table `assetstatuses`
--
ALTER TABLE `ram_assetstatuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `assetId` (`assetId`,`shotId`) USING BTREE,
  ADD KEY `shotId` (`shotId`);

--
-- Index pour la table `projects`
--
ALTER TABLE `ram_projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`,`shortName`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Index pour la table `projectshot`
--
ALTER TABLE `ram_projectshot`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shotId` (`shotId`,`projectId`),
  ADD KEY `projectId` (`projectId`);

--
-- Index pour la table `projectstage`
--
ALTER TABLE `ram_projectstage`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `projectStage` (`stageId`,`projectId`),
  ADD KEY `projectId` (`projectId`);

--
-- Index pour la table `shots`
--
ALTER TABLE `ram_shots`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Index pour la table `stages`
--
ALTER TABLE `ram_stages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`,`shortName`),
  ADD UNIQUE KEY `name_2` (`name`,`shortName`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Index pour la table `status`
--
ALTER TABLE `ram_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`,`shortName`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Index pour la table `users`
--
ALTER TABLE `ram_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userName` (`userName`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `assets`
--
ALTER TABLE `ram_assets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `assetstatuses`
--
ALTER TABLE `ram_assetstatuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `projects`
--
ALTER TABLE `ram_projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `projectshot`
--
ALTER TABLE `ram_projectshot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `projectstage`
--
ALTER TABLE `ram_projectstage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `shots`
--
ALTER TABLE `ram_shots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `stages`
--
ALTER TABLE `ram_stages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `status`
--
ALTER TABLE `ram_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `ram_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `assets`
--
ALTER TABLE `ram_assets`
  ADD CONSTRAINT `assets_ibfk_1` FOREIGN KEY (`statusId`) REFERENCES `ram_status` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `assets_ibfk_2` FOREIGN KEY (`stageId`) REFERENCES `ram_stages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `assets_ibfk_3` FOREIGN KEY (`projectId`) REFERENCES `ram_projects` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `assetstatuses`
--
ALTER TABLE `ram_assetstatuses`
  ADD CONSTRAINT `assetstatuses_ibfk_2` FOREIGN KEY (`assetId`) REFERENCES `ram_assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `assetstatuses_ibfk_3` FOREIGN KEY (`shotId`) REFERENCES `ram_shots` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `projectshot`
--
ALTER TABLE `ram_projectshot`
  ADD CONSTRAINT `projectshot_ibfk_1` FOREIGN KEY (`shotId`) REFERENCES `ram_shots` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `projectshot_ibfk_2` FOREIGN KEY (`projectId`) REFERENCES `ram_projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `projectstage`
--
ALTER TABLE `ram_projectstage`
  ADD CONSTRAINT `projectstage_ibfk_1` FOREIGN KEY (`stageId`) REFERENCES `ram_stages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `projectstage_ibfk_2` FOREIGN KEY (`projectId`) REFERENCES `ram_projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
