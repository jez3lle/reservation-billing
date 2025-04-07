-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2025 at 09:35 PM
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
(1, 'admin', '$2y$10$ldG1WlEtcQPoX9Sy6nCuMeTrdgjHmlUXgQIzyiMO//Zr/FUsVcisu'),
(2, 'JEZ', 'ULOL');

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'Admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Admin Name', 'admin@example.com', '$2y$10$eImiTXuWVxfM37uY4JANjQ==', 'Admin'),
(2, 'Jezelle F', 'formenterajezelle@gmail.com', '$2y$10$K9O5UpLmAPW4tPoT0UVHhOx1IAG2f9Qz9V4ybk2F81nTLHxkqZ1Y6', 'Admin'),
(3, 'Jezelle Formentera', 'admin@gmail.com', '$2y$10$K9O5UpLmAPW4tPoT0UVHhOx1IAG2f9Qz9V4ybk2F81nTLHxkqZ1Y6', 'admin'),
(4, 'Lady Gaga', 'ladygaga@gmail.com', 'LADYGAGA123!', 'Admin'),
(5, 'LADY GAGA', 'jezelleformentera21@gmail.com', '$2y$10$wFZ2d9yoc07HE9AveGtKYORJavkolN8V6ChXjMJihhaqEAeFHJ1/q', 'Admin');

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
  `status` varchar(50) DEFAULT 'Pending',
  `transaction_number` varchar(100) NOT NULL,
  `proof_of_payment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guest_reservation`
--

INSERT INTO `guest_reservation` (`id`, `reservation_code`, `check_in`, `check_out`, `first_name`, `last_name`, `email`, `contact_number`, `adult_count`, `kid_count`, `tour_type`, `special_requests`, `extra_mattress`, `extra_pillow`, `extra_blanket`, `base_price`, `extras_price`, `total_price`, `created_at`, `status`, `transaction_number`, `proof_of_payment`) VALUES
(1, 'KS2503296701', '2025-03-29', '2025-03-30', 'Keith', 'Samosa', 'ayk.example@yahoo.com', '09876524355', 19, 12, '0', '', 1, 2, 1, 18600.00, 300.00, 18900.00, '2025-03-27 19:08:52', 'Rejected', '', NULL),
(2, 'IS2503297219', '2025-03-29', '2025-03-30', 'Ike', 'Samosa', 'ikediezel.samosa@gmail.com', '0987652435', 24, 12, '0', '', 2, 2, 2, 13400.00, 500.00, 13900.00, '2025-03-27 19:12:21', 'Rejected', '', NULL),
(3, 'CS2504010512', '2025-04-01', '2025-04-02', 'ce', 'Samosa', 'ayk.example@yahoo.com', '09876524355', 22, 12, '0', '', 2, 1, 1, 12600.00, 400.00, 13000.00, '2025-03-31 15:55:54', 'Rejected', '', NULL),
(4, 'MS2504029194', '2025-04-02', '2025-04-03', 'Mark', 'Santos', 'ayk.exam@yahoo.com', '09876524355', 22, 12, '0', 'sadfasfas', 1, 1, 1, 20400.00, 250.00, 20650.00, '2025-03-31 16:29:44', 'Rejected', '', NULL),
(5, 'DC2504036459', '2025-04-03', '2025-04-04', 'Diezelike', 'Caceres', 'ayk.example@yahoo.com', '09374583254', 44, 12, '0', 'afasfasfas123', 3, 2, 2, 21400.00, 650.00, 22050.00, '2025-03-31 16:30:37', 'Rejected', '', NULL),
(6, 'JT2504058962', '2025-04-05', '2025-04-06', 'Jade', 'Torreajs', 'torrejas.jade@ue.edu.ph', '09544646496', 31, 11, '0', '', 1, 1, 1, 25200.00, 250.00, 25450.00, '2025-04-01 15:23:55', 'Rejected', '', NULL),
(8, 'JB2504068466', '2025-04-06', '2025-04-07', 'Jeron', 'Ballon', 'ballon.jeron@yahoo.com', '09876524355', 34, 22, '0', '', 1, 1, 1, 21400.00, 250.00, 21650.00, '2025-04-01 16:39:46', 'Rejected', '', NULL),
(10, 'MS2504089136', '2025-04-08', '2025-04-09', 'Mark', 'Santos', 'samosa.ikediezel@ue.edu.ph', '09374583254', 11, 11, '0', '', 0, 0, 0, 10000.00, 0.00, 10000.00, '2025-04-02 06:12:00', 'Rejected', '', NULL),
(16, 'AM2504106058', '2025-04-10', '2025-04-11', 'Ariel', 'Mermaid', 'ariel.mermaid@gmail.com', '09876524355', 32, 12, '0', '', 0, 0, 0, 26400.00, 0.00, 26400.00, '2025-04-02 06:59:07', 'Approved', '', NULL),
(17, 'JF2504124932', '2025-04-12', '2025-04-13', 'jezelle', 'formentera', 'jez@gmail.com', '09876524355', 12, 9, '0', '', 3, 3, 3, 11000.00, 750.00, 11750.00, '2025-04-02 14:29:10', 'Rejected', '', NULL),
(18, 'AD2504277619', '2025-04-27', '2025-04-28', 'Andrei', 'Dionisio', 'dionisio.andrei@gmail.com', '09124563421', 21, 12, '0', '', 0, 0, 0, 19800.00, 0.00, 19800.00, '2025-04-03 17:12:43', 'Approved', '', NULL),
(19, 'HS2505097924', '2025-05-09', '2025-05-10', 'Hermionie', 'Samosa', 'hermioniesamosa@gmail.com', '09375926381', 50, 5, '0', '', 0, 0, 0, 33000.00, 0.00, 33000.00, '2025-04-04 08:04:47', 'Rejected', '', NULL),
(20, 'AC2504246025', '2025-04-24', '2025-04-25', 'Anne', 'Curtis', 'annecursti@gmail.com', '09123456789', 12, 12, '0', '', 2, 2, 2, 16000.00, 500.00, 16500.00, '2025-04-04 18:15:20', 'Approved', '808080080808', 'proof_of_payment/AC2504246025_1743790744.jpg'),
(21, 'GH2504253618', '2025-04-25', '2025-04-26', 'Gigi', 'Hadid', 'jezelleformentera21@gmail.com', '09123456789', 12, 12, '0', '', 3, 2, 2, 16000.00, 650.00, 16650.00, '2025-04-04 18:56:56', 'Approved', '928312391823', 'proof_of_payment/GH2504253618_1743793068.jpg'),
(22, 'KJ2504288142', '2025-04-28', '2025-04-29', 'Kendall', 'Jenner', 'jezelleformentera21@gmail.com', '09123456789', 12, 12, '0', '', 2, 2, 2, 16000.00, 500.00, 16500.00, '2025-04-04 19:32:31', 'Approved', '54231238126431', 'proof_of_payment/KJ2504288142_1743795173.jpg'),
(23, 'HB2505016315', '2025-05-01', '2025-05-02', 'Hailey', 'Bieber', 'jezelleformentera21@gmail.com', '09123456789', 12, 12, '0', '', 1, 1, 1, 16000.00, 250.00, 16250.00, '2025-04-04 19:44:19', 'Approved', '12131231312331', 'proof_of_payment/HB2505016315_1743795876.jpg'),
(24, 'KB2505037066', '2025-05-03', '2025-05-04', 'Kylie', 'Bieber', 'formenterajezelle@gmail.com', '09123456789', 12, 12, '0', '', 0, 0, 0, 16000.00, 0.00, 16000.00, '2025-04-04 20:36:27', 'Confirmed', '1312321424124131234', 'proof_of_payment/KB2505037066_1743799002.jpg'),
(25, 'DS2504143015', '2025-04-14', '2025-04-15', 'Drew', 'Starkey', 'formenterajezelle@gmail.com', '09123456789', 12, 12, '0', '', 0, 0, 0, 16000.00, 0.00, 16000.00, '2025-04-04 21:22:24', 'Confirmed', '3124124323313', 'proof_of_payment/DS2504143015_1743801754.jpg'),
(26, 'TC2504184720', '2025-04-18', '2025-04-19', 'Timothee', 'Chalamet', 'jezelleformentera21@gmail.com', '09123456789', 12, 12, '0', 'None', 2, 2, 0, 16000.00, 400.00, 16400.00, '2025-04-04 21:31:33', 'Approved', '423424234242143', 'proof_of_payment/TC2504184720_1743802307.jpg'),
(27, 'JP2504202166', '2025-04-20', '2025-04-21', 'John', 'Pork', 'jezelleformentera21@gmail.com', '09123456789', 13, 11, '0', 'Hehe', 1, 1, 1, 16000.00, 250.00, 16250.00, '2025-04-04 21:42:21', 'Approved', '24242142141', 'proof_of_payment/JP2504202166_1743802952.jpg'),
(28, 'LR2504230708', '2025-04-23', '2025-04-24', 'Lily', 'Rose', 'jezelleformentera21@gmail.com', '09123456789', 14, 9, '0', 'What', 2, 2, 2, 16000.00, 500.00, 16500.00, '2025-04-04 21:45:40', 'Approved', '121312342134234', 'proof_of_payment/LR2504230708_1743803156.jpg'),
(29, 'FO2504290478', '2025-04-29', '2025-04-30', 'Frank', 'Ocean', 'jezelleformentera21@gmail.com', '09123456789', 13, 10, '0', 'ano', 3, 1, 1, 16000.00, 550.00, 16550.00, '2025-04-04 21:49:59', 'Approved', '1324321422413', 'proof_of_payment/FO2504290478_1743803410.jpg'),
(30, 'JP2505056911', '2025-05-05', '2025-05-06', 'Jake', 'Peralta', 'jezelleformentera21@gmail.com', '09123456789', 12, 12, '0', 'Wa', 2, 2, 1, 16000.00, 450.00, 16450.00, '2025-04-04 22:03:59', 'Approved', '121312323123', 'proof_of_payment/JP2505056911_1743804274.jpg'),
(31, 'AS2505083944', '2025-05-08', '2025-05-09', 'Amy', 'Santiago', 'jezelleformentera21@gmail.com', '09123456789', 12, 12, '0', '', 2, 2, 2, 16000.00, 500.00, 16500.00, '2025-04-04 22:15:43', 'Approved', '21332131', 'proof_of_payment/AS2505083944_1743804961.jpg'),
(32, 'GD2505049435', '2025-05-04', '2025-05-05', 'Gina', 'Dela Cruz', 'jezelleformentera21@gmail.com', '09123456789', 13, 5, '0', '', 2, 2, 2, 15000.00, 500.00, 15500.00, '2025-04-05 07:46:51', 'Approved', '1233131242321', 'proof_of_payment/GD2505049435_1743839226.jpg'),
(33, 'KS2504177205', '2025-04-17', '2025-04-18', 'Karen', 'Smith', 'formenterajezelle@gmail.com', '09123456789', 10, 10, '0', '', 2, 2, 2, 9000.00, 500.00, 9500.00, '2025-04-05 07:48:16', 'Approved', '3213123213134', 'proof_of_payment/KS2504177205_1743839305.jpg'),
(34, 'ZM2504197757', '2025-04-19', '2025-04-20', 'Zayn', 'Malik', 'jezelleformentera21@gmail.com', '09123456789', 10, 10, '0', '', 2, 2, 1, 15000.00, 450.00, 15450.00, '2025-04-05 08:04:57', 'Approved', '3213131312331', 'proof_of_payment/ZM2504197757_1743840556.jpg'),
(35, 'AE2505104170', '2025-05-10', '2025-05-11', 'Angel', 'Eclarinal', 'jeielformentera@gmail.com', '09123456789', 12, 12, '0', '', 2, 3, 3, 11000.00, 600.00, 11600.00, '2025-04-05 08:30:30', 'Approved', '312312312313123', 'proof_of_payment/AE2505104170_1743842087.jpg'),
(36, 'NM2505027378', '2025-05-02', '2025-05-03', 'Nicki', 'Minaj', 'jezelleformentera21@gmail.com', '09123456789', 13, 14, '0', '', 2, 2, 2, 18000.00, 500.00, 18500.00, '2025-04-05 08:53:50', 'Approved', '1231231231231211', 'proof_of_payment/NM2505027378_1743843243.jpg'),
(37, 'RG2505133800', '2025-05-13', '2025-05-14', 'Rory', 'Gilmore', 'jezelleformentera21@gmail.com', '09123456789', 13, 13, '0', '', 2, 1, 2, 18000.00, 450.00, 18450.00, '2025-04-05 09:01:47', 'Approved', '21212121212', 'proof_of_payment/RG2505133800_1743843720.jpg'),
(38, 'LG2505267522', '2025-05-26', '2025-05-27', 'Lorelai', 'Gilmore', 'jezelleformentera21@gmail.com', '09123456721', 11, 11, '1', '', 2, 2, 0, 10000.00, 400.00, 10400.00, '2025-04-05 09:13:56', 'Approved', '111111111', 'proof_of_payment/LG2505267522_1743844455.jpg'),
(39, 'LD2505119681', '2025-05-11', '2025-05-12', 'Lana', 'Del Rey', 'formenterajezelle@gmail.com', '09123456789', 15, 14, '2', '', 2, 2, 2, 12000.00, 500.00, 12500.00, '2025-04-05 09:25:29', 'Approved', '131312131', 'proof_of_payment/LD2505119681_1743845147.jpg'),
(40, 'NJ2506115501', '2025-06-11', '2025-06-12', 'Nate', 'Jacobs', 'formenterajezelle@gmail.com', '09123456789', 13, 8, '2', '', 5, 2, 3, 11000.00, 1000.00, 12000.00, '2025-04-05 09:45:23', 'Approved', '21313131312312', 'proof_of_payment/NJ2506115501_1743846353.jpg'),
(41, 'KM2505231656', '2025-05-23', '2025-05-24', 'Kath', 'Muhlach', 'formenterajezelle@gmail.com', '09123456789', 12, 12, '2', '', 0, 0, 0, 11000.00, 0.00, 11000.00, '2025-04-05 09:54:42', 'Approved', '344234', 'proof_of_payment/KM2505231656_1743847064.jpg'),
(42, 'TK2507143704', '2025-07-14', '2025-07-24', 'TANGINA', 'KA', 'formenterajezelle@gmail.com', '09123456789', 12, 12, '2', '', 0, 0, 0, 11000.00, 0.00, 11000.00, '2025-04-05 10:03:55', 'Approved', '2123123123', 'proof_of_payment/TK2507143704_1743847447.jpg'),
(43, 'GA2506145685', '2025-06-14', '2025-06-15', 'Giselle', 'Adrianna', 'formenterajezelle@gmail.com', '09123456789', 9, 11, '1', '', 3, 2, 1, 9000.00, 600.00, 9600.00, '2025-04-05 10:12:05', 'Approved', '212121212111111', 'proof_of_payment/GA2506145685_1743847939.jpg'),
(44, 'MO2505296579', '2025-05-29', '2025-05-30', 'Mommy', 'Oni', 'formenterajezelle@gmail.com', '09123456789', 12, 14, '1', '', 4, 3, 1, 11000.00, 800.00, 11800.00, '2025-04-05 10:21:38', 'Approved', '21323123123', 'proof_of_payment/MO2505296579_1743848512.jpg'),
(45, 'AG2505206604', '2025-05-20', '2025-05-21', 'Ariana', 'Grande', 'formenterajezelle@gmail.com', '09123456721', 12, 17, '2', '', 6, 7, 5, 12000.00, 1500.00, 13500.00, '2025-04-05 10:30:55', 'Approved', '41541255156', 'proof_of_payment/AG2505206604_1743849067.jpg'),
(46, 'RM2505315810', '2025-05-31', '2025-06-01', 'Rihanna', 'Mae', 'jezelleformentera21@gmail.com', '09123456789', 12, 12, '1', '', 0, 0, 0, 10000.00, 0.00, 10000.00, '2025-04-05 10:37:24', 'Approved', '32222221', 'proof_of_payment/RM2505315810_1743849457.jpg'),
(47, 'SP2506020854', '2025-06-02', '2025-06-03', 'Sophie', 'Prime', 'jezelleformentera21@gmail.com', '09123456721', 10, 16, '0', 'Ediwow', 5, 5, 5, 18000.00, 1250.00, 19250.00, '2025-04-05 11:26:03', 'Rejected', '12331355343', 'proof_of_payment/SP2506020854_1743852786.jpg'),
(48, 'RG2506265818', '2025-06-26', '2025-06-27', 'Regina', 'George', 'jezelleformentera21@gmail.com', '09123456789', 15, 16, '0', '', 2, 2, 2, 18600.00, 500.00, 19100.00, '2025-04-05 14:07:56', 'Approved', '222222224141', 'proof_of_payment/RG2506265818_1743862089.jpg'),
(49, 'IS2506296044', '2025-06-29', '2025-06-30', 'Ike', 'Samosa', 'samosa.ikediezel@ue.edu.ph', '09123456789', 13, 12, '2', '', 12, 2, 2, 11000.00, 2000.00, 13000.00, '2025-04-05 14:49:48', 'Approved', '90354355', 'proof_of_payment/IS2506296044_1743864624.jpg');

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
  `file_path` varchar(255) NOT NULL,
  `transaction_number` varchar(100) NOT NULL,
  `proof_of_payment` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `guest_reservation_code`, `user_reservation_code`, `payment_receipt`, `status`, `uploaded_at`, `file_path`, `transaction_number`, `proof_of_payment`) VALUES
(12, 26, NULL, NULL, '', 'Approved', '2025-02-26 15:21:50', 'uploads/payments/payment_1740583310_26.png', '', ''),
(13, 0, 'AM2504106058', NULL, 'asdfa12', 'Pending', '2025-04-02 07:02:25', 'uploads/payment_proofs/AM2504106058_1743577345.jpg', '', ''),
(14, 45, NULL, '20250402-10911C', 'iandk143', 'Pending', '2025-04-02 07:11:34', 'uploads/payment_proofs/20250402-10911C_1743577894.jpg', '', ''),
(15, 0, 'JF2504124932', NULL, '1231asfsaf', 'Pending', '2025-04-02 14:31:31', 'uploads/payment_proofs/JF2504124932_1743604291.jpg', '', ''),
(16, 0, 'AD2504277619', NULL, 'gdssdf54', 'Pending', '2025-04-03 17:13:03', 'uploads/payment_proofs/AD2504277619_1743700383.jpg', '', ''),
(17, 0, 'HS2505097924', NULL, 'ily143', 'Pending', '2025-04-04 08:05:24', 'uploads/payment_proofs/HS2505097924_1743753924.jpg', '', ''),
(19, 0, 'AC2504246025', NULL, '808080080808', 'Pending', '2025-04-04 18:19:04', 'proof_of_payment/AC2504246025_1743790744.jpg', '', ''),
(20, 0, 'GH2504253618', NULL, '928312391823', 'Pending', '2025-04-04 18:57:48', 'proof_of_payment/GH2504253618_1743793068.jpg', '', ''),
(21, 0, 'KJ2504288142', NULL, '54231238126431', 'Pending', '2025-04-04 19:32:53', 'proof_of_payment/KJ2504288142_1743795173.jpg', '', ''),
(22, 0, 'HB2505016315', NULL, '12131231312331', 'Pending', '2025-04-04 19:44:36', 'proof_of_payment/HB2505016315_1743795876.jpg', '', ''),
(23, 0, 'KB2505037066', NULL, '1312321424124131234', 'Pending', '2025-04-04 20:36:42', 'proof_of_payment/KB2505037066_1743799002.jpg', '', ''),
(24, 0, 'DS2504143015', NULL, '3124124323313', 'Pending', '2025-04-04 21:22:34', 'proof_of_payment/DS2504143015_1743801754.jpg', '', ''),
(25, 0, 'TC2504184720', NULL, '423424234242143', 'Pending', '2025-04-04 21:31:47', 'proof_of_payment/TC2504184720_1743802307.jpg', '', ''),
(26, 0, 'JP2504202166', NULL, '24242142141', 'Pending', '2025-04-04 21:42:32', 'proof_of_payment/JP2504202166_1743802952.jpg', '', ''),
(27, 0, 'LR2504230708', NULL, '121312342134234', 'Pending', '2025-04-04 21:45:56', 'proof_of_payment/LR2504230708_1743803156.jpg', '', ''),
(28, 0, 'FO2504290478', NULL, '1324321422413', 'Pending', '2025-04-04 21:50:10', 'proof_of_payment/FO2504290478_1743803410.jpg', '', ''),
(29, 0, 'JP2505056911', NULL, '121312323123', 'Pending', '2025-04-04 22:04:34', 'proof_of_payment/JP2505056911_1743804274.jpg', '', ''),
(30, 0, 'AS2505083944', NULL, '21332131', 'Pending', '2025-04-04 22:16:01', 'proof_of_payment/AS2505083944_1743804961.jpg', '', ''),
(31, 0, 'GD2505049435', NULL, '1233131242321', 'Pending', '2025-04-05 07:47:06', 'proof_of_payment/GD2505049435_1743839226.jpg', '', ''),
(32, 0, 'KS2504177205', NULL, '3213123213134', 'Pending', '2025-04-05 07:48:25', 'proof_of_payment/KS2504177205_1743839305.jpg', '', ''),
(33, 0, 'ZM2504197757', NULL, '3213131312331', 'Pending', '2025-04-05 08:09:16', 'proof_of_payment/ZM2504197757_1743840556.jpg', '', ''),
(34, 0, 'AE2505104170', NULL, '312312312313123', 'Pending', '2025-04-05 08:34:47', 'proof_of_payment/AE2505104170_1743842087.jpg', '', ''),
(35, 0, 'NM2505027378', NULL, '1231231231231211', 'Pending', '2025-04-05 08:54:03', 'proof_of_payment/NM2505027378_1743843243.jpg', '', ''),
(36, 0, 'RG2505133800', NULL, '21212121212', 'Pending', '2025-04-05 09:02:00', 'proof_of_payment/RG2505133800_1743843720.jpg', '', ''),
(37, 0, 'LG2505267522', NULL, '111111111', 'Pending', '2025-04-05 09:14:15', 'proof_of_payment/LG2505267522_1743844455.jpg', '', ''),
(38, 0, 'LD2505119681', NULL, '131312131', 'Pending', '2025-04-05 09:25:47', 'proof_of_payment/LD2505119681_1743845147.jpg', '', ''),
(39, 0, 'NJ2506115501', NULL, '21313131312312', 'Pending', '2025-04-05 09:45:53', 'proof_of_payment/NJ2506115501_1743846353.jpg', '', ''),
(40, 0, 'KM2505231656', NULL, '344234', 'Pending', '2025-04-05 09:57:43', 'proof_of_payment/KM2505231656_1743847063.jpg', '', ''),
(41, 0, 'KM2505231656', NULL, '344234', 'Pending', '2025-04-05 09:57:44', 'proof_of_payment/KM2505231656_1743847064.jpg', '', ''),
(42, 0, 'KM2505231656', NULL, '344234', 'Pending', '2025-04-05 09:57:44', 'proof_of_payment/KM2505231656_1743847064.jpg', '', ''),
(43, 0, 'TK2507143704', NULL, '2123123123', 'Pending', '2025-04-05 10:04:07', 'proof_of_payment/TK2507143704_1743847447.jpg', '', ''),
(44, 0, 'GA2506145685', NULL, '212121212111111', 'Pending', '2025-04-05 10:12:19', 'proof_of_payment/GA2506145685_1743847939.jpg', '', ''),
(45, 0, 'MO2505296579', NULL, '21323123123', 'Pending', '2025-04-05 10:21:52', 'proof_of_payment/MO2505296579_1743848512.jpg', '', ''),
(46, 0, 'AG2505206604', NULL, '41541255156', 'Pending', '2025-04-05 10:31:07', 'proof_of_payment/AG2505206604_1743849067.jpg', '', ''),
(47, 0, 'RM2505315810', NULL, '32222221', 'Pending', '2025-04-05 10:37:37', 'proof_of_payment/RM2505315810_1743849457.jpg', '', ''),
(48, 0, 'SP2506020854', NULL, '12331355343', 'Pending', '2025-04-05 11:33:06', 'proof_of_payment/SP2506020854_1743852786.jpg', '', ''),
(49, 0, 'RG2506265818', NULL, '222222224141', 'Pending', '2025-04-05 14:08:09', 'proof_of_payment/RG2506265818_1743862089.jpg', '', ''),
(50, 0, 'IS2506296044', NULL, '90354355', 'Pending', '2025-04-05 14:50:24', 'proof_of_payment/IS2506296044_1743864624.jpg', '', ''),
(51, 63, NULL, '20250405-2BD8C7', '5757567', 'Pending', '2025-04-05 15:06:50', 'proof_of_payment/20250405-2BD8C7_1743865610.jpg', '', ''),
(52, 64, NULL, '20250405-487939', '324214124214', 'Pending', '2025-04-05 17:56:19', 'proof_of_payment/20250405-487939_1743875779.jpg', '', ''),
(53, 64, NULL, '20250405-CC41B1', '542334565636356', 'Pending', '2025-04-05 19:00:18', 'proof_of_payment/20250405-CC41B1_1743879618.jpg', '', ''),
(54, 64, NULL, '20250405-D0ABFB', '2121212121', 'Pending', '2025-04-05 19:29:50', 'proof_of_payment/20250405-D0ABFB_1743881390.jpg', '', '');

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
(60, 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', '$2y$10$0gjQOY2u88BP6rTAr5BixuFzeM49CUWFp/xOhNvK6M8H8qx2W07Cu', NULL, NULL, NULL),
(63, 'Jezelle', 'Formentera', 'jezelleformentera21@gmail.com', '09123456789', '$2y$10$H.ubB88xNB6GGLM7nI/I7.jm3norwpNu3FNyN3bDwBEMfU7MXhLSK', NULL, NULL, NULL),
(64, 'Zayn', 'Malik', 'formenterajezelle@gmail.com', '09123456789', '$2y$10$Bn0EiSKr4fy/vFHXA13J0uSK2DycMcqXkQdEwE9wyJ3LI2aX6D3N2', NULL, NULL, NULL);

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
  `proof_of_payment` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `transaction_number` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_reservation`
--

INSERT INTO `user_reservation` (`id`, `user_id`, `reservation_code`, `check_in`, `check_out`, `first_name`, `last_name`, `email`, `contact_number`, `adult_count`, `kid_count`, `tour_type`, `special_requests`, `extra_mattress`, `extra_pillow`, `extra_blanket`, `total_price`, `extras_total`, `total_amount`, `created_at`, `proof_of_payment`, `status`, `transaction_number`) VALUES
(1, 60, '20250327-B8CEE6', '2025-03-29', '2025-03-30', 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', 42, 12, '0', '', 5, 7, 4, 32400.00, 1300.00, 33700.00, '2025-03-27 19:18:20', NULL, 'Rejected', NULL),
(2, 60, '20250331-4DD6DF', '2025-04-04', '2025-04-05', 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', 22, 22, '0', 'asdfsad12', 1, 1, 1, 26400.00, 250.00, 26650.00, '2025-03-31 16:31:29', NULL, 'Pending', NULL),
(3, 60, '20250401-D8AF54', '2025-04-07', '2025-04-08', 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', 55, 12, '0', '', 5, 5, 5, 40200.00, 1250.00, 41450.00, '2025-04-01 16:41:41', NULL, 'Pending', NULL),
(5, 60, '20250402-41ED23', '2025-04-09', '2025-04-10', 'Cerike', 'Samosa', 'ikediezel.samosa@gmail.com', '09125663432', 42, 23, '0', '', 5, 7, 5, 39000.00, 1350.00, 40350.00, '2025-04-02 05:51:05', 'uploads/proof.jpg', 'Pending', '123456789'),
(7, 45, '20250402-10911C', '2025-04-26', '2025-04-27', 'Bosing', 'Narciso', 'samosa.ikediezel@ue.edu.ph', '09374583254', 2, 2, '0', '', 0, 0, 0, 12000.00, 0.00, 12000.00, '2025-04-02 07:11:07', NULL, 'Pending', NULL),
(8, 63, '20250405-AAEA77', '2025-05-21', '2025-05-22', 'Jezelle', 'Formentera', 'jezelleformentera21@gmail.com', '09123456789', 12, 12, '0', '', 1, 1, 3, 16000.00, 350.00, 16350.00, '2025-04-05 15:05:22', NULL, 'Pending', NULL),
(9, 63, '20250405-2BD8C7', '2025-05-21', '2025-05-22', 'Jezelle', 'Formentera', 'jezelleformentera21@gmail.com', '09123456789', 12, 12, '0', '', 1, 1, 3, 16000.00, 350.00, 16350.00, '2025-04-05 15:06:42', NULL, 'Approved', NULL),
(10, 63, '20250405-5B986F', '2025-06-20', '2025-06-21', 'Jezelle', 'Formentera', 'jezelleformentera21@gmail.com', '09123456789', 13, 12, '0', '', 1, 2, 1, 11000.00, 300.00, 11300.00, '2025-04-05 16:47:12', NULL, 'Pending', NULL),
(11, 64, '20250405-487939', '2025-06-22', '2025-06-23', 'Zayn', 'Malik', 'formenterajezelle@gmail.com', '09123456789', 15, 15, '0', '', 4, 3, 4, 12000.00, 950.00, 12950.00, '2025-04-05 17:56:00', NULL, 'Approved', NULL),
(12, 64, '20250405-CC41B1', '2025-06-25', '2025-06-26', 'Zayn', 'Malik', 'formenterajezelle@gmail.com', '09123456789', 3, 3, '0', '', 0, 0, 0, 7000.00, 0.00, 7000.00, '2025-04-05 18:59:41', NULL, 'Pending', NULL),
(13, 64, '20250405-D0ABFB', '2025-06-28', '2025-06-29', 'Zayn', 'Malik', 'formenterajezelle@gmail.com', '09123456789', 21, 12, '0', '', 0, 0, 0, 13500.00, 0.00, 13500.00, '2025-04-05 19:25:24', 'proof_of_payment/20250405-D0ABFB_1743881390.jpg', 'Approved', '2121212121');

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
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `guest_reservation`
--
ALTER TABLE `guest_reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `user_reservation`
--
ALTER TABLE `user_reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
