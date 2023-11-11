<?php 
session_start();
include "config.php";
$delete_id = $_POST['loading_id'];
$error = "error";
$success = "success";


$delete_opcr = mysqli_query($conn,"DELETE FROM opcr WHERE opcr_id = '$delete_id'");
if($delete_opcr){
	$message = "Successfully deleted!";
	$_SESSION['alert'] = $success; 
    $_SESSION['message'] =  $message;   //success to insert
    header("Location: ../deans/opcr.php");
}else{
	$message = "Deletion Failed";
	$_SESSION['alert'] = $error; 
    $_SESSION['message'] =  $message;   //failed to insert
    header("Location: ../deans/opcr.php");
}

?>