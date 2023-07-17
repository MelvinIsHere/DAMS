<?php

include "config.php";
include "functions.php";
$department_name = $_POST['department_name'];
$department_abbrv = $_POST['abbrv'];

$sql = mysqli_query($conn,"SELECT * FROM departments WHERE department_name = '$department_name'");
if(mysqli_num_rows($sql) > 0){
	$row = mysqli_fetch_assoc($sql);
	$dept_name = $row['department_name'];
	$lower_case_dept_name = strtolower($dept_name);
	$lower_case_deptartment_name = strtolower($department_name);
	if($lower_case_deptartment_name == $lower_case_dept_name){
		header("Location: ../admin/manage_departments.php? Message: There is a department " . $department_name ." already!");
	}
	else{
		$insert = mysqli_query($conn,"INSERT INTO departments(department_name,department_abbrv)VALUES('$department_name','$department_abbrv') ");
		if($insert){
			header("Location: ../admin/manage_departments.php?Message: ".$department_name." has been added as a new department!");
		}
		else{
			header("Location: ../admin/manage_departments.php?Message: ".$department_name." department adding failed!");	
		}

	}
}
else{
	$insert = mysqli_query($conn,"INSERT INTO departments(department_name,department_abbrv)VALUES('$department_name','$department_abbrv') ");
	if($insert){
		header("Location: ../admin/manage_departments.php?Message: ".$department_name." has been added as a new department!");
	}
	else{
		header("Location: ../admin/manage_departments.php?Message: ".$department_name." department adding failed!");	
	}
}
?>
