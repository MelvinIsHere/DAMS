<?php
session_start();
include "config.php";
include "functions.php";
$program_name = $_POST['program_name'];
$program_abbrv = $_POST['program_abbrv'];
$dept_name = $_POST['department_name'];
$error = "error";
$success = "success";
$department_id = getDeptId($dept_name);
if($department_id != ""){
	$sql = mysqli_query($conn,"INSERT INTO programs(program_title,department_id,program_abbrv) 
								VALUES('$program_name','$department_id','$program_abbrv')");
	if($sql){
		$message = "The  $program_name has been successfully added!";
		$_SESSION['alert'] = $success; 
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../admin/program_management.php");
	}
	else{
		$message = "Something went wrong adding the program $program_name";
		$_SESSION['alert'] = $error; 
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../admin/program_management.php");
	}
}
else{
	$message = "Something went wrong adding the program $program_name";
	$_SESSION['alert'] = $error; 
    $_SESSION['message'] =  $message;   //failed to insert
    header("Location: ../admin/program_management.php");
}

?>
