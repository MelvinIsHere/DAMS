<?php

include "config.php";
include "functions.php";
$course_id = $_POST['course_id'];


if(!empty($course_id)){
	
	$sql = mysqli_query($conn,"DELETE FROM courses  WHERE course_id = '$course_id'");
	if($sql){
		header("Location: ../deans/courses_management.php?Message :Course has been successfully deleted!");
	}
	else{
		header("Location: ../deans/courses_management.php?Message :Course deletion failed!");

	}
}
else{
	header("Location: ../deans/courses_management.php?Message :Course deletion failed!");
}


?>
