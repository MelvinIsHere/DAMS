<?php
session_start();
$users_id = $_SESSION['user_id'];
$status_id = $_GET['id'];
$task_name = $_GET['task_name'];
echo $task_name;
echo $status_id;

include "config.php";
include "php/functions.php";
 
 $update = mysqli_query($conn,"UPDATE task_status SET is_completed = 1 WHERE status_id = '$status_id'");

    $name = getName($users_id);
    
    

    $notif = notifications_return($name,$task_name);
                
    $user_notif = user_notif_dean($users_id,$notif);

   $act_log = activity_log_submitted_documents($users_id,$task_name);


   echo "<script>alert('Work Returned!')
   			window.location.href = 'admin/submission_monitoring.php';</script>"







?>