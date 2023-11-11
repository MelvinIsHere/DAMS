<?php 
session_start();
include "functions/faculty_schedule_functions.php";
include "config.php";



$time_start = $_POST['time_start'];
$time_end = $_POST['time_end'];
$day = $_POST['day'];
$faculty_name = $_GET['faculty_name'];
$dept_id = $_POST['department_id']; 


	 $time_end = date("h:i A", strtotime($time_end));
	 $time_start = date("h:i A", strtotime($time_start));

//to be inserted ids
$faculty_id_to_be_inserted = ''; 
$time_start_id_to_be_inserted = '';
$time_end_id_to_be_inserted = '';
$semester_id_to_be_inserted = '';
$academic_year_id_to_be_inserted = '';



$success = "success";
$error = "error";




$faculty_id = get_faculty_id_by_name($faculty_name);
if($faculty_id != "error"){
	$faculty_id_to_be_inserted = $faculty_id;
	$time_start_id = time_id_start($time_start);
	if($time_start_id != "error"){	
		$time_start_id_to_be_inserted = $time_start_id;
		$time_end_id = time_id_end($time_end);
		if($time_end_id != "error"){
			$time_end_id_to_be_inserted = $time_end_id;
			$semester_id = get_sem_active_id();
			if($semester_id  != "error"){
				$semester_id_to_be_inserted = $semester_id; 
				$acad_id =  get_active_id_acad_year();
				if($acad_id != "error"){
					$academic_year_id_to_be_inserted = $acad_id;
				}
				else{	
					echo "error";
				}
			}
			else{
				echo "error";
			}
		}
		else{
			echo "errors";
		}
	}
	else{
		echo "error";
	}
}
else{
	echo "error";
}








$conflictQuery  = "
SELECT 
	f.faculty_id,
	f.firstname,
	f.middlename,
	f.lastname,
	f.suffix,
	d.department_name,
	
	
	ay.acad_year,
	sm.sem_description,

	t.time_s,
	
	t2.time_e,
	
	fs.day
	
FROM faculty_schedule fs
LEFT JOIN faculties f ON f.faculty_id = fs.faculty_id
LEFT JOIN departments d ON d.department_id = fs.department_id

LEFT JOIN semesters sm ON sm.semester_id = fs.semester_id
LEFT JOIN academic_year ay ON ay.acad_year_id = fs.acad_year_id

LEFT JOIN `time` t ON t.time_id = fs.time_start_id
LEFT JOIN `time` t2 ON t2.time_id = fs.time_end_id
WHERE fs.day = '$day' AND d.department_id = '$dept_id'



";


$conflictResult = mysqli_query($conn, $conflictQuery);

$conflictDetected = false;




while ($row = mysqli_fetch_array($conflictResult)) {
    $existing_start_time = $row['time_s'];
    $existing_end_time = $row['time_e'];
    
    $day_db = $row['day'];
    
    $new_start_timestamp = strtotime($time_start);
    $new_end_timestamp = strtotime($time_end);
    $existing_start_timestamp = strtotime($existing_start_time);
    $existing_end_timestamp = strtotime($existing_end_time);

    $faculty_id_db = $row['faculty_id'];

    if (
        ($new_start_timestamp >= $existing_start_timestamp && $new_start_timestamp < $existing_end_timestamp) ||
        ($new_end_timestamp > $existing_start_timestamp && $new_end_timestamp <= $existing_end_timestamp) && 
        $day_db == $day && $faculty_id_db = $faculty_id
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
    
	header("Location: ../deans/administrative_hours.php?faculty_name=$faculty_name");
	
} else {

//continue tommorow
	




	$administrative_hours_conflict_sql = "
	SELECT 
	f.faculty_id,
	f.firstname,
	f.middlename,
	f.lastname,
	f.suffix,
	d.department_name,
	
	ay.acad_year,
	sm.sem_description,
	

	
	fs.day,
	t3.`time_s`,
	t3.`time_e`
	
	
FROM faculty_schedule fs
LEFT JOIN faculties f ON f.faculty_id = fs.faculty_id
LEFT JOIN departments d ON d.department_id = fs.department_id

LEFT JOIN semesters sm ON sm.semester_id = fs.semester_id
LEFT JOIN academic_year ay ON ay.acad_year_id = fs.acad_year_id


LEFT JOIN administrative_hours ah ON ah.`faculty_id`= fs.`faculty_id`
LEFT JOIN `time` t3 ON t3.time_id = ah.time_start_id
WHERE fs.faculty_id = '$faculty_id' AND d.department_id = '$dept_id';
";
   

$administrative_hours_conflict_sql_result = mysqli_query($conn, $administrative_hours_conflict_sql);


while ($row = mysqli_fetch_array($administrative_hours_conflict_sql_result)) {
    $existing_start_time = $row['time_s'];
    $existing_end_time = $row['time_e'];
    
    $day_db = $row['day'];
    
    $new_start_timestamp = strtotime($time_start);
    $new_end_timestamp = strtotime($time_end);
    $existing_start_timestamp = strtotime($existing_start_time);
    $existing_end_timestamp = strtotime($existing_end_time);

    $faculty_id_db = $row['faculty_id'];

    if (
        ($new_start_timestamp >= $existing_start_timestamp && $new_start_timestamp < $existing_end_timestamp) ||
        ($new_end_timestamp > $existing_start_timestamp && $new_end_timestamp <= $existing_end_timestamp) && 
        $day_db == $day && $faculty_id_db = $faculty_id
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
    
	header("Location: ../deans/administrative_hours.php?faculty_name=$faculty_name");
	
	
	}
	else{
		
		
		$insert_administrative_hours_sql = "INSERT INTO administrative_hours(time_start_id,time_end_id,faculty_id,semester_id,academic_id,day) VALUES('$time_start_id_to_be_inserted','$time_end_id_to_be_inserted','$faculty_id_to_be_inserted','$semester_id_to_be_inserted','$academic_year_id_to_be_inserted','$day')";

		$result = mysqli_query($conn,$insert_administrative_hours_sql);
		if($result){
			
		 	$_SESSION['alert'] = $success; 
		    $message = "No conflict";
		    $_SESSION['message'] =  $message;	//schedule conflict
		    
			header("Location: ../deans/administrative_hours.php?faculty_name=$faculty_name");
		}
		else{
			$_SESSION['alert'] = $error; 
		    $message = "Something went wrong! Insert Failed!";
		    $_SESSION['message'] =  $message;	//schedule conflict
		    
			header("Location: ../deans/administrative_hours.php?faculty_name=$faculty_name");
	}
}



}



?>