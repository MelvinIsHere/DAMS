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
    
    $ovcaa = isset($_POST['ovcaa']) ? $_POST['ovcaa'] : null;
    if($deans == 1 ||  $ovcaa == 1 ){

        if ($deans == 1 && $ovcaa == 1) {
                $doc_temp_id = getTemplateId($task_name);
              $id = adminInsertTask($task_name,$description,$doc_temp_id,$dateStart,$dateEnd,$ovcaa,$deans); //insert task into the deans


                $sql = "SELECT department_id FROM departments";
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




        else if($deans == 1 && $ovcaa != 1){
            $doc_temp_id = getTemplateId($task_name);
              $id = adminInsertTask($task_name,$description,$doc_temp_id,$dateStart,$dateEnd,$ovcaa,$deans); 

           


                $sql = "SELECT department_id FROM departments WHERE department_abbrv != 'OVCAA'";
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
        else{
$doc_temp_id = getTemplateId($task_name);
              $id = adminInsertTask($task_name,$description,$doc_temp_id,$dateStart,$dateEnd,$ovcaa,$deans); 
                echo $id;
                $sql = "SELECT department_id FROM departments WHERE department_abbrv = 'OVCAA'";
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

  

        // header("Location: generate_task.php?success=TaskUploaded");
        echo "success";

        }

   
    }
    else{
        // header("Location: generate_task.php?error=department required");
        echo "no";
    }


$conn->close();
                                           
   ?>