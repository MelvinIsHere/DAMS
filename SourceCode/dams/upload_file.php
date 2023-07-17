<?php
session_start();
$users_id = $_SESSION['user_id'];
$dept_name = $_SESSION['dept_name'];

include "php/functions.php";

 $conn = new mysqli("localhost","root","","dams2");
     if($conn->connect_error){
        die('Connection Failed : ' .$conn->connect_error);
     }
  // if(isset($_POST['submit'])){
      $task_id = $_POST['task_id'];


    

      
      


      
        





            $fileName = $_FILES['file']['name'];
            $fileTmpName = $_FILES['file']['tmp_name'];
            $path = "task_files/".$fileName;
            
            if(empty($fileName)){
               echo "Submission Fail No File Attached";
               
            }
            else{
                $run =  insertQuery($fileName,$path,$users_id);
                if($run != ""){
                     move_uploaded_file($fileTmpName,$path);
                    $office_id = getDeptId($dept_name);
                    if($office_id != ""){
                         $update = updateTaskStats($office_id,$task_id);
                         if($update != ""){


                                 $name = getName($users_id);
                                 if($name != ""){
                                      $task_name = getTaskName($task_id);
                                      if($task_name != ""){
                                           $notif = notifications($name,$task_name);
                                           if($notif != ""){
                                               $user_notif = user_notif_dean($users_id,$notif);
                                               if($user_notif != ""){
                                                 $act_log = activity_log_submitted_documents($users_id,$task_name);
                                                 if($act_log != ""){
                                                    if(!empty($user_notif)){
                                                     echo "Work Submitted" . $office_id;
                                                            }
                                                            else{
                                                              echo "error";
                                                                    }

                                                 }else{
                                                    echo "Something went wrong";
                                                 }
                                                

                                              


                                                

                                           }else{
                                            echo "Something went wrong";
                                           }

                                               }else{
                                                echo "Something went wrong";
                                               }

                                               
                                
                             
                                      
                                      }else{
                                        echo "Something went wrong";
                                      }

                             
                                 }
                                 else{
                                    echo "Something went wrong";
                                 }
                              
                         }  
                         else{
                            echo "Something went wrong a";
                         }
                          
                    }else{
                        echo "Something Went wrong getting the data about the department";
                    }
                   

                }
                else{
                    echo "Something went wrong submittng the file";
                }
                  
             
              

               
               
                // header("Location: deans/deans.php?Success : File Submitted");
               
         
            
             
                

                  }
//}
   // code...

?>