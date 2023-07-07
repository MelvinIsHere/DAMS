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
	$sql = mysqli_query($conn,"INSERT INTO notifications(content,is_task) VALUES('$content','no')");
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



//tasks
function insertTask($task_name,$description,$dateStart,$dateEnd,$ovcaa,$deans,$department){
	include "config.php";
	  $conn -> query("INSERT INTO tasks (task_name, task_desc, date_posted, due_date, for_ovcaa,for_deans, for_heads) VALUES ('$task_name','$description', '$dateStart', '$dateEnd','$ovcaa', '$deans', '$department')");
                // $result = $conn->query($sql);
                // Print auto-generated id
                $id = $conn -> insert_id;

           if(!empty($id)){
           	return $id;
           }
           else{
           		return "Not inserted";
           }
}
function  getDept_id($task_id){
				include "config.php";
                $sql = "SELECT department_id FROM departments WHERE department_abbrv != 'OVCAA'";
                $result = mysqli_query($conn,$sql);
                if ($result) {
                    while($row = mysqli_fetch_array($result)){
                        $dept_id = $row['department_id'];
                        $sql = mysqli_query($conn,"INSERT INTO task_status(task_id,office_id,is_completed)VALUES('$id','$dept_id',1)");
                    }
                }
            }
function adminInsertTask($task_name,$description,$dateStart,$dateEnd,$ovcaa,$deans){
	include "config.php";
	   $conn -> query("INSERT INTO tasks (task_name, task_desc, date_posted, due_date, for_ovcaa,for_deans) VALUES ('$task_name','$description', '$dateStart', '$dateEnd','$ovcaa', '$deans')");
                // $result = $conn->query($sql);
                // Print auto-generated id
                $id = $conn -> insert_id;

            return $id;
                
}
function insertTaskNotification($task_name){
	include "config.php";
	$content = "Office of Vice Chancellor of Academic Affairs Uploaded a task ".$task_name;
	
	
	$sql = mysqli_query($conn,"INSERT INTO notifications(content,is_task) VALUES('$content','yes')");
	if(!$sql){
		return mysqli_error($conn);
		
	}
	else{
		return $conn->insert_id;
	}



}
function getEmail($users_id){
	include "config.php";
	$getName = mysqli_query($conn,"SELECT email FROM users WHERE user_id = '$users_id'");
	$emailArray = mysqli_fetch_assoc($getName);
	$email = $emailArray['email'];

	return $email;
}


function activity_log($users_id){
	include "config.php";
	$email = getEmail($users_id);
	$activity = $email . "  Uploaded a task";   
	$act_log = mysqli_query($conn,"INSERT INTO activity_log(activity,user_id) VALUES('$activity','$users_id')")	;



}

function activity_log_submitted_documents($users_id,$task_name){
	include "config.php";
	$email = getEmail($users_id);
	$activity = $email . "  Submitted a document in " . $task_name;   
	$act_log = mysqli_query($conn,"INSERT INTO activity_log(activity,user_id) VALUES('$activity','$users_id')")	;



}
function notifFilter($users_id){


}



 ?>

