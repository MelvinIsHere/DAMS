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
     
         <!-- CREATE TASK INPUT -->
                 <div class="container-fluid tabcontent" id="opcrTask">



                   

                    <!-- Page Heading -->
                    <h1 class="h3 mb-1 text-gray-800">Task Faculty Loading</h1>
                  
                    

                     <!-- GENERATE OPCR REPORT -->
                    


                    <!-- Content Row -->
                    <div class="container-fluid">
                        
                            <form action="task.php" method="POST">
                            
                                <div class="form-group">
                                <label for="name" class="text-left">Task Name</label>
                                <input type="text" class="form-control" id="name" name="task_name" placeholder="Task Name" required>
                              </div>
                              <div class="form-group">
                                <label for="description" class="text-left">Description</label>
                                <input type="text" class="form-control" id="description" name="description" placeholder="description" required>
                              </div>
                              <div class="form-group">
                                <label for="dateStart">Date Start:</label>
                                <input type="date" class="form-control" id="dateStart" name="dateStart" required>
                              </div>
                             <div class="form-group">
                                <label for="dateEnd">Date End:</label>
                                <input type="date" class="form-control" id="dateEnd" name="dateEnd" required>
                              </div>
                              <div class="checkbox">
                                    <label><input type="checkbox" name="dean" value="1" > Deans</label>
                                    
                                    <label><input type="checkbox" name="ovcaa" value="1"> OVCAA</label>
                                    
                                </div>

                              <button type="submit" class="btn btn-primary pull-right">Submit</button>
                            </form>
                        

                     

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