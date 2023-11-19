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
            <div class="container tabcontent">
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
                                        <select id="academicYear" class="form-control" name="academic_year"></select>
                                        <!-- <input type="text" class="form-control" id="academicYear" name="academic_year"  required placeholder="e.g. 2023-2024"> -->

                                    </div>
                                      <div class="form-group">
                                        <label for="term">Current Term</label>
                                        <select id="term" name="term" class="form-control">
                                            <option>January - July</option>
                                            <option>July - December</option>
                                            
                                        </select>


                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Year</label>
                                        <select id='date-dropdown' class="form-control" name="year"></select>

                                    </div>
                                    <div class="form-group">
                                        <label for="semester">Semester</label>
                                        <select class="form-control" id="semester" name="semester">
                                            <option value="First Semester">1st Semester</option>
                                            <option value="Second Semester">2nd Semester</option>
                                            <option value="Summer Class">Summer Class</option>
                                            
                                    </select>
                                </div>
                                    <button type="submit" class="btn btn-success" name="submit">Save Configuration</button>
                                </form>
                            </div>
                        </div>
                    </div>    
              </div>



                    
                <script>
                  var dateDropdown = document.getElementById('date-dropdown'); 
                       
                  var currentYear = new Date().getFullYear();    
                  var earliestYear = 2020;     
                  while (currentYear >= earliestYear) {      
                    var dateOption = document.createElement('option');          
                    dateOption.text = currentYear;      
                    dateOption.value = currentYear;        
                    dateDropdown.add(dateOption);      
                    currentYear -= 1;    
                  }
                </script>
            
                <script>
                    var dateDropdown = document.getElementById('academicYear');

                    var currentYear = new Date().getFullYear();
                    var startYear = 2020;

                    while (currentYear >= startYear) {
                        var academicYear = `${startYear}-${startYear + 1}`;
                        var dateOption = document.createElement('option');
                        dateOption.text = academicYear;
                        dateOption.value = academicYear;
                        dateDropdown.appendChild(dateOption); // Use .appendChild() instead of .add()
                        startYear += 1;
                    }
                </script>


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
<!-- <script type="text/javascript">
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
</script> -->

<!-- <script type="text/javascript">
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
</script> -->

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
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
<?php 

}}else{
    header("Location: ../index.php");
}?>