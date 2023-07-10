<?php 
include "../config.php";
include "functions.php";

$section_name = $_POST['section_name'];
$course = $_POST['course'];
$program = $_POST['program'];
$semester = $_POST['semester'];
$total_studs = $_POST['total_stud'];

if(empty($section_name) || empty($course) || empty($program) || empty($semester) || empty($total_studs)){
	echo "Fields are required please input all";
}
else{
	//get course_id 
	$course_id = getCourseId($course);
	$program_id = getProgramId($program);

	$semester_id = getSemesterId($semester);
	if($course_id === "" || $semester_id === "" || $program_id === ""){
		echo "Something went wrong please try again";

	}
	else{
		$sql = mysqli_query($conn,"INSERT INTO sections(course_id,program_id,section_name,semester_id,no_of_students)VALUES('$course_id','$program_id','$section_name','$semester_id','$total_studs')");
		if($sql){
			echo "New section " . $section_name . "added!";
		}
		else{
			echo "Something Went wrong section not added!";
		}

	}
}


?>