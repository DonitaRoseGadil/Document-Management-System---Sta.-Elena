-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2025 at 03:46 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lgu_dms`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `account_status` varchar(50) NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `email`, `password`, `role`, `account_status`, `token`) VALUES
(1, 'StaElenaCN_SBSecretary', '$2y$10$Z8mtYeQBYBrwTUGhXsaRTe..DllUrtMfdYWwAdHlbnAjltTsf59Le', 'master', 'active', 'c8468f03ff4de7bbb45720d918e8d53ba693b29469bc5f4881c9384afd75d600');

-- --------------------------------------------------------

--
-- Table structure for table `history_log`
--

CREATE TABLE `history_log` (
  `id` int(255) NOT NULL,
  `action` varchar(255) DEFAULT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  `status` varchar(800) NOT NULL,
  `file_id` int(255) DEFAULT NULL,
  `title` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `minutes`
--

CREATE TABLE `minutes` (
  `id` int(255) NOT NULL,
  `no_regSession` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `genAttachment` longblob NOT NULL,
  `resNo` varchar(255) NOT NULL,
  `title` varchar(2000) NOT NULL,
  `type` varchar(255) NOT NULL,
  `status` varchar(800) NOT NULL,
  `returnNo` varchar(255) NOT NULL,
  `resolutionNo` varchar(255) NOT NULL,
  `attachment` longblob NOT NULL,
  `returnDate` date NOT NULL,
  `resolutionDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `officials`
--

CREATE TABLE `officials` (
  `id` int(11) NOT NULL,
  `parentId` varchar(5) NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `surname` varchar(100) DEFAULT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `birthplace` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `education_attainment` varchar(255) DEFAULT NULL,
  `education_school` varchar(255) DEFAULT NULL,
  `education_date` varchar(100) DEFAULT NULL,
  `civil_status` enum('Single','Married','Widowed','Divorced') DEFAULT NULL,
  `spouse_name` varchar(255) DEFAULT NULL,
  `spouse_birthday` date DEFAULT NULL,
  `spouse_birthplace` varchar(255) DEFAULT NULL,
  `dependents` text DEFAULT NULL,
  `gsis_number` varchar(50) DEFAULT NULL,
  `pagibig_number` varchar(50) DEFAULT NULL,
  `philhealth_number` varchar(50) DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ordinance`
--

CREATE TABLE `ordinance` (
  `id` int(255) NOT NULL,
  `mo_no` varchar(255) NOT NULL,
  `title` varchar(500) NOT NULL,
  `date_adopted` date NOT NULL,
  `author_sponsor` varchar(255) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `notes` varchar(500) NOT NULL,
  `date_fwd` date NOT NULL,
  `date_signed` date NOT NULL,
  `sp_resoNo` varchar(255) NOT NULL,
  `sp_approval` varchar(255) NOT NULL,
  `attachment` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resolution`
--

CREATE TABLE `resolution` (
  `id` int(255) NOT NULL,
  `reso_no` varchar(255) NOT NULL,
  `title` varchar(500) NOT NULL,
  `descrip` varchar(255) NOT NULL,
  `d_adopted` date NOT NULL,
  `author_sponsor` varchar(255) NOT NULL,
  `co_author` varchar(255) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `d_forward` date NOT NULL,
  `d_signed` date NOT NULL,
  `sp_resoNo` varchar(255) NOT NULL,
  `d_approved` date NOT NULL,
  `attachment` longblob DEFAULT NULL,
  `notes` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history_log`
--
ALTER TABLE `history_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `minutes`
--
ALTER TABLE `minutes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `officials`
--
ALTER TABLE `officials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ordinance`
--
ALTER TABLE `ordinance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resolution`
--
ALTER TABLE `resolution`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `history_log`
--
ALTER TABLE `history_log`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `minutes`
--
ALTER TABLE `minutes`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `officials`
--
ALTER TABLE `officials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ordinance`
--
ALTER TABLE `ordinance`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resolution`
--
ALTER TABLE `resolution`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
