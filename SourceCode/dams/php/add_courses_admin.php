<?php

include "config.php";
include "functions.php";
$course_name =$_POST['course_name'];
$course_code = $_POST['course_code'];
$units = $_POST['units'];
$lecture = $_POST['lecture'];
$rle = $_POST['rle'];
$lab = $_POST['lab'];
$dept_name = $_POST['department_name'];

$department_id = getDeptId($dept_name);

if($department_id != ""){
	
			
				$sql = mysqli_query($conn,"INSERT INTO courses(course_code,course_description,department_id,units,lec_hrs_wk,rle_hrs_wk,lab_hrs_wk)VALUES('$course_code','$course_name','$department_id','$units','$lecture','$rle','$lab')");
				if($sql){
					header("Location: ../admin/courses_management.php? Message: The course has been successfully added!");
				}
				else{
					header("Location: ../admin/courses_management.php? Message: The adding of new course failed!");	
				}
		
}
else{
	header("Location: ../admin/courses_management.php? Message: Failed to add course!");
}



?>
