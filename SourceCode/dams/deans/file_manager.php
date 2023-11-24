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



?><!DOCTYPE html>
<html lang="en">
<?php include "../header/header_deans.php"; ?>

<body id="page-top">
    
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php include "../sidebar/sidebar_deans.php"; ?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <?php include "../topbar/topbar_deans.php"?>
                <!-- Begin Page Content -->
                <div class="container-fluid tabcontent">
                    <h1 class="h3 mb-4 text-gray-800">File Manager</h1>

                    <!-- File List -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">File List</h6>
                                </div>
                                <div class="card-body">
                                    <!-- Display your file list with additional information -->
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                          <?php 

                                          include "../php/config.php";

                                          $query = mysqli_query($conn,"SELECT 
                                                                        ft.`file_id`,
                                                                        u.`email`,
                                                                        ft.`file_name`,
                                                                        ft.`directory`,
                                                                        ft.`date`
                                                                        
                                                                    FROM file_table ft
                                                                    LEFT JOIN users u ON u.`user_id` = ft.`file_owner_id`
                                                                    LEFT JOIN faculties f ON f.`faculty_id` = u.`faculty_id`
                                                                    WHERE f.`department_id` = '$department_id'");
                                          if($query){
                                                while ($row = mysqli_fetch_array($query)) {
                                                   

                                                   ?>
                                             <i class="fas fa-file-alt mr-2"></i>
                                            <strong><?php echo $row['file_name']; ?></strong>
                                            <a class="btn btn-success float-right" href="../view_files.php?file_id=<?php echo $row['file_id'];?>"><i class="fas fa-download"></i> Download</a>
                                          <?php
                                                }
                                          }else{

                                          }
                                          ?>
                                           
                                        </li>
                                       
                                        <!-- Add more files with descriptions as needed -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Page Content -->
            </div>
            <!-- End Main Content -->
        </div>
        <!-- End Content Wrapper -->
    </div>
    <!-- End Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Your custom JavaScript for file manager functionalities goes here -->

</body>

</html>

<?php 

}}else{
    header("Location: ../index.php");
}?>