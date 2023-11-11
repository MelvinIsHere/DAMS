<?php 
session_start();
include "config.php";
$delete_id = $_POST['loading_id'];
$error = "error";
$success = "success";


$delete_mfo = mysqli_query($conn,"DELETE FROM ipcr_table WHERE ipcr_id = '$delete_id'");
if($delete_mfo){
	$message = "Successfully deleted!";
	$_SESSION['alert'] = $success; 
    $_SESSION['message'] =  $message;   //failed to insert
    header("Location: ../staffs/ipcr.php");
}else{
	$message = "Deletion Failed";
	$_SESSION['alert'] = $error; 
    $_SESSION['message'] =  $message;   //failed to insert
    header("Location: ../staffs/ipcr.php");
}

?>