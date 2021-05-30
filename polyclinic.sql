-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2021 at 10:06 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `polyclinic`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `status` enum('Upcoming','Ongoing','Finished','Cancelled') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `schedule_id`, `patient_id`, `status`) VALUES
(43, 4, 3, 'Finished');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`) VALUES
(1, 'General'),
(2, 'Eye'),
(3, 'Dentist');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `full_name` varchar(30) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` char(60) NOT NULL,
  `session_id` char(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `department_id`, `email`, `full_name`, `username`, `password`, `session_id`) VALUES
(1, 1, 'michael.gunawan2002@gmail.com', 'Rico Rudikan', 'ricorudikan', '$2y$10$c0c/rjlzV9c0prXjXfwJwOQVxbqPsqYTXORbFTKCD3JaMsjIGz5.u', NULL),
(2, 1, '', 'Michael Eco', 'eco123', '$2y$10$n1bFcuyTqeQbcUVwnbAGJuW6oKAsFhk0ejVAZwK2mf0LaQabzNq5G', NULL),
(3, 1, 'reynaldi_kindarto@yahoo.com', 'Reynaldi Kindarto', 'rey', '$2y$10$D4VFpwvTJPQf2spBbyre0ODE9q/uErh8jPb58d1BntVB0FCP7hyNC', NULL),
(4, 1, '', 'Rico', 'rico', '$2y$10$MYayAl8LeyBxm.YgFQ9bfut8E9VDaLPFhL63LuJOlk05ssHKYpHfm', NULL),
(5, 2, '', 'Rudy', 'Kayne', '$2y$10$TUmZb3mfYmqUuHhM2jTwk.rTAzYwog2qA5uchQK1uhk9qWCHLM/xW', NULL),
(6, 3, '', 'Wilbert Anthony', 'wilbert', '$2y$10$7XxHaBsGjGD9lvzFVu8J/uk2RuzLZcWnj0SeHJPsGuJlXQhuR/UEy', NULL),
(7, 3, '', 'Rudy Kayne', 'bomek', '$2y$10$5xFeOug4jezlVz/ZbGSdSuHeSaVkuG/gUpQZ4iKvLDzkW8buax4WK', NULL),
(8, 1, '', 'Maklo Geming', 'maklo', '$2y$10$QTlYw5PLOYnK1EP1ryfEIOD6EbbfBqucVxm7rNozFV.DS.DrXRG2G', NULL),
(9, 1, '', 'Bapaklo Geming', 'bapaklo', '$2y$10$ln6xwd1MClJh8GH1Jd4.oejw04zr67ZA4n/rwTp7qa9ONQY7elhGm', NULL),
(10, 1, '', 'Reynaldi Kindarto', 'kepet', '$2y$10$DtjxNkMPeSjGUPjP5OJ11OGZ368rx8JJREeRIrRQQcFmj3V0QQeQy', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `doctors_token`
--

CREATE TABLE `doctors_token` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `token` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `id_number` char(16) NOT NULL,
  `email` varchar(40) NOT NULL,
  `full_name` varchar(40) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact` varchar(13) NOT NULL,
  `pin` char(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `id_number`, `email`, `full_name`, `address`, `contact`, `pin`) VALUES
(3, '123', 'michael.gunawan2002@gmail.com', 'Wilbert Anthony', 'Perum Citra Fajar Golf AT D7191', '085156578351', '$2y$10$nVV9hguRAxiWch69JAaWg.WHqKA7TqZeOay89KEu33VhjP4yJZx9i'),
(11, '1234567', '', 'b', 'b', '12345', '$2y$10$5Kk0NtQ6aLIOEH1i0nmZG.FODA3JnMEQDR2De2n.EOHrXst/1nTky'),
(12, '12345', 'rkindarto@student.ciputra.ac.id', 'Reynaldi Kindarto', 'Darmo Baru Barat XI/5', '123', '$2y$10$BA/KdBeqM.QRvYfJir6Ba.5sVLal6QmKtc5ZngIWg2glEvbXlmuf2'),
(16, '11111', 'joshua@gmail.com', 'Joshua', 'ajsdkuasd', '12345', '$2y$10$XDgij9Vi6Gj4MOApYgQbH.9nUQO8SuikxSEGXkApoSu0dmRB6rYaC');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `schedule_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `day` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `time` time NOT NULL,
  `availability` enum('Available','Unavailable') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`schedule_id`, `department_id`, `doctor_id`, `day`, `time`, `availability`) VALUES
(4, 1, 1, 'Monday', '10:00:00', 'Unavailable'),
(5, 2, 5, 'Monday', '12:00:00', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `screening`
--

CREATE TABLE `screening` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `result` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`result`)),
  `time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `screening`
--

INSERT INTO `screening` (`id`, `patient_id`, `result`, `time`) VALUES
(1, 3, '{\"question1\":\"true\",\"question2\":\"true\"}', '2021-05-27 08:24:04'),
(5, 12, '{\"question1\":\"false\",\"question2\":\"false\"}', '2021-05-28 04:48:57'),
(7, 16, '{\"question1\":\"false\",\"question2\":\"false\"}', '2021-05-28 05:01:23'),
(8, 3, '{\"question1\":\"false\",\"question2\":\"true\"}', '2021-05-28 08:17:28'),
(9, 3, '{\"question1\":\"true\",\"question2\":\"false\"}', '2021-05-28 08:54:37'),
(10, 3, '{\"question1\":\"false\",\"question2\":\"true\"}', '2021-05-30 05:23:58'),
(11, 3, '{\"question1\":\"false\",\"question2\":\"false\"}', '2021-05-30 07:22:13'),
(12, 3, '{\"question1\":\"true\",\"question2\":\"true\"}', '2021-05-30 07:24:57'),
(13, 3, '{\"question1\":\"false\",\"question2\":\"false\"}', '2021-05-30 07:27:58'),
(14, 3, '{\"question1\":\"false\",\"question2\":\"false\"}', '2021-05-30 07:47:44'),
(15, 3, '{\"question1\":\"false\",\"question2\":\"true\"}', '2021-05-30 07:51:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedule_id_key` (`schedule_id`),
  ADD KEY `patient_appointment_key` (`patient_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id_key` (`department_id`);

--
-- Indexes for table `doctors_token`
--
ALTER TABLE `doctors_token`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_token_key` (`doctor_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `doctor_schedule_key` (`doctor_id`),
  ADD KEY `department_schedule_key` (`department_id`);

--
-- Indexes for table `screening`
--
ALTER TABLE `screening`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_diagnosis_key` (`patient_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `doctors_token`
--
ALTER TABLE `doctors_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `screening`
--
ALTER TABLE `screening`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `patient_id_key` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedule_id_key` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`schedule_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `department_id_key` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `doctors_token`
--
ALTER TABLE `doctors_token`
  ADD CONSTRAINT `doctor_token_key` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`);

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `department_schedule_key` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `doctor_id_key` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `screening`
--
ALTER TABLE `screening`
  ADD CONSTRAINT `patient_screening_key` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
