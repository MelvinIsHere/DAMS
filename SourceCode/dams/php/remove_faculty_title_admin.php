<?php

include "config.php";
include "functions.php";
$faculty_title_id = $_POST['faculty_title_id'];


if(!empty($faculty_title_id)){
	
	$sql = mysqli_query($conn,"DELETE FROM faculty_titles  WHERE fac_title_id = '$faculty_title_id'");
	if($sql){
		header("Location: ../admin/faculty_titles_management.php?Message : Title been successfully removed!");
	}
	else{
		header("Location: ../admin/faculty_titles_management.php?Message : Title removal failed!");

	}
}
else{
	header("Location: ../admin/faculty_titles_management.php?Message : Title removal failed!");
}


?>
