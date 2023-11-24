<?php 
include "config.php";
session_start();
$designation_id = $_POST['designation_id'];
$designation_name = $_POST['designation_name'];
$error = "error";
$success = "success";

$query = mysqli_query($conn,"UPDATE designation SET designation = '$designation_name' WHERE designation_id = '$designation_id'");
if($query){
	$_SESSION['alert'] = $success;
	$_SESSION['message'] = "Designation has been successfully updated!";

	header("Location: ../admin/designations.php");
}else{
	$_SESSION['alert'] = $error;
	$_SESSION['message'] = "Something went wrong updating the Designation!";

	header("Location: ../admin/designations.php");
}



?>