<?php 


function getfacultyId($facultyname){
	include "../config.php";
	$sql = mysqli_query($conn,"SELECT faculty_id FROM faculties  WHERE CONCAT(firstname,' ',middlename, ' ',lastname) = '$facultyname'");
	$row = mysqli_fetch_assoc($sql);
	$faculty_id = $row['faculty_id'];

	if(empty($faculty_id)){
		$value = "";
		return $value;
	}
	else{
		return $faculty_id;
	}
}
function getTitleId($titles){
	include "../config.php";

	$sql = mysqli_query($conn,"SELECT title_id FROM titles WHERE title_description = '$titles'");
	$row = mysqli_fetch_assoc($sql);
	$title_id = $row['title_id'];

	if(empty($title_id)){
				$value = "";
		return $value;
	}
	else{
		return $title_id;
	}
}
function getDeptId($department){
	include "../config.php";
	$sql = mysqli_query($conn,"SELECT department_id FROM departments WHERE department_name = '$department'");
	$row = mysqli_fetch_assoc($sql);
	$dept_id = $row['department_id'];

	if(empty($dept_id)){
		$value = "";
		return $value;
	}
	else{
		return $dept_id;
	}
}
function getCourseId($course){
	include "../config.php";
	$sql = mysqli_query($conn,"SELECT course_id FROM courses WHERE course_description = '$course'");
	$row = mysqli_fetch_assoc($sql);
	$course_id = $row['course_id'];
	if(empty($course_id)){
		$value = "";
		return $value;
	}
	else{
		return $course_id;
	}
}
function getProgramId($program){
	include "../config.php";
	$sql = mysqli_query($conn,"SELECT program_id FROM programs WHERE program_title = '$program'");
	$row = mysqli_fetch_assoc($sql);
	$program_id = $row['program_id'];
	if(empty($program_id)){
		$value = "";
		return $value;
	}
	else{
		return $program_id;
	}
}

function getSemesterId($semester){
	include "../config.php";
	$sql = mysqli_query($conn,"SELECT semester_id FROM semesters WHERE sem_description = '$semester'");
	$row = mysqli_fetch_assoc($sql);
	$semester_id = $row['semester_id'];
	if(empty($semester_id)){
		$value = "";
		return $value;
	}
	else{
		return $semester_id;
	}
}
?>