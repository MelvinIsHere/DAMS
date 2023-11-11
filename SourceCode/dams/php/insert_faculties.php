<?php
session_start();

include "config.php";
include "functions.php";
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$middle_name = $_POST['middle_name'];
$suffix = $_POST['suffix'];
$type = $_POST['type'];
$department_id = $_POST['department_id'];
$fullname = $last_name . " " . $first_name . " " . $middle_name . " " . $suffix;
$gened = $_POST['gened'];
$error = "error";
$success = "success";
$designation = $_POST['designation'];
$position = $_POST['position'];

$designation_get_id = getDesignationId($designation);	
$position_get_id = getPositionId($position);





if(!empty($department_id) && !empty($designation_get_id) && !empty($position_get_id)){
	if($type == "Permanent"){
		$sql = mysqli_query($conn, "INSERT INTO faculties(firstname, lastname, middlename, suffix, department_id, is_permanent, is_guest, is_partTime, is_gen_ed, designation_id,position_id)
    VALUES('$first_name', '$last_name', '$middle_name', '$suffix', '$department_id', 1, 0, 0, '$gened', '$designation_get_id','$position_get_id')");
		if($sql){
			$message = "Faculty has been successfully added!";
			$_SESSION['alert'] = $success; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");
		}else{
			echo mysqli_error($conn);
			$message = "Something went wrong adding a faculty";  
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");

		}
	}elseif($type == "Temporary"){
		$sql = mysqli_query($conn,"INSERT INTO faculties(firstname,lastname,middlename,suffix,department_id,is_permanent,is_guest,is_partTime,is_gen_ed,designation_id,position_id)
									VALUES('$first_name','$last_name','$middle_name','$suffix','$department_id',0,1,0,'$gened','$designation_id','$position_get_id')");
		if($sql){
			$message = "Faculty has been successfully added!";
			$_SESSION['alert'] = $success; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");
		}else{
			$message = "Something went wrong adding a faculty";  
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");
		}
	}
	elseif($type == "Part Time"){
		$sql = mysqli_query($conn,"INSERT INTO faculties(firstname,lastname,middlename,suffix,department_id,is_permanent,is_guest,is_partTime,is_gen_ed,designation_id,position_id)
									VALUES('$first_name','$last_name','$middle_name','$suffix','$department_id',0,0,1,'$gened','$designation_id','$position_get_id')");
		if($sql){
			$message = "Faculty has been successfully added!";
			$_SESSION['alert'] = $success; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");
		}else{
			$message = "Something went wrong adding a faculty";  
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");
		}
	}
	else{
		$message = "Something went wrong adding a faculty";  
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");
		
	}
}elseif(!empty($department_id) && empty($designation_get_id) && empty($position_get_id)){


		if($type == "Permanent"){
		$sql = mysqli_query($conn, "INSERT INTO faculties(firstname, lastname, middlename, suffix, department_id, is_permanent, is_guest, is_partTime, is_gen_ed, designation_id,position_id)
    VALUES('$first_name', '$last_name', '$middle_name', '$suffix', '$department_id', 1, 0, 0, '$gened', NULL,NULL)");
		if($sql){
			$message = "Faculty has been successfully added!";
			$_SESSION['alert'] = $success; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");
		}else{
			echo mysqli_error($conn);
			$message = "Something went wrong adding a faculty";  
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");

		}
	}elseif($type == "Temporary"){
		$sql = mysqli_query($conn,"INSERT INTO faculties(firstname,lastname,middlename,suffix,department_id,is_permanent,is_guest,is_partTime,is_gen_ed,designation_id,position_id)
									VALUES('$first_name','$last_name','$middle_name','$suffix','$department_id',0,1,0,'$gened',NULL,NULL)");
		if($sql){
			$message = "Faculty has been successfully added!";
			$_SESSION['alert'] = $success; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");
		}else{
			$message = "Something went wrong adding a faculty";  
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");
		}
	}
	elseif($type == "Part Time"){
		$sql = mysqli_query($conn,"INSERT INTO faculties(firstname,lastname,middlename,suffix,department_id,is_permanent,is_guest,is_partTime,is_gen_ed,designation_id,position_id)
									VALUES('$first_name','$last_name','$middle_name','$suffix','$department_id',0,0,1,'$gened',NULL,NULL)");
		if($sql){
			$message = "Faculty has been successfully added!";
			$_SESSION['alert'] = $success; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");
		}else{
			$message = "Something went wrong adding a faculty";  
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");
		}
	}
	else{
		$message = "Something went wrong adding a faculty";  
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");
		
	}



}elseif(!empty($department_id) && !empty($designation_get_id) && empty($position_get_id)){


		if($type == "Permanent"){
		$sql = mysqli_query($conn, "INSERT INTO faculties(firstname, lastname, middlename, suffix, department_id, is_permanent, is_guest, is_partTime, is_gen_ed, designation_id,position_id)
    VALUES('$first_name', '$last_name', '$middle_name', '$suffix', '$department_id', 1, 0, 0, '$gened', '$designation_get_id',NULL)");
		if($sql){
			$message = "Faculty has been successfully added!";
			$_SESSION['alert'] = $success; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");
		}else{
			echo mysqli_error($conn);
			$message = "Something went wrong adding a faculty";  
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");

		}
	}elseif($type == "Temporary"){
		$sql = mysqli_query($conn,"INSERT INTO faculties(firstname,lastname,middlename,suffix,department_id,is_permanent,is_guest,is_partTime,is_gen_ed,designation_id,position_id)
									VALUES('$first_name','$last_name','$middle_name','$suffix','$department_id',0,1,0,'$gened','$designation_get_id',NULL)");
		if($sql){
			$message = "Faculty has been successfully added!";
			$_SESSION['alert'] = $success; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");
		}else{
			$message = "Something went wrong adding a faculty";  
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");
		}
	}
	elseif($type == "Part Time"){
		$sql = mysqli_query($conn,"INSERT INTO faculties(firstname,lastname,middlename,suffix,department_id,is_permanent,is_guest,is_partTime,is_gen_ed,designation_id,position_id)
									VALUES('$first_name','$last_name','$middle_name','$suffix','$department_id',0,0,1,'$gened','$designation_get_id',NULL)");
		if($sql){
			$message = "Faculty has been successfully added!";
			$_SESSION['alert'] = $success; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");
		}else{
			$message = "Something went wrong adding a faculty";  
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");
		}
	}
	else{
		$message = "Something went wrong adding a faculty";  
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");
		
	}



}elseif(!empty($department_id) && empty($designation_get_id) && !empty($position_get_id)){


		if($type == "Permanent"){
		$sql = mysqli_query($conn, "INSERT INTO faculties(firstname, lastname, middlename, suffix, department_id, is_permanent, is_guest, is_partTime, is_gen_ed, designation_id,position_id)
    VALUES('$first_name', '$last_name', '$middle_name', '$suffix', '$department_id', 1, 0, 0, '$gened', NULL,'$position_get_id')");
		if($sql){
			$message = "Faculty has been successfully added!";
			$_SESSION['alert'] = $success; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");
		}else{
			echo mysqli_error($conn);
			$message = "Something went wrong adding a faculty";  
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");

		}
	}elseif($type == "Temporary"){
		$sql = mysqli_query($conn,"INSERT INTO faculties(firstname,lastname,middlename,suffix,department_id,is_permanent,is_guest,is_partTime,is_gen_ed,designation_id,position_id)
									VALUES('$first_name','$last_name','$middle_name','$suffix','$department_id',0,1,0,'$gened',NULL,'$position_get_id')");
		if($sql){
			$message = "Faculty has been successfully added!";
			$_SESSION['alert'] = $success; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");
		}else{
			$message = "Something went wrong adding a faculty";  
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");
		}
	}
	elseif($type == "Part Time"){
		$sql = mysqli_query($conn,"INSERT INTO faculties(firstname,lastname,middlename,suffix,department_id,is_permanent,is_guest,is_partTime,is_gen_ed,designation_id,position_id)
									VALUES('$first_name','$last_name','$middle_name','$suffix','$department_id',0,0,1,'$gened',NULL,'$position_get_id')");
		if($sql){
			$message = "Faculty has been successfully added!";
			$_SESSION['alert'] = $success; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");
		}else{
			$message = "Something went wrong adding a faculty";  
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");
		}
	}
	else{
		$message = "Something went wrong adding a faculty";  
			$_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../deans/faculties_management.php");
		
	}



}
else{
				$message = "Something went wrong adding a faculty";  
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

?>
