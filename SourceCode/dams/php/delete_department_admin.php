<?php

include "config.php";
include "functions.php";

$department_id = $_POST['dept_id'];


if(!empty($department_id)){
	$sql = mysqli_query($conn,"DELETE FROM departments WHERE department_id = '$department_id'");
	if($sql){
		echo "The department has been successfully removed!";
	}
	else{
		echo "Something went wrong removing the department";
	}
}
else{
	echo "Something went wrong removing the department";
}


?>
