<?php 
///ayaw gumana ng emppty
session_start();
include "functions/faculty_schedule_functions.php";
include "config.php";
$faculty_id_to_be_inserted = '';
$course_id_to_be_inserted = '';	
$section_id_to_be_inserted = '';
$room_id_to_be_inserted = '';
$semester_id_to_be_inserted = '';
$acad_id_to_be_inserted = '';
$time_start_id_to_be_inserted = '';
$time_end_id_to_be_inserted = '';
$task_id = get_task_id_by_active_term();

$faculty_name = $_POST['faculty'];
$dept_id = $_POST['department_id']; 
// $room_name = $_POST['room_name'];
$day = $_POST['days'];

$time_start = $_POST['time_start'];
$time_end = $_POST['time_end'];
$faculty_name = $_GET['faculty_name'];
$description = $_POST['description'];
$error ="error";
$success = "success";
$time_end = date("h:i A", strtotime($time_end));
$time_start = date("h:i A", strtotime($time_start));
$amPm_start = substr($time_start, -2);
$amPm_end = substr($time_end, -2);


if($description == "Official time morning" || $description == "Official time afternoon"){
	header("Location: insert_rcot.php?day=$day&dept_id=$dept_id&task_id=$task_id&faculty_name=$faculty_name&time_start=$time_start&time_end=$time_end&description=$description");
}else{


$faculty_id = get_faculty_id_by_name($faculty_name);
if($faculty_id != "error"){	
	$faculty_id_to_be_inserted = $faculty_id;		
	// $room_id = get_room_id_by_room_name($room_name);
	// if($room_id != "error"){
		// $room_id_to_be_inserted = $room_id;									 	
		$time_start_id = time_id_start($time_start);
		if($time_start_id != "error"){
			$time_start_id_to_be_inserted =  $time_start_id;
			$time_end_id = time_id_end($time_end);
			if($time_end_id != "error"){
			 	$time_end_id_to_be_inserted = $time_end_id;
			 	$semeste_id = get_sem_active_id();
			 	if($semeste_id != "error"){
			 		$semester_id_to_be_inserted = $semeste_id;
			 		$acad_id = get_active_id_acad_year();			 				
			 		if($acad_id != "error"){
			 			$acad_id_to_be_inserted =  $acad_id;			 					
			 		}else{
			 			$message = "Something went wrong adding Schedule to faculty!";	//acad id
			 			$_SESSION['alert'] = $error;   
    					$_SESSION['message'] =  $message;
						// header("Location: ../deans/faculty_schedule_individual.php?faculty_name=$faculty_name");
			 		}
			 	}else{
			 		$message = "Something went wrong adding Schedule to faculty!s";	//semester id
			 		$_SESSION['alert'] = $error;     
    				$_SESSION['message'] =  $message;
					// header("Location: ../deans/faculty_schedule_individual.php?faculty_name=$faculty_name");
			 	}
			 }else{
			 	$message = "Something went wrong! You cant end with that kind of time!"; //time end id	
			 	$_SESSION['alert'] = $error;     			
    			$_SESSION['message'] =  $message;
				// header("Location: ../deans/faculty_schedule_individual.php?faculty_name=$faculty_name");
			 }
		}else{
			 $message = "Something went wrong! You cant start with that kind of time!"; //time start id	
			 $_SESSION['alert'] = $error;     
    		 $_SESSION['message'] =  $message;
				// header("Location: ../deans/faculty_schedule_individual.php?faculty_name=$faculty_name");
		}
	// }else{
	// 	$message = "Something went wrong adding Schedule to faculty!q";	//room id
	// 	$_SESSION['alert'] = $error;     
    // 	$_SESSION['message'] =  $message;
	// 	// header("Location: ../deans/faculty_schedule_individual.php?faculty_name=$faculty_name");
	// }
}else{	
	$message = "Something went wrong adding Schedule to faculty!g";
	$_SESSION['alert'] = $error;     
    $_SESSION['message'] =  $message;	    	
	// header("Location: ../deans/faculty_schedule_individual.php?faculty_name=$faculty_name");
}





//query if there is comnflict in schedule
$time_start_dt = DateTime::createFromFormat('H:i s', $time_start);
$time_end_dt = DateTime::createFromFormat('H:i s', $time_end);


$conflictQuery  = "
SELECT 
	f.firstname,
	f.middlename,
	f.lastname,
	f.suffix,
	d.department_name,
	c.course_code,
	-- r.room_name,
	ay.acad_year,
	sm.sem_description,
	s.section_name,
	p.program_abbrv,
	t.time_s,
	
	t2.time_e,
	
	fs.day
	
FROM faculty_schedule fs
LEFT JOIN faculties f ON f.faculty_id = fs.faculty_id
LEFT JOIN departments d ON d.department_id = fs.department_id
LEFT JOIN courses c ON c.course_id = fs.course_id
-- LEFT JOIN rooms r ON r.room_id = fs.room_id
LEFT JOIN semesters sm ON sm.semester_id = fs.semester_id
LEFT JOIN academic_year ay ON ay.acad_year_id = fs.acad_year_id
LEFT JOIN sections s ON s.section_id = fs.section_id
LEFT JOIN programs p ON p.program_id = s.program_id
LEFT JOIN `time` t ON t.time_id = fs.time_start_id
LEFT JOIN `time` t2 ON t2.time_id = fs.time_end_id
LEFT JOIN tasks tt ON tt.task_id = fs.task_id
WHERE fs.day = '$day' AND d.department_id = '$dept_id'
AND tt.task_id = '$task_id'
AND CONCAT(f.lastname,' ',f.firstname,' ',f.middlename,' ',f.suffix) = '$faculty_name'
AND description != 'Official time morning' AND description != 'Official time afternoon'";
//execute the query
$conflictResult = mysqli_query($conn, $conflictQuery);
//set the conflict to false
$conflictDetected = false;
//verify if there is conflict
while ($row = mysqli_fetch_array($conflictResult)) {
    $existing_start_time = $row['time_s'];
    $existing_end_time = $row['time_e'];
    
    $day_db = $row['day'];
    
    $new_start_timestamp = strtotime($time_start);
    $new_end_timestamp = strtotime($time_end);
    $existing_start_timestamp = strtotime($existing_start_time);
    $existing_end_timestamp = strtotime($existing_end_time);

    if (
        ($new_start_timestamp >= $existing_start_timestamp && $new_start_timestamp < $existing_end_timestamp) ||
        ($new_end_timestamp > $existing_start_timestamp && $new_end_timestamp <= $existing_end_timestamp) && 
        $day_db == $day //&& $room_db == $room_name
    ) {
            $conflictDetected = true;//set the conflict to true because there is a conflict
        break; // break the loop
    } else {
        echo "No overlap."; //else do nothing
    }
}


// //verify for official time
// $conflictQuery  = "
// SELECT 
// 	f.firstname,
// 	f.middlename,
// 	f.lastname,
// 	f.suffix,
// 	d.department_name,
// 	c.course_code,
// 	-- r.room_name,
// 	ay.acad_year,
// 	sm.sem_description,
// 	s.section_name,
// 	p.program_abbrv,
// 	t.time_s,
	
// 	t2.time_e,
	
// 	fs.day,
// 	fs.description
	
// FROM faculty_schedule fs
// LEFT JOIN faculties f ON f.faculty_id = fs.faculty_id
// LEFT JOIN departments d ON d.department_id = fs.department_id
// LEFT JOIN courses c ON c.course_id = fs.course_id
// -- LEFT JOIN rooms r ON r.room_id = fs.room_id
// LEFT JOIN semesters sm ON sm.semester_id = fs.semester_id
// LEFT JOIN academic_year ay ON ay.acad_year_id = fs.acad_year_id
// LEFT JOIN sections s ON s.section_id = fs.section_id
// LEFT JOIN programs p ON p.program_id = s.program_id
// LEFT JOIN `time` t ON t.time_id = fs.time_start_id
// LEFT JOIN `time` t2 ON t2.time_id = fs.time_end_id
// LEFT JOIN tasks tt ON tt.task_id = fs.task_id
// WHERE fs.day = '$day' AND d.department_id = '$dept_id'
// AND tt.task_id = '$task_id'
// AND CONCAT(f.lastname,' ',f.firstname,' ',f.middlename,' ',f.suffix) = '$faculty_name'
// AND (fs.description = 'Official time morning' OR fs.description = 'Official time afternoon')";
// //execute the query
// $conflictResult = mysqli_query($conn, $conflictQuery);
// //set the conflict to false

// //verify if there is conflict
// while ($row = mysqli_fetch_array($conflictResult)) {
// 	echo 'a';
//     $existing_start_time = $row['time_s'];
//     $existing_end_time = $row['time_e'];
    
//     $day_db = $row['day'];
//     $description_db = $row['description'];
//     $new_start_timestamp = strtotime($time_start);
//     $new_end_timestamp = strtotime($time_end);
//     $existing_start_timestamp = strtotime($existing_start_time);
//     $existing_end_timestamp = strtotime($existing_end_time);

// if($description_db == 'Official time morning'){
// 	if($new_start_timestamp < $existing_start_timestamp || $new_end_timestamp > $existing_end_timestamp){
// 		$conflictDetected = true;
// 		echo "no overlap mor";
// 	}
// 	if($new_start_timestamp > $existing_end_timestamp){
// 		$conflictDetected = true;
// 		echo "no overlap mor";
// 	}
// 	else{
// 		echo "no overlap mot";
// 	}
// }if($description_db == 'Official time afternoon'){
// 	if($new_start_timestamp < $existing_start_timestamp || $new_end_timestamp > $existing_end_timestamp){
// 		$conflictDetected = true;
// 		echo "no overlap aft";
// 	}
// 	if($new_start_timestamp > $existing_end_timestamp){
// 		$conflictDetected = true;
// 		echo "overlap aft";
// 	}
// 	else{
// 		echo "no overlap aft";
// 	}
// }

   
// }



// $conflictQuery2 = "SELECT 
//         cs.`class_sched_id`,
        
        
//         t.`time_s`,
  
//         t2.time_e
//     FROM class_schedule cs 
//     LEFT JOIN rooms r ON r.room_id = cs.room_id
//     LEFT JOIN `time` t ON t.time_id = cs.time_start_id
//     LEFT JOIN `time` t2 ON t2.time_id = cs.time_end_id
//     LEFT JOIN faculty_loadings fl ON fl.`fac_load_id` = cs.`faculty_loading_id`
//     LEFT JOIN departments dp ON dp.`department_id` = fl.`dept_id`
//     WHERE cs.`day` = '$day' AND r.room_id = '$room_id_to_be_inserted'
//     AND fl.`dept_id` = '$dept_id'
	

// ";

// $conflictResult = mysqli_query($conn, $conflictQuery2);



// while ($row = mysqli_fetch_array($conflictResult)) {
//     $existing_start_time = $row['time_s'];
//     $existing_end_time = $row['time_e'];
    
//     $new_start_timestamp = strtotime($time_start);
//     $new_end_timestamp = strtotime($time_end);
//     $existing_start_timestamp = strtotime($existing_start_time);
//     $existing_end_timestamp = strtotime($existing_end_time);

//     if (
//         ($new_start_timestamp >= $existing_start_timestamp && $new_start_timestamp < $existing_end_timestamp) ||
//         ($new_end_timestamp > $existing_start_timestamp && $new_end_timestamp <= $existing_end_timestamp)
//     ) {
//             $conflictDetected = true;
//         break;
//     } else {
//         echo "No overlap2.";
//     }
// }




if ($conflictDetected) {
    $_SESSION['alert'] = $error; 
    $message = "Something went wrong! Schedule conflict detected!";
    $_SESSION['message'] =  $message;	//schedule conflict
    
	// header("Location: ../deans/faculty_schedule_individual.php?faculty_name=$faculty_name");
	
} else {
   
 	 $insertFacultyScheduleSQL = mysqli_query($conn,"INSERT INTO faculty_schedule(faculty_id,department_id,semester_id,acad_year_id,time_start_id,time_end_id,day,description,task_id) VALUES('$faculty_id_to_be_inserted','$dept_id','$semester_id_to_be_inserted','$acad_id_to_be_inserted','$time_start_id_to_be_inserted','$time_end_id_to_be_inserted','$day','$description','$task_id')");    
 if($insertFacultyScheduleSQL){    		
 				  $_SESSION['alert'] = $success; 
    			$message = "Faculty Schedule inserted successfully!";	
    			$_SESSION['message'] =  $message;	//failed to insert
    			
				// header("Location: ../deans/faculty_schedule_individual.php?faculty_name=$faculty_name");
   } 
   else{
    		  $_SESSION['alert'] = $error; 
    $message = "Something went wrong adding Schedule to faculty!";	
    $_SESSION['message'] =  $message;	//failed to insert
    echo mysqli_error($conn);
	// header("Location: ../deans/faculty_schedule_individual.php?faculty_name=$faculty_name");
    	}
}






}//end of else



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

	