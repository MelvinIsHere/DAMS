<?php 

include "../config.php";
include "functions.php";

$course_name = $_POST['course'];
$course_code = $_POST['course_code'];
$department = $_POST['department'];
$units = $_POST['units'];
$lecture = $_POST['lecture'];
$rle = $_POST['rle'];
$lab = $_POST['lab'];

if(empty($course_name) || empty($course_code) || empty($department) || empty($units) || empty($lecture) || empty($rle) || empty($lab)){

	echo"Fields are required please fill up them";

}
else{
	$dept_id = getDeptId($department);
	if ($dept_id === "") {
		return "There no department such as ". $department;
	}
	else{
		$sql = mysqli_query($conn, "INSERT INTO courses(course_code,course_description,department_id,units,lec_hrs_wk,rle_hrs_wk,lab_hrs_wk) VALUES('$course_code','$course_name','$dept_id','$units','$lecture','$rle','$lab')");
		if($sql){
			 echo "Course " . $course_name . " has been added!";
		}
		else{
			 echo "Something went wrong course not Inserted"; 	
		}

	}
}

?>