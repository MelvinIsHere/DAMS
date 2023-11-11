<?php 
session_start();
include "config.php";
$faculty_sched_id = $_POST['loading_id'];
$error = "error";
$success = "success";
$faculty_name = $_GET['faculty_name'];

$description = getDescription($faculty_sched_id);
$class_sched_delete = false;
$faculty_sched_delete = false;
if($description == "Class Schedule"){
	$delete = deleteClassSched($faculty_sched_id);
	$class_sched_delete = true;
}else{
	$delete_query = "DELETE FROM faculty_schedule WHERE faculty_sched_id = '$faculty_sched_id'";
	$result = mysqli_query($conn,$delete_query);
	$faculty_sched_delete = true;
}



if($class_sched_delete || $faculty_sched_delete){
	$_SESSION['alert'] = $success; 
	$message = "Shedule has been successfully deleted!";
	 $_SESSION['message'] =  $message;	

	header("Location: ../deans/faculty_schedule_individual.php?faculty_name=$faculty_name");
}
else{
	 $_SESSION['alert'] = $error; 
    $message = "Something went wrong! Schedule cannot be deleted!";
    $_SESSION['message'] =  $message;	//schedule conflict
	header("Location: ../deans/faculty_schedule_individual.php?faculty_name=$faculty_name");
}



function selectClassSchedId($faculty_sched_id){
	include "config.php";
	$sql = "SELECT class_sched_id FROM faculty_schedule WHERE faculty_sched_id = '$faculty_sched_id' AND description = 'Class Schedule'";
	$result = mysqli_query($conn,$sql);
	if($result){
		$class_sched_id = '';
		while($row = mysqli_fetch_assoc($result)){
			$class_sched_id = $row['class_sched_id'];
		}
		return $class_sched_id;
	}else{
		return "error";
	}

	
}
function deleteClassSched($faculty_sched_id){
	include "config.php";
	$class_sched_id = selectClassSchedId($faculty_sched_id);
	if($class_sched_id != "error"){
		$sql = "DELETE FROM class_schedule WHERE class_sched_id = '$class_sched_id'";
		$execute = mysqli_query($conn,$sql);
		if($execute){
			return true;
		}else{
			return false;
		}
	}else{
		return false;//not in there
	}
	
}
function getDescription($faculty_sched_id){
	include "config.php";

	$sql = "SELECT description FROM faculty_schedule WHERE faculty_sched_id = '$faculty_sched_id'";
	$result = mysqli_query($conn,$sql);

	if($result){
		$description = '';
		while($row = mysqli_fetch_assoc($result)){
			$description = $row['description'];
		}
		return $description;
	}else{
		return 'error';
	}
}
?>