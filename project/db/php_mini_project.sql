-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2025 at 12:25 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php_mini_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `meal_type` varchar(50) DEFAULT NULL,
  `food_quantity` int(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `landmark` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `donation_status` varchar(20) DEFAULT 'Pending',
  `volunteer_status` enum('Pending','Pickup Processing','Picked Up','Completed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `user_id`, `first_name`, `last_name`, `phone`, `meal_type`, `food_quantity`, `address`, `city`, `landmark`, `state`, `donation_status`, `volunteer_status`, `created_at`) VALUES
(20, 7, 'ivijoiji', 'kokmoj', '8967543254', 'breakfast', 2, 'jnijn', 'knokn', 'jbjnijn', 'knkon', 'Pending', 'Completed', '2025-04-08 17:30:48'),
(21, 7, 'ivijoiji', 'kokmoj', '8967543254', 'breakfast', 2, 'jnijn', 'knokn', 'jbjnijn', 'knkon', 'Pending', 'Completed', '2025-04-08 17:32:42'),
(22, 7, 'ivijoiji', 'kokmoj', '8967543254', 'breakfast', 2, 'jnijn', 'knokn', 'jbjnijn', 'knkon', 'Pending', 'Pickup Processing', '2025-04-08 17:33:38'),
(23, 7, 'ivijoiji', 'kokmoj', '8967543254', 'breakfast', 2, 'jnijn', 'knokn', 'jbjnijn', 'knkon', 'Pending', 'Completed', '2025-04-08 17:33:53'),
(24, 7, 'ivijoiji', 'kokmoj', '8967543254', 'breakfast', 2, 'jnijn', 'knokn', 'jbjnijn', 'knkon', 'Pending', 'Pending', '2025-04-08 17:34:10'),
(25, 7, 'ivijoiji', 'kokmoj', '8967543254', 'breakfast', 2, 'jnijn', 'knokn', 'jbjnijn', 'knkon', 'Cancelled', 'Pending', '2025-04-08 17:34:35'),
(26, 7, 'ivijoiji', 'kokmoj', '8967543254', 'breakfast', 2, 'jnijn', 'knokn', 'jbjnijn', 'knkon', 'Cancelled', 'Pending', '2025-04-08 17:35:29'),
(27, 7, 'ivijoiji', 'kokmoj', '8967543254', 'breakfast', 2, 'jnijn', 'knokn', 'jbjnijn', 'knkon', 'Cancelled', 'Pending', '2025-04-08 17:35:46'),
(28, 7, 'ivijoiji', 'kokmoj', '8967543254', 'breakfast', 2, 'jnijn', 'knokn', 'jbjnijn', 'knkon', 'Cancelled', 'Pending', '2025-04-08 17:35:58'),
(29, 7, 'jnijn', 'n kjn', '8765341234', 'lunch', 2, 'oh', 'kjoj', 'lnkn', 'ioo', 'Cancelled', 'Pending', '2025-04-08 18:25:29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('volunteer','donor','admin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`, `created_at`) VALUES
(1, 'Admin User', 'admin@example.com', 'admin123', 'admin', '2025-04-06 07:19:47'),
(6, 'amal', 'amal@gmail.com', '$2y$10$IIX/KMAwQciEsL7bh.X4/ugAsfXAEsoEYuJYEydwq73jx/1TTcX8K', 'donor', '2025-04-06 10:18:00'),
(7, 'anju', 'anju@gmail.com', '$2y$10$fp0M4aOXh/4wst7C3vbwBuVXzWxsQUIlsu3x9SR/bNTyLmgB9VH9q', 'donor', '2025-04-08 14:40:30'),
(8, 'test', 'test@gmail.com', 'test@1212', 'volunteer', '2025-04-09 06:25:20');

-- --------------------------------------------------------

--
-- Table structure for table `volunteers`
--

CREATE TABLE `volunteers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `aadhaar` varchar(14) NOT NULL,
  `availability` enum('weekdays','weekends','full-time') NOT NULL,
  `authority` enum('NSS','NCC','other') NOT NULL,
  `authority_id` varchar(50) NOT NULL,
  `skills` text DEFAULT NULL,
  `status` enum('pending','approved','cancelled') NOT NULL DEFAULT 'pending',
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volunteers`
--

INSERT INTO `volunteers` (`id`, `name`, `email`, `phone`, `aadhaar`, `availability`, `authority`, `authority_id`, `skills`, `status`, `registered_at`) VALUES
(1, 'vishnu', 'vishnu@gmail.com', '6587905467', '8989 9889 0909', 'weekdays', 'NCC', '899678968969868796', 'hjbhbhjbfjbjbjbjb', 'pending', '2025-04-09 05:57:08'),
(5, 'test', 'test@gmail.com', '8909875634', '9889 8989 9009', 'weekends', 'NSS', '0989675434', 'jonjnkjlknk\'nmkon', 'approved', '2025-04-09 06:04:55'),
(6, 'user', 'user@gmail.com', '8967546789', '0990 8989 0909', 'weekdays', 'other', '9889089788797', 'hbiihjbijbijbnbjbjn', 'approved', '2025-04-09 06:11:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `volunteers`
--
ALTER TABLE `volunteers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `volunteers`
--
ALTER TABLE `volunteers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
