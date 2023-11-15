<?php 
session_start();

include "config.php";
$user_id = $_SESSION['user_id'];

$mfo = $_POST['mfo'];
$success_indicators = $_POST['success_indicator'];
$category = $_POST['category'];
$description = "";//empty at first
$department_id = $_POST['department_id'];
$error = "error";
$success = "success";
$acad_id = getAcadYear();
$sem_id = getSemid();

if(isset($_POST['description'])){
	if($category == "Instruction" || $category == "Support"){
		$description = "";

	}else{
		$description = $_POST['description'];
	}
}
echo $description;

$success_indicators = trim($success_indicators);
$execute = mysqli_query($conn,"INSERT INTO ipcr_table(major_output,success_indicator,department_id,user_id,category,description,acad_year_id,semester_id) VALUES('$mfo','$success_indicators','$department_id','$user_id','$category','$description','$acad_id','$sem_id')");
if($execute){
	$message = "Successfully inserted!";
	$_SESSION['alert'] = $success; 
    $_SESSION['message'] =  $message;   //failed to insert
    header("Location: ../staffs/ipcr.php");
}else{
	$message = "Insertion Failed";
	$_SESSION['alert'] = $error; 
    $_SESSION['message'] =  $message;   //failed to insert
    header("Location: ../staffs/ipcr.php");
}

function getAcadYear(){
	include "config.php";
	$query = mysqli_query($conn,"SELECT acad_year_id FROM academic_year WHERE status = 'ACTIVE'");
	if($query){
		if(mysqli_num_rows($query) > 0){
			$row = mysqli_fetch_assoc($query);
			$acad_id = $row['acad_year_id'];

			return $acad_id; 
		}else{
			return false;
		}
	}else{
		return false;
	}
}

function getSemid(){
	include "config.php";
	$query = mysqli_query($conn,"SELECT semester_id FROM semesters WHERE status = 'ACTIVE'");
	if($query){
		if(mysqli_num_rows($query) > 0){
			$row = mysqli_fetch_assoc($query);
			$semester_id = $row['semester_id'];

			return $semester_id; 
		}else{
			return false;
		}
	}else{
		return false;
	}
}


// ?>