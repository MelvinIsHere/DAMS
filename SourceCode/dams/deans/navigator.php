<?php 
include "../php/functions.php";
session_start();
$users_id = $_SESSION['user_id'];

$template = $_GET['name'];


$template_name = getTemplateName($template);
if($template_name == "Faculty Loading"){
	header("Location: faculty_loading_ui.php");
}
else{
	header("Location: pendingDocuments.php?Alert : No task Such as" . $template);
}
?>