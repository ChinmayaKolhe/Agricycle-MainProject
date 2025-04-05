-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Apr 05, 2025 at 10:23 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agricycle`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`) VALUES
(1, 'admin@gmail.com', '$2y$10$DFQQDyoGkhHfPGTBH.DtY.7J0eB/UKtADJGYXeH1fWNd3gM8Vbc/G');

-- --------------------------------------------------------

--
-- Table structure for table `bank_policies`
--

CREATE TABLE `bank_policies` (
  `id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `pdf_path` varchar(255) DEFAULT NULL,
  `bank_link` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bank_policies`
--

INSERT INTO `bank_policies` (`id`, `agent_id`, `name`, `pdf_path`, `bank_link`, `created_at`) VALUES
(5, 2, 'Maan Dhan Yojana', 'uploads/policies/policy_67f0d7c6318270.83178529.pdf', 'https://translate.google.com/translate?u=https://maandhan.in/&hl=hi&sl=en&tl=hi&client=srp', '2025-04-05 07:12:06'),
(6, 2, 'Kisan Credit Card', 'uploads/policies/policy_67f0dadeafbab8.86197405.pdf', 'https://www.myscheme.gov.in/schemes/kcc', '2025-04-05 07:25:18'),
(7, 1, 'kisan credit card', 'uploads/policies/policy_67f11ad0908880.86541618.pdf', 'https://www.myscheme.gov.in/schemes/kcc', '2025-04-05 11:58:08'),
(8, 1, 'Bank Of India', 'uploads/policies/policy_67f1602c140ed6.62650301.pdf', 'https://bankofindia.co.in/policy-guidelines', '2025-04-05 16:54:04');

-- --------------------------------------------------------

--
-- Table structure for table `buyers`
--

CREATE TABLE `buyers` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `aadhaar_path` varchar(255) DEFAULT NULL,
  `verification_requested` tinyint(4) DEFAULT 0,
  `is_verified` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buyers`
--

INSERT INTO `buyers` (`id`, `email`, `password`, `name`, `phone`, `company`, `aadhaar_path`, `verification_requested`, `is_verified`) VALUES
(1, 'chinmayakolhe2005@gmail.com', '$2y$10$k5fbleSAsSACOzKMa.W/bufj73GaSAxRaWbonhVvC16Iic5UVBShm', 'Chinmaya Bhushan Kolhe', '8999316982', 'Code crafters', NULL, 0, 0),
(2, 'kirti@gmail.com', '$2y$10$BWedCgB0dbprjP7HT3NylODaC16kuo7ETtGhMVPyPOquygz1QpltG', 'Kirti Kolhe', '4567895678', 'Code Fast', NULL, 0, 0),
(3, 'yamini@gmail.com', '$2y$10$HjtxA.ODlPEMzZ7GQ6Z5qOmXNr.H6r/zXu.Vso51eV0NS2TbKh2Ze', 'Yamini Mahesh Bhole', '7856783423', 'Waste buyers', 'uploads/aadhaar_buyers/buyer_3_AdharCard.pdf', 0, 1),
(5, 'virat@gmail.com', '$2y$10$H0XUBMPFJ/8ZdG1/5K73tujnKtsg/nt.kgZrR0KA0t24BZ0KHgIUC', 'Virat Kohli', '9969897856', 'Indian EcoCoders', NULL, 0, 0),
(6, 'rakhi@pccoepune.org', '$2y$10$HRHfCL7m2ng56eBkegl0xO8a4B/72PAJaYkB/9Z0TOuPwa6D6A0qy', 'Rakhi Pagar', '6789567845', 'Indian EcoCoders', NULL, 0, 0),
(7, 'rohit@gmail.com', '$2y$10$57N1wqD8sOAG516lvtV.cuMCLX/iSQjrdqNgeJGKW59UCuVtwCBFq', 'Rohit Sharma', '8956784567', 'Mumbai wasters', 'uploads/aadhaar_buyers/buyer_7_AdharCard.pdf', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `community_comments`
--

CREATE TABLE `community_comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` enum('farmer','buyer') NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_comments`
--

INSERT INTO `community_comments` (`id`, `post_id`, `user_id`, `role`, `comment`, `created_at`) VALUES
(1, 1, 1, 'buyer', 'Use Potash', '2025-04-05 10:36:04');

-- --------------------------------------------------------

--
-- Table structure for table `community_posts`
--

CREATE TABLE `community_posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` enum('farmer','buyer') NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_posts`
--

INSERT INTO `community_posts` (`id`, `user_id`, `role`, `title`, `content`, `created_at`) VALUES
(1, 1, 'farmer', 'Best Fertilizer', 'Suggest me the best fertilizer\\r\\n', '2025-04-05 10:11:49');

-- --------------------------------------------------------

--
-- Table structure for table `farmers`
--

CREATE TABLE `farmers` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `aadhaar_path` varchar(255) DEFAULT NULL,
  `verification_requested` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farmers`
--

INSERT INTO `farmers` (`id`, `email`, `password`, `name`, `phone`, `location`, `is_verified`, `aadhaar_path`, `verification_requested`) VALUES
(1, 'bhushan@gmail.com', '$2y$10$b2GFmLBx5OxTQtZMlUhB/.Pg0giL/dWtOKxui6lvrJa8T8EEc8DCy', 'Bhushan Kolhe', '9969897856', 'Pune', 1, '../uploads/aadhaar/AdharCard.pdf', 0),
(2, 'chinmaya.kolhe24@pccoepune.org', '$2y$10$Eju2w3lKHDG8ixaReKbCi.3MlJktW4GFX.750xsJysW9fyqje38YG', 'Chinmaya Kolhe', '8999316982', 'Jalgaon', 1, '../uploads/aadhaar/AdharCard.pdf', 0),
(3, 'lubda@gmail.com', '$2y$10$SDLeesLLTDPXAhLCpaScBukXFGTEYvysFNtWksuT2xbBv2vZfTzju', 'lubdha chaudhari', '7890678978', 'Mumbai', 1, '../uploads/aadhaar/AdharCard.pdf', 0);

-- --------------------------------------------------------

--
-- Table structure for table `insurance_agents`
--

CREATE TABLE `insurance_agents` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `agency` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `insurance_agents`
--

INSERT INTO `insurance_agents` (`id`, `email`, `password`, `name`, `agency`, `phone`) VALUES
(1, 'rudrakolhe@gmail.com', '$2y$10$VM3SGhY9LArwSI2atbzBqerFUNgky4Dv3vZ07OkUXo/7bbROM3Du2', 'Rudra Bhushan Kolhe', 'RBL', '6789567845');

-- --------------------------------------------------------

--
-- Table structure for table `marketplace_items`
--

CREATE TABLE `marketplace_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `item_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `contact_info` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `marketplace_items`
--

INSERT INTO `marketplace_items` (`id`, `user_id`, `photo_path`, `item_name`, `description`, `price`, `quantity`, `contact_info`, `created_at`) VALUES
(10, 2, 'uploads/wasteimg/waste_67f16fcdbc17c.jpeg', 'Food Waste', 'Food waste is available you can buy', 250.00, 12, '7867908978', '2025-04-05 18:00:45');

-- --------------------------------------------------------

--
-- Table structure for table `marketplace_orders`
--

CREATE TABLE `marketplace_orders` (
  `id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('Pending','Completed','Cancelled') DEFAULT 'Pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `total_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `buyer_id`, `item_id`, `quantity`, `total_price`, `created_at`) VALUES
(0, 1, 4, 1, 200.00, '2025-04-05 10:52:58'),
(0, 1, 3, 1, 400.00, '2025-04-05 11:23:09'),
(0, 1, 3, 2, 800.00, '2025-04-05 12:16:13'),
(0, 1, 5, 4, 1600.00, '2025-04-05 12:21:23'),
(0, 1, 5, 5, 2000.00, '2025-04-05 12:29:18'),
(0, 1, 5, 5, 2000.00, '2025-04-05 12:35:16'),
(0, 1, 5, 5, 2000.00, '2025-04-05 12:36:02'),
(0, 1, 5, 5, 2000.00, '2025-04-05 12:36:10'),
(0, 1, 6, 1, 300.00, '2025-04-05 12:40:38'),
(0, 1, 7, 4, 2000.00, '2025-04-05 12:56:03'),
(0, 7, 8, 6, 27270.00, '2025-04-05 17:57:01');

-- --------------------------------------------------------

--
-- Table structure for table `pickup_requests`
--

CREATE TABLE `pickup_requests` (
  `id` int(11) NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `waste_type` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` enum('Pending','Scheduled','Completed') DEFAULT 'Pending',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pickup_requests`
--

INSERT INTO `pickup_requests` (`id`, `farmer_id`, `waste_type`, `quantity`, `status`, `request_date`) VALUES
(3, 2, 'Animal Manure', 4, 'Pending', '2025-04-04 08:20:41'),
(4, 1, 'Crop Residue', 5, 'Pending', '2025-04-05 11:22:26');

-- --------------------------------------------------------

--
-- Table structure for table `policy_requests`
--

CREATE TABLE `policy_requests` (
  `id` int(11) NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `policy_id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `policy_requests`
--

INSERT INTO `policy_requests` (`id`, `farmer_id`, `policy_id`, `agent_id`, `status`, `applied_at`) VALUES
(11, 1, 7, 1, 'Approved', '2025-04-05 11:58:29'),
(12, 2, 7, 1, 'Approved', '2025-04-05 16:46:48'),
(13, 2, 8, 1, 'Approved', '2025-04-05 16:54:30'),
(14, 3, 7, 1, 'Rejected', '2025-04-05 16:56:56'),
(15, 3, 8, 1, 'Approved', '2025-04-05 16:57:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('farmer','buyer','admin','insurance_agent') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Chinmaya Bhushan Kolhe', 'chinmayakolhe2005@gmail.com', '$2y$10$R7ccbIuHUut3pqpCOJ9.xu89mSrMQl73jG6E0qPWUoy1lDLt/qaGK', 'buyer', '2025-04-03 16:49:02'),
(2, 'Suresh Bharambe', 'sureshbharambe@gmail.com', '$2y$10$QmP2/XPBXmTFu2/n7hPQ9egclYCy.k9g0iUj9EAX0Bvueo1ut27pS', 'farmer', '2025-04-03 16:57:59'),
(3, 'admin', 'admin@agricycle.com', '0192023a7bbd73250516f069df18b500', 'admin', '2025-04-04 02:19:27'),
(6, 'Rudra Kolhe', 'rudra@gmail.com', '$2y$10$Wi3neiWRFXOkzvra0FuJc.cAeZiXlq/NjlgQFBvFL6LmEClVFP0CS', 'insurance_agent', '2025-04-04 06:57:52'),
(9, 'Krishna Bharambe', 'krishna@gmail.com', '$2y$10$4PjDUHU6CoebUrsDF6NBHu6QOYKD9NZLbbXqcda/RTVQxaLZu2gP6', 'farmer', '2025-04-04 08:23:00');

-- --------------------------------------------------------

--
-- Table structure for table `waste_listings`
--

CREATE TABLE `waste_listings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `waste_type` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `pickup_available` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waste_listings`
--

INSERT INTO `waste_listings` (`id`, `user_id`, `waste_type`, `quantity`, `pickup_available`, `created_at`) VALUES
(1, 2, 'Fruit & Vegetable Waste', 10, 1, '2025-04-04 07:33:12'),
(2, 1, 'Animal Manure', 4, 0, '2025-04-05 07:24:06'),
(3, 1, 'Fruit & Vegetable Waste', 4, 1, '2025-04-05 11:22:16'),
(4, 1, 'Plastic Mulch', 10, 0, '2025-04-05 11:25:18'),
(5, 1, 'Weeds & Grass', 30, 0, '2025-04-05 11:25:26');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `item_id`, `added_at`) VALUES
(0, 1, 6, '2025-04-05 12:56:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `bank_policies`
--
ALTER TABLE `bank_policies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buyers`
--
ALTER TABLE `buyers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `community_comments`
--
ALTER TABLE `community_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `community_posts`
--
ALTER TABLE `community_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `farmers`
--
ALTER TABLE `farmers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `insurance_agents`
--
ALTER TABLE `insurance_agents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `marketplace_items`
--
ALTER TABLE `marketplace_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `marketplace_orders`
--
ALTER TABLE `marketplace_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buyer_id` (`buyer_id`),
  ADD KEY `seller_id` (`seller_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pickup_requests`
--
ALTER TABLE `pickup_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `farmer_id` (`farmer_id`);

--
-- Indexes for table `policy_requests`
--
ALTER TABLE `policy_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `farmer_id` (`farmer_id`),
  ADD KEY `policy_id` (`policy_id`),
  ADD KEY `policy_requests_ibfk_3` (`agent_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `waste_listings`
--
ALTER TABLE `waste_listings`
  ADD PRIMARY KEY (`id`),
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
-- AUTO_INCREMENT for table `bank_policies`
--
ALTER TABLE `bank_policies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `buyers`
--
ALTER TABLE `buyers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `community_comments`
--
ALTER TABLE `community_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `community_posts`
--
ALTER TABLE `community_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `farmers`
--
ALTER TABLE `farmers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `insurance_agents`
--
ALTER TABLE `insurance_agents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `marketplace_items`
--
ALTER TABLE `marketplace_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `marketplace_orders`
--
ALTER TABLE `marketplace_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pickup_requests`
--
ALTER TABLE `pickup_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `policy_requests`
--
ALTER TABLE `policy_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `waste_listings`
--
ALTER TABLE `waste_listings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `community_comments`
--
ALTER TABLE `community_comments`
  ADD CONSTRAINT `community_comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `community_posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `marketplace_items`
--
ALTER TABLE `marketplace_items`
  ADD CONSTRAINT `marketplace_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `marketplace_orders`
--
ALTER TABLE `marketplace_orders`
  ADD CONSTRAINT `marketplace_orders_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `marketplace_orders_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `marketplace_orders_ibfk_3` FOREIGN KEY (`item_id`) REFERENCES `marketplace_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pickup_requests`
--
ALTER TABLE `pickup_requests`
  ADD CONSTRAINT `pickup_requests_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `waste_listings`
--
ALTER TABLE `waste_listings`
  ADD CONSTRAINT `waste_listings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
