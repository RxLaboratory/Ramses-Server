SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


INSERT INTO `ram_applicationfiletype` (`id`, `applicationId`, `filetypeId`, `type`, `latestUpdate`, `removed`) VALUES
(2, 1, 19, 'import', '2021-07-03 11:46:06', 0),
(3, 1, 1, 'import', '2021-07-03 11:46:10', 0),
(4, 1, 2, 'import', '2021-07-03 11:46:13', 0),
(5, 1, 20, 'import', '2021-07-03 11:46:16', 0),
(6, 1, 5, 'import', '2021-07-03 11:46:18', 0),
(7, 1, 6, 'import', '2021-07-03 11:46:29', 0),
(8, 1, 7, 'import', '2021-07-03 11:46:31', 0),
(9, 1, 36, 'import', '2021-07-03 11:46:32', 0),
(10, 1, 9, 'import', '2021-07-03 11:46:34', 0),
(11, 1, 13, 'import', '2021-07-03 11:46:36', 0),
(12, 1, 14, 'import', '2021-07-03 11:46:38', 0),
(13, 1, 12, 'import', '2021-07-03 11:46:39', 0),
(14, 1, 11, 'import', '2021-07-03 11:46:42', 0),
(15, 1, 10, 'import', '2021-07-03 11:46:43', 0),
(16, 1, 15, 'import', '2021-07-03 11:46:47', 0),
(17, 1, 16, 'import', '2021-07-03 11:46:49', 0),
(18, 1, 17, 'import', '2021-07-03 11:46:50', 0),
(19, 1, 4, 'import', '2021-07-03 11:46:54', 0),
(20, 1, 19, 'export', '2021-07-03 11:47:05', 0),
(21, 1, 1, 'export', '2021-07-03 11:47:08', 0),
(22, 1, 2, 'export', '2021-07-03 11:47:12', 0),
(23, 1, 20, 'export', '2021-07-03 11:47:14', 0),
(24, 1, 5, 'export', '2021-07-03 11:47:16', 0),
(26, 1, 27, 'export', '2021-07-03 11:47:25', 0),
(28, 1, 6, 'export', '2021-07-03 11:49:41', 0),
(29, 1, 8, 'export', '2021-07-03 11:49:43', 0),
(30, 1, 36, 'export', '2021-07-03 11:49:45', 0),
(31, 1, 9, 'export', '2021-07-03 11:49:47', 0),
(32, 1, 7, 'export', '2021-07-03 11:49:48', 0),
(33, 1, 10, 'export', '2021-07-03 11:49:50', 0),
(34, 1, 11, 'export', '2021-07-03 11:49:52', 0),
(35, 1, 13, 'export', '2021-07-03 11:49:54', 0),
(36, 1, 14, 'export', '2021-07-03 11:49:55', 0),
(37, 1, 12, 'export', '2021-07-03 11:49:57', 0),
(38, 1, 16, 'export', '2021-07-03 11:49:59', 0),
(39, 1, 17, 'export', '2021-07-03 11:50:01', 0),
(40, 1, 32, 'export', '2021-07-03 11:50:02', 0),
(41, 1, 46, 'export', '2021-07-03 11:50:09', 0),
(42, 1, 4, 'export', '2021-07-03 11:50:11', 0),
(43, 1, 18, 'import', '2021-07-03 11:50:19', 0),
(44, 1, 18, 'export', '2021-07-03 11:50:21', 0),
(45, 1, 8, 'import', '2021-07-03 11:50:25', 0),
(46, 2, 15, 'native', '2021-07-03 11:50:52', 0),
(47, 2, 19, 'import', '2021-07-03 11:51:00', 0),
(48, 2, 5, 'import', '2021-07-03 11:51:01', 0),
(49, 2, 3, 'import', '2021-07-03 11:51:05', 0),
(50, 2, 36, 'import', '2021-07-03 11:51:18', 0),
(51, 2, 7, 'import', '2021-07-03 11:51:19', 0),
(52, 2, 16, 'import', '2021-07-03 11:51:27', 0),
(53, 2, 17, 'import', '2021-07-03 11:51:29', 0),
(54, 2, 36, 'export', '2021-07-03 11:51:38', 0),
(55, 2, 7, 'export', '2021-07-03 11:51:46', 0),
(56, 2, 48, 'export', '2021-07-03 11:53:45', 0),
(57, 2, 47, 'export', '2021-07-03 11:53:47', 0),
(59, 3, 47, 'import', '2021-07-03 11:54:36', 0),
(60, 2, 47, 'import', '2021-07-03 11:54:42', 0),
(61, 2, 22, 'import', '2021-07-03 11:54:43', 0),
(62, 2, 48, 'import', '2021-07-03 11:54:47', 0),
(63, 3, 18, 'import', '2021-07-03 11:54:56', 0),
(64, 3, 17, 'import', '2021-07-03 11:54:58', 0),
(65, 3, 16, 'import', '2021-07-03 11:55:01', 0),
(66, 3, 15, 'import', '2021-07-03 11:55:04', 0),
(67, 3, 12, 'import', '2021-07-03 11:55:08', 0),
(68, 3, 14, 'import', '2021-07-03 11:55:10', 0),
(69, 3, 13, 'import', '2021-07-03 11:55:12', 0),
(70, 3, 11, 'import', '2021-07-03 11:55:14', 0),
(71, 3, 10, 'import', '2021-07-03 11:55:16', 0),
(72, 3, 9, 'import', '2021-07-03 11:55:19', 0),
(73, 3, 36, 'import', '2021-07-03 11:55:21', 0),
(74, 3, 8, 'import', '2021-07-03 11:55:23', 0),
(75, 3, 7, 'import', '2021-07-03 11:55:24', 0),
(76, 3, 6, 'import', '2021-07-03 11:55:26', 0),
(77, 3, 5, 'import', '2021-07-03 11:55:33', 0),
(78, 3, 20, 'import', '2021-07-03 11:55:36', 0),
(79, 3, 35, 'import', '2021-07-03 11:55:37', 0),
(80, 3, 2, 'import', '2021-07-03 11:55:40', 0),
(81, 3, 1, 'import', '2021-07-03 11:55:43', 0),
(82, 3, 19, 'import', '2021-07-03 11:55:45', 0),
(84, 3, 47, 'export', '2021-07-03 11:55:59', 0),
(86, 3, 18, 'export', '2021-07-03 11:56:07', 0),
(87, 3, 17, 'export', '2021-07-03 11:56:09', 0),
(88, 3, 16, 'export', '2021-07-03 11:56:11', 0),
(89, 3, 12, 'export', '2021-07-03 11:56:17', 0),
(90, 3, 13, 'export', '2021-07-03 11:56:19', 0),
(91, 3, 14, 'export', '2021-07-03 11:56:21', 0),
(93, 3, 11, 'export', '2021-07-03 11:56:27', 0),
(94, 3, 10, 'export', '2021-07-03 11:56:30', 0),
(95, 3, 9, 'export', '2021-07-03 11:56:34', 0),
(96, 3, 36, 'export', '2021-07-03 11:56:36', 0),
(97, 3, 8, 'export', '2021-07-03 11:56:37', 0),
(98, 3, 7, 'export', '2021-07-03 11:56:39', 0),
(99, 3, 6, 'export', '2021-07-03 11:56:44', 0),
(100, 3, 5, 'export', '2021-07-03 11:56:49', 0),
(101, 3, 20, 'export', '2021-07-03 11:56:54', 0),
(102, 3, 35, 'export', '2021-07-03 11:56:55', 0),
(103, 3, 2, 'export', '2021-07-03 11:56:56', 0),
(104, 3, 1, 'export', '2021-07-03 11:56:59', 0),
(105, 3, 19, 'export', '2021-07-03 11:57:01', 0),
(106, 4, 10, 'native', '2021-07-03 11:57:23', 0),
(107, 4, 11, 'native', '2021-07-03 11:57:25', 0),
(108, 4, 19, 'import', '2021-07-03 11:57:35', 0),
(110, 4, 1, 'import', '2021-07-03 11:57:41', 0),
(112, 4, 2, 'import', '2021-07-03 11:57:51', 0),
(113, 4, 35, 'import', '2021-07-03 11:57:53', 0),
(114, 4, 20, 'import', '2021-07-03 11:57:55', 0),
(115, 4, 5, 'import', '2021-07-03 11:57:57', 0),
(116, 4, 27, 'import', '2021-07-03 11:58:02', 0),
(117, 4, 28, 'import', '2021-07-03 11:58:04', 0),
(118, 4, 41, 'import', '2021-07-03 11:58:06', 0),
(119, 4, 6, 'import', '2021-07-03 11:58:07', 0),
(120, 4, 7, 'import', '2021-07-03 11:58:09', 0),
(121, 4, 8, 'import', '2021-07-03 11:58:10', 0),
(122, 4, 36, 'import', '2021-07-03 11:58:11', 0),
(123, 4, 9, 'import', '2021-07-03 11:58:13', 0),
(124, 4, 16, 'import', '2021-07-03 11:58:21', 0),
(125, 4, 17, 'import', '2021-07-03 11:58:23', 0),
(126, 4, 18, 'import', '2021-07-03 11:58:25', 0),
(127, 4, 47, 'import', '2021-07-03 11:58:28', 0),
(128, 4, 47, 'export', '2021-07-03 11:58:33', 0),
(129, 4, 31, 'export', '2021-07-03 11:58:37', 0),
(130, 4, 18, 'export', '2021-07-03 11:58:39', 0),
(131, 4, 17, 'export', '2021-07-03 11:58:41', 0),
(132, 4, 16, 'export', '2021-07-03 11:58:43', 0),
(133, 4, 9, 'export', '2021-07-03 11:58:50', 0),
(134, 4, 36, 'export', '2021-07-03 11:58:52', 0),
(135, 4, 8, 'export', '2021-07-03 11:58:54', 0),
(136, 4, 7, 'export', '2021-07-03 11:58:56', 0),
(137, 4, 6, 'export', '2021-07-03 11:58:58', 0),
(138, 4, 27, 'export', '2021-07-03 11:59:00', 0),
(139, 4, 28, 'export', '2021-07-03 11:59:02', 0),
(140, 4, 5, 'export', '2021-07-03 11:59:06', 0),
(141, 4, 2, 'export', '2021-07-03 11:59:13', 0),
(142, 4, 35, 'export', '2021-07-03 11:59:16', 0),
(143, 4, 1, 'export', '2021-07-03 11:59:19', 0),
(144, 4, 19, 'export', '2021-07-03 11:59:22', 0),
(145, 5, 21, 'native', '2021-07-03 11:59:36', 0),
(146, 5, 19, 'import', '2021-07-03 11:59:41', 0),
(147, 5, 5, 'import', '2021-07-03 11:59:48', 0),
(148, 5, 7, 'import', '2021-07-03 11:59:53', 0),
(149, 5, 36, 'import', '2021-07-03 11:59:55', 0),
(150, 5, 11, 'import', '2021-07-03 12:00:00', 0),
(151, 5, 10, 'import', '2021-07-03 12:00:02', 0),
(152, 5, 15, 'import', '2021-07-03 12:00:05', 0),
(153, 5, 16, 'import', '2021-07-03 12:00:10', 0),
(155, 5, 47, 'export', '2021-07-03 12:00:19', 0),
(156, 5, 47, 'import', '2021-07-03 12:00:22', 0),
(158, 5, 15, 'export', '2021-07-03 12:00:36', 0),
(160, 5, 36, 'export', '2021-07-03 12:00:43', 0),
(161, 5, 7, 'export', '2021-07-03 12:00:49', 0),
(162, 5, 5, 'export', '2021-07-03 12:00:51', 0),
(163, 5, 19, 'export', '2021-07-03 12:00:55', 0),
(164, 6, 23, 'native', '2021-07-03 12:01:12', 0),
(165, 6, 24, 'native', '2021-07-03 12:01:14', 0),
(166, 6, 21, 'import', '2021-07-03 12:01:18', 0),
(167, 6, 30, 'import', '2021-07-03 12:01:26', 0),
(168, 6, 19, 'import', '2021-07-03 12:01:30', 0),
(169, 6, 1, 'import', '2021-07-03 12:01:33', 0),
(171, 6, 33, 'import', '2021-07-03 12:01:42', 0),
(172, 6, 35, 'import', '2021-07-03 12:01:47', 0),
(173, 6, 5, 'import', '2021-07-03 12:01:50', 0),
(175, 6, 28, 'import', '2021-07-03 12:01:58', 0),
(176, 6, 27, 'import', '2021-07-03 12:02:00', 0),
(179, 6, 36, 'import', '2021-07-03 12:02:11', 0),
(180, 6, 25, 'import', '2021-07-03 12:02:15', 0),
(181, 6, 10, 'import', '2021-07-03 12:02:18', 0),
(182, 6, 11, 'import', '2021-07-03 12:02:19', 0),
(183, 6, 34, 'import', '2021-07-03 12:02:25', 0),
(184, 6, 16, 'import', '2021-07-03 12:02:27', 0),
(185, 6, 17, 'import', '2021-07-03 12:02:29', 0),
(186, 6, 31, 'import', '2021-07-03 12:02:33', 0),
(187, 6, 46, 'import', '2021-07-03 12:02:36', 0),
(188, 6, 46, 'export', '2021-07-03 12:02:47', 0),
(189, 6, 31, 'export', '2021-07-03 12:02:51', 0),
(190, 6, 32, 'export', '2021-07-03 12:02:53', 0),
(191, 6, 16, 'export', '2021-07-03 12:02:57', 0),
(192, 6, 11, 'export', '2021-07-03 12:03:05', 0),
(193, 6, 36, 'export', '2021-07-03 12:03:09', 0),
(194, 6, 27, 'export', '2021-07-03 12:03:13', 0),
(195, 6, 28, 'export', '2021-07-03 12:03:16', 0),
(196, 6, 5, 'export', '2021-07-03 12:03:23', 0),
(197, 6, 1, 'export', '2021-07-03 12:03:29', 0),
(198, 6, 19, 'export', '2021-07-03 12:03:33', 0),
(200, 7, 25, 'native', '2021-07-03 12:04:01', 0),
(201, 7, 46, 'import', '2021-07-03 12:04:06', 0),
(202, 7, 31, 'import', '2021-07-03 12:04:08', 0),
(203, 7, 32, 'import', '2021-07-03 12:04:10', 0),
(204, 7, 16, 'import', '2021-07-03 12:04:13', 0),
(205, 7, 26, 'import', '2021-07-03 12:04:18', 0),
(206, 7, 11, 'import', '2021-07-03 12:04:22', 0),
(207, 7, 10, 'import', '2021-07-03 12:04:25', 0),
(208, 7, 36, 'import', '2021-07-03 12:04:30', 0),
(209, 7, 27, 'import', '2021-07-03 12:04:34', 0),
(210, 7, 29, 'import', '2021-07-03 12:04:36', 0),
(211, 7, 28, 'import', '2021-07-03 12:04:38', 0),
(212, 7, 5, 'import', '2021-07-03 12:04:40', 0),
(213, 7, 1, 'import', '2021-07-03 12:04:47', 0),
(214, 7, 19, 'import', '2021-07-03 12:04:49', 0),
(215, 7, 30, 'import', '2021-07-03 12:04:52', 0),
(216, 7, 21, 'import', '2021-07-03 12:04:54', 0),
(217, 7, 30, 'export', '2021-07-03 12:05:02', 0),
(218, 7, 19, 'export', '2021-07-03 12:05:05', 0),
(219, 7, 1, 'export', '2021-07-03 12:05:07', 0),
(220, 7, 5, 'export', '2021-07-03 12:05:13', 0),
(221, 7, 29, 'export', '2021-07-03 12:05:16', 0),
(222, 7, 28, 'export', '2021-07-03 12:05:18', 0),
(223, 7, 27, 'export', '2021-07-03 12:05:19', 0),
(224, 7, 36, 'export', '2021-07-03 12:05:21', 0),
(225, 7, 16, 'export', '2021-07-03 12:05:29', 0),
(226, 7, 17, 'export', '2021-07-03 12:05:31', 0),
(227, 7, 32, 'export', '2021-07-03 12:05:32', 0),
(229, 7, 46, 'export', '2021-07-03 12:05:39', 0),
(230, 8, 37, 'native', '2021-07-03 12:06:08', 0),
(231, 8, 1, 'import', '2021-07-03 12:06:13', 0),
(233, 8, 42, 'import', '2021-07-03 12:06:19', 0),
(234, 8, 19, 'import', '2021-07-03 12:06:37', 0),
(235, 8, 45, 'import', '2021-07-03 12:06:40', 0),
(236, 8, 2, 'import', '2021-07-03 12:06:44', 0),
(237, 8, 35, 'import', '2021-07-03 12:06:46', 0),
(238, 8, 5, 'import', '2021-07-03 12:06:48', 0),
(239, 8, 3, 'import', '2021-07-03 12:06:50', 0),
(240, 8, 28, 'import', '2021-07-03 12:06:55', 0),
(241, 8, 27, 'import', '2021-07-03 12:06:57', 0),
(242, 8, 41, 'import', '2021-07-03 12:06:58', 0),
(243, 8, 36, 'import', '2021-07-03 12:07:00', 0),
(244, 8, 11, 'import', '2021-07-03 12:07:03', 0),
(245, 8, 10, 'import', '2021-07-03 12:07:05', 0),
(246, 8, 15, 'import', '2021-07-03 12:07:08', 0),
(247, 8, 16, 'import', '2021-07-03 12:07:10', 0),
(248, 8, 17, 'import', '2021-07-03 12:07:12', 0),
(249, 8, 32, 'import', '2021-07-03 12:07:13', 0),
(250, 8, 18, 'import', '2021-07-03 12:07:15', 0),
(251, 8, 46, 'import', '2021-07-03 12:07:18', 0),
(252, 8, 42, 'export', '2021-07-03 12:07:24', 0),
(253, 8, 30, 'export', '2021-07-03 12:07:29', 0),
(254, 8, 30, 'import', '2021-07-03 12:07:34', 0),
(255, 8, 19, 'export', '2021-07-03 12:07:38', 0),
(256, 8, 1, 'export', '2021-07-03 12:07:41', 0),
(257, 8, 45, 'export', '2021-07-03 12:07:42', 0),
(258, 8, 2, 'export', '2021-07-03 12:07:45', 0),
(259, 8, 5, 'export', '2021-07-03 12:07:49', 0),
(260, 8, 29, 'export', '2021-07-03 12:07:53', 0),
(261, 8, 28, 'export', '2021-07-03 12:07:55', 0),
(262, 8, 27, 'export', '2021-07-03 12:07:56', 0),
(263, 8, 41, 'export', '2021-07-03 12:07:58', 0),
(264, 8, 36, 'export', '2021-07-03 12:08:00', 0),
(265, 8, 16, 'export', '2021-07-03 12:08:08', 0),
(266, 8, 17, 'export', '2021-07-03 12:08:10', 0),
(267, 8, 32, 'export', '2021-07-03 12:08:11', 0),
(269, 8, 46, 'export', '2021-07-03 12:08:19', 0),
(272, 9, 42, 'import', '2021-07-03 12:09:05', 0),
(273, 9, 43, 'import', '2021-07-03 12:09:08', 0),
(275, 9, 19, 'import', '2021-07-03 12:09:15', 0),
(276, 9, 1, 'import', '2021-07-03 12:09:17', 0),
(279, 9, 45, 'import', '2021-07-03 12:09:26', 0),
(280, 9, 35, 'import', '2021-07-03 12:09:32', 0),
(281, 9, 5, 'import', '2021-07-03 12:09:36', 0),
(282, 9, 28, 'import', '2021-07-03 12:09:39', 0),
(283, 9, 27, 'import', '2021-07-03 12:09:40', 0),
(284, 9, 41, 'import', '2021-07-03 12:09:42', 0),
(285, 9, 36, 'import', '2021-07-03 12:09:45', 0),
(286, 9, 16, 'import', '2021-07-03 12:09:53', 0),
(287, 9, 17, 'import', '2021-07-03 12:09:55', 0),
(288, 9, 42, 'export', '2021-07-03 12:10:06', 0),
(289, 9, 43, 'export', '2021-07-03 12:10:10', 0),
(290, 9, 19, 'export', '2021-07-03 12:10:13', 0),
(291, 9, 1, 'export', '2021-07-03 12:10:15', 0),
(292, 9, 45, 'export', '2021-07-03 12:10:17', 0),
(293, 9, 5, 'export', '2021-07-03 12:10:22', 0),
(294, 9, 41, 'export', '2021-07-03 12:10:25', 0),
(295, 9, 36, 'export', '2021-07-03 12:10:27', 0),
(296, 9, 16, 'export', '2021-07-03 12:10:32', 0),
(297, 9, 17, 'export', '2021-07-03 12:10:36', 0),
(298, 10, 40, 'native', '2021-07-03 12:10:54', 0),
(299, 10, 42, 'import', '2021-07-03 12:10:58', 0),
(300, 10, 43, 'import', '2021-07-03 12:11:00', 0),
(303, 10, 30, 'import', '2021-07-03 12:11:09', 0),
(304, 10, 19, 'import', '2021-07-03 12:11:12', 0),
(305, 10, 1, 'import', '2021-07-03 12:11:15', 0),
(306, 10, 45, 'import', '2021-07-03 12:11:16', 0),
(308, 10, 28, 'import', '2021-07-03 12:11:20', 0),
(309, 10, 35, 'import', '2021-07-03 12:11:24', 0),
(310, 10, 5, 'import', '2021-07-03 12:11:27', 0),
(311, 10, 27, 'import', '2021-07-03 12:11:31', 0),
(312, 10, 41, 'import', '2021-07-03 12:11:33', 0),
(313, 10, 36, 'import', '2021-07-03 12:11:34', 0),
(314, 10, 16, 'import', '2021-07-03 12:11:39', 0),
(315, 10, 17, 'import', '2021-07-03 12:11:41', 0),
(316, 10, 42, 'export', '2021-07-03 12:11:48', 0),
(317, 10, 43, 'export', '2021-07-03 12:11:50', 0),
(318, 10, 30, 'export', '2021-07-03 12:11:52', 0),
(319, 10, 19, 'export', '2021-07-03 12:11:55', 0),
(320, 10, 1, 'export', '2021-07-03 12:11:57', 0),
(321, 10, 45, 'export', '2021-07-03 12:11:59', 0),
(322, 10, 5, 'export', '2021-07-03 12:12:02', 0),
(323, 10, 28, 'export', '2021-07-03 12:12:06', 0),
(324, 10, 27, 'export', '2021-07-03 12:12:08', 0),
(325, 10, 41, 'export', '2021-07-03 12:12:09', 0),
(326, 10, 36, 'export', '2021-07-03 12:12:11', 0),
(327, 10, 16, 'export', '2021-07-03 12:12:17', 0),
(328, 10, 17, 'export', '2021-07-03 12:12:20', 0),
(329, 11, 44, 'native', '2021-07-03 12:12:40', 0),
(330, 11, 42, 'import', '2021-07-03 12:12:43', 0),
(332, 11, 30, 'import', '2021-07-03 12:12:50', 0),
(333, 11, 19, 'import', '2021-07-03 12:12:54', 0),
(334, 11, 1, 'import', '2021-07-03 12:12:57', 0),
(335, 11, 45, 'import', '2021-07-03 12:12:58', 0),
(336, 11, 35, 'import', '2021-07-03 12:13:01', 0),
(337, 11, 5, 'import', '2021-07-03 12:13:04', 0),
(338, 11, 28, 'import', '2021-07-03 12:13:07', 0),
(339, 11, 27, 'import', '2021-07-03 12:13:08', 0),
(340, 11, 41, 'import', '2021-07-03 12:13:10', 0),
(341, 11, 36, 'import', '2021-07-03 12:13:11', 0),
(342, 11, 16, 'import', '2021-07-03 12:13:17', 0),
(343, 11, 17, 'import', '2021-07-03 12:13:19', 0),
(344, 11, 42, 'export', '2021-07-03 12:13:27', 0),
(345, 11, 30, 'export', '2021-07-03 12:13:33', 0),
(346, 11, 19, 'export', '2021-07-03 12:13:35', 0),
(347, 11, 1, 'export', '2021-07-03 12:13:37', 0),
(348, 11, 45, 'export', '2021-07-03 12:13:39', 0),
(349, 11, 5, 'export', '2021-07-03 12:13:42', 0),
(350, 11, 28, 'export', '2021-07-03 12:13:45', 0),
(351, 11, 27, 'export', '2021-07-03 12:13:46', 0),
(352, 11, 41, 'export', '2021-07-03 12:13:48', 0),
(353, 11, 36, 'export', '2021-07-03 12:13:49', 0),
(354, 11, 16, 'export', '2021-07-03 12:13:53', 0),
(355, 11, 17, 'export', '2021-07-03 12:13:55', 0),
(356, 12, 49, 'native', '2021-07-03 12:15:23', 0),
(357, 12, 41, 'import', '2021-07-03 12:15:52', 0),
(358, 12, 36, 'import', '2021-07-03 12:15:55', 0),
(359, 12, 42, 'import', '2021-07-03 12:17:38', 0),
(360, 12, 45, 'import', '2021-07-03 12:17:41', 0),
(361, 12, 17, 'import', '2021-07-03 12:21:21', 0),
(362, 12, 16, 'import', '2021-07-03 12:21:24', 0),
(363, 12, 11, 'import', '2021-07-03 12:21:29', 0),
(364, 12, 10, 'import', '2021-07-03 12:21:31', 0),
(365, 12, 5, 'import', '2021-07-03 12:21:35', 0),
(366, 12, 1, 'import', '2021-07-03 12:21:41', 0),
(367, 12, 1, 'export', '2021-07-03 12:21:45', 0),
(368, 12, 36, 'export', '2021-07-03 12:21:48', 0),
(369, 12, 5, 'export', '2021-07-03 12:21:50', 0),
(370, 12, 11, 'export', '2021-07-03 12:21:51', 0),
(371, 12, 10, 'export', '2021-07-03 12:21:53', 0),
(372, 12, 16, 'export', '2021-07-03 12:21:56', 0),
(373, 12, 17, 'export', '2021-07-03 12:21:58', 0),
(374, 15, 50, 'native', '2021-07-03 12:25:04', 0),
(375, 15, 19, 'import', '2021-07-03 12:25:11', 0),
(376, 15, 1, 'import', '2021-07-03 12:25:13', 0),
(377, 15, 5, 'import', '2021-07-03 12:25:18', 0),
(378, 15, 35, 'import', '2021-07-03 12:25:19', 0),
(379, 15, 28, 'import', '2021-07-03 12:25:23', 0),
(380, 15, 27, 'import', '2021-07-03 12:25:24', 0),
(381, 15, 36, 'import', '2021-07-03 12:25:28', 0),
(382, 15, 16, 'import', '2021-07-03 12:25:32', 0),
(383, 15, 17, 'import', '2021-07-03 12:25:35', 0),
(384, 15, 46, 'import', '2021-07-03 12:25:37', 0),
(385, 15, 19, 'export', '2021-07-03 12:25:45', 0),
(386, 15, 1, 'export', '2021-07-03 12:25:47', 0),
(387, 15, 5, 'export', '2021-07-03 12:25:50', 0),
(388, 15, 36, 'export', '2021-07-03 12:25:52', 0),
(389, 15, 16, 'export', '2021-07-03 12:25:54', 0),
(390, 15, 17, 'export', '2021-07-03 12:25:56', 0),
(391, 15, 28, 'export', '2021-07-03 12:25:59', 0),
(392, 15, 27, 'export', '2021-07-03 12:26:01', 0),
(393, 15, 30, 'export', '2021-07-03 12:26:03', 0),
(394, 15, 30, 'import', '2021-07-03 12:26:09', 0),
(408, 7, 54, 'export', '2021-07-03 12:29:05', 0),
(409, 7, 53, 'export', '2021-07-03 12:29:07', 0),
(410, 7, 26, 'export', '2021-07-03 12:29:29', 0),
(411, 7, 53, 'import', '2021-07-03 12:29:37', 0),
(412, 7, 54, 'import', '2021-07-03 12:29:40', 0),
(413, 7, 51, 'import', '2021-07-03 12:29:42', 0),
(414, 7, 52, 'import', '2021-07-03 12:29:44', 0),
(415, 7, 52, 'export', '2021-07-03 12:29:47', 0),
(416, 7, 51, 'export', '2021-07-03 12:29:49', 0),
(417, 16, 54, 'import', '2021-07-03 12:30:12', 0),
(418, 16, 53, 'import', '2021-07-03 12:30:15', 0),
(419, 16, 52, 'import', '2021-07-03 12:30:31', 0),
(420, 16, 51, 'import', '2021-07-03 12:30:34', 0),
(421, 16, 31, 'import', '2021-07-03 12:30:39', 0),
(422, 16, 32, 'import', '2021-07-03 12:30:41', 0),
(423, 16, 17, 'import', '2021-07-03 12:30:43', 0),
(424, 16, 16, 'import', '2021-07-03 12:30:45', 0),
(426, 16, 36, 'import', '2021-07-03 12:30:54', 0),
(427, 16, 27, 'import', '2021-07-03 12:30:57', 0),
(428, 16, 28, 'import', '2021-07-03 12:30:59', 0),
(429, 16, 29, 'import', '2021-07-03 12:31:01', 0),
(430, 16, 5, 'import', '2021-07-03 12:31:03', 0),
(431, 16, 1, 'import', '2021-07-03 12:31:08', 0),
(432, 16, 30, 'import', '2021-07-03 12:31:12', 0),
(433, 16, 54, 'export', '2021-07-03 12:31:16', 0),
(434, 16, 53, 'export', '2021-07-03 12:31:17', 0),
(435, 16, 52, 'export', '2021-07-03 12:31:19', 0),
(436, 16, 16, 'export', '2021-07-03 12:31:27', 0),
(437, 16, 36, 'export', '2021-07-03 12:31:33', 0),
(438, 16, 27, 'export', '2021-07-03 12:31:36', 0),
(439, 16, 28, 'export', '2021-07-03 12:31:38', 0),
(440, 16, 5, 'export', '2021-07-03 12:31:42', 0),
(441, 16, 19, 'export', '2021-07-03 12:31:46', 0),
(442, 16, 55, 'export', '2021-07-03 12:31:52', 0),
(443, 16, 55, 'import', '2021-07-03 12:32:11', 0),
(444, 15, 55, 'import', '2021-07-03 12:32:17', 0),
(445, 11, 55, 'import', '2021-07-03 12:32:22', 0),
(446, 10, 55, 'import', '2021-07-03 12:32:25', 0),
(447, 9, 55, 'import', '2021-07-03 12:32:28', 0),
(448, 8, 55, 'import', '2021-07-03 12:32:31', 0),
(449, 7, 55, 'import', '2021-07-03 12:32:37', 0),
(450, 6, 55, 'import', '2021-07-03 12:32:40', 0),
(451, 9, 38, 'native', '2021-10-20 13:54:18', 0),
(452, 9, 39, 'native', '2021-10-20 13:54:22', 0),
(453, 1, 3, 'native', '2021-10-20 13:54:31', 0);

INSERT INTO `ram_applications` (`id`, `uuid`, `name`, `shortName`, `executableFilePath`, `latestUpdate`, `removed`, `order`, `comment`) VALUES
(1, 'a5ef7a35-3901-55bf-83a3-dab447d2514d', 'Krita', 'KRITA', '', '2021-07-03 11:45:39', 0, 0, ''),
(2, '7b8d2675-4704-53eb-8524-2181bd1c2e31', 'Inkscape', 'INKSCP', '', '2021-07-03 11:50:47', 0, 0, ''),
(3, 'e6c46120-8e2e-51e4-b74f-ce02cfc20b0b', 'The Gimp', 'GIMP', '', '2021-07-03 11:54:21', 0, 0, ''),
(4, 'b105f8b6-7122-5104-a5af-4e7b36c7dcd4', 'Adobe Photoshop', 'PS', '', '2021-07-03 11:57:18', 0, 0, ''),
(5, 'b042dcbf-9e21-586b-bd39-555e9932670b', 'Adobe Illustrator', 'AI', '', '2021-07-03 11:59:33', 0, 0, ''),
(6, '758e67c7-8a8a-50e0-991c-c76ad2c9f428', 'Adobe After Effects', 'AE', '', '2021-07-03 12:01:08', 0, 0, ''),
(7, '52d00135-8c10-5325-94ce-516bf86d87ad', 'Adobe Premiere Pro', 'PR', '', '2021-07-03 12:32:36', 0, 0, ''),
(8, 'ee382eac-8220-5ce2-aff9-76a99fc8bba0', 'Blender', 'BLEND', '', '2021-07-03 12:06:01', 0, 0, ''),
(9, '0e5d6aee-fae9-5c85-8c5c-d7546aec611f', 'Autodesk Maya', 'MAYA', '', '2021-07-03 12:08:34', 0, 0, ''),
(10, '8d0dd63a-c9ee-56f4-a8a7-2eba19d0e660', 'Autodesk 3DS Max', '3DS', '', '2021-10-20 13:55:09', 0, 0, ''),
(11, '2ce359cc-666c-5d49-8074-203884f80b2b', 'Maxon Cinema4D', 'C4D', '', '2021-07-03 12:12:33', 0, 0, ''),
(12, 'e89d029e-4831-512a-874f-449f22b3b47d', 'The Foundry Mari', 'MARI', '', '2021-07-03 12:14:11', 0, 0, ''),
(13, '48c5e50c-6f0a-5403-9844-7883f499fcdc', 'Adobe Substance Designer', 'SBSTDSGN', '', '2021-07-03 12:22:21', 0, 0, ''),
(14, '2dd8a108-fc08-5d24-9de4-4b95ce228809', 'Adobe Substance Painter', 'SBSTPNTR', '', '2021-07-03 12:23:57', 0, 0, ''),
(15, '140f6f9c-1f2c-5989-a2b4-867bd4460123', 'The Foundry Nule', 'NUKE', '', '2021-07-03 12:24:26', 0, 0, ''),
(16, '8e3f3080-5b37-57c1-8090-60688611fbe2', 'Lightworks', 'LWKS', '', '2021-07-03 12:26:22', 0, 0, ''),
(17, '234246cb-a8c0-525e-a9a8-e8ba7399a606', 'SideFX Houdini', 'HOUDINI', '', '2021-10-20 13:55:05', 0, 0, '');

INSERT INTO `ram_filetypes` (`id`, `uuid`, `name`, `shortName`, `extensions`, `previewable`, `latestUpdate`, `removed`, `order`, `comment`) VALUES
(1, '3a03d734-50f5-51c9-93a4-d9e8470fc24b', 'OpenEXR Image Data', 'exr', 'exr', 0, '2021-07-03 11:20:09', 0, 0, ''),
(2, 'cbc5367c-c929-5c3b-8dc5-98bcde8db40c', 'GIF Image or animation', 'GIF', 'gif', 1, '2021-07-03 11:20:41', 0, 0, ''),
(3, '8a92ecf4-acf4-5732-b27b-12c599333c2d', 'Krita Document', 'kra', 'kra', 0, '2021-07-03 11:20:57', 0, 0, ''),
(4, 'f414c644-a025-5b3c-9f45-9987c2f19ec8', 'The Gimp Document', 'xcf', 'xcf', 0, '2021-07-03 11:21:55', 0, 0, ''),
(5, '76bc81a4-7a0f-512d-9140-fefc5d394955', 'JPEG Image', 'jpg', 'jpg,jpeg,jpe', 1, '2021-07-03 11:29:42', 0, 0, ''),
(6, '63c3c32a-9a4f-5b94-879a-5fa14cc8eba2', 'PBM Image', 'pbm', 'pbm', 0, '2021-07-03 11:27:37', 0, 0, ''),
(7, '1777f4e9-7715-50c8-bd60-01670fa6d5d1', 'PDF Document', 'pdf', 'pdf', 1, '2021-07-03 11:29:40', 0, 0, ''),
(8, '924df830-29de-5b72-a4ca-6b5d328f9c3a', 'PGM Image', 'pgm', 'pgm', 0, '2021-07-03 11:28:15', 0, 0, ''),
(9, 'd89f1a98-c8b1-5994-8b29-d6752b2ff1a4', 'PPM Image', 'ppm', 'ppm', 0, '2021-07-03 11:28:40', 0, 0, ''),
(10, 'ebf0f2ee-f080-56a1-bbaf-e639bffe4643', 'Photoshop Large Document', 'psb', 'psb', 0, '2021-07-03 11:29:01', 0, 0, ''),
(11, '21540a3a-240e-55e4-a438-0a4522cf9908', 'Photoshop Document', 'psd', 'psd', 0, '2021-07-03 11:29:11', 0, 0, ''),
(12, '5b8ecdf6-3aeb-5ab1-b9cf-89f855926497', 'R8 Heightmap', 'r8', 'r8', 0, '2021-07-03 11:29:37', 0, 0, ''),
(13, 'ef857dfc-4711-56bf-ac97-a077f3c77f56', 'R16 Heightmap', 'r16', 'r16', 0, '2021-07-03 11:29:57', 0, 0, ''),
(14, 'ca627fd0-80f3-55d4-aa96-19b7ccddc9da', 'R32 Heightmap', 'r32', 'r32', 0, '2021-07-03 11:30:06', 0, 0, ''),
(15, '9cac4765-5012-5090-8217-50a528cf8d49', 'SVG Vector Image', 'svg', 'svg', 1, '2021-07-03 11:30:22', 0, 0, ''),
(16, 'd502f23f-b522-5844-ab16-5da3993b4bad', 'TGA Image', 'tga', 'tga,icb,tpic,vda,vst', 1, '2021-07-03 11:31:39', 0, 0, ''),
(17, 'dd3a15c6-ea5d-5866-9108-007abccfb125', 'TIFF Image', 'tif', 'tif,tiff', 1, '2021-07-03 11:31:37', 0, 0, ''),
(18, 'a851a4f2-2b19-52bf-86f8-ac5678b6d6dd', 'WebP Image', 'webp', 'webp', 1, '2021-07-03 11:31:40', 0, 0, ''),
(19, '4a9332c2-8e7e-5bde-be5b-0d917c425e18', 'BMP Windows Image', 'bmp', 'bmp', 1, '2021-07-03 11:32:04', 0, 0, ''),
(20, '1e882367-9e1a-5be6-9c2e-5cb0fc9715ab', 'Windows Icon', 'ico', 'ico', 1, '2021-07-03 11:32:13', 0, 0, ''),
(21, 'f3d25460-b8d5-52e7-a99c-552ad1c6b455', 'Illustrator Document', 'ai', 'ai', 0, '2021-07-03 11:32:40', 0, 0, ''),
(22, '70fe757a-e583-553c-9fb5-48ebae38e11f', 'InDesign Document', 'indd', 'indd,idml', 0, '2021-07-03 11:33:01', 0, 0, ''),
(23, 'b205e1f7-bd5f-5dec-a403-45dc6bf6d5ae', 'After Effects Project', 'aep', 'aep,aet,aepx', 0, '2021-07-03 11:33:41', 0, 0, ''),
(24, '56f12a2f-52b3-500c-87cf-c5c8da670c8e', 'After Effects Template', 'aet', 'aet,aetx', 0, '2021-07-03 11:33:45', 0, 0, ''),
(25, '2edc3e9d-f58c-5252-90d3-8629bb39dc70', 'Premiere Project', 'prproj', 'prproj', 0, '2021-07-03 11:34:00', 0, 0, ''),
(26, '1382512f-2cb1-5583-b4e9-ca9556d989ac', 'Audition Session', 'sesx', 'sesx,ses', 0, '2021-07-03 11:34:49', 0, 0, ''),
(27, 'ee87926f-e47a-536e-ba6f-59aed078df7b', 'MP4 Video', 'mp4', 'mp4', 1, '2021-07-03 11:35:59', 0, 0, ''),
(28, '4ab0512a-afc1-536e-8fdc-ec6ba50f413f', 'Quicktime Movie', 'mov', 'mov', 1, '2021-07-03 11:35:57', 0, 0, ''),
(29, '6103e83e-db01-5e8a-904f-6e007a942c75', 'Matroska Video', 'mkv', 'mkv', 1, '2021-07-03 11:35:55', 0, 0, ''),
(30, '199d91d2-15d3-599f-8d7d-7da2a21d5a41', 'Audio Video Interleave', 'avi', 'avi', 1, '2021-07-03 11:37:08', 0, 0, ''),
(31, 'f66c7ee4-bb99-558e-9743-e1b3d77120cb', 'Windows Video', 'wmv', 'wmv', 1, '2021-07-03 11:37:13', 0, 0, ''),
(32, '725f19ce-450f-5dd5-b4c1-e7647604b20a', 'WebM Movie', 'webm', 'webm', 1, '2021-07-03 11:37:06', 0, 0, ''),
(33, 'd73dd034-8a0b-53bf-93de-804cbac3874d', 'Animate Flash Document', 'fla', 'fla', 0, '2021-07-03 11:37:45', 0, 0, ''),
(34, 'db7f7f21-9a56-5b5a-b5f4-04bb71a12b99', 'Animate Flash Movie', 'swf', 'swf', 0, '2021-07-03 11:37:57', 0, 0, ''),
(35, '33beedee-f778-5e7e-a87e-23852152af0c', 'High Dynamic Range Image', 'hdr', 'hdr', 0, '2021-07-03 11:39:08', 0, 0, ''),
(36, '851b18ee-e904-538e-8f76-4347c9fd9cb6', 'PNG Image', 'png', 'png', 1, '2021-07-03 11:39:26', 0, 0, ''),
(37, '15f9e6e7-dc85-5471-9103-8df8ff96e2d4', 'Blender Scene', 'blend', 'blend,blend1,blend2,blend3,blend4,blend5,blend6,blend7,blend8,blend9', 0, '2021-07-03 11:40:07', 0, 0, ''),
(38, 'cbeb92f2-6cbd-5e9a-8b52-e90acdea53bf', 'Maya Scene (ASCII)', 'ma', 'ma', 0, '2021-07-03 11:40:24', 0, 0, ''),
(39, '6ef83ec3-f9ee-5253-a5bd-6ac6cd8f5ecf', 'Maya Scene (Binary)', 'mb', 'mb', 0, '2021-07-03 12:08:57', 0, 0, ''),
(40, 'cfd34c59-3a59-5bd1-804a-299b7177e173', '3DS Max Scene', 'max', 'max,3ds', 0, '2021-07-03 11:45:24', 0, 0, ''),
(41, 'd134878d-8d5e-5e54-aa62-2d14ba56cbcb', '3D Object', 'obj', 'obj', 0, '2021-07-03 11:41:02', 0, 0, ''),
(42, '1377453a-5bf7-560e-9005-1fc593675f21', 'Alembic Geometry Data', 'abc', 'abc', 0, '2021-07-03 12:06:27', 0, 0, ''),
(43, '58ac0002-ccbc-51ab-a9cf-f965708a76a9', 'Arnold Scene Source', 'ass', 'ass', 0, '2021-07-03 11:44:58', 0, 0, 'Arnold Scene Source is Arnold\'s native scene definition format, stored in human-readable, editable ASCII files.'),
(44, 'b03050fa-f8f9-5a75-873d-0f80bf0efce7', 'Cinema4D Scene', 'c4d', 'c4d', 0, '2021-07-03 11:42:36', 0, 0, ''),
(45, '9ff954f5-af95-59d1-8733-63d86316099c', 'FBX Adaptable file format', 'fbx', 'fbx', 0, '2021-07-03 11:43:33', 0, 0, 'FBX® data exchange technology is a 3D asset exchange format that facilitates higher-fidelity data exchange between 3ds Max, Maya, MotionBuilder, Mudbox and other propriety and third-party software.'),
(46, '6aa0a873-c2d2-5c85-8002-8102652e2331', 'OGG video', 'ogv', 'ogv,ogg', 1, '2021-07-03 11:49:12', 0, 0, ''),
(47, 'e417cdfe-8b11-54e3-b440-bf6e2556a15a', 'PostScript', 'eps', 'eps', 0, '2021-07-03 11:52:56', 0, 0, ''),
(48, 'cfb73b8f-77a2-5a9f-8fa8-051760960acf', 'Synfig Animation', 'sif', 'sif', 0, '2021-07-03 11:53:38', 0, 0, ''),
(49, '89d8929e-1604-5a06-9b8f-c959100a5c82', 'Mari Project', 'ptx', 'ptx', 0, '2021-07-03 12:15:19', 0, 0, ''),
(50, 'a986e5de-2bf2-57b3-8a47-fdd5d146d14d', 'Nuke Scripts', 'nk', 'nk,nuke,nkple', 0, '2021-07-03 12:24:48', 0, 0, ''),
(51, '320e579f-4b7d-5d25-9c98-6914e73487e3', 'Final Cut XML', 'xml', 'xml', 0, '2021-07-03 12:27:14', 0, 0, ''),
(52, '68743f25-79b9-5816-a449-df4dad0c4ad7', 'Edit Decision List File', 'edl', 'edl', 0, '2021-07-03 12:28:01', 0, 0, ''),
(53, 'a3b17399-6a4b-5a85-be17-ba69bc7e1b40', 'Advanced Authoring Format', 'aaf', 'aaf', 0, '2021-07-03 12:30:21', 0, 0, ''),
(54, '3b7be413-5940-55f2-9ff5-597418225d44', 'Open Media Framework', 'omf', 'omf', 0, '2021-07-03 12:28:48', 0, 0, ''),
(55, '6a6e19e0-f6ac-5227-b42f-f12f3aec470f', 'Waveform', 'wav', 'wav', 1, '2021-07-03 12:32:03', 0, 0, '');

INSERT INTO `ram_servermetadata` (`id`, `version`, `date`) VALUES
(1, '0.2.4-alpha', '2021-11-27 10:44:59');

INSERT INTO `ram_states` (`id`, `uuid`, `name`, `shortName`, `color`, `latestUpdate`, `completionRatio`, `removed`, `comment`) VALUES
(1, 'f1b78562-0964-5e04-adcc-5f9f7b61d155', 'Nothing to do', 'NO', '#484848', '2021-07-03 11:22:55', 0, 0, 'There\'s nothing to do at this step.'),
(2, '2fa28213-b2ce-5227-92ea-604ea4a72e7b', 'Ready to do', 'TODO', '#00aaff', '2021-07-03 11:22:59', 0, 0, 'This step is ready, one can work on this.'),
(3, 'ff23c06c-fd07-572b-aca8-d6285fed18c1', 'Stand by', 'STB', '#939393', '2021-07-03 11:23:04', 0, 0, 'Something is missing, let\'s wait a bit.'),
(4, 'e3482f5d-a43f-5efc-bd8a-f487ef93a3d2', 'Work in progress', 'WIP', '#ffff7f', '2021-07-03 11:26:10', 50, 0, 'This currently in production.'),
(5, '22430eb8-c774-5e92-9762-a07a562bd617', 'Question', 'QST', '#b855ff', '2021-07-03 11:23:23', 20, 0, 'More information is needed to continue working on this.'),
(6, '224195f3-63c7-538f-8057-b7367ad4fd3e', 'Waiting for approval', 'CHK', '#ff8741', '2021-07-03 11:23:27', 80, 0, 'This is ready to be reviewed.'),
(7, '7fe3e133-dab6-5952-91dc-e7f906ee970d', 'Could be better', 'CBB', '#9aff8b', '2021-07-03 11:23:30', 95, 0, 'This works like that, but if possible it can still be improved.'),
(8, '35446d2e-0173-5872-993d-55f92cbdc2b7', 'Finished', 'OK', '#00ce00', '2021-07-03 11:23:35', 100, 0, 'This has been reviewed and validated.'),
(9, '2f758137-549f-52cd-b995-6a88ec52c453', 'Needs a retake', 'RTK', '#ff1930', '2021-07-03 11:23:39', 70, 0, 'Something has to be fixed.'),
(10, '33d3fdaa-e232-5b10-9d4b-132a4425335c', 'Rendering', 'RDR', '#6a39ff', '2021-07-03 11:23:42', 90, 0, 'This is currently rendering, soon we\'ll be able to watch this beautiful work!');

INSERT INTO `ram_templateassetgroups` (`id`, `uuid`, `name`, `shortName`, `latestUpdate`, `removed`, `order`, `comment`) VALUES
(1, '4f1ab268-3535-564a-9922-a359fcd4d143', 'Main Characters', 'CHAR', '2021-07-03 11:01:36', 0, 0, ''),
(2, 'a5e1d117-6a22-54ef-a569-5f805b327062', 'Secondary Characters', 'CHAR2', '2021-07-03 11:01:46', 0, 0, ''),
(3, '4a359f49-ccfe-5dc0-b1eb-5d403b366958', 'Props', 'PROP', '2021-07-03 11:04:45', 0, 0, 'Playing / Animated props'),
(4, '138f3d32-f718-5f45-8485-8e700d965e31', 'Sets', 'SETS', '2021-07-03 11:05:29', 0, 0, '3D Sets'),
(5, 'c81852a5-13f3-5e61-bf18-9bb341c199bd', 'Backgrounds', 'BGS', '2021-07-03 11:05:42', 0, 0, '2D Environments'),
(6, '26a63e3e-11aa-5812-9161-2f15a7841527', 'Paintings', 'PAINT', '2021-07-03 11:05:19', 0, 0, 'Matte paintings and other backgrounds'),
(7, '34d813d1-95fd-5c85-8a4f-142adf47b0c2', 'Accessories', 'ACCSRS', '2021-07-03 11:04:57', 0, 0, 'Non-playing, not animated props');

INSERT INTO `ram_templatesteps` (`id`, `uuid`, `name`, `shortName`, `autoCreateAssets`, `latestUpdate`, `type`, `color`, `estimationMethod`, `estimationVeryEasy`, `estimationEasy`, `estimationMedium`, `estimationHard`, `estimationVeryHard`, `removed`, `order`, `comment`) VALUES
(1, 'f96be95e-3ed9-5394-a372-ba79a07b978f', 'Storyboard', 'STRBRD', 0, '2021-07-03 10:50:21', 'pre', '#50b8c8', 'shot', '0.20', '0.50', '1.00', '2.00', '3.00', 0, 0, ''),
(2, '897bc267-151b-5c10-9a52-46933e2dd0b8', 'Character Design', 'CD', 0, '2021-07-03 10:51:00', 'asset', '#73e7ab', 'shot', '0.20', '0.50', '1.00', '2.00', '3.00', 0, 0, ''),
(3, '913195ca-eeb3-5402-aa7a-e73dd1546c28', 'Set Dressing', 'SET', 0, '2021-10-20 13:53:22', 'asset', '#98df85', 'shot', '0.20', '0.50', '1.00', '2.00', '3.00', 0, 0, ''),
(4, '95ac01d8-191c-5de5-9fa5-6e1210a59fe7', 'Props Design', 'PROPS', 0, '2021-07-03 10:51:53', 'asset', '#cfff90', 'shot', '0.20', '0.50', '1.00', '2.00', '3.00', 0, 0, ''),
(5, 'a46785cb-f410-5c3e-b0ca-9d436f83408e', 'Background Design', 'BG', 0, '2021-07-03 10:52:25', 'asset', '#f8ffb4', 'shot', '0.20', '0.50', '1.00', '2.00', '3.00', 0, 0, ''),
(6, '12132cf0-645a-5001-8bdc-94bd16563e0b', 'Script', 'SCRIPT', 0, '2021-07-03 10:52:51', 'pre', '#b0c2ff', 'shot', '0.20', '0.50', '1.00', '2.00', '3.00', 0, 0, ''),
(7, '8e07369c-ab9c-5f03-a80b-cf628ef893d6', 'Textures', 'TEX', 0, '2021-07-03 10:53:38', 'asset', '#dfa775', 'shot', '0.20', '0.50', '1.00', '2.00', '3.00', 0, 0, ''),
(8, 'e5d97647-ceee-5f8f-8e5d-d22be37360db', 'Shading', 'SHADE', 0, '2021-07-03 10:54:04', 'asset', '#ff7c50', 'shot', '0.20', '0.50', '1.00', '2.00', '3.00', 0, 0, ''),
(9, '48fee363-6ac7-5ca2-940b-2c1a14ec722a', 'Modeling', 'MOD', 0, '2021-10-20 13:52:25', 'asset', '#ff4039', 'shot', '0.50', '1.00', '2.00', '5.00', '10.00', 0, 0, ''),
(10, '7fc06313-e956-5a9a-a1e7-e1d6f3e9e532', 'Sculpting', 'SCULPT', 0, '2021-10-20 13:53:12', 'asset', '#ff4c73', 'shot', '0.20', '0.50', '2.00', '5.00', '8.00', 0, 0, ''),
(11, 'c6c16856-1585-5c97-a2be-debe287ac23f', 'Rigging', 'RIG', 0, '2021-10-20 13:52:59', 'asset', '#ef75ff', 'shot', '1.00', '5.00', '10.00', '20.00', '30.00', 0, 0, ''),
(12, 'b19f26c4-cac8-5345-a931-c02aab6a90c9', 'Animation', 'ANIM', 0, '2021-07-03 10:56:01', 'shot', '#c992ff', 'shot', '0.20', '0.50', '1.00', '2.00', '3.00', 0, 0, ''),
(13, '2c7a7eb9-4df2-54f4-8c26-b74384ece50e', 'Animatic', 'ANMTC', 0, '2021-07-03 10:56:52', 'pre', '#55f7ff', 'shot', '0.20', '0.50', '1.00', '2.00', '3.00', 0, 0, ''),
(14, 'e94720f5-6deb-5106-970e-c503ed1c4730', 'Layout', 'LAY', 0, '2021-10-20 13:51:17', 'asset', '#8aa3ff', 'shot', '0.10', '0.20', '0.30', '0.50', '1.00', 0, 0, ''),
(15, 'fd114d3f-e234-523b-ac52-b45c93f0e58c', 'Visual Effects', 'VFX', 0, '2021-07-03 10:57:56', 'shot', '#27ffa5', 'shot', '0.20', '0.50', '1.00', '2.00', '3.00', 0, 0, ''),
(16, '867b5178-7d36-5250-b626-3bcbe8e795cc', 'Cloth', 'CLOTH', 0, '2021-07-03 10:58:09', 'shot', '#ceffa5', 'shot', '0.20', '0.50', '1.00', '2.00', '3.00', 0, 0, ''),
(17, 'a89ef7b1-2c4d-5d2a-94ec-0540b58a2b76', 'Lighting', 'LIGHT', 0, '2021-10-20 13:51:58', 'shot', '#ff9d25', 'shot', '0.10', '0.50', '0.80', '2.00', '4.00', 0, 0, ''),
(18, '6e5431be-c11c-5248-b4d9-bd69fbb91b7c', 'Compositing', 'COMP', 0, '2021-07-03 10:59:12', 'shot', '#d5ff02', 'shot', '0.20', '0.50', '1.00', '2.00', '3.00', 0, 0, ''),
(19, '0983d009-eb6d-5749-8a51-e607edd4d3c2', 'Editing', 'EDIT', 0, '2021-07-03 10:59:38', 'post', '#ff62fc', 'shot', '0.20', '0.50', '1.00', '2.00', '3.00', 0, 0, ''),
(20, '8fe9c014-ca09-5f72-b96d-7d1b70b1ed22', 'Sound Design', 'SFX', 0, '2021-07-03 11:00:07', 'post', '#ff5aaa', 'shot', '0.20', '0.50', '1.00', '2.00', '3.00', 0, 0, ''),
(21, 'ee48e49b-ef49-558d-8bc0-f451a5be6f10', 'Music', 'MUSIC', 0, '2021-07-03 11:00:33', 'post', '#ff8692', 'shot', '0.20', '0.50', '1.00', '2.00', '3.00', 0, 0, ''),
(22, '516050fa-26cc-58c7-84c8-d9c79aad8e77', 'Color Correction', 'CC', 0, '2021-07-03 11:00:58', 'post', '#a14fff', 'shot', '0.20', '0.50', '1.00', '2.00', '3.00', 0, 0, '');

INSERT INTO `ram_users` (`id`, `uuid`, `name`, `shortName`, `password`, `email`, `latestUpdate`, `folderPath`, `role`, `color`, `removed`, `order`, `comment`) VALUES
(2, '9804963f-5a6f-5987-8adc-3a70ac6a1a68', 'Ramses Daemon', 'Ramses', '$2y$13$Woc8BBF.13i1dsKuNjX.y.SN4afvi/hUpaevDViqt7.WO.HZSR/3G', 'TDJQVWJITzczekFpVUxnWjFsRFJrUT09OjpYRTFJRTFLdFR3SXJOeG9kendFRkp3PT0=', '2021-10-20 13:44:59', 'auto', '$2y$04$K3QIYoKF8WAFkXDXatWqRea8Is9Cx8iQo3fhGcBez/qvKFIg/z0qq', '#e3e3e3', 0, 0, 'This is the little daemon which works discretely for you in the backyard of the Rx Asset Management System.'),
(3, 'e49e0411-19c8-5c85-8b33-d57ca9d72086', 'Nicolas Duduf Dufresne', 'Duduf', '$2y$13$jPdpAs8S54iy0SCk9zl07OZVn/P/JDoaykdfSLgw7EOolzWu.9pEe', 'ZjJTc1VXKy9EUXlxMzJGc29wSXgxdz09OjpWUTF3RVF2YjZYWFpzZmxYNUIwbmtRPT0=', '2021-10-20 13:44:59', 'auto', '$2y$04$E1cY68.5ZeIJ9jxpA4VD9OJyAUyElcwvCrOgmwWSDKWsLr1ZBviNC', '#e3e3e3', 0, 0, 'Duduf is the developer of Ramses! As Ramses is free, he really needs your support, please have a look at https://patreon.com/duduf or make a donation to RxLab on https://rainboxlab.org\nThanks!');
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
