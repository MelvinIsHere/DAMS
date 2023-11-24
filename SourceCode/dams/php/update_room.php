<?php 
include "config.php";
session_start();
$room_id = $_POST['room_id'];
$room_name = $_POST['room_name'];
$error = "error";
$success = "success";

$query = mysqli_query($conn,"UPDATE rooms SET room_name = '$room_name' WHERE room_id = '$room_id'");
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