-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 10.11.38.120:3306
-- Generation Time: Jun 24, 2025 at 02:43 PM
-- Server version: 10.0.38-MariaDB
-- PHP Version: 8.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `supp0rttest_iibf_staging`
--

-- --------------------------------------------------------

--
-- Table structure for table `verify_otp_citap`
--

CREATE TABLE `verify_otp_citap` (
  `otp_id` bigint(20) NOT NULL,
  `regnumber` text NOT NULL,
  `email` text NOT NULL,
  `mobile` text NOT NULL,
  `user_otp` text NOT NULL,
  `otp_remove` enum('y','n') NOT NULL COMMENT 'y=yes, n=no',
  `user_otp_send_on` timestamp NULL DEFAULT NULL,
  `user_otp_expired_on` timestamp NULL DEFAULT NULL,
  `is_otp_verified` enum('y','n') NOT NULL DEFAULT 'n' COMMENT 'y=yes, n=no',
  `otp_verified_on` timestamp NULL DEFAULT NULL,
  `user_wrong_otp_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `verify_otp_citap`
--
ALTER TABLE `verify_otp_citap`
  ADD PRIMARY KEY (`otp_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `verify_otp_citap`
--
ALTER TABLE `verify_otp_citap`
  MODIFY `otp_id` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
