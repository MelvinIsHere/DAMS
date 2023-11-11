<?php
session_start();
include "config.php";
include "functions.php";

$students = $_POST['students'];
$section_id = $_POST['section_id'];
$error = "error";
$success = "success";
if(!empty($section_id)){
    
    $sql = mysqli_query($conn,"UPDATE sections SET no_of_students = '$students' WHERE section_id = '$section_id'");
    if($sql){
        $message = "Section has been successfully updated!";
        $_SESSION['alert'] = $success; 
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../admin/section_management.php");
    }
    else{
        $message = "Something went wrong updating the Section";
        $_SESSION['alert'] = $error; 
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../admin/section_management.php");
    
    }
    
}else{
      $message = "Something went wrong updating the Section";
       $_SESSION['alert'] = $error; 
       $_SESSION['message'] =  $message;   //failed to insert
    header("Location: ../admin/section_management.php");
}




?>
