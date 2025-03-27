-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2025 at 08:19 PM
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
-- Database: `resort_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password_hash`) VALUES
(1, 'admin', '$2y$10$ldG1WlEtcQPoX9Sy6nCuMeTrdgjHmlUXgQIzyiMO//Zr/FUsVcisu');

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_status` enum('Pending','Paid','Expired') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`id`, `reservation_id`, `user_id`, `total_amount`, `payment_status`, `created_at`) VALUES
(21, 39, 45, 100.00, 'Pending', '2025-03-13 06:14:02'),
(22, 40, 45, 100.00, 'Pending', '2025-03-13 06:35:26'),
(23, 41, 60, 100.00, 'Pending', '2025-03-13 15:42:59');

-- --------------------------------------------------------

--
-- Table structure for table `guest_reservation`
--

CREATE TABLE `guest_reservation` (
  `id` int(11) NOT NULL,
  `reservation_code` varchar(255) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `adult_count` int(11) NOT NULL,
  `kid_count` int(11) NOT NULL,
  `tour_type` varchar(50) NOT NULL,
  `special_requests` text DEFAULT NULL,
  `extra_mattress` int(11) DEFAULT 0,
  `extra_pillow` int(11) DEFAULT 0,
  `extra_blanket` int(11) DEFAULT 0,
  `base_price` decimal(10,2) NOT NULL,
  `extras_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guest_reservation`
--

INSERT INTO `guest_reservation` (`id`, `reservation_code`, `check_in`, `check_out`, `first_name`, `last_name`, `email`, `contact_number`, `adult_count`, `kid_count`, `tour_type`, `special_requests`, `extra_mattress`, `extra_pillow`, `extra_blanket`, `base_price`, `extras_price`, `total_price`, `created_at`) VALUES
(1, 'KS2503296701', '2025-03-29', '2025-03-30', 'Keith', 'Samosa', 'ayk.example@yahoo.com', '09876524355', 19, 12, '0', '', 1, 2, 1, 18600.00, 300.00, 18900.00, '2025-03-27 19:08:52'),
(2, 'IS2503297219', '2025-03-29', '2025-03-30', 'Ike', 'Samosa', 'ikediezel.samosa@gmail.com', '0987652435', 24, 12, '0', '', 2, 2, 2, 13400.00, 500.00, 13900.00, '2025-03-27 19:12:21');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_receipt` varchar(255) NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `file_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `reservation_id`, `user_id`, `payment_receipt`, `status`, `uploaded_at`, `file_path`) VALUES
(12, 17, 26, '', 'Approved', '2025-02-26 15:21:50', 'uploads/payments/payment_1740583310_26.png');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL,
  `email` varchar(128) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `reset_token_hash` varchar(64) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL,
  `account_activation_hash` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `email`, `contact_number`, `password_hash`, `reset_token_hash`, `reset_token_expires_at`, `account_activation_hash`) VALUES
(30, 'Ike', '', 'ayk@yahoo.com', '09485537455', '$2y$10$GzVphKmaRN/YFz1BHOSoI.hp/4BhAzZQ2pJmLmQNhkJmnBO60vqAa', 'b3e7b6c88cdb0b09d3416ee03b844769eb9667c1c9797c9028747b46612194ae', '2025-02-28 17:17:32', 'abfb1800e5aad29bd5036296e8143d6b4591758eedd1f2d283d4eb3187fe1c55'),
(37, 'meimeirin', '', 'm31meirin@gmail.com', '09374583254', '$2y$10$5uzUTeCi/syzH8UEwxmk2OLmU9CdcBekb4DA1pCxyR0wRfx.GMoUK', 'fc43fd7e39a594ba288c4ae2c28d6195adcfa3e71b6839c2730db94ff3876380', '2025-02-26 04:19:03', NULL),
(39, 'Jade', '', 'torrejas.jade@ue.edu.ph', '09876524355', '$2y$10$fERSxKd2JOCpVHgQHQBynuOBhxiHKiOP44xQbOKOnLvl09sHDPERq', NULL, NULL, '2d617bcbfe04e0139c8bc6c6fae06520a3d2b5f24970a88a09b07e5ca1ece163'),
(42, 'Ayk', '', 'ayk.example@yahoo.com', '09125663432', '$2y$10$0kLy6xmkgCN7Qb4db4a6r.xnHpo9KhObG8gqc40MfIRiIqeXqra1S', NULL, NULL, '9cee9981be1d0a3fba1284de7125524ad2c89c6b7714eabf1289528ea2c2376e'),
(45, 'Bosing', '', 'samosa.ikediezel@ue.edu.ph', '09374583254', '$2y$10$mLcGRqgtnyEGkG71uypm6.P/aHlXWF.64jcLPfbL2ynJTPN5a1TrO', NULL, NULL, NULL),
(60, 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', '$2y$10$0gjQOY2u88BP6rTAr5BixuFzeM49CUWFp/xOhNvK6M8H8qx2W07Cu', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_reservation`
--

CREATE TABLE `user_reservation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reservation_code` varchar(255) DEFAULT NULL,
  `check_in` date DEFAULT NULL,
  `check_out` date DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `adult_count` int(11) DEFAULT NULL,
  `kid_count` int(11) DEFAULT NULL,
  `tour_type` varchar(50) DEFAULT NULL,
  `special_requests` text DEFAULT NULL,
  `extra_mattress` int(11) DEFAULT 0,
  `extra_pillow` int(11) DEFAULT 0,
  `extra_blanket` int(11) DEFAULT 0,
  `total_price` decimal(10,2) DEFAULT NULL,
  `extras_total` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_reservation`
--

INSERT INTO `user_reservation` (`id`, `user_id`, `reservation_code`, `check_in`, `check_out`, `first_name`, `last_name`, `email`, `contact_number`, `adult_count`, `kid_count`, `tour_type`, `special_requests`, `extra_mattress`, `extra_pillow`, `extra_blanket`, `total_price`, `extras_total`, `total_amount`, `created_at`) VALUES
(1, 60, '20250327-B8CEE6', '2025-03-29', '2025-03-30', 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', 42, 12, '0', '', 5, 7, 4, 32400.00, 1300.00, 33700.00, '2025-03-27 19:18:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservation_id` (`reservation_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `guest_reservation`
--
ALTER TABLE `guest_reservation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reservation_code` (`reservation_code`),
  ADD KEY `idx_reservation_code` (`reservation_code`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservation_id` (`reservation_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `reset_token_hash` (`reset_token_hash`),
  ADD UNIQUE KEY `account_activation_hash` (`account_activation_hash`);

--
-- Indexes for table `user_reservation`
--
ALTER TABLE `user_reservation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reservation_code` (`reservation_code`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `guest_reservation`
--
ALTER TABLE `guest_reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `user_reservation`
--
ALTER TABLE `user_reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `user_reservation`
--
ALTER TABLE `user_reservation`
  ADD CONSTRAINT `user_reservation_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
