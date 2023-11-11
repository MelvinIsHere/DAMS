<?php
session_start();
$users_id = $_SESSION['user_id'];

include "php/functions.php";
include "php/config.php";
 // $conn = new mysqli("localhost","root","","dams2");
 //     if($conn->connect_error){
 //        die('Connection Failed : ' .$conn->connect_error);
 //     }
  if(isset($_POST['submit'])){
      $task_id = $_POST['task_id'];


    

      
      


      
        





            $fileName = $_FILES['file']['name'];
            $fileTmpName = $_FILES['file']['tmp_name'];
            $path = "task_files/".$fileName;
            if(empty($fileName)){
               header("Location: admin/admin.php?Error : Submittion Fail No File attached");
            }
            else{
             
              $run =  insertQuery($fileName,$path,$users_id);
              echo $run;


                move_uploaded_file($fileTmpName,$path);
                $update = updateTaskStats($task_id);
                echo $update;

                $name = getName($users_id);
                $task_name = getTaskName($task_id);

                $notif = notifications($name,$task_name);
                echo $notif;




                
                  
                header("Location: admin/admin.php?Success: file Successully Submitted");
               
             
            }
            
        
            
            
             
                

}

else{
   echo "Error: " . mysqli_error($conn);
   echo "sad";
}

   // code...

?>