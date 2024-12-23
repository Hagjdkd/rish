-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 23, 2024 at 01:02 PM
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
-- Database: `spo_creatives`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`cart_item_id`, `user_id`, `product_id`, `quantity`, `added_at`) VALUES
(7, 10, 2, 1, '2024-12-23 11:38:14');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `status` enum('Confirmed','Pending','Cancelled','Shipped','Sold') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `image`, `stock_quantity`, `status`, `created_at`, `updated_at`) VALUES
(1, 'dsad', 'das', 22.00, 'uploads/1733579021-OIP.jpg', 21, '', '2024-12-23 10:11:14', '2024-12-23 10:11:14'),
(2, 'dsad', '2adsd', 223.00, 'uploads/1734842923-6762f0eb6bf823.12735904.jpg', 1, '', '2024-12-23 11:19:56', '2024-12-23 11:19:56'),
(3, 'dsad', 'dsada', 21.00, 'uploads/1734710220-mona.jpg', 1, '', '2024-12-23 11:58:58', '2024-12-23 11:58:58');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `trans_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('Pending','Confirmed','Cancelled','Shipped') NOT NULL DEFAULT 'Pending',
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `shipping_address` text NOT NULL,
  `contact_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`trans_id`, `user_id`, `price`, `status`, `transaction_date`, `shipping_address`, `contact_number`) VALUES
(3, 10, 22.00, 'Confirmed', '2024-12-23 04:00:17', 'dulag', '37878428');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_products`
--

CREATE TABLE `transaction_products` (
  `trans_product_id` int(11) NOT NULL,
  `trans_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_products`
--

INSERT INTO `transaction_products` (`trans_product_id`, `trans_id`, `product_id`, `quantity`, `price`) VALUES
(3, 3, 1, 1, 22.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `full_name`, `role`, `created_at`, `updated_at`) VALUES
(1, 'irisg', 'irishmeridor@gmail.com', '$2y$10$wZ.jc6o9SWabcIlUchNcKOAAuRpWSiwU5WZJN8RTQWXbWHD5NQ08W', 'dsadasd', 'user', '2024-12-09 08:43:39', '2024-12-23 11:46:03'),
(2, 'rkyshamae', 'rkyshamae@gmail.com', '$2y$10$2sMDhH7zpKsZ5ZA.4ixdHOsZJ6a6Wzlrk4pBhYO1ys6pf6RlJC76O', 'asdfghjkl', 'user', '2024-12-09 15:15:04', '2024-12-09 15:15:04'),
(5, 'irishmae.meridor@evsu.edu.ph', 'irishmae.meridor2904@gmail.com', '$2y$10$QKmTNQ.r9znAgHyZRW0NpO7QoFSAkAf/UJg.N9rej7xgkKr0qfKoq', 'imae', 'user', '2024-12-09 15:27:27', '2024-12-09 15:27:27'),
(6, 'irishmae', 'irishmae@gmail.com', '$2y$10$OuPoYu3M0rqVBOPX7ViNXO/h0qToTS9PElqnBzEMkmrV4cD2ITKKC', 'eme', 'user', '2024-12-10 02:53:54', '2024-12-10 02:53:54'),
(7, 'irish', 'ayrish@gmail.com', '$2y$10$wZ.jc6o9SWabcIlUchNcKOAAuRpWSiwU5WZJN8RTQWXbWHD5NQ08W', 'jahjsh', 'user', '2024-12-14 11:20:33', '2024-12-23 11:45:04'),
(9, 'haresh', 'ayresnag@gmail.com', '$2y$10$2hBxd5pI6mw0XSOHABH6b.994oCYguJN4LPRZkdFs/6yhlzAT/OFu', 'hjgdyg', 'user', '2024-12-14 11:43:04', '2024-12-14 11:43:04'),
(10, 'rish', 'rish@gmail.com', '$2y$10$93bmpvwfLTIHUcD2BDD2oefWi9fZFircO3Z2lUw3/moYvcyMnnT0W', 'rishmeridor', 'user', '2024-12-21 09:30:13', '2024-12-21 09:30:13'),
(11, 'irishmeridor', 'IRIS@gmail.com', '$2y$10$wZ.jc6o9SWabcIlUchNcKOAAuRpWSiwU5WZJN8RTQWXbWHD5NQ08W', 'IRISH MERIDOR ', 'admin', '2024-12-23 06:29:56', '2024-12-23 06:29:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`trans_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transaction_products`
--
ALTER TABLE `transaction_products`
  ADD PRIMARY KEY (`trans_product_id`),
  ADD KEY `trans_id` (`trans_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `trans_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transaction_products`
--
ALTER TABLE `transaction_products`
  MODIFY `trans_product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction_products`
--
ALTER TABLE `transaction_products`
  ADD CONSTRAINT `transaction_products_ibfk_1` FOREIGN KEY (`trans_id`) REFERENCES `transactions` (`trans_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_products_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
