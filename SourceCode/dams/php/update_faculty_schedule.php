<?php 
include "functions/faculty_schedule_functions.php";
include "config.php";
session_start();
$faculty_sched_id = $_POST['loading_id']; // too lazy to change the variable
$class_hours_start = $_POST['class_hours_start'];
$class_hours_end = $_POST['class_hours_end'];
$day = $_POST['day'];
$room = $_POST['room'];
$department_id = $_POST['department_id'];
$class_hours_start = date("h:i A", strtotime($class_hours_start));
$class_hours_end = date("h:i A", strtotime($class_hours_end));
$faculty_name = $_GET['faculty_name'];

$error = "error";
$success = "success";
//assignment variables
$class_hours_start_id_to_be_inserted = '';
$class_hours_end_id_to_be_inserted = '';
$room_id_to_be_inserted = '';


//fetching id in the database using functions :)

$class_hours_start_time_id = time_id_start($class_hours_start);
if($class_hours_start_time_id != "error"){
	$class_hours_start_id_to_be_inserted = $class_hours_start_time_id;
	$class_hours_end_time_id = time_id_end($class_hours_end);
	if($class_hours_end_time_id != "error"){
		$class_hours_end_id_to_be_inserted = $class_hours_end_time_id;
		$room_id = get_room_id_by_room_name($room);
		$room_id_to_be_inserted = $room_id;
		if($room_id != "error"){
			$room_id_to_be_inserted = $room_id;
		}
		else{
			$_SESSION['alert'] = $error;
			$message = "Something went wrong! There are no room such as $room !";
			$_SESSION['message'] = $message;
			header("Location: ../deans/faculty_schedule_individual.php?faculty_name=$faculty_name");
		}
	}
	else{
		$_SESSION['alert'] = $error;
		$message = "Something went wrong! You cant end with that time!";
		$_SESSION['message'] = $message;
		header("Location: ../deans/faculty_schedule_individual.php?faculty_name=$faculty_name");
	}
}
else{
	$_SESSION['alert'] = $error;
	$message = "Something went wrong! You cant start with that time!";
	$_SESSION['message'] = $message;
	header("Location: ../deans/faculty_schedule_individual.php?faculty_name=$faculty_name");
}


$conflictQuery = "SELECT 
	f.firstname,
	f.middlename,
	f.lastname,
	f.suffix,
	d.department_name,
	c.course_code,
	r.room_name,
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
LEFT JOIN rooms r ON r.room_id = fs.room_id
LEFT JOIN semesters sm ON sm.semester_id = fs.semester_id
LEFT JOIN academic_year ay ON ay.acad_year_id = fs.acad_year_id
LEFT JOIN sections s ON s.section_id = fs.section_id
LEFT JOIN programs p ON p.program_id = s.program_id
LEFT JOIN `time` t ON t.time_id = fs.time_start_id
LEFT JOIN `time` t2 ON t2.time_id = fs.time_end_id
WHERE fs.day = '$day' AND d.department_id = '$department_id'
AND fs.faculty_sched_id != '$faculty_sched_id'

";

$conflictResult = mysqli_query($conn, $conflictQuery);

$conflictDetected = false;

while ($row = mysqli_fetch_array($conflictResult)) {
    $existing_start_time = $row['time_s'];
    $existing_end_time = $row['time_e'];
    
    $new_start_timestamp = strtotime($class_hours_start);
    $new_end_timestamp = strtotime($class_hours_end);
    $existing_start_timestamp = strtotime($existing_start_time);
    $existing_end_timestamp = strtotime($existing_end_time);

    if (
        ($new_start_timestamp >= $existing_start_timestamp && $new_start_timestamp < $existing_end_timestamp) ||
        ($new_end_timestamp > $existing_start_timestamp && $new_end_timestamp <= $existing_end_timestamp)
    ) {
            $conflictDetected = true;
        break;
    } else {
        echo "No overlap1.";
    }
}







// $conflictQuery2 = "SELECT 
//         cs.`class_sched_id`,
        
        
//         t.`time_s`,
  
//         t2.time_e
//     FROM class_schedule cs 
//     LEFT JOIN rooms r ON r.room_id = cs.room_id
//     LEFT JOIN `time` t ON t.time_id = cs.time_start_id
//     LEFT JOIN `time` t2 ON t2.time_id = cs.time_end_id
//     LEFT JOIN faculty_loadings fl ON fl.`fac_load_id` = cs.`faculty_schedule_id`
//     LEFT JOIN departments dp ON dp.`department_id` = fl.`dept_id`
//     WHERE cs.`day` = '$day' AND r.room_id = '$room_id_to_be_inserted'
//     AND fl.`dept_id` = '$department_id'
    
	

// ";

// $conflictResult = mysqli_query($conn, $conflictQuery2);



// while ($row = mysqli_fetch_array($conflictResult)) {
//     $existing_start_time = $row['time_s'];
//     $existing_end_time = $row['time_e'];
    
//     $new_start_timestamp = strtotime($class_hours_start);
//     $new_end_timestamp = strtotime($class_hours_end);
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
    $message =  "Schedule conflict detected!";
    $_SESSION['message'] = $message;
    header("Location: ../deans/faculty_schedule_individual.php?faculty_name=$faculty_name");
} else {
   		
   		
   			$update_class_sched = updateClassSched($class_hours_start_id_to_be_inserted,$class_hours_end_id_to_be_inserted,$room_id_to_be_inserted,$day,$faculty_sched_id);
   			echo $update_class_sched;
   		
   				$update_faculty_schedule_sql = "UPDATE faculty_schedule SET time_start_id = '$class_hours_start_id_to_be_inserted', time_end_id = '$class_hours_end_id_to_be_inserted', room_id = '$room_id_to_be_inserted',day ='$day' WHERE faculty_sched_id = '$faculty_sched_id'";
 		$result = mysqli_query($conn,$update_faculty_schedule_sql);
   		
 	  	
 		
 		if($result){
 			$_SESSION['alert'] = $success;
 			$message = "Faculty Schedule has been successfully updated!";
 			$_SESSION['message'] = $message;
 			header("Location: ../deans/faculty_schedule_individual.php?faculty_name=$faculty_name");
 			
 		}
 		else{
 			$_SESSION['alert'] = $error;
    		$message =  "Something went wrong! update failed!";
    		$_SESSION['message'] = $message;
    		header("Location: ../deans/faculty_schedule_individual.php?faculty_name=$faculty_name");
    		echo mysqli_error($conn);
 		}
  
}









function updateClassSched($class_hours_start_id_to_be_inserted,$class_hours_end_id_to_be_inserted,$room_id_to_be_inserted,$day,$faculty_sched_id){
	include "config.php";
	$class_sched_id = selectClassSchedId($faculty_sched_id);
	if($class_sched_id != "error"){
		$update_class_sched_query = "UPDATE class_schedule SET day = '$day',room_id = '$room_id_to_be_inserted',time_start_id = '$class_hours_start_id_to_be_inserted',time_end_id = '$class_hours_end_id_to_be_inserted' WHERE class_sched_id = '$class_sched_id'";
		$result = mysqli_query($conn,$update_class_sched_query);
		if($result){
			return true;
		}
	}else{
		echo "Error: " . mysqli_error($conn);
    return false;
	}	


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
