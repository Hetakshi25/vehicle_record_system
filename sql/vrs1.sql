-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 04, 2025 at 09:42 AM
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
-- Database: `vehicle_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `activity_date` date NOT NULL,
  `activity_time` time NOT NULL,
  `type` enum('visit','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$eqfs6l3ZB4oe/Vl0X7rJPOnzlD4e34afzS3ZCbHnoGk5a2qXwLNCO', '2025-11-04 09:31:10');

-- --------------------------------------------------------

--
-- Table structure for table `deals`
--

CREATE TABLE `deals` (
  `id` int(11) NOT NULL,
  `inquiry_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `final_amount` decimal(12,2) NOT NULL,
  `status` enum('initiated','payment_pending','handover_pending','completed','cancelled') DEFAULT 'initiated',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `downpayment_amount` decimal(12,2) DEFAULT 0.00,
  `invoice_generated` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deals`
--

INSERT INTO `deals` (`id`, `inquiry_id`, `buyer_id`, `seller_id`, `final_amount`, `status`, `created_at`, `updated_at`, `downpayment_amount`, `invoice_generated`) VALUES
(1, 2, 3, 1, 50.00, 'initiated', '2025-11-04 07:20:24', '2025-11-04 07:28:04', 0.00, 1),
(2, 3, 3, 1, 10.00, 'initiated', '2025-11-04 07:49:58', '2025-11-04 07:50:01', 0.00, 1),
(3, 4, 3, 1, 100.00, 'initiated', '2025-11-04 08:25:31', '2025-11-04 08:25:39', 0.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `vehicle_id`, `created_at`) VALUES
(5, 1, 2, '2025-11-04 07:36:06');

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`id`, `user_id`, `vehicle_id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 3, 2, 'i_use', 'i_use@gmail.com', 'hi', '2025-11-04 05:23:06'),
(2, 3, 2, 'i_use', 'i_use@gmail.com', 'hi', '2025-11-04 07:10:39'),
(3, 3, 2, 'i_use', 'i_use@gmail.com', 'hello', '2025-11-04 07:47:04'),
(4, 3, 2, 'i_use', 'i_use@gmail.com', 'asjdkajd', '2025-11-04 08:23:12');

-- --------------------------------------------------------

--
-- Table structure for table `inquiry_offers`
--

CREATE TABLE `inquiry_offers` (
  `id` int(11) NOT NULL,
  `inquiry_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `offer_amount` decimal(12,2) NOT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiry_offers`
--

INSERT INTO `inquiry_offers` (`id`, `inquiry_id`, `user_id`, `offer_amount`, `status`, `created_at`) VALUES
(1, 2, 3, 50.00, 'accepted', '2025-11-04 07:18:13'),
(2, 3, 3, 10.00, 'accepted', '2025-11-04 07:49:11'),
(3, 4, 3, 100.00, 'accepted', '2025-11-04 08:24:42');

-- --------------------------------------------------------

--
-- Table structure for table `inquiry_replies`
--

CREATE TABLE `inquiry_replies` (
  `id` int(11) NOT NULL,
  `inquiry_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reply_message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiry_replies`
--

INSERT INTO `inquiry_replies` (`id`, `inquiry_id`, `user_id`, `reply_message`, `created_at`) VALUES
(1, 2, 3, 'Made an offer of ₹50.00', '2025-11-04 07:18:13'),
(2, 3, 3, 'Made an offer of ₹10.00', '2025-11-04 07:49:11'),
(3, 4, 3, 'Made an offer of ₹100.00', '2025-11-04 08:24:42');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `notify_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('buyer','seller','admin') DEFAULT 'buyer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `address` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `approval_status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role`, `created_at`, `updated_at`, `address`, `dob`, `gender`, `bio`, `approval_status`) VALUES
(1, 'Ayush', 'aayush@gmail.com', '1234567890', '$2y$10$CIac.TpEl8hDdLaN73XWa.R8geAyYFZA.9iPuwkeDe7hoRj05bnKW', 'seller', '2025-11-03 16:52:55', '2025-11-04 08:28:28', 'Anand', '2025-11-04', 'male', 'hii', 'approved'),
(3, 'i_use', 'i_use@gmail.com', '4567891230', '$2y$10$t5SJmUf5jLYhtmP5K9foL.tCxvN5oUTiUptbUXya4q0v.a1wWL77O', 'buyer', '2025-11-04 03:25:34', '2025-11-04 08:28:26', NULL, NULL, NULL, NULL, 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  `variant` varchar(100) DEFAULT NULL,
  `trim` varchar(100) DEFAULT NULL,
  `year` int(11) DEFAULT NULL CHECK (`year` >= 1886),
  `vin` varchar(50) DEFAULT NULL,
  `registration_no` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `engine` varchar(100) DEFAULT NULL,
  `horsepower` int(11) DEFAULT NULL,
  `torque` varchar(50) DEFAULT NULL,
  `fuel` enum('Petrol','Diesel','Hybrid','Electric') NOT NULL,
  `fuel_capacity` decimal(6,2) DEFAULT NULL,
  `transmission` enum('Manual','Automatic','CVT','Dual-Clutch') DEFAULT NULL,
  `drivetrain` varchar(50) DEFAULT NULL,
  `seats` int(11) DEFAULT NULL,
  `doors` int(11) DEFAULT NULL,
  `mileage` decimal(10,2) DEFAULT NULL,
  `vehicle_condition` enum('New','Used','Certified Pre-Owned') DEFAULT 'Used',
  `owners` int(11) DEFAULT 1,
  `warranty` varchar(100) DEFAULT NULL,
  `insurance` varchar(100) DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `negotiable` tinyint(1) DEFAULT 0,
  `emi_available` tinyint(1) DEFAULT 0,
  `features` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `image1` varchar(255) DEFAULT NULL,
  `image2` varchar(255) DEFAULT NULL,
  `image3` varchar(255) DEFAULT NULL,
  `image4` varchar(255) DEFAULT NULL,
  `image5` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `approval_status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `user_id`, `brand`, `model`, `variant`, `trim`, `year`, `vin`, `registration_no`, `color`, `engine`, `horsepower`, `torque`, `fuel`, `fuel_capacity`, `transmission`, `drivetrain`, `seats`, `doors`, `mileage`, `vehicle_condition`, `owners`, `warranty`, `insurance`, `price`, `negotiable`, `emi_available`, `features`, `description`, `video_url`, `image1`, `image2`, `image3`, `image4`, `image5`, `created_at`, `updated_at`, `approval_status`) VALUES
(2, 1, 'Buggati', 'Divo', 'W16', 'xyz', 2019, 'xyz', '123', 'Black', '7993 cc', 1479, '1600Nm', 'Petrol', 100.00, 'Automatic', 'xyz', 2, 2, 20.00, 'New', 0, 'yes', 'yes', 410000000.00, 1, 1, 'xyz', 'The Bugatti Divo has 1 Petrol Engine on offer. The Petrol engine is 7993 cc . It is available with Automatic transmission. The Divo is a 2 seater 16 cylinder car and has length of 4641mm, width of 2018mm and a wheelbase of 2711mm.', 'https://www.youtube.com/watch?v=hqDT5_4z_YI', '', '', '', '', '', '2025-11-04 04:05:09', '2025-11-04 04:30:39', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `visits`
--

CREATE TABLE `visits` (
  `id` int(11) NOT NULL,
  `inquiry_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `visit_date` datetime NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visits`
--

INSERT INTO `visits` (`id`, `inquiry_id`, `buyer_id`, `seller_id`, `visit_date`, `status`, `created_at`) VALUES
(1, 2, 3, 1, '2025-11-12 12:48:00', 'pending', '2025-11-04 07:19:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `activity_date` (`activity_date`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `deals`
--
ALTER TABLE `deals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inquiry_id` (`inquiry_id`),
  ADD KEY `buyer_id` (`buyer_id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`vehicle_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_inquiry_user` (`user_id`),
  ADD KEY `fk_inquiry_vehicle` (`vehicle_id`);

--
-- Indexes for table `inquiry_offers`
--
ALTER TABLE `inquiry_offers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inquiry_id` (`inquiry_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `inquiry_replies`
--
ALTER TABLE `inquiry_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reply_inquiry` (`inquiry_id`),
  ADD KEY `fk_reply_user` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vin` (`vin`),
  ADD UNIQUE KEY `registration_no` (`registration_no`),
  ADD KEY `fk_vehicle_user` (`user_id`);

--
-- Indexes for table `visits`
--
ALTER TABLE `visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_visit_inquiry` (`inquiry_id`),
  ADD KEY `fk_visit_buyer` (`buyer_id`),
  ADD KEY `fk_visit_seller` (`seller_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `deals`
--
ALTER TABLE `deals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `inquiry_offers`
--
ALTER TABLE `inquiry_offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inquiry_replies`
--
ALTER TABLE `inquiry_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `visits`
--
ALTER TABLE `visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `deals`
--
ALTER TABLE `deals`
  ADD CONSTRAINT `deals_ibfk_1` FOREIGN KEY (`inquiry_id`) REFERENCES `inquiries` (`id`),
  ADD CONSTRAINT `deals_ibfk_2` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `deals_ibfk_3` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD CONSTRAINT `fk_inquiry_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_inquiry_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inquiry_offers`
--
ALTER TABLE `inquiry_offers`
  ADD CONSTRAINT `inquiry_offers_ibfk_1` FOREIGN KEY (`inquiry_id`) REFERENCES `inquiries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inquiry_offers_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inquiry_replies`
--
ALTER TABLE `inquiry_replies`
  ADD CONSTRAINT `fk_reply_inquiry` FOREIGN KEY (`inquiry_id`) REFERENCES `inquiries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reply_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `fk_vehicle_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `visits`
--
ALTER TABLE `visits`
  ADD CONSTRAINT `fk_visit_buyer` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_visit_inquiry` FOREIGN KEY (`inquiry_id`) REFERENCES `inquiries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_visit_seller` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
