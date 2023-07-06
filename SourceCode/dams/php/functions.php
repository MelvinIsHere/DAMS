<?php

	
function insertQuery(string $fileName, string $path,string $users_id){
include "config.php";
 $query = "INSERT INTO file_table(file_name,directory,file_owner_id) VALUES ('$fileName','$path','$users_id')";
 $run = mysqli_query($conn,$query);
if (!$run) {
		echo "Error: " . mysqli_error($conn);
	}
}
function updateTaskStats($task_id){
	include "config.php";
	$update = mysqli_query($conn,"UPDATE task_status SET is_completed = '0' WHERE task_id = '$task_id'");
	if (!$update) {
		echo "Error: " . mysqli_error($conn);
	}

}

function getName($user_id){
	include "config.php";
	$sql = mysqli_query($conn,"SELECT department_name FROM departments WHERE $user_id = '$user_id'");
	$info = mysqli_fetch_assoc($sql);
	if ($info) {
		$name =  $info['department_name'];
		return $name;
	}
	else{
		$error = mysqli_error($conn);
		return $error;
	}

}
function  getTaskName($task_id){
	include "config.php";
	$id = $task_id;
	$sql = mysqli_query($conn,"SELECT task_name FROM tasks WHERE task_id = '$id'");
	$task = mysqli_fetch_assoc($sql);
	$task_name = $task['task_name'];
	if(!$sql){
		return mysqli_error($conn);
	}
	else{
		return $task_name;
		echo "getTaskName";
	}
}
function notifications($name,$task_name){
	include "config.php";
	$content = $name . "Submitted a file in " . $task_name . "!";
	$sql = mysqli_query($conn,"INSERT INTO notifications(content) VALUES('$content')");
	if(!$sql){
		return mysqli_error($conn);
		
	}
	else{
		return $conn->insert_id;
	}

}
function user_notif_dean($users_id,$notif_id){
	include "config.php";
	$sql = mysqli_query($conn,"INSERT INTO user_notifications(status,notif_id,user_id) VALUES(0,'$notif_id','$users_id')");
	if(!$sql){
		return mysqli_error($conn);
	}
	else{
		return "success";
	}
}

 ?>

