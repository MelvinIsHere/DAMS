<?php 
include "../config.php";

session_start();

 if(isset($_SESSION['unique_id']) && isset($_SESSION['user_id'])){
    $users_id = $_SESSION['unique_id'];
    $id = $_SESSION['user_id'];
    $acad_id = $_SESSION['acad_id'];
    $semester_id = $_SESSION['semester_id'];


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
        $fullname = $row['firstname']." ".$row['middlename']." ".$row['lastname']." ".$row['suffix'];



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

<div class="container-fluid tabcontent" id="createfill-Up">
    <div class="col-md-6">                 
        <div class="image">
            <img src="<?php echo '../php/images/'.$img?>" class="profile-img">
        </div>                                                                                                         
    </div>    
    <div class="col-md-6">
        <div class="profile-card">                 
            <div class="image col-md-6">
                <img src="<?php echo '../php/images/'.$img?>" class="profile-img">
            </div>     
        </div>
    </div>
           
           


            
        
    
</div>

                <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <form id="update_loading" method="POST" action="../php/update_faculty_loading.php">
                    




                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Form</h5>
                </div>
                <div class="modal-body" id="editModal-body">

                   <label for="faculty_name" class="form-label"> Faculty Name</label>
                   <input type="text" name="loading_id" id="loading_id" hidden style="height: 0px; width:0px">
                                    <input class="form-control" list="faculty_names" name="faculty_name" id="faculty_name" placeholder="Ente Faculty Name ">
                                    <datalist id="faculty_names">
                                        <?php 
                                            $sql = "SELECT DISTINCT firstname,lastname,middlename,suffix FROM faculties";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                                $faculty_name = $row['lastname'] . " " . $row['firstname'] . " " . $row['middlename'] . " " . $row['suffix'];
                                            
                                        ?>
                                      <option value="<?php echo $faculty_name ?>">
                                      <?php }?>
                                    </datalist>

<!-- ########################################################################################################################## -->


                    <label for="course_code" class="form-label">Course Code</label>
                                    <input class="form-control" list="course_codes" name="course_code" id="course_code" placeholder="Ente Course Code ">
                                    <datalist id="course_codes">
                                        <?php 
                                            $sql = "SELECT DISTINCT course_code FROM courses WHERE department_id = '$department_id'";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                                $course_code = $row['course_code'];
                                            
                                        ?>
                                      <option value="<?php echo $course_code ?>">
                                      <?php }?>
                                    </datalist>


<!-- ########################################################################################################################## -->                                    
                    
                        
                             <label for="section" class="form-label">Section</label>
                                    <input class="form-control" list="sections" name="section" id="section" placeholder="Enter Section ">
                                    <datalist id="sections">
                                        <?php 
    
    $sql = "SELECT DISTINCT s.section_name, d.department_name FROM sections s
            LEFT JOIN programs p ON p.program_id = s.program_id
            LEFT JOIN departments d ON d.department_id = p.department_id
            WHERE d.department_id = '$department_id'";
    $result = mysqli_query($conn, $sql);

    while($row = mysqli_fetch_array($result)) {
        $section_name = $row['section_name'];
    ?>
    <option value="<?php echo $section_name; ?>">
    <?php 
    }
    ?>
                                    </datalist>
                        



<!-- ########################################################################################################################## -->                    <!--     
                        <div class="col-xs-12 col-sm-8">
                         <label for="semester" class="form-label">Semester</label>
                                    <input class="form-control" list="semesters" name="semester" id="semester" placeholder="Enter Semester ">
                                    <datalist id="semesters">
                                        <?php 
                                            $sql = "SELECT DISTINCT sem_description FROM semesters";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                                $sem_description = $row['sem_description'];
                                            
                                        ?>
                                      <option value="<?php echo $sem_description ?>">
                                      <?php }?>
                                    </datalist>
                        </div> -->
                    
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit" >Save</button>
                    <button class="btn btn-warning" type="button" data-dismiss="modal">Back</button>
                </div>





                </form>
                
            </div>
        </div>
    </div>    

<!-- ########################################################################################################################## -->





<!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <form id="delete_loading" action="../php/delete_faculty_loading.php" method="POST">
                    <div class="modal-header">
                    
                    <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
                </div>
                <div class="modal-body" id="deleteModal-body">
                    <input type="" name="loading_id" id="delete_id" sty;e="width:0px;height:0px" hidden>

                Are you sure to delete this information?
            </div>
                <div class="modal-footer">
                    <button class="btn btn-warning" type="submit">Delete</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Back</button>
                </div>
                </form>

                
            </div>
        </div>
    </div> 
    <!-- Edit Modal HTML -->
    <div id="addEmployeeModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="../php/insert_faculty_loading.php">
                    <div class="modal-header">                      
                        <h4 class="modal-title">Add Faculty Loading</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">                    
                        <div class="form-group">
                            <label for="lname" class="form-label">Faculty</label>
                                    <input class="form-control" list="lnames" name="faculty" id="lname" placeholder="Enter faculty name">
                                    <datalist id="lnames">
                                        <?php 
                                            $sql = "SELECT DISTINCT faculty_id,firstname,lastname,middlename FROM faculties WHERE is_gen_ed = 'Yes' OR department_id = '$department_id'";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                                $id = $row['faculty_id'];
                                                $name =   $row['lastname'] .' ' . $row['firstname'] .' '.$row['middlename'] ;
                                            
                                        ?>
                                      <option value="<?php echo $name?>">
                                      <?php }?>
                                    </datalist>
                        </div>
                       <!--   <div class="form-group">
                             <label for="descriptions" class="form-label">description</label>
                                     <select id="descriptions" name="description">
                                        <option value="Class Schedule">Class Schedule</option>
                                        <option value="Consultation">Consultation</option>
                                        <option value="Research">Research</option>
                                        <option value="Administrative">Administrative</option>
                                        
                                    </select>
                        </div> -->
                        <div class="form-group">
                             <label for="browser" class="form-label">Course Code</label>
                                    <input class="form-control" list="browsers" name="course_code" id="browser" placeholder="Choose a Course Code">
                                    <datalist id="browsers">
                                        <?php 
                                            $sql = "SELECT DISTINCT course_code FROM courses WHERE department_id = '$department_id'";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                                $code = $row['course_code'];
                                            
                                        ?>
                                      <option value="<?php echo $code ?>">
                                      <?php }?>
                                    </datalist>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                  <div class="col">
                                   <label for="ini" class="form-label">Section</label>
                                    <input class="form-control" list="inis" name="section" id="ini" placeholder="Enter Section ">
                                                                        
                                    <datalist id="inis">
                                        <?php 
                                        
                                        $sql = "SELECT  s.section_name, d.department_name ,p.program_abbrv FROM sections s
                                                LEFT JOIN programs p ON p.program_id = s.program_id
                                                LEFT JOIN departments d ON d.department_id = p.department_id
                                                WHERE d.department_id = '$department_id'";
                                        $result = mysqli_query($conn, $sql);

                                        while($row = mysqli_fetch_array($result)) {
                                            $section_name = 'BS'.$row['program_abbrv'] . ' ' .$row['section_name'];
                                        ?>
                                        <option value="<?php echo $section_name; ?>">
                                        <?php 
                                        }
                                        ?>


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
                                <div class="col">
                                   <label for="acads" class="form-label">Semester</label>
                                    <input class="form-control" list="academ" name="acad" id="acads" placeholder="Academic year">
                                    <datalist id="academ">
                                        <?php 
                                            $sql = "SELECT DISTINCT acad_year FROM academic_year";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                                $acad = $row['acad_year'];
                                            
                                        ?>
                                      <option value="<?php echo $acad ?>">
                                      <?php }?>
                                    </datalist>
                                </div>
                            </div>
                        </div>
                                  
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <button  class="btn btn-primary btn-sm" type="submit" name="submit">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



<script>
    function toggleCourseCodeInput() {
        var descriptionsSelect = document.getElementById('descriptions');
        var courseCodeInput = document.getElementById('browser');
        var sectionInput = document.getElementById('ini');

        if (descriptionsSelect.value === 'Class Schedule') {
            courseCodeInput.disabled = false;
            sectionInput.disabled = false;
        } else {
            courseCodeInput.disabled = true;
            sectionInput.disabled = true;
        }
    }
</script>










<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php 
if(isset($_SESSION['alert'])){
    $value = $_SESSION['alert'];
    if($value == "success"){
        $message = $_SESSION['message'];
        echo "
        <script type='text/javascript'>
            swal({
                title: 'Success!',
                text: '$message',
                icon: 'success'
            });
        </script>";
    } elseif($value == "error"){
        $message = $_SESSION['message'];
        echo "
        <script type='text/javascript'>
            swal({
                title: 'Error!',
                text: '$message',
                icon: 'error'
            });
        </script>";
    }
    // Clear the session alert and message after displaying
    unset($_SESSION['alert']);
    unset($_SESSION['message']);
}
?>
    <!-- Bootstrap core JavaScript-->
    <!--<script src="vendor/jquery/jquery.min.js"></script>-->
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
    <!--<script src="js/demo/viewTask_details.js"></script>-->
    
    <!--<script src="js/demo/admin_faculty_loading.js"></script>-->
    <!--<script src="js/demo/faculty_sched_table.js"></script>-->
     <!-- Custom scripts for all pages-->
    <!--<script src="js/sb-admin-2.min.js"></script>-->

    <!-- Page level plugins -->
    <!--<script src="vendor/datatables/jquery.dataTables.min.js"></script>-->
    <!--<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>-->
    <script src="js/sb-admin-2.min.js"></script>



<?php }
}?>
</body>
</html>