<?php

include "config.php";
include "functions.php";
$section_name = $_POST['section_name'];
$students = $_POST['students'];
$programs = $_POST['programs'];
$semester = $_POST['semester'];
$dept_name = $_POST['department_name'];

$program_id = getProgramId($programs);
if($program_id != "error"){
	$semester_id = getSemesterId($semester);

	if($semester_id != ""){
		


			if(!empty($students)){
				$sql = mysqli_query($conn,"INSERT INTO sections(program_id,section_name,semester_id,no_of_students)
											VALUES('$program_id','$section_name','$semester_id','$Students')");
				if($sql){
					header("Location: ../admin/section_management.php?Message : Section  Successfully Added!");
				}
				else{
					header("Location: ../admin/section_management.php?Message : Adding Unsuccess!");	
				}
			}
			else{
				$sql = mysqli_query($conn,"INSERT INTO sections(program_id,section_name,semester_id,no_of_students)
											VALUES('$program_id','$section_name','$semester_id',0)");
				if($sql){
					header("Location: ../admin/section_management.php?Message : Section  Successfully Added!");
				}
				else{
					header("Location: ../admin/section_management.php?Message : Adding Unsuccess!");	
				}
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




