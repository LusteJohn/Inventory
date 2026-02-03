-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Jan 28, 2026 at 01:36 PM
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
-- Database: `sunn_inventory_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_item`
--

CREATE TABLE `tbl_item` (
  `item_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `item_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `unit` varchar(50) NOT NULL,
  `status` enum('active','inactive') DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_item`
--

INSERT INTO `tbl_item` (`item_id`, `user_id`, `item_name`, `description`, `unit`, `status`, `created_at`, `updated_at`) VALUES
(11, 1, 'Ballpen', 'hahah', 'Pieces', 'active', '2026-01-28 11:19:51', '2026-01-28 18:19:51'),
(12, 1, 'Bond Paper', 'hahah', 'Pieces', 'active', '2026-01-28 11:20:42', '2026-01-28 18:20:42'),
(13, 1, 'Mineral Water', 'hahah', 'Liter', 'inactive', '2026-01-28 11:22:20', '2026-01-28 11:24:40'),
(14, 1, 'Asin', 'hahah', 'Grams', 'active', '2026-01-28 12:26:40', '2026-01-28 19:26:40');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_stock`
--

CREATE TABLE `tbl_stock` (
  `stock_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_stock`
--

INSERT INTO `tbl_stock` (`stock_id`, `item_id`, `quantity`, `last_updated`) VALUES
(1, 14, 150, '2026-01-28 13:20:13'),
(2, 13, 250, '2026-01-28 13:19:55');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_stock_in`
--

CREATE TABLE `tbl_stock_in` (
  `stock_in_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `date_received` datetime DEFAULT current_timestamp(),
  `received_by` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_stock_ledger`
--

CREATE TABLE `tbl_stock_ledger` (
  `ledger_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `stock_in_id` int(11) DEFAULT NULL,
  `stock_out_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `transaction_type` varchar(100) NOT NULL,
  `qty_in` int(11) NOT NULL,
  `qty_out` int(11) NOT NULL,
  `balance_after` int(11) NOT NULL,
  `transaction_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_stock_out`
--

CREATE TABLE `tbl_stock_out` (
  `stock_out_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `requested_by` varchar(100) NOT NULL,
  `purpose` text NOT NULL,
  `date_released` datetime DEFAULT current_timestamp(),
  `released_by` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Staff') NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `fullname`, `username`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Admin, Admin Admin', 'Admin', '$2y$10$ONwWDk1KVf9N8vQa9H0q8O21fWir2E1AB8g6qnRmKOIYoMbFNTy9W', 'Admin', 'active', '2026-01-27 22:27:47', '2026-01-27 22:27:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_item`
--
ALTER TABLE `tbl_item`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_stock`
--
ALTER TABLE `tbl_stock`
  ADD PRIMARY KEY (`stock_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `tbl_stock_in`
--
ALTER TABLE `tbl_stock_in`
  ADD PRIMARY KEY (`stock_in_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `tbl_stock_ledger`
--
ALTER TABLE `tbl_stock_ledger`
  ADD PRIMARY KEY (`ledger_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `stock_in_id` (`stock_in_id`),
  ADD KEY `stock_out_id` (`stock_out_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_stock_out`
--
ALTER TABLE `tbl_stock_out`
  ADD PRIMARY KEY (`stock_out_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_item`
--
ALTER TABLE `tbl_item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_stock`
--
ALTER TABLE `tbl_stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_stock_in`
--
ALTER TABLE `tbl_stock_in`
  MODIFY `stock_in_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_stock_ledger`
--
ALTER TABLE `tbl_stock_ledger`
  MODIFY `ledger_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_stock_out`
--
ALTER TABLE `tbl_stock_out`
  MODIFY `stock_out_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_item`
--
ALTER TABLE `tbl_item`
  ADD CONSTRAINT `tbl_item_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`);

--
-- Constraints for table `tbl_stock`
--
ALTER TABLE `tbl_stock`
  ADD CONSTRAINT `tbl_stock_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `tbl_item` (`item_id`);

--
-- Constraints for table `tbl_stock_in`
--
ALTER TABLE `tbl_stock_in`
  ADD CONSTRAINT `tbl_stock_in_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `tbl_item` (`item_id`);

--
-- Constraints for table `tbl_stock_ledger`
--
ALTER TABLE `tbl_stock_ledger`
  ADD CONSTRAINT `tbl_stock_ledger_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `tbl_item` (`item_id`),
  ADD CONSTRAINT `tbl_stock_ledger_ibfk_2` FOREIGN KEY (`stock_in_id`) REFERENCES `tbl_stock_in` (`stock_in_id`),
  ADD CONSTRAINT `tbl_stock_ledger_ibfk_3` FOREIGN KEY (`stock_out_id`) REFERENCES `tbl_stock_out` (`stock_out_id`),
  ADD CONSTRAINT `tbl_stock_ledger_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`);

--
-- Constraints for table `tbl_stock_out`
--
ALTER TABLE `tbl_stock_out`
  ADD CONSTRAINT `tbl_stock_out_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `tbl_item` (`item_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
