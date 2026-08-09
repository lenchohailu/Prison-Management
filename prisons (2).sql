-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 09, 2026 at 02:05 PM
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
-- Database: `prisons`
--

-- --------------------------------------------------------

--
-- Table structure for table `archive`
--

CREATE TABLE `archive` (
  `prison_ID` varchar(15) NOT NULL,
  `prison_fname` varchar(30) NOT NULL,
  `prison_mname` varchar(30) NOT NULL,
  `prison_lname` varchar(30) NOT NULL,
  `prison_age` int(3) NOT NULL,
  `prison_gen` varchar(10) NOT NULL,
  `prison_add` varchar(50) NOT NULL,
  `prison_cont` int(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `prison_stat` varchar(20) NOT NULL,
  `Prison_Date` date NOT NULL,
  `end_date` date NOT NULL,
  `RDate` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `prison_ID` int(15) NOT NULL,
  `prison_fname` varchar(30) NOT NULL,
  `prison_mname` varchar(30) NOT NULL,
  `Date` varchar(30) NOT NULL,
  `Attendance` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`prison_ID`, `prison_fname`, `prison_mname`, `Date`, `Attendance`) VALUES
(53, 'Bona', 'Tola', '2026-05-14 21:02:07', 'Permission'),
(60, 'Kota', 'Boru', '2026-05-15 11:17:36', 'Present'),
(49, 'tola', 'chala', '2026-05-14 12:08:09', 'Present'),
(49, 'tola', 'chala', '2026-05-14 17:37:05', 'Present'),
(31, 'Badasa', 'Fedasa', '2026-05-13', 'Present'),
(40, 'abdu', 'moh', '2026-04-20 18:24:50', 'Present'),
(33, 'Chala', 'Beka', '2026-04-18 21:44:35', 'Present'),
(41, 'Sami', 'Tola', '2026-05-13 17:59:01', 'Permission'),
(15, 'qqqqqqqqqqqqqq', 'qqqqqqqqqqqqqq', '2026-01-21 22:19:25', 'Absent'),
(21, 'Kena', 'Tola', '2026-04-08 10:03:49', 'Present'),
(31, 'Badasa', 'Fedasa', '2026-05-13 15:20:56', 'Present'),
(47, 'nam', 'dida', '2026-05-13 18:46:58', 'Permission'),
(41, 'Sami', 'Tola', '2026-05-13 20:47:30', 'Present'),
(31, 'Badasa', 'Fedasa', '2026-05-13 21:01:28', 'Present');

-- --------------------------------------------------------

--
-- Table structure for table `helpdesk`
--

CREATE TABLE `helpdesk` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `issue` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `helpdesk`
--

INSERT INTO `helpdesk` (`id`, `name`, `email`, `issue`, `status`, `created_at`) VALUES
(1, 'Elias Hailu', 'lenchohailu78@gmail.com', 'Need your help', 'Pending', '2026-05-13 22:01:50'),
(2, 'Adune', 'hawitefera98@gmail.com', 'I need as you help me', 'Pending', '2026-05-14 13:32:25'),
(3, 'Bekan', 'eliyaszegeye940@gmail.com', 'please help me', 'Pending', '2026-05-15 08:03:02');

-- --------------------------------------------------------

--
-- Table structure for table `job`
--

CREATE TABLE `job` (
  `prison_ID` int(15) NOT NULL,
  `title` varchar(100) NOT NULL,
  `post` text NOT NULL,
  `postby` varchar(100) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `job`
--

INSERT INTO `job` (`prison_ID`, `title`, `post`, `postby`, `date`) VALUES
(26, 'Hard Work', 'starting from ID 40 to 50', 'bona', '2026-05-15 11:21:58'),
(25, 'Gardan', 'Starting from ID No 20-30', 'rabira', '2026-05-14 17:55:41'),
(24, 'Dig', 'they dig the ground', 'lencho', '2026-05-14 13:27:58'),
(22, 'Hard Work', 'Everybody should participate on the Job', 'lenchopo', '2026-04-17 16:39:06'),
(23, 'Haed work', 'All Team 1 should have participate on the job', 'adune', '2026-05-13 15:03:06');

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `ID` int(15) NOT NULL,
  `title` varchar(100) NOT NULL,
  `post` text NOT NULL,
  `postby` varchar(30) NOT NULL,
  `date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`ID`, `title`, `post`, `postby`, `date`) VALUES
(33, 'Hello', 'This is an urgent report', 'adugna', '2026-05-14'),
(32, 'system problem', 'it can\'t open', 'adugna', '2026-05-14'),
(25, 'Need help', 'help me', 'inspector', '2026-04-08'),
(27, 'Help me', 'Teach me about this system', 'c', '2026-04-13'),
(30, 'Need your', 'Hello Admin, I need to learn about the  system', 'lenchopo', '2026-04-17'),
(29, 'Urgeng Report', 'I need to learn about the system', 'office', '2026-04-14'),
(31, 'Urgent report', 'Make sure as every prisoner information is pretected', 'inspector', '2026-04-19');

-- --------------------------------------------------------

--
-- Table structure for table `prisoner`
--

CREATE TABLE `prisoner` (
  `prison_ID` int(20) NOT NULL,
  `prison_fname` varchar(30) NOT NULL,
  `prison_mname` varchar(30) NOT NULL,
  `prison_lname` varchar(30) NOT NULL,
  `prison_age` int(3) NOT NULL,
  `prison_gen` varchar(10) NOT NULL,
  `prison_add` text NOT NULL,
  `prison_cont` varchar(20) NOT NULL,
  `country_code` varchar(10) DEFAULT NULL,
  `previews_record` varchar(30) CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL,
  `criminal_record` text NOT NULL,
  `criminal_severity` varchar(10) NOT NULL DEFAULT 'Low',
  `prison_stat` varchar(20) NOT NULL,
  `Prison_Date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `prisoner`
--

INSERT INTO `prisoner` (`prison_ID`, `prison_fname`, `prison_mname`, `prison_lname`, `prison_age`, `prison_gen`, `prison_add`, `prison_cont`, `country_code`, `previews_record`, `criminal_record`, `criminal_severity`, `prison_stat`, `Prison_Date`, `end_date`) VALUES
(56, 'Geleta', 'Kelbesa', 'Merga', 20, 'Male', 'Woliso', '922364589', NULL, 'No', 'killer', 'Low', 'Single', '2026-05-14', '2026-05-29'),
(53, 'Bona', 'Tola', 'Kena', 28, 'Male', 'Woliso, Oromia, Ethiopia', '+251925684521', NULL, 'No', 'Cheating and conflict', 'Low', 'Single', '2026-05-14', '2027-05-14'),
(54, 'Eliyas', 'Zegeye', 'Hunde', 22, 'Male', 'Woliso, Oromia, Ethiopia', '0926213584', NULL, 'no', 'cheating', 'Low', 'Single', '2026-05-14', '2026-12-31'),
(55, 'Eliyas', 'Zegeye', 'Hunde', 22, 'Male', 'Woliso, Oromia, Ethiopia', '0926213584', NULL, 'no', 'cheating', 'Low', 'Single', '2026-05-14', '2026-12-31'),
(52, 'Bona', 'Tola', 'Kena', 28, 'Male', 'Woliso, Oromia, Ethiopia', '+251925684521', NULL, 'No', 'Cheating', 'Low', 'Single', '2026-05-14', '2027-05-14'),
(51, 'bulo', 'chala', 'hika', 20, 'Male', 'Woliso', '+251925152874', NULL, 'No', 'Serial Killer ', 'High', 'Single', '2026-05-14', '2043-02-14'),
(50, 'bikila', 'bulo', 'lama', 20, 'Male', 'Woliso, Oromia, Ethiopia', '+251936252828', NULL, 'no', 'suspiciouss', 'Low', 'Single', '2026-05-14', '2027-01-16'),
(49, 'tola', 'chala', 'beka', 22, 'Male', 'Woliso, Oromia, Ethiopia', '+251983909452', NULL, 'No', 'cheat material', 'Low', 'Single', '2026-05-14', '4053-10-28'),
(36, 'Beka', 'Kena', 'Mola', 25, 'Male', 'Waliso', '9452614', NULL, 'No', '', 'Low', 'Single', '2026-04-17', '2027-06-16'),
(37, 'Roba', 'Beka', 'Chala', 26, 'Male', 'Woliso, Oromia, Ethiopia', '914582365', NULL, 'No', 'Cheating  criminal', 'Low', 'Married', '2026-04-20', '2026-12-30'),
(57, 'Geleta', 'Kelbesa', 'Merga', 20, 'Male', 'Woliso', '922364589', NULL, 'No', 'killer', 'Low', 'Single', '2026-05-14', '2026-05-29'),
(39, 'Bona', 'Beka', 'Bortola', 27, 'Male', 'Woliso, Oromia, Ethiopia', '942156841', NULL, 'No', 'Killing criminal', 'High', 'Single', '2026-04-20', '2034-01-20'),
(58, 'tola', 'chala', 'beka', 20, 'Male', 'Woliso', '95682146+68', NULL, 'No', 'High problem that contain big problem', 'High', 'Single', '2026-05-15', '2030-06-14'),
(41, 'Sami', 'Tola', 'Bula', 18, 'Male', 'Woliso, Oromia, Ethiopia', '9458946582564', NULL, 'No', 'Cheating on Matterial', 'Low', 'Single', '2026-05-13', '2027-07-18'),
(42, 'kuma', 'tola', 'merga', 25, 'Male', 'welkite,oromia,02', '925368548', NULL, 'yes', 'konkoolatan walitti bu\\\'e', 'Medium', 'Single', '2026-05-13', '2027-10-14'),
(59, 'Bona', 'chala', 'beka', 20, 'Male', 'Woliso', '+251915478965', NULL, 'no', 'Cheating', 'Low', 'Single', '2026-05-15', '2027-10-13'),
(60, 'Kota', 'Boru', 'Bona', 25, 'Male', 'Woliso, Oromia, Ethiopia', '+251929477247', NULL, 'no', 'cheating', 'Low', 'Single', '2026-05-15', '2027-10-20'),
(48, 'Bekan', 'Boka', 'Dandana', 20, 'Male', 'Ambo, Oromia, Ethiopia', '+251925643658', NULL, 'No', 'Cheating', 'Low', 'Single', '2026-05-13', '2026-11-26');

-- --------------------------------------------------------

--
-- Table structure for table `prisoner_images`
--

CREATE TABLE `prisoner_images` (
  `id` int(11) NOT NULL,
  `prisoner_id` int(11) NOT NULL,
  `path` varchar(500) NOT NULL,
  `uploaded_by` varchar(100) NOT NULL,
  `upload_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prisoner_images`
--

INSERT INTO `prisoner_images` (`id`, `prisoner_id`, `path`, `uploaded_by`, `upload_date`) VALUES
(1, 41, 'prisoner_images/prisoner_41_2026_05_13_1778694259.jpg', 'Elias', '2026-05-13 20:44:19'),
(2, 50, 'prisoner_images/prisoner_50_2026_05_14_1778765159.jpg', 'lencho', '2026-05-14 16:25:59'),
(3, 53, 'prisoner_images/prisoner_53_2026_05_14_1778781406.jpg', 'rabira', '2026-05-14 20:56:46'),
(4, 60, 'prisoner_images/prisoner_60_2026_05_15_1778832999.jpg', 'bona', '2026-05-15 11:16:39');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `request_id` int(11) NOT NULL,
  `ID` int(15) NOT NULL,
  `place` text NOT NULL,
  `reason` text NOT NULL,
  `status` varchar(30) NOT NULL,
  `date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`request_id`, `ID`, `place`, `reason`, `status`, `date`) VALUES
(42, 49, 'bole', 'health problem', 'Not Approved', '2026-05-14'),
(40, 47, 'bole', 'heath problem', 'Approved', '2026-05-13'),
(41, 47, 'Adama', 'heath problem', 'Approved', '2026-05-13');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `ID` int(15) NOT NULL,
  `date` int(11) NOT NULL,
  `days` varchar(150) NOT NULL,
  `morning` varchar(50) NOT NULL,
  `afternoon` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`ID`, `date`, `days`, `morning`, `afternoon`) VALUES
(1, 2016, 'Monday', '1:30 -6:30', '8:00 -11:30'),
(8, 2026, 'Thursday', '02:30 - 06:30', '08:00 - 11:00'),
(9, 2026, 'Friday', '02:00 - 04:30', '08:00 - 10:00'),
(10, 2026, 'Saturday', '02:00 - 40:00', '08:30 - 10:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `userName` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `userType` enum('Admin','Prisoner','Inspector','Police Officer','Police Commissioner') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `prisoner_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `userName`, `password`, `userType`, `created_at`, `prisoner_id`) VALUES
(1, 'admin', '$2y$10$yNe29cz69dBftwmDWIi.8O7zrPXaz3tQT168rCw7KKeXn9E9b7chO', 'Admin', '2026-05-13 21:29:55', NULL),
(2, 'elias', '$2y$10$9CWQYGUYyMxL/VnpMFeNweWc1f94PcCjUFui01EnK94JG8ebBeRJC', 'Admin', '2026-05-13 21:32:41', NULL),
(3, 'beku', '$2y$10$uWTI5AhvwFrfqpt9BGGIq.8tHylWvBAqt6Xtxzrd8zPuAqWbNauyu', 'Police Officer', '2026-05-13 21:33:08', NULL),
(4, 'adugna', '$2y$10$.86T/JieSiWnrmJmFm55b.Vwoyg95ymR..2Nd10ZZp3KxbLunZjI6', 'Police Commissioner', '2026-05-14 08:38:29', NULL),
(5, 'lencho', '$2y$10$CoqBq51GViAQR9U1XcY.R.pWroaSCeYwsvQu5FnyZedrfSqZrfTLa', 'Police Officer', '2026-05-14 08:54:12', NULL),
(7, 'abduu', '$2y$10$qI7MIJnqjVhuoYxOUyqEsO5HeA1ag4hgzxkT7jrn3bKsAsr5B0vmW', 'Inspector', '2026-05-14 09:16:06', NULL),
(9, 'lama', '$2y$10$noHRwZ7BG.m0jcivDaJrmugaE/0Kv/yIbVpYkc8/gS2zebyTlnS5W', 'Prisoner', '2026-05-14 09:38:41', NULL),
(10, 'rabira', '$2y$10$8BjPxxd1v20T0OhJcOg5se35ntFpr8A2fxH8sew4DGVDnSlz6pA6G', 'Police Officer', '2026-05-14 14:34:02', NULL),
(11, 'BonaKena', 'BonaKena', 'Prisoner', '2026-05-14 17:10:00', 53),
(12, 'eliyas.hunde', '$2y$10$gb/DQSBBNPDcJTT0o6G7euPEB3PutMkLUZ1//9Yklgtbk3pFyVjSi', 'Prisoner', '2026-05-14 17:23:52', 54),
(14, 'geleta.merga', '$2y$10$0ctm8Yrk4VWTa4n4aHt4G.8zHGI.JZsUhrai9rccH0wvETiawFn4i', 'Prisoner', '2026-05-14 17:32:16', 56),
(16, 'tola.beka', '$2y$10$s2avro5v1MLN8MHZJ/EEheyT18MXkz5PO.YJTnMo2pYodFKA634a6', 'Prisoner', '2026-05-14 17:35:03', 58),
(17, 'bona.beka', '$2y$10$vwkvVbHt5ZzdO9r5BNfKi.4mli4AT1bBsdVq8SIboZGhIvBK8NWN.', 'Prisoner', '2026-05-14 17:52:56', 59),
(18, 'bona', '$2y$10$UQf7IqWCel4b6Mjy6FOY4.0FdEuaBjlp1TrnyyWZFJz3R82Yk9YJi', 'Police Officer', '2026-05-15 08:05:10', NULL),
(19, 'kota.bona', '$2y$10$xmgp9QYUQEyacJ7gRx3g1e9CZIEKw8F0TDhyitJg/h1qX8fnbIy4q', 'Prisoner', '2026-05-15 08:13:47', 60);

-- --------------------------------------------------------

--
-- Table structure for table `visiting_time`
--

CREATE TABLE `visiting_time` (
  `ID` varchar(15) NOT NULL,
  `date` date NOT NULL,
  `days` varchar(150) NOT NULL,
  `morning` varchar(50) NOT NULL,
  `after` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `visiting_time`
--

INSERT INTO `visiting_time` (`ID`, `date`, `days`, `morning`, `after`) VALUES
('', '2026-04-14', 'Monday-Friday', '3:00-6:00', '8:00-10:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `archive`
--
ALTER TABLE `archive`
  ADD PRIMARY KEY (`prison_ID`);

--
-- Indexes for table `helpdesk`
--
ALTER TABLE `helpdesk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`prison_ID`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `prisoner`
--
ALTER TABLE `prisoner`
  ADD PRIMARY KEY (`prison_ID`),
  ADD KEY `idx_criminal_severity` (`criminal_severity`);

--
-- Indexes for table `prisoner_images`
--
ALTER TABLE `prisoner_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_prisoner_id` (`prisoner_id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userName` (`userName`);

--
-- Indexes for table `visiting_time`
--
ALTER TABLE `visiting_time`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `helpdesk`
--
ALTER TABLE `helpdesk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `job`
--
ALTER TABLE `job`
  MODIFY `prison_ID` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `ID` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `prisoner`
--
ALTER TABLE `prisoner`
  MODIFY `prison_ID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `prisoner_images`
--
ALTER TABLE `prisoner_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `ID` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
