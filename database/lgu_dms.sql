-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 07, 2025 at 01:44 PM
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
(1, 'StaElenaCN_SBSecretary', '$2y$10$WawE97QTa3IYWhWCNgtCReHPc/a8ZiebyEjRCC1x1KBMWOdj0h9p.', 'master', 'active', '');

-- --------------------------------------------------------

--
-- Table structure for table `committee`
--

CREATE TABLE `committee` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `cmteDescription` varchar(100) DEFAULT NULL,
  `cmteImage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `committee`
--

INSERT INTO `committee` (`id`, `name`, `cmteDescription`, `cmteImage`) VALUES
(3, 'Sample committee', 'Sample Description new test', ''),
(4, 'new committee', 'description', 'images/committee/686772efa601f.png');

-- --------------------------------------------------------

--
-- Table structure for table `committee_reports`
--

CREATE TABLE `committee_reports` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `committee_category` varchar(255) DEFAULT NULL,
  `committee_section` varchar(255) DEFAULT NULL,
  `councilor` varchar(255) NOT NULL,
  `date_report` date NOT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `committee_reports`
--

INSERT INTO `committee_reports` (`id`, `title`, `committee_category`, `committee_section`, `councilor`, `date_report`, `attachment_path`, `created_at`) VALUES
(6, 'draft new', 'Economic', 'Committee on Cooperatives, People’s Organization and Non-Government Organizations', 'mass motion', '2025-07-05', 'uploads/resolution_report.pdf', '2025-07-04 10:38:47'),
(8, 'report', 'Infrastructure', 'Committee on Public Works and Infrastructure', 'doni', '2025-07-04', 'uploads/resolution_report.pdf', '2025-07-04 10:39:58'),
(9, 'com report', 'Social', 'Committee on Youth and SK Affairs', 'new', '2025-07-25', 'uploads/resolution_report.pdf', '2025-07-04 10:47:00'),
(12, 'final', 'Economic', 'Committee on Agriculture and Livelihood', 'councilorrr', '2025-07-17', 'uploads/resolution_report (1).pdf', '2025-07-04 10:49:16'),
(13, 'with the var title', 'Infrastructure', 'Committee on Public Works and Infrastructure', 'mass motion', '2025-07-05', 'uploads/resolution_report.pdf', '2025-07-05 02:35:20'),
(14, 'with the var title', 'Infrastructure', 'Committee on Public Works and Infrastructure', 'mass motion', '2025-07-05', 'uploads/resolution_report.pdf', '2025-07-05 02:35:20'),
(15, 'report draft for title edit', 'Economic', 'Committee on Cooperatives, People’s Organization and Non-Government Organizations', 'new', '2025-07-17', 'uploads/resolution_report (3).pdf', '2025-07-05 03:39:09');

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE `contents` (
  `id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `content_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contents`
--

INSERT INTO `contents` (`id`, `section_id`, `content_text`) VALUES
(16, 6, 'trial title'),
(17, 6, 'trial title'),
(18, 7, 'section content test'),
(19, 7, 'section content test'),
(22, 8, 'asdfghjkl'),
(23, 8, 'asdfghjkl'),
(24, 9, 'abc'),
(25, 9, 'abc'),
(26, 10, 'efg'),
(29, 12, 'zxcvb'),
(32, 1, 'new section content sample'),
(33, 1, 'new section sample 2 edited new');

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
(1, 'Created', 'Rules', '', NULL, 'no 1 title', '2025-06-16 02:46:57'),
(2, 'Created', 'Resolution', '', 1, 'new', '2025-06-16 02:55:17'),
(5, 'Created', 'Rules', '', 15, 'new new title', '2025-06-16 04:20:33'),
(6, 'Created', 'Rules', '', 17, 'trial title', '2025-06-16 04:32:36'),
(8, 'Created', 'Rules', '', 21, 'qwerty', '2025-06-16 07:18:01'),
(9, 'Edited', 'Resolution', '', 1, 'new', '2025-06-16 07:52:15'),
(10, 'Edited', 'Rules', '', 8, 'qwerty new', '2025-06-16 08:17:18'),
(11, 'Created', 'Rules', '', 25, 'new try title abc', '2025-06-16 08:25:06'),
(13, 'Created', 'Rules', '', 28, 'trial title', '2025-06-16 08:27:44'),
(15, 'Created', 'Rules', '', 13, 'rule test title', '2025-06-16 08:33:54'),
(16, 'Created', 'Rules', '', 14, '1234', '2025-06-16 08:43:38'),
(17, 'Deleted', 'Rules', '', 13, 'rule test title', '2025-06-16 10:52:44'),
(18, 'Edited', 'Rules', '', 1, 'new rule title sample working', '2025-06-16 10:54:06'),
(19, 'Deleted', 'Rules', '', 3, 'try', '2025-06-16 10:54:27'),
(20, 'Edited', 'Rules', '', 5, 'new new title edited working', '2025-06-16 10:55:31'),
(21, 'Deleted', 'Rules', '', 11, 'trial title', '2025-06-16 10:55:57'),
(22, 'Deleted', 'Rules', '', 5, 'new new title edited working', '2025-06-16 10:57:50'),
(23, 'Edited', 'Resolution', '', 1, 'new', '2025-06-18 09:44:50'),
(24, 'Created', 'Resolution', '', 2, 'Resolution 1', '2025-07-04 02:24:53'),
(25, 'Created', 'Resolution', '', 3, 'Resolution 2', '2025-07-04 02:26:48'),
(26, 'Created', 'Resolution', '', 4, 'Resolution 3', '2025-07-04 02:29:14'),
(27, 'Created', 'Ordinance', '', 1, 'Ordinance 1', '2025-07-04 02:35:47'),
(28, 'Edited', 'Resolution', '', 2, 'Resolution 1', '2025-07-04 02:37:56'),
(29, 'Edited', 'Resolution', '', 2, 'Resolution 1', '2025-07-04 02:39:15'),
(30, 'Edited', 'Resolution', '', 3, 'Resolution 2', '2025-07-04 02:41:16'),
(31, 'Edited', 'Resolution', '', 4, 'Resolution 3', '2025-07-04 02:41:49'),
(32, 'Edited', 'Ordinance', '', 1, 'Ordinance 1', '2025-07-04 02:42:30'),
(33, 'Created', 'Committee Report', '', 2, NULL, '2025-07-04 09:43:21'),
(34, 'Created', 'Committee Report', '', 4, NULL, '2025-07-04 10:32:10'),
(35, 'Created', 'Committee Report', '', 6, NULL, '2025-07-04 10:38:47'),
(36, 'Created', 'Committee Report', '', 8, NULL, '2025-07-04 10:39:58'),
(37, 'Created', 'Committee Report', '', 10, NULL, '2025-07-04 10:47:00'),
(38, 'Created', 'Committee Report', '', 12, NULL, '2025-07-04 10:49:16'),
(39, 'Created', 'Committee Report', '', 14, 'with the var title', '2025-07-05 02:35:20'),
(40, 'Edited', 'Resolution', '', 1, 'new', '2025-07-05 03:07:03'),
(41, 'Created', 'Committee Report', '', 15, 'report draft for title', '2025-07-05 03:39:09'),
(42, 'Edited', 'Committee Report', '', 15, 'report draft for title edit', '2025-07-05 04:35:53'),
(43, 'Edited', 'Committee Report', '', 15, 'report draft for title edit', '2025-07-05 04:36:33'),
(44, 'Edited', 'Committee Report', '', 15, 'report draft for title edit', '2025-07-05 04:36:58'),
(45, 'Deleted', 'Committee Report', '', 10, 'com report', '2025-07-05 05:03:09'),
(46, 'Deleted', 'Committee Report', '', 3, 'draft', '2025-07-05 05:03:24'),
(47, 'Deleted', 'Committee Report', '', 4, 'draft', '2025-07-05 05:03:46'),
(48, 'Deleted', 'Committee Report', '', 5, 'draft new', '2025-07-05 05:03:50'),
(49, 'Deleted', 'Committee Report', '', 1, 'bew', '2025-07-05 05:04:00'),
(50, 'Deleted', 'Committee Report', '', 7, 'report', '2025-07-05 05:04:05'),
(51, 'Edited', 'Committee Report', '', 9, 'com report', '2025-07-05 05:04:20'),
(52, 'Edited', 'Committee Report', '', 6, 'draft new', '2025-07-05 05:04:35'),
(53, 'Deleted', 'Committee Report', '', 11, 'final', '2025-07-05 05:04:46'),
(54, 'Deleted', 'Committee Report', '', 2, 'bew', '2025-07-05 05:05:16'),
(55, 'Edited', 'Committee Report', '', 8, 'report', '2025-07-05 05:05:29');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `term` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `committee_id` int(11) DEFAULT NULL
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
  `committee` varchar(50) DEFAULT NULL,
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
  `term` varchar(55) NOT NULL,
  `committee` varchar(55) NOT NULL,
  `committeeType` varchar(55) NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officials`
--

INSERT INTO `officials` (`id`, `parentId`, `position`, `surname`, `firstname`, `middlename`, `birthday`, `birthplace`, `address`, `mobile_number`, `email`, `gender`, `education_attainment`, `education_school`, `education_date`, `civil_status`, `spouse_name`, `spouse_birthday`, `spouse_birthplace`, `dependents`, `gsis_number`, `pagibig_number`, `philhealth_number`, `term`, `committee`, `committeeType`, `photo_path`) VALUES
(1, '', 'Vice-Mayor', 'Doe', 'John', '', '2000-01-01', 'Sta. Elena', 'Sta. Elena', '09091234567', 'john@gmail.com', 'Male', '', '', '', 'Single', '', '0000-00-00', '', '', '100', '200', '300', '2025-2028', 'SB', 'Chairman', 'images/profiles/68674174ef06f.png');

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

--
-- Dumping data for table `ordinance`
--

INSERT INTO `ordinance` (`id`, `mo_no`, `title`, `brgy`, `date_adopted`, `author_sponsor`, `remarks`, `notes`, `date_fwd`, `date_signed`, `sp_resoNo`, `sp_approval`, `attachment`) VALUES
(1, '1', 'Ordinance 1', 'San Pedro', '2025-06-08', 'Mass motion', 'Signed by LCE', '', '0000-00-00', '2025-06-10', '', '', 0x75706c6f6164732f7265736f6c7574696f6e5f7265706f7274202834292e706466);

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

--
-- Dumping data for table `resolution`
--

INSERT INTO `resolution` (`id`, `reso_no`, `title`, `brgy`, `descrip`, `d_adopted`, `author_sponsor`, `co_author`, `remarks`, `d_forward`, `d_signed`, `sp_resoNo`, `d_approved`, `attachment`, `notes`) VALUES
(1, '078 s.2012', 'new', 'Salvacion', '', '2025-06-16', 'mass motion', 'try', 'Forwarded to LCE', '2025-06-16', '0000-00-00', '', '0000-00-00', '', ''),
(2, '1', 'Resolution 1', 'Rizal', '', '2025-06-01', 'Mass Motion', '', 'Signed by LCE', '0000-00-00', '2025-06-02', '', '0000-00-00', 0x75706c6f6164732f7265736f6c7574696f6e5f7265706f72742e706466, ''),
(3, '2', 'Resolution 2', 'San Lorenzo', '', '2025-06-03', 'Mass Motion', '', 'Signed by LCE', '0000-00-00', '2025-06-04', '', '0000-00-00', 0x75706c6f6164732f7265736f6c7574696f6e5f7265706f7274202832292e706466, ''),
(4, '3', 'Resolution 3', 'Bulala', '', '2025-06-05', 'Mass Motion', '', 'Signed by LCE', '0000-00-00', '2025-06-07', '', '0000-00-00', 0x75706c6f6164732f7265736f6c7574696f6e5f7265706f7274202833292e706466, '');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `title`) VALUES
(1, 'new rule title sample working'),
(6, 'trial title'),
(7, 'test title'),
(8, 'qwerty new'),
(9, 'new try title abc'),
(10, 'abcs'),
(12, 'new try title for today');

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
-- Dumping data for table `subcontents`
--

INSERT INTO `subcontents` (`id`, `content_id`, `subcontent_text`) VALUES
(1, 26, 'hijk'),
(2, 26, 'hijk');

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
-- Indexes for table `committee_reports`
--
ALTER TABLE `committee_reports`
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
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id`),
  ADD KEY `committee_id` (`committee_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `committee_reports`
--
ALTER TABLE `committee_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `contents`
--
ALTER TABLE `contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `history_log`
--
ALTER TABLE `history_log`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `minutes`
--
ALTER TABLE `minutes`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `officials`
--
ALTER TABLE `officials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ordinance`
--
ALTER TABLE `ordinance`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `resolution`
--
ALTER TABLE `resolution`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
-- Constraints for table `member`
--
ALTER TABLE `member`
  ADD CONSTRAINT `member_ibfk_1` FOREIGN KEY (`committee_id`) REFERENCES `committee` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `subcontents`
--
ALTER TABLE `subcontents`
  ADD CONSTRAINT `subcontents_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
