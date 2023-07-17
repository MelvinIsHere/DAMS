<?php

include "config.php";
include "functions.php";
$program_id = $_POST['program_id'];



if(!empty($program_id)){
	
		$sql = mysqli_query($conn,"DELETE FROM programs WHERE program_id = '$program_id'");
		if($sql){
			
			echo "Program has been successfully deleted!";

		
		}
		else{
			echo "There is something wrong deleting the program ";	
		}

}
else{
	echo "There is something wrong deleting the program ";	
}


	



?>
