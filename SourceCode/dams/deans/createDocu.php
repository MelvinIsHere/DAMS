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
            d.department_abbrv
            FROM users u
            LEFT JOIN departments d ON u.user_id = d.user_id
            WHERE unique_id = '$users_id' 
    
            ");
    $data_result = mysqli_fetch_assoc($data);
    $department_name = $data_result['department_name'];

    if($data_result){


?>
<!DOCTYPE html>
<html>
<?php  include "../header/header_deans.php"?>
<body>
    <div id="wrapper">
    <?php
     include "../sidebar/sidebar_deans.php";
     ?>
  <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
    <?php include "../topbar/topbar_deans.php"; ?>
     

               <div class="container-fluid tabcontent" id="createDocu">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Document Templates</h1>

                    <div class="row">

                                <div class="col-xl-3 col-md-6 mb-4">
                               <a href="dataFillUp.php">
                                    <div class="card border-left-primary shadow h-100 py-2">
                                        <div class="card-body">
                                            <div class="container">
                                                <img src="sample.jpg" width="100%" height="auto">
                                            </div>
                                        </div>                            
                                        <div class="text-xs font-weight-bold text-hello mb-1" onclick="openCity(event,'viewDocument')">Faculty Loading</div>
                                    </div>   
                                    </a>                                 
                                </div>
                            


                       

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <img src="sample.jpg" width="100%" height="auto">
                                </div>                            
                                <div class="text-xs font-weight-bold text-hello text-uppercase mb-1">IPCR</div>
                            </div>                                    
                        </div>


                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <img src="sample.jpg" width="100%" height="auto">
                                </div>                            
                                <div class="text-xs font-weight-bold text-hello mb-1">Faculty Loading</div>
                            </div>                                    
                        </div>


                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <img src="sample.jpg" width="100%" height="auto">
                                </div>                            
                                <div class="text-xs font-weight-bold text-hello mb-1">Accomplishment Report</div>
                            </div>                                    
                        </div>
                    </div>
                </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
 
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

    <script src="js/demo/datatables-demo.js"></script>
    <script src="js/demo/viewTask_details.js"></script>
    <script src="js/demo/faculty_loading.js"></script>
    <script src="js/demo/faculty_sched_table.js"></script>
     <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>


<?php }
}?>
</body>
</html>