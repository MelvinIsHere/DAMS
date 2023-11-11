<?php
session_start();
include "config.php";
include "functions.php";
$department_name = $_POST['department_name'];
$department_abbrv = $_POST['department_abbrv'];
$department_id = $_POST['dept_id'];
$error = "error";
$success = "success";

$sql = mysqli_query($conn,"UPDATE departments SET department_name = '$department_name',department_abbrv = '$department_abbrv' 
							WHERE department_id = '$department_id'");
if($sql){
	$message = "The department has been successfully updated!";
	$_SESSION['alert'] = $success; 
    $_SESSION['message'] =  $message;   //failed to insert
    header("Location: ../admin/manage_departments.php");


}
else{
	$message = "The department data update has failed!"; 
	$_SESSION['alert'] = $error; 
         $_SESSION['message'] =  $message;   //failed to insert
         header("Location: ../admin/manage_departments.php");
}



?>
