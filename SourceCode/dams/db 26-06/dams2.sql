-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 18, 2023 at 09:56 AM
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
-- Database: `dams2`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_academicYear_sp` (IN `in_id` INT(8))   BEGIN
		DELETE FROM `academic_year`
		WHERE acad_year_id = in_id;
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_course_sp` (IN `in_id` INT(8))   BEGIN
		delete from `courses`
		where course_id = in_id;
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_department_sp` (IN `in_id` INT(8))   BEGIN
		DELETE FROM `departments`
		WHERE department_id = in_id;
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_faculty_sp` (IN `in_id` INT(8))   BEGIN
		DELETE FROM `faculties`
		WHERE faculty_id = in_id;
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_program_sp` (IN `in_id` INT(8))   BEGIN
		DELETE FROM `programs`
		WHERE program_id = in_id;
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_section_sp` (IN `in_id` INT(8))   BEGIN
		DELETE FROM `sections`
		WHERE section_id = in_id;
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_semester_sp` (IN `in_id` INT(8))   BEGIN
		DELETE FROM `semesters`
		WHERE semester_id = in_id;
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `new_academicYear_sp` (IN `in_desc` VARCHAR(9))   BEGIN
		INSERT INTO `academic_year` (
			`acad_year`
			)
		VALUES (in_desc
		);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `new_course_sp` (IN `in_code` VARCHAR(12), IN `in_desc` VARCHAR(50), IN `in_dept` VARCHAR(12), IN `in_units` INT(4), IN `in_lec` DOUBLE(4,2), IN `in_rle` DOUBLE(4,2), IN `in_lab` DOUBLE(4,2))   BEGIN
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `new_department_sp` (IN `in_name` VARCHAR(50), IN `in_abbrv` VARCHAR(12))   BEGIN
		INSERT INTO `departments` (
			`department_name`,
			`department_abbrv`
			)
		VALUES (in_name,
			in_abbrv
		);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `new_faculty_sp` (IN `in_fname` VARCHAR(50), IN `in_lname` VARCHAR(50), IN `in_mname` VARCHAR(50), IN `in_suffix` VARCHAR(12), IN `in_dept` VARCHAR(12), IN `in_perm` BOOL, IN `in_guest` BOOL, IN `in_partTime` BOOL)   BEGIN
		insert into faculties(`firstname`,
			`lastname`,
			`middlename`,
			`suffix`,
			`department_id`,
			`is_permanent`,
			`is_guest`,
			`is_partTime`
		)
		values(in_fname,
			in_lname,
			in_mname,
			in_suffix,
			(SELECT department_id
			FROM departments
			WHERE department_abbrv = in_dept),
			in_perm,
			in_guest,
			in_partTime
		);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `new_program_sp` (IN `in_title` VARCHAR(50), IN `in_dept` VARCHAR(12), IN `in_abbrv` VARCHAR(12))   BEGIN
		INSERT INTO `programs` (
			`program_name`,
			`program_abbrv`
			)
		VALUES (in_title,
			(Select department_id
			from departments
			where department_abbrv = in_dept),
			in_abbrv
		);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `new_section_sp` (IN `in_course` VARCHAR(12), IN `in_program` VARCHAR(12), IN `in_name` VARCHAR(12), IN `in_sem` VARCHAR(12), IN `in_stdnts` INT(4))   BEGIN
		INSERT INTO `sections` (
			`course_id`,
			`program_id`,
			`section_name`,
			`semester_id`,
			`no_of_students`
			)
		VALUES ((SELECT course_id
			FROM courses
			WHERE course_abbrv = in_course),
			(SELECT program_id
			FROM programs
			WHERE program_abbrv = in_program),
			in_name,
			(SELECT semester_id
			FROM semesters
			WHERE sem_description = in_sem),
			in_stdnts
		);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `new_semester_sp` (IN `in_desc` VARCHAR(50))   BEGIN
		INSERT INTO `semesters` (
			`sem_description`
			)
		VALUES (in_desc
		);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `new_task_sp` (IN `in_name` VARCHAR(50), IN `in_desc` VARCHAR(50), IN `in_docName` VARCHAR(50), IN `in_post` DATE, IN `in_due` DATE, IN `in_ovcaa` BOOL, IN `in_deans` BOOL, IN `in_heads` BOOL, IN `in_acadyr` VARCHAR(9), IN `in_sem` VARCHAR(12))   BEGIN
		DECLARE LAST_INSERT_ID INT;

		START TRANSACTION;

	    -- Step 2: Insert into the first table
		INSERT INTO tasks(`task_name`,
			`task_desc`,
			`document_id`,
			`date_posted`,
			`due_date`,
			`for_ovcaa`,
			`for_deans`,
			`for_heads`,
			`acad_year_id`,
			`sem_id`
		)
		VALUES(in_name,
			in_desc,
			(SELECT doc_template_id
			FROM document_templates
			WHERE template_title=in_docName),
			in_post,
			in_due,
			in_ovcaa,
			in_deans,
			in_heads,
			(SELECT acad_year_id
			FROM academic_year
			WHERE acad_year = in_acadyr),
			(SELECT semester_id
			FROM semesters
			WHERE sem_description=in_sem)
		);
		
		-- Retrieve the last inserted ID
		SET LAST_INSERT_ID = LAST_INSERT_ID();
		
		IF in_ovcaa=1 THEN
			INSERT INTO `task_status_deans`(`task_id`,`office_id`,`is_completed`)
			VALUES (LAST_INSERT_ID,
				(SELECT department_id
				FROM departments
				WHERE department_abbrv="OVCAA"),
				0
			);
		ELSEIF in_heads=1 THEN
			INSERT INTO `task_status_deans`(`task_id`,`office_id`,`is_completed`)
				VALUES (LAST_INSERT_ID,
					(SELECT department_id
					FROM departments
					WHERE department_abbrv="CICS"),
					0
				);
			INSERT INTO `task_status_deans`(`task_id`,`office_id`,`is_completed`)
				VALUES (LAST_INSERT_ID,
					(SELECT department_id
					FROM departments
					WHERE department_abbrv="CAS"),
					0
				);
		end if;
		COMMIT;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_academicYear_sp` (IN `in_id` INT(8), IN `in_desc` VARCHAR(9))   BEGIN
		UPDATE `academic_year` 
		SET 
			`acad_year` = in_desc
		WHERE acad_year_id = in_id;
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_course_sp` (IN `in_id` INT(8), IN `in_code` VARCHAR(12), IN `in_desc` VARCHAR(50), IN `in_dept` VARCHAR(12), IN `in_units` INT(4), IN `in_lec` DOUBLE(4,2), IN `in_rle` DOUBLE(4,2), IN `in_lab` DOUBLE(4,2))   BEGIN
		UPDATE `courses` 
		SET `course_code` = in_code,
			`course_description` = in_desc, 
			`department_id` = (SELECT department_id
					FROM departments
					WHERE department_abbrv = in_dept),
			`units` = in_units,
			`lec_hrs_wk` = in_lec,
			`rle_hrs_wk` = in_rle,
			`lab_hrs_wk` = in_lab
		where course_id = in_id;
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_department_sp` (IN `in_id` INT(8), IN `in_name` VARCHAR(50), IN `in_abbrv` VARCHAR(12))   BEGIN
		UPDATE `departments` 
		SET 
			`department_name` = in_name,
			`department_abbrv` = in_abbrv
		WHERE department_id = in_id;
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_faculty_sp` (IN `in_fname` VARCHAR(50), IN `in_lname` VARCHAR(50), IN `in_mname` VARCHAR(50), IN `in_suffix` VARCHAR(12), IN `in_dept` VARCHAR(12), IN `in_perm` BOOL, IN `in_guest` BOOL, IN `in_partTime` BOOL)   BEGIN
		UPDATE `faculties` 
		SET `firstname`=in_fname,
			`lastname`=in_lname,
			`middlename`=in_mname,
			`suffix`=in_suffix,
			`department_id`=(SELECT department_id
					FROM departments
					WHERE department_abbrv = in_dept),
			`is_permanent`=in_perm,
			`is_guest`=in_guest,
			`is_partTime`=in_partTime
		WHERE faculty_id = in_id;
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_program_sp` (IN `in_id` INT(8), IN `in_title` VARCHAR(50), IN `in_dept` VARCHAR(12), IN `in_abbrv` VARCHAR(12))   BEGIN
		UPDATE `programs` 
		SET 
			`program_name` = in_title,
			`department_id` = (Select department_id
					from departments
					where department_abbrv = in_dept),
			`program_abbrv` = in_abbrv
		WHERE program_id = in_id;
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_section_sp` (IN `in_id` INT(8), IN `in_program` VARCHAR(12), IN `in_name` VARCHAR(12), IN `in_sem` VARCHAR(12), IN `in_stdnts` INT(4))   BEGIN
		UPDATE `sections` 
		SET `course_id` = (SELECT course_id
				FROM courses
				WHERE course_abbrv = in_course),
			`program_id` = (SELECT program_id
				FROM programs
				WHERE program_abbrv = in_program),
			`section_name` = in_name,
			`semester_id` = (SELECT semester_id
				FROM semesters
				WHERE sem_description = in_sem),
			`no_of_students` = in_stdnts
		WHERE section_id = in_id;
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_semester_sp` (IN `in_id` INT(8), IN `in_desc` VARCHAR(50))   BEGIN
		UPDATE `semesters` 
		SET 
			`sem_description` = in_desc
		WHERE semester_id = in_id;
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `view_facultyLoading_byDepartment_sp` (IN `in_dept_abbrv` VARCHAR(12))   BEGIN
		SELECT
		getFullName_surnameFirst(fc.firstname,fc.middlename,fc.lastname,fc.`suffix`) "Name of Faculty",
		cs.course_code "Course Code",
		getProg_sec(pr.`program_abbrv`,sc.`section_name`) "Section",
		sc.no_of_students "No. of Students",
		cs.`units` "Total Units",
		cs.`lec_hrs_wk` "Lec. hrs/wk",
		cs.`lab_hrs_wk` "Lab. hrs/wk",
		SUM(cs.`lec_hrs_wk`+cs.`lab_hrs_wk`) "Total hrs/wk",
		cs.`course_description` "Course Description"
		FROM
		faculty_loadings fl
		LEFT JOIN faculties fc ON fl.`faculty_id`=fc.`faculty_id`
		LEFT JOIN courses cs ON fl.`course_id`=cs.`course_id`
		LEFT JOIN sections sc ON fl.`section_id`=sc.`section_id`
		LEFT JOIN programs pr ON sc.`program_id`=pr.`program_id`
		LEFT JOIN departments dp ON dp.`department_id`=fl.`dept_id`
		WHERE fl.`dept_id` = (select department_id
		from departments
		where department_abbrv=in_dept_abbrv)
		GROUP BY fl.`fac_load_id`;
	END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `getFullName` (`firstname` VARCHAR(50), `middlename` VARCHAR(50), `lastname` VARCHAR(50), `suffix` VARCHAR(4)) RETURNS VARCHAR(255) CHARSET utf8mb4  BEGIN
    DECLARE full_name VARCHAR(255);
    SET full_name = firstname;
    
    IF middlename IS NOT NULL AND middlename != '' THEN
        SET full_name = CONCAT(full_name, ' ', middlename);
    END IF;
    
    set full_name = concat(full_name, ' ', lastname);
    
    IF suffix IS NOT NULL AND suffix != '' THEN
        SET full_name = CONCAT(full_name, ' ', suffix);
    END IF;

    RETURN full_name;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `getFullName_surnameFirst` (`firstname` VARCHAR(50), `middlename` VARCHAR(50), `lastname` VARCHAR(50), `suffix` VARCHAR(4)) RETURNS VARCHAR(255) CHARSET utf8mb4  BEGIN
    DECLARE full_name VARCHAR(255);
    SET full_name = CONCAT(lastname, ',', ' ', firstname);
    
    IF middlename IS NOT NULL AND middlename != '' THEN
        SET full_name = CONCAT(full_name, ' ', middlename);
    END IF;

    IF suffix IS NOT NULL AND suffix != '' THEN
        SET full_name = CONCAT(full_name, ' ', suffix);
    END IF;

    RETURN full_name;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `getProg_sec` (`prog_abbrv` VARCHAR(12), `section` VARCHAR(8)) RETURNS VARCHAR(20) CHARSET utf8mb4  BEGIN
    DECLARE prog_sec VARCHAR(20);
    SET prog_sec = CONCAT(prog_abbrv, ' ', section);

    RETURN prog_sec;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `academic_year`
--

CREATE TABLE `academic_year` (
  `acad_year_id` int(8) NOT NULL,
  `acad_year` varchar(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `academic_year`
--

INSERT INTO `academic_year` (`acad_year_id`, `acad_year`) VALUES
(1, '2022-2023');

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `log_id` int(8) NOT NULL,
  `activity` varchar(255) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `user_id` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`log_id`, `activity`, `date`, `user_id`) VALUES
(1, 'ovcaa.arasof@gmail.com  Submitted a document in Accomplishment Report', '2023-07-15 18:18:22', 2),
(2, 'cics.arasof@gmail.com  Submitted a document in Faculty Loading', '2023-07-15 18:21:53', 1),
(3, 'ovcaa.arasof@gmail.com  Submitted a document in Accomplishment Report', '2023-07-15 18:22:32', 2),
(4, 'ovcaa.arasof@gmail.com  Uploaded a task', '2023-07-17 22:35:39', 2),
(5, 'ovcaa.arasof@gmail.com  Uploaded a task', '2023-07-17 22:43:46', 2),
(6, 'ovcaa.arasof@gmail.com  Uploaded a task', '2023-07-17 22:45:59', 2),
(7, 'ovcaa.arasof@gmail.com  Uploaded a task', '2023-07-17 22:52:21', 2),
(8, 'cics.arasof@gmail.com  Submitted a document in Faculty Loading', '2023-07-17 22:55:27', 1),
(9, 'cics.arasof@gmail.com  Submitted a document in Faculty Loading', '2023-07-17 23:12:22', 1),
(10, 'cics.arasof@gmail.com  Submitted a document in Faculty Loading', '2023-07-17 23:14:13', 1),
(11, 'ovcaa.arasof@gmail.com  Uploaded a task', '2023-07-17 23:17:05', 2),
(12, 'cics.arasof@gmail.com  Submitted a document in Faculty Loading', '2023-07-17 23:17:40', 1),
(13, 'ovcaa.arasof@gmail.com  Uploaded a task', '2023-07-17 23:21:41', 2),
(14, 'ovcaa.arasof@gmail.com  Uploaded a task', '2023-07-17 23:23:17', 2),
(15, 'ovcaa.arasof@gmail.com  Uploaded a task', '2023-07-17 23:25:12', 2),
(16, 'ovcaa.arasof@gmail.com  Uploaded a task', '2023-07-17 23:34:40', 2),
(17, 'ovcaa.arasof@gmail.com  Uploaded a task', '2023-07-17 23:37:46', 2),
(18, 'ovcaa.arasof@gmail.com  Submitted a document in Accomplishment Report', '2023-07-18 08:36:52', 2);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_code`, `course_description`, `department_id`, `units`, `lec_hrs_wk`, `rle_hrs_wk`, `lab_hrs_wk`) VALUES
(2, 'CS 422', 'Machine Learning', 8, 3, 2.00, 0.00, 2.00),
(3, 'CS 421', 'CS Thesis 2', 8, 3, 3.00, 0.00, 0.00),
(4, 'CS 111', 'Computer Programming', 8, 3, 2.00, 0.00, 3.00),
(5, 'IT 321', 'Human-computer Interaction', 8, 3, 3.00, 0.00, 0.00),
(6, 'CS 131', 'Data Structures and Algorithms', 8, 3, 2.00, 1.00, 3.00),
(8, 'CS 322', 'Software Engineering', 8, 3, 2.00, 1.00, 2.00),
(9, 'Com Sci 9', 'Data Structures and Algorithms', 8, 3, 3.00, 0.00, 0.00),
(10, 'CS 417', 'Game Development', 8, 3, 2.00, 0.00, 2.00),
(11, 'CS 423', 'Social Issues and Professional Practice', 8, 3, 3.00, 0.00, 0.00),
(13, 'CpE 419', 'Routing and Switching (CISCO 2)', 8, 1, 0.00, 0.00, 2.00),
(14, 'CS 321', 'Programming Languages', 8, 3, 0.00, 0.00, 0.00),
(15, 'IT 221', 'Information Management', 8, 3, 2.00, 0.00, 3.00),
(16, 'IT 325', 'IT Project Management', 8, 3, 3.00, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(8) NOT NULL,
  `department_name` varchar(50) NOT NULL,
  `department_abbrv` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`, `department_abbrv`) VALUES
(8, 'Computer of Informatics and Computing Science', 'CICS'),
(9, 'Office of Vice Chancellor of Academic Affairs', 'OVCAA'),
(10, 'College of Arts and Sciences', 'CAS'),
(13, 'College of Teacher Education', 'CTE');

-- --------------------------------------------------------

--
-- Table structure for table `document_templates`
--

CREATE TABLE `document_templates` (
  `doc_template_id` int(8) NOT NULL,
  `template_title` varchar(50) NOT NULL,
  `path` varchar(50) NOT NULL,
  `sem_id` int(8) NOT NULL,
  `ref_no` varchar(50) NOT NULL,
  `effectivity_date` date NOT NULL,
  `rev_no` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `document_templates`
--

INSERT INTO `document_templates` (`doc_template_id`, `template_title`, `path`, `sem_id`, `ref_no`, `effectivity_date`, `rev_no`) VALUES
(1, 'Faculty Loading', '', 1, 'BatStateU-FO-COL-26', '2022-05-18', '03');

-- --------------------------------------------------------

--
-- Table structure for table `faculties`
--

CREATE TABLE `faculties` (
  `faculty_id` int(8) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `suffix` varchar(4) DEFAULT NULL,
  `department_id` int(8) NOT NULL,
  `is_permanent` int(4) NOT NULL,
  `is_guest` int(4) NOT NULL,
  `is_partTime` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `faculties`
--

INSERT INTO `faculties` (`faculty_id`, `firstname`, `lastname`, `middlename`, `suffix`, `department_id`, `is_permanent`, `is_guest`, `is_partTime`) VALUES
(1, 'Leo ', 'Delayola', NULL, 'Sr.', 8, 1, 0, 0),
(2, 'Lorissa Joana', 'Buenas', 'E.', '', 8, 1, 0, 0),
(3, 'Noelyn', 'De Jesus', 'M.', '', 8, 1, 0, 0),
(4, 'Kristine Grace', 'Estilo', 'B.', '', 8, 1, 0, 0),
(5, 'Inocencio', 'Madriaga', 'C.', 'Jr.', 8, 1, 0, 0),
(6, 'Albert', 'Paytaren', 'V.', '', 8, 1, 0, 0),
(7, 'Djoanna Marie', 'Salac', 'V.', '', 8, 1, 0, 0),
(8, 'Renz Mervin', 'Salac', 'A.', '', 8, 1, 0, 0),
(9, 'Benjie', 'Samonte', 'R.', '', 8, 1, 0, 0),
(10, 'Alfred', 'Brio', 'C.', '', 8, 1, 0, 0),
(11, 'Engelbert', 'Marcos', 'P.', '', 8, 1, 0, 0),
(12, 'Jayson', 'Magsino', 'C.', '', 8, 1, 0, 0),
(13, 'Albert', 'Mercado', 'S.', '', 8, 0, 1, 0),
(14, 'Bill Jericko', 'Mecado', 'C.', '', 8, 0, 0, 1),
(15, 'Audrey', 'Bataclao', 'K.', '', 8, 1, 0, 0),
(16, 'John Denver', 'Maano', '', '', 8, 1, 0, 0),
(17, 'Marrian', 'Smith', 'M', '', 8, 0, 0, 1);

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
  `sem_id` int(8) NOT NULL,
  `dept_id` int(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `faculty_loadings`
--

INSERT INTO `faculty_loadings` (`fac_load_id`, `faculty_id`, `course_id`, `section_id`, `acad_year_id`, `sem_id`, `dept_id`) VALUES
(8, 15, 5, 6, 1, 2, 8),
(9, 16, 8, 1, 1, 2, 8),
(10, 16, 8, 2, 1, 2, 8),
(11, 16, 8, 3, 1, 2, 8),
(17, 1, 1, 1, 1, 2, NULL),
(18, 6, 1, 1, 1, 1, NULL),
(19, 6, 1, 1, 1, 1, NULL),
(20, 6, 1, 1, 1, 1, NULL),
(21, 6, 1, 1, 1, 1, NULL),
(22, 6, 1, 1, 1, 1, NULL),
(23, 6, 1, 1, 1, 1, NULL),
(25, 9, 6, 1, 1, 1, 8),
(26, 9, 6, 1, 1, 1, 8),
(27, 3, 2, 3, 1, 2, 8),
(28, 8, 1, 1, 1, 2, 8),
(29, 8, 1, 1, 1, 2, 8),
(30, 8, 1, 1, 1, 2, 8),
(31, 8, 1, 1, 1, 2, 8),
(32, 8, 1, 1, 1, 2, 8),
(33, 4, 1, 1, 1, 2, 8),
(34, 15, 1, 9, 1, 1, 8),
(35, 2, 2, 1, 1, 1, 8),
(36, 2, 2, 8, 1, 1, 8),
(37, 1, 2, 9, 1, 1, 8),
(39, 15, 1, 1, 1, 1, 8),
(41, 2, 1, 1, 1, 2, 8),
(42, 2, 1, 1, 1, 2, 8);

-- --------------------------------------------------------

--
-- Table structure for table `faculty_titles`
--

CREATE TABLE `faculty_titles` (
  `fac_title_id` int(8) NOT NULL,
  `faculty_id` int(8) NOT NULL,
  `title_id` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `faculty_titles`
--

INSERT INTO `faculty_titles` (`fac_title_id`, `faculty_id`, `title_id`) VALUES
(1, 1, 1),
(4, 1, 4),
(5, 2, 4),
(6, 8, 1),
(7, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `file_table`
--

CREATE TABLE `file_table` (
  `file_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `directory` varchar(255) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `file_owner_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notif_id` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `is_task` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `program_id` int(8) NOT NULL,
  `program_title` varchar(50) NOT NULL,
  `department_id` int(8) NOT NULL,
  `program_abbrv` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`program_id`, `program_title`, `department_id`, `program_abbrv`) VALUES
(1, 'Computer Science', 8, 'CS'),
(2, 'Information Technology', 8, 'IT');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `section_id` int(8) NOT NULL,
  `program_id` int(8) NOT NULL,
  `section_name` varchar(8) NOT NULL,
  `semester_id` int(8) NOT NULL,
  `no_of_students` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`section_id`, `program_id`, `section_name`, `semester_id`, `no_of_students`) VALUES
(1, 2, '1101', 2, 30),
(2, 2, '1102', 2, 20),
(3, 2, '1103', 2, 40),
(4, 2, '1104', 2, 30),
(5, 2, '1201', 2, 40),
(7, 2, '1203', 2, 40),
(8, 2, '1204', 2, 30),
(9, 2, '2201', 2, 40),
(10, 2, '2202', 2, 40),
(11, 2, '2203', 2, 40),
(12, 2, '2204', 2, 29),
(13, 2, '3201-BA', 2, 400),
(14, 2, '3201-NT', 2, 40),
(15, 1, '3201', 2, 8),
(16, 1, '4201', 2, 20);

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `semester_id` int(8) NOT NULL,
  `sem_description` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`task_id`, `task_name`, `task_desc`, `document_id`, `date_posted`, `due_date`, `for_ovcaa`, `for_deans`, `for_heads`, `acad_year_id`, `sem_id`) VALUES
(1, 'OVCAA TASKS', 'TASK', 0, '2023-07-03', '2023-07-04', 1, 0, 0, 0, 0),
(2, 'DEANS ', 'DEANS', 0, '2023-07-03', '2023-07-03', 0, 1, 0, 0, 0),
(3, 'DEANS 2 TASKS', 'amskm', 0, '2023-07-03', '2023-07-18', 0, 1, 0, 0, 0),
(4, 'aaa', 'aa', 0, '2023-07-05', '2023-07-21', 0, 1, 0, 0, 0),
(5, 'Ticket for Parking Lot inside the Campus', 'The project would help the students to have a safe', 0, '2023-07-03', '2023-07-31', 1, 1, 0, 0, 0),
(6, 'aa', 'aa', 0, '2023-07-03', '2023-07-18', 1, 0, 0, 0, 0),
(7, 'TASK ', 'TASK', 0, '2023-07-03', '2023-07-03', 1, 0, 0, 0, 0),
(8, 'TASK ', 'TASK', 0, '2023-07-03', '2023-07-03', 1, 0, 0, 0, 0),
(9, 'TASK ', 'TASK', 0, '2023-07-03', '2023-07-03', 1, 0, 0, 0, 0),
(10, 'Faculty Loading', 'Faculty Loading AY 22-23, First Sem)', 1, '2022-08-01', '2022-12-18', 0, 1, 0, 1, 1),
(11, 'Faculty Loading', 'Second Semester 23-24', 1, '0000-00-00', '0000-00-00', 0, 1, 0, 1, 2),
(12, 'sss', 'ssss', 0, '2023-07-12', '2023-07-12', 1, 0, 0, 0, 0),
(13, 'bb', 'bb', 0, '2023-07-12', '2023-07-12', 1, 0, 0, 0, 0),
(14, 'bb', 'bb', 0, '0001-11-11', '0001-11-11', 1, 0, 0, 0, 0),
(15, '4444', '444', 0, '0044-04-04', '0004-04-04', 1, 1, 0, 0, 0),
(16, '555555', '5555555', 0, '0555-05-05', '0055-05-05', 1, 0, 0, 0, 0),
(17, 'aa', 'aaa', 0, '2023-07-12', '2023-07-12', 1, 1, 0, 0, 0),
(18, 'Faculty Loading', 'aaa', 0, '2023-07-19', '2023-07-13', 1, 1, 0, 0, 0),
(19, 'Faculty Loading', '4555', 0, '0055-05-05', '0055-05-05', 1, 1, 0, 0, 0),
(20, 'Faculty Loading', '5555', 0, '0555-05-05', '0055-05-05', 1, 1, 0, 0, 0),
(21, 'Faculty Loading', '555', 1, '0055-05-05', '0005-05-05', 1, 1, 0, 0, 0),
(22, 'Faculty Loading', '5555', 1, '0055-05-05', '0055-05-05', 1, 0, 0, 0, 0),
(23, 'Faculty Loading', 'sssss', 1, '2023-07-18', '2023-07-20', 1, 1, 0, 0, 0),
(24, 'Faculty Loading', 'adad', 1, '0001-01-01', '0001-01-01', 1, 1, 0, 0, 0),
(25, 'Faculty Loading', 'ss', 1, '2023-07-14', '2023-07-22', 1, 1, 0, 0, 0),
(26, 'Faculty Loading', 'aa', 1, '0001-01-01', '0001-01-01', 0, 1, 0, 0, 0),
(27, 'Faculty Loading', 'a', 1, '2023-07-19', '2023-07-18', 0, 1, 0, 0, 0),
(28, 'Faculty Loading', 'aaa', 1, '2023-07-17', '2023-07-17', 0, 1, 0, 0, 0),
(29, 'Faculty Loading', 'a', 1, '2023-07-17', '2023-07-26', 0, 1, 0, 0, 0),
(30, 'Faculty Loading', 'a', 1, '2023-07-17', '2023-07-18', 0, 1, 0, 0, 0),
(31, 'Faculty Loading', 'a', 1, '2023-07-17', '2023-07-17', 0, 1, 0, 0, 0),
(32, 'Faculty Loading', 'a', 1, '2023-06-29', '2023-07-17', 0, 1, 0, 0, 0),
(33, 'Faculty Loading', 'a', 1, '2023-07-17', '2023-07-17', 0, 1, 0, 0, 0),
(34, 'Faculty Loading', 'a', 1, '2023-07-17', '2023-07-17', 0, 1, 0, 0, 0),
(35, 'Faculty Loading', 'a', 1, '2023-07-28', '2023-08-05', 0, 1, 0, 0, 0),
(36, 'Faculty Loading', 'a', 1, '2023-07-19', '2023-07-04', 0, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `task_status_deans`
--

CREATE TABLE `task_status_deans` (
  `status_id` int(8) NOT NULL,
  `task_id` int(8) NOT NULL,
  `office_id` int(8) NOT NULL,
  `is_completed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `task_status_deans`
--

INSERT INTO `task_status_deans` (`status_id`, `task_id`, `office_id`, `is_completed`) VALUES
(1, 1, 9, 1),
(2, 2, 8, 1),
(3, 3, 8, 1),
(4, 4, 8, 1),
(5, 5, 8, 1),
(6, 6, 9, 1),
(7, 7, 9, 1),
(8, 8, 9, 1),
(9, 9, 9, 1),
(10, 10, 8, 1),
(11, 10, 10, 1),
(12, 15, 8, 1),
(13, 15, 9, 1),
(14, 15, 10, 1),
(15, 16, 9, 1),
(16, 17, 8, 1),
(17, 17, 9, 1),
(18, 17, 10, 1),
(19, 18, 8, 0),
(20, 18, 9, 1),
(21, 18, 10, 1),
(22, 0, 8, 1),
(23, 0, 9, 1),
(24, 0, 10, 1),
(25, 0, 8, 1),
(26, 0, 9, 1),
(27, 0, 10, 1),
(28, 0, 8, 1),
(29, 0, 9, 1),
(30, 0, 10, 1),
(31, 0, 8, 1),
(32, 0, 9, 1),
(33, 0, 10, 1),
(34, 19, 8, 0),
(35, 19, 9, 1),
(36, 19, 10, 1),
(37, 20, 8, 0),
(38, 20, 9, 1),
(39, 20, 10, 1),
(40, 21, 8, 1),
(41, 21, 9, 1),
(42, 21, 10, 1),
(43, 22, 9, 1),
(44, 23, 8, 0),
(45, 23, 9, 1),
(46, 23, 10, 1),
(47, 24, 8, 0),
(48, 24, 9, 1),
(49, 24, 10, 1),
(50, 25, 8, 0),
(51, 25, 9, 1),
(52, 25, 10, 1),
(53, 26, 8, 0),
(54, 26, 10, 1),
(55, 27, 8, 0),
(56, 27, 10, 1),
(57, 27, 13, 1),
(58, 28, 8, 0),
(59, 28, 10, 1),
(60, 28, 13, 1),
(61, 29, 8, 0),
(62, 29, 10, 1),
(63, 29, 13, 1),
(64, 30, 8, 0),
(65, 30, 10, 1),
(66, 30, 13, 1),
(67, 31, 8, 1),
(68, 31, 10, 1),
(69, 31, 13, 1),
(70, 32, 8, 1),
(71, 32, 10, 1),
(72, 32, 13, 1),
(73, 33, 8, 1),
(74, 33, 10, 1),
(75, 33, 13, 1),
(76, 34, 8, 1),
(77, 34, 10, 1),
(78, 34, 13, 1),
(79, 35, 8, 1),
(80, 35, 10, 1),
(81, 35, 13, 1),
(82, 36, 8, 1),
(83, 36, 10, 1),
(84, 36, 13, 1);

-- --------------------------------------------------------

--
-- Table structure for table `titles`
--

CREATE TABLE `titles` (
  `title_id` int(8) NOT NULL,
  `title_description` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `titles`
--

INSERT INTO `titles` (`title_id`, `title_description`) VALUES
(1, 'Professor 1'),
(2, 'Vice Chancellor for Academic Affairs'),
(3, 'Dean'),
(4, 'Faculty Researcher');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `unique_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `unique_id`, `department_id`, `email`, `password`, `img`, `status`, `type`) VALUES
(22, 402569764, 13, 'cte.arasof@gmail.com', '44fb9f21c19e7bc98470f77407027fe8', '1689607284farlight.png', 'Offline now', 'Dean'),
(23, 1514148403, 10, 'cas.arasof@gmail.com', '44fb9f21c19e7bc98470f77407027fe8', '1689646225farlight.png', 'Offline now', 'Dean'),
(25, 555239712, 9, 'admin@gmail.com', '44fb9f21c19e7bc98470f77407027fe8', '1689646772farlight.png', 'Offline now', 'Admin'),
(26, 1619800923, 9, 'ovcaa.arasof@gmail.com', '44fb9f21c19e7bc98470f77407027fe8', '16896471948cd0a7dd98109ad4554e9ab62171435b.jpg', 'Active now', 'Admin'),
(27, 796113525, 8, 'cics.arasof@gmail.com', '44fb9f21c19e7bc98470f77407027fe8', '16896474488cd0a7dd98109ad4554e9ab62171435b.jpg', 'Offline now', 'Dean');

-- --------------------------------------------------------

--
-- Table structure for table `user_notifications`
--

CREATE TABLE `user_notifications` (
  `user_notif_id` int(11) NOT NULL,
  `status` varchar(5) NOT NULL DEFAULT '0',
  `notif_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_facultyloadings`
-- (See below for the actual view)
--
CREATE TABLE `vw_facultyloadings` (
`Name of Faculty` varchar(255)
,`permanent` int(4)
,`Guest` int(4)
,`part_time` int(4)
,`Course Code` varchar(12)
,`Section` varchar(20)
,`No. of Students` int(8)
,`Total Units` int(4)
,`Lec. hrs/wk` double(4,2)
,`Lab. hrs/wk` double(4,2)
,`Total hrs/wk` double(19,2)
,`Course Description` varchar(50)
);

-- --------------------------------------------------------

--
-- Structure for view `vw_facultyloadings`
--
DROP TABLE IF EXISTS `vw_facultyloadings`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_facultyloadings`  AS   (select `getFullName_surnameFirst`(`fc`.`firstname`,`fc`.`middlename`,`fc`.`lastname`,`fc`.`suffix`) AS `Name of Faculty`,`fc`.`is_permanent` AS `permanent`,`fc`.`is_guest` AS `Guest`,`fc`.`is_partTime` AS `part_time`,`cs`.`course_code` AS `Course Code`,`getProg_sec`(`pr`.`program_abbrv`,`sc`.`section_name`) AS `Section`,`sc`.`no_of_students` AS `No. of Students`,`cs`.`units` AS `Total Units`,`cs`.`lec_hrs_wk` AS `Lec. hrs/wk`,`cs`.`lab_hrs_wk` AS `Lab. hrs/wk`,sum(`cs`.`lec_hrs_wk` + `cs`.`lab_hrs_wk`) AS `Total hrs/wk`,`cs`.`course_description` AS `Course Description` from ((((`faculty_loadings` `fl` left join `faculties` `fc` on(`fl`.`faculty_id` = `fc`.`faculty_id`)) left join `courses` `cs` on(`fl`.`course_id` = `cs`.`course_id`)) left join `sections` `sc` on(`fl`.`section_id` = `sc`.`section_id`)) left join `programs` `pr` on(`sc`.`program_id` = `pr`.`program_id`)) group by `fl`.`fac_load_id`)  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_year`
--
ALTER TABLE `academic_year`
  ADD PRIMARY KEY (`acad_year_id`);

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`log_id`);

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
-- Indexes for table `document_templates`
--
ALTER TABLE `document_templates`
  ADD PRIMARY KEY (`doc_template_id`);

--
-- Indexes for table `faculties`
--
ALTER TABLE `faculties`
  ADD PRIMARY KEY (`faculty_id`);

--
-- Indexes for table `faculty_loadings`
--
ALTER TABLE `faculty_loadings`
  ADD PRIMARY KEY (`fac_load_id`),
  ADD KEY `faculty_loadings_ibfk_1` (`section_id`),
  ADD KEY `faculty_loadings_ibfk_2` (`course_id`),
  ADD KEY `faculty_loadings_ibfk_3` (`faculty_id`),
  ADD KEY `faculty_loadings_ibfk_4` (`sem_id`);

--
-- Indexes for table `faculty_titles`
--
ALTER TABLE `faculty_titles`
  ADD PRIMARY KEY (`fac_title_id`);

--
-- Indexes for table `file_table`
--
ALTER TABLE `file_table`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `file_owner_id` (`file_owner_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notif_id`);

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
-- Indexes for table `task_status_deans`
--
ALTER TABLE `task_status_deans`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `titles`
--
ALTER TABLE `titles`
  ADD PRIMARY KEY (`title_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD PRIMARY KEY (`user_notif_id`),
  ADD KEY `notif_id` (`notif_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_year`
--
ALTER TABLE `academic_year`
  MODIFY `acad_year_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `log_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `document_templates`
--
ALTER TABLE `document_templates`
  MODIFY `doc_template_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `faculties`
--
ALTER TABLE `faculties`
  MODIFY `faculty_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `faculty_loadings`
--
ALTER TABLE `faculty_loadings`
  MODIFY `fac_load_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `faculty_titles`
--
ALTER TABLE `faculty_titles`
  MODIFY `fac_title_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `file_table`
--
ALTER TABLE `file_table`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notif_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `program_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `section_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `semester_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `task_status_deans`
--
ALTER TABLE `task_status_deans`
  MODIFY `status_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `titles`
--
ALTER TABLE `titles`
  MODIFY `title_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `user_notifications`
--
ALTER TABLE `user_notifications`
  MODIFY `user_notif_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `file_table`
--
ALTER TABLE `file_table`
  ADD CONSTRAINT `file_table_ibfk_1` FOREIGN KEY (`file_owner_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD CONSTRAINT `user_notifications_ibfk_1` FOREIGN KEY (`notif_id`) REFERENCES `notifications` (`notif_id`),
  ADD CONSTRAINT `user_notifications_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
