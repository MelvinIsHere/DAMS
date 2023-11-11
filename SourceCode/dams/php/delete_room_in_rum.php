<?php 
session_start();
$error = "error";
$success = "success";
include "config.php";
$rum_id = $_POST['loading_id'];


$sql = mysqli_query($conn, "DELETE FROM room_utilization_matrixes WHERE rum_id = '$rum_id'");
if ($sql) {
             $_SESSION['alert'] = $success; 
        $message = "Room has been successfully removed!";    
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../deans/room_utilization_matrix_ui.php");
} else {
             $_SESSION['alert'] = $error; 
        $message = "Something went wrong! room removal failed!";    
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../deans/room_utilization_matrix_ui.php");
}
?>
