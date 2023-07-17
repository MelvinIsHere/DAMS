-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 29, 2023 at 12:26 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dams2`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `new_course` (IN `in_code` VARCHAR(12), IN `in_desc` VARCHAR(50), IN `in_dept` VARCHAR(12), IN `in_units` INT(4), IN `in_lec` DOUBLE(4,2), IN `in_rle` DOUBLE(4,2), IN `in_lab` DOUBLE(4,2))   BEGIN
		INSERT INTO `courses` (
			`course_code`,
			`course_description`, 
			`department_id`,
			`units`,
			`lec_hrs_wk`,
			`rle_hrs_wk`,
			`lab_hrs_wk`
			)
		values (in_code,
			in_desc,
			(select department_id
			from departments
			where department_abbrv = in_dept),
			in_units,
			in_lec,
			in_rle,
			in_lab);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `new_fac_titles` (IN `in_fac` INT(8), IN `in_title` INT(8))   BEGIN
		INSERT INTO faculty_titles(
			faculty_id,
			title_id
		)
		VALUES(
			in_fac,
			in_title
		);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `show_fac_titles` (IN `in_fac_id` INT(8))   BEGIN
		SELECT
		CASE tt.`title_description`
			WHEN 'Dean' THEN CONCAT(tt.`title_description`,' ',dp.department_abbrv)
			ELSE tt.`title_description`
		END "Titles"
		FROM faculty_titles ft
		LEFT JOIN titles tt ON ft.`title_id`=tt.`title_id`
		LEFT JOIN faculties fc ON ft.`faculty_id`=fc.`faculty_id`
		LEFT JOIN departments dp ON fc.`department_id`=dp.department_id
		WHERE fc.`faculty_id` = in_fac_id; # 1 is ID of faculty
	END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `academic_year`
--

CREATE TABLE `academic_year` (
  `acad_year_id` int(8) NOT NULL,
  `start_year` int(4) DEFAULT NULL,
  `end_year` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academic_year`
--

INSERT INTO `academic_year` (`acad_year_id`, `start_year`, `end_year`) VALUES
(1, 2022, 2023);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(8) NOT NULL,
  `course_code` varchar(12) NOT NULL,
  `course_description` varchar(50) NOT NULL,
  `department_id` int(8) NOT NULL,
  `units` int(4) NOT NULL,
  `lec_hrs_wk` double(4,2) NOT NULL,
  `rle_hrs_wk` double(4,2) NOT NULL,
  `lab_hrs_wk` double(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_code`, `course_description`, `department_id`, `units`, `lec_hrs_wk`, `rle_hrs_wk`, `lab_hrs_wk`) VALUES
(1, 'CSE 401', 'CS Professional Elective 1', 1, 3, 2.00, 0.00, 2.00),
(2, 'CS 422', 'Machine Learning', 1, 3, 2.00, 0.00, 2.25),
(3, 'CS 421', 'CS Thesis 2', 1, 3, 3.00, 0.00, 0.00),
(4, 'CS 111', 'Computer Programming', 1, 3, 2.00, 0.00, 3.00),
(5, 'CS 111', 'Computer Programming', 1, 3, 2.00, 0.00, 3.00);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(8) NOT NULL,
  `department_name` varchar(50) NOT NULL,
  `department_abbrv` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`, `department_abbrv`) VALUES
(1, 'College of Informatics and Computing Sciences', 'CICS'),
(2, 'College of Arts and Sciences', 'CAS'),
(3, 'College of Teacher Education', 'CTE');

-- --------------------------------------------------------

--
-- Table structure for table `faculties`
--

CREATE TABLE `faculties` (
  `faculty_id` int(8) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `middlename` varchar(50) NOT NULL,
  `department_id` int(8) NOT NULL,
  `is_permanent` int(4) NOT NULL,
  `is_guest` int(4) NOT NULL,
  `is_partTime` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculties`
--

INSERT INTO `faculties` (`faculty_id`, `firstname`, `lastname`, `middlename`, `department_id`, `is_permanent`, `is_guest`, `is_partTime`) VALUES
(1, 'Lorissa Joana', 'Buenas', 'E.', 1, 1, 0, 0),
(2, 'Lorenjane', 'Balan', 'E.', 1, 1, 0, 0),
(3, 'Lorenjane', 'Balan', 'E.', 1, 1, 0, 0),
(4, 'Noelyn', 'De Jesus', 'M.', 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `faculty_loadings`
--

CREATE TABLE `faculty_loadings` (
  `fac_load_id` int(8) NOT NULL,
  `faculty_id` int(8) NOT NULL,
  `course_id` int(8) NOT NULL,
  `section_id` int(8) NOT NULL,
  `acad_year_id` int(8) NOT NULL,
  `sem_id` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_loadings`
--

INSERT INTO `faculty_loadings` (`fac_load_id`, `faculty_id`, `course_id`, `section_id`, `acad_year_id`, `sem_id`) VALUES
(1, 1, 1, 1, 1, 2),
(2, 1, 2, 2, 1, 2),
(3, 1, 3, 2, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `faculty_titles`
--

CREATE TABLE `faculty_titles` (
  `fac_title_id` int(8) NOT NULL,
  `faculty_id` int(8) NOT NULL,
  `title_id` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_titles`
--

INSERT INTO `faculty_titles` (`fac_title_id`, `faculty_id`, `title_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 2, 4);

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `program_id` int(8) NOT NULL,
  `program_title` varchar(50) NOT NULL,
  `department_id` int(8) NOT NULL,
  `program_abbrv` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`program_id`, `program_title`, `department_id`, `program_abbrv`) VALUES
(1, 'Computer Science', 1, 'CS');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `section_id` int(8) NOT NULL,
  `course_id` int(8) NOT NULL,
  `program_id` int(8) NOT NULL,
  `section_name` varchar(8) NOT NULL,
  `semester_id` int(8) NOT NULL,
  `no_of_students` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`section_id`, `course_id`, `program_id`, `section_name`, `semester_id`, `no_of_students`) VALUES
(1, 1, 1, '3201', 2, 8),
(2, 1, 1, '4201', 2, 8);

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `semester_id` int(8) NOT NULL,
  `sem_description` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `semesters`
--

INSERT INTO `semesters` (`semester_id`, `sem_description`) VALUES
(1, 'First'),
(2, 'Second');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int(8) NOT NULL,
  `task_name` varchar(50) NOT NULL,
  `task_desc` varchar(50) NOT NULL,
  `document_id` int(8) NOT NULL,
  `date_posted` date NOT NULL,
  `due_date` date NOT NULL,
  `for_ovcaa` tinyint(1) NOT NULL,
  `for_deans` tinyint(1) NOT NULL,
  `for_heads` tinyint(1) NOT NULL,
  `acad_year_id` int(8) NOT NULL,
  `sem_id` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`task_id`, `task_name`, `task_desc`, `document_id`, `date_posted`, `due_date`, `for_ovcaa`, `for_deans`, `for_heads`, `acad_year_id`, `sem_id`) VALUES
(1, 'Faculty Loading', 'Faculty Loading AY 22-23, Second Sem)', 1, '2023-06-01', '2023-06-30', 0, 1, 0, 1, 2),
(2, 'OPCR', 'aergfsads', 1, '2023-06-01', '2023-06-30', 1, 1, 1, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `task_status`
--

CREATE TABLE `task_status` (
  `status_id` int(8) NOT NULL,
  `task_id` int(8) NOT NULL,
  `office_id` int(8) NOT NULL,
  `is_completed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_status`
--

INSERT INTO `task_status` (`status_id`, `task_id`, `office_id`, `is_completed`) VALUES
(1, 1, 1, 0),
(2, 1, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `titles`
--

CREATE TABLE `titles` (
  `title_id` int(8) NOT NULL,
  `title_description` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `titles`
--

INSERT INTO `titles` (`title_id`, `title_description`) VALUES
(1, 'Professor 1'),
(2, 'Vice Chancellor for Academic Affairs'),
(3, 'Dean'),
(4, 'Faculty Researcher');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_year`
--
ALTER TABLE `academic_year`
  ADD PRIMARY KEY (`acad_year_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `faculties`
--
ALTER TABLE `faculties`
  ADD PRIMARY KEY (`faculty_id`);

--
-- Indexes for table `faculty_loadings`
--
ALTER TABLE `faculty_loadings`
  ADD PRIMARY KEY (`fac_load_id`);

--
-- Indexes for table `faculty_titles`
--
ALTER TABLE `faculty_titles`
  ADD PRIMARY KEY (`fac_title_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`program_id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`section_id`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`semester_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`);

--
-- Indexes for table `task_status`
--
ALTER TABLE `task_status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `titles`
--
ALTER TABLE `titles`
  ADD PRIMARY KEY (`title_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_year`
--
ALTER TABLE `academic_year`
  MODIFY `acad_year_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `faculties`
--
ALTER TABLE `faculties`
  MODIFY `faculty_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `faculty_loadings`
--
ALTER TABLE `faculty_loadings`
  MODIFY `fac_load_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `faculty_titles`
--
ALTER TABLE `faculty_titles`
  MODIFY `fac_title_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `program_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `section_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `semester_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `task_status`
--
ALTER TABLE `task_status`
  MODIFY `status_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `titles`
--
ALTER TABLE `titles`
  MODIFY `title_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
