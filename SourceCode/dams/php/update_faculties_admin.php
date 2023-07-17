<?php

include "config.php";
include "functions.php";
$faculty_id = $_POST['faculty_id'];
$facultyname = $_POST['faculty_name'];
$type = $_POST['type'];


if($type == "Permanent"){
			$sql = mysqli_query($conn,"UPDATE faculties SET is_permanent = 1,is_guest=0,is_partTime=0 WHERE faculty_id = '$faculty_id'");
			if($sql){
				header("Location: ../admin/faculties_management.php?Message : ".$facultyname . " has been successfully updated!");
			}
			else{
				header("Location: ../admin/faculties_management.php?Message : Update failed!");
			}
		}


elseif($type == "Temporary"){
			$sql = mysqli_query($conn,"UPDATE faculties SET is_permanent = 0,is_guest=1,is_partTime=0 WHERE faculty_id = '$faculty_id'");
			if($sql){
				header("Location: ../admin/faculties_management.php?Message : ".$facultyname . " has been successfully updated!");
			}
			else{
				echo "Update failed!";
			}
}
else{
	$sql = mysqli_query($conn,"UPDATE faculties SET is_permanent = 0,is_guest=0,is_partTime=1 WHERE faculty_id = '$faculty_id'");
			if($sql){
				header("Location: ../admin/faculties_management.php?Message : ".$facultyname . " has been successfully updated!");
			}
			else{
				header("Location: ../admin/faculties_management.php?Message : Update failed!");
			}

}


?>
