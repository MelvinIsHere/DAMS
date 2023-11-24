<?php 
session_start();

include "config.php";
$user_id = $_SESSION['user_id'];
$mfo_ppa = $_POST['mfo'];
$department_id = $_POST['department_id'];
$success_indicator = $_POST['success_indicator'];
$category = $_POST['category'];
$budget = $_POST['budget'];
$description = "";
$dean_id = $user_id;
$error = "error";
$success = "success";
$type = $_POST['type'];
$task_id = get_task_id();
if(isset($_POST['description'])){
	if($category == "CORE FUNCTION"){
		$description = "";

	}else{
		$description = $_POST['description'];
	}
}
$success_indicator = trim($success_indicator);
$insert_opcr = mysqli_query($conn,"INSERT INTO opcr(dean_id,mfo_ppa,success_indicator,budgets,department_id,description,category,task_id)
												VALUES('$dean_id','$mfo_ppa','$success_indicator','$budget','$department_id','$description','$category','$task_id')");
if($insert_opcr){
	if($type == 'Dean'){
			$message = "Successfully inserted!";
			$_SESSION['alert'] = $success; 
		    $_SESSION['message'] =  $message;   //failed to insert
		    header("Location: ../deans/opcr.php");
	}elseif($type == 'Head'){
			$message = "Successfully inserted!";
			$_SESSION['alert'] = $success; 
		    $_SESSION['message'] =  $message;   //failed to insert
		    header("Location: ../heads/opcr.php");
	}

}else{
	if($type == 'Dean'){
			$message = "Insertion Failed";
			$_SESSION['alert'] = $error; 
		    $_SESSION['message'] =  $message;   //failed to insert
		    header("Location: ../deans/opcr.php");
	}elseif($type == 'Head'){
			$message = "Insertion Failed";
			$_SESSION['alert'] = $error; 
		    $_SESSION['message'] =  $message;   //failed to insert
		    header("Location: ../heads/opcr.php");
	}
	
    ;
}
function get_task_id(){
	include "config.php";
	$query = mysqli_query($conn,"
						SELECT 
							tt.task_id,
							tt.`task_name`
						FROM tasks tt 
						LEFT JOIN terms t ON t.`term_id` = tt.`term_id`
						WHERE tt.`task_name` =  'Office Performance Commitment and Review Target' AND t.`status` = 'ACTIVE'");
	if($query){
		if(mysqli_num_rows($query)>0){
			$row = mysqli_fetch_assoc($query);
			$task_id = $row['task_id'];

			return $task_id;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

?>