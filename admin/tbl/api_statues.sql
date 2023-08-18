-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 31, 2020 at 10:41 AM
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
-- Table structure for table `api_statues`
--

CREATE TABLE `api_statues` (
  `id` int(11) NOT NULL,
  `api_id` bigint(20) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `api_statues`
--

INSERT INTO `api_statues` (`id`, `api_id`, `status`) VALUES
(1, 1, 'Shipment - Booked'),
(2, 1, 'Shipment - Arrived at Origin'),
(3, 1, 'Shipment - In Transit'),
(4, 1, 'Shipment - Arrived at Destination'),
(5, 1, 'Shipment - Out for Delivery'),
(6, 1, 'Shipment - Rider Exchange'),
(7, 1, 'Shipment - Not Attempted'),
(8, 1, 'Shipment - Delivery Unsuccessful'),
(9, 1, 'Shipment - On Hold'),
(10, 1, 'Shipment - Non-Service Area'),
(11, 1, 'Shipment - Misrouted'),
(12, 1, 'Return - Confirmation Pending'),
(13, 1, 'Shipment - Re-Attempt'),
(14, 1, 'Shipment - Delivered'),
(15, 1, 'Shipment - On Hold for Self Collection'),
(16, 1, 'Shipment - Cancelled'),
(17, 1, 'Shipment - Lost'),
(18, 1, 'Shipment - Re-Booked'),
(19, 1, 'Return - Confirm'),
(20, 1, 'Return - In Transit'),
(21, 1, 'Return - Arrived at Origin'),
(22, 1, 'Return - Dispatched'),
(23, 1, 'Return - Delivery Unsuccessful'),
(24, 1, 'Return - Delivered to Shipper'),
(25, 1, 'Replacement - In Transit'),
(26, 1, 'Replacement - Arrived at Origin'),
(27, 1, 'Replacement - Dispatched'),
(28, 1, 'Replacement - Delivery Unsuccessful'),
(29, 1, 'Replacement - Collected'),
(30, 1, 'Replacement - Delivered to Shipper'),
(31, 1, 'Return - Rider Exchange'),
(32, 1, 'Replacement - Rider Exchange'),
(33, 1, 'Return - Not Attempted'),
(34, 1, 'Return - On Hold'),
(35, 1, 'Shipment - Misroute Forwarded'),
(36, 1, 'Shipment - Recalled'),
(37, 1, 'Shipment - Case Closed'),
(38, 1, 'Shipment - Re-Attempt Requested'),
(39, 1, 'Shipment - Rider Picked'),
(40, 1, 'Intercept Requested'),
(41, 1, 'Intercept Approved'),
(42, 1, 'Replacement-Not Collected'),
(43, 1, 'Return Note Shifted'),
(44, 1, 'Shipment - Waiting For Consolidation'),
(45, 1, 'Shipment - Consolidated'),
(46, 1, 'Return Unsuccessful for CX and Sales'),
(47, 1, 'Shipment - Arrival Service Center'),
(48, 1, 'Shipment - Multiple Pieces Hold');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api_statues`
--
ALTER TABLE `api_statues`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `api_statues`
--
ALTER TABLE `api_statues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
