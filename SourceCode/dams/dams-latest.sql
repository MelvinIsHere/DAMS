-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 22, 2023 at 12:11 PM
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
-- Table structure for table `createtask`
--

CREATE TABLE `createtask` (
  `id` bigint(255) NOT NULL,
  `taskName` varchar(255) NOT NULL,
  `task_owner` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `dateStart` date NOT NULL,
  `dateEnd` date NOT NULL,
  `deans` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `staff` varchar(255) NOT NULL,
  `custom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `createtask`
--

INSERT INTO `createtask` (`id`, `taskName`, `task_owner`, `description`, `dateStart`, `dateEnd`, `deans`, `department`, `staff`, `custom`) VALUES
(1, 'OPCR', 'Office of Vice Chancellor of Academic Affairs', 'sample', '2023-06-21', '2023-06-24', '', 'Tasked', '', ''),
(2, 'OPCR', 'Office of Vice Chancellor of Academic Affairs', 'sample', '2023-06-21', '2023-06-24', '', 'Tasked', '', ''),
(3, 'OPCR', 'Office of Vice Chancellor of Academic Affairs', 'sample', '2023-06-21', '2023-06-23', 'Tasked', 'Tasked', '', ''),
(4, 'OPCR', 'Office of Vice Chancellor of Academic Affairs', 'something', '2023-07-08', '2023-06-21', 'Tasked', '', '', ''),
(5, 'OPCR', 'Office of Vice Chancellor of Academic Affairs', 'sampless', '2023-06-26', '2023-07-01', 'Tasked', 'Tasked', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `data_start`
--

CREATE TABLE `data_start` (
  `id` bigint(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `course_code` varchar(255) NOT NULL,
  `section` varchar(255) NOT NULL,
  `no_of_students` int(255) NOT NULL,
  `total_units` int(255) NOT NULL,
  `lec_hrs_wk` int(255) NOT NULL,
  `rle_hrs_wk` int(255) NOT NULL,
  `lab_hrs_wk` int(255) NOT NULL,
  `total_hrs_wk` int(255) NOT NULL,
  `course_title` varchar(255) NOT NULL,
  `regular_hrs` int(255) NOT NULL,
  `overload` int(255) NOT NULL,
  `no_of_prep` int(255) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `type` varchar(255) NOT NULL,
  `college` varchar(255) NOT NULL,
  `campus` varchar(255) NOT NULL DEFAULT 'ARASOG',
  `semester` int(255) NOT NULL,
  `academic_year_start` year(4) NOT NULL,
  `academic_year_end` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `data_start`
--

INSERT INTO `data_start` (`id`, `full_name`, `course_code`, `section`, `no_of_students`, `total_units`, `lec_hrs_wk`, `rle_hrs_wk`, `lab_hrs_wk`, `total_hrs_wk`, `course_title`, `regular_hrs`, `overload`, `no_of_prep`, `date`, `type`, `college`, `campus`, `semester`, `academic_year_start`, `academic_year_end`) VALUES
(1, 'bryan russel rosel', 'IT 311', 'BSIT 3301 BA', 38, 15, 3, 3, 3, 15, 'Fundamentals of Business Analytics', 3, 3, 1, '2023-06-22', 'permanent', 'CICS', 'ARASOF', 1, 2023, 2024),
(2, 'kyle aster chadwick', 'IT 321', 'BSIT 1101', 40, 15, 3, 3, 3, 15, 'Computer Programming 1', 40, 1, 1, '2023-06-22', 'temporary', 'CICS', 'ARASOF', 1, 2023, 2024),
(3, 'Melvin Felicisimo', 'IT 211', 'BSIT 2201', 30, 3, 3, 3, 33, 3, 'Data Structure and Algorithms', 3, 1, 1, '2023-06-22', 'permanent', 'CICS', 'ARASOF', 1, 2023, 2024),
(4, 'Melvin Felicisimo', 'IT 211', 'BSIT 2201', 30, 3, 3, 3, 33, 3, 'Data Structure and Algorithms', 3, 1, 1, '2023-06-22', 'permanent', 'CICS', 'ARASOF', 1, 2023, 2024),
(5, 'Melvin Felicisimo', 'IT 211', 'BSIT 2201', 30, 3, 3, 3, 33, 3, 'Data Structure and Algorithms', 3, 1, 1, '2023-06-22', 'permanent', 'CICS', 'ARASOF', 1, 2023, 2024),
(6, 'Melvin Felicisimo', 'IT 211', 'BSIT 2201', 30, 3, 3, 3, 33, 3, 'Data Structure and Algorithms', 3, 1, 1, '2023-06-22', 'permanent', 'CICS', 'ARASOF', 1, 2023, 2024),
(7, 'Melvin Felicisimo', 'IT 211', 'BSIT 2201', 30, 3, 3, 3, 33, 3, 'Data Structure and Algorithms', 3, 1, 1, '2023-06-22', 'permanent', 'CICS', 'ARASOF', 1, 2023, 2024),
(8, 'Melvin Felicisimo', 'IT 211', 'BSIT 2201', 30, 3, 3, 3, 33, 3, 'Data Structure and Algorithms', 3, 1, 1, '2023-06-22', 'permanent', 'CICS', 'ARASOF', 1, 2023, 2024),
(9, 'Melvin Felicisimo', 'IT 211', 'BSIT 2201', 30, 3, 3, 3, 33, 3, 'Data Structure and Algorithms', 3, 1, 1, '2023-06-22', 'permanent', 'CICS', 'ARASOF', 1, 2023, 2024),
(10, 'Kim Kent Mik', 'IT 111', 'BSIT 3201', 40, 3, 3, 3, 3, 15, 'Computer Networking 1', 3, 11, 1, '2023-06-22', 'part time', 'CICS', 'ARASOF', 1, 2023, 2024);

-- --------------------------------------------------------

--
-- Table structure for table `loading`
--

CREATE TABLE `loading` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `courseCode` varchar(255) NOT NULL,
  `section` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `loading`
--

INSERT INTO `loading` (`id`, `name`, `courseCode`, `section`) VALUES
(1, 'melvin', 'ba 311', 'bsit 3201');

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

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `unique_id` int(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `unique_id`, `fname`, `lname`, `email`, `password`, `img`, `status`, `type`) VALUES
(1, 1118025438, 'melvin', 'mel', 'melvin@gmail.com', 'dabd018f98476c1f6eb2f23e8d9b8920', '1687338135nokia.jpg', 'Active now', 'admin'),
(2, 695277994, 'Bryan', 'Russel', 'bryan@gmail.com', '7d4ef62de50874a4db33e6da3ff79f75', '1687338207nokia.jpg', 'Active now', 'staffs'),
(3, 911431477, 'Kyle', 'Aster', 'kyle@gmail.com', '4b75751e170e00f56886726c3f46eecd', '1687338223nokia.jpg', 'Active now', 'deans');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `createtask`
--
ALTER TABLE `createtask`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_start`
--
ALTER TABLE `data_start`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loading`
--
ALTER TABLE `loading`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `createtask`
--
ALTER TABLE `createtask`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `data_start`
--
ALTER TABLE `data_start`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `loading`
--
ALTER TABLE `loading`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
