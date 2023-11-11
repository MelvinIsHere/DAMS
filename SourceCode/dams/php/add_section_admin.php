<?php
session_start();
include "config.php";
include "functions.php";
$section_name = $_POST['section_name'];
$students = $_POST['students'];
$programs = $_POST['programs'];
$semester = $_POST['semester'];


$program_id = getProgramId($programs);
$success = "success";
$error = "error";
if($program_id != "error"){
	$semester_id = getSemesterId($semester);

	if($semester_id != ""){
		


			if(!empty($students)){
				$sql = mysqli_query($conn,"INSERT INTO sections(program_id,section_name,semester_id,no_of_students)
											VALUES('$program_id','$section_name','$semester_id','$students')");
				if($sql){
					$message = "Section  Successfully Added!";
					$_SESSION['alert'] = $success; 
                	$_SESSION['message'] =  $message;   //failed to insert
                	header("Location: ../admin/section_management.php");
				}
				else{
					$message = "Something went wrong adding the Section!";	
					$_SESSION['alert'] = $error; 
                	$_SESSION['message'] =  $message;   //failed to insert
                	header("Location: ../admin/section_management.php");
				}
			}
			else{
				$sql = mysqli_query($conn,"INSERT INTO sections(program_id,section_name,semester_id,no_of_students)
											VALUES('$program_id','$section_name','$semester_id',0)");
				if($sql){
					$message = "Section  Successfully Added!";
					$_SESSION['alert'] = $success; 
                	$_SESSION['message'] =  $message;   //failed to insert
                	header("Location: ../admin/section_management.php");
				}
				else{
						$message = "Something went wrong adding the Section!";	
					$_SESSION['alert'] = $error; 
                	$_SESSION['message'] =  $message;   //failed to insert
                	header("Location: ../admin/section_management.php");

				}
			}

		
	}
	else{
		
				$message = "Something went wrong adding the Section!";	
					$_SESSION['alert'] = $error; 
                	$_SESSION['message'] =  $message;   //failed to insert
                	header("Location: ../admin/section_management.php");

	}

}
else{
			$message = "Something went wrong adding the Section!";	
					$_SESSION['alert'] = $error; 
                	$_SESSION['message'] =  $message;   //failed to insert
                	header("Location: ../admin/section_management.php");
}


?>




