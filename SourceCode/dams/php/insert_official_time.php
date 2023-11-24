<?php 
session_start();
	include "config.php";
include "functions/faculty_schedule_functions.php";
$department_id = $_POST['department_id'];
$faculty_name = $_POST['faculty_name'];

$day = $_POST['days'];
$time_start = $_POST['time_start'];
$time_end = $_POST['time_end'];
$day_sched = $_POST['day_sched'];
$error = "error";
$success = "success";

$time_end = date("h:i A", strtotime($time_end));
$time_start = date("h:i A", strtotime($time_start));
$amPm_start = substr($time_start, -2);
$amPm_end = substr($time_end, -2);


$faculty_id = get_faculty_id_by_name($faculty_name);
if($faculty_id != "error"){	
	$faculty_id_to_be_inserted = $faculty_id;		
	
}else{	
	$message = "Something went wrong updating Schedule!";
	$_SESSION['alert'] = $error;     
    $_SESSION['message'] =  $message;	    	
	header("Location: ../deans/faculty_schedule_individual.php?faculty_name=$faculty_name");
}
$term_id = get_term_id();
$task_id = get_task_id_by_active_term();
echo $term_id;
$conflictDetected = false;

$conflictQuery  = "SELECT
                        ot.official_id,
                        ot.time_start,
                        ot.time_end,
                        ot.day,
                        ot.day_sched                                        
                   FROM official_time ot
                   LEFT JOIN tasks tt ON tt.`task_id` = ot.task_id
                   
                   LEFT JOIN faculties f ON f.`faculty_id` = ot.faculty_id                                    
                   WHERE f.department_id = '$department_id'
                   AND CONCAT(f.lastname,' ',f.firstname,' ',f.middlename,' ',f.suffix) = '$faculty_name'
                   AND tt.term_id = '$term_id'
                   AND ot.day = '$day' AND ot.day_sched = '$day_sched'";
$conflictResult = mysqli_query($conn, $conflictQuery);
if($conflictResult){
	if(mysqli_num_rows($conflictResult) >0){
			$conflictDetected = true;

	}else{
		echo "nonee";
	}
		
	
}else{
	echo "none";
}         

$conflictQuery  = "SELECT
                        ot.official_id,
                        ot.time_start,
                        ot.time_end,
                        ot.day,
                        ot.day_sched                                        
                   FROM official_time ot
                   LEFT JOIN tasks tt ON tt.`task_id` = ot.task_id
                   
                   LEFT JOIN faculties f ON f.`faculty_id` = ot.faculty_id                                    
                   WHERE f.department_id = '$department_id'
                   AND CONCAT(f.lastname,' ',f.firstname,' ',f.middlename,' ',f.suffix) = '$faculty_name'
                   AND tt.term_id = '$term_id'
                   AND ot.day = '$day'";


//execute the query
$conflictResult = mysqli_query($conn, $conflictQuery);

//verify if there is conflict
while ($row = mysqli_fetch_array($conflictResult)) {
    $existing_start_time = $row['time_start'];
    $existing_end_time = $row['time_end'];
    
    $day_db = $row['day'];
    
    $new_start_timestamp = strtotime($time_start);
    $new_end_timestamp = strtotime($time_end);
    $existing_start_timestamp = strtotime($existing_start_time);
    $existing_end_timestamp = strtotime($existing_end_time);

    if (
        ($new_start_timestamp >= $existing_start_timestamp && $new_start_timestamp < $existing_end_timestamp) ||
        ($new_end_timestamp > $existing_start_timestamp && $new_end_timestamp <= $existing_end_timestamp)
        
    ) {
            $conflictDetected = true;//set the conflict to true because there is a conflict
            echo "conflictDetected";
        break; // break the loop
    } else {
        echo "No overlap."; //else do nothing
    }
   
}



if(!$conflictDetected){
	 $insert_official_time = mysqli_query($conn,"INSERT INTO official_time(time_start,time_end,day,task_id,day_sched,faculty_id) VALUES('$time_start','$time_end','$day','$task_id','$day_sched','$faculty_id')");    
 if($insert_official_time){    		
 				  $_SESSION['alert'] = $success; 
    			$message = "Schedule added successfully!";	
    			$_SESSION['message'] =  $message;	//failed to insert
    			
				header("Location: ../deans/official_time.php?faculty_name=$faculty_name");
   } 
   else{
    		  $_SESSION['alert'] = $error; 
    $message = "Something went wrong adding schedule!";	
    $_SESSION['message'] =  $message;	//failed to insert
    echo mysqli_error($conn);
	header("Location: ../deans/official_time.php?faculty_name=$faculty_name");
    	}


}else{

    		  $_SESSION['alert'] = $error; 
    $message = "Schedule conflicted!";	
    $_SESSION['message'] =  $message;	//failed to insert
    echo mysqli_error($conn);
	header("Location: ../deans/official_time.php?faculty_name=$faculty_name");
}


function get_term_id(){
	include "config.php";
	$query = mysqli_query($conn,"SELECT 
										term_id

								FROM terms
								
								WHERE status = 'ACTIVE'");
	if($query){
		if(mysqli_num_rows($query) > 0){
			$row = mysqli_fetch_assoc($query);
			$term_id = $row['term_id'];

			return $term_id;
		}else{
			return false;
		}
	}else{
		return false;
	}
}
function get_task_id_by_active_term(){
	include "config.php";
	$query = mysqli_query($conn,"SELECT 
									t.term_id,
									t.status,
									tt.task_name,
									tt.`task_id` AS task_id

								FROM tasks tt 
								LEFT JOIN terms t ON t.term_id = tt.`term_id`  
								WHERE t.status = 'ACTIVE'  AND tt.`task_name` = 'Faculty Schedule'");
	if($query){
		if(mysqli_num_rows($query) > 0){
			$row = mysqli_fetch_assoc($query);
			$taskid = $row['task_id'];

			return $taskid;
		}else{
			return false;
		}
	}else{
		return false;
	}
}
?>	