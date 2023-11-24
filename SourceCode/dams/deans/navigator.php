<?php 
include "../php/functions.php";
session_start();
$users_id = $_SESSION['user_id'];

$template_name = $_GET['name'];


// $template_name = getTemplateName($template);
if($template_name == "Faculty Loading"){
	header("Location: faculty_loading_ui.php");
}
elseif($template_name == "Class Schedule"){
	header("Location: class_schedule_ui.php");
}
elseif($template_name == "Faculty Schedule"){
	header("Location: faculty_schedule.php");
}elseif($template_name == "Room Utilization Matrix"){
	header("Location: room_utilization_matrix_ui.php");
}elseif($template_name == "Office Performance Commitment and Review Target"){
	header("Location: opcr.php");
}elseif($template_name == "Office Performance Commitment and Review Accomplishment"){
	header("Location: opcr.php");
}elseif($template_name == "Individual Performance Commitment and Review Target"){
	header("Location: ipcr.php");
}elseif($template_name == "Individual Performance Commitment and Review Accomplishment"){
	header("Location: ipcr.php");
}
else{
	// header("Location: pendingDocuments.php?Alert : No task Such as" . $template);
}
?>