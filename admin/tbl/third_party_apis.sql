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
-- Table structure for table `third_party_apis`
--

CREATE TABLE `third_party_apis` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `api_secret_key` varchar(255) DEFAULT NULL,
  `vendor_id` varchar(255) DEFAULT NULL,
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `third_party_apis`
--

INSERT INTO `third_party_apis` (`id`, `title`, `api_key`, `api_secret_key`, `vendor_id`, `created_on`, `updated_on`) VALUES
(1, 'Sonic', 'QXNkalIxNkFqaFYxaFgzNjIwWmZ5VGpEdzhNN2tnaDQ4eW9kdkl6S0p1TVRWSXhRZHZZc1VubTRaMEZa5f3fbd4ee280d', '', NULL, '2020-08-26 12:23:12', '2020-11-06 09:27:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `third_party_apis`
--
ALTER TABLE `third_party_apis`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `third_party_apis`
--
ALTER TABLE `third_party_apis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
