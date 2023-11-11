<?php
session_start();
$error = "error";
$success = "success";

include "config.php";
include "functions.php";
$loading_id = $_POST['loading_id'];
$faculty = $_POST['faculty_name'];
$course_code = $_POST['course_code'];
$section = $_POST['section'];
$semester = $_POST['semester'];
$facultyname = str_replace(',', '', $faculty);


$faculty_id = getFacultyId($facultyname);
if($faculty_id != ""){
	$update_faculty = updateWithFaculty($loading_id,$facultyname,$course_code,$section);
	if($update_faculty){
		$message = "Successfully Updated!";
        $_SESSION['alert'] = $success; 
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../deans/faculty_loading_ui.php");
	}else{
		$message = "Something went wrong, Update failed!";
        $_SESSION['alert'] = $error; 
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../deans/faculty_loading_ui.php");
	}
}else{
	 $update_without_faculty = updateWithoutFaculty($loading_id,$course_code,$section);
	 if($update_without_faculty){
	 	$message = "Successfully Updated!";
        $_SESSION['alert'] = $success; 
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../deans/faculty_loading_ui.php");
	 }else{
	 	$message = "Something went wrong, Update failed!";
        $_SESSION['alert'] = $error; 
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../deans/faculty_loading_ui.php");
	 }
}


//get faculty id




function updateWithFaculty($loading_id,$facultyname,$course_code,$section){
	include "config.php";
	$faculty_id = getFacultyId($facultyname);
if($faculty_id != ""){
	$course_id = getCourseData($course_code);
	if($course_id != ""){
		$section_id = getSectionId($section);
		if($section_id != ""){
			$sql = "UPDATE faculty_loadings SET faculty_id = '$faculty_id', course_id = '$course_id',section_id = '$section_id' WHERE fac_load_id = '$loading_id'";
				if (mysqli_query($conn, $sql)) {
					return true;
				}else{
					return false;
				}
		}else{
			return false;
		}
	}else{
		return false;
	}
}else{
	return false;
	}	
}





function updateWithoutFaculty($loading_id,$course_code,$section){
	include "config.php";
	$course_id = getCourseData($course_code);
	if($course_id != ""){
		$section_id = getSectionId($section);
		if($section_id != ""){
			$sql = "UPDATE faculty_loadings SET course_id = '$course_id',section_id = '$section_id' WHERE fac_load_id = '$loading_id'";
				if (mysqli_query($conn, $sql)) {
					return true;
				}else{
					return false;
				}
		}else{
			return false;
		}
	}else{
		return false;
		}
}




?>
