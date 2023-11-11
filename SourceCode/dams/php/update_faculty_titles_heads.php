<?php
session_start();
include "config.php";
include "functions.php";
$faculty = $_POST['faculty_name'];
$title = $_POST['faculty_title'];
$faculty_title_id = $_POST['faculty_title_id'];

$error = "error";
$success = "success";

$faculty_id = getFacultyId($faculty);
if($faculty_id != ""){
	$title_id = getTitleId($title);
	if($title_id != "error"){
		$sql = mysqli_query($conn,"UPDATE faculty_titles SET faculty_id = '$faculty_id', title_id = '$title_id' WHERE fac_title_id = '$faculty_title_id'");
		if($sql){
			
			
			$message =  "Faculty title has been successfully updated!";
			$_SESSION['alert'] = $success; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../heads/faculty_titles_management.php");

			
		}
		else{
			
			$message = "Something went wrong updating the title!";	

			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../heads/faculty_titles_management.php");	
		}
	}
	else{
		$message = "Something went wrong updating the title!";	

			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../heads/faculty_titles_management.php");	
	} 
}
else{
	$message = "Something went wrong updating the title!";	

			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../heads/faculty_titles_management.php");	
}


?>
