<?php
session_start();
include "config.php";
include "functions.php";
$program_id = $_POST['program_id'];
$program_name = $_POST['program_name'];
$program_abbrv = $_POST['program_abbrv'];
$error = "error";
$success = "success";

if(!empty($program_id)){
	
		$sql = mysqli_query($conn,"UPDATE programs SET program_title = '$program_name',program_abbrv = '$program_abbrv' 
									WHERE program_id = '$program_id'");
		if($sql){
			
			$message = "Program ".$program_name." has been successfully updated!";
			$_SESSION['alert'] = $success; 
	        $_SESSION['message'] =  $message;   //failed to insert
	        header("Location: ../admin/program_management.php");	


		
		}
		else{
			
			$message = "Something wrong updating the program $program_name";
			$_SESSION['alert'] = $error; 
	        $_SESSION['message'] =  $message;   //failed to insert
	        header("Location: ../admin/program_management.php");	
		}

}
else{
	$message = "There are no program such as " .$program_name;
	$_SESSION['alert'] = $error; 
    $_SESSION['message'] =  $message;   //failed to insert
    header("Location: ../admin/program_management.php");	
}


	



?>
