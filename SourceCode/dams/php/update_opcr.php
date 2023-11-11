<?php 
session_start();
include "config.php";

$mfo_ppa = $_POST['mfo'];
$opcr_id = $_POST['loading_id'];
$success_indicator = $_POST['success_indicators'];
$success_indicator = trim($success_indicator);
$category = $_POST['category'];
$budget = $_POST['budget'];
$descriptions = $_POST['description'];

$update_opcr = mysqli_query($conn,"UPDATE opcr SET mfo_ppa = '$mfo_ppa',success_indicator = '$success_indicator',budgets = '$budget',description = '$descriptions',category = '$category' WHERE opcr_id = '$opcr_id'");
if($update_opcr){
	$message = "Successfully Uploaded";
	$_SESSION['alert'] = $error; 
    $_SESSION['message'] =  $message;   //failed to insert
    header("Location: ../deans/opcr.php");
}else{
	$message = "Upload Failed";
	$_SESSION['alert'] = $error; 
    $_SESSION['message'] =  $message;   //failed to insert
    header("Location: ../deans/opcr.php");
}



?>