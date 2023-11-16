<?php

session_start();
include "config.php";
$department_id = $_GET['department_id'];
echo $department_id;
$error = "error";
$success = "success";
$class_sched_task_id = get_task_id_by_active_term_class_sched();
$rum_task_id = get_task_id_by_active_term();
$matrix_sql = "SELECT 
				cs.class_sched_id
		    FROM class_schedule cs
		    LEFT JOIN room_utilization_matrixes ru ON cs.class_sched_id = ru.class_sched_id
		    LEFT JOIN faculty_loadings fl ON fl.`fac_load_id` = cs.`faculty_loading_id`
		    LEFT JOIN tasks tt ON tt.task_id = cs.task_id
		    WHERE fl.`dept_id` = '$department_id' AND cs.task_id = '$class_sched_task_id'

            ";
     

$matrix_data = mysqli_query($conn,$matrix_sql);
$no_rooms = false;


while ($row = mysqli_fetch_array($matrix_data)) {
    	$load_in = -1;
	$id = $row['class_sched_id'];
	$verify = verify_matrix_id($id,$conn,$rum_task_id);
	if($verify){
		//nothing to do here because it exist already it will pass by so it can continue inserting
		$no_rooms = true;
	}elseif(!$verify){
		//if false meaning there are no rum_id that already inserted
		$load_in = insert_room_matrix($id,$conn,$rum_task_id); //execute this
		echo $load_in;
	}else{
		//nothing to do here
	}
	

   
}
	$message = "Room has been loaded!";
	$_SESSION['alert'] = $success; 
	$_SESSION['message'] =  $message;   //failed to insert
	header("Location: ../deans/room_utilization_matrix_ui.php");

function insert_room_matrix($id,$conn,$rum_task_id){

	
	$load_in_query = "INSERT INTO room_utilization_matrixes(class_sched_id,task_id) VALUES('$id','$rum_task_id')";
	$execution = mysqli_query($conn,$load_in_query);
	if($execution){
		 $last_inserted_id = $conn->insert_id;
		return $last_inserted_id;
	}else{
		return "failed";
	}
}
function verify_matrix_id($id,$conn,$rum_task_id){
	$matrix_sql = " SELECT 
                        r.rum_id
                    FROM room_utilization_matrixes r
                    LEFT JOIN tasks tt ON tt.task_id = r.task_id
                    WHERE r.class_sched_id = '$id'
                    AND r.task_id  = '$rum_task_id'
                               
                                    

            ";
     

     $matrix_data = mysqli_query($conn,$matrix_sql);
     if(mysqli_num_rows($matrix_data) > 0){
     	return true;//already inserted
     }else{
     	return false;//not in there so it will insert
     }
}

function get_task_id_by_active_term_class_sched(){
    include 'config.php';
    $query = mysqli_query($conn,"SELECT 
                                    t.term_id,
                                    t.status,
                                    tt.task_name,
                                    tt.`task_id` AS task_id

                                FROM tasks tt 
                                LEFT JOIN terms t ON t.term_id = tt.`term_id`  
                                WHERE t.status = 'ACTIVE'  AND tt.`task_name` = 'Class Schedule'");
    if($query){
        if(mysqli_num_rows($query) > 0){
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

function get_task_id_by_active_term(){
    include 'config.php';
    $query = mysqli_query($conn,"SELECT 
                                    t.term_id,
                                    t.status,
                                    tt.task_name,
                                    tt.`task_id` AS task_id

                                FROM tasks tt 
                                LEFT JOIN terms t ON t.term_id = tt.`term_id`  
                                WHERE t.status = 'ACTIVE'  AND tt.`task_name` = 'Room Utilization Matrix'");
    if($query){
        if(mysqli_num_rows($query) > 0){
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