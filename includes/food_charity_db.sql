-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2025 at 01:21 AM
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
-- Database: `food_charity_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cash_donations`
--

CREATE TABLE `cash_donations` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `message` text DEFAULT NULL,
  `donated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cash_donations`
--

INSERT INTO `cash_donations` (`id`, `name`, `email`, `amount`, `message`, `donated_at`) VALUES
(1, 'al sa', 'doner@example.com', 1000.00, '', '2025-04-19 04:18:04'),
(2, 'aa', 'doner2@example.com', 26000.00, 'aaa', '2025-04-19 04:39:23'),
(3, 'aa', 'bb@email.com', 31000.00, '', '2025-04-20 02:45:37');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `donation_id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `food_description` text NOT NULL,
  `quantity` varchar(100) DEFAULT NULL,
  `pickup_address` text NOT NULL,
  `pickup_time_preference` varchar(255) DEFAULT NULL,
  `status` enum('pending','assigned','collected','delivered','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `assigned_volunteer_id` int(11) DEFAULT NULL,
  `collection_time` datetime DEFAULT NULL,
  `delivery_time` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`donation_id`, `donor_id`, `food_description`, `quantity`, `pickup_address`, `pickup_time_preference`, `status`, `created_at`, `assigned_volunteer_id`, `collection_time`, `delivery_time`, `notes`) VALUES
(2, 4, 'pizzas', '70', 'doner vile, tvm, 123456', 'today', 'assigned', '2025-04-13 22:03:56', 2, NULL, NULL, NULL),
(3, 7, '10 sss', 'ss', 'tvm vass', '', 'delivered', '2025-04-19 21:40:46', 6, '2025-04-20 03:11:41', '2025-04-20 03:11:44', NULL),
(4, 7, 'ss', 'ss', 'tvm va', 'ss', 'delivered', '2025-04-19 21:40:53', 6, '2025-04-20 03:11:49', '2025-04-20 03:11:54', NULL),
(5, 7, 'sss', 'ss', 'tvm va', 'ss', 'delivered', '2025-04-19 21:41:00', 6, '2025-04-20 03:12:00', '2025-04-20 03:12:03', NULL),
(6, 7, 'sss', 'ss', 'sstvm va', 'ss', 'pending', '2025-04-19 21:41:05', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `help_requests`
--

CREATE TABLE `help_requests` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `amount_needed` decimal(12,2) NOT NULL,
  `upi_id` varchar(100) NOT NULL,
  `bank_details` text DEFAULT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `is_approved` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `disease` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `ifsc_code` varchar(20) DEFAULT NULL,
  `branch_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `help_requests`
--

INSERT INTO `help_requests` (`id`, `title`, `description`, `amount_needed`, `upi_id`, `bank_details`, `document_path`, `is_approved`, `created_at`, `full_name`, `age`, `disease`, `phone`, `email`, `account_number`, `ifsc_code`, `branch_name`) VALUES
(2, 'money for tour', '123456', 123456.00, '12345678', '', 'uploads/help_docs/doc_6804053e0ba24_Screenshot 2025-04-14 141254.png', 1, '2025-04-20 01:49:10', 'babu', 12, 'kazhap', '1234567890', 'bb@email.com', '123456', '123456', 'koilandi'),
(3, 'aa', 'aa', 133446.00, '12345678', '', 'uploads/help_docs/doc_68040683a61b9_Screenshot 2025-04-14 141254.png', 1, '2025-04-20 01:54:35', 'new babu', 41, 'kazhap', '1234567890', 'bb@gmail.com', '', '', ''),
(4, 'aa', 'aa', 123456.00, '12345678', '', 'uploads/help_docs/doc_6804070da50eb_Screenshot 2025-04-14 141254.png', 1, '2025-04-20 01:56:53', 'vere babu', 42, 'kazhap', '123456789', 'bb@email.com', '', '', ''),
(5, 'aaa', 'aa', 123456.00, '12345678', '', 'uploads/help_docs/doc_68040a1785acb_Screenshot 2025-04-14 141254.png', 1, '2025-04-20 02:09:51', 'vere vere babu', 123, 'kazhap', '1230446789', 'bb@email.com', '', '', ''),
(6, 'AA', 'aaa', 123.00, '12345678', '', 'uploads/help_docs/doc_68040aae44ec6_Screenshot 2025-04-14 141254.png', 1, '2025-04-20 02:12:22', 'babu', 123, 'kazhap', '1230456789', 'bb@email.com', '', '', ''),
(7, 'aa', 'aa', 122.00, '122', '', 'uploads/help_docs/doc_68040b3fa7927_Screenshot 2025-04-14 141254.png', 1, '2025-04-20 02:14:47', 'babu', 12, 'kazhap', '100', 'bb@email.com', '', '', ''),
(8, 'aa', 'aaa', 1132.00, '1212', '', '', 1, '2025-04-20 02:18:56', 'babu', 12, 'kazhap', '1234456789', 'bb@email.com', '', '', ''),
(9, 'aa', 'aa', 1212.00, '1212', '', '', 1, '2025-04-20 02:21:12', 'babu', 123, 'kazhap', '1212', 'bb@email.com', '', '', ''),
(10, 'aa', 'aa', 123.00, '1223', '', '', 1, '2025-04-20 02:24:44', 'bb', 123, 'kazhap', '123', 'bb@email.com', '', '', ''),
(11, 'aa', 'aa', 122.00, '212', '', '', 1, '2025-04-20 02:26:38', 'bb', 12, 'kazhap', '12', 'bb@email.com', '', '', ''),
(12, 'aa', 'aa', 121.00, '212', '', '', 1, '2025-04-20 02:28:35', 'bb', 12, 'kazhap', '1212', 'bb@email.com', '', '', ''),
(13, 'aa', 'aa', 121.00, '121212', '', '', 1, '2025-04-20 02:39:21', 'babu', 12, 'kazhap', '121', 'bb@email.com', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `help_request_donations`
--

CREATE TABLE `help_request_donations` (
  `id` int(11) NOT NULL,
  `help_request_id` int(11) NOT NULL,
  `donor_name` varchar(100) NOT NULL,
  `donor_email` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `donated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token_hash` varchar(255) NOT NULL,
  `requested_at` datetime NOT NULL,
  `approved` tinyint(1) DEFAULT 0,
  `approved_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `role` enum('donor','volunteer','admin') NOT NULL,
  `is_approved` tinyint(1) DEFAULT 0,
  `reset_token_hash` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `password`, `phone`, `address`, `city`, `postal_code`, `role`, `is_approved`, `reset_token_hash`, `reset_token_expiry`, `registration_date`) VALUES
(1, 'Admin', 'User', 'admin@example.com', '$2y$10$v2l8057uAMP0VCdKBEvjBuKXcN7HvWkcMwCZnsIAP1Y1.Yvlnv5cK', '1234567890', '1 Admin Street', 'Adminville', 'A1 B2C', 'admin', 1, NULL, NULL, '2025-03-29 18:58:05'),
(2, 'abc', 'def', 'volunteer@example.com', '$2y$10$UJPeI26QISaQfE1QyFHXNOFOLdwEIm.u9f/MdpVpq2ZKIvvcgH1M6', '123456790', '', '', '', 'volunteer', 1, '$2y$10$Tk0y/CH3TyqZg/Ct4FlXxedyktEqo/fhru0AlgPzXuWAt6kcHTYjy', '2025-04-13 20:34:32', '2025-04-12 22:08:33'),
(4, 'doner', 'ss', 'doner@example.com', '$2y$10$WtH2q1OYOcB8yLxnCLCyAeFwY72OvNWvjlFhTJ7eiPWhLRaD.qULm', '123456', 'doner vile', 'tvm', '123456', 'donor', 1, '$2y$10$WUMcMpww7umsslwj4W1AeeMxzCuFezIz7zZs4BQPRggs29T4FSfTi', '2025-04-14 01:11:35', '2025-04-13 22:03:15'),
(6, 'Vol', '2', 'vol2@example.com', '$2y$10$/AgNMWdbLrfVF6mBhSezJunsrvLJoG2F46QJpwv82LI9paanBhOeW', '123', '', '', '', 'volunteer', 1, NULL, NULL, '2025-04-17 21:58:11'),
(7, 'doner', '2', 'doner2@example.com', '$2y$10$f.CnX4EczOzXF8sLXiwP5.wER2ug6TSHMfVGA8FxOHisi1ZiH8ShC', '1212', 'tvm va', '', '', 'donor', 1, NULL, NULL, '2025-04-18 22:19:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cash_donations`
--
ALTER TABLE `cash_donations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`donation_id`),
  ADD KEY `donations_ibfk_1` (`donor_id`),
  ADD KEY `donations_ibfk_2` (`assigned_volunteer_id`);

--
-- Indexes for table `help_requests`
--
ALTER TABLE `help_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `help_request_donations`
--
ALTER TABLE `help_request_donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `help_request_id` (`help_request_id`);

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
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_reset_token_expiry` (`reset_token_expiry`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cash_donations`
--
ALTER TABLE `cash_donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `donation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `help_requests`
--
ALTER TABLE `help_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `help_request_donations`
--
ALTER TABLE `help_request_donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`donor_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `donations_ibfk_2` FOREIGN KEY (`assigned_volunteer_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `help_request_donations`
--
ALTER TABLE `help_request_donations`
  ADD CONSTRAINT `help_request_donations_ibfk_1` FOREIGN KEY (`help_request_id`) REFERENCES `help_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
