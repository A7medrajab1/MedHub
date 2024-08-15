-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2024 at 11:43 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `medhub`
--

DROP Database IF EXISTS medhub;
CREATE Database medhub;
USE medhub;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `email_admin` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `phone` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `address` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `gender` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `year_of_birth` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `appointment_id` int(10) NOT NULL,
  `email_doctor` varchar(50) NOT NULL,
  `email_patient` varchar(50),
  `time` time NOT NULL,
  `date` date NOT NULL,
  `price` double(10,2) DEFAULT 0,
  `visa_info` text ,
  `is_paid` tinyint(1) DEFAULT 0,
  `is_done` tinyint(1) DEFAULT 0,
  `is_reserved` tinyint(1) DEFAULT 0

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `email_doctor` varchar(250) NOT NULL,
  `password` text NOT NULL,
  `name` text NOT NULL,
  `Speciality` text NOT NULL COMMENT 'refers to the specific area of medicine in which they have received advanced training and expertise',
  `phone` text NOT NULL,
  `address` text NOT NULL,
  `gender` text NOT NULL,
  `salary` double(10,2) NOT NULL,
  `year_of_birth` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `doctor`
--

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `message_id` int(100) NOT NULL AUTO_INCREMENT,
  `patient_email` varchar(250) NOT NULL,
  `staff_email` varchar(250) NOT NULL,
  `message_title` text NOT NULL,
  `message` text NOT NULL,
  `responded` boolean DEFAULT 0,
  PRIMARY KEY (message_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `email_patient` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `phone` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `address` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `gender` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `year_of_birth` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prescription`
--

CREATE TABLE `prescription` (
  `prescription_id` int(100) NOT NULL ,
  `appointment_id` int(100) NOT NULL UNIQUE,
  `treatment` text NOT NULL,
  `disease_info` text DEFAULT NULL,
  `disease_title` text DEFAULT NULL,
  `age` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `email_staff` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `phone` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `address` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `gender` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `year_of_birth` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`email_admin`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `create_email_doctor_fk` (`email_doctor`),
  ADD KEY `create_email_patient_fk` (`email_patient`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`email_doctor`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD KEY `create_staff_email_fk` (`staff_email`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`email_patient`);

--
-- Indexes for table `prescription`
--
ALTER TABLE `prescription`
  ADD PRIMARY KEY (`prescription_id`),
  ADD KEY `create_appointment_id_fk` (`appointment_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`email_staff`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appointment_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prescription`
--
ALTER TABLE `prescription`
  MODIFY `prescription_id` int(100) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `create_email_doctor_fk` FOREIGN KEY (`email_doctor`) REFERENCES `doctor` (`email_doctor`),
  ADD CONSTRAINT `create_email_patient_fk` FOREIGN KEY (`email_patient`) REFERENCES `patient` (`email_patient`);

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `create_staff_email_fk` FOREIGN KEY (`staff_email`) REFERENCES `staff` (`email_staff`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prescription`
--
ALTER TABLE `prescription`
  ADD CONSTRAINT `create_appointment_id_fk` FOREIGN KEY (`appointment_id`) REFERENCES `appointment` (`appointment_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
