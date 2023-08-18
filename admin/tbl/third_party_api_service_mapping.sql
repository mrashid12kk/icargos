-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 31, 2020 at 10:55 AM
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
-- Table structure for table `third_party_api_service_mapping`
--

CREATE TABLE `third_party_api_service_mapping` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `api_service_id` int(11) NOT NULL,
  `api_provider_id` int(11) NOT NULL,
  `api_service_name` varchar(255) NOT NULL,
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `third_party_api_service_mapping`
--

INSERT INTO `third_party_api_service_mapping` (`id`, `service_id`, `service_name`, `api_service_id`, `api_provider_id`, `api_service_name`, `created_on`) VALUES
(3, 4, 'Cash On Delivery', 1, 1, 'Overnight', '2020-09-12 12:41:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `third_party_api_service_mapping`
--
ALTER TABLE `third_party_api_service_mapping`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `third_party_api_service_mapping`
--
ALTER TABLE `third_party_api_service_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
