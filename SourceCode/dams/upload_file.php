<?php
session_start();
$users_id = $_SESSION['user_id'];

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
                  
             
               move_uploaded_file($fileTmpName,$path);
                $update = updateTaskStats($task_id);
                
                 $name = getName($users_id);
                $task_name = getTaskName($task_id);

                $notif = notifications($name,$task_name);
                
                $user_notif = user_notif_dean($users_id,$notif);
                

              


                if(!empty($user_notif)){
                     echo "Work Submitted";
                }
                else{
                  echo "error";
                }

               
               
                // header("Location: deans/deans.php?Success : File Submitted");
               
         
            
             
                

                  }
//}
   // code...

?>