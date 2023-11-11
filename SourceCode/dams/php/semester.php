<?php 
include "config.php";
$semester = $_POST['semester'];

$sql = mysqli_query($conn,"SELECT sem_description FROM semesters WHERE sem_description = '$semester'");
if(mysqli_num_rows($sql) > 0 ){
	$update = mysqli_query($conn,"UPDATE semesters SET status = 'NOT ACTIVE'");
	if($update){
		$status_new  = mysqli_query($conn,"UPDATE semesters SET status = 'ACTIVE' WHERE sem_description = '$semester'");
		if($status_new){
			echo "success";
		}else{
			echo "error";
		}

	}
	else{
		echo "error";
	}
}
else{
	echo "error";
}
?>