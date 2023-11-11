<?php
session_start();
include "config.php";
include "class_schedule_functions.php";

$department_name = $_SESSION['dept_name'];
$section_name = $_GET['section_name'];

$course_code = $_POST['course_code'];
// $students = $_POST['students'];
$day = $_POST['day'];
$room_name = $_POST['room'];
$class_hours_start = $_POST['class_hours_start'];
$class_hours_end = $_POST['class_hours_end'];
$class_sched_id = $_POST['loading_id'];


 $time_end = date("h:i A", strtotime($class_hours_end));
 $time_start = date("h:i A", strtotime($class_hours_start));


    $error = "error";
    $success = "success";
    

 	
	$course_id_to_be_inserted = '';
	$dept_id_to_be_inserted = '';
	$section_id_to_be_inserted = '';
	$room_id_to_be_inserted = '';
	$semester_id_to_be_inserted = '';
	$acad_id_to_be_inserted = '';
	$time_start_id_to_be_inserted = '';
	$time_end_id_to_be_inserted = '';



	$dept_id = get_dept_id_by_dept_name($department_name);
	if($dept_id != "error"){
		$dept_id_to_be_inserted = $dept_id;
		
			$course_id = get_course_id_by_course_code($course_code);
			if($course_id != "error"){
				$course_id_to_be_inserted =  $course_id;
				$room_id = get_room_id_by_room_name($room_name);
				if($room_id != "error"){
					$room_id_to_be_inserted =  $room_id;
					$time_start_id = time_id_start($time_start);
					if($time_start_id){
						$time_start_id_to_be_inserted =  $time_start_id;
						$time_end_id = time_id_end($time_end);
						if($time_end_id != "error"){
							$time_end_id_to_be_inserted = $time_end_id;
						}
						else{
							  $message = "Something went wrong, Update failed!";
                              $_SESSION['alert'] = $error; 
                              $_SESSION['message'] =  $message;   //failed to insert
                              header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
						}

					}
					else{
						$message = "Something went wrong, Update failed!";
                              $_SESSION['alert'] = $error; 
                              $_SESSION['message'] =  $message;   //failed to insert
                              header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
					}
				}
				else{
					$message = "Something went wrong, Update failed!";
                              $_SESSION['alert'] = $error; 
                              $_SESSION['message'] =  $message;   //failed to insert
                              header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
				}
			}
			else{
				$message = "Something went wrong, Update failed!";
                              $_SESSION['alert'] = $error; 
                              $_SESSION['message'] =  $message;   //failed to insert
                              header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
			}
	
	}	
	else{
		$message = "Something went wrong, Update failed!";
                              $_SESSION['alert'] = $error; 
                              $_SESSION['message'] =  $message;   //failed to insert
                              header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
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
WHERE fs.day = '$day' AND d.department_id = '$dept_id_to_be_inserted'


    
    AND fs.class_sched_id != '$class_sched_id'
	
	

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
        echo "No overlap.";
    }
}



//VERIFYING
// $conflict = false;
// $verify = mysqli_query($conn, "
//    SELECT 
//         cs.`class_sched_id`,
//         r.room_name,
        
//         t.`time_s` AS 'class start',
  
//         t2.time_e AS 'class end'
//     FROM class_schedule cs 
//     LEFT JOIN rooms r ON r.room_id = cs.room_id
//     LEFT JOIN `time` t ON t.time_id = cs.time_start_id
//     LEFT JOIN `time` t2 ON t2.time_id = cs.time_end_id
//     LEFT JOIN faculty_loadings fl ON fl.`fac_load_id` = cs.`faculty_schedule_id`
//     LEFT JOIN departments dp ON dp.`department_id` = fl.`dept_id`
//     WHERE cs.room_id = '$room_id_to_be_inserted' AND cs.`day` = '$day'
//     AND fl.`dept_id` = '$department_id'
// 	"
// );


if($conflictDetected) {
    echo "conflictDetected";
    $message = "Conflict Detected, Update failed!";
                              $_SESSION['alert'] = $error; 
                              $_SESSION['message'] =  $message;   //failed to insert
                              header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
} else {
    
    $update = mysqli_query($conn,"UPDATE class_schedule SET   time_start_id = '$time_start_id_to_be_inserted', time_end_id = '$time_end_id_to_be_inserted',day = '$day',room_id = '$room_id_to_be_inserted' WHERE class_sched_id = '$class_sched_id'");

    if($update){
    	$update_faculty_schedule =  updateFacultySchedule($class_sched_id,$time_start_id_to_be_inserted,$time_end_id_to_be_inserted,$day,$room_id_to_be_inserted);
    	if($update_faculty_schedule){
    			$message = "Schedule has been successfully updated!";
                              $_SESSION['alert'] = $success; 
                              $_SESSION['message'] =  $message;   //failed to insert
                              header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
    	}else{
    		$message = "Something went wrong, Update failed!";
                              $_SESSION['alert'] = $error; 
                              $_SESSION['message'] =  $message;   //failed to insert
                              header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
    	}
    	
    }
    else{
    	$message = "Something went wrong, Update failed!";
                              $_SESSION['alert'] = $error; 
                              $_SESSION['message'] =  $message;   //failed to insert
                              header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
    }
}















function updateFacultySchedule($class_sched_id,$time_start_id_to_be_inserted,$time_end_id_to_be_inserted,$day,$room_id_to_be_inserted){
	include "config.php";
	$sql = "UPDATE faculty_schedule SET room_id = '$room_id_to_be_inserted',time_start_id = '$time_start_id_to_be_inserted',time_end_id = '$time_end_id_to_be_inserted', day = '$day' WHERE class_sched_id = '$class_sched_id'";
	$result = mysqli_query($conn,$sql);
	if($result){
		return true;
	}else{
		return false;
	}

}





 ?>