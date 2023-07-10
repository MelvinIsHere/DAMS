<?php

include "../config.php";
include "functions.php";

$facultyname = $_POST['faculty_name'];
$titles = $_POST['title'];
if(empty($facultyname) || empty($titles)){
	echo "Some fields are empty please fill out";
}

else{
	$faculty_id = getFacultyId($facultyname); //getting the id of faculty

//will now get the faculty title id
$title_id = getTitleId($titles);


if($faculty_id === ""){
	echo "No faculty such as " . $facultyname;
}
else if($title_id === ""){
	echo "there are no title such as " . $titles;
}
else{
	$sql = mysqli_query($conn,"INSERT INTO faculty_titles(faculty_id,title_id) VALUES('$faculty_id','$title_id')");
	if($sql){
		echo $facultyname . " are now have the title " . $titles; 
	}
	else{
		echo "error";
	}
}	
}


?>