<?php
session_start();
include "config.php";
include "class_schedule_functions.php";

$department_id = $_POST['department_id'];
$section_name = $_GET['section_name'];

$course_code = $_POST['course_code'];
// $students = $_POST['students'];
$day = $_POST['day'];
$room_name = $_POST['room'];
$class_hours_start = $_POST['class_hours_start'];
$class_hours_end = $_POST['class_hours_end'];
$class_sched_id = $_POST['loading_id'];

$faculty_sched_id = '';
 $time_end = date("h:i A", strtotime($class_hours_end));
 $time_start = date("h:i A", strtotime($class_hours_start));


    $error = "error";
    $success = "success";
    

 	
	$course_id_to_be_inserted = '';
	$dept_id_to_be_inserted = '';
	$section_id_to_be_inserted = '';
	$room_id_to_be_inserted = 0;
	$semester_id_to_be_inserted = '';
	$acad_id_to_be_inserted = '';
	$time_start_id_to_be_inserted = '';
	$time_end_id_to_be_inserted = '';


$section_id = getsectionid($section_name);
if($section_id){
	echo $section_id;
}
$faculty_id = getfacultyid($class_sched_id);
if($faculty_id){
	echo $faculty_id;
}else{
	echo "error";
}
		
			
			$course_id = get_course_id_by_course_code($course_code);
			if($course_id != "error"){
				$course_id_to_be_inserted =  $course_id;
				$room_id = getroomid($conn,$room_name);
				
				if($room_id){
					$room_id_to_be_inserted =  1;
					
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
                              // header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
						}

					}
					else{
						$message = "Something went wrong, Update failed!";
                              $_SESSION['alert'] = $error; 
                              $_SESSION['message'] =  $message;   //failed to insert
                              // header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
					}
				}
				else{
					$message = "Something went wrong, Update failed!";
                              $_SESSION['alert'] = $error; 
                              $_SESSION['message'] =  $message;   //failed to insert
                              // header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
				}
			}
			else{
				$message = "Something went wrong, Update failed!";
                              $_SESSION['alert'] = $error; 
                              $_SESSION['message'] =  $message;   //failed to insert
                              // header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
			}
	



//this is very important to verify the timewcg
$time_start_dt = DateTime::createFromFormat('H:i s', $time_start);
$time_end_dt = DateTime::createFromFormat('H:i s', $time_end);

//query if there is conflict in the faculty sched
$conflictQuery  = "
SELECT 
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
AND f.faculty_id = '$faculty_id'";


$conflictResult = mysqli_query($conn, $conflictQuery);
//set the conflcit to false and just set it to true if there is a conflict later
$conflictDetected = false;
if(mysqli_num_rows($conflictResult) >0){
echo "meron";
}else{
	echo "wala";
}
//verify it if there is conflict
while ($row = mysqli_fetch_array($conflictResult)) {
	echo "aaa";
    $existing_start_time = $row['time_s'];
    $existing_end_time = $row['time_e'];
    $room_db = $row['room_name'];
    $day_db = $row['day'];
    
    $new_start_timestamp = strtotime($time_start);
    $new_end_timestamp = strtotime($time_end);
    $existing_start_timestamp = strtotime($existing_start_time);
    $existing_end_timestamp = strtotime($existing_end_time);

    if (
        ($new_start_timestamp >= $existing_start_timestamp && $new_start_timestamp < $existing_end_timestamp) ||
        ($new_end_timestamp > $existing_start_timestamp && $new_end_timestamp <= $existing_end_timestamp) && 
        $day_db == $day
    ) {
            $conflictDetected = true;//set conflict to true because there is a conflict
        break;
    } else {
        echo "No overlap.";
    }
}






//verify the schedule for  class sched by room name 
$conflictQuery = "
SELECT 
    cs.day,
    r.`room_name`,
    t1.`time_s`,
    t2.`time_e`
    
    
FROM class_schedule cs
LEFT JOIN rooms r ON r.`room_id` = cs.`room_id`
LEFT JOIN `time` t1 ON t1.`time_id` = cs.`time_start_id`
LEFT JOIN `time` t2 ON t2.`time_id` = cs.`time_end_id`
WHERE r.`room_name` = '$room_name'
AND cs.day = '$day'";



$conflictResult = mysqli_query($conn, $conflictQuery);
//set the condlict to false
if(mysqli_num_rows($conflictResult) >0){
	echo "meron";
}
//compare the schedule for class schedule
while ($row = mysqli_fetch_assoc($conflictResult)) {

    $existing_start_time = $row['time_s'];
    $existing_end_time = $row['time_e'];
    $room_db = $row['room_name'];
    $day_db = $row['day'];    
    $new_start_timestamp = strtotime($time_start);
    $new_end_timestamp = strtotime($time_end);
    $existing_start_timestamp = strtotime($existing_start_time);
    $existing_end_timestamp = strtotime($existing_end_time);
    if (
    (
        ($new_start_timestamp >= $existing_start_timestamp && $new_start_timestamp < $existing_end_timestamp) ||
        ($new_end_timestamp > $existing_start_timestamp && $new_end_timestamp <= $existing_end_timestamp)
    )
    
) {
    //if conflict detected set conflict to true
    $conflictDetected = true;

    break;
} else {
    // do nothing
    echo "No overlap.";
}

}



//verify sched by section on fac sched if there is a conflict
$conflictQuery = "

SELECT
    s.`section_name`,
    t1.`time_s`,
    t2.`time_e`
FROM `class_schedule` cs
LEFT JOIN faculty_loadings fl ON fl.`fac_load_id` = cs.`faculty_loading_id`
LEFT JOIN sections s ON s.section_id = fl.`section_id`
LEFT JOIN `time` t1 ON t1.`time_id` = cs.`time_start_id`
LEFT JOIN `time` t2 ON t2.`time_id` = cs.`time_end_id`
LEFT JOIN programs pr ON pr.`program_id` = s.`program_id`
WHERE fl.section_id = '$section_id'
AND cs.day = '$day'";



$conflictResult3 = mysqli_query($conn, $conflictQuery);
if($conflictResult3){
    echo "good";
    echo "$section_name";
}
//set the condlict to false

//compare the schedule for class schedule
while ($row = mysqli_fetch_assoc($conflictResult3)){
    echo "verify";
    $existing_start_time = $row['time_s'];
    $existing_end_time = $row['time_e'];
      
    $new_start_timestamp = strtotime($time_start);
    $new_end_timestamp = strtotime($time_end);
    $existing_start_timestamp = strtotime($existing_start_time);
    $existing_end_timestamp = strtotime($existing_end_time);
    if (
    (
        ($new_start_timestamp >= $existing_start_timestamp && $new_start_timestamp < $existing_end_timestamp) ||
        ($new_end_timestamp > $existing_start_timestamp && $new_end_timestamp <= $existing_end_timestamp)
    )
    
) {
    //if conflict detected set conflict to true
    $conflictDetected = true;

    break;
} else {
    // do nothing
    echo "No overlap.";
}

}










if($conflictDetected) {
    echo "conflictDetected";
    $message = "Conflict Detected, Update failed!";
                              $_SESSION['alert'] = $error; 
                              $_SESSION['message'] =  $message;   //failed to insert
                              // header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
} else {
    
    $update = mysqli_query($conn,"UPDATE class_schedule SET   time_start_id = '$time_start_id_to_be_inserted', time_end_id = '$time_end_id_to_be_inserted',day = '$day',room_id = '$room_id_to_be_inserted' WHERE class_sched_id = '$class_sched_id'");

    if($update){

    	$update_faculty_schedule =  updateFacultySchedule($class_sched_id,$time_start_id_to_be_inserted,$time_end_id_to_be_inserted,$day,$room_id_to_be_inserted);
    	if($update_faculty_schedule){
    			$message = "Schedule has been successfully updated!";
                              $_SESSION['alert'] = $success; 
                              $_SESSION['message'] =  $message;   //failed to insert
                              echo $_SESSION['message'];
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
                              $_SESSION['message'] =  mysqli_error($conn);   //failed to insert
                              header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
    }
}







function getfacultyid($class_sched_id){
	include "config.php";
	$query = mysqli_query($conn,"SELECT faculty_id FROM faculty_schedule WHERE class_sched_id = '$class_sched_id'");
	if($query){
		if(mysqli_num_rows($query) > 0){
			$row = mysqli_fetch_assoc($query);
			$faculty_id = $row['faculty_id'];
			return $faculty_id;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

function getsectionid($section_name){
	include 'config.php';
	$query = mysqli_query($conn,"SELECT 
										sc.section_id
								FROM class_schedule cs
								LEFT JOIN faculty_loadings fl ON fl.`fac_load_id` = cs.`faculty_loading_id`
								LEFT JOIN sections sc ON sc.`section_id` = fl.`section_id`
								LEFT JOIN programs pr ON pr.`program_id` = sc.`program_id`  
								WHERE CONCAT('BS',pr.program_abbrv,' ',sc.section_name) = '$section_name'");
	if($query){
		if(mysqli_num_rows($query) > 0){
			$row = mysqli_fetch_assoc($query);
			$section_id = $row['section_id'];
			return $section_id;
		}else{
			return false;
		}
	}else{
		return false;
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

function getroomid($conn,$room_name){
	$query = mysqli_query($conn,"SELECT room_id FROM rooms WHERE room_name = '$room_name'");
	if($query){
		if(mysqli_num_rows($query) > 0){
			$row = mysqli_fetch_assoc($query);
			$room_id = $row['room_id'];
			return $room_id;
		}else{
			return false;
		}
	}else{
		return false;
	}
	

}
function getfaculty_sched_id($conn,$class_sched_id){
	$query = mysqli_query($conn,"SELECT faculty_schedule_id FROM class_schedule WHERE class_sched_id = '$class_sched_id'");
	if($query){
		if(mysqli_num_rows($query) > 0){
			$row = mysqli_fetch_assoc($query);
			$faculty_sched_id = $row['faculty_sched_id'];
			return $faculty_sched_id;
		}else{	
			return false;
		}
	}else{
		return false;
	}
}


 ?>