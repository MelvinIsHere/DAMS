<?php 
session_start();
include "config.php";

$mfo = $_POST['mfo'];
$loading_id = $_POST['loading_id'];
$success_indicators = $_POST['success_indicators'];
$description = $_POST['description'];
$category = $_POST['category'];
$error = "error";
$success = "success";

$update_ipcr = mysqli_query($conn,"UPDATE ipcr_table SET major_output = '$mfo',success_indicator = '$success_indicators',category = '$category',description = '$description' WHERE ipcr_id = '$loading_id'");
if($update_ipcr){
	$message = "Successfully Updated!";
	$_SESSION['alert'] = $success; 
    $_SESSION['message'] =  $message;   //failed to insert
    header("Location: ../staffs/ipcr.php");
}else{
	$message = "update Failed";
	$_SESSION['alert'] = $error; 
    $_SESSION['message'] =  $message;   //failed to insert
    header("Location: ../staffs/ipcr.php");
}










?>