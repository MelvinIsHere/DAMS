<?php 
session_start();
include "config.php";


$type = $_POST['type'];
$faculty_designation_id = $_POST['faculty_designation_id'];
$success = 'success';
$error = "error";
$query = mysqli_query($conn,"DELETE FROM faculty_designation WHERE fac_desig_id = '$faculty_designation_id'");
if($query){
	 $message = "Successfully removed designation of faculty!";
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
	$message = "Something went wrong removing designation!";
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


?>