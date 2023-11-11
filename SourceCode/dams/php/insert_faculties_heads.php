<?php
session_start();

include "config.php";
include "functions.php";
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$middle_name = $_POST['middle_name'];
$suffix = $_POST['suffix'];
$type = $_POST['type'];
$department_id = $_POST['department_id'];
$fullname = $last_name . " " . $first_name . " " . $middle_name . " " . $suffix;
$error = "error";
$success = "success";

if(!empty($department_id)){
	if($type == "Permanent"){
		$sql = mysqli_query($conn,"INSERT INTO faculties(firstname,lastname,middlename,suffix,department_id,is_permanent,is_guest,is_partTime)
									VALUES('$first_name','$last_name','$middle_name','$suffix','$department_id',1,0,0)");
		if($sql){
			$message = "Faculty has been successfully added!";
			$_SESSION['alert'] = $success; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../heads/faculties_management.php");
		}else{
			$message = "Something went wrong adding a faculty";  
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../heads/faculties_management.php");
		}
	}elseif($type == "Temporary"){
		$sql = mysqli_query($conn,"INSERT INTO faculties(firstname,lastname,middlename,suffix,department_id,is_permanent,is_guest,is_partTime)
									VALUES('$first_name','$last_name','$middle_name','$suffix','$department_id',0,1,0)");
		if($sql){
			$message = "Faculty has been successfully added!";
			$_SESSION['alert'] = $success; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../heads/faculties_management.php");
		}else{
				$message = "Something went wrong adding a faculty";  
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../heads/faculties_management.php");
		}
	}
	elseif($type == "Part Time"){
		$sql = mysqli_query($conn,"INSERT INTO faculties(firstname,lastname,middlename,suffix,department_id,is_permanent,is_guest,is_partTime)
									VALUES('$first_name','$last_name','$middle_name','$suffix','$department_id',0,0,1)");
		if($sql){
			$message = "Faculty has been successfully added!";
			$_SESSION['alert'] = $success; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../heads/faculties_management.php");
		}else{
				$message = "Something went wrong adding a faculty";  
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../heads/faculties_management.php");
		}
	}
	else{
				$message = "Something went wrong adding a faculty";  
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../heads/faculties_management.php");
		}
}	
else{
	$message = "Something went wrong adding a faculty";  
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../heads/faculties_management.php");
}

?>
