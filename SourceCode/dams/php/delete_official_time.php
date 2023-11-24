<?php 
session_start();
$error = "error";
$success = "success";

include "config.php";

$delete_id = $_POST['loading_id'];

$query = mysqli_query($conn,"DELETE FROM official_time WHERE official_id = '$delete_id'");
if($query){
		 $_SESSION['alert'] = $success; 
    $message = "Schedule deleted succesfully!";	
    $_SESSION['message'] =  $message;	//failed to insert
    echo mysqli_error($conn);
	header("Location: ../deans/official_time.php?faculty_name=$faculty_name");
}else{
	 $_SESSION['alert'] = $error; 
    $message = "Something went wrong deleting schedule!";	
    $_SESSION['message'] =  $message;	//failed to insert
    echo mysqli_error($conn);
	header("Location: ../deans/official_time.php?faculty_name=$faculty_name");
}




?>