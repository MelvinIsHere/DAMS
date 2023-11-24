<?php 
include "config.php";
session_start();
$room_id = $_POST['room_id'];

$error = "error";
$success = "success";

$query = mysqli_query($conn,"DELETE FROM rooms WHERE room_id = '$room_id'");
if($query){
	$_SESSION['alert'] = $success;
	$_SESSION['message'] = "Room has been successfully updated!";

	header("Location: ../admin/room_management.php");
}else{
	$_SESSION['alert'] = $error;
	$_SESSION['message'] = "Something went wrong updating the room!";

	header("Location: ../admin/room_management.php");
}



?>