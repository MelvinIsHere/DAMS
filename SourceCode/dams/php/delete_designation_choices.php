<?php 
include "config.php";
session_start();
$designation_id = $_POST['designation_id'];

$error = "error";
$success = "success";

$query = mysqli_query($conn,"DELETE FROM designation WHERE designation_id = '$designation_id'");
if($query){
	$_SESSION['alert'] = $success;
	$_SESSION['message'] = "Designation has been successfully updated!";

	header("Location: ../admin/designations.php");
}else{
	$_SESSION['alert'] = $error;
	$_SESSION['message'] = "Something went wrong updating the designation!";

	header("Location: ../admin/designations.php");
}



?>