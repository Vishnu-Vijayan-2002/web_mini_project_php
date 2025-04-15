-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2025 at 07:00 PM
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
-- Table structure for table `cash_documents`
--

CREATE TABLE `cash_documents` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `document_type` varchar(100) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cash_documents`
--

INSERT INTO `cash_documents` (`id`, `request_id`, `file_path`, `document_type`, `uploaded_at`) VALUES
(2, 3, 'uploads/1744542523_kft-report-format.png', 'medical_certificate', '2025-04-13 11:08:43'),
(3, 4, 'uploads/1744545751_kft-report-format.png', 'medical_certificate', '2025-04-13 12:02:31'),
(4, 5, 'uploads/1744548798_kft-report-format.png', 'medical_certificate', '2025-04-13 12:53:18');

-- --------------------------------------------------------

--
-- Table structure for table `cash_requests`
--

CREATE TABLE `cash_requests` (
  `id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `age` int(11) NOT NULL,
  `disease` varchar(255) NOT NULL,
  `contact_info` varchar(255) NOT NULL,
  `upi_id` varchar(100) NOT NULL,
  `bank_details` text NOT NULL,
  `required_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `feature_start_date` date DEFAULT NULL,
  `feature_end_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cash_requests`
--

INSERT INTO `cash_requests` (`id`, `full_name`, `age`, `disease`, `contact_info`, `upi_id`, `bank_details`, `required_amount`, `status`, `feature_start_date`, `feature_end_date`, `created_at`, `approved_at`) VALUES
(3, 'Arun ', 43, 'Kidney Failure', '9832398782', 'aruncy@ybl', 'Account No: SBIN00354278, IFSC: BANKIFC09283NN, Branch: Trivandrum Town', 300000.00, 'rejected', NULL, NULL, '2025-04-13 11:08:43', NULL),
(4, 'Zameer', 23, 'Liver Failure', '935627727', 'zmrre@upi', 'Account No: 98282766267, IFSC: IFC1234533, Branch: Kollam', 80000.00, 'approved', NULL, NULL, '2025-04-13 12:02:31', '2025-04-13 08:33:42'),
(5, 'eferfer', 34, 'efegf', '4564666', 'sfdf', 'Account No: r3434343, IFSC: 4343434, Branch: thth', 499.00, 'approved', NULL, NULL, '2025-04-13 12:53:18', '2025-04-13 09:24:22');

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
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(8, 'test', 'test@gmail.com', 'test@1212', 'volunteer', '2025-04-09 06:25:20'),
(9, 'Abhijith P', 'abhijithpradeep2208@gmail.com', 'abhijith@123', 'donor', '2025-04-13 05:04:03');

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
-- Indexes for table `cash_documents`
--
ALTER TABLE `cash_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `cash_requests`
--
ALTER TABLE `cash_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `cash_documents`
--
ALTER TABLE `cash_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cash_requests`
--
ALTER TABLE `cash_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `volunteers`
--
ALTER TABLE `volunteers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cash_documents`
--
ALTER TABLE `cash_documents`
  ADD CONSTRAINT `cash_documents_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `cash_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
