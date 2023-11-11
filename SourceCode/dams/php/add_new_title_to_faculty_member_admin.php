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
            header("Location: ../admin/faculty_titles_management.php");

		
		}
		else{
			$message = "Something went wrong granting title to $faculty";	
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../admin/faculty_titles_management.php");
		}
	}
	else{
		$message = "There are no title such as " . $title;
		$_SESSION['alert'] = $error; 
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../admin/faculty_titles_management.php");
	} 
}
else{
	$message = "There are no faculty member such as " . $faculty;
	$_SESSION['alert'] = $error; 
    $_SESSION['message'] =  $message;   //failed to insert
    header("Location: ../admin/faculty_titles_management.php");
}


?>
