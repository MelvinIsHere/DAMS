<?php
session_start();
$users_id = $_SESSION['user_id'];
$task_owner_id = $_POST['user_id'];
$status_id = $_POST['status_id'];
$task_name = $_POST['task_name'];
$user_id = $_POST['user_id'];
$file_id = $_POST['file_id'];
$remarks = $_POST['remarks'];


include "config.php";
//validate if there are data 
$delete_file = deletefile($file_id);
if($delete_file){
    $validate =mysqli_query($conn,"SELECT * FROM task_status_deans WHERE status_id = '$status_id' AND is_completed = 0");
 if(mysqli_num_rows($validate) > 0){
//update the task status
 $update = mysqli_query($conn,"UPDATE task_status_deans SET is_completed = 1 WHERE status_id = '$status_id'");
 if($update){
     //if update then get the name of the department
    $name = getName($users_id);
    if($name != ""){
        
        
    $notif_id = notifications_return($task_name,$remarks); //insert new notifications about the document are returned
    $user_notif_update = user_notif_update($user_id,$notif_id); //insert new user notifications
    if($user_notif_update != ""){
        if($notif_id != ""){

                  
        
           echo "<script>alert('Work Returned!')
                    window.location.href = 'admin/submission_monitoring.php';</script>";
              }
              

            
        
    

 
        
    
    else{
           echo "<script>alert('Something Went Wrong3!')
            window.location.href = 'admin/submission_monitoring.php';</script>";
    }
    }else{
        
        
        echo "<script>alert('Something Went Wrong!4')
            window.location.href = 'admin/submission_monitoring.php';</script>";
        
        
        
        
        
    }
    
                
    

        
    }
    
    else{
         echo "<script>alert('Something Went Wrong!')
            window.location.href = 'admin/submission_monitoring.php';</script>";
        
    }    



 }
 else{
      echo "<script>alert('There are no work to return!')
            window.location.href = 'admin/submission_monitoring.php';</script>";

 }



 }
 else{
     
      echo "<script>alert('There are no work to return!')
            window.location.href = 'admin/submission_monitoring.php';</script>";
 }

}else{
     // echo "<script>alert('There are no work to return!')
     //        window.location.href = 'admin/submission_monitoring.php';</script>";
}
 
     
 function deletefile($file_id){
    include "config.php";
    $query = mysqli_query($conn,"DELETE FROM file_table WHERE file_id = '$file_id'");
    if($query){
        return true;
    }else{
        return false;
    }
 }
function notifications_return($task_name,$remarks){
    include "config.php";
    $content = "Admin has return your task " . $task_name . " ".$remarks ;
    $sql = mysqli_query($conn,"INSERT INTO notifications(content,is_task) VALUES('$content','yes')");
    if(!$sql){
        return "";
        
    }
    else{
        return $conn->insert_id;
    }

}
function user_notif_update($user_id,$notif_id){
    include "config.php";
    $sql = mysqli_query($conn,"INSERT INTO user_notifications(status,notif_id,user_id) VALUES(0,'$notif_id','$user_id')");
    if(!$sql){
        return "";
    }
    else{
        return "success";
    }
}
function getName($user_id){
    include "config.php";
    $sql = mysqli_query($conn,"SELECT department_name FROM departments WHERE $user_id = '$user_id'");
    $info = mysqli_fetch_assoc($sql);
    if ($info) {
        $name =  $info['department_name'];
        return $name;
    }
    else{
        
        return "";
    }

}
?>