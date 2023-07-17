<?php

include "config.php";
include "functions.php";
$section = $_POST['section_name'];
$students = $_POST['students'];

$section_id = getSectionId($section);
if($section_id != ""){
	$sql = mysqli_query($conn,"UPDATE sections SET no_of_students = '$students' WHERE section_id = '$section_id'");
	if($sql){
		echo $section . " has successfully updated!";
	}
	else{
		echo $section ." update failed" ;

	}
}
else{
	echo $section ." update failed" ;
}


?>
