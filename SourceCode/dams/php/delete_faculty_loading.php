<?php

include "config.php";
session_start();
$loading_id = $_POST['loading_id'];


$error = "error";
$success = "success";

$sql = mysqli_query($conn,"DELETE FROM faculty_loadings WHERE fac_load_id = '$loading_id'");
if($sql){
	$message = "Load has been successfully deleted!";
    $_SESSION['alert'] = $success; 
    $_SESSION['message'] =  $message;   //failed to insert
	header("Location: ../deans/faculty_loading_ui.php");
}
else{
	$message = "Something went wrong, deletion failed!";
    $_SESSION['alert'] = $error; 
    $_SESSION['message'] =  $message;   //failed to insert
	header("Location: ../deans/faculty_loading_ui.php");
}

 ?>