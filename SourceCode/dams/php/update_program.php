<?php

include "config.php";
include "functions.php";
$program_id = $_POST['program_id'];
$program_name = $_POST['program_name'];
$program_abbrv = $_POST['program_abbrv'];


if(!empty($program_id)){
	
		$sql = mysqli_query($conn,"UPDATE programs SET program_title = '$program_name',program_abbrv = '$program_abbrv' 
									WHERE program_id = '$program_id'");
		if($sql){
			
			header("Location: ../admin/program_management.php?Message : Program ".$program_name." has been successfully updated!");

		
		}
		else{
			header("Location: ../admin/program_management.php?Message : There is something wrong updating the program ". $program_name);	
		}

}
else{
	header("Location: ../admin/program_management.php?Message : There are no program such as " .$program_name);
}


	



?>
