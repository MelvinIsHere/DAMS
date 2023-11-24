<?php 
session_start();
include "config.php";
$faculty_name = $_POST['faculty'];
$designation = $_POST['designation'];
$type = $_POST['type'];
$success = 'success';
$error = "error";
$faculty_id = get_faculty_id($faculty_name);
$designation_id = get_designation_id($designation);

$query = mysqli_query($conn,"INSERT INTO faculty_designation(faculty_id,designation_id) VALUES('$faculty_id','$designation_id')");
if($query){
	 $message = "Successfully Added designation to faculty!";
	$_SESSION['alert'] = $success; 
    $_SESSION['message'] =  $message;   //failed to insert
    if($type == 'Admin'){
    	header("Location: ../admin/designations.php");
    }elseif($type == 'Dean'){   
    	header("Location: ../deans/designations.php");
    }elseif($type == 'Head'){   
    	header("Location: ../heads/designations.php");
    }                                  
}else{
	$message = "Something went wrong adding designation!";
	$_SESSION['alert'] = $error; 
    $_SESSION['message'] =  $message;   //failed to insert
    if($type == 'Admin'){
    	header("Location: ../admin/designations.php");
    }elseif($type == 'Dean'){   
    	header("Location: ../deans/designations.php");
    }elseif($type == 'Head'){   
    	header("Location: ../heads/designations.php");
    }        
}



function get_faculty_id($faculty_name){
	include "config.php";

	$query = mysqli_query($conn,"SELECT faculty_id FROM faculties WHERE CONCAT(lastname,' ',firstname,' ',middlename,' ',suffix) = '$faculty_name'");
	if($query){
		if(mysqli_num_rows($query)>0){
			$row = mysqli_fetch_assoc($query);
			$faculty_id = $row['faculty_id'];

			return $faculty_id;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

function get_designation_id($designation){
	include "config.php";

	$query = mysqli_query($conn,"SELECT designation_id FROM designation WHERE designation = '$designation'");
	if($query){
		if(mysqli_num_rows($query)>0){
			$row = mysqli_fetch_assoc($query);
			$designation_id = $row['designation_id'];

			return $designation_id;
		}else{
			return false;
		}
	}else{
		return false;
	}
}






?>