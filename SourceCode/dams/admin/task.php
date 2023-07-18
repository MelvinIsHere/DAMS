 <?php 
 session_start();
 $users_id = $_SESSION['user_id'];
$conn = new mysqli("localhost", "root", "", "dams2");
include "../php/functions.php";
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

    $task_name = $_POST['task_name'];
    $description = $_POST['description'];
    $dateStart = $_POST['dateStart'];
    $dateEnd = $_POST['dateEnd'];
    $deans = isset($_POST['dean']) ? $_POST['dean'] : null;
    $heads = isset($_POST['heads']) ? $_POST['heads'] : null;
    
    $ovcaa = isset($_POST['ovcaa']) ? $_POST['ovcaa'] : null;
    if($deans == 1 ||  $ovcaa == 1 || $heads == 1 ){
        $admin_type = "Admin";
        $head_type = "Heads";
        $dean_type = "Dean";

        if ($deans == 1 && $ovcaa == 1 && $heads == 1) {
                $doc_temp_id = getTemplateId($task_name);
              $id = adminInsertTask($task_name,$description,$doc_temp_id,$dateStart,$dateEnd,$ovcaa,$deans,$heads); //insert task into the deans


                $sql = "SELECT department_id FROM departments";
                $result = mysqli_query($conn,$sql);
                if ($result) {
                    while($row = mysqli_fetch_array($result)){
                        $dept_id = $row['department_id'];
                        $sql = mysqli_query($conn,"INSERT INTO task_status_deans(task_id,office_id,is_completed)VALUES('$id','$dept_id',1)");
        }
        $notif_id = insertTaskNotification($task_name); //insert task notifications to others
        $user_notif = user_notif_all($users_id,$notif_id,$dean_type,$admin_type,$head_type); // insert thee status
        $act_log = activity_log($users_id);
    }

  

        // header("Location: admin.php?success=TaskUploaded");
    echo "success";
        }




        else if($deans == 1 && $ovcaa != 1 && $heads != 1){
            $doc_temp_id = getTemplateId($task_name);
              $id = adminInsertTask($task_name,$description,$doc_temp_id,$dateStart,$dateEnd,$ovcaa,$deans,$heads); 

           


                $sql = "SELECT d.`department_id` FROM departments d
                        LEFT JOIN users u ON u.`department_id` = d.`department_id`
                        WHERE u.`type` = 'Dean'";
                $result = mysqli_query($conn,$sql);
                if ($result) {
                    while($row = mysqli_fetch_array($result)){
                        $dept_id = $row['department_id'];
                        $sql = mysqli_query($conn,"INSERT INTO task_status_deans(task_id,office_id,is_completed)VALUES('$id','$dept_id',1)");
        }
        $notif_id = insertTaskNotification($task_name); //insert task notifications to others
        $user_notif = user_notif_dean_only($users_id,$notif_id,$dean_type); // insert thee status
        $act_log = activity_log($users_id);
    }

  

        // header("Location: admin.php?success=TaskUploaded");
    echo "success";
        }

        else if($deans == 1 && $ovcaa == 1 && $heads != 1){
            $doc_temp_id = getTemplateId($task_name);
              $id = adminInsertTask($task_name,$description,$doc_temp_id,$dateStart,$dateEnd,$ovcaa,$deans,$heads); 

           


                $sql = "SELECT d.`department_id` FROM departments d
                        LEFT JOIN users u ON u.`department_id` = d.`department_id`
                        WHERE u.`type` = 'Dean' OR u.`type` = 'Admin'";
                $result = mysqli_query($conn,$sql);
                if ($result) {
                    while($row = mysqli_fetch_array($result)){
                        $dept_id = $row['department_id'];
                        $sql = mysqli_query($conn,"INSERT INTO task_status_deans(task_id,office_id,is_completed)VALUES('$id','$dept_id',1)");
        }
        $notif_id = insertTaskNotification($task_name); //insert task notifications to others
        
        $user_notif = user_notif_dean_ovcaa($users_id,$notif_id,$dean_type,$admin_type); // insert thee status
        $act_log = activity_log($users_id);
    }

  

        // header("Location: admin.php?success=TaskUploaded");
    echo "success";
        }


          else if($deans == 1 && $ovcaa != 1 && $heads == 1){
            $doc_temp_id = getTemplateId($task_name);
              $id = adminInsertTask($task_name,$description,$doc_temp_id,$dateStart,$dateEnd,$ovcaa,$deans,$heads); 

           


                $sql = "SELECT d.`department_id` FROM departments d
                        LEFT JOIN users u ON u.`department_id` = d.`department_id`
                        WHERE u.`type` = 'Dean' OR u.`type` = 'Heads'";
                $result = mysqli_query($conn,$sql);
                if ($result) {
                    while($row = mysqli_fetch_array($result)){
                        $dept_id = $row['department_id'];

                        $sql = mysqli_query($conn,"INSERT INTO task_status_deans(task_id,office_id,is_completed)VALUES('$id','$dept_id',1)");
        }
        $notif_id = insertTaskNotification($task_name); //insert task notifications to others
        $type1 = "Dean";
        $type2 = "Heads";
        $user_notif = user_notif_dean_heads($users_id,$notif_id,$type1,$type2); // insert thee status
        $act_log = activity_log($users_id);
    }

  

        // header("Location: admin.php?success=TaskUploaded");
    echo "success";
        }

         else if($deans != 1 && $ovcaa == 1 && $heads == 1){
            $doc_temp_id = getTemplateId($task_name);
              $id = adminInsertTask($task_name,$description,$doc_temp_id,$dateStart,$dateEnd,$ovcaa,$deans,$heads); 

           


                $sql = "SELECT d.`department_id` FROM departments d
                        LEFT JOIN users u ON u.`department_id` = d.`department_id`
                        WHERE u.`type` = 'Admin' OR u.`type` = 'Heads'";
                $result = mysqli_query($conn,$sql);
                if ($result) {
                    while($row = mysqli_fetch_array($result)){
                        $dept_id = $row['department_id'];
                        $sql = mysqli_query($conn,"INSERT INTO task_status_deans(task_id,office_id,is_completed)VALUES('$id','$dept_id',1)");
        }
        $notif_id = insertTaskNotification($task_name); //insert task notifications to others
        $user_notif = user_notif_dean($users_id,$notif_id); // insert thee status
        $act_log = activity_log($users_id);
    }

  

        // header("Location: admin.php?success=TaskUploaded");
    echo "success";
        }


         else if($deans != 1 && $ovcaa != 1 && $heads == 1){
            $doc_temp_id = getTemplateId($task_name);
              $id = adminInsertTask($task_name,$description,$doc_temp_id,$dateStart,$dateEnd,$ovcaa,$deans,$heads); 

           


                $sql = "SELECT d.`department_id` FROM departments d
        LEFT JOIN users u ON u.`department_id` = d.`department_id`
        WHERE u.`type` = 'Heads'";
$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_array($result)) {
        $dept_id = $row['department_id'];
        $query = "INSERT INTO task_status_deans (task_id, office_id, is_completed) VALUES ('$id', '$dept_id', 1)";
        if (mysqli_query($conn, $query)) {
            echo "Task status inserted for department ID: $dept_id<br>";
        } else {
            echo "Error inserting task status for department ID: $dept_id. Error: " . mysqli_error($conn) . "<br>";
        }
    }

    $notif_id = insertTaskNotification($task_name); //insert task notifications to others
    $type = 'Heads';
    $user_notif = user_notif_dean($users_id, $notif_id,$type); // insert the status
    $act_log = activity_log($users_id);
} 
}







        else{
$doc_temp_id = getTemplateId($task_name);
              $id = adminInsertTask($task_name,$description,$doc_temp_id,$dateStart,$dateEnd,$ovcaa,$deans,$heads); 
                echo $id;
                $sql = "SELECT d.`department_id` FROM departments d
                        LEFT JOIN users u ON u.`department_id` = d.`department_id`
                        WHERE u.`type` = 'Admin'";
                $result = mysqli_query($conn,$sql);
                if ($result) {
                    while($row = mysqli_fetch_array($result)){
                        $dept_id = $row['department_id'];
                        $sql = mysqli_query($conn,"INSERT INTO task_status_deans(task_id,office_id,is_completed)VALUES('$id','$dept_id',1)");
        }
         $notif_id = insertTaskNotification($task_name); //insert task notifications to others
        $user_notif = user_notif_dean($users_id,$notif_id,$admin_types); // insert thee status
        $act_log = activity_log($users_id);
    }

  

        // header("Location: generate_task.php?success=TaskUploaded");
        echo "success";

        }

   
    }
    else{
        // header("Location: generate_task.php?error=department required");
        echo "Something Went wrong";
    }


$conn->close();
                                           
   ?>