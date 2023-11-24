<?php 
include "config.php";
session_start();
$position_name = $_POST['position_name'];
$error = "error";
$success = "success";

$query = mysqli_query($conn,"INSERT INTO titles (title_description) VALUES('$position_name')");
if($query){
	$_SESSION['alert'] = $success;
	$_SESSION['message'] = "Position has been successfully added!";

	header("Location: ../admin/positions.php");
}else{
	$_SESSION['alert'] = $error;
	$_SESSION['message'] = "Something went wrong adding the position!";

	header("Location: ../admin/positions.php");
}



?>