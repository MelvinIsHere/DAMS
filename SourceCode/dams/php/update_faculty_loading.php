<?php

include "config.php";
include "functions.php";
$loading_id = $_POST['loading_id'];
$faculty = $_POST['faculty_name'];
$course_code = $_POST['course_code'];
$section = $_POST['section'];
$semester = $_POST['semester'];
$facultyname = str_replace(',', '', $faculty);


//get faculty id
$faculty_id = getFacultyId($facultyname);
if($faculty_id != ""){
	$course_id = getCourseData($course_code);
	if($course_id != ""){
		$section_id = getSectionId($section);
		if($section_id != ""){
			$semester_id = getSemesterId($semester);
			if($semester_id != ""){
				$sql = "UPDATE faculty_loadings SET faculty_id = '$faculty_id', course_id = '$course_id',section_id = '$section_id', sem_id = '$semester_id' WHERE fac_load_id = '$loading_id'";
				if (mysqli_query($conn, $sql)) {
					echo "Faculty Loading Information Successfully Updated!" . $loading_id;
				} 
				else{
						echo "Faculty Loading Information Update Unsuccessfull!";
					}

			}
			else{
	echo mysqli_error($conn);
}

		}
		else{
	echo mysqli_error($conn);
}
	}
	else{
	echo "error";
}

}
else{
	echo "error";
}


?>
