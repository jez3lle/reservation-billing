-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2025 at 11:58 AM
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` int(10) UNSIGNED DEFAULT NULL COMMENT 'Timestamp when reservation expires'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guest_reservation`
--

INSERT INTO `guest_reservation` (`id`, `reservation_code`, `check_in`, `check_out`, `first_name`, `last_name`, `email`, `contact_number`, `adult_count`, `kid_count`, `tour_type`, `special_requests`, `extra_mattress`, `extra_pillow`, `extra_blanket`, `base_price`, `extras_price`, `total_price`, `created_at`, `expires_at`) VALUES
(1, 'KS2503296701', '2025-03-29', '2025-03-30', 'Keith', 'Samosa', 'ayk.example@yahoo.com', '09876524355', 19, 12, '0', '', 1, 2, 1, 18600.00, 300.00, 18900.00, '2025-03-27 19:08:52', 1744140539),
(2, 'IS2503297219', '2025-03-29', '2025-03-30', 'Ike', 'Samosa', 'ikediezel.samosa@gmail.com', '0987652435', 24, 12, '0', '', 2, 2, 2, 13400.00, 500.00, 13900.00, '2025-03-27 19:12:21', 1744140539),
(3, 'CS2504010512', '2025-04-01', '2025-04-02', 'ce', 'Samosa', 'ayk.example@yahoo.com', '09876524355', 22, 12, '0', '', 2, 1, 1, 12600.00, 400.00, 13000.00, '2025-03-31 15:55:54', 1744140539),
(4, 'MS2504029194', '2025-04-02', '2025-04-03', 'Mark', 'Santos', 'ayk.exam@yahoo.com', '09876524355', 22, 12, '0', 'sadfasfas', 1, 1, 1, 20400.00, 250.00, 20650.00, '2025-03-31 16:29:44', 1744140539),
(5, 'DC2504036459', '2025-04-03', '2025-04-04', 'Diezelike', 'Caceres', 'ayk.example@yahoo.com', '09374583254', 44, 12, '0', 'afasfasfas123', 3, 2, 2, 21400.00, 650.00, 22050.00, '2025-03-31 16:30:37', 1744140539),
(6, 'JT2504058962', '2025-04-05', '2025-04-06', 'Jade', 'Torreajs', 'torrejas.jade@ue.edu.ph', '09544646496', 31, 11, '0', '', 1, 1, 1, 25200.00, 250.00, 25450.00, '2025-04-01 15:23:55', 1744140539),
(8, 'JB2504068466', '2025-04-06', '2025-04-07', 'Jeron', 'Ballon', 'ballon.jeron@yahoo.com', '09876524355', 34, 22, '0', '', 1, 1, 1, 21400.00, 250.00, 21650.00, '2025-04-01 16:39:46', 1744140539),
(10, 'MS2504089136', '2025-04-08', '2025-04-09', 'Mark', 'Santos', 'samosa.ikediezel@ue.edu.ph', '09374583254', 11, 11, '0', '', 0, 0, 0, 10000.00, 0.00, 10000.00, '2025-04-02 06:12:00', 1744140539),
(16, 'AM2504106058', '2025-04-10', '2025-04-11', 'Ariel', 'Mermaid', 'ariel.mermaid@gmail.com', '09876524355', 32, 12, '0', '', 0, 0, 0, 26400.00, 0.00, 26400.00, '2025-04-02 06:59:07', 1744140539),
(17, 'JF2504124932', '2025-04-12', '2025-04-13', 'jezelle', 'formentera', 'jez@gmail.com', '09876524355', 12, 9, '0', '', 3, 3, 3, 11000.00, 750.00, 11750.00, '2025-04-02 14:29:10', 1744140539),
(18, 'AD2504277619', '2025-04-27', '2025-04-28', 'Andrei', 'Dionisio', 'dionisio.andrei@gmail.com', '09124563421', 21, 12, '0', '', 0, 0, 0, 19800.00, 0.00, 19800.00, '2025-04-03 17:12:43', 1744140539),
(19, 'HS2505097924', '2025-05-09', '2025-05-10', 'Hermionie', 'Samosa', 'hermioniesamosa@gmail.com', '09375926381', 50, 5, '0', '', 0, 0, 0, 33000.00, 0.00, 33000.00, '2025-04-04 08:04:47', 1744140539),
(20, 'KS2504166773', '2025-04-16', '2025-04-17', 'Keith', 'Samosa', 'k8.example@yahoo.com', '09125663432', 23, 24, '0', '', 1, 1, 1, 28200.00, 250.00, 28450.00, '2025-04-06 15:48:13', 1744140539),
(21, 'MZ2504220306', '2025-04-22', '2025-04-23', 'Mark', 'Zuckerberg', 'mark.zuckerberg@gmail.com', '09123123287', 12, 12, '0', '', 0, 0, 0, 16000.00, 0.00, 16000.00, '2025-04-08 16:04:48', 1744143321),
(22, 'KS2504230462', '2025-04-23', '2025-04-24', 'Keith', 'Samosa', 'ikediezel.samosa@gmail.com', '09124563421', 42, 12, '0', '', 0, 0, 0, 32400.00, 0.00, 32400.00, '2025-04-08 17:29:33', 1744144207),
(23, 'MZ2504285089', '2025-04-28', '2025-04-29', 'Mark', 'Zuckerberg', 'mark.zuckerberg@gmail.com', '09876524355', 32, 22, '0', '', 0, 0, 0, 32400.00, 0.00, 32400.00, '2025-04-09 06:00:06', 1744189214),
(24, 'DS2505036278', '2025-05-03', '2025-05-04', 'Diezelike', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '09125663432', 22, 12, 'whole_day', '', 0, 0, 0, 14000.00, 0.00, 14000.00, '2025-04-09 06:56:42', 1744356792);

-- --------------------------------------------------------

--
-- Table structure for table `p2_guest_reservation`
--

CREATE TABLE `p2_guest_reservation` (
  `id` int(11) NOT NULL,
  `booking_reference` varchar(20) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `adults` int(11) NOT NULL,
  `children` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `special_requests` text DEFAULT NULL,
  `payment_method` varchar(20) NOT NULL,
  `payment_details` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `p2_guest_reservation`
--

INSERT INTO `p2_guest_reservation` (`id`, `booking_reference`, `check_in`, `check_out`, `adults`, `children`, `total_amount`, `first_name`, `last_name`, `email`, `contact_number`, `special_requests`, `payment_method`, `payment_details`, `status`, `created_at`, `updated_at`) VALUES
(1, 'BOOK17438614201290', '2025-04-06', '2025-04-07', 2, 0, 1500.00, 'Keith', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '09125663432', '', 'bank-transfer', '{\"bank_transfer\":\"Bank transfer selected\"}', 'Confirmed', '2025-04-05 13:57:00', '2025-04-05 13:57:00'),
(2, 'BOOK17438615235434', '2025-04-06', '2025-04-07', 2, 0, 1000.00, 'Mark', 'Portugal', 'ayk.ex@yahoo.com', '09876524355', '', 'bank-transfer', '{\"bank_transfer\":\"Bank transfer selected\"}', 'Confirmed', '2025-04-05 13:58:43', '2025-04-05 13:58:43'),
(3, 'BOOK17438617801761', '0000-00-00', '0000-00-00', 2, 2, 2000.00, 'Diezel', 'Samosa', 'ayk@yahoo.com', '9124563421', '', 'gcash', '{\"gcash_number\":\"0912312321\",\"gcash_name\":\"Ike Samosa\"}', 'Confirmed', '2025-04-05 14:03:00', '2025-04-05 14:03:00'),
(4, 'BOOK17438618921389', '2025-04-07', '2025-04-08', 6, 3, 4000.00, 'Ariel', 'Princess', 'ariel.mermaid@gmail.com', '9876524355', '', 'bank-transfer', '{\"bank_transfer\":\"Bank transfer selected\"}', 'Confirmed', '2025-04-05 14:04:52', '2025-04-05 14:04:52'),
(5, 'P217438638365760', '2025-04-07', '2025-04-08', 6, 3, 4000.00, 'Ariel', 'Princess', 'ariel.mermaid@gmail.com', '9876524355', '', 'bank-transfer', '{\"bank_transfer\":\"Bank transfer selected\"}', 'Confirmed', '2025-04-05 14:37:16', '2025-04-05 14:37:16'),
(6, 'P217438643357667', '2025-04-10', '2025-04-11', 4, 3, 1500.00, 'Keith', 'Samosa', 'ayk@yahoo.com', '9876524355', '', 'bank-transfer', '{\"bank_transfer\":\"Bank transfer selected\"}', 'Confirmed', '2025-04-05 14:45:35', '2025-04-05 14:45:35'),
(8, 'P217438665616315', '2025-04-06', '2025-04-07', 2, 0, 1500.00, 'Ike', 'Narciso', 'diezel.example@yahoo.com', '09876524355', '', 'gcash', '{\"gcash_number\":\"0954532132\",\"gcash_name\":\"Ike Narciso\"}', 'Confirmed', '2025-04-05 15:22:41', '2025-04-05 15:22:41'),
(10, 'P217438668719597', '2025-04-06', '2025-04-07', 5, 3, 4000.00, 'Diezelike', 'Caceres', 'ayk@yahoo.com', '9876524355', '', 'bank-transfer', '{\"bank_transfer\":\"Bank transfer selected\"}', 'Confirmed', '2025-04-05 15:27:51', '2025-04-05 15:27:51'),
(11, 'P217438670397135', '2025-04-06', '2025-04-07', 2, 0, 2000.00, 'Ike', 'Reyes', 'ayk@yahoo.com', '9124563421', '', 'bank-transfer', '{\"bank_transfer\":\"Bank transfer selected\"}', 'Confirmed', '2025-04-05 15:30:39', '2025-04-05 15:30:39'),
(12, 'P217438671106203', '2025-04-06', '2025-04-07', 2, 0, 2000.00, 'Ike', 'Reyes', 'ayk@yahoo.com', '9124563421', '', 'bank-transfer', '{\"bank_transfer\":\"Bank transfer selected\"}', 'Confirmed', '2025-04-05 15:31:50', '2025-04-05 15:31:50'),
(13, 'P217438673253509', '2025-04-06', '2025-04-07', 2, 0, 4000.00, 'Mark', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '9125663432', '', 'bank-transfer', '{\"bank_transfer\":\"Bank transfer selected\"}', 'Confirmed', '2025-04-05 15:35:25', '2025-04-05 15:35:25'),
(14, 'P217438673603461', '2025-04-06', '2025-04-07', 2, 0, 4000.00, 'Mark', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '9125663432', '', 'bank-transfer', '{\"bank_transfer\":\"Bank transfer selected\"}', 'Confirmed', '2025-04-05 15:36:00', '2025-04-05 15:36:00'),
(15, 'P217438674448640', '2025-04-06', '2025-04-07', 2, 0, 4000.00, 'Mark', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '9125663432', '', 'bank-transfer', '{\"bank_transfer\":\"Bank transfer selected\"}', 'Confirmed', '2025-04-05 15:37:24', '2025-04-05 15:37:24'),
(16, 'P217438679846676', '2025-04-06', '2025-04-07', 2, 0, 2500.00, 'Cerike', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '9125663432', '', 'bank-transfer', '{\"bank_transfer\":\"Bank transfer selected\"}', 'Confirmed', '2025-04-05 15:46:24', '2025-04-05 15:46:24'),
(17, 'P217438680792588', '2025-04-06', '2025-04-07', 2, 0, 2500.00, 'Cerike', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '9125663432', '', 'bank-transfer', '{\"bank_transfer\":\"Bank transfer selected\"}', 'Confirmed', '2025-04-05 15:47:59', '2025-04-05 15:47:59'),
(18, 'P217438685503786', '2025-04-06', '2025-04-07', 2, 0, 4000.00, 'Keith', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '9125663432', '', 'gcash', '{\"gcash_number\":\"0954532132\",\"gcash_name\":\"Ike Samosa\"}', 'Confirmed', '2025-04-05 15:55:50', '2025-04-05 15:55:50'),
(19, 'P217438686944726', '2025-04-06', '2025-04-07', 2, 0, 1500.00, 'Ariel', 'Santos', 'ayk.example@yahoo.com', '9876524355', '', 'gcash', '{\"gcash_number\":\"0954532132\",\"gcash_name\":\"Ike Samosa\"}', 'Confirmed', '2025-04-05 15:58:14', '2025-04-05 15:58:14'),
(20, 'P217438688512564', '2025-04-07', '2025-04-08', 2, 0, 4000.00, 'Keith', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '9125663432', '', 'gcash', '{\"gcash_number\":\"0912312321\",\"gcash_name\":\"Ike Narciso\"}', 'Confirmed', '2025-04-05 16:00:51', '2025-04-05 16:00:51'),
(21, 'P217438691343962', '2025-04-07', '2025-04-08', 2, 0, 5500.00, 'Cerike', 'Reyes', 'ikediezel.samosa@gmail.com', '9124563421', 'asdfasdsdaf', 'gcash', '{\"gcash_number\":\"0954532132\",\"gcash_name\":\"Ike Samosa\"}', 'Confirmed', '2025-04-05 16:05:34', '2025-04-05 16:05:34'),
(22, 'P217438692705749', '2025-04-07', '2025-04-08', 2, 0, 1500.00, 'Keith', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '9124563421', 'asdf2', 'gcash', '{\"gcash_number\":\"0954532132\",\"gcash_name\":\"Ike Samosa\"}', 'Confirmed', '2025-04-05 16:07:50', '2025-04-05 16:07:50'),
(23, 'P217438693067782', '2025-04-07', '2025-04-08', 2, 0, 1500.00, 'Keith', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '9124563421', 'asdf2', 'gcash', '{\"gcash_number\":\"0954532132\",\"gcash_name\":\"Ike Samosa\"}', 'Confirmed', '2025-04-05 16:08:26', '2025-04-05 16:08:26'),
(24, 'P217438694041923', '2025-04-07', '2025-04-08', 2, 0, 2000.00, 'Ariel', 'Reyes', 'ayk@yahoo.com', '9124563421', '', 'gcash', '{\"gcash_number\":\"0912312321\",\"gcash_name\":\"Ike Samosa\"}', 'Confirmed', '2025-04-05 16:10:04', '2025-04-05 16:10:04'),
(25, 'P217438696995720', '2025-04-07', '2025-04-08', 2, 0, 1500.00, 'Keith', 'Reyes', 'ayk@yahoo.com', '9876524355', '', 'bank-transfer', '{\"bank_transfer\":\"Bank transfer selected\"}', 'Confirmed', '2025-04-05 16:14:59', '2025-04-05 16:14:59'),
(26, 'P217438698558976', '2025-04-07', '2025-04-08', 2, 0, 4000.00, 'Keith', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '9125663432', 'asdfasfasfasf123', 'gcash', '{\"gcash_number\":\"0912312321\",\"gcash_name\":\"Ike Samosa\"}', 'Confirmed', '2025-04-05 16:17:35', '2025-04-05 16:17:35'),
(27, 'P217438750819983', '2025-04-07', '2025-04-08', 2, 0, 1500.00, 'Keith', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '9125663432', '', 'gcash', '{\"gcash_number\":\"0954532132\",\"gcash_name\":\"Ike Samosa\"}', 'Confirmed', '2025-04-05 17:44:41', '2025-04-05 17:44:41'),
(28, 'P217438752133810', '2025-04-07', '2025-04-08', 2, 0, 5500.00, 'Keith', 'Reyes', 'ayk.example@yahoo.com', '9125663432', '', 'bank-transfer', '{\"bank_transfer\":\"Bank transfer selected\"}', 'Confirmed', '2025-04-05 17:46:53', '2025-04-05 17:46:53'),
(29, 'P217438754169812', '2025-04-07', '2025-04-08', 2, 0, 4000.00, 'Ike', 'Samosa', 'ayk.example@yahoo.com', '9125663432', '', 'gcash', '{\"gcash_number\":\"0912312321\",\"gcash_name\":\"Ike Samosa\"}', 'Confirmed', '2025-04-05 17:50:16', '2025-04-05 17:50:16'),
(30, 'P217438756103324', '2025-04-08', '2025-04-09', 2, 0, 1500.00, 'Keith', 'Reyes', 'samosa.ikediezel@ue.edu.ph', '9374583254', '', 'gcash', '{\"gcash_number\":\"0912312321\",\"gcash_name\":\"Ike Narciso\"}', 'Confirmed', '2025-04-05 17:53:30', '2025-04-05 17:53:30'),
(31, 'P217438758096500', '2025-04-23', '2025-04-24', 2, 2, 9000.00, 'Keith', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '9125663432', '', 'gcash', 'GCash Number: 0912312321, Name: Ike Samosa', 'Confirmed', '2025-04-05 17:56:49', '2025-04-05 17:56:49'),
(32, 'P217438758663090', '2025-04-23', '2025-04-24', 2, 2, 9000.00, 'Keith', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '9125663432', '', 'gcash', 'GCash Number: 0912312321, Name: Ike Samosa', 'Confirmed', '2025-04-05 17:57:46', '2025-04-05 17:57:46'),
(33, 'P217438762358296', '2025-04-07', '2025-04-08', 2, 0, 4000.00, 'Keith', 'Samosa', 'ikediezel.samosa@gmail.com', '9125663432', '', 'gcash', 'GCash Number: 0912312321, Name: Ike Samosa', 'Confirmed', '2025-04-05 18:03:55', '2025-04-05 18:03:55'),
(34, 'P217438763241668', '2025-04-07', '2025-04-08', 2, 0, 4000.00, 'Keith', 'Samosa', 'ikediezel.samosa@gmail.com', '9125663432', '', 'gcash', 'GCash Number: 0912312321, Name: Ike Samosa', 'Confirmed', '2025-04-05 18:05:24', '2025-04-05 18:05:24'),
(35, 'P217438764131992', '2025-04-07', '2025-04-08', 2, 0, 1500.00, 'Keith', 'Samosa', 'ikediezel.samosa@gmail.com', '9125663432', '', 'gcash', 'GCash Number: 0954532132, Name: Ike Narciso', 'Confirmed', '2025-04-05 18:06:53', '2025-04-05 18:06:53'),
(36, 'P217438764525204', '2025-04-07', '2025-04-08', 2, 0, 1500.00, 'Keith', 'Samosa', 'ikediezel.samosa@gmail.com', '9125663432', '', 'gcash', 'GCash Number: 0954532132, Name: Ike Narciso', 'Confirmed', '2025-04-05 18:07:32', '2025-04-05 18:07:32'),
(37, 'P217438766369849', '2025-04-07', '2025-04-08', 2, 0, 4000.00, 'Ariel', 'Santos', 'ayk.example@yahoo.com', '9125663432', '', 'gcash', 'GCash Number: 0954532132, Name: Ike Samosa', 'Confirmed', '2025-04-05 18:10:36', '2025-04-05 18:10:36'),
(38, 'P217438768901575', '2025-04-07', '2025-04-08', 2, 0, 1500.00, 'Cerike', 'Reyes', 'samosa.ikediezel@ue.edu.ph', '9125663432', '', 'gcash', 'GCash Number: 0912312321, Name: Ike Samosa', 'Confirmed', '2025-04-05 18:14:50', '2025-04-05 18:14:50'),
(39, 'P217438769146015', '2025-04-07', '2025-04-08', 2, 0, 1500.00, 'Cerike', 'Reyes', 'samosa.ikediezel@ue.edu.ph', '9125663432', '', 'gcash', 'GCash Number: 0912312321, Name: Ike Samosa', 'Confirmed', '2025-04-05 18:15:14', '2025-04-05 18:15:14'),
(40, 'P217438771336150', '2025-04-07', '2025-04-08', 2, 0, 4000.00, 'Ariel', 'Santos', 'samosa.ikediezel@ue.edu.ph', '9125663432', '', 'gcash', 'GCash Number: 0954532132, Name: Ike Narciso', 'Confirmed', '2025-04-05 18:18:53', '2025-04-05 18:18:53'),
(41, 'P217438772167494', '2025-04-07', '2025-04-08', 2, 0, 4000.00, 'Diezelike', 'Portugal', 'ayk.example@yahoo.com', '9125663432', '', 'gcash', 'GCash Number: 0954532132, Name: Ike Samosa', 'Confirmed', '2025-04-05 18:20:16', '2025-04-05 18:20:16'),
(42, 'P217438772512104', '2025-04-07', '2025-04-08', 2, 0, 4000.00, 'Diezelike', 'Portugal', 'ayk.example@yahoo.com', '9125663432', '', 'gcash', 'GCash Number: 0954532132, Name: Ike Samosa', 'Confirmed', '2025-04-05 18:20:51', '2025-04-05 18:20:51'),
(43, 'P217438775408170', '2025-04-07', '2025-04-08', 2, 0, 1500.00, 'Cerike', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '9374583254', '', 'bank-transfer', 'Bank transfer selected', 'Confirmed', '2025-04-05 18:25:40', '2025-04-05 18:25:40'),
(44, 'P217438775789315', '2025-04-07', '2025-04-08', 2, 0, 1500.00, 'Cerike', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '9374583254', '', 'bank-transfer', 'Bank transfer selected', 'Confirmed', '2025-04-05 18:26:18', '2025-04-05 18:26:18'),
(45, 'P217438778252320', '2025-04-24', '2025-04-25', 6, 2, 6500.00, 'Diezelike', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '9374583254', '', 'gcash', 'GCash Number: 0954532132, Name: Ike Samosa', 'Confirmed', '2025-04-05 18:30:25', '2025-04-05 18:30:25'),
(46, 'P217439488655821', '2025-04-07', '2025-04-08', 2, 0, 5000.00, 'Diezelike', 'Samosa', 'ikediezel.samosa@gmail.com', '9125663432', 'clean the room please, thank you', 'gcash', 'GCash Number: 0954532132, Name: Ike Samosa', 'Confirmed', '2025-04-06 14:14:25', '2025-04-06 14:14:25'),
(47, 'P217439491767761', '2025-04-07', '2025-04-08', 2, 0, 4000.00, 'Ariel', 'Reyes', 'ayk.example@yahoo.com', '9125663432', '', 'bank-transfer', 'Bank transfer selected', 'Confirmed', '2025-04-06 14:19:36', '2025-04-06 14:19:36'),
(48, 'P217439493328585', '2025-04-07', '2025-04-08', 2, 0, 4800.00, 'Keith', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '9125663432', '', 'gcash', 'GCash Number: 0912312321, Name: Ike Narciso', 'Confirmed', '2025-04-06 14:22:12', '2025-04-06 14:22:12'),
(49, 'P217439494457924', '2025-04-07', '2025-04-08', 2, 0, 4800.00, 'Keith', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '9125663432', '', 'gcash', 'GCash Number: 0912312321, Name: Ike Narciso', 'Confirmed', '2025-04-06 14:24:05', '2025-04-06 14:24:05'),
(50, 'P217439497267865', '2025-04-07', '2025-04-08', 2, 0, 1500.00, 'Cerike', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '9374583254', '', 'bank-transfer', 'Bank transfer selected', 'Confirmed', '2025-04-06 14:28:46', '2025-04-06 14:28:46'),
(51, 'P217439515447582', '2025-04-07', '2025-04-08', 2, 0, 3500.00, 'Keith', 'Reyes', 'samosa.ikediezel@ue.edu.ph', '09374583254', 'asfsaf', 'bank-transfer', NULL, 'Confirmed', '2025-04-06 14:59:04', '2025-04-06 14:59:04'),
(53, 'P217439529496866', '2025-04-07', '2025-04-08', 2, 0, 1500.00, 'Keith', 'Samosa', 'ikediezel.samosa@gmail.com', '9125663432', '', 'bank-transfer', 'Bank transfer selected', 'Confirmed', '2025-04-06 15:22:29', '2025-04-06 15:22:29'),
(54, 'P217439539273021', '2025-04-07', '2025-04-08', 2, 0, 2500.00, 'Ike', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '9374583254', '', 'gcash', 'GCash Number: 0954532132, Name: Ike Samosa', 'Confirmed', '2025-04-06 15:38:47', '2025-04-06 15:38:47'),
(55, 'P217439545875940', '2025-04-07', '2025-04-08', 2, 0, 1500.00, 'Ike', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '9374583254', '', 'gcash', 'GCash Number: 0954532132, Name: Ike Samosa', 'Confirmed', '2025-04-06 15:49:47', '2025-04-06 15:49:47'),
(56, 'P217439561651737', '2025-04-09', '2025-04-10', 2, 2, 4000.00, 'Keith', 'Samosa', 'ayk.example@yahoo.com', '9374583254', 'Clean the room please', 'bank-transfer', 'Bank transfer selected', 'Confirmed', '2025-04-06 16:16:05', '2025-04-06 16:16:05'),
(59, 'P217440421675416', '2025-04-10', '2025-04-11', 4, 2, 2000.00, 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '9374583254', '', 'gcash', 'GCash Number: 0954532132, Name: Ike Samosa', 'Confirmed', '2025-04-07 16:09:27', '2025-04-07 16:09:27'),
(61, 'P217440471902436', '2025-04-10', '2025-04-11', 2, 0, 2000.00, 'Andrei', 'Dionisio', 'dionisio.andrei@gmail.com', '9374583254', '', 'gcash', 'GCash Number: 0954532132, Name: Andrei Dionisio', 'Confirmed', '2025-04-07 17:33:10', '2025-04-07 17:33:10'),
(62, 'P217440492781201', '2025-04-09', '2025-04-10', 3, 0, 1500.00, 'Keith', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '9374583254', '', 'gcash', 'GCash Number: 0954532132, Name: Keith Samosa', 'Confirmed', '2025-04-07 18:07:58', '2025-04-07 18:07:58'),
(63, 'P217440508717983', '2025-04-11', '2025-04-12', 2, 0, 1500.00, 'Ike', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '9374583254', '', 'gcash', 'GCash Number: 0954532132, Name: Ike Samosa', 'Pending', '2025-04-07 18:34:31', '2025-04-07 18:34:31');

-- --------------------------------------------------------

--
-- Table structure for table `p2_guest_reservation_room`
--

CREATE TABLE `p2_guest_reservation_room` (
  `id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_per_night` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `p2_guest_reservation_room`
--

INSERT INTO `p2_guest_reservation_room` (`id`, `reservation_id`, `room_id`, `quantity`, `price_per_night`) VALUES
(1, 1, 2, 1, 1500.00),
(2, 2, 5, 1, 1000.00),
(3, 3, 4, 1, 2000.00),
(4, 4, 3, 1, 4000.00),
(5, 5, 3, 1, 4000.00),
(6, 6, 8, 1, 1500.00),
(8, 8, 2, 1, 1500.00),
(10, 10, 1, 1, 4000.00),
(11, 11, 4, 1, 2000.00),
(12, 12, 4, 1, 2000.00),
(13, 13, 3, 1, 4000.00),
(14, 14, 3, 1, 4000.00),
(15, 15, 3, 1, 4000.00),
(16, 16, 2, 1, 1500.00),
(17, 16, 5, 1, 1000.00),
(18, 17, 2, 1, 1500.00),
(19, 17, 5, 1, 1000.00),
(20, 18, 1, 1, 4000.00),
(21, 19, 2, 1, 1500.00),
(22, 20, 1, 1, 4000.00),
(23, 21, 2, 1, 1500.00),
(24, 21, 3, 1, 4000.00),
(25, 22, 2, 1, 1500.00),
(26, 23, 2, 1, 1500.00),
(27, 24, 2, 1, 2000.00),
(28, 25, 2, 1, 1500.00),
(29, 26, 3, 1, 4000.00),
(30, 27, 2, 1, 1500.00),
(31, 28, 2, 1, 1500.00),
(32, 28, 3, 1, 4000.00),
(33, 29, 1, 1, 4000.00),
(34, 30, 2, 1, 1500.00),
(35, 31, 3, 1, 4000.00),
(36, 31, 4, 1, 2000.00),
(37, 31, 7, 1, 3000.00),
(38, 32, 3, 1, 4000.00),
(39, 32, 4, 1, 2000.00),
(40, 32, 7, 1, 3000.00),
(41, 33, 3, 1, 4000.00),
(42, 34, 3, 1, 4000.00),
(43, 35, 2, 1, 1500.00),
(44, 36, 2, 1, 1500.00),
(45, 37, 1, 1, 4000.00),
(46, 38, 2, 1, 1500.00),
(47, 39, 2, 1, 1500.00),
(48, 40, 1, 1, 4000.00),
(49, 41, 3, 1, 4000.00),
(50, 42, 3, 1, 4000.00),
(51, 43, 2, 1, 1500.00),
(52, 44, 2, 1, 1500.00),
(53, 45, 3, 1, 6500.00),
(54, 46, 1, 1, 4000.00),
(55, 46, 5, 1, 1000.00),
(56, 47, 3, 1, 4000.00),
(57, 48, 1, 1, 4000.00),
(58, 48, 9, 1, 800.00),
(59, 49, 1, 1, 4000.00),
(60, 49, 9, 1, 800.00),
(61, 50, 2, 1, 1500.00),
(62, 51, 2, 1, 3500.00),
(63, 53, 2, 1, 1500.00),
(64, 54, 2, 1, 1500.00),
(65, 54, 5, 1, 1000.00),
(66, 55, 2, 1, 1500.00),
(67, 56, 3, 1, 4000.00),
(68, 59, 4, 1, 2000.00),
(70, 61, 2, 1, 2000.00),
(71, 62, 6, 1, 1500.00),
(72, 63, 2, 1, 1500.00);

-- --------------------------------------------------------

--
-- Table structure for table `p2_user_reservation`
--

CREATE TABLE `p2_user_reservation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `p2_user_reservation_rooms`
--

CREATE TABLE `p2_user_reservation_rooms` (
  `id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `room_quantity` int(5) NOT NULL DEFAULT 1,
  `price_per_night` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(17, 0, 'HS2505097924', NULL, 'ily143', 'Pending', '2025-04-04 08:05:24', 'uploads/payment_proofs/HS2505097924_1743753924.jpg'),
(18, 0, 'KS2504166773', NULL, 'asdfa12a', 'Pending', '2025-04-06 15:48:54', 'uploads/payment_proofs/KS2504166773_1743954534.png'),
(19, 0, 'MZ2504220306', NULL, NULL, 'Pending', '2025-04-08 17:01:06', ''),
(20, 0, 'KS2504230462', NULL, '', 'Rejected', '2025-04-08 17:29:40', ''),
(21, 60, NULL, '20250408-8CF8D1', 'TEMP-73A7055847', 'Approved', '2025-04-08 20:50:48', 'uploads/payment_proofs/20250408-8CF8D1_1744145448_678a60f86ef754.81329459.png'),
(22, 60, NULL, '20250408-9609F6', '', 'Rejected', '2025-04-08 21:34:33', ''),
(23, 0, 'MZ2504285089', NULL, '', 'Pending', '2025-04-09 06:00:14', ''),
(24, 45, NULL, '20250409-DF198A', '', 'Pending', '2025-04-09 06:20:06', ''),
(25, 45, NULL, '20250409-2CDC85', 'TEMP-73A7055847', 'Approved', '2025-04-09 06:46:17', 'uploads/payment_proofs/20250409-2CDC85_1744181177_678a75f92fbe71.37537824.jpg'),
(26, 45, NULL, '20250409-993975', 'asfsa214', 'Approved', '2025-04-09 08:53:10', 'uploads/payment_proofs/20250409-993975_1744188790_678a60f86ef754.81329459.png'),
(27, 0, 'DS2505036278', NULL, 'TEMP-73A7055847', 'Approved', '2025-04-09 07:33:12', 'uploads/payment_proofs/DS2505036278_1744183992_678a60f86ef754.81329459.png'),
(36, 60, NULL, '20250409-C330D3', 'TEMP-73A7055847', 'Approved', '2025-04-09 08:54:08', 'uploads/payment_proofs/20250409-C330D3_1744188848_678a75f92fbe71.37537824.jpg'),
(37, 60, NULL, '20250409-DFA0A3', 'fdhdfg12', 'Approved', '2025-04-09 09:19:17', 'uploads/payment_proofs/20250409-DFA0A3_1744190357_678a60f86ef754.81329459.png');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `day_tour_price` decimal(10,2) NOT NULL,
  `night_tour_price` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `quantity` int(12) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `day_tour_price`, `night_tour_price`, `description`, `quantity`, `image`, `status`) VALUES
(1, 'UP AND DOWN HOUSE', 4000.00, 6500.00, 'Equip with own kitchen, own cr, and bedroom. Good for 10-15pax', 1, 'images/up_and_down_house_main.jpg', 'Available'),
(2, 'GLASS CABIN', 1500.00, 2000.00, 'Perfect for couple or small family. Good for 2-4pax', 2, 'images/glass_cabin_main.jpg', 'Available'),
(3, 'CONCRETE HOUSE', 4000.00, 6500.00, 'Equip with own kitchen, own cr, and bedroom with AC. Good for 10-15pax', 1, 'images/concrete_house_main.jpg', 'Available'),
(4, 'CONCRETE ROOM', 2000.00, 2500.00, 'Equip with own cr, and bedroom. Good for 4-6pax', 4, 'images/concrete_room_main.jpg', 'Available'),
(5, 'TP HUT', 1000.00, 1500.00, 'Perfect for couple or small family. Good for 2-4pax', 2, 'images/tp_hut_main.jpg', 'Available'),
(6, 'KUBO ROOM', 1500.00, 2000.00, 'Perfect for couple or small family. Good for 2-4pax', 1, 'images/kubo_room_main.jpg', 'Available'),
(7, 'GROUP CABIN', 3000.00, 6000.00, 'Perfect for group of friends and privacy. Good for 6-12pax', 1, 'images/group_cabin_main.jpg', 'Available'),
(8, 'OPEN COTTAGE', 1500.00, 2000.00, 'Perfect for family gathering or friend hangouts. Good for 10-15pax', 6, 'images/open_cottage_main.jpg', 'Available'),
(9, 'CANOPY TENT', 800.00, 1300.00, 'Perfect for family gathering or friend hangouts. Good for 10pax', 3, 'images/canopy_tent_main.jpg', 'Available');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` int(10) UNSIGNED DEFAULT NULL COMMENT 'Timestamp when reservation expires'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_reservation`
--

INSERT INTO `user_reservation` (`id`, `user_id`, `reservation_code`, `check_in`, `check_out`, `first_name`, `last_name`, `email`, `contact_number`, `adult_count`, `kid_count`, `tour_type`, `special_requests`, `extra_mattress`, `extra_pillow`, `extra_blanket`, `total_price`, `extras_total`, `total_amount`, `created_at`, `expires_at`) VALUES
(1, 60, '20250327-B8CEE6', '2025-03-29', '2025-03-30', 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', 42, 12, '0', '', 5, 7, 4, 32400.00, 1300.00, 33700.00, '2025-03-27 19:18:20', 1744140539),
(2, 60, '20250331-4DD6DF', '2025-04-04', '2025-04-05', 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', 22, 22, '0', 'asdfsad12', 1, 1, 1, 26400.00, 250.00, 26650.00, '2025-03-31 16:31:29', 1744140539),
(3, 60, '20250401-D8AF54', '2025-04-07', '2025-04-08', 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', 55, 12, '0', '', 5, 5, 5, 40200.00, 1250.00, 41450.00, '2025-04-01 16:41:41', 1744140539),
(5, 60, '20250402-41ED23', '2025-04-09', '2025-04-10', 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', 42, 23, '0', '', 5, 7, 5, 39000.00, 1350.00, 40350.00, '2025-04-02 05:51:05', 1744140539),
(7, 45, '20250402-10911C', '2025-04-26', '2025-04-27', 'Bosing', 'Narciso', 'samosa.ikediezel@ue.edu.ph', '09374583254', 2, 2, '0', '', 0, 0, 0, 12000.00, 0.00, 12000.00, '2025-04-02 07:11:07', 1744140539),
(8, 60, '20250408-8CF8D1', '2025-04-25', '2025-04-26', 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', 24, 24, '0', '', 0, 0, 0, 18200.00, 0.00, 18200.00, '2025-04-08 20:34:06', 1744156337),
(9, 60, '20250408-9609F6', '2025-04-29', '2025-04-30', 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', 32, 12, '0', '', 1, 1, 1, 26400.00, 250.00, 26650.00, '2025-04-08 21:33:57', 1744158872),
(10, 45, '20250409-DF198A', '2025-04-20', '2025-04-21', 'Bosing', 'Caceres', 'samosa.ikediezel@ue.edu.ph', '09374583254', 42, 5, '0', '', 0, 0, 0, 28200.00, 0.00, 28200.00, '2025-04-09 06:20:01', 1744190406),
(11, 45, '20250409-2CDC85', '2025-04-30', '2025-05-01', 'Bosing', 'Narciso', 'samosa.ikediezel@ue.edu.ph', '09374583254', 27, 18, '0', '', 0, 0, 0, 27000.00, 0.00, 27000.00, '2025-04-09 06:45:36', 1744353977),
(12, 45, '20250409-993975', '2025-05-02', '2025-05-03', 'Bosing', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '09374583254', 20, 10, 'night_tour', '', 0, 0, 0, 11000.00, 0.00, 11000.00, '2025-04-09 06:46:50', 1744361590),
(14, 60, '20250409-C330D3', '2025-04-13', '2025-04-14', 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', 32, 0, '0', '', 0, 0, 0, 13000.00, 0.00, 13000.00, '2025-04-09 08:47:59', 1744361648),
(15, 60, '20250409-DFA0A3', '2025-05-04', '2025-05-05', 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', 40, 0, '0', '', 0, 0, 0, 17000.00, 0.00, 17000.00, '2025-04-09 09:18:29', 1744363157);

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
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_expires_at` (`expires_at`);

--
-- Indexes for table `p2_guest_reservation`
--
ALTER TABLE `p2_guest_reservation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_reference` (`booking_reference`);

--
-- Indexes for table `p2_guest_reservation_room`
--
ALTER TABLE `p2_guest_reservation_room`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `p2_guest_reservation_room_ibfk_1` (`reservation_id`);

--
-- Indexes for table `p2_user_reservation`
--
ALTER TABLE `p2_user_reservation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Indexes for table `p2_user_reservation_rooms`
--
ALTER TABLE `p2_user_reservation_rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservation_id` (`reservation_id`),
  ADD KEY `idx_p2_user_reservation_rooms_room` (`room_id`);

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
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_expires_at` (`expires_at`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `p2_guest_reservation`
--
ALTER TABLE `p2_guest_reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `p2_guest_reservation_room`
--
ALTER TABLE `p2_guest_reservation_room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `p2_user_reservation`
--
ALTER TABLE `p2_user_reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `p2_user_reservation_rooms`
--
ALTER TABLE `p2_user_reservation_rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `p2_guest_reservation_room`
--
ALTER TABLE `p2_guest_reservation_room`
  ADD CONSTRAINT `p2_guest_reservation_room_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `p2_guest_reservation` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `p2_guest_reservation_room_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `p2_user_reservation`
--
ALTER TABLE `p2_user_reservation`
  ADD CONSTRAINT `p2_user_reservation_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `p2_user_reservation_ibfk_2` FOREIGN KEY (`reservation_id`) REFERENCES `p2_guest_reservation` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_guest_reservation` FOREIGN KEY (`guest_reservation_code`) REFERENCES `guest_reservation` (`reservation_code`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_reservation` FOREIGN KEY (`user_reservation_code`) REFERENCES `user_reservation` (`reservation_code`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
