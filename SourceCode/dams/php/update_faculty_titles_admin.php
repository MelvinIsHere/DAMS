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
			
			$message =  "$faculty title has been successfully updated!";

			$_SESSION['alert'] = $success; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../admin/faculty_titles_management.php");

			
		}
		else{
			
			$message = "Something went wrong updating the title for $faculty";	

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
