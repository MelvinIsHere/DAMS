<?php
session_start();
include "config.php";
include "functions.php";
$section_name = $_POST['section_name'];
$students = $_POST['students'];
$programs = $_POST['programs'];
$semester = $_POST['semester'];
$adviser = $_POST['adviser'];
$error = "error";
$success = "success";


//get adviser id
$adviser_id =getAdviserId($conn,$adviser);
if($adviser_id){
	//if adviser id is not empty
	$program_id = getProgramId($programs);
	if($program_id != "error"){
		$semester_id = getSemesterId($semester);
		if($program_id != ""){
			if(!empty($students)){
				$sql = mysqli_query($conn,"INSERT INTO sections(program_id,section_name,semester_id,no_of_students,adviser_id)
										VALUES('$program_id','$section_name','$semester_id','$students','$adviser_id')");
				if($sql){
					$message = "Section has been Successfully Added!";
					$_SESSION['alert'] = $success; 
                	$_SESSION['message'] =  $message;   //failed to insert
                	header("Location: ../deans/section_management.php");                
				}else{
					$message = "Something went wrong adding the Section!";	
					$_SESSION['alert'] = $error; 
                	$_SESSION['message'] =  $message;   //failed to insert
                	header("Location: ../deans/section_management.php");
				}
			}else{
				$sql = mysqli_query($conn,"INSERT INTO sections(program_id,section_name,semester_id,no_of_students,adviser_id)
										VALUES('$program_id','$section_name','$semester_id',0,'$adviser_id')");
				if($sql){
					$message = "Section has been Successfully Added!";
					$_SESSION['alert'] = $success; 
                	$_SESSION['message'] =  $message;   //failed to insert
                	header("Location: ../deans/section_management.php");
				}else{
					$message = "Something went wrong adding the Section!";	
					$_SESSION['alert'] = $error; 
                	$_SESSION['message'] =  $message;   //failed to insert
                	header("Location: ../deans/section_management.php");
				}
			}
		
		}else{
			$message = "Something went wrong adding the Section!";	
			$_SESSION['alert'] = $error; 
        	$_SESSION['message'] =  $message;   //failed to insert
        	header("Location: ../deans/section_management.php");
		}

	}else{
		$message = "Something went wrong adding the Section!";	
		$_SESSION['alert'] = $error; 
    	$_SESSION['message'] =  $message;   //failed to insert
		header("Location: ../deans/section_management.php");
	}
}else{
//if empty adviser
	$program_id = getProgramId($programs);
	if($program_id != "error"){
		$semester_id = getSemesterId($semester);
		if($program_id != ""){
			if(!empty($students)){
				$sql = mysqli_query($conn,"INSERT INTO sections(program_id,section_name,semester_id,no_of_students)
										VALUES('$program_id','$section_name','$semester_id','$students')");
				if($sql){
					$message = "Section has been Successfully Added!";
					$_SESSION['alert'] = $success; 
                	$_SESSION['message'] =  $message;   //failed to insert
                	header("Location: ../deans/section_management.php");                
				}else{
					$message = "Something went wrong adding the Section!";	
					$_SESSION['alert'] = $error; 
                	$_SESSION['message'] =  $message;   //failed to insert
                	header("Location: ../deans/section_management.php");
				}
			}else{
				$sql = mysqli_query($conn,"INSERT INTO sections(program_id,section_name,semester_id,no_of_students)
										VALUES('$program_id','$section_name','$semester_id',0)");
				if($sql){
					$message = "Section has been Successfully Added!";
					$_SESSION['alert'] = $success; 
                	$_SESSION['message'] =  $message;   //failed to insert
                	header("Location: ../deans/section_management.php");
				}else{
					$message = "Something went wrong adding the Section!";	
					$_SESSION['alert'] = $error; 
                	$_SESSION['message'] =  $message;   //failed to insert
                	header("Location: ../deans/section_management.php");
				}
			}
		
		}else{
			$message = "Something went wrong adding the Section!";	
			$_SESSION['alert'] = $error; 
        	$_SESSION['message'] =  $message;   //failed to insert
        	header("Location: ../deans/section_management.php");
		}

	}else{
		$message = "Something went wrong adding the Section!";	
		$_SESSION['alert'] = $error; 
    	$_SESSION['message'] =  $message;   //failed to insert
		header("Location: ../deans/section_management.php");
	}
}


function getAdviserId($conn,$adviser){
	$query = mysqli_query($conn,"SELECT faculty_id FROM faculties WHERE CONCAT(firstname,' ',middlename,' ',lastname,' ',suffix) = '$adviser'");
	if($query){
		if(mysqli_num_rows($query) >0){
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
?>
