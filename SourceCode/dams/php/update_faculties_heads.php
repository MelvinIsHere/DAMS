<?php
session_start();
include "config.php";
include "functions.php";
$faculty_id = $_POST['faculty_id'];
$facultyname = $_POST['faculty_name'];
$type = $_POST['type'];
$error = "error";
$success = "success";


if($type == "Permanent"){
	$sql = mysqli_query($conn,"UPDATE faculties SET is_permanent = 1,is_guest=0,is_partTime=0 WHERE faculty_id = '$faculty_id'");
	if($sql){
		$message = "Faculty has been successfully updated!";
		$_SESSION['alert'] = $success; 
	    $_SESSION['message'] =  $message;   //failed to insert
	    header("Location: ../heads/faculties_management.php");
	}else{
		$message = "Something went wrong updating faculty information!";
		$_SESSION['alert'] = $error; 
	    $_SESSION['message'] =  $message;   //failed to insert
	    header("Location: ../heads/faculties_management.php");
	}
}elseif($type == "Temporary"){
	$sql = mysqli_query($conn,"UPDATE faculties SET is_permanent = 0,is_guest=1,is_partTime=0 WHERE faculty_id = '$faculty_id'");
	if($sql){
		$message = "Faculty has been successfully updated!";
		$_SESSION['alert'] = $success; 
	    $_SESSION['message'] =  $message;   //failed to insert
	    header("Location: ../heads/faculties_management.php");
	}else{
		$message = "Something went wrong updating faculty information!";
		$_SESSION['alert'] = $error; 
	    $_SESSION['message'] =  $message;   //failed to insert
	    header("Location: ../heads/faculties_management.php");
	}
}elseif($type == "Part Time"){
	$sql = mysqli_query($conn,"UPDATE faculties SET is_permanent = 0,is_guest=0,is_partTime=1 WHERE faculty_id = '$faculty_id'");
	if($sql){
		$message = "$facultyname has been successfully updated!";
		$_SESSION['alert'] = $success; 
	    $_SESSION['message'] =  $message;   //failed to insert
	    header("Location: ../heads/faculties_management.php");
	}else{
		$message = "Something went wrong updating faculty information!";
		$_SESSION['alert'] = $error; 
	    $_SESSION['message'] =  $message;   //failed to insert
	    header("Location: ../heads/faculties_management.php");
	}
}else{
	$message = "Something went wrong updating faculty information!";
	$_SESSION['alert'] = $error; 
	$_SESSION['message'] =  $message;   //failed to insert
	header("Location: ../heads/faculties_management.php");
}


?>
