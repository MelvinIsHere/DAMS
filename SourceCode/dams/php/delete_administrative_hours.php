<?php 
session_start();
include "config.php";
$administrative_id = $_POST['loading_id']; //too lazy
$faculty_name = $_GET['faculty_name'];

$success = "success";
$error = "error";

$delteSQL = "DELETE FROM administrative_hours WHERE administrative_hrs_id = '$administrative_id'";
$result = mysqli_query($conn,$delteSQL);
if($result){
	$_SESSION['alert'] = $success; 
	$message = "Schedule has been successfully deleted!";
	$_SESSION['message'] =  $message;	//schedule conflict
		    
	header("Location: ../deans/administrative_hours.php?faculty_name=$faculty_name");
}
else{
	$_SESSION['alert'] = $error; 
	$message = "Something went wrong! Deletion Failed!";
	$_SESSION['message'] =  $message;	//schedule conflict
		    
	header("Location: ../deans/administrative_hours.php?faculty_name=$faculty_name");
}

?>