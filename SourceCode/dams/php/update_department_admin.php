<?php

include "config.php";
include "functions.php";
$department_name = $_POST['department_name'];
$department_abbrv = $_POST['department_abbrv'];
$department_id = $_POST['dept_id'];

$sql = mysqli_query($conn,"UPDATE departments SET department_name = '$department_name',department_abbrv = '$department_abbrv' 
							WHERE department_id = '$department_id'");
if($sql){
	header("Location: ../admin/manage_departments.php?Message : The department " . $department_name . " has been successfully updated!");


}
else{
	header("Location: ../admin/manage_departments.php?Message : The department ".$department_name." data update has failed!"); 
}



?>
