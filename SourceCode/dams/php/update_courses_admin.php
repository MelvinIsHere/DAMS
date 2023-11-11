<?php
session_start();
include "config.php";
include "functions.php";
$course_id = $_POST['course_id'];
$course_name = $_POST['course_name'];
$course_code = $_POST['course_code'];
$units = $_POST['units'];
$lecture = $_POST['lecture'];
$rle = $_POST['rle'];
$lab = $_POST['lab'];
$error = "error";
$success = "success";
if(!empty($course_id)){
	$sql = mysqli_query($conn,"UPDATE courses SET course_description = '$course_name', course_code = '$course_code',units = '$units',
								lec_hrs_wk = '$lecture', rle_hrs_wk = '$rle', lab_hrs_wk = '$lab' WHERE course_id = '$course_id'");
	if($sql){
		$message = "Course $course_name has been successfully updated!";
		$_SESSION['alert'] = $success; 
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../admin/courses_management.php");
	}
	else{
		$message = "Something went wrong updating the course  $course_name!";
		$_SESSION['alert'] = $error; 
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../admin/courses_management.php");
	}

}
else{
		$message = "Something went wrong updating the course  $course_name!";
		$_SESSION['alert'] = $error; 
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../admin/courses_management.php");
}

?>
