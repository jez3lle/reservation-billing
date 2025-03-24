-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 21, 2025 at 04:49 PM
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
-- Table structure for table `guest_reservations`
--

CREATE TABLE `guest_reservations` (
  `id` int(11) NOT NULL,
  `reservation_code` varchar(20) NOT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `guest_adult` int(30) NOT NULL,
  `guest_kid` int(30) NOT NULL,
  `special_requests` text DEFAULT NULL,
  `payment_amount` int(255) NOT NULL,
  `payment_method` varchar(64) NOT NULL,
  `created_at` datetime NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guest_reservations`
--

INSERT INTO `guest_reservations` (`id`, `reservation_code`, `check_in_date`, `check_out_date`, `first_name`, `last_name`, `email`, `contact_number`, `guest_adult`, `guest_kid`, `special_requests`, `payment_amount`, `payment_method`, `created_at`, `status`) VALUES
(1, 'G-350FF2EB', '2025-03-23', '2025-03-24', 'Ike Diezel', 'Reyes', 'ikediezel.samosa@gmail.com', '09374583254', 6, 0, '', 0, '', '2025-03-15 16:30:29', 'pending'),
(2, 'G-E5CE7087', '2025-03-23', '2025-03-24', 'Ike Diezel', 'Reyes', 'ikediezel.samosa@gmail.com', '09374583254', 6, 0, '', 0, '', '2025-03-15 16:30:43', 'pending'),
(3, 'G-62452DDA', '2025-03-25', '2025-03-26', 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', 3, 0, '', 0, '', '2025-03-15 16:40:33', 'pending'),
(4, 'G-CB43D863', '2025-03-18', '2025-03-19', 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', 0, 0, '', 0, '', '2025-03-15 17:29:16', 'pending'),
(5, 'G-55674B96', '2025-03-24', '2025-03-25', 'Diezel', 'Reyes', 'samosa.ikediezel@ue.edu.ph', '09876524355', 17, 6, '', 25760, 'credit_card', '2025-03-15 18:19:24', 'confirmed'),
(6, 'G-F392AB55', '2025-03-30', '2025-03-31', 'Cerike', 'Reyes', 'samosa.ikediezel@ue.edu.ph', '09876524355', 10, 5, '', 0, '', '2025-03-16 17:32:47', 'pending'),
(7, 'G-3197EA51', '2025-03-30', '2025-03-31', 'Cerike', 'Reyes', 'samosa.ikediezel@ue.edu.ph', '09374583254', 15, 4, '', 22400, 'gcash', '2025-03-16 17:33:51', 'confirmed'),
(8, 'G-332C9950', '2025-03-24', '2025-03-26', 'Bosing', 'Reyes', 'samosa.ikediezel@ue.edu.ph', '09374583254', 0, 0, '', 0, '', '2025-03-20 16:34:40', 'pending'),
(9, 'G-75AA8786', '2025-03-24', '2025-03-26', 'Ike Diezel', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', 25, 8, '', 0, '', '2025-03-20 16:35:38', 'pending'),
(10, 'G-E0CD6557', '2025-03-24', '2025-03-26', 'Bosing', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '09374583254', 0, 0, '', 0, '', '2025-03-20 16:49:26', 'pending'),
(11, 'G-4C0D6712', '2025-03-23', '2025-03-24', 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', 0, 0, '', 0, '', '2025-03-21 16:28:31', 'pending'),
(12, 'G-EC96C4C5', '2025-03-23', '2025-03-24', 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', 0, 0, '', 0, '', '2025-03-21 16:30:15', 'pending'),
(13, 'G-ECF808C5', '2025-03-23', '2025-03-24', 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', 0, 0, '', 0, '', '2025-03-21 16:30:21', 'pending'),
(14, 'G-97105543', '2025-03-23', '2025-03-24', 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', 0, 0, '', 0, '', '2025-03-21 16:30:30', 'pending'),
(15, 'G-67C3916F', '2025-03-23', '2025-03-24', 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', 0, 0, '', 0, '', '2025-03-21 16:47:17', 'pending');

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
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `status` enum('Pending','Confirmed','Expired') DEFAULT 'Pending',
  `payment_proof` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `payment_status` enum('No Payment','Pending Verification','Paid','Rejected') DEFAULT 'No Payment'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `check_in`, `check_out`, `status`, `payment_proof`, `created_at`, `last_name`, `first_name`, `email`, `contact_number`, `payment_status`) VALUES
(17, 45, '2025-02-28', '2025-03-01', 'Confirmed', NULL, '2025-02-26 15:21:29', 'Formentera', 'jezelle', 'jezelleformentera21@gmail.com', '091292383813', 'Paid'),
(39, 45, '2025-03-14', '2025-03-15', 'Pending', NULL, '2025-03-13 06:14:02', 'Samosa', 'Bosing', 'samosa.ikediezel@ue.edu.ph', '09374583254', 'No Payment'),
(40, 45, '2025-03-16', '2025-03-17', 'Pending', NULL, '2025-03-13 06:35:26', 'Reyes', 'Bosing', 'samosa.ikediezel@ue.edu.ph', '09374583254', 'No Payment'),
(41, 60, '2025-03-17', '2025-03-18', 'Pending', NULL, '2025-03-13 15:42:59', 'Samosa', 'Cerike', 'ikediezel.samosa@gmail.com', '09125663432', 'No Payment');

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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`) VALUES
(19),
(20),
(26);

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
-- Indexes for table `guest_reservations`
--
ALTER TABLE `guest_reservations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservation_id` (`reservation_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `guest_reservations`
--
ALTER TABLE `guest_reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bills_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
