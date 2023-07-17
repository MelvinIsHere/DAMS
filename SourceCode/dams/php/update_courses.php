<?php

include "config.php";
include "functions.php";
$course_id = $_POST['course_id'];
$course_name = $_POST['course_name'];
$course_code = $_POST['course_code'];
$units = $_POST['units'];
$lecture = $_POST['lecture'];
$rle = $_POST['rle'];
$lab = $_POST['lab'];

if(!empty($course_id)){
	$sql = mysqli_query($conn,"UPDATE courses SET course_description = '$course_name', course_code = '$course_code',units = '$units',
								lec_hrs_wk = '$lecture', rle_hrs_wk = '$rle', lab_hrs_wk = '$lab' WHERE course_id = '$course_id'");
	if($sql){
		header("Location: ../deans/courses_management.php?Message :Course " . $course_name . " has been successfully updated!");
	}
	else{
		header("Location: ../deans/courses_management.php?Message :Course ". $course_name . "update failed!");
	}

}
else{
	header("Location: ../deans/courses_management.php?Message :Course ". $course_name . "update failed!");
}

?>
