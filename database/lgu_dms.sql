-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2025 at 03:02 AM
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
(1, 'StaElenaCN_SBSecretary', '$2y$10$WawE97QTa3IYWhWCNgtCReHPc/a8ZiebyEjRCC1x1KBMWOdj0h9p.', 'master', 'active', '6cd773740c5c79a01761773a481233622512d2cd0f2a9d794e7aa5878a38f299');

-- --------------------------------------------------------

--
-- Table structure for table `committee`
--

CREATE TABLE `committee` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `cmteDescription` varchar(10000) DEFAULT NULL,
  `cmteImage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `committee`
--

INSERT INTO `committee` (`id`, `name`, `cmteDescription`, `cmteImage`) VALUES
(1, 'Local Development Council', 'The Municipal Development shall have the following functions:\n1.) Formulate long-term, medium-term, and annual socio-economic development plans and policies;\n2.) Formulate the medium-term and annual public investment programs;\n3.) Appraise and prioritize socio-economic development programs and projects;\n4.) Formulate local investment incentives to promote the inflow and direction of private investment capital;\n5.) Coordinate, monitor, and evaluate the implementation of development programs and projects; and\n6.) Perform such other functions as may be provided by law or component authority.', 'images/committee/6865ceae941b5.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE `contents` (
  `id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `content_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

--
-- Dumping data for table `history_log`
--

INSERT INTO `history_log` (`id`, `action`, `file_type`, `status`, `file_id`, `title`, `timestamp`) VALUES
(1, 'Created', 'Minutes', 'Approved', 1, 'ss', '2025-06-15 04:26:16'),
(2, 'Created', 'Rules', '', 1, ' RULE I. COMPOSITION', '2025-06-17 01:53:46'),
(3, 'Deleted', 'Rules', '', 1, ' RULE I. COMPOSITION', '2025-06-17 01:54:22');

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
  `committee` varchar(50) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `status` varchar(800) NOT NULL,
  `returnNo` varchar(255) NOT NULL,
  `resolutionNo` varchar(255) NOT NULL,
  `attachment` longblob NOT NULL,
  `returnDate` date NOT NULL,
  `resolutionDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `minutes`
--

INSERT INTO `minutes` (`id`, `no_regSession`, `date`, `genAttachment`, `resNo`, `title`, `committee`, `type`, `status`, `returnNo`, `resolutionNo`, `attachment`, `returnDate`, `resolutionDate`) VALUES
(1, '1st Regular Session', '2025-06-15', '', '1', 'ss', NULL, '', 'Approved', '', 'res', '', '0000-00-00', '2025-06-15');

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
  `term` varchar(55) NOT NULL,
  `committee` varchar(55) NOT NULL,
  `committeeType` varchar(55) NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officials`
--

INSERT INTO `officials` (`id`, `parentId`, `position`, `surname`, `firstname`, `middlename`, `birthday`, `birthplace`, `address`, `mobile_number`, `email`, `gender`, `education_attainment`, `education_school`, `education_date`, `civil_status`, `spouse_name`, `spouse_birthday`, `spouse_birthplace`, `dependents`, `gsis_number`, `pagibig_number`, `philhealth_number`, `term`, `committee`, `committeeType`, `photo_path`) VALUES
(1, '', 'Vice-Mayor', 'Gadil', 'Donita Rose', 'Ilan', '2003-08-18', 'Vinzons Camarines Norte', 'Guinacutan Vinzons Camarines Norte', '09387909863', 'gadildonitarose@gmail.com', 'Female', 'College Undergraduate', 'Camarines Norte State College', 'June 2025', 'Single', 'N/A', '0000-00-00', 'N/A', 'N/A', '111111111', '111111111', '111111111', '2025 - 2028', 'Local Development Council', 'Chairman', 'images/profiles/6865c4ce2101c.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `ordinance`
--

CREATE TABLE `ordinance` (
  `id` int(255) NOT NULL,
  `mo_no` varchar(255) NOT NULL,
  `title` varchar(500) NOT NULL,
  `brgy` varchar(50) DEFAULT NULL,
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
  `brgy` varchar(50) DEFAULT NULL,
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

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subcontents`
--

CREATE TABLE `subcontents` (
  `id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `subcontent_text` text NOT NULL
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
-- Indexes for table `committee`
--
ALTER TABLE `committee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_id` (`section_id`);

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
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subcontents`
--
ALTER TABLE `subcontents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `content_id` (`content_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `committee`
--
ALTER TABLE `committee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contents`
--
ALTER TABLE `contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `history_log`
--
ALTER TABLE `history_log`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `minutes`
--
ALTER TABLE `minutes`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `officials`
--
ALTER TABLE `officials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subcontents`
--
ALTER TABLE `subcontents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contents`
--
ALTER TABLE `contents`
  ADD CONSTRAINT `contents_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subcontents`
--
ALTER TABLE `subcontents`
  ADD CONSTRAINT `subcontents_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
