<?php 
include "../config.php";
session_start();

 if(isset($_SESSION['unique_id']) && isset($_SESSION['user_id'])){
    $users_id = $_SESSION['unique_id'];
    $id = $_SESSION['user_id'];



    $data = mysqli_query($conn,"SELECT 
                                u.`email`,
                                u.`password`,
                                u.`type`,
                                u.`img`,
                                u.`unique_id`,
                                u.`user_id`,
                                f.`department_id`,
                                
                                f.`firstname`,
                                f.`middlename`,
                                f.`lastname`,
                                f.`suffix`,
                                d.`designation`,
                                f.`position`,
                                dp.`department_abbrv`,
                                dp.`department_name`,
                                dp.`department_id`

                            FROM users u 
                            LEFT JOIN faculties f ON f.`faculty_id` = u.faculty_id
                            LEFT JOIN departments dp ON dp.`department_id` = f.`department_id`
                            LEFT JOIN designation d ON d.designation_id = f.designation_id
                            WHERE u.user_id = '$id'
    
            ");
    

   

    if($data){
        $row = mysqli_fetch_assoc($data);
         $department_name = $row['department_name'];
         $department_id = $row['department_id'];
        $img = $row['img'];
        $type =$row['type'];
        $department_abbrv = $row['department_abbrv'];
        $email = $row['email'];
        $term_id = $_SESSION['term_id'];



?>
<!DOCTYPE html>
<html>
<?php  include "../header/header_staffs.php"?>
<body>
    <div id="wrapper">
    <?php
     include "../sidebar/sidebar_staff.php";
     ?>
  <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
    <?php include "../topbar/topbar_staffs.php"; ?>
    <div class="container-fluid tabcontent" id="pendingDocu">
    <!-- Page Heading -->


      <div class="container-fluid tabcontent" id="viewMonitoring">
                <div>
                                        <!-- Page Heading -->
                    <h1 class="h3 mb-1 text-gray-800">Pending Documents</h1>
                    <div class="row">
                     <?php 
             $conn = new mysqli("localhost", "root", "", "dams2");
            if ($conn->connect_error) {
                    die("Connection failed : " . $conn->connect_error);
            }

            // $dept_name_sql = mysqli_query($conn,"SELECT department_abbrv FROM departments WHERE department_name = '$department_name'");
            // $dept_res = mysqli_fetch_assoc($dept_name_sql);

            // $dept_name = $dept_res['department_abbrv'];

            // echo $dept_name;


                $currentDate = date('Y-m-d');

                $sql = "SELECT
                        tt.task_name AS task_name,
                        tt.date_posted AS date_posted,
                        tt.due_date AS due_date,
                        ts.`is_completed`,
                        ts.task_id AS task_id,
                        ts.status_id
                        FROM tasks tt
                        LEFT JOIN task_status_deans ts ON tt.task_id=ts.`task_id`
                        LEFT JOIN users u ON u.`user_id`=ts.`user_id`
                        WHERE u.`user_id` = '$id' AND ts.`is_completed` = 1
                        AND tt.due_date >= '$currentDate'
                        AND tt.term_id = '$term_id'";
                $result = $conn->query($sql);

                while($row = mysqli_fetch_array($result)){
                    $taskid = $row['task_id'];
                    $taskName = $row['task_name'];
                    $posted = $row['date_posted'];
                    $deadline = $row['due_date'];
                     $status_id = $row['status_id'];
                    
                    
                
            ?>


 <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="card  rounded-lg shadow-lg mb-4" style="border-color:#A52A2A">
            <div class="card-body">
                <h4 class="card-title mb-3 "><?php echo $taskName; ?></h4>
                <p class="card-text text-muted mb-2">Due Date: <?php echo $deadline; ?></p>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <a href="pending.php?id=<?php echo $taskid;?>&status_id=<?php echo $status_id;?>" class="btn btn-danger btn-sm">Upload</a>
                    <a href="navigator.php?name=<?php echo $taskName; ?>" class="btn btn-outline-danger btn-sm"style="border-color:#A52A2A">View Task</a>
                </div>
            </div>
        </div>
    </div>

                    
                    
            <?php }?>
                   
                </div>
            </div>
        </div>
</div>

    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>



<?php }
}?>
</body>
</html>