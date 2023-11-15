<?php 
include "../config.php";
session_start();

 if(isset($_SESSION['unique_id']) && isset($_SESSION['user_id'])){
    $users_id = $_SESSION['unique_id'];
    $user_id = $_SESSION['user_id'];



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
                            WHERE u.user_id = '$user_id'
    
            ");
    

   

    if($data){
        $row = mysqli_fetch_assoc($data);
         $department_name = $row['department_name'];
         $department_id = $row['department_id'];
        $img = $row['img'];
        $type =$row['type'];
        $department_abbrv = $row['department_abbrv'];
        $email = $row['email'];



?>
<!DOCTYPE html>
<html lang="en">
<?php include "../header/header_heads.php"; ?>

<body id="page-top">



    <!-- Page Wrapper -->
    <div id="wrapper">

       <?php include "../sidebar/sidebar_heads.php"; ?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include "../topbar/topbar_heads.php"?>
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
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                <h6><strong>Pending Task</strong></h6>
                                                </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <center>
                                                   
                                                    <?php
                                                        $pending_count = mysqli_query($conn,"  SELECT
                        COUNT(*)
                        FROM tasks tt
                        LEFT JOIN task_status_deans ts ON tt.task_id=ts.`task_id`
                        LEFT JOIN departments dp ON ts.`office_id`=dp.`department_id`
                        WHERE tt.for_heads = 1 AND dp.department_id = '$department_id' AND ts.`is_completed` = 1
                        OR ts.user_id = '$user_id'");
                                                        $result = mysqli_fetch_assoc($pending_count);
                                                        if($result){
                                                            echo $result['COUNT(*)'];
                                                        }
                                                        else{
                                                            echo "0";
                                                        }

                                                     ?>

                                                        </center>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-book fa-2x text-danger"></i>
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
                                                <h6><strong>Completed Task</strong></h6>
                                                </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <center>
                                                    <?php
                                                        $pending_count = mysqli_query($conn,"  SELECT
                                        COUNT(*)
                                       FROM tasks tt
                        LEFT JOIN task_status_deans ts ON tt.task_id=ts.`task_id`
                        LEFT JOIN departments dp ON ts.`office_id`=dp.`department_id`
                        WHERE tt.for_heads = 1 AND dp.department_id = '$department_id' AND ts.`is_completed` = 0
                        OR ts.user_id = '$user_id'");
                                                        $result = mysqli_fetch_assoc($pending_count);
                                                        if($result){
                                                            echo $result['COUNT(*)'];
                                                        }
                                                        else{
                                                            echo "0";
                                                        }

                                                     ?></center></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-check fa-2x text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                     

                       
                    </div>

                        <!-- Content Row -->

                    <div class="row">

                   
                         <!-- Content Column -->
                       <div class="container-fluid">
    <div class="row" >
        <div class="col-lg-12 mb-12" > <!-- Change the column width to occupy the full width -->
            <!-- Project Card Example -->
            <div class="card shadow mb-12" >
                <div class="card-header py-3" style="background-color:#A52A2A">
                    <h6 class="m-0 font-weight-bold" style="color:white">All Tasks Completion</h6>
                </div>
                <div class="card-body">
                    <h4 class="small font-weight-bold"> <span class="float-right">
                        <?php
                        // // Create a MySQLi connection
                        // $conn = new mysqli("localhost", "root", "", "dams2");

                        // // Check connection
                        // if ($conn->connect_error) {
                        //     die("Connection failed: " . $conn->connect_error);
                        // }
                        include "../config.php";

                        $query = "SELECT ROUND((COUNT(CASE WHEN is_completed = 0 THEN 1 END) / COUNT(*)) * 100) AS percentage_completed
                                FROM task_status_deans
                                WHERE office_id = '$department_id'";

                        $result = $conn->query($query);
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            echo $row['percentage_completed'] . "%";
                        } else {
                            echo "0%";
                        }

                        $conn->close();
                        ?>
                        </span>
                    </h4>
                    <div class="progress mb-4" style="width: 100%;"> <!-- Set the width to 100% to span the entire container -->
                        <div class="progress-bar" role="progressbar" style="background-color:#00BFA5;width: <?php
                            // Create a MySQLi connection
                            // $conn = new mysqli("localhost", "root", "", "dams2");

                            // // Check connection
                            // if ($conn->connect_error) {
                            //     die("Connection failed: " . $conn->connect_error);
                            // }
                            include "../config.php";

                            $query = "SELECT ROUND((COUNT(CASE WHEN is_completed = 0 THEN 1 END) / COUNT(*)) * 100) AS percentage_completed
                                    FROM task_status_deans
                                    WHERE office_id = '$department_id'";

                            $result = $conn->query($query);
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                echo $row['percentage_completed'] . "%";
                            } else {
                                echo "0%";
                            }

                            $conn->close();
                            ?>;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


             

                </div>
                    <!-- Content Row -->
                    <div class="row">

                       

                        <div class="col-lg-6 mb-4">

                        

                          

                        </div>
                    </div>

                   

                </div>











           


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
    <!--<script src="js/sb-admin-2.min.js"></script>-->

    <!-- Page level plugins -->
    <!--<script src="vendor/datatables/jquery.dataTables.min.js"></script>-->
    <!--<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>-->
    <script src="js/sb-admin-2.min.js"></script>    

</body>

</html>
<?php 

}}else{
    header("Location: ../index.php");
}?>