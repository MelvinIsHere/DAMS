<?php

include "config.php";
include "functions.php";

$department_id = $_POST['dept_id'];


if(!empty($department_id)){
	$sql = mysqli_query($conn,"DELETE FROM departments WHERE department_id = '$department_id'");
	if($sql){
		header("Location: ../admin/manage_departments.php?Message : The department has been successfully removed!");
	}
	else{
		header("Location: ../admin/manage_departments.php?Message : Something went wrong removing the department");
	}
}
else{
	header("Location: ../admin/manage_departments.php?Message : Something went wrong removing the department");
}


?>
