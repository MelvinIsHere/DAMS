-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 20, 2023 at 06:27 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dams`
--

-- --------------------------------------------------------

--
-- Table structure for table `pendingtask`
--

CREATE TABLE `pendingtask` (
  `pendingTaskID` bigint(255) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `task_owner` varchar(255) NOT NULL,
  `task_posted` date NOT NULL,
  `task_duedate` date NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pendingtask`
--

INSERT INTO `pendingtask` (`pendingTaskID`, `task_name`, `task_owner`, `task_posted`, `task_duedate`, `description`) VALUES
(1, 'Room Utilization Matrix', 'Office of Vice Chancellor of Academic Affairs', '2023-06-20', '2023-06-29', 'This is just a sample text'),
(2, 'OPCR', 'Office of Vice Chancellor of Academic Affairs', '2023-06-06', '2023-06-21', 'Something'),
(3, 'IPCR', 'CICS DEAN', '2023-06-15', '2023-06-23', 'Something');

-- --------------------------------------------------------

--
-- Table structure for table `usermonitoring`
--

CREATE TABLE `usermonitoring` (
  `id` bigint(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `login` datetime NOT NULL,
  `logout` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `usermonitoring`
--

INSERT INTO `usermonitoring` (`id`, `firstName`, `lastName`, `position`, `department`, `login`, `logout`) VALUES
(1, 'melvin', 'felicisimo', 'Staff', 'CICS', '2023-06-20 12:07:20', '2023-06-20 23:07:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pendingtask`
--
ALTER TABLE `pendingtask`
  ADD PRIMARY KEY (`pendingTaskID`);

--
-- Indexes for table `usermonitoring`
--
ALTER TABLE `usermonitoring`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pendingtask`
--
ALTER TABLE `pendingtask`
  MODIFY `pendingTaskID` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `usermonitoring`
--
ALTER TABLE `usermonitoring`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
