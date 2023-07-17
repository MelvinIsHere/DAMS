<?php

include "config.php";
include "functions.php";
$program_name = $_POST['program_name'];
$program_abbrv = $_POST['program_abbrv'];
$dept_name = $_POST['department_name'];

$department_id = getDeptId($dept_name);
if($department_id != ""){
	$sql = mysqli_query($conn,"INSERT INTO programs(program_title,department_id,program_abbrv) 
								VALUES('$program_name','$department_id','$program_abbrv')");
	if($sql){
		header("Location: ../admin/program_management.php?Message: The " .$program_name ." has been successfully added!");
	}
	else{
		header("Location: ../admin/program_management.php?Message: The " .$program_name ." failed to be added!");
	}
}
else{
	header("Location: ../admin/program_management.php?Message: Something went wrong!");
}

?>
