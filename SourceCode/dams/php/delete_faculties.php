<?php
session_start();
include "config.php";
include "functions.php";
$faculty_id = $_POST['faculty_id'];
$error = "error";
$success = "success";

if(!empty($faculty_id)){
	
	$sql = mysqli_query($conn,"DELETE FROM faculties  WHERE faculty_id = '$faculty_id'");
	if($sql){
		$message = "Faculty member has been successfully removed!";
		$_SESSION['alert'] = $success; 
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../deans/faculties_management.php");
	}
	else{
		$message = "Something went wrong removing faculty member";
		$_SESSION['alert'] = $error; 
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../deans/faculties_management.php");

	}
}
else{
		$message = "Something went wrong removing faculty member";
		$_SESSION['alert'] = $error; 
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../deans/faculties_management.php");
}


?>
