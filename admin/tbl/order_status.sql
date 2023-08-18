-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 31, 2020 at 10:44 AM
-- Server version: 5.7.32
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `transcoi_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `order_status`
--

CREATE TABLE `order_status` (
  `sts_id` int(100) NOT NULL,
  `status` varchar(100) DEFAULT NULL,
  `color_code` varchar(10) DEFAULT NULL,
  `font_name` varchar(10) DEFAULT NULL,
  `sort_num` int(10) NOT NULL DEFAULT '0',
  `active` int(10) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_status`
--

INSERT INTO `order_status` (`sts_id`, `status`, `color_code`, `font_name`, `sort_num`, `active`) VALUES
(1, 'Pending', '#07b0f3', NULL, 0, 1),
(2, 'Customer Contacted', '#a2f207', NULL, 16, 1),
(3, 'Pick up in progress', '#3456fb', NULL, 1, 1),
(4, 'Parcel Received at office', '#cfda24', NULL, 3, 1),
(5, 'Parcel in Transit to Destination', '#24daa8', NULL, 4, 1),
(6, 'Parcel Received at Destination', '#18906f', NULL, 5, 1),
(7, 'Processing', '#e288ff', NULL, 6, 1),
(8, 'Delivered', '#3eaf2a', NULL, 9, 1),
(15, 'Returned to Shipper', '#ef524e', NULL, 8, 1),
(14, 'Parcel Return to office', '#6a106b', NULL, 13, 1),
(16, 'Discarded', '#000', NULL, 10, 1),
(17, 'Refused By Consignee', '#f77976', NULL, 7, 1),
(18, 'Re-attempt', '#ca9988', NULL, 11, 1),
(19, 'Return Received At Origin', '#ad28af', NULL, 12, 1),
(21, 'Pickup Done', '#20328a', NULL, 2, 1),
(22, 'Shipper Advice', '#678a7a', NULL, 15, 1),
(23, 'Return In Process', '#f25d07', NULL, 17, 1),
(24, 'Consignee Not Responding', '#6b0e0c', NULL, 18, 1),
(26, 'N/A', '#6b0e0c', NULL, 18, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`sts_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `order_status`
--
ALTER TABLE `order_status`
  MODIFY `sts_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
