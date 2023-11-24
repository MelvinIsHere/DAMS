<?php 
session_start();
include "config.php";
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$middlename = $_POST['middlename'];
$suffix = $_POST['suffix'];
$faculty_id = $_POST['faculty_id'];
$type = $_POST['type'];
    $error = "error";
    $success = "success";

	$update = mysqli_query($conn,"UPDATE faculties SET firstname = '$firstname',middlename='$middlename',lastname = '$lastname',suffix = '$suffix' WHERE faculty_id = '$faculty_id'");
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