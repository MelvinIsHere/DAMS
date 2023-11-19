<?php 
session_start();
//only update the ipcr
include "config.php";


$accomplishment =$_POST['accomplishment'];
$quality = $_POST['quality'];
$efficiency = $_POST['efficiency'];
$timeliness = $_POST['timeliness'];
$ipcr_id = $_POST['ipcr_id'];
$type = $_POST['type'];
$query = mysqli_query($conn,"UPDATE ipcr_table SET actual_accomplishment = '$accomplishment' WHERE ipcr_id = '$ipcr_id'");

if(!empty($quality)){
	$query = mysqli_query($conn,"UPDATE ipcr_table SET quality = '$quality' WHERE ipcr_id = '$ipcr_id'");


}else{
	$query = mysqli_query($conn,"UPDATE ipcr_table SET quality = NULL WHERE ipcr_id = '$ipcr_id'");
}
if(!empty($efficiency)){
	$query = mysqli_query($conn,"UPDATE ipcr_table SET efficiency = '$efficiency' WHERE ipcr_id = '$ipcr_id'");


}else{
	$query = mysqli_query($conn,"UPDATE ipcr_table SET efficiency = NULL WHERE ipcr_id = '$ipcr_id'");
}
if(!empty($timeliness)){
	$query = mysqli_query($conn,"UPDATE ipcr_table SET timeliness = '$timeliness' WHERE ipcr_id = '$ipcr_id'");


}else{
	$query = mysqli_query($conn,"UPDATE ipcr_table SET timeliness = NULL WHERE ipcr_id = '$ipcr_id'");
}

$_SESSION['message'] = "Updated Successfully!";
$_SESSION['alert'] = "success";
if($type == 'Staff' || $type == 'Faculty'){
	header("Location: ../staffs/ipcr.php");
}elseif($type == 'Dean'){
	header("Location: ../deans/ipcr.php");
}elseif($type == 'Head'){
	header("Location: ../heads/ipcr.php");
}








?>