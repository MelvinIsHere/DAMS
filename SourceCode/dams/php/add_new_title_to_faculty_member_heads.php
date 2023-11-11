<?php

include "config.php";
include "functions.php";
$faculty = $_POST['faculty'];
$title = $_POST['title'];
$error = "error";
$success = "success";

$faculty_id = getFacultyId($faculty);
if($faculty_id != ""){
	$title_id = getTitleId($title);
	if($title_id != "error"){
		$sql = mysqli_query($conn,"INSERT INTO faculty_titles(faculty_id,title_id) VALUES('$faculty_id','$title_id')");
		if($sql){
			
			
			$message = "Title has been successfully granted tp $faculty";
			header("Location: ../heads/faculty_titles_management.php?alert=$success&message=$message");

		
		}
		else{
			
			$message = "Something went wrong when granting title $title to $faculty";	
			header("Location: ../heads/faculty_titles_management.php?alert=$error&message=$message");	
		}
	}
	else{
		$message = "There are no title such as " . $title;
		header("Location: ../heads/faculty_titles_management.php?alert=$error&message=$message");	
	} 
}
else{
	$message = "There are no faculty member such as " . $faculty;
	header("Location: ../heads/faculty_titles_management.php?alert=$error&message=$message");	
}


?>
