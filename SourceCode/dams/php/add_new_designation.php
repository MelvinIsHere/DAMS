<?php 
include "config.php";
session_start();
$designation_name = $_POST['designation_name'];
$error = "error";
$success = "success";

$query = mysqli_query($conn,"INSERT INTO designation (designation) VALUES('$designation_name')");
if($query){
	$_SESSION['alert'] = $success;
	$_SESSION['message'] = "Designation has been successfully added!";

	header("Location: ../admin/designations.php");
}else{
	$_SESSION['alert'] = $error;
	$_SESSION['message'] = "Something went wrong adding the room!";

	header("Location: ../admin/designations.php");
}



?>