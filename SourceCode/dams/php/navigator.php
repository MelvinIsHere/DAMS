<?php 
$type = $_POST['type'];// the account type
$document = $_POST['document'];
$dept_id = $_POST['dept_id'];
$user_id = $_POST['user_id'];
$term_id = $_POST['term_id'];
$supervisor = $_POST['supervisor'];
if($document == 'IPCR'){
	if($type == 'Staff'){
		header("Location: automation_documents/staff_opcr_ipcr/generate_ipcr.php?dept_id=$dept_id&user_id=$user_id&term_id=$term_id&supervisor=$supervisor");
	}elseif($type == 'Faculty' || $type == 'Dean' || $type == 'Head'){
		header("Location: automation_documents/faculty_opcr_ipcr/generate_ipcr.php?dept_id=$dept_id&user_id=$user_id&term_id=$term_id&supervisor=$supervisor&type=$type");
		
	}
}elseif($document == 'OPCR'){
	if($type == 'Head'){
		header("Location: automation_documents/dean_heads_opcr/head_opcr.php?dept_id=$dept_id&term_id=$term_id&user_id=$user_id&type=$type&supervisor=$supervisor");
	}elseif($type == 'Dean'){
		header("Location: automation_documents/dean_heads_opcr/dean_opcr.php?dept_id=$dept_id&term_id=$term_id&user_id=$user_id&type=$type&supervisor=$supervisor");
	}
}else{

}

//generate_ipcr.php
?>
