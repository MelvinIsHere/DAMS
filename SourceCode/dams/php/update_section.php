<?php
session_start();
include "config.php";
include "functions.php";
$section = $_POST['section_name'];
$students = $_POST['students'];
$error = "error";
$success = "success";
$adviser = $_POST['adviser'];
$section_id = $_POST['section_id'];
$section = substr($section, 2);
$section = trim($section);




$adviser_id =getAdviserId($conn,$adviser);
if($adviser_id){
	$sql = mysqli_query($conn,"UPDATE sections SET no_of_students = '$students', section_name = '$section', adviser_id = '$adviser_id' WHERE section_id = '$section_id'");
	if($sql){
		$message = "Section has been successfully updated!";
        $_SESSION['alert'] = $success; 
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../deans/section_management.php");
	}else{
		$message = "Something went wrong updating the Section";
        $_SESSION['alert'] = $error; 
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../deans/section_management.php");

	}
}else{
	$sql = mysqli_query($conn,"UPDATE sections SET no_of_students = '$students',section_name = '$section' WHERE section_id = '$section_id'");
	if($sql){
		$message = "Section has been successfully updated!";
        $_SESSION['alert'] = $success; 
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../deans/section_management.php");
	}else{
		$message = "Something went wrong updating the Section";
        $_SESSION['alert'] = $error; 
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../deans/section_management.php");

	}
}
	


function getAdviserId($conn,$adviser){
	$query = mysqli_query($conn,"SELECT faculty_id FROM faculties WHERE CONCAT(firstname,' ',middlename,' ',lastname,' ',suffix) = '$adviser'");
	if($query){
		if(mysqli_num_rows($query) >0){
			$row = mysqli_fetch_assoc($query);
			$faculty_id = $row['faculty_id'];
			return $faculty_id;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

?>
