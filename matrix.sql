-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2023 at 01:42 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `matrix`
--

-- --------------------------------------------------------

--
-- Table structure for table `package_five`
--

CREATE TABLE `package_five` (
  `user_id` int(10) NOT NULL,
  `update_time` datetime NOT NULL,
  `merge_status` int(5) NOT NULL DEFAULT 0,
  `pay_status` int(5) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `package_four`
--

CREATE TABLE `package_four` (
  `user_id` int(10) NOT NULL,
  `update_time` int(11) NOT NULL,
  `merge_status` int(5) NOT NULL DEFAULT 0,
  `pay_status` int(5) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `package_one`
--

CREATE TABLE `package_one` (
  `user_id` int(20) NOT NULL,
  `update_time` datetime NOT NULL,
  `merge_status` int(5) NOT NULL DEFAULT 0,
  `pay_status` int(5) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `package_three`
--

CREATE TABLE `package_three` (
  `user_id` int(10) NOT NULL,
  `update_time` datetime NOT NULL,
  `merge_status` int(5) NOT NULL DEFAULT 0,
  `pay_status` int(5) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `package_two`
--

CREATE TABLE `package_two` (
  `user_id` int(5) NOT NULL,
  `update_time` datetime NOT NULL,
  `merge_status` int(11) NOT NULL DEFAULT 0,
  `pay_status` int(5) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paydetails`
--

CREATE TABLE `paydetails` (
  `user_id` int(11) NOT NULL,
  `bank_name` varchar(10) NOT NULL,
  `full_name` varchar(20) NOT NULL,
  `account_name` varchar(20) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `account_number` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `user_id` int(11) NOT NULL,
  `earnings` bigint(100) NOT NULL,
  `donations` bigint(100) NOT NULL,
  `package` int(20) NOT NULL,
  `last_update` datetime NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`user_id`, `earnings`, `donations`, `package`, `last_update`, `active`) VALUES
(1, 0, 0, 0, '2023-02-24 23:01:12', 0),
(2, 0, 0, 0, '2023-02-24 23:02:08', 0),
(3, 0, 0, 0, '2023-02-24 23:26:32', 0),
(4, 0, 0, 0, '2023-02-24 23:27:41', 0),
(5, 0, 0, 0, '2023-02-24 23:31:41', 0),
(6, 0, 0, 0, '2023-02-24 23:32:38', 0),
(7, 0, 0, 0, '2023-02-25 00:14:00', 0),
(8, 0, 0, 0, '2023-02-25 00:29:26', 0),
(9, 0, 0, 0, '2023-02-25 00:31:46', 0),
(1, 0, 0, 0, '2023-02-25 00:54:06', 0),
(2, 0, 0, 0, '2023-02-25 00:54:26', 0),
(3, 0, 0, 0, '2023-02-25 00:55:48', 0),
(4, 0, 0, 0, '2023-02-25 00:57:31', 0),
(5, 0, 0, 0, '2023-02-25 00:58:31', 0),
(6, 0, 0, 0, '2023-02-25 01:00:32', 0),
(7, 0, 0, 0, '2023-02-25 01:02:45', 0),
(8, 0, 0, 0, '2023-02-25 01:09:12', 0),
(1, 0, 0, 0, '2023-02-25 01:11:56', 0),
(2, 0, 0, 0, '2023-02-25 01:13:06', 0),
(3, 0, 0, 0, '2023-02-25 01:19:58', 0),
(4, 0, 0, 0, '2023-02-25 01:24:57', 0),
(5, 0, 0, 0, '2023-02-25 01:27:33', 0),
(6, 0, 0, 0, '2023-02-25 01:28:25', 0),
(7, 0, 0, 0, '2023-02-25 01:29:07', 0),
(8, 0, 0, 0, '2023-02-25 01:37:12', 0);

-- --------------------------------------------------------

--
-- Table structure for table `temp`
--

CREATE TABLE `temp` (
  `user_id` bigint(10) NOT NULL,
  `user_package` tinyint(5) NOT NULL,
  `update_time` datetime NOT NULL,
  `sn` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `user_id` int(10) NOT NULL,
  `user_id_up` int(10) DEFAULT NULL,
  `time_start` datetime DEFAULT NULL,
  `time_end` datetime DEFAULT NULL,
  `to_user_id` int(10) DEFAULT NULL,
  `from_user_id_one` int(10) DEFAULT NULL,
  `from_user_id_two` int(10) DEFAULT NULL,
  `button_paid` tinyint(5) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(5) NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `user_email` varchar(64) NOT NULL,
  `user_password_hash` varchar(255) NOT NULL,
  `user_activation_hash` varchar(255) DEFAULT NULL,
  `user_registration_date` datetime NOT NULL,
  `user_registration_ip` varchar(64) NOT NULL DEFAULT '0.0.0.0',
  `user_filled_logins` tinyint(1) DEFAULT 0,
  `user_last_filled_login` int(10) DEFAULT NULL,
  `user_rememberme_token` varchar(255) DEFAULT NULL,
  `user_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `user_password_hash`, `user_activation_hash`, `user_registration_date`, `user_registration_ip`, `user_filled_logins`, `user_last_filled_login`, `user_rememberme_token`, `user_active`) VALUES
(1, 'joyce', 'joy@gmail.com', '$2y$10$e2/hT8q9MrBpyNRYSBXewewXGiYiQ5SfzNxEr6htB8P78ZmNy3nim', '86e1f00345447d0badd503ad3431d9ab56d74d62', '2023-02-25 01:11:56', '::1', 0, NULL, NULL, 1),
(2, 'kwezi', 'kwezi@gmail.com', '$2y$10$dhSxN5ItsxdQTwrkofjQde/Rp2YcP3SrU1LbsOrko9PD6eautaHTC', '595cf3ce7d0a7fafdf22c51f1a19f3a78725f8cd', '2023-02-25 01:13:06', '::1', 0, NULL, NULL, 1),
(3, '12345', '12345@gmail.com', '$2y$10$85chYCaeuToQU6orUh7qFeT/SDQ0Rj6kq0fsH/F0x/sd2VnJLgGXa', '8511e04cf0fc9173a735d921953a92930e80d761', '2023-02-25 01:19:58', '::1', 0, NULL, NULL, 1),
(4, 'anisha', 'muna@gmail.com', '$2y$10$5xLD6q.oOcqth3RNWBM9Vu/XpQ/tG2072WashwQmvHbj9CNI39loC', '2997afc17e4737a94c2b8b3cb04150e00c343f78', '2023-02-25 01:24:57', '::1', 0, NULL, NULL, 1),
(5, 'kwezinn', 'limbos@gmail.com', '$2y$10$kXR0cteSrn9jEW2U4ncGL.6cYu/WIqEvMnvHvYh.o3GQ16fihejiO', 'f6a42ba20c0503adef0d11914d07f8341412e64e', '2023-02-25 01:27:33', '::1', 0, NULL, NULL, 1),
(6, 'akpan', 'faila@gmail.com', '$2y$10$DefXrxlp41hIX3LLdH0pFOUqf4exsOIeWMvOqBTHJpcm.1sVi/XKa', '3c9af3eb57efb898ba42d3eb9edfe34fcb5173bc', '2023-02-25 01:28:25', '::1', 0, NULL, NULL, 1),
(7, 'david', 'david@gmail.com', '$2y$10$DgNnp5OkQMG1v4RpV3CxheHd4758JJdcUx3VRS4VGSTHpn2msVzc2', '7f9ce5815a1fb09f24481c80d63a16f8d542cb4d', '2023-02-25 01:29:07', '::1', 0, NULL, NULL, 1),
(8, 'penis', 'penis@gmail.com', '$2y$10$aUuLDjp4nUHWnkoHRx0qWuhSDaURXV4vgqU.8pGcS4anOQB.5QEz6', '86190bcb84a8244b07276b1607f147ef08ad9b30', '2023-02-25 01:37:12', '::1', 0, NULL, NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `temp`
--
ALTER TABLE `temp`
  ADD PRIMARY KEY (`sn`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `temp`
--
ALTER TABLE `temp`
  MODIFY `sn` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
