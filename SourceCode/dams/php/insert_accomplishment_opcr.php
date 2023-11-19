<?php 
session_start();
//only update the ipcr
include "config.php";


$accomplishment =$_POST['accomplishment'];
$quality = $_POST['quality'];
$efficiency = $_POST['efficiency'];
$timeliness = $_POST['timeliness'];
$opcr_id = $_POST['opcr_id'];
$type = $_POST['type'];
$accountable = $_POST['accountable'];
$query = mysqli_query($conn,"UPDATE opcr SET actual_accomplishment = '$accomplishment',accountable = '$accountable' WHERE opcr_id = '$opcr_id'");

if(!empty($quality)){
	$query = mysqli_query($conn,"UPDATE opcr SET quality = '$quality' WHERE opcr_id = '$opcr_id'");


}else{
	$query = mysqli_query($conn,"UPDATE opcr SET quality = NULL WHERE opcr_id = '$opcr_id'");
}
if(!empty($efficiency)){
	$query = mysqli_query($conn,"UPDATE opcr SET efficiency = '$efficiency' WHERE opcr_id = '$opcr_id'");


}else{
	$query = mysqli_query($conn,"UPDATE opcr SET efficiency = NULL WHERE opcr_id = '$opcr_id'");
}
if(!empty($timeliness)){
	$query = mysqli_query($conn,"UPDATE opcr SET timeliness = '$timeliness' WHERE opcr_id = '$opcr_id'");


}else{
	$query = mysqli_query($conn,"UPDATE opcr SET timeliness = NULL WHERE opcr_id = '$opcr_id'");
}

$_SESSION['message'] = "Updated Successfully!";
$_SESSION['alert'] = "success";
if($type == 'Dean'){
	header("Location: ../deans/opcr.php");
}elseif($type == 'Head'){
	header("Location: ../heads/opcr.php");
}








?>