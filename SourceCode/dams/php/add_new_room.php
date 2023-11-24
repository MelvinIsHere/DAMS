<?php 
include "config.php";
session_start();
$room_name = $_POST['room_name'];
$error = "error";
$success = "success";

$query = mysqli_query($conn,"INSERT INTO rooms (room_name) VALUES('$room_name')");
if($query){
	$_SESSION['alert'] = $success;
	$_SESSION['message'] = "Room has been successfully added!";

	header("Location: ../admin/room_management.php");
}else{
	$_SESSION['alert'] = $error;
	$_SESSION['message'] = "Something went wrong adding the room!";

	header("Location: ../admin/room_management.php");
}



?>