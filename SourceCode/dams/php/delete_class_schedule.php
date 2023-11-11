<?php 
session_start();
$error = "error";
$success = "success";
include "config.php";
$sched_id = $_POST['loading_id'];
$section_name = $_GET['section_name'];

$sql = mysqli_query($conn, "DELETE FROM class_schedule WHERE class_sched_id = '$sched_id'");
if ($sql) {
             $_SESSION['alert'] = $success; 
        $message = "Schedule has been successfully deleted!";    
        $_SESSION['message'] =  $message;   //failed to insert
        $delete_fac_sched = deleteClassinFacSched($sched_id);
        header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
} else {
             $_SESSION['alert'] = $error; 
        $message = "Something went wrong! Schedule deletion failed!";    
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../deans/class_schedule_individual.php?section_name=$section_name");
}
function deleteClassinFacSched($sched_id){
    include "config.php";
    $sql = "DELETE FROM faculty_schedule WHERE class_sched_id = '$sched_id'";
    $result = mysqli_query($conn,$sql);
    if($result){
        return true;
    }else{
        return false;
    }
}
?>
