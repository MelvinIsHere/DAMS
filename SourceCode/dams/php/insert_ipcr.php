<?php 
session_start();

include "config.php";
$user_id = $_SESSION['user_id'];
$mfo = $_POST['mfo'];
$success_indicators = $_POST['success_indicator'];
$category = $_POST['category'];
$description = $_POST['description'];
$department_id = $_POST['department_id'];
$error = "error";
$success = "success";

$success_indicators = trim($success_indicators);
$execute = mysqli_query($conn,"INSERT INTO ipcr_table(major_output,success_indicator,department_id,user_id,category,description) VALUES('$mfo','$success_indicators','$department_id','$user_id','$category','$description')");
if($execute){
	$message = "Successfully inserted!";
	$_SESSION['alert'] = $success; 
    $_SESSION['message'] =  $message;   //failed to insert
    header("Location: ../staffs/ipcr.php");
}else{
	$message = "Insertion Failed";
	$_SESSION['alert'] = $error; 
    $_SESSION['message'] =  $message;   //failed to insert
    header("Location: ../staffs/ipcr.php");
}


// ?>