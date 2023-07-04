 <?php 
$conn = new mysqli("localhost", "root", "", "dams2");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_name = $_POST['task_name'];
    $description = $_POST['description'];
    $dateStart = $_POST['dateStart'];
    $dateEnd = $_POST['dateEnd'];
    $deans = isset($_POST['dean']) ? $_POST['dean'] : null;
    
    $ovcaa = isset($_POST['ovcaa']) ? $_POST['ovcaa'] : null;
    if($deans == 1 ||  $ovcaa == 1 ){
        if($deans == 1 && $ovcca != 1){

             $conn -> query("INSERT INTO tasks (task_name, task_desc, date_posted, due_date, for_ovcaa,for_deans, for_heads) VALUES ('$task_name','$description', '$dateStart', '$dateEnd','$ovcaa', '$deans', '$department')");
                // $result = $conn->query($sql);
                // Print auto-generated id
                $id = $conn -> insert_id;
                
                $sql = "SELECT department_id FROM departments WHERE department_abbrv != 'OVCAA'";
                $result = mysqli_query($conn,$sql);
                if ($result) {
                    while($row = mysqli_fetch_array($result)){
                        $dept_id = $row['department_id'];
                        $sql = mysqli_query($conn,"INSERT INTO task_status(task_id,office_id,is_completed)VALUES('$id','$dept_id',1)");
        }
    }

  

        header("Location: admin.php?success=TaskUploaded");
        }
        else{

            $conn -> query("INSERT INTO tasks (task_name, task_desc, date_posted, due_date, for_ovcaa,for_deans) VALUES ('$task_name','$description', '$dateStart', '$dateEnd','$ovcaa', '$deans')");
                
                $id = $conn -> insert_id;
                echo $id;
                $sql = "SELECT department_id FROM departments WHERE department_abbrv = 'OVCAA'";
                $result = mysqli_query($conn,$sql);
                if ($result) {
                    while($row = mysqli_fetch_array($result)){
                        $dept_id = $row['department_id'];
                        $sql = mysqli_query($conn,"INSERT INTO task_status(task_id,office_id,is_completed)VALUES('$id','$dept_id',1)");
        }
    }

  

        header("Location: admin.php?success=TaskUploaded");

        }

   
    }
    else{
        header("Location: admin.php?error=department required");
    }
}
     else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }


$conn->close();
                                           
   ?>