<?php
session_start();
include "config.php";
include "functions.php";
$department_name = $_POST['department_name'];
$department_abbrv = $_POST['abbrv'];
$success = "success";
$error = "error";
$sql = mysqli_query($conn,"SELECT * FROM departments WHERE department_name = '$department_name'");
if(mysqli_num_rows($sql) > 0){
	$row = mysqli_fetch_assoc($sql);
	$dept_name = $row['department_name'];
	$lower_case_dept_name = strtolower($dept_name);
	$lower_case_deptartment_name = strtolower($department_name);
	if($lower_case_deptartment_name == $lower_case_dept_name){
		$message = "There is a department " . $department_name ." already!";
		 $_SESSION['alert'] = $error; 
         $_SESSION['message'] =  $message;   //failed to insert
         header("Location: ../admin/manage_departments.php");
	}
	else{
		$insert = mysqli_query($conn,"INSERT INTO departments(department_name,department_abbrv)VALUES('$department_name','$department_abbrv') ");
		if($insert){
			$message = "$department_name has been added as a new department!";
		    $_SESSION['alert'] = $success; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../admin/manage_departments.php");
		}
		else{
			$message = "$department_name department adding failed!";	
			 $_SESSION['alert'] = $error; 
             $_SESSION['message'] =  $message;   //failed to insert
             header("Location: ../admin/manage_departments.php");
		}

	}
}
else{
	$insert = mysqli_query($conn,"INSERT INTO departments(department_name,department_abbrv)VALUES('$department_name','$department_abbrv') ");
	if($insert){
		$message  = "$department_name has been added as a new department!";
		$_SESSION['alert'] = $success; 
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../admin/manage_departments.php");
	}
	else{
		$message = "$department_name department adding failed!";	
		$_SESSION['alert'] = $error; 
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../admin/manage_departments.php");
	}
}
?>
