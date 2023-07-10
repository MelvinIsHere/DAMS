<?php 
include "../config.php";
include "functions.php";

$program_name = $_POST['program_name'];
$department = $_POST['department'];
$abbrv = $_POST['abbrv'];

if(empty($program_name) || empty($department) || empty($abbrv)){
	echo "Fields are required please fill out";
}
else{
	$dept_id = getDeptId($department);
	if($dept_id === ""){
		echo "There are no department such as " . $department;
	}
	else{
		$sql = mysqli_query($conn,"INSERT INTO programs(program_title,department_id,program_abbrv)VALUES('$program_name','$dept_id','$abbrv')");
		if($sql){
			echo "Successfully added program " . $program_name;
		}
		else{
			echo "Something Went wrong please try again";
		}
	}
}
?>