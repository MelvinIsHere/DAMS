<?php 
session_start();

include "config.php";
$user_id = $_SESSION['user_id'];
$mfo_ppa = $_POST['mfo'];
$department_id = $_POST['department_id'];
$success_indicator = $_POST['success_indicator'];
$category = $_POST['category'];
$budget = $_POST['budget'];
$description = $_POST['description'];
$dean_id = $user_id;
$error = "error";
$success = "success";

$success_indicator = trim($success_indicator);
$insert_opcr = mysqli_query($conn,"INSERT INTO opcr(dean_id,mfo_ppa,success_indicator,budgets,department_id,description,category)
												VALUES('$dean_id','$mfo_ppa','$success_indicator','$budget','$department_id','$description','$category')");
if($insert_opcr){
	$message = "Successfully inserted!";
	$_SESSION['alert'] = $success; 
    $_SESSION['message'] =  $message;   //failed to insert
    header("Location: ../deans/opcr.php");
}else{
	$message = "Insertion Failed";
	$_SESSION['alert'] = $error; 
    $_SESSION['message'] =  $message;   //failed to insert
    header("Location: ../deans/opcr.php");
}

?>