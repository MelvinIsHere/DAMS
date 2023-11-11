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
            <div class="container-fluid tabcontent">
                <!-- Page Heading -->
              <div class="row">
                  <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Academic Year & Semester Configuration</h1>
                </div>
                <div class="container setting-holder">
                    <!-- Academic Year and Semester Configuration -->
                    <div class="card">
                        <div class="card-header">
                            Academic Year 
                        </div>
                        <div class="card-body">
                            <form id="settingsForm" method="POST" action="../php/academic_year_semester.php">
                                <div class="form-group">
                                    <label for="academicYear">Academic Year</label>
                                    <input type="text" class="form-control" id="academicYear" name="academic_year"  required placeholder="e.g. 2023-2024">
                                </div>
                                                         <button type="submit" class="btn btn-success" name="submit">Save Configuration</button>
                            </form>
                        </div>
                    </div>
                </div>    
              </div>


                     <div class="row" style="margin-top: 100px">
                   
                <div class="container setting-holder">
                    <!-- Academic Year and Semester Configuration -->
                    <div class="card">
                        <div class="card-header">
                             Semester 
                        </div>
                        <div class="card-body">
                            <form id="semesterForm" method="POST" action="../php/academic_year_semester.php">
                                
                                <div class="form-group">
                                    <label for="semester">Semester</label>
                                    <select class="form-control" id="semester" name="semester">
                                        <option value="First">1st Semester</option>
                                        <option value="Second">2nd Semester</option>
                                        <option value="Summer 1">Summer Class 1</option>
                                        <option value="Summer 2">Summer Class 2</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success" name="submit">Save Configuration</button>
                            </form>
                        </div>
                    </div>
                </div>    
              </div>

            </div>
        </div>
        <!-- End of tab Content -->
    </div>
    <!-- End of Content  -->
</div>
<!-- end of content wrapper -->
</div>
<!-- end of wrapper -->


    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

   
  <!-- Include SweetAlert JavaScript -->



<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- ... Your existing HTML code ... -->

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
   $("#settingsForm").submit(function(e) {
      e.preventDefault(); // Prevent the form from submitting normally

      // Get the form data
      var formData = new FormData(this);

      // Send the AJAX request
      $.ajax({
         url: "../php/academic_year_semester.php", // PHP file to handle the insertion
         type: "POST",
         data: formData,
         processData: false,
         contentType: false,
         success: function(response) {
            console.log(response);
            
            if(response.trim() === "success"){ // Trim any whitespace from the response
               // Handle the response from the PHP file
               swal({
                  title: "Updated!",
                  text: "The Academic Year has been Updated!",
                  icon: "success",
               });
            } else {
               swal({
                  title: "Error",
                  text: "An error occurred. Please try again.",
                  icon: "error",
               });
            }
         },
         error: function(xhr, status, error) {
            swal({
               title: "Error",
               text: "The Academic Year did not update!",
               icon: "error",
            });
         }
      });
   });
});
</script>

<script type="text/javascript">
$(document).ready(function() {
   $("#semesterForm").submit(function(e) {
      e.preventDefault(); // Prevent the form from submitting normally

      // Get the form data
      var formData = new FormData(this);

      // Send the AJAX request
      $.ajax({
         url: "../php/semester.php", // PHP file to handle the insertion
         type: "POST",
         data: formData,
         processData: false,
         contentType: false,
         success: function(response) {
            console.log(response);
            
            if(response.trim() === "success"){ // Trim any whitespace from the response
               // Handle the response from the PHP file
               swal({
                  title: "Updated!",
                  text: "The Academic Year has been Updated!",
                  icon: "success",
               });
            } else {
               swal({
                  title: "Error",
                  text: "There are no semester name like that.",
                  icon: "error",
               });
            }
         },
         error: function(xhr, status, error) {
            swal({
               title: "Error",
               text: "The Academic Year did not update!",
               icon: "error",
            });
         }
      });
   });
});
</script>

<!-- ... Your existing HTML code ... -->





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
    <!--<script src="js/sb-admin-2.min.js"></script>-->

    <!-- Page level plugins -->
    <!--<script src="vendor/datatables/jquery.dataTables.min.js"></script>-->
    <!--<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>-->

</body>

</html>
<?php 

}}else{
    header("Location: ../index.php");
}?>