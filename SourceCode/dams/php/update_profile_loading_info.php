<?php 
session_start();
include "config.php";
$deloading = $_POST['deloading'];
$no_of_research = $_POST['research'];
$faculty_id = $_POST['faculty_id'];
$type = $_POST['type'];
$success = "success";
$error = "error";

$update = mysqli_query($conn,"UPDATE faculties SET no_deloading = '$deloading', no_of_research = '$no_of_research' WHERE faculty_id = '$faculty_id'");
if($update){
	$message = "Information successfully updated!";
        $_SESSION['alert'] = $success; 
        $_SESSION['message'] =  $message;   //failed to insert
        if($type == 'Admin'){                                               
            header("Location: ../admin/profile.php");
        }elseif($type == 'Dean'){   
           	header("Location: ../deans/profile.php");
        }elseif($type == 'Head'){   
			header("Location: ../heads/profile.php");
        } 
}else{
	$message = "Something went wrong, information failed to update!";
        $_SESSION['alert'] = $error; 
        $_SESSION['message'] =  $message;   //failed to insert
        if($type == 'Admin'){                                               
            header("Location: ../admin/profile.php");
        }elseif($type == 'Dean'){   
           	header("Location: ../deans/profile.php");
        }elseif($type == 'Head'){   
			header("Location: ../heads/profile.php");
        } 
}

?>