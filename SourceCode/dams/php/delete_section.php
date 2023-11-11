<?php
session_start();
include "config.php";
include "functions.php";
$section = $_POST['section_id'];

$error = "error";
$success = "success";
if(!empty($section)){
	
	$sql = mysqli_query($conn,"DELETE FROM sections  WHERE section_id = '$section'");
	if($sql){
		$message = "Section has been successfully deleted!";
			$_SESSION['alert'] = $success; 
            $_SESSION['message'] =  $message;   //failed to insert
             header("Location: ../deans/section_management.php");
	}
	else{
		$message = "Something went wrong when removing the section!";
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/section_management.php");
	}
}
else{$message = "Something went wrong when removing the section!";
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/section_management.php");
}


?>
