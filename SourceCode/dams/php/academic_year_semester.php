<?php 
include 'config.php';
include "functions.php";

$academic_year = $_POST['academic_year'];


$verify = verify_academic_year($academic_year);
if($verify == "success"){
	echo "success";
}
elseif($verify == "success"){
 echo "success";
}
else{
	$update_status_before = mysqli_query($conn,"UPDATE academic_year SET status = 'NOT ACTIVE'");
	if($update_status_before){
		$academic_year_active = mysqli_query($conn,"INSERT INTO academic_year(acad_year,status)VALUES('$academic_year','ACTIVE')");
	if($academic_year){

		echo "success";
	}else{
		echo "error";
	}
	}
	else{
		echo "error";
	}
}



?>