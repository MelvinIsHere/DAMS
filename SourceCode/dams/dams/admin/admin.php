<?php 
include "../config.php";
session_start();

 if(isset($_SESSION['unique_id']) && isset($_SESSION['user_id'])){
    $users_id = $_SESSION['unique_id'];
    $id = $_SESSION['user_id'];



    $data = mysqli_query($conn,"SELECT 
            u.user_id,
            u.unique_id,
            u.email,
            u.password,
            u.img,
            u.status,
            u.type,
            d.department_name,
            d.department_abbrv,
            d.department_id
            FROM users u
            LEFT JOIN departments d ON u.department_id = d.department_id
             WHERE user_id = '$id' 
    
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
<html lang="en">
<?php include "../header/header.php"; ?>

<body id="page-top">
    


    <!-- Page Wrapper -->
    <div id="wrapper">

       <?php include "../sidebar/sidebar.php"; ?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include "../topbar/topbar.php"?>
                <!-- Begin Page Content -->
                <div class="container-fluid tabcontent" id="dashboard" >

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Pending Task</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                
                                                    <?php
                                                        $pending_count = mysqli_query($conn,"  SELECT
                        COUNT(*)
                        FROM tasks tt
                        LEFT JOIN task_status_deans ts ON tt.task_id=ts.`task_id`
                        LEFT JOIN departments dp ON ts.`office_id`=dp.`department_id`
                        WHERE tt.for_ovcaa = 1  AND ts.`is_completed` = 1 ");
                                                        $result = mysqli_fetch_assoc($pending_count);
                                                        if($result){
                                                            echo $result['COUNT(*)'];
                                                        }
                                                        else{
                                                            echo "0";
                                                        }

                                                     ?>


                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Completed Task</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    <?php
                                                        $pending_count = mysqli_query($conn,"  SELECT
                        COUNT(*)
                        FROM tasks tt
                        LEFT JOIN task_status_deans ts ON tt.task_id=ts.`task_id`
                        LEFT JOIN departments dp ON ts.`office_id`=dp.`department_id`
                        WHERE tt.for_ovcaa = 1 AND ts.`is_completed` = 0");
                                                        $result = mysqli_fetch_assoc($pending_count);
                                                        if($result){
                                                            echo $result['COUNT(*)'];
                                                        }
                                                        else{
                                                            echo "0";
                                                        }

                                                     ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                     

                       
                    </div>

                    <!-- Content Row -->

                    <div class="row">

                   

                        <!-- Content Column -->
<div class="col-lg-12 mb-4"> <!-- Change the column width to occupy the full width -->
    <!-- Project Card Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Tasks Completion</h6>
        </div>
        <?php

            include "../php/config.php";

            $querySQL = "SELECT ROUND((COUNT(CASE WHEN tsd.is_completed = 0 THEN 1 END) / COUNT(*)) * 100) AS percentage_completed,
                            tsd.`status_id`,
                            tsd.`office_id`,
                            tsd.is_completed,
                            t.task_name
                            FROM task_status_deans tsd
                            LEFT JOIN tasks t ON t.task_id = tsd.`task_id`

                            GROUP BY t.`task_name`
                            ";

            $result = mysqli_query($conn,$querySQL);
            $facLoadingPercent = -1;
            $classSchedPercent = -1;
            $facultyScehdPercent = -1;
            $room_utilization_matrix_percent = -1;
            while($row = mysqli_fetch_array($result)){
                $task_name = $row['task_name'];
                $percentage = $row['percentage_completed'];
              
                if($task_name == 'Faculty Loading'){
                    $facLoadingPercent = $percentage;
                }
                elseif($task_name == 'Class Schedule'){
                    $classSchedPercent = $percentage;
                }
                elseif($task_name == 'Faculty Schedule'){
                    $facultyScehdPercent = $percentage;
                }elseif($task_name = 'Room Utilization Matrix'){
                    $room_utilization_matrix_percent = $percentage;
                }
            
            }
            if($facLoadingPercent < 0 && $classSchedPercent < 0 && $facultyScehdPercent < 0){
                ?>


                <div class="card-body">
                <center><p>NO WORK TO BE <dialog></dialog></p></center>
               
        </div>
        <?php
            }else{
               
         ?>
         <?php
            
            if($facLoadingPercent >= 0 && $facLoadingPercent <= 99){
                ?>
                    <div class="card-body">
            <h4 class="small font-weight-bold">Faculty Loading <span class="float-right">
                <?php
                echo $facLoadingPercent . "%";
                ?>
                </span>
            </h4>
            <div class="progress mb-4" style="width: 100%;"> <!-- Set the width to 100% to span the entire container -->
                <div class="progress-bar bg-danger" role="progressbar" style="width:<?php
                    // Create a MySQLi connection
                  echo $facLoadingPercent . "%";
                    ?>
                    "

                aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>






                <?php
            }else{
                
            }
          ?>












             <?php
        
            if($classSchedPercent >= 0 && $classSchedPercent <= 99){
                ?>
                    <div class="card-body">
            <h4 class="small font-weight-bold">Class Schedule <span class="float-right">
                <?php
                //percent here
                echo $classSchedPercent ."%";
                ?>
                </span>
            </h4>
            <div class="progress mb-4" style="width: 100%;"> <!-- Set the width to 100% to span the entire container -->
                <div class="progress-bar bg-danger" role="progressbar" style="width:
                <?php

                echo $classSchedPercent ."%";

                 ?>
                    "

                aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>






                <?php
            }else{
                
            }
          ?>






          <?php
        
            if($facultyScehdPercent >= 0 && $facultyScehdPercent <= 99){
                ?>
                    <div class="card-body">
            <h4 class="small font-weight-bold">Faculty Schedule <span class="float-right">
                <?php
                //percent here
                echo $facultyScehdPercent ."%";
                ?>
                </span>
            </h4>
            <div class="progress mb-4" style="width: 100%;"> <!-- Set the width to 100% to span the entire container -->
                <div class="progress-bar bg-danger" role="progressbar" style="width:
                <?php

                echo $facultyScehdPercent ."%";

                 ?>
                    "

                aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>






                <?php
            }else{
                
            }
        }
          ?>








            <?php
        
            if($room_utilization_matrix_percent >= 0 && $room_utilization_matrix_percent <= 99){
                ?>
                    <div class="card-body">
            <h4 class="small font-weight-bold">Room Utilization Matrix <span class="float-right">
                <?php
                //percent here
                echo $room_utilization_matrix_percent ."%";
                ?>
                </span>
            </h4>
            <div class="progress mb-4" style="width: 100%;"> <!-- Set the width to 100% to span the entire container -->
                <div class="progress-bar bg-danger" role="progressbar" style="width:
                <?php

                echo $room_utilization_matrix_percent ."%";

                 ?>
                    "

                aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>






                <?php
            }else{
                
            }
        
          ?>
    
     
    </div>
</div>


                    <!-- Content Row -->
                    <div class="row">

                       

                        <div class="col-lg-6 mb-4">

                        

                          

                        </div>
                    </div>

                  

                </div>







       



               

          
          
                <!-- /.container-fluid -->


            </div>

            <!-- End of Main Content -->
            
            <!-- Footer -->
<!--             <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer> -->
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

   
  

    <!-- Bootstrap core JavaScript-->
    <!--<script src="vendor/jquery/jquery.min.js"></script>-->
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!--<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>-->

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
    <!--<script src="js/demo/datatables-demo2.js"></script>-->
    <!--<script src="js/demo/viewTask_details.js"></script>-->
    <!--<script src="js/demo/faculty_table.js"></script>-->
    <!--<script src="js/demo/faculty_sched_table.js"></script>-->
     <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <!--<script src="vendor/datatables/jquery.dataTables.min.js"></script>-->
    <!--<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>-->


</body>

</html>
<?php 

}}else{
    header("Location: ../index.php");
}?>