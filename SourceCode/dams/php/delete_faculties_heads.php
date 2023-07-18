<?php

include "config.php";
include "functions.php";
$faculty_id = $_POST['faculty_id'];


if(!empty($faculty_id)){
	
	$sql = mysqli_query($conn,"DELETE FROM faculties  WHERE faculty_id = '$faculty_id'");
	if($sql){
		header("Location: ../heads/faculties_management.php?Message :Faculty member has been successfully removed as Faculty!");
	}
	else{
		header("Location: ../heads/faculties_management.php?Message :Faculty member removal failed!");

	}
}
else{
	header("Location: ../heads/faculties_management.php?Message : Faculty member removal failed!") ;
}


?>
