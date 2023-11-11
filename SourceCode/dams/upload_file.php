<?php
session_start();
$users_id = $_SESSION['user_id'];
$dept_name = $_SESSION['dept_name'];

include "php/functions.php";
include "php/config.php";
$task_id = $_POST['task_id'];
$status_id = $_POST['task_status_id'];
$fileName = $_FILES['file']['name'];
$fileTmpName = $_FILES['file']['tmp_name'];
$path = "task_files/".$fileName;            
if(empty($fileName)){
    echo "Submission Fail No File Attached";
}else{
    $run =  insertQuery($fileName,$path,$users_id,$status_id);
    if($run != ""){
        move_uploaded_file($fileTmpName,$path);
        $office_id = getDepartmentId($dept_name);
        if(!$office_id){
            $update = update_status_task($status_id);
            if($update){
                $name = getName($users_id);
                if($name != ""){
                    $task_name = getTaskName($task_id);
                    if($task_name != ""){
                        $notif = notifications($dept_name,$task_name);
                        if($notif != ""){
                            $type = "Admin";
                            $user_notif = user_notif_ovcaa($users_id,$notif,$type);
                            if($user_notif != ""){                                                 
                                if(!empty($user_notif)){
                                    echo "Work has been successfully Submitted!";
                                }else{
                                    echo "Something Went wrong,Upload Upload Failed!1";
                                }                                                
                            }else{
                                echo "Something Went wrong,Upload Upload Failed!2";
                            }
                        }else{
                            echo "Something Went wrong,Upload Upload Failed!3";
                        }
                    }else{
                        echo "Something Went wrong,Upload Upload Failed!4";
                    }
                }else{
                    echo "Something Went wrong,Upload Upload Failed!5";
                }
            }else{
                echo "Something Went wrong,Upload Upload Failed!6";
            }
        }else{
            echo "Something Went wrong,Upload Upload Failed!7";
        }
    }else{
        echo "Something went wrong submittng the file8";
    }
}

function getDepartmentId($dept_name){
    include "config.php";
    $query = mysqli_query($conn,"SELECT department_id FROM departments WHERE department_name = '$dept_name'");
    if($query){
        if(mysqli_num_rows($query) > 0){
            $row = mysqli_fetch_assoc($query);
            $department_id = $row['department_id'];
            return $department_id;
        }else{  
            return false;
        }
    }else{
        return false;
    }
}
function update_status_task($status_id){
    include "config.php";

    $query = mysqli_query($conn,"UPDATE task_status_deans SET is_completed = '0' WHERE status_id = '$status_id'");
    if($query){
        return true;
    }else{
        return false;
    }
}



?>