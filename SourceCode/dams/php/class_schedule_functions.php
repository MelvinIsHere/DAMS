<?php 
function get_faculty_id_by_faculty_sched_id($faculty_sched_id){
	include "config.php";

	$sql = mysqli_query($conn,"SELECT faculty_id FROM faculty_schedule WHERE faculty_sched_id = '$faculty_sched_id'");
	if(mysqli_num_rows($sql) > 0){
		$row = mysqli_fetch_assoc($sql);
		$faculty_id = $row['faculty_id'];
		return $faculty_id;
	}else{
		return "error";
	}
}
function get_course_id_by_course_code($course_code){
	include "config.php";

	$sql = mysqli_query($conn,"SELECT course_id FROM courses WHERE course_code = '$course_code'");
	if(mysqli_num_rows($sql) > 0){
		$row = mysqli_fetch_assoc($sql);
		$course_id = $row['course_id'];
		return $course_id;
	}else{
		return "error";
	}
}
function get_dept_id_by_dept_name($department_name){
	include "config.php";

	$sql = mysqli_query($conn,"SELECT department_id FROM departments WHERE department_name = '$department_name'");
	if(mysqli_num_rows($sql) > 0){
		$row = mysqli_fetch_assoc($sql);
		$dept_id = $row['department_id'];
		return $dept_id;
	}else{
		return "error";
	}
}
function get_section_id_by_section_name($section,$dept_id){
	include "config.php";

	$sql = mysqli_query($conn,"
			SELECT 
			s.section_id, 
			s.section_name
			FROM sections s
			LEFT JOIN programs p ON p.program_id = s.program_id
			WHERE  p.department_id = '$dept_id' AND CONCAT('BS',p.program_abbrv, ' ',s.section_name) = '$section';
");
	if(mysqli_num_rows($sql) > 0){
		$row = mysqli_fetch_assoc($sql);
		$section_id = $row['section_id'];
		return $section_id;
	}else{
		return "error";
	}
}
function get_room_id_by_faculty_sched_id($faculty_sched_id){
	include "config.php";

	$sql = mysqli_query($conn,"SELECT room_id FROM faculty_schedule WHERE faculty_sched_id = '$faculty_sched_id'");
	if(mysqli_num_rows($sql) > 0){
		$row = mysqli_fetch_assoc($sql);
		$room_id = $row['room_id'];
		return $room_id;
	}else{
		return "error";
	}
}
function get_sem_active_id(){
	include "config.php";

	$sql = mysqli_query($conn,"SELECT semester_id FROM semesters WHERE status = 'ACTIVE'");
	if(mysqli_num_rows($sql) > 0){
		$row = mysqli_fetch_assoc($sql);
		$sem_id = $row['semester_id'];
		return $sem_id;
	}else{
		return "error";
	}
}
function get_active_id_acad_year(){
	include "config.php";

	$sql = mysqli_query($conn,"SELECT acad_year_id FROM academic_year WHERE status = 'ACTIVE'");
	if(mysqli_num_rows($sql) > 0){
		$row = mysqli_fetch_assoc($sql);
		$acad_id = $row['acad_year_id'];
		return $acad_id;
	}else{
		return "error";
	}
}
function time_id_start($time_start){
	include "config.php";



	$sql = mysqli_query($conn,"SELECT time_id FROM `time` WHERE time_s = '$time_start'");
	if(mysqli_num_rows($sql) > 0){
		$row = mysqli_fetch_assoc($sql);
		$time_id = $row['time_id'];
		return $time_id;
	}else{
		return "error";
	}
	
}
function time_id_end($time_end){
	include "config.php";


	
	$sql = mysqli_query($conn,"SELECT time_id FROM `time` WHERE `time_e` = '$time_end'");
	if(mysqli_num_rows($sql) > 0){
		$row = mysqli_fetch_assoc($sql);
		$time_id = $row['time_id'];

		return $time_id;
	}else{
		return "error";
	}
	
}



//update

function get_section_id_by_section_name_update($section,$dept_id){
	include "config.php";

	$sql = mysqli_query($conn,"
		SELECT 
	    s.section_id, 
	    s.section_name,
	    p.program_abbrv
		FROM sections s
		LEFT JOIN programs p ON p.program_id = s.program_id
		WHERE p.department_id = '$dept_id' AND CONCAT('BS',p.program_abbrv, ' ', s.section_name) = '$section'
");
	if(mysqli_num_rows($sql) > 0){
		$row = mysqli_fetch_assoc($sql);
		$section_id = $row['section_id'];
		return $section_id;
	}else{
		return "error";
	}
}

function get_class_sched_id($section_id,$department_id,$course_id){

include "config.php";

$sql = "SELECT 
fl.`fac_load_id`
FROM faculty_loadings fl

LEFT JOIN semesters sm ON sm.`semester_id` = fl.`sem_id`
LEFT JOIN academic_year ay ON ay.`acad_year_id` = fl.`acad_year_id`
WHERE fl.`dept_id` = '$department_id' 
 AND fl.section_id = '$section_id' AND sm.`status` = 'ACTIVE' AND ay.`status` = 'ACTIVE'
 AND course_id = '$course_id'
";
$result = mysqli_query($conn,$sql);
if(mysqli_num_rows($result) > 0){
		$row = mysqli_fetch_assoc($result);
		$fac_load_id = $row['fac_load_id'];
		return $fac_load_id;
	}else{
		return "error";
	}
}
function get_room_id_by_room_name($room_name){
	include "config.php";

	$sql = mysqli_query($conn,"SELECT room_id FROM rooms WHERE room_name = '$room_name'");
	if(mysqli_num_rows($sql) > 0){
		$row = mysqli_fetch_assoc($sql);
		$room_id = $row['room_id'];
		return $room_id;
	}else{
		return "error";
	}
}


?>