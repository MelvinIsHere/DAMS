<?php
session_start();
include "config.php";
include "functions.php";
$faculty = $_POST['faculty'];
$title = $_POST['title'];
$error = "error";
$success = "success";
$faculty_id = getFacultyId($faculty);
if($faculty_id != ""){
	$title_id = getTitleId($title);
	if($title_id != "error"){
		$sql = mysqli_query($conn,"INSERT INTO faculty_titles(faculty_id,title_id) VALUES('$faculty_id','$title_id')");
		if($sql){
			
			$message = "Title has been successfully added!";
			$_SESSION['alert'] = $success; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculty_titles_management.php");

		
		}
		else{
			$message = "Something went wrong adding title";	
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculty_titles_management.php");	
		}
	}
	else{
		$message = "Something went wrong adding title";	
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculty_titles_management.php");	
	} 
}
else{
	$message = "Something went wrong adding title";	
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculty_titles_management.php");	
}


?>
