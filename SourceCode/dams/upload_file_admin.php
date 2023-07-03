<?php
session_start();
$users_id = $_SESSION['user_id'];
echo $users_id;

 $conn = new mysqli("localhost","root","","dams2");
     if($conn->connect_error){
        die('Connection Failed : ' .$conn->connect_error);
     }
  if(isset($_POST['submit'])){
      $task_id = $_POST['task_id'];


    

      
      


      
        





            $fileName = $_FILES['file']['name'];
            $fileTmpName = $_FILES['file']['tmp_name'];
            $path = "task_files/".$fileName;
            if(empty($fileName)){
               header("Location: admin/admin.php?Error : Submittion Fail No File attached");
            }
            else{
               $query = "INSERT INTO file_table(file_name,directory,file_owner_id) VALUES ('$fileName','$path','$users_id')";
             $run = mysqli_query($conn,$query);
             if($run){
                move_uploaded_file($fileTmpName,$path);
                $update = mysqli_query($conn,"UPDATE task_status SET is_completed = '0' WHERE task_id = '$task_id'");
                if($update){
                  
                header("Location: admin/admin.php?Success: file Successully Submitted");
               }
               else{
                  echo "Error: " . mysqli_error($conn);
               }
             }
             else{
                 echo "Error: " . mysqli_error($conn);
             }
            }
            
        
            
            
             
                

}

else{
   echo "Error: " . mysqli_error($conn);
   echo "sad";
}

   // code...

?>