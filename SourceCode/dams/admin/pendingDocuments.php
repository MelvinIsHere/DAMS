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
                                f.`designation`,
                                f.`firstname`,
                                f.`middlename`,
                                f.`lastname`,
                                f.`suffix`,
                                f.`designation`,
                                f.`position`,
                                dp.`department_abbrv`,
                                dp.`department_name`,
                                dp.`department_id`

                            FROM users u 
                            LEFT JOIN faculties f ON f.`faculty_id` = u.faculty_id
                            LEFT JOIN departments dp ON dp.`department_id` = f.`department_id`
                            WHERE u.user_id = '$id'
    
            ");
    

   

    if($data){
        $row = mysqli_fetch_assoc($data);
         $department_name = $row['department_name'];
         $department_id = $row['department_id'];
        $img = $row['img'];
        $type =$row['type'];
        $department_abbrv = $row['department_abbrv'];



?>
<!DOCTYPE html>
<html>
<?php  include "../header/header.php"?>
<body>
    <div id="wrapper">
    <?php
     include "../sidebar/sidebar.php";
     ?>
  <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
    <?php include "../topbar/topbar.php"; ?>
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>-->
    <div class="container-fluid tabcontent" id="pendingDocu">
    <!-- Page Heading -->
   
      <div class="container-fluid tabcontent" id="viewMonitoring">
                <div>
                                        <!-- Page Heading -->
                    <h1 class="h3 mb-1 text-gray-800">Pending Tasks</h1>
                    <div class="row">
                     <?php 
            //  $conn = new mysqli("localhost", "root", "", "dams2");
  
            // if ($conn->connect_error) {
            //         die("Connection failed : " . $conn->connect_error);
            // }
        include "../config.php";
         




                $sql = " SELECT
                        tt.task_name AS task_name,
                        tt.date_posted AS date_posted,
                        tt.due_date AS due_date,
                        ts.`is_completed`,
                        ts.task_id AS task_id,
                        ts.status_id
                        FROM tasks tt
                        LEFT JOIN task_status_deans ts ON tt.task_id=ts.`task_id`
                        LEFT JOIN departments dp ON ts.`office_id`=dp.`department_id`
                        WHERE tt.for_ovcaa = 1 AND ts.`is_completed` = 1
                        GROUP BY due_date,task_name";
                $result = $conn->query($sql);
                while($row = mysqli_fetch_array($result)){
                    $taskid = $row['task_id'];
                    $taskName = $row['task_name'];
                    $posted = $row['date_posted'];
                    $deadline = $row['due_date'];
                    $status_id = $row['status_id'];
                    
                    
                
            ?>

            <!--         <div class="col">
                        <div class="card" id="cardbtn" style="margin-top:20px">
                            <div class="card-body" id="card-body" style="height: 140px;  width: auto;">
                                <div class="task_info" style="white-space: nowrap;">
                                    <h3 class="h3 mb-1 text-gray-800"><?php echo $taskName; ?></h3>
                                    <p class="task_info_text"><span>Due Date: <?php echo $deadline; ?></span></p>
                                    
                                </div>
                                <div class="d-flex flex-column" style="display:inline-flex;">
                                    <a href="pending.php?id=<?php echo $taskid;?>" class="btn btn-primary btn-sm" id="viewTaskbtn">Upload</a>
                                    <a href="navigator.php?name=<?php echo $taskName; ?>" class="btn btn-primary btn-sm" id="viewTaskbtn">View Assigned Task</a>
                                </div>
                            </div>
                            
                        </div>
                    </div> -->

      <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="card border-primary rounded-lg shadow-lg mb-4"  style="border-color:#A52A2A">
            <div class="card-body">
                <h4 class="card-title mb-3 text-primary"><?php echo $taskName; ?></h4>
                <p class="card-text text-muted mb-2">Due Date: <?php echo $deadline; ?></p>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <a href="pending.php?id=<?php echo $taskid;?>&status_id=<?php echo $status_id;?>" class="btn btn-danger btn-sm">Upload</a>
                    <a href="navigator.php?name=<?php echo $taskName; ?>?>" class="btn btn-outline-danger btn-sm">View Task</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Repeat the above code block three more times -->
    <!-- ... -->


        
            <?php }?>
                   
                </div>
            </div>
        </div>
</div>

    <!-- Bootstrap core JavaScript-->
    <!--<script src="vendor/jquery/jquery.min.js"></script>-->
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <!--<script src="vendor/jquery-easing/jquery.easing.min.js"></script>-->
 
    <!-- Custom scripts for all pages-->
    <!--<script src="js/sb-admin-2.min.js"></script>-->

    <!-- Page level plugins -->
    <!--<script src="vendor/chart.js/Chart.min.js"></script>-->

    <!-- Page level custom scripts -->
    <!--<script src="js/demo/chart-area-demo.js"></script>-->
    <!--<script src="js/demo/chart-pie-demo.js"></script>-->

    <!--<script src="js/demo/datatables-demo.js"></script>-->
    <!--<script src="js/demo/viewTask_details.js"></script>-->
    <!--<script src="js/demo/faculty_loading.js"></script>-->
    <!--<script src="js/demo/faculty_sched_table.js"></script>-->
     <!-- Custom scripts for all pages-->
    <!--<script src="js/sb-admin-2.min.js"></script>-->

    <!-- Page level plugins -->
    <!--<script src="vendor/datatables/jquery.dataTables.min.js"></script>-->
    <!--<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>-->
    <script src="js/sb-admin-2.min.js"></script>


<?php }
}
else{

    header("Location: ../index.php");
}?>
</body>
</html>