-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 13, 2025 at 07:00 AM
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
-- Database: `new_alamanac`
--

-- --------------------------------------------------------

--
-- Table structure for table `dates`
--

CREATE TABLE `dates` (
  `id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dates`
--

INSERT INTO `dates` (`id`, `semester_id`, `start_date`, `end_date`, `year`) VALUES
(27, 1, '2025-01-08', '2025-04-24', 2025),
(28, 2, '2025-05-08', '2025-08-24', 2025),
(29, 3, '2025-09-09', '2025-12-11', 2025),
(30, 1, '2026-01-10', '2026-04-25', 2026),
(31, 2, '2026-05-10', '2026-08-25', 2026),
(32, 3, '2026-09-10', '2026-12-12', 2026);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `date_id` int(11) NOT NULL,
  `week_no` int(11) NOT NULL,
  `event_date` date NOT NULL,
  `semester_event` varchar(255) NOT NULL,
  `committee` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `date_id`, `week_no`, `event_date`, `semester_event`, `committee`) VALUES
(42, 27, 1, '2025-01-08', '<p>-Opening of the University 6th January 2025</p>\r\n<p>-Submission of Marked Scripts to Exams Office&ndash; 6/1/2025</p>\r\n<p>-Departmental Examination Board&ndash; 7/1/25</p>\r\n<p>-Examination Board -8/1/25</p>\r\n<p>-New Students&rsquo; Orientation &ndash; ', '<p>-Student Academic Irregularity Disciplinary Committee 9th January 2025</p>\r\n<p>-FPAC 8th January 2025</p>'),
(43, 27, 2, '2025-01-13', '<p>-University Examiners Board - 15/01/2025</p>\r\n<p>-Class Commencement New Students 13th January 2025</p>', '<p>-Deans Committee 13th January 2025</p>\r\n<p>-University Management Board &ndash; 14th January 2025</p>'),
(44, 27, 3, '2025-01-20', '<p>-External Moderation of Marked Scripts 20/1/25 to 24/1/25</p>\r\n<p>-Release of Specials-Supplementary Examination Timetable &ndash; 24th January 2025</p>\r\n<p>-Classes</p>', '<p>-School Board - 20th January 2025</p>'),
(45, 27, 4, '2025-01-27', '<p>-Assignment I</p>\r\n<p>-Special Examinations &ndash;3rd to 7th Feb 2025</p>', '<p>-Matriculation ceremony 6th</p>\r\n<p>-February 2025FPAC &ndash; 5th February 2025</p>\r\n<p>-School Board &ndash; 3rd February 2025</p>'),
(46, 27, 5, '2025-02-03', '<p>-School Moderation of Draft Exams</p>\r\n<p>-Departmental Examination Board&ndash; 12/02/25</p>\r\n<p>-School Examination Board -13/02/25</p>\r\n<p>-University Examiners Board &ndash; 14/02/2025</p>\r\n<p>-Internal Moderation of Drafts</p>', '<p>-Deans Committee 10th February 2025Student Academic Irregularity Disciplinary&nbsp;</p>\r\n<p>-Committee 12th February 2025University Management Board 11th February 2025</p>\r\n<p>-Board of Postgraduate Studies Meeting 13th February 2025</p>'),
(47, 28, 1, '2025-05-08', '<p>-Submission of Marked Scripts to Exams Office&ndash; 8/05/2025</p>\r\n<p>-Departmental Examination Board&ndash; 8/05/25</p>\r\n<p>-School Examination Board -9/05/25</p>\r\n<p>-New Students&rsquo; Orientation &ndash; 9/05/25</p>\r\n<p>-Commencement of classes &', '<p>-Deans Committee 5th May 2025</p>\r\n<p>-FPAC &ndash; 7th May 2025</p>'),
(48, 28, 2, '2025-05-12', '<p>-University Examination Board&ndash; 14/05/25</p>\r\n<p>-Classes</p>', '<p>-University Management Board 13th May 2025</p>\r\n<p>-School Board &ndash; 12th May 2025</p>'),
(49, 28, 3, '2025-05-19', '<p>-Classes&nbsp;</p>\r\n<p>-Release of Special and Supplementary Examinations Timetable - 23/05/25</p>', '<p>-Deans Committee 19th May 2025</p>'),
(50, 28, 4, '2025-05-26', '<p>-Setting of Draft Examinations (Ordinary and Special/Supplementary)&nbsp;</p>\r\n<p>-External Moderation of Marked Scripts&ndash; 26/5/25 to 30/5/25</p>', '<p>-School Board &ndash; 26th May 2025</p>\r\n<p>-Library Committee 30th May 2025</p>\r\n<p>-Research and innovation Week 26th May &ndash; 30th May 2025</p>'),
(51, 28, 5, '2025-06-02', '<p>-Submission of Draft Exams to Exams Office</p>\r\n<p>-Classes</p>\r\n<p>-Special and Supplementary Examinations&nbsp;</p>\r\n<p>-Assignment I</p>', '<p>-Deans Committee 2nd Jun 2025</p>\r\n<p>-FPAC &ndash; 4th June 2025</p>\r\n<p>-Matriculation ceremony 5th June 2025</p>'),
(52, 29, 1, '2025-09-09', '<p>-Submission of Marked Scripts to Exams Office&ndash; 3/09/2025</p>\r\n<p>-Departmental Examination Board&ndash; 3/09/25</p>\r\n<p>-School Examination Board -4/09/25</p>\r\n<p>-New Students&rsquo; Orientation &ndash; 5/09/25</p>\r\n<p>-Commencement of Classes 1', '<p>-FPAC &ndash; 3rd September 2025</p>\r\n<p>-School Board &ndash; 1st September 2025</p>'),
(53, 29, 2, '2025-09-12', '<p>-University Examiners Board &ndash; 10/09/25</p>\r\n<p>-Classes</p>', '<p>-Deans Committee 8th September 2025</p>\r\n<p>-University Management Board 9th September 2025</p>'),
(54, 29, 3, '2025-09-15', '<p>-Classes</p>', '<p>-School Board &ndash; 15th September 2025</p>'),
(55, 29, 4, '2025-09-22', '<p>-Setting of Draft Examinations (Ordinary and Special/Supplementary)</p>\r\n<p>-External Moderation of Marked Scripts &ndash;22/9/25 to 26/9/25</p>', '<p>-Deans Committee 22nd September 2025</p>\r\n<p>-Library Committee 26th September 2025</p>'),
(56, 29, 5, '2025-09-29', '<p>-Submission of Draft Exams to Exams Office</p>\r\n<p>-Special and Supplementary Examinations&nbsp;</p>\r\n<p>-Assignment I</p>', '<p>-Matriculation ceremony 3rd October 2025</p>\r\n<p>-FPAC 1st October 2025</p>\r\n<p>-University Senate &ndash; 2nd October 2025</p>\r\n<p>-School Board &ndash; 29th September 2025</p>');

-- --------------------------------------------------------

--
-- Table structure for table `semester`
--

CREATE TABLE `semester` (
  `semester_id` int(11) NOT NULL,
  `semester_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `semester`
--

INSERT INTO `semester` (`semester_id`, `semester_name`) VALUES
(1, 'January-April'),
(2, 'May-August'),
(3, 'September-December');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `reset_token`, `reset_expiry`) VALUES
(1, 'admin', 'admin123@gmail.com', '$2y$10$Ki6dgPpdHHI/aH4ibF/Wse5Aw4m31/t0Xi87EgjD9A/hCp4Fa3WhW', NULL, NULL),
(2, 'joseph', 'kirikajoseph16@gmail.com', '$2y$10$oSw5Eytc8.JGi.S9AqGG0unywtIvnpnsQZEUzDoTANNgYZrzO/eR.', 'f480894b52a7d7fd18e53adabbbbf187e6726fabe4282245171044eeaaf53f60', '2025-03-03 09:37:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dates`
--
ALTER TABLE `dates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_dates_semester` (`semester_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_ibfk_1` (`date_id`);

--
-- Indexes for table `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`semester_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dates`
--
ALTER TABLE `dates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `semester`
--
ALTER TABLE `semester`
  MODIFY `semester_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dates`
--
ALTER TABLE `dates`
  ADD CONSTRAINT `fk_dates_semester` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`semester_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
