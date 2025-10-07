-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 07, 2025 at 04:54 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `slogin`
--

-- --------------------------------------------------------

--
-- Table structure for table `privileges`
--

CREATE TABLE `privileges` (
  `id` int NOT NULL,
  `level` enum('superadmin','admin','user') NOT NULL,
  `can_create_admin` tinyint(1) DEFAULT '0',
  `can_create_user` tinyint(1) DEFAULT '0',
  `can_edit_admin` tinyint(1) DEFAULT '0',
  `can_edit_user` tinyint(1) DEFAULT '0',
  `can_delete_admin` tinyint(1) DEFAULT '0',
  `can_delete_user` tinyint(1) DEFAULT '0',
  `can_view_all_users` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `privileges`
--

INSERT INTO `privileges` (`id`, `level`, `can_create_admin`, `can_create_user`, `can_edit_admin`, `can_edit_user`, `can_delete_admin`, `can_delete_user`, `can_view_all_users`) VALUES
(1, 'superadmin', 1, 1, 1, 1, 1, 1, 1),
(2, 'admin', 0, 1, 0, 1, 0, 1, 1),
(3, 'user', 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `level` enum('superadmin','admin','user') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `level`, `created_at`, `updated_at`, `status`) VALUES
(1, 'superadmin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'superadmin@example.com', 'superadmin', '2025-10-07 03:23:01', '2025-10-07 03:23:01', 'active'),
(2, 'user', '$2y$10$d1w3eVoi7.ER0RL8d5TBJuczrIqq/sgB8bAvT1lApEXheUc.9twVa', 'user@example.com', 'user', '2025-10-07 03:26:46', '2025-10-07 03:26:46', 'active'),
(3, 'admin', '$2y$10$TcD3UFwKuJpYQClDAytNGO/k74j0XAT.CX61JPsYoXAseL4.6sZru', 'admin@example.com', 'admin', '2025-10-07 03:29:56', '2025-10-07 03:29:56', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `privileges`
--
ALTER TABLE `privileges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `privileges`
--
ALTER TABLE `privileges`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
