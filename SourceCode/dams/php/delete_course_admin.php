<?php
session_start();
include "config.php";
include "functions.php";
$course_id = $_POST['course_id'];
$error = "error";
$success = "success";

if(!empty($course_id)){
	
	$sql = mysqli_query($conn,"DELETE FROM courses  WHERE course_id = '$course_id'");
	if($sql){
		
			$message = "Course has been successfully deleted!";
			$_SESSION['alert'] = $success; 
            $_SESSION['message'] =  $message;   //failed to insert
                header("Location: ../admin/courses_management.php");
	}
	else{
			$message = "Something went wrong Deleting the course!";
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../admin/courses_management.php");

	}
}
else{
	
	$message = "Something went wrong Deleting the course!";
	$_SESSION['alert'] = $error; 
    $_SESSION['message'] =  $message;   //failed to insert
    header("Location: ../admin/courses_management.php");
}


?>
