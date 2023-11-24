<?php
session_start();
include "config.php";
include "functions.php";
$faculty_id = $_POST['faculty_id'];
$facultyname = $_POST['faculty_name'];
$type = $_POST['type'];
$error = "error";
$success = "success";
$designation = $_POST['designation'];
$position = $_POST['position'];

// $position_id = getPositionId($position);
// $designation_id = getDesignationId($designation);
// $designation_verify = verifyDesignation($designation,$faculty_id);


	
if($type == "Permanent"){
			$sql = mysqli_query($conn,"UPDATE faculties SET is_permanent = 1,is_guest=0,is_partTime=0 WHERE faculty_id = '$faculty_id'");
			if($sql){
				$message = "Faculty has been successfully updated!";
				$_SESSION['alert'] = $success; 
	    		$_SESSION['message'] =  $message;   //failed to insert
	    		header("Location: ../deans/faculties_management.php");
			}else{
				$message = "Something went wrong updating faculty!";
				$_SESSION['alert'] = $error; 
	    		$_SESSION['message'] =  $message;   //failed to insert
	    		header("Location: ../deans/faculties_management.php");
			}
			
}elseif($type == "Temporary"){
	$sql = mysqli_query($conn,"UPDATE faculties SET is_permanent = 0,is_guest=1,is_partTime=0 WHERE faculty_id = '$faculty_id'");
	if($sql){
		$message = "Faculty has been successfully updated!";
		$_SESSION['alert'] = $success; 
	    $_SESSION['message'] =  $message;   //failed to insert
	    header("Location: ../deans/faculties_management.php");
	}else{
		$message = "Something went wrong updating faculty!";
		$_SESSION['alert'] = $error; 
	    $_SESSION['message'] =  $message;   //failed to insert
	    header("Location: ../deans/faculties_management.php");
	}
}elseif($type == "Part Time"){
	$sql = mysqli_query($conn,"UPDATE faculties SET is_permanent = 0,is_guest=0,is_partTime=1 WHERE faculty_id = '$faculty_id'");
	if($sql){
		$message = "Faculty has been successfully updated!";
		$_SESSION['alert'] = $success; 
	    $_SESSION['message'] =  $message;   //failed to insert
	    header("Location: ../deans/faculties_management.php");
	}else{
		$message = "Something went wrong updating faculty!";
		$_SESSION['alert'] = $error; 
	    $_SESSION['message'] =  $message;   //failed to insert
	    header("Location: ../deans/faculties_management.php");
			}

}
else{
		$message = "Something went wrong updating faculty!";
		$_SESSION['alert'] = $error; 
	    $_SESSION['message'] =  $message;   //failed to insert
	    header("Location: ../deans/faculties_management.php");
			}





	





function getDesignationId($designation){
	include 'config.php';

	$query = mysqli_query($conn,"SELECT designation_id FROM designation WHERE designation = '$designation'");
	if($query){
		if(mysqli_num_rows($query) > 0){
			$row = mysqli_fetch_assoc($query);
			$designation_id = $row['designation_id'];
			return $designation_id;
		}else{
			return false;
		}
	}else{
		return false;
	}
}
function getPositionId($position){
	include "config.php";

	$query = mysqli_query($conn,"SELECT title_id FROM titles WHERE title_description = '$position'");
	if($query){
		if(mysqli_num_rows($query) > 0){
			$row = mysqli_fetch_assoc($query);
			$position_id = $row['title_id'];

			return $position_id;

		}else{
			return false;
		}
	}else{
		return false;
	}
}
function verifyDesignation($designation,$faculty_id){
	include "config.php";
	$query = mysqli_query($conn,"SELECT 
								f.`firstname`,
								f.`middlename`,
								f.`lastname`,
								f.`suffix`,
								d.designation
								
								
							FROM faculties f
							LEFT JOIN designation d ON d.designation_id = f.`designation_id`							
							WHERE designation = '$designation'
							AND f.faculty_id != '$faculty_id'");
	if($query){
		if(mysqli_num_rows($query) > 0){
			return false;
		}else{
			return true;
		}
	}else{
		return true;
	}
}
?>
