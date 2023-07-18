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
            LEFT JOIN departments d ON u.department_id = d.department_id
            WHERE unique_id = '$users_id' 
    
            ");
    $data_result = mysqli_fetch_assoc($data);
    $department_name = $data_result['department_name'];

    if($data_result){


?>
<!DOCTYPE html>
<html>
<?php  include "../header/header_heads.php"?>
<body>
    <div id="wrapper">
    <?php
     include "../sidebar/sidebar_heads.php";
     ?>
  <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
    <?php include "../topbar/topbar_heads.php"; ?>
      <div class="container-fluid tabcontent" id="insertData">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-1 text-gray-800">Insert Data for Faculty Loading</h1>
                        <div class="card-body container">
                            <div class="row">
                                <div class="col">
                                    <a class=" btn btn-primary" style="top:100%;float: right;" href="generateReport.php">Generate Document</a>
                   
                                </div>
                             
                        
                                       
                                
                            </div>
                            <br><br><br>
                            <form method="POST" action="../php/insert_faculty_loading.php">
                              <div class="row">
                                   <div class="col">
                                   <label for="lname" class="form-label">Faculty</label>
                                    <input class="form-control" list="lnames" name="faculty" id="lname" placeholder="Enter faculty name">
                                    <datalist id="lnames">
                                        <?php 
                                            $sql = "SELECT DISTINCT faculty_id,firstname,lastname,middlename FROM faculties";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                                $id = $row['faculty_id'];
                                                $name = $row['firstname'] .' ' . $row['middlename'] .' '. $row['lastname'];
                                            
                                        ?>
                                      <option value="<?php echo $name?>">
                                      <?php }?>
                                    </datalist>
                                </div>
                            </div>
                            <div class="row">
                                 <div class="col">
                                   <label for="browser" class="form-label">Course Code</label>
                                    <input class="form-control" list="browsers" name="course_code" id="browser" placeholder="Choose a Course Code">
                                    <datalist id="browsers">
                                        <?php 
                                            $sql = "SELECT DISTINCT course_code FROM courses";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                                $code = $row['course_code'];
                                            
                                        ?>
                                      <option value="<?php echo $code ?>">
                                      <?php }?>
                                    </datalist>
                                </div>
                            </div>
                            <div class="row">
                                     <div class="col">
                                   <label for="ini" class="form-label">Section</label>
                                    <input class="form-control" list="inis" name="section" id="ini" placeholder="Ente Section ">
                                    <datalist id="inis">
                                        <?php 
                                            $sql = "SELECT DISTINCT section_name FROM sections";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                                $md = $row['section_name'];
                                            
                                        ?>
                                      <option value="<?php echo $md ?>">
                                      <?php }?>
                                    </datalist>
                                </div>
                                <div class="col">
                                   <label for="sem" class="form-label">Semester</label>
                                    <input class="form-control" list="sems" name="semester" id="sem" placeholder="Semester">
                                    <datalist id="sems">
                                        <?php 
                                            $sql = "SELECT DISTINCT sem_description FROM semesters";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                                $md = $row['sem_description'];
                                            
                                        ?>
                                      <option value="<?php echo $md ?>">
                                      <?php }?>
                                    </datalist>
                                </div>
                            </div>
                                      
                                
                              

                              

                               
                                 
                                 <div style="display: block; position:absolute;top:70%;right:8%;">
                            <button class="btn btn-success" type="submit" name="submit"style="width: 200px;">Insert data</button>
                        </div>
                        
                              </form>

                            
                        </div>
                        <br><br><br>
                        

                            
                          
                    
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