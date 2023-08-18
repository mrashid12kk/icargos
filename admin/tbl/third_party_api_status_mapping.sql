-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 31, 2020 at 10:46 AM
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
-- Table structure for table `third_party_api_status_mapping`
--

CREATE TABLE `third_party_api_status_mapping` (
  `id` int(11) NOT NULL,
  `status_id` int(11) DEFAULT NULL,
  `api_provider_id` int(11) DEFAULT NULL,
  `api_status` varchar(255) DEFAULT NULL,
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `third_party_api_status_mapping`
--

INSERT INTO `third_party_api_status_mapping` (`id`, `status_id`, `api_provider_id`, `api_status`, `created_on`) VALUES
(38, 19, 1, 'Return - Arrived at Origin', '2020-11-13 05:05:44'),
(3, 1, 1, 'Shipment - Booked', '2020-10-20 18:03:19'),
(34, 4, 1, 'Shipment - Arrived at Origin', '2020-11-09 12:34:36'),
(33, 5, 1, 'Shipment - In Transit', '2020-11-09 12:33:32'),
(32, 6, 1, 'Shipment - Arrived at Destination', '2020-11-07 10:00:24'),
(36, 7, 1, 'Shipment - Out for Delivery', '2020-11-12 07:03:20'),
(30, 8, 1, 'Shipment - Delivered', '2020-11-07 09:48:02'),
(29, 18, 1, 'Shipment - Re-Attempt', '2020-11-07 09:40:03'),
(26, 19, 1, 'Return - Delivered to Shipper', '2020-11-07 08:02:44'),
(37, 16, 1, 'Shipment - Cancelled', '2020-11-13 05:02:57'),
(39, 21, 1, 'Shipment - Rider Picked', '2020-11-13 05:06:52'),
(40, 22, 1, 'Shipment - Re-Attempt Requested', '2020-11-13 05:07:48'),
(41, 23, 1, 'Return - In Transit', '2020-11-13 05:08:37'),
(42, 26, 1, 'Intercept Requested', '2020-11-13 05:09:40'),
(43, 26, 1, 'Intercept Approved', '2020-11-13 05:10:13'),
(44, 26, 1, 'Replacement-Not Collected', '2020-11-13 05:11:33'),
(45, 26, 1, 'Replacement - Arrived at Origin', '2020-11-13 05:12:25'),
(46, 26, 1, 'Replacement - Collected', '2020-11-13 05:13:19'),
(47, 26, 1, 'Return - Delivery Unsuccessful', '2020-11-13 05:13:59'),
(48, 26, 1, 'Replacement - Dispatched', '2020-11-13 05:14:37'),
(49, 26, 1, 'Replacement - In Transit', '2020-11-13 05:15:17'),
(50, 26, 1, 'Replacement - Delivered to Shipper', '2020-11-13 05:15:58'),
(51, 26, 1, 'Return - Confirmation Pending', '2020-11-13 05:16:41'),
(52, 26, 1, 'Replacement - Delivery Unsuccessful', '2020-11-13 05:19:00'),
(53, 26, 1, 'Return - Dispatched', '2020-11-13 05:19:40'),
(54, 26, 1, 'Return - Not Attempted', '2020-11-13 05:20:19'),
(55, 26, 1, 'Return - On Hold', '2020-11-13 05:21:02'),
(56, 26, 1, 'Return - Confirm', '2020-11-13 05:21:35'),
(57, 26, 1, 'Return Note Shifted', '2020-11-13 05:22:19'),
(58, 26, 1, 'Return Unsuccessful for CX and Sales', '2020-11-13 05:24:02'),
(59, 26, 1, 'Return - Rider Exchange', '2020-11-13 05:24:50'),
(60, 26, 1, 'Shipment - Lost', '2020-11-13 05:25:42'),
(61, 26, 1, 'Shipment - Misroute Forwarded', '2020-11-13 05:26:56'),
(62, 26, 1, 'Shipment - Non-Service Area', '2020-11-13 05:27:31'),
(63, 26, 1, 'Shipment - On Hold', '2020-11-13 05:29:24'),
(64, 26, 1, 'Shipment - On Hold for Self Collection', '2020-11-13 05:30:02'),
(65, 26, 1, 'Shipment - Re-Booked', '2020-11-13 05:30:47'),
(66, 26, 1, 'Shipment - Rider Exchange', '2020-11-13 05:31:23'),
(67, 26, 1, 'Shipment - Arrival Service Center', '2020-11-13 05:32:03'),
(68, 26, 1, 'Shipment - Consolidated', '2020-11-13 05:32:47'),
(69, 26, 1, 'Shipment - Case Closed', '2020-11-13 05:33:21'),
(70, 26, 1, 'Shipment - Multiple Pieces Hold', '2020-11-13 05:34:58'),
(71, 26, 1, 'Shipment - Recalled', '2020-11-13 05:35:36'),
(72, 26, 1, 'Shipment - Waiting For Consolidation', '2020-11-13 05:36:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `third_party_api_status_mapping`
--
ALTER TABLE `third_party_api_status_mapping`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `third_party_api_status_mapping`
--
ALTER TABLE `third_party_api_status_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
