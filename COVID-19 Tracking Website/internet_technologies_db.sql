-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2021 at 02:28 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `internet_technologies_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `productsId` int(11) NOT NULL,
  `productsQuantity` int(8) NOT NULL DEFAULT 0,
  `productsPrice` decimal(8,2) NOT NULL,
  `productsName` varchar(128) NOT NULL COMMENT 'This field is for the product''s name or model.',
  `productsDescription` text DEFAULT NULL COMMENT 'This field is for very simple and general object description.',
  `productsImageFilepath` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`productsId`, `productsQuantity`, `productsPrice`, `productsName`, `productsDescription`, `productsImageFilepath`) VALUES
(6, 1, '5111.00', 'Supreme Logo', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Recusandae consequuntur iure, nesciunt vel magnam quisquam omnis quas at eveniet nulla suscipit itaque, odit tempore. Harum optio voluptates quod rerum quo.Lorem ipsum, dolor sit amet consectetur adipisicing elit. Recusandae consequuntur iure, nesciunt vel magnam quisquam omnis quas at eveniet nulla suscipit itaque, odit tempore. Harum optio voluptates quod rerum quo.Lorem ipsum, dolor sit amet consectetur adipisicing elit. Recusandae consequuntur iure, nesciunt vel magnam quisquam omnis quas at eveniet nulla suscipit itaque, odit tempore. Harum optio voluptates quod rerum quo. Lorem ipsum, dolor sit amet consectetur adipisicing elit. Recusandae consequuntur iure, nesciunt vel magnam quisquam omnis quas at eveniet nulla suscipit itaque, odit tempore. Harum optio voluptates quod rerum quo. Lorem ipsum, dolor sit amet consectetur adipisicing elit. Recusandae consequuntur iure, nesciunt vel magnam quisquam omnis quas at eveniet nulla suscipit itaque, odit tempore. Harum optio voluptates quod rerum quo.Lorem ipsum, dolor sit amet consectetur adipisicing elit. Recusandae consequuntur iure, nesciunt vel magnam quisquam omnis quas at eveniet nulla suscipit itaque, odit tempore. Harum optio voluptates quod rerum quo.', '../img/store/productID_60a20337d5877.png'),
(7, 25, '521.00', 'Gabe Newell our Lord and Saviour', 'Bless Him', '../img/store/productID_60a39e50a145f.jpg'),
(8, 150, '49.99', 'XM-14 Running Shoes', 'Good Running Shoes', '../img/store/productID_60a315a58259b.jpeg'),
(10, 200, '21.00', '2 kilogram dumbells', 'Pink Dumbells', '../img/store/productID_60a42251aa24c.jpeg'),
(11, 0, '31.50', 'Predator™ Boxing Gloves', 'Good Quality Boxing Gloves', '../img/store/productID_60a4228959235.jpeg'),
(12, 400, '41.99', 'Predator™ Black Women\'s T-Shirt', '', '../img/store/productID_60a422ae0b2a0.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `usersId` int(9) NOT NULL,
  `usersUsername` varchar(128) NOT NULL,
  `usersEmail` varchar(128) NOT NULL,
  `usersPass` varchar(128) NOT NULL,
  `usersAdminStatus` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Boolean value indicating the status of the user- 0 (FALSE) for regular user, 1 for admin.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`usersId`, `usersUsername`, `usersEmail`, `usersPass`, `usersAdminStatus`) VALUES
(2, 'Vladen', 'bSmith@gmail.com', '$2y$10$3Njyz9Xp1ZjNfURon5eEJ.ocRoAvy.Bk4L0ObXeOlTklKLEhUqLsq', 0),
(3, 'dimitar', 'john_doe@gmil.com', '$2y$10$m4qVZYSQXWDSPEEo/rFZJe0QCqEjzTkv0qX2c0flApnvvNc.lUVYC', 1),
(6, 'dggaydad', 'dggaydad@bradford.co.uk', '$2y$10$CH56peq10TIdPvmlxBDLVO2dTqWkGijt0VZvr3nIn2Bh2gCMU1D2y', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`productsId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`usersId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `productsId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `usersId` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
