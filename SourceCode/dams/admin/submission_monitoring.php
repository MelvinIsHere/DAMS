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
        <div class="container-fluid tabcontent" id="viewMonitoring">
                <div>
                                        <!-- Page Heading -->
                    <h1 class="h3 mb-1 text-gray-800">Submission Monitoring</h1>

                    
                    
                  <!--   <div class="col">
                        <div class="card" style="margin-top:20px">
                            <div class="card-body" id="card-body" style="height: 140px;  width:auto;">
                                <div class="task_info" style="white-space: nowrap;">
                                    <h3 class="h3 mb-1 text-gray-800">Faculty Loading</h3>
                                    <p class="task_info_text"><span>Due Date: June 30, 2023</span></p>
                                    
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="faculty_loading_monitoring_ui.php" class="btn btn-primary btn-sm" id="viewTaskbtn"  style="margin-top: 40px; margin-right: 50px;">View Assigned Task</a>
                                </div>
                            </div>
                            
                        </div>
                    </div> -->
                    <?php
                    $sem_id = $_SESSION['semester_id'];
                    $acad_id = $_SESSION['acad_id'];

                    
                       $sql = "
                        
                        
                       SELECT 
                         tt.task_name AS task_name,
                        tt.date_posted AS date_posted,
                        tt.due_date AS due_date,
                        ts.`is_completed`,
                        ts.task_id AS task_id,
                        ts.status_id
                        FROM tasks tt
                        LEFT JOIN task_status_deans ts ON tt.task_id=ts.`task_id`
                        LEFT JOIN departments dp ON ts.`office_id`=dp.`department_id`
                        WHERE term_id = '$term_id'
                        GROUP BY due_date,task_name";
                        $result = $conn->query($sql);
                        while($row = mysqli_fetch_array($result)){
                            $taskid = $row['task_id'];
                            $taskName = $row['task_name'];
                            $posted = $row['date_posted'];
                            $deadline = $row['due_date'];
                            $count = 1;
                            
                            
                
            

                     ?>
                    <div class="container-fluid">
                        <div class="card">
                            <div class="card-body">
                                <div class="task_info">
                                    <h4 class=" mb-1 text-gray-800"><?php echo $taskName; ?></h4>
                                    
                                </div>
                                <div class="task_info">
                                    <p class="task_info_text"><span>Date : <?php echo $deadline; ?> </span></p>
                                </div>
                                <div class="d-flex flex-column" style="margin-right: 30px">
                                    <a href="<?php
                                   switch ($taskName) {
                                          case 'Faculty Loading':
                                            echo "faculty_loading_monitoring_ui.php?due_date=$deadline";
                                            break;
                                          case 'Class Schedule':
                                            echo "class_schedule_monitoring_ui.php?due_date=$deadline";
                                            break;
                                          case 'Faculty Schedule':
                                            echo "faculty_schedule_monitoring_ui.php?due_date=$deadline";
                                            break;
                                          case 'Room Utilization Matrix':
                                            echo "room_utilization_matrix_monitoring_ui.php?due_date=$deadline";
                                            break;
                                          case 'OPCR':
                                            echo "opcr_monitoring_ui.php?due_date=$deadline";
                                            break;
                                            
                                          default:
                                            echo "submission_monitoring.php";
                                        }

                                    ?>


                                   " class="btn btn-danger btn-md">view Task</a>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <?php $count = $count+1;}?>

                   
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