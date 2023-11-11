<?php
session_start();
include "config.php";
include "functions.php";
$program_id = $_POST['program_id'];
$error = "error";
$success = "success";


if(!empty($program_id)){
	
		$sql = mysqli_query($conn,"DELETE FROM programs WHERE program_id = '$program_id'");
		if($sql){
			
			$message = "Program has been successfully deleted!";
			$_SESSION['alert'] = $success; 
        	$_SESSION['message'] =  $message;   //failed to insert
        	header("Location: ../admin/program_management.php");

		
		}
		else{
			$message = "Something wrong deleting the program";
			$_SESSION['alert'] = $error; 
        	$_SESSION['message'] =  $message;   //failed to insert
        	header("Location: ../admin/program_management.php");
		}

}
else{
		$message = "Something wrong deleting the program";
		$_SESSION['alert'] = $error; 
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../admin/program_management.php");
}


	



?>
