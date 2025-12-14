-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Nov 26, 2025 at 11:42 AM
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
-- Database: `rumahjamur`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `user_id`, `product_id`, `quantity`, `created_at`) VALUES
(5, 1, 3, 3, '2025-11-26 10:17:27'),
(6, 1, 6, 3, '2025-11-26 10:19:24');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(50) DEFAULT NULL,
  `customer_address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `price` int(11) NOT NULL,
  `image_url` text NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image_url`, `description`, `created_at`) VALUES
(1, 'Nugget Jamur Kancing', 24000, 'https://asset.kompas.com/crops/FqTvdtPsNQ16JNwYcNpzSXGilAw=/2x0:1000x665/1200x800/data/photo/2021/01/03/5ff13e6d7a703.jpg', 'Nugget Jamur Kancing yang enak dan krispi\r\n(isi 20 pcs)', '2025-11-17 11:16:00'),
(2, 'Nugget Jamur Tiram', 20000, 'https://asset.kompas.com/crops/qqOZdYMKMQJDX8psZPzexDhXiSg=/74x0:919x563/1200x800/data/photo/2020/09/29/5f73528912598.jpg', 'Nugget Jamur Tiram yang enak dan krispi\r\n(isi 20 pcs)', '2025-11-17 11:16:00'),
(3, 'Pentol Jamur Kuping', 15000, 'https://assets.promediateknologi.id/crop/35x324:697x1293/750x500/webp/photo/2023/06/18/Screenshot_20230618_234913-2165192163.jpg', 'Pentol Jamur Kuping yang kenyal dan bergizi\r\n(isi 18 pcs)', '2025-11-17 11:16:00'),
(4, 'Nugget Jamur Tiram', 20000, 'https://asset.kompas.com/crops/qqOZdYMKMQJDX8psZPzexDhXiSg=/74x0:919x563/1200x800/data/photo/2020/09/29/5f73528912598.jpg', 'Nugget Jamur Tiram yang enak dan krispi\r\n(isi 20 pcs)', '2025-11-17 11:16:00'),
(5, 'Cireng Jamur Tiram', 10000, 'https://cdn.yummy.co.id/content-images/images/20200712/1gW8zLtvjeojDiNbBjynZHGEpjDCYf0z-31353934353633383633d41d8cd98f00b204e9800998ecf8427e.jpg', 'Cireng Jamur Tiram yang krspi dan kenyal\r\n(isi 15 pcs)', '2025-11-17 11:16:00'),
(6, 'Pentol Jamur Kuping', 15000, 'https://assets.promediateknologi.id/crop/35x324:697x1293/750x500/webp/photo/2023/06/18/Screenshot_20230618_234913-2165192163.jpg', 'Pentol Jamur Kuping yang kenyal dan bergizi\r\n(isi 18 pcs)', '2025-11-17 11:16:00'),
(7, 'Dimsum Jamur Kuping', 20000, 'https://filebroker-cdn.lazada.co.id/kf/S453c5d07e5d44257b84a0edfdccbab28n.jpg', 'Dimsum Jamur Kuping yang lezat dan sehat\r\n(isi 10 pcs)', '2025-11-17 11:16:00'),
(8, 'Nugget Jamur Kancing', 24000, 'https://asset.kompas.com/crops/FqTvdtPsNQ16JNwYcNpzSXGilAw=/2x0:1000x665/1200x800/data/photo/2021/01/03/5ff13e6d7a703.jpg', 'Nugget Jamur Kancing yang enak dan krispi\r\n(isi 20 pcs)', '2025-11-17 11:16:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(1, 'tester', 'tester@email.id', '$2y$10$7Ult.6VQzhn8aRZNxcWU2O.Z6HuYvNHmBkHnQfQkRxxX8hFuUkVeC');
(2, 'hai', 'admin@admin.com', '$2y$10$t4y8wwPeUgdgmYKbHNkjzOOM/hIZEeRow4aCxWl3SXMCTkbXdFqBe');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

CREATE TABLE payments (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  order_id VARCHAR(100) NOT NULL,
  amount INT NOT NULL,
  currency VARCHAR(8) DEFAULT 'IDR',
  provider VARCHAR(64) DEFAULT 'sandbox',
  provider_tx_id VARCHAR(128) DEFAULT NULL,
  status ENUM('pending','paid','failed') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_order_id (order_id)
);

ALTER TABLE orders
  ADD COLUMN status ENUM('pending','paid','failed') NOT NULL DEFAULT 'pending';

ALTER TABLE orders
ADD COLUMN midtrans_order_id VARCHAR(100) UNIQUE;