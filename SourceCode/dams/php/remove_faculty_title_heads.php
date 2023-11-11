<?php
session_start();
include "config.php";
include "functions.php";
$faculty_title_id = $_POST['faculty_title_id'];
$error = "error";
$success = "success";


if(!empty($faculty_title_id)){
	
	$sql = mysqli_query($conn,"DELETE FROM faculty_titles  WHERE fac_title_id = '$faculty_title_id'");
	if($sql){
		
		$message = "Faculty title has been successfully removed!";
		$_SESSION['alert'] = $success; 
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../heads/faculty_titles_management.php");
	}
	else{
		$message = "Something went wrong removing faculty title!";
		$_SESSION['alert'] = $error; 
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../heads/faculty_titles_management.php");


	}
}
else{
		$message = "Something went wrong removing faculty title!";
		$_SESSION['alert'] = $error; 
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../heads/faculty_titles_management.php");

}


?>
