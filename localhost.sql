-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Sep 27, 2020 at 08:20 AM
-- Server version: 5.7.26
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `ec-dmeo`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `item_id`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 185, 1, 3, '2020-09-25 12:05:43', '2020-09-25 12:05:43'),
(2, 153, 1, 1, '2020-09-25 12:09:10', '2020-09-25 12:09:10');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_price` int(11) NOT NULL,
  `item_description` varchar(1000) NOT NULL,
  `item_stock` int(11) NOT NULL,
  `item_thumbnail` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `seller_id`, `item_name`, `item_price`, `item_description`, `item_stock`, `item_thumbnail`, `created_at`, `updated_at`) VALUES
(1, 185, 'item1', 1300, 'item1', 2, 'nike1.jpg', '2020-09-24 11:49:40', '2020-09-24 11:49:40'),
(2, 153, 'item2', 3000, 'item2', 2, 'nike4.jpg', '2020-09-26 02:44:46', '2020-09-26 02:44:46'),
(3, 153, 'item3', 3000, 'item3', 4, 'nike11.jpg', '2020-09-26 08:05:04', '2020-09-26 08:05:04'),
(4, 153, 'item4', 3000, 'item4', 2, 'nike6.jpg', '2020-09-26 08:05:21', '2020-09-26 08:05:21'),
(5, 153, 'item5', 1300, 'item5', 3, 'nike9.jpg', '2020-09-26 08:05:58', '2020-09-26 08:05:58'),
(6, 153, 'item6', 20000, 'item6', 3, 'nike1.jpg', '2020-09-26 08:06:13', '2020-09-26 08:06:13'),
(7, 153, 'item7', 1600, 'item7', 4, 'nike2.jpg', '2020-09-26 08:06:43', '2020-09-26 08:06:43'),
(8, 153, 'item8', 4211, 'item8', 2, 'nike10.jpg', '2020-09-26 08:07:02', '2020-09-26 08:07:02'),
(9, 153, 'item9', 3000, 'item9', 2, 'nike3.jpg', '2020-09-26 08:07:30', '2020-09-26 08:07:30');

-- --------------------------------------------------------

--
-- Table structure for table `item_images`
--

CREATE TABLE `item_images` (
  `image_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `item_images`
--

INSERT INTO `item_images` (`image_id`, `item_id`, `image_name`, `created_at`, `updated_at`) VALUES
(1, 1, 'nike2.jpg', '2020-09-24 11:50:53', '2020-09-24 11:50:53');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `post_name` varchar(255) NOT NULL,
  `post_content` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `item_id`, `post_name`, `post_content`, `created_at`, `updated_at`) VALUES
(20, 1, '名無さん', '最高渋い', '2020-09-26 03:42:24', '2020-09-26 03:42:24'),
(21, 1, '名無さん', '渋すぎて引く', '2020-09-26 03:57:00', '2020-09-26 03:57:00'),
(22, 1, '名無さん', '渋すぎわろた', '2020-09-26 04:00:11', '2020-09-26 04:00:11'),
(23, 1, '名無さん', '渋すぎやろほんまに', '2020-09-26 04:00:23', '2020-09-26 04:00:23'),
(24, 2, '名無さん', '美しい', '2020-09-26 04:04:40', '2020-09-26 04:04:40'),
(25, 3, '名無さん', '非常に魅力的な商品だと思います。', '2020-09-27 07:25:59', '2020-09-27 07:25:59'),
(26, 3, '名無さん', '購入をお考えの方はぜひ！！', '2020-09-27 07:26:18', '2020-09-27 07:26:18'),
(27, 3, '名無さん', 'いい商品です！', '2020-09-27 07:27:51', '2020-09-27 07:27:51'),
(28, 3, '名無さん', '素敵です！', '2020-09-27 07:30:14', '2020-09-27 07:30:14');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(254) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `pass_reset_token` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `user_image` varchar(256) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `pass_reset_token`, `address`, `user_image`, `status`, `created_at`, `updated_at`) VALUES
(145, 'user1', 'user1@gmail.com', '$2y$10$xTfmvdkXVyLeQITAwteVLeHEMF8BWoISjlPBDwvOC5QhsMv0opDMi', '', 'user1user1user1', 'photoshop.png', 0, '2020-09-21 02:28:03', '2020-09-21 02:28:03'),
(147, 'admin', 'waibandl_0412@yahoo.co.jp', '$2y$10$xqvc43JpZ3QSrB84aQ7UIulGz/8PoanPWz1p0V9XLJ8rVvWJ69MBy', 'ead5fbc09095d36144ff315ff99a3f071e59ce21', 'adminadmin', 'nike1.jpg', 0, '2020-09-21 03:24:47', '2020-09-21 03:24:47'),
(148, '大西　純平', 'waibandl321@gmail.com', '$2y$10$ObL.JhapXQ0TQXF.M5qf7OBYELkWglEs5Ee8PYSItxjJoNoC6D7CG', NULL, '品川区旗の台6-27-6', 'photoshop.png', 0, '2020-09-22 22:01:58', '2020-09-22 22:01:58'),
(149, 'test1', 'test1@gmail.com', '$2y$10$TTLEYG2VwWGRzdu.UItkSOcmbzG.CIRT6ScjPLhz5tPX1aEGBUzF6', NULL, 'test1test1', 'nike6.jpg', 0, '2020-09-23 21:37:35', '2020-09-23 21:37:35'),
(150, 'test2', 'test2@gmail.com', '$2y$10$WK34TW7sIokMzdJrD.JIxO5iROZUa3P9Al1qygJ6x3ybXoNjmI/Yy', NULL, 'test2test2', 'nike9.jpg', 0, '2020-09-23 21:57:08', '2020-09-23 21:57:08'),
(151, 'test3', 'test3@test.com', '$2y$10$yA3Wt7LpduGHTtxcdvDNYOuYTtwQwRI9ZDHTvk.mDf47dtGbtRVY2', NULL, 'test3test3', 'nike6.jpg', 0, '2020-09-23 22:26:35', '2020-09-23 22:26:35'),
(152, 'test4', 'test4@gmail.com', '$2y$10$S1.7LLEL2PqGmDIpctM5z.Zsf38yqbjwt1rW7upEPHKwWfASOHE7.', NULL, 'test4test4', 'nike2.jpg', 1, '2020-09-24 10:57:48', '2020-09-24 10:57:48'),
(153, 'test5', 'test5@gmail.com', '$2y$10$2i1f7GLyLb5yoDuJ.GqWw.KTfF3cvIs5NPY2CX66/TlO15Bdf9tDS', NULL, 'test5test5', 'nike3.jpg', 1, '2020-09-24 11:01:44', '2020-09-24 11:01:44'),
(185, 'test6', 'test6@gmail.com', '$2y$10$AW7sLXEczlacQy5Y7CrStu55qBW.wM.01yd0kQBuCni6WgdE3zk4S', NULL, 'test6test6', 'nike11.jpg', 1, '2020-09-24 11:44:20', '2020-09-24 11:44:20'),
(186, 'J', 'waibandl321@gmail.com', '$2y$10$ufzqiSP5p4SS/DTeDkY5rupiSukmmuZbFpWd1pTeA8rt3Esyi3dCK', NULL, '品川区旗の台6-27-6', '顔写真2020-08-31 .png', 1, '2020-09-25 12:04:34', '2020-09-25 12:04:34');

-- --------------------------------------------------------

--
-- Table structure for table `user_image`
--

CREATE TABLE `user_image` (
  `image_id` int(11) NOT NULL,
  `image_name` varchar(256) NOT NULL,
  `image_type` varchar(64) NOT NULL,
  `image_content` mediumblob NOT NULL,
  `image_size` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `item_images`
--
ALTER TABLE `item_images`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_image`
--
ALTER TABLE `user_image`
  ADD PRIMARY KEY (`image_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `item_images`
--
ALTER TABLE `item_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=187;

--
-- AUTO_INCREMENT for table `user_image`
--
ALTER TABLE `user_image`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT;
