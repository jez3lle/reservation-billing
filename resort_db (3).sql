-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 04, 2025 at 11:29 AM
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
-- Table structure for table `guest_reservation`
--

CREATE TABLE `guest_reservation` (
  `id` int(11) NOT NULL,
  `reservation_code` varchar(255) NOT NULL,
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
  `base_price` decimal(10,2) DEFAULT NULL,
  `extras_price` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guest_reservation`
--

INSERT INTO `guest_reservation` (`id`, `reservation_code`, `check_in`, `check_out`, `first_name`, `last_name`, `email`, `contact_number`, `adult_count`, `kid_count`, `tour_type`, `special_requests`, `extra_mattress`, `extra_pillow`, `extra_blanket`, `base_price`, `extras_price`, `total_price`, `created_at`) VALUES
(1, 'KS2503296701', '2025-03-29', '2025-03-30', 'Keith', 'Samosa', 'ayk.example@yahoo.com', '09876524355', 19, 12, '0', '', 1, 2, 1, 18600.00, 300.00, 18900.00, '2025-03-27 19:08:52'),
(2, 'IS2503297219', '2025-03-29', '2025-03-30', 'Ike', 'Samosa', 'ikediezel.samosa@gmail.com', '0987652435', 24, 12, '0', '', 2, 2, 2, 13400.00, 500.00, 13900.00, '2025-03-27 19:12:21'),
(3, 'CS2504010512', '2025-04-01', '2025-04-02', 'ce', 'Samosa', 'ayk.example@yahoo.com', '09876524355', 22, 12, '0', '', 2, 1, 1, 12600.00, 400.00, 13000.00, '2025-03-31 15:55:54'),
(4, 'MS2504029194', '2025-04-02', '2025-04-03', 'Mark', 'Santos', 'ayk.exam@yahoo.com', '09876524355', 22, 12, '0', 'sadfasfas', 1, 1, 1, 20400.00, 250.00, 20650.00, '2025-03-31 16:29:44'),
(5, 'DC2504036459', '2025-04-03', '2025-04-04', 'Diezelike', 'Caceres', 'ayk.example@yahoo.com', '09374583254', 44, 12, '0', 'afasfasfas123', 3, 2, 2, 21400.00, 650.00, 22050.00, '2025-03-31 16:30:37'),
(6, 'JT2504058962', '2025-04-05', '2025-04-06', 'Jade', 'Torreajs', 'torrejas.jade@ue.edu.ph', '09544646496', 31, 11, '0', '', 1, 1, 1, 25200.00, 250.00, 25450.00, '2025-04-01 15:23:55'),
(8, 'JB2504068466', '2025-04-06', '2025-04-07', 'Jeron', 'Ballon', 'ballon.jeron@yahoo.com', '09876524355', 34, 22, '0', '', 1, 1, 1, 21400.00, 250.00, 21650.00, '2025-04-01 16:39:46'),
(10, 'MS2504089136', '2025-04-08', '2025-04-09', 'Mark', 'Santos', 'samosa.ikediezel@ue.edu.ph', '09374583254', 11, 11, '0', '', 0, 0, 0, 10000.00, 0.00, 10000.00, '2025-04-02 06:12:00'),
(16, 'AM2504106058', '2025-04-10', '2025-04-11', 'Ariel', 'Mermaid', 'ariel.mermaid@gmail.com', '09876524355', 32, 12, '0', '', 0, 0, 0, 26400.00, 0.00, 26400.00, '2025-04-02 06:59:07'),
(17, 'JF2504124932', '2025-04-12', '2025-04-13', 'jezelle', 'formentera', 'jez@gmail.com', '09876524355', 12, 9, '0', '', 3, 3, 3, 11000.00, 750.00, 11750.00, '2025-04-02 14:29:10'),
(18, 'AD2504277619', '2025-04-27', '2025-04-28', 'Andrei', 'Dionisio', 'dionisio.andrei@gmail.com', '09124563421', 21, 12, '0', '', 0, 0, 0, 19800.00, 0.00, 19800.00, '2025-04-03 17:12:43'),
(19, 'HS2505097924', '2025-05-09', '2025-05-10', 'Hermionie', 'Samosa', 'hermioniesamosa@gmail.com', '09375926381', 50, 5, '0', '', 0, 0, 0, 33000.00, 0.00, 33000.00, '2025-04-04 08:04:47');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `guest_reservation_code` varchar(255) DEFAULT NULL,
  `user_reservation_code` varchar(255) DEFAULT NULL,
  `payment_receipt` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `file_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `guest_reservation_code`, `user_reservation_code`, `payment_receipt`, `status`, `uploaded_at`, `file_path`) VALUES
(12, 26, NULL, NULL, '', 'Approved', '2025-02-26 15:21:50', 'uploads/payments/payment_1740583310_26.png'),
(13, 0, 'AM2504106058', NULL, 'asdfa12', 'Pending', '2025-04-02 07:02:25', 'uploads/payment_proofs/AM2504106058_1743577345.jpg'),
(14, 45, NULL, '20250402-10911C', 'iandk143', 'Pending', '2025-04-02 07:11:34', 'uploads/payment_proofs/20250402-10911C_1743577894.jpg'),
(15, 0, 'JF2504124932', NULL, '1231asfsaf', 'Pending', '2025-04-02 14:31:31', 'uploads/payment_proofs/JF2504124932_1743604291.jpg'),
(16, 0, 'AD2504277619', NULL, 'gdssdf54', 'Pending', '2025-04-03 17:13:03', 'uploads/payment_proofs/AD2504277619_1743700383.jpg'),
(17, 0, 'HS2505097924', NULL, 'ily143', 'Pending', '2025-04-04 08:05:24', 'uploads/payment_proofs/HS2505097924_1743753924.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `quantity` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `quantity`) VALUES
(1, 'UP AND DOWN HOUSE', 1),
(2, 'GLASS CABIN', 4),
(3, 'CONCRETE HOUSE', 1),
(4, 'CONCRETE ROOM', 4),
(5, 'TP HUT', 2),
(6, 'KUBO ROOM', 2),
(7, 'GROUP CABIN', 1),
(8, 'OPEN COTTAGE', 6),
(9, 'CANOPY TENT', 3);

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
  `reservation_code` varchar(255) NOT NULL,
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
(1, 60, '20250327-B8CEE6', '2025-03-29', '2025-03-30', 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', 42, 12, '0', '', 5, 7, 4, 32400.00, 1300.00, 33700.00, '2025-03-27 19:18:20'),
(2, 60, '20250331-4DD6DF', '2025-04-04', '2025-04-05', 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', 22, 22, '0', 'asdfsad12', 1, 1, 1, 26400.00, 250.00, 26650.00, '2025-03-31 16:31:29'),
(3, 60, '20250401-D8AF54', '2025-04-07', '2025-04-08', 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', 55, 12, '0', '', 5, 5, 5, 40200.00, 1250.00, 41450.00, '2025-04-01 16:41:41'),
(5, 60, '20250402-41ED23', '2025-04-09', '2025-04-10', 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', 42, 23, '0', '', 5, 7, 5, 39000.00, 1350.00, 40350.00, '2025-04-02 05:51:05'),
(7, 45, '20250402-10911C', '2025-04-26', '2025-04-27', 'Bosing', 'Narciso', 'samosa.ikediezel@ue.edu.ph', '09374583254', 2, 2, '0', '', 0, 0, 0, 12000.00, 0.00, 12000.00, '2025-04-02 07:11:07');

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
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_user_reservation` (`user_reservation_code`),
  ADD KEY `fk_guest_reservation` (`guest_reservation_code`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

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
  ADD UNIQUE KEY `reservation_code_2` (`reservation_code`),
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
-- AUTO_INCREMENT for table `guest_reservation`
--
ALTER TABLE `guest_reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `user_reservation`
--
ALTER TABLE `user_reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_guest_reservation` FOREIGN KEY (`guest_reservation_code`) REFERENCES `guest_reservation` (`reservation_code`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_reservation` FOREIGN KEY (`user_reservation_code`) REFERENCES `user_reservation` (`reservation_code`) ON UPDATE CASCADE;

--
-- Constraints for table `user_reservation`
--
ALTER TABLE `user_reservation`
  ADD CONSTRAINT `user_reservation_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
