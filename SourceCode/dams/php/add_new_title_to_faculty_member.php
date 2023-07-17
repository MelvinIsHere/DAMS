<?php

include "config.php";
include "functions.php";
$faculty = $_POST['faculty'];
$title = $_POST['title'];

$faculty_id = getFacultyId($faculty);
if($faculty_id != ""){
	$title_id = getTitleId($title);
	if($title_id != "error"){
		$sql = mysqli_query($conn,"INSERT INTO faculty_titles(faculty_id,title_id) VALUES('$faculty_id','$title_id')");
		if($sql){
			
			header("Location: ../deans/faculty_titles_management.php?Message : Title has been granted to ". $faculty." successfully");

		
		}
		else{
			header("Location: ../deans/faculty_titles_management.php?Message : Failed to grant title " . $title . " to " . $faculty);		
		}
	}
	else{
		header("Location: ../deans/faculty_titles_management.php?Message : There are no title such as " . $title);
	} 
}
else{
	header("Location: ../deans/faculty_titles_management.php?Message : There are no faculty member such as " . $faculty);
}


?>
