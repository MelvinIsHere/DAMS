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
        $office_id = getDeptId($dept_name);
        if($office_id != ""){
            $update = updateTaskStats($users_id,$task_id,$status_id);
            if($update == "success"){
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
                                    echo "Something Went wrong,Upload Upload Failed!";
                                }                                                
                            }else{
                                echo "Something Went wrong,Upload Upload Failed!";
                            }
                        }else{
                            echo "Something Went wrong,Upload Upload Failed!";
                        }
                    }else{
                        echo "Something Went wrong,Upload Upload Failed!";
                    }
                }else{
                    echo "Something Went wrong,Upload Upload Failed!";
                }
            }else{
                echo "Something Went wrong,Upload Upload Failed!";
            }
        }else{
            echo "Something Went wrong,Upload Upload Failed!";
        }
    }else{
        echo "Something went wrong submittng the file";
    }
}


?>