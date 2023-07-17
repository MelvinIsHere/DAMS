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

if(!empty($department_id)){
	if($type == "Permanent"){
		$sql = mysqli_query($conn,"INSERT INTO faculties(firstname,lastname,middlename,suffix,department_id,is_permanent,is_guest,is_partTime)
									VALUES('$first_name','$last_name','$middle_name','$suffix','$department_id',1,0,0)");
		if($sql){
			header("Location: ../deans/faculties_management.php?Message: faculty successfully added");
		}else{
			header("Location: ../deans/faculties_management.php?Message: faculty adding failed");
		}
	}elseif($type == "Temporary"){
		$sql = mysqli_query($conn,"INSERT INTO faculties(firstname,lastname,middlename,suffix,department_id,is_permanent,is_guest,is_partTime)
									VALUES('$first_name','$last_name','$middle_name','$suffix','$department_id',0,1,0)");
		if($sql){
			header("Location: ../deans/faculties_management.php?Message: faculty successfully added");
		}else{
			header("Location: ../deans/faculties_management.php?Message: faculty adding failed");
		}
	}
	else{
		$sql = mysqli_query($conn,"INSERT INTO faculties(firstname,lastname,middlename,suffix,department_id,is_permanent,is_guest,is_partTime)
									VALUES('$first_name','$last_name','$middle_name','$suffix','$department_id',0,0,1)");
		if($sql){
			header("Location: ../deans/faculties_management.php?Message: faculty successfully added");
		}else{
			header("Location: ../deans/faculties_management.php?Message: faculty adding failed");
		}
	}
}	
else{
	header("Location: ../deans/faculties_management.php?Message : Insert Failed!");
}

?>
