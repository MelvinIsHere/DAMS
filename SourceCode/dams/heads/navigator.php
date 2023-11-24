<?php 
include "../php/functions.php";
session_start();
$users_id = $_SESSION['user_id'];

$template = $_GET['name'];


// $template_name = getTemplateName($template);
if($template == "Faculty Loading"){
	header("Location: faculty_loading_ui.php");
}
elseif($template == "Class Schedule"){
	header("Location: class_schedule_ui.php");
}
elseif($template == "Faculty Schedule"){
	header("Location: faculty_schedule.php");
}elseif($template == "Room Utilization Matrix"){
	header("Location: room_utilization_matrix_ui.php");
}elseif($template == "IPCR"){
	header("Location: ipcr.php");
}
elseif($template == "Office Performance Commitment and Review Target"){
	header("Location: opcr.php");
}elseif($template == "Office Performance Commitment and Review Accomplishment"){
	header("Location: opcr.php");
}
else{
	// header("Location: pendingDocuments.php?Alert : No task Such as" . $template);
	echo $template;
}
?>