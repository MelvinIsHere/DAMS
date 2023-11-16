<?php
//here start the intialization of the variables 
session_start();
include "class_schedule_functions.php";
include "config.php";
$department_name = $_SESSION['dept_name'];
$course_code = $_POST['course_code'];
$section_name = $_POST['section_name'];
$room_name = $_POST['room_name'];
$time_start = $_POST['time_start'];
$time_end = $_POST['time_end'];
$day = $_POST['days'];	
$department_id = $_POST['department_id'];
$error = "error";
$success = "success";
$faculty_loading_id_to_be_inserted = '';
$course_id_to_be_inserted = '';
$dept_id_to_be_inserted = '';
$section_id_to_be_inserted = '';
$room_id_to_be_inserted = '';
$time_start_id_to_be_inserted = '';
$time_end_id_to_be_inserted = '';
$time_end = date("h:i A", strtotime($time_end));
$time_start = date("h:i A", strtotime($time_start));
$amPm_start = substr($time_start, -2);
$amPm_end = substr($time_end, -2);	
$room_id = get_room_id_by_room_name($room_name);
$task_id = get_task_id_by_active_term();
$task_id_fac_sched = get_task_id_by_active_term_for_faculty_schedule();







//here, i am getting the ids necessary for inserts
$faculty_id = getfacultyIdfromloading($conn,$section_name,$course_code,$department_id);
if($faculty_id){
    echo $faculty_id;
}else{
    echo "errors";
}

$semester_id =  getactivesem();
if(!$semester_id){
    header("Location: ../deans/class_schedule_individual.php?section_name=$section_name"."success");
}else{

}

$acad_year_id = getActiveAcadyear();
if(!$acad_year_id){
    header("Location: ../deans/class_schedule_individual.php?section_name=$section_name"."successacad");
}





//get room id 
if($room_id != "error"){
	//if there is a room id
	$room_id_to_be_inserted = $room_id;
}else{
	//if not
	$_SESSION['alert'] = $error; 
	$message = "Something went wrong! Load failed!";	
	$_SESSION['message'] =  $message;	//failed to insert
	// header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
}
//get the time start id
$time_start_id = time_id_start($time_start);
if($time_start_id != "error"){
	//if there is a time start id
	$time_start_id_to_be_inserted =  $time_start_id;
}else{
	//if not
	$_SESSION['alert'] = $error; 
	 $message = "Something went wrong! Load failed!";	
	$_SESSION['message'] =  $message;	//failed to insert
	// header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
}
//get the time end id
$time_end_id = time_id_end($time_end);
if($time_end_id != "error"){
	//if there is a time end id
	$time_end_id_to_be_inserted = $time_end_id;
}else{
	//if not
	$_SESSION['alert'] = $error; 
	 $message = "Something went wrong! Load failed!";	
	$_SESSION['message'] =  $message;	//failed to insert
	// header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
}



	$course_id = get_course_id_by_course_code($course_code);
	if($course_id != "error"){
		$course_id_to_be_inserted = $course_id;
		$section_id = get_section_id_by_section_name($section_name,$department_id);
		if($section_id != "error"){
			$section_id_to_be_inserted = $section_id;
			$faculty_loading_id = get_fac_load_id($section_id,$department_id,$course_id);
			if($faculty_loading_id != "error"){
				$faculty_loading_id_to_be_inserted =$faculty_loading_id;
			}else{
				$_SESSION['alert'] = $error; 
				$message = "Something went wrong! Load failed!";	
				$_SESSION['message'] =  $message;	//failed to insert
				// header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
			}
		}else{
			$_SESSION['alert'] = $error; 
			$message = "Something went wrong! Load failed!";	
			$_SESSION['message'] =  $message;	//failed to insert
			// header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
		}
	}else{
		$_SESSION['alert'] = $error; 
		$message = "Something went wrong! Load failed!";	
		$_SESSION['message'] =  $message;	//failed to insert
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
LEFT JOIN tasks tt ON tt.task_id = fs.task_id
WHERE fs.day = '$day' AND d.department_id = '$department_id'
AND f.faculty_id = '$faculty_id'
AND tt.task_id = '$task_id_fac_sched'";


$conflictResult = mysqli_query($conn, $conflictQuery);
//set the conflcit to false and just set it to true if there is a conflict later
$conflictDetected = false;



//verify it if there is conflict
while ($row = mysqli_fetch_array($conflictResult)) {
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
LEFT JOIN tasks tt ON tt.task_id = cs.task_id
WHERE r.`room_name` = '$room_name'
AND cs.day = '$day'
AND tt.task_id = '$task_id'";



$conflictResult = mysqli_query($conn, $conflictQuery);
//set the condlict to false

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
LEFT JOIN tasks tt ON tt.task_id = cs.task_id
WHERE fl.section_id = '$section_id'
AND cs.day = '$day'
AND tt.task_id = '$task_id'";



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













//if conlict return there is confluct
if ($conflictDetected) {
    $_SESSION['alert'] = $error; 
    $message = "Something went wrong! Schedule conflict detected!";
    $_SESSION['message'] =  $message;	//schedule conflict
    
    	// header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
}else {
   
 	$insertFacultyScheduleSQL = mysqli_query($conn,"INSERT INTO class_schedule(faculty_loading_id,day,room_id,time_start_id,time_end_id,task_id) VALUES('$faculty_loading_id_to_be_inserted','$day','$room_id_to_be_inserted','$time_start_id_to_be_inserted','$time_end_id_to_be_inserted','$task_id')"); 
    $class_sched_id = $conn->insert_id;   
    if($insertFacultyScheduleSQL){    		
        $_SESSION['alert'] = $success; 
    	$message = "Faculty Schedule inserted successfully!";	
    	$_SESSION['message'] =  $message;	//failed to insert
    	echo $message;
    	$ai = insertFacultySched($faculty_id,$department_id,$semester_id,$acad_year_id,$course_id,$section_id,$room_id,$time_start_id,$time_end_id,$day,$class_sched_id,$task_id_fac_sched);
        if($ai){
            // header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
        }else{
            echo "error";
        }
    			
    			
	} else{
   	$_SESSION['alert'] = $error; 
    $message = "Something went wrong adding Schedule to facultyss!";	
    
    $message = mysqli_error($conn);
    
     $_SESSION['message'] =  $message;	//failed to insert

    // echo "<script>console.log($error)</script>";
   
	// header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
    	}
}




function insertFacultySched($faculty_id,$department_id,$semester_id,$acad_year_id,$course_id,$section_id,$room_id,$time_start_id,$time_end_id,$day,$class_sched_id,$task_id_fac_sched){
	include "config.php";

	$sql = "INSERT INTO faculty_schedule (faculty_id,department_id,semester_id,acad_year_id,course_id,section_id,room_id,time_start_id,time_end_id,day,description,class_sched_id,task_id) VALUES('$faculty_id','$department_id','$semester_id','$acad_year_id','$course_id','$section_id','$room_id','$time_start_id','$time_end_id','$day','Class Schedule','$class_sched_id','$task_id_fac_sched')";
	$result = mysqli_query($conn,$sql);
	if($sql){
		return true;
	}else{
		return false;
	}
}
function verifyLoad($faculty_id,$department_id,$semester_id,$acad_year_id,$course_id,$section_id,$room_id,$time_start_id,$time_end_id,$day){
	include "config.php";
	$sql = "SELECT * FROM faculty_schedule 
        WHERE faculty_id = '$faculty_id' 
        AND department_id = '$department_id'
        AND semester_id = '$semester_id'
        AND acad_year_id = '$acad_year_id' 
        AND course_id = '$course_id'
        AND section_id = '$section_id'
        AND room_id = '$room_id'
        AND time_start_id = '$time_start_id'
        AND time_end_id = '$time_end_id'
        AND day = '$day'";
	$result = mysqli_query($conn,$sql);
	if($result){
			if(mysqli_num_rows($result) > 0){
				return false;
			}else{
				return true;
			}
	}else{
		return false;
	}
	
}
function getfacultyIdfromloading($conn,$section_name,$course_code,$department_id){
    $query = " SELECT DISTINCT
                   fc.faculty_id                                    
                FROM
                faculty_loadings fl
                LEFT JOIN faculties fc ON fl.`faculty_id`=fc.`faculty_id`
                LEFT JOIN courses cs ON fl.`course_id`=cs.`course_id`
                LEFT JOIN sections sc ON fl.`section_id`=sc.`section_id`
                LEFT JOIN programs pr ON sc.`program_id`=pr.`program_id`
                LEFT JOIN departments dp ON dp.`department_id`=fl.`dept_id`
                LEFT JOIN semesters s ON s.semester_id = fl.sem_id
                LEFT JOIN academic_year ay ON ay.acad_year_id = fl.acad_year_id
                 
                WHERE s.status = 'ACTIVE'
                AND ay.status = 'ACTIVE'                                     
                AND CONCAT('BS',pr.program_abbrv,' ',sc.section_name) = '$section_name'
                AND cs.course_code = '$course_code'
                AND fl.dept_id = '$department_id'";

    $result = mysqli_query($conn,$query);
    if($result){
        if(mysqli_num_rows($result) >0){
            $row = mysqli_fetch_assoc($result);
            $faculty_id = $row['faculty_id'];
            return $faculty_id;
        }else{
            return false;
        }
    }else{
        return false;
    }
} 

function getactivesem(){
    include "config.php";
    $query = mysqli_query($conn,"SELECT semester_id FROM semesters WHERE status = 'ACTIVE'");
    if($query){
        if(mysqli_num_rows($query) > 0){
            $row = mysqli_fetch_assoc($query);
            $semester_id = $row['semester_id'];
            return $semester_id;
        }else{
            return false;
        }
    }else{
        return false;
    }
}
function getActiveAcadyear(){
    include "config.php";
    $query = mysqli_query($conn,"SELECT acad_year_id FROM academic_year WHERE status = 'ACTIVE'");
    if($query){
        if(mysqli_num_rows($query) > 0){
            $row = mysqli_fetch_assoc($query);
            $acad_year_id = $row['acad_year_id'];
            return $acad_year_id;
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



function get_task_id_by_active_term_for_faculty_schedule(){
    include 'config.php';
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