<?php 
include "config.php";
session_start();
$position_id = $_POST['position_id'];
$position_name = $_POST['position_name'];
$error = "error";
$success = "success";

$query = mysqli_query($conn,"UPDATE titles SET title_description = '$position_name' WHERE title_id = '$position_id'");
if($query){
	$_SESSION['alert'] = $success;
	$_SESSION['message'] = "Position has been successfully updated!";

	header("Location: ../admin/positions.php");
}else{
	$_SESSION['alert'] = $error;
	$_SESSION['message'] = "Something went wrong updating the position!";

	header("Location: ../admin/positions.php");
}



?>