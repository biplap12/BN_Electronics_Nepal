-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 11, 2024 at 05:24 PM
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
-- Database: `shop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `photo` varchar(200) NOT NULL,
  `admin_status` varchar(50) NOT NULL DEFAULT 'regular',
  `blk` varchar(100) DEFAULT NULL,
  `unblk` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `password`, `photo`, `admin_status`, `blk`, `unblk`) VALUES
(1, 'admin', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', '', 'super', NULL, NULL),
(2, 'admin2', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', '', 'regular', NULL, NULL),
(3, 'admin3', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', '', 'regular', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `quantity` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `draft`
--

CREATE TABLE `draft` (
  `id` int(50) NOT NULL,
  `user_id` int(50) NOT NULL,
  `admin_id` int(10) NOT NULL,
  `subject` longtext NOT NULL,
  `admin_reply` longtext NOT NULL,
  `date_Time` varchar(50) DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `admin_id` int(50) DEFAULT NULL,
  `orderID` varchar(50) DEFAULT NULL,
  `PayTran_ID` varchar(50) DEFAULT NULL,
  `user_message` longtext NOT NULL,
  `msg_pic` varchar(86) NOT NULL DEFAULT 'No photo',
  `date_Time` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `method` varchar(50) NOT NULL,
  `order_status` varchar(20) NOT NULL DEFAULT 'Pending',
  `payment_Status` varchar(100) NOT NULL DEFAULT 'Pending',
  `invoice_no` varchar(100) NOT NULL,
  `idx` varchar(100) NOT NULL,
  `token` varchar(100) NOT NULL,
  `total_products` varchar(200) NOT NULL,
  `total_price` varchar(100) NOT NULL,
  `placed_on` datetime NOT NULL DEFAULT current_timestamp(),
  `cpl_o_inserter` int(50) DEFAULT NULL,
  `shipped_order_inserter` int(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders_items`
--

CREATE TABLE `orders_items` (
  `id` int(100) NOT NULL,
  `user_id` int(100) DEFAULT NULL,
  `pid` int(100) DEFAULT NULL,
  `oid` int(100) DEFAULT NULL,
  `order_qty` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `admin_id` int(50) NOT NULL,
  `category_id` int(100) NOT NULL,
  `p_name` varchar(100) NOT NULL,
  `details` varchar(500) NOT NULL,
  `price` int(10) NOT NULL,
  `old_price` int(50) NOT NULL,
  `image_01` varchar(100) NOT NULL,
  `image_02` varchar(100) NOT NULL,
  `image_03` varchar(100) NOT NULL,
  `P_quantity` int(100) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `admin_id`, `category_id`, `p_name`, `details`, `price`, `old_price`, `image_01`, `image_02`, `image_03`, `P_quantity`) VALUES
(1, 1, 1, 'camera', '20MP resolution, 4K video, f/1.8 aperture, optical image stabilization, 10fps burst mode, ISO 100-25600, 3-inch LCD, Wi-Fi, Bluetooth connectivity.', 500, 550, 'camera1.jpg', 'camera2.jpg', 'camera3.jpg', 0),
(2, 2, 2, 'fridge', '25 cu. ft. capacity, energy-efficient, stainless steel finish, adjustable shelves, ice maker, water dispenser, digital temperature control, LED lighting, smart connectivity.', 400, 500, 'fridge1.jpg', 'fridge2.jpg', 'fridge3.jpg', 1),
(3, 2, 3, 'laptop', '14-inch display, Intel Core i7 processor, 16GB RAM, 512GB SSD, dedicated graphics, backlit keyboard, Windows 10, HD webcam, USB-C, long-lasting battery.', 1000, 1500, 'laptop1.jpg', 'laptop2.jpg', 'laptop3.jpg', 54),
(4, 1, 4, 'Mobile', 'Mobile phones are handheld electronic devices for wireless communication, offering features like calls, texts, internet access, apps, and multimedia capabilities.', 100, 600, 'mobile1.jpg', 'mobile2.jpg', 'mobile3.jpg', 100);

-- --------------------------------------------------------

--
-- Table structure for table `product_category`
--

CREATE TABLE `product_category` (
  `id` int(100) NOT NULL,
  `category` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_category`
--

INSERT INTO `product_category` (`id`, `category`) VALUES
(1, 'Camera'),
(2, 'Fridge'),
(3, 'Laptop'),
(4, 'Mobile');

-- --------------------------------------------------------

--
-- Table structure for table `product_ratings`
--

CREATE TABLE `product_ratings` (
  `id` int(100) NOT NULL,
  `admin_id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `rating` int(100) NOT NULL,
  `comments` longtext NOT NULL,
  `anonymous` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sent_msg`
--

CREATE TABLE `sent_msg` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `admin_id` int(100) NOT NULL,
  `Subject` longtext NOT NULL,
  `admin_reply` longtext NOT NULL,
  `admin_attch` varchar(100) NOT NULL,
  `date_Time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `user_status` tinyint(1) NOT NULL DEFAULT 1,
  `user_picture` varchar(200) NOT NULL,
  `admin_blocker` int(100) DEFAULT NULL,
  `admin_UNblocker` int(100) DEFAULT NULL,
  `code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `state`, `city`, `address`, `user_status`, `user_picture`, `admin_blocker`, `admin_UNblocker`, `code`) VALUES
(1, 'user', 'aaagamming111@gmail.co', '9800000000', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'Bagmati State', 'Kathmandu ', 'nayabazar', 1, '0-02-03-8712d92d81c091db2750a4eb337ddd0356931e298a2efd37d0379ce2391eb189_5ba465f217b7f9a9.jpg', NULL, NULL, '492653'),
(14, 'biplapq', 'aaagamming111@gmail.', '9800000005555', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', '', '', '', 1, '', NULL, NULL, ''),
(15, 'biplap', 'aaagamming111@gmail.com', '98000089005', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'Bagmati State', 'Kathmandu ', 'nayabazar', 1, '0-02-03-8712d92d81c091db2750a4eb337ddd0356931e298a2efd37d0379ce2391eb189_5ba465f217b7f9a9.jpg', NULL, NULL, ''),
(16, 'ayan', 'angilbohara@gmail.com', '98000000599', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'Bagmati State', 'banasthali', 'balju', 1, '0-02-03-8712d92d81c091db2750a4eb337ddd0356931e298a2efd37d0379ce2391eb189_5ba465f217b7f9a9.jpg', NULL, NULL, ''),
(17, 'abc', 'aaa', '980000000000000', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'Bagmati State', 'kathmandu', 'nayabazar', 1, '', NULL, NULL, ''),
(18, 'Biplap Neupane', 'biplapneupane77@gmail.com', '9800000', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Bagmati State', 'Kathmandu', 'Nayabazar', 1, 'IMG_20230322_175906_090453.jpg', NULL, NULL, '0');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `draft`
--
ALTER TABLE `draft`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `cpl_o_inserter` (`cpl_o_inserter`),
  ADD KEY `shipped_order_inserter` (`shipped_order_inserter`);

--
-- Indexes for table `orders_items`
--
ALTER TABLE `orders_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oid` (`oid`),
  ADD KEY `pid` (`pid`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_ratings`
--
ALTER TABLE `product_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `pid` (`pid`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sent_msg`
--
ALTER TABLE `sent_msg`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_blocker` (`admin_blocker`),
  ADD KEY `admin_UNblocker` (`admin_UNblocker`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `draft`
--
ALTER TABLE `draft`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders_items`
--
ALTER TABLE `orders_items`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_category`
--
ALTER TABLE `product_category`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_ratings`
--
ALTER TABLE `product_ratings`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sent_msg`
--
ALTER TABLE `sent_msg`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `draft`
--
ALTER TABLE `draft`
  ADD CONSTRAINT `draft_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `draft_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`cpl_o_inserter`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`shipped_order_inserter`) REFERENCES `admins` (`id`);

--
-- Constraints for table `orders_items`
--
ALTER TABLE `orders_items`
  ADD CONSTRAINT `orders_items_ibfk_1` FOREIGN KEY (`oid`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `orders_items_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `orders_items_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `product_category` (`id`);

--
-- Constraints for table `product_ratings`
--
ALTER TABLE `product_ratings`
  ADD CONSTRAINT `product_ratings_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `product_ratings_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `product_ratings_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `sent_msg`
--
ALTER TABLE `sent_msg`
  ADD CONSTRAINT `sent_msg_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `sent_msg_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`admin_blocker`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`admin_UNblocker`) REFERENCES `admins` (`id`);

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
