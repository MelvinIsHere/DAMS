<?php
session_start();
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
$error = "error";
$success = "success";
if($department_id != ""){
	
			
				$sql = mysqli_query($conn,"INSERT INTO courses(course_code,course_description,department_id,units,lec_hrs_wk,rle_hrs_wk,lab_hrs_wk)VALUES('$course_code','$course_name','$department_id','$units','$lecture','$rle','$lab')");
				if($sql){
					$message = "The course has been successfully added!";
					$_SESSION['alert'] = $success; 
	                $_SESSION['message'] =  $message;   //failed to insert
	                header("Location: ../admin/courses_management.php");
				}
				else{	
					$message = "Something went wrong when adding a new course!";	
					$_SESSION['alert'] = $error; 
                $_SESSION['message'] =  $message;   //failed to insert
                header("Location: ../admin/courses_management.php");
				}
		
}
else{
				$message = "Something went wrong when adding a new course!";	
					$_SESSION['alert'] = $error; 
                $_SESSION['message'] =  $message;   //failed to insert
                header("Location: ../admin/courses_management.php");
}



?>
