<?php

include "config.php";
include "functions.php";
$section = $_POST['section_id'];


if(!empty($section)){
	
	$sql = mysqli_query($conn,"DELETE FROM sections  WHERE section_id = '$section'");
	if($sql){
		header("Location: ../deans/section_management.php? Message : Section has been successfully deleted!");
	}
	else{
		header("Location: ../deans/section_management.php? Message : Section deletion failed!") ;

	}
}
else{
	header("Location: ../deans/section_management.php? Message : Section deletion failed!");
}


?>
