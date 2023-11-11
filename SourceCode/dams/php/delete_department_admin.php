<?php
session_start();
include "config.php";
include "functions.php";

$department_id = $_POST['dept_id'];
$erro = "error";
$success = "success";

if(!empty($department_id)){
	$sql = mysqli_query($conn,"DELETE FROM departments WHERE department_id = '$department_id'");
	if($sql){
		$message = "The department has been successfully removed!";
		$_SESSION['alert'] = $success; 
         $_SESSION['message'] =  $message;   //failed to insert
         header("Location: ../admin/manage_departments.php");
	}
	else{
		$message = "Something went wrong removing the department";
		$_SESSION['alert'] = $error; 
         $_SESSION['message'] =  $message;   //failed to insert
         header("Location: ../admin/manage_departments.php");
	}
}
else{
	$message = "Something went wrong removing the department";
	$_SESSION['alert'] = $error; 
    $_SESSION['message'] =  $message;   //failed to insert
    header("Location: ../admin/manage_departments.php");
}


?>
