<?php 
include "../../config.php";
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
<?php  include "../../header/header_deans.php"?>
<body>
    <div id="wrapper">
    <?php
     include "../../sidebar/sidebar_deans.php";
     ?>
  <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
    <?php include "../../topbar/topbar_deans.php"; ?>

<div class="container-fluid tabcontent" id="createfill-Up">
                    <h1 class="h3 mb-1 text-gray-800">Create Documents | Faculty Loading</h1>

                    <div class="card-body">
                            <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-xs-6">
                            <h2>Faculty Loading</b></h2>
                        </div>
                        <div class="col-xs-6">
                            <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Faculty Loading</span></a>
                              <a href="generateReport.php" class="btn btn-success"><i class="material-icons">&#xE147;</i> <span>Create Document</span></a>
                                                
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Section Name</th>
                            <th>Students</th>
                           
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                                include "../../config.php";

                            if(isset($_GET['page_no']) && $_GET['page_no'] !=""){
                                $page_no = $_GET['page_no'];
                            }else{
                                $page_no = 1;
                            }
                            $total_records_per_page = 6;
                            $off_set = ($page_no - 1) * $total_records_per_page;
                            $previous_page = $page_no - 1;
                            $next_page = $page_no + 1;
                            $adjacents = "2";

                            $result_count = mysqli_query($conn, "SELECT
                            COUNT(*)  AS total_records FROM sections");
                            $total_records = mysqli_fetch_array($result_count);
                            $total_records = $total_records['total_records'];
                            $total_no_of_page = ceil($total_records / $total_records_per_page);
                            $second_last = $total_no_of_page - 1;

                            $sql = "SELECT
                                    s.section_id AS 'Section Id',
                                    p.program_abbrv AS 'Program Abbrv',
                                    s.section_name AS 'Section Name',
                                    s.no_of_students AS 'Students'
                                    FROM sections s 
                                    LEFT JOIN programs p ON p.`program_id` = s.`program_id`
                                    ";
                            $results = $conn->query($sql);
                            if(!$results){
                                die("Query failed: " . mysqli_error($conn));
                            }
                            $results->data_seek($off_set);
                            $count = 1;
                            while ($row = mysqli_fetch_array($results)) {
                                $id = $row['Section Id'];
                                $section_name = $row['Section Name'];
                                $studs = $row['Students'];

                                $count++;
                            

                         ?>
                        <tr>
                            <td class="section_id"><?php echo $id;?></td>
                            <td><?php echo $section_name;?></td>
                            <td><?php echo $studs;?></td>
                            
                            <td>
                                <a href="#editEmployeeModal" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                                <a href="#deleteEmployeeModal" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                            </td>
                        </tr>
                    <?php 

                         // Break the loop if the desired limit is reached
    if ($count > $total_records_per_page) {
        break;
    }
                    }?>
                        
                    </tbody>
                </table>
                
            </div>
            <ul class="pagination pull-right">
    <li class="pull-left btn btn-default disabled">showing page <?php echo $page_no . " of " . $total_no_of_page; ?></li>
    <li <?php if ($page_no <= 1) { echo "class='disabled page-item'"; } ?>>
        <a <?php if ($page_no > 1) { echo "href='?page_no=$previous_page'"; } ?>>Previous</a>
    </li>

    <?php
    if ($total_no_of_page <= 10) {
        for ($counter = 1; $counter <= $total_no_of_page; $counter++) {
            if ($counter == $page_no) {
                echo "<li class='active page-item'><a>$counter</a></li>";
            } else {
                echo "<li><a href='?page_no=$counter'>$counter</a></li>";
            }
        }
    } elseif ($total_no_of_page > 10) {
        if ($page_no <= 4) {
            for ($counter = 1; $counter <= 8; $counter++) {
                if ($counter == $page_no) {
                    echo "<li class='active page-item'><a>$counter</a></li>";
                } else {
                    echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                }
            }
            echo "<li class='page-item'><a>...</a></li>";
            echo "<li class='page-item'><a href='?page_no=$second_last'>$second_last</a></li>";
            echo "<li class='page-item'><a href='?page_no=$total_no_of_page'>$total_no_of_page</a></li>";
        } elseif ($page_no > 4 && $page_no < $total_no_of_page - 4) {
            echo "<li class='page-item'><a href='?page_no=1'>1</a></li>";
            echo "<li class='page-item'><a href='?page_no=2'>2</a></li>";
            echo "<li class='page-item'><a>...</a></li>";

            for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {
                if ($counter == $page_no) {
                    echo "<li class='active page-item'><a>$counter</a></li>";
                } else {
                    echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                }
            }
            echo "<li class='page-item'><a>...</a></li>";
            echo "<li class='page-item'><a href='?page_no=$second_last'>$second_last</a></li>";
            echo "<li class='page-item'><a href='?page_no=$total_no_of_page'>$total_no_of_page</a></li>";
        } else {
            echo "<li class='page-item'><a href='?page_no=1'>1</a></li>";
            echo "<li class='page-item'><a href='?page_no=2'>2</a></li>";
            echo "<li class='page-item'><a>...</a></li>";
            for ($counter = $total_no_of_page - 6; $counter <= $total_no_of_page; $counter++) {
                if ($counter == $page_no) {
                    echo "<li class='active page-item'><a>$counter</a></li>";
                } else {
                    echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                }
            }
        }
    }
    ?>
    <li <?php if ($page_no >= $total_no_of_page) { echo "class='disabled page-item'"; } ?>>
        <a <?php if ($page_no < $total_no_of_page) { echo "href='?page_no=$next_page'"; } ?>>Next</a>
    </li>
    <?php
    if ($page_no < $total_no_of_page) {
        echo "<li class = 'page-item'><a href='?page_no=$total_no_of_page'>Last &rsquo;</a></li>";
    }
    ?>
</ul>

        </div>  
                            <script type="text/javascript">
                                $(document).ready(function() {
                                $('.edit').on('click',function(){
                                    $('#editModal').modal('show');

                                    $tr = $(this).closest('tr');

                                    var data = $tr.children("td").map(function(){
                                        return $(this).text();
                                    }).get();

                                    console.log(data);

                                     var loading_id  = $(this).closest('tr').find('.loading_id').text();
                                    console.log(loading_id)
                                    $('#loading_id').val(loading_id);;
                                    $('#faculty_name').val(data[1]);
                                    $('#course_code').val(data[2]);
                                    $('#section').val(data[3]);
                                    

                                });
                                });



                                $(document).ready(function() {
                                $('.delete').on('click',function(e){
                                    e.preventDefault();

                                    var loading_id  = $(this).closest('tr').find('.loading_id').text();
                                    console.log(loading_id)
                                    $('#delete_id').val(loading_id);;
                                    $('#deleteModal').modal('show');  

                                });
                                });
                            </script>
                        <!-- 
                        <script type="text/javascript">
                          $(document).ready(function() {
                            $('a[data-toggle="modal"]').click(function() {
                              var id = $(this).data('id');
                              $('#h').text(id);
                            });
                          });
                        </script>
 -->                        </div>


                    

            </div>

                <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="update_loading">
                    




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
                                            $sql = "SELECT DISTINCT course_code FROM courses";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                                $course_code = $row['course_code'];
                                            
                                        ?>
                                      <option value="<?php echo $course_code ?>">
                                      <?php }?>
                                    </datalist>


<!-- ########################################################################################################################## -->                                    
                    <div class="row">
                        <div class="col-xs-12 col-sm-4">
                             <label for="section" class="form-label">Section</label>
                                    <input class="form-control" list="inis" name="section" id="section" placeholder="Ente Section ">
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



<!-- ########################################################################################################################## -->                        
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
                        </div>
                    </div>
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
                <form id="delete_loading">
                    <div class="modal-header">
                    
                    <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
                </div>
                <div class="modal-body" id="deleteModal-body">
                    <input type="" name="loading_id" id="delete_id">

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
                        <h4 class="modal-title">Add Employee</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">                    
                        <div class="form-group">
                            <label for="lname" class="form-label">Faculty</label>
                                    <input class="form-control" list="lnames" name="faculty" id="lname" placeholder="Enter faculty name">
                                    <datalist id="lnames">
                                        <?php 
                                            $sql = "SELECT DISTINCT faculty_id,firstname,lastname,middlename FROM faculties";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                                $id = $row['faculty_id'];
                                                $name =   $row['lastname'] .' ' . $row['firstname'] .' '.$row['middlename'] ;
                                            
                                        ?>
                                      <option value="<?php echo $name?>">
                                      <?php }?>
                                    </datalist>
                        </div>
                        <div class="form-group">
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
                        <div class="form-group">
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





<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
   $("#delete_loading").submit(function(e) {
      e.preventDefault(); // Prevent the form from submitting normally

      // Get the form data
      var formData = new FormData(this);

      // Send the AJAX request
      $.ajax({
         url: "../php/delete_faculty_loading.php", // PHP file to handle the insertion
         type: "POST",
         data: formData,
         processData: false,
         contentType: false,
         success: function(response) {
            // Handle the response from the PHP file
            $("#delete_loading").trigger('reset');
            alert(response);
            $('#deleteModal').modal('hide'); 

         },
         error: function(xhr, status, error) {
            // Handle errors
            console.error(error); // Log the error message
         }
      });
   });
});
</script>

<script type="text/javascript">
$(document).ready(function() {
   $("#update_loading").submit(function(e) {
      e.preventDefault(); // Prevent the form from submitting normally

      // Get the form data
      var formData = new FormData(this);

      // Send the AJAX request
      $.ajax({
         url: "../php/update_faculty_loading.php", // PHP file to handle the insertion
         type: "POST",
         data: formData,
         processData: false,
         contentType: false,
         success: function(response) {
            // Handle the response from the PHP file
            
            alert(response);
            $('#editModal').modal('hide'); 

         },
         error: function(xhr, status, error) {
            // Handle errors
            console.error(error); // Log the error message
         }
      });
   });
});
</script>




      
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
    
    <script src="js/demo/admin_faculty_loading.js"></script>
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