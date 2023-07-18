<?php

include "config.php";
include "functions.php";
$faculty = $_POST['faculty_name'];
$title = $_POST['faculty_title'];
$faculty_title_id = $_POST['faculty_title_id'];

$faculty_id = getFacultyId($faculty);
if($faculty_id != ""){
	$title_id = getTitleId($title);
	if($title_id != "error"){
		$sql = mysqli_query($conn,"UPDATE faculty_titles SET faculty_id = '$faculty_id', title_id = '$title_id' WHERE fac_title_id = '$faculty_title_id'");
		if($sql){
			
			header("Location: ../heads/faculty_titles_management.php?Message : ".$faculty." title has been successfully updated!");

			
		}
		else{
			header("Location: ../heads/faculty_titles_management.php?Message : Failed to update title for " . $faculty);		
		}
	}
	else{
		header("Location: ../heads/faculty_titles_management.php?Message : There are no title such as " . $title);
	} 
}
else{
	header("Location: ../heads/faculty_titles_management.php?Message : There are no faculty member such as " . $faculty);
}


?>
