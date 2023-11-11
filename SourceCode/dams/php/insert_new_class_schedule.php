<?php 
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
$faculty_sched_id_to_be_inserted = '';
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

//get room id 
if($room_id != "error"){
	//if there is a room id
	$room_id_to_be_inserted = $room_id;
}else{
	//if not
	$_SESSION['alert'] = $error; 
	$message = "Something went wrong! Load failed!";	
	$_SESSION['message'] =  $message;	//failed to insert
	header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
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
	header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
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
	header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
}



	$course_id = get_course_id_by_course_code($course_code);
	if($course_id != "error"){
		$course_id_to_be_inserted = $course_id;
		$section_id = get_section_id_by_section_name($section_name,$department_id);
		if($section_id != "error"){
			$section_id_to_be_inserted = $section_id;
			$faculty_sched_id = get_class_sched_id($section_id,$department_id,$course_id);
			if($faculty_sched_id != "error"){
				$faculty_sched_id_to_be_inserted =$faculty_sched_id;
			}else{
				$_SESSION['alert'] = $error; 
				$message = "Something went wrong! Load failed!";	
				$_SESSION['message'] =  $message;	//failed to insert
				header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
			}
		}else{
			$_SESSION['alert'] = $error; 
			$message = "Something went wrong! Load failed!";	
			$_SESSION['message'] =  $message;	//failed to insert
			header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
		}
	}else{
		$_SESSION['alert'] = $error; 
		$message = "Something went wrong! Load failed!";	
		$_SESSION['message'] =  $message;	//failed to insert
		header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
	}



$time_start_dt = DateTime::createFromFormat('H:i s', $time_start);
$time_end_dt = DateTime::createFromFormat('H:i s', $time_end);
$conflictQuery  = "           
            
SELECT 
                                cs.class_sched_id,
                                fl.faculty_id,
                                cs.day,
                                fc.firstname,
                                fc.lastname,
                                fc.middlename,
                                fc.suffix,
                                c.course_code,
                                pr.`program_abbrv`,
                                sc.section_name,
                                sc.`no_of_students`,
                                r.room_name,
                                t1.time_s,
                                t2.time_e,
                                dp.department_name,
                                s.`sem_description`,
                                ay.`acad_year`
                                FROM class_schedule cs
                                LEFT JOIN faculty_loadings fl ON fl.`fac_load_id` = cs.`faculty_schedule_id`
                              LEFT JOIN faculties fc ON fl.`faculty_id`=fc.`faculty_id`
                                    LEFT JOIN courses c ON fl.`course_id`=c.`course_id`
                                    LEFT JOIN sections sc ON fl.`section_id`=sc.`section_id`
                                    LEFT JOIN programs pr ON sc.`program_id`=pr.`program_id`
                                    LEFT JOIN departments dp ON dp.`department_id`=fl.`dept_id`
                                    LEFT JOIN semesters s ON s.semester_id = fl.sem_id
                                    LEFT JOIN academic_year ay ON ay.acad_year_id = fl.acad_year_id
                                    LEFT JOIN rooms r ON r.`room_id` = cs.room_id
                                    LEFT JOIN `time` t1 ON t1.`time_id` = cs.time_start_id
                                    LEFT JOIN `time` t2 ON t2.`time_id` = cs.time_end_id
                                    WHERE pr.`department_id` = 8  
                                    AND s.status = 'ACTIVE'
                                    AND ay.status = 'ACTIVE'
                                    AND (fc.`faculty_id` IS NULL OR r.room_name = '$room_name')
                                    AND cs.day = '$day'
                                    GROUP BY fl.`fac_load_id`

";


$conflictResult = mysqli_query($conn, $conflictQuery);

$conflictDetected = false;




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
    (
        ($new_start_timestamp >= $existing_start_timestamp && $new_start_timestamp < $existing_end_timestamp) ||
        ($new_end_timestamp > $existing_start_timestamp && $new_end_timestamp <= $existing_end_timestamp)
    )
    
) {
    $conflictDetected = true;
    break;
} else {
    echo "No overlap.";
}

}

if ($conflictDetected) {
    $_SESSION['alert'] = $error; 
    $message = "Something went wrong! Schedule conflict detected!";
    $_SESSION['message'] =  $message;	//schedule conflict
    echo $message;
    
	header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
	
}

 else {
   
 	  $insertFacultyScheduleSQL = mysqli_query($conn,"INSERT INTO class_schedule(faculty_schedule_id,day,room_id,time_start_id,time_end_id) VALUES('$faculty_sched_id_to_be_inserted','$day','$room_id_to_be_inserted','$time_start_id_to_be_inserted','$time_end_id_to_be_inserted')");    
 if($insertFacultyScheduleSQL){    		
 				  $_SESSION['alert'] = $success; 
    			$message = "Faculty Schedule inserted successfully!";	
    			$_SESSION['message'] =  $message;	//failed to insert
    			echo $message;
    			$ai =insertSchedTofacSched($dept_id_to_be_inserted,$section_name);
    			
    			
				header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
   } 
   else{
   	// $_SESSION['alert'] = $error; 
    // $message = "Something went wrong adding Schedule to facultyss!";	
    
    $error=  mysqli_error($conn);
    $message = " $department_ids";	
     $_SESSION['message'] =  $message;	//failed to insert

    // echo "<script>console.log($error)</script>";
   
	// header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
    	}
}


function insertSchedTofacSched($dept_id_to_be_inserted,$section_name){
	include "config.php";
	 $faculty_ids = []; // Initialize an empty array to store faculty IDs
	$sql = " SELECT 
                                cs.class_sched_id,
                                fl.faculty_id,
                                cs.day,                               
                                c.course_id,
                                
                                sc.section_id,
                            
                                r.room_id,
                                t1.time_id AS 'time_start_id',
                                t2.time_id AS 'time_end_id',
                                dp.department_id,
                                s.semester_id,
                                ay.acad_year_id
                                FROM class_schedule cs
                                LEFT JOIN faculty_loadings fl ON fl.`fac_load_id` = cs.`faculty_schedule_id`
                              LEFT JOIN faculties fc ON fl.`faculty_id`=fc.`faculty_id`
                                    LEFT JOIN courses c ON fl.`course_id`=c.`course_id`
                                    LEFT JOIN sections sc ON fl.`section_id`=sc.`section_id`
                                    LEFT JOIN programs pr ON sc.`program_id`=pr.`program_id`
                                    LEFT JOIN departments dp ON dp.`department_id`=fl.`dept_id`
                                    LEFT JOIN semesters s ON s.semester_id = fl.sem_id
                                    LEFT JOIN academic_year ay ON ay.acad_year_id = fl.acad_year_id
                                    LEFT JOIN rooms r ON r.`room_id` = cs.room_id
                                    LEFT JOIN `time` t1 ON t1.`time_id` = cs.time_start_id
                                    LEFT JOIN `time` t2 ON t2.`time_id` = cs.time_end_id
                                    WHERE pr.`department_id` = '$dept_id_to_be_inserted'  
                                    AND s.status = 'ACTIVE'
                                    AND ay.status = 'ACTIVE'
                                    
                                    GROUP BY cs.class_sched_id";
           $result = mysqli_query($conn,$sql);
           while($row = mysqli_fetch_array($result)){
           	$class_sched_id = $row['class_sched_id'];
           		$fac_id = $row['faculty_id'];
           		$faculty_ids[] = $fac_id; // Add faculty ID to the array

           		$faculty_id = $row['faculty_id'];
           		$day = $row['day'];
           		$course_id = $row['course_id'];
           		$section_id = $row['section_id'];
           		$room_id = $row['room_id'];
           		$time_start_id = $row['time_start_id'];
           		$time_end_id = $row['time_end_id'];
           		$department_id = $row['department_id'];
           		$semester_id = $row['semester_id'];
           		$acad_year_id = $row['acad_year_id'];

           		$verify = verifyLoad($faculty_id,$department_id,$semester_id,$acad_year_id,$course_id,$section_id,$room_id,$time_start_id,$time_end_id,$day);
           		if($verify){
           			$insert = insertFacultySched($faculty_id,$department_id,$semester_id,$acad_year_id,$course_id,$section_id,$room_id,$time_start_id,$time_end_id,$day,$class_sched_id);
           			echo "inserted to fac loading";
           		}else{
           			echo "already there";
           		}
           		
           		
           }
           return $faculty_ids;
}



function insertFacultySched($faculty_id,$department_id,$semester_id,$acad_year_id,$course_id,$section_id,$room_id,$time_start_id,$time_end_id,$day,$class_sched_id){
	include "config.php";

	$sql = "INSERT INTO faculty_schedule (faculty_id,department_id,semester_id,acad_year_id,course_id,section_id,room_id,time_start_id,time_end_id,day,description,class_sched_id) VALUES('$faculty_id','$department_id','$semester_id','$acad_year_id','$course_id','$section_id','$room_id','$time_start_id','$time_end_id','$day','Class Schedule','$class_sched_id')";
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
 


?>