 
<?php 
include "../config.php";
session_start();
$_SESSION['unique_id'] = '1188612194';
$_SESSION['user_id'] = 8;


 if(isset($_SESSION['unique_id']) && isset($_SESSION['user_id'])){
    $users_id = $_SESSION['unique_id'];
    $id = $_SESSION['user_id'];
    $department_name = $_SESSION['dept_name'];




    $data = mysqli_query($conn,"SELECT 
            u.user_id,
            u.unique_id,
            u.email,
            u.password,
            u.img,
            u.status,
            u.type,
            d.department_id AS 'department_id',
            d.department_name,
            d.department_abbrv
            FROM users u
            LEFT JOIN departments d ON u.department_id = d.department_id
            WHERE unique_id = '$users_id'
    
            ");
    $data_result = mysqli_fetch_assoc($data);
    $department_name = $data_result['department_name'];
    $department_id = $data_result['department_id'];

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

<div class="container-fluid tabcontent" id="createfill-Up">
                    <h1 class="h3 mb-1 text-gray-800">Course Management</h1>

                    <div class="card-body">
                            <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-xs-6">
                            <h2>Courses in <?php echo $department_name;?></b></h2>
                        </div>
                        <div class="col-xs-6">
                            <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Course</span></a>
                              
                                                
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Units</th>
                            <th>Lecture hrs/wk</th>
                            <th>Rle hrs/wk</th>
                            <th>Lab hrs/wk</th>
                           
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                                include "../config.php";

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
                            COUNT(*)  AS total_records FROM courses");
                            $total_records = mysqli_fetch_array($result_count);
                            $total_records = $total_records['total_records'];
                            $total_no_of_page = ceil($total_records / $total_records_per_page);
                            $second_last = $total_no_of_page - 1;

                            $sql = "SELECT
                                    course_id,
                                    course_code,
                                    course_description,
                                    units,
                                    lec_hrs_wk,
                                    rle_hrs_wk,
                                    lab_hrs_wk 
                                    FROM courses
                                    WHERE department_id = '$department_id'";
                            $results = $conn->query($sql);
                            if(!$results){
                                die("Query failed: " . mysqli_error($conn));
                            }
                            $results->data_seek($off_set);
                            $count = 1;
                            while ($row = mysqli_fetch_array($results)) {
                                $id = $row['course_id'];
                                $course_code = $row['course_code'];
                                $course_description = $row['course_description'];
                                $units = $row['units'];
                                $lec = $row['lec_hrs_wk'];
                                $rle = $row['rle_hrs_wk'];
                                $lab = $row['lab_hrs_wk'];
                                

                                $count++;
                            

                         ?>
                        <tr id="table_row">
                            
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

                                     var course_id  = $(this).closest('tr').find('.course_id').text();
                                    console.log(course_id)
                                    $('#course_id').val(course_id);;
                                    $('#course_code').val(data[1]);
                                    $('#course_name').val(data[2]);
                                    $('#units').val(data[3]);
                                    $('#lecture').val(data[4]);
                                    $('#rle').val(data[5]);
                                    $('#lab').val(data[6]);
                                    

                                });
                                });



                                $(document).ready(function() {
                                $('.delete').on('click',function(e){
                                    e.preventDefault();

                                    var course_id  = $(this).closest('tr').find('.course_id').text();
                                    console.log(course_id)
                                    $('#delete_id').val(course_id);;
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
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <form id="update_courses">
                    




                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Courses</h5>
                </div>
                <div class="modal-body" id="editModal-body">

                   <label for="faculty_name" class="form-label"> Course Name</label>
                   <input type="text" name="course_id" id="course_id" style="width: 0px; height:0px " hidden >
                                    <input class="form-control" name="course_name" id="course_name" placeholder="Enter Course Name " required>
                                  

<!-- ########################################################################################################################## -->

                
                    

<!-- ########################################################################################################################## -->                                    
                    <div class="row">
                        <div class="col">
                           <label for="course_code" class="form-label">Course Code</label>
                            <input class="form-control" name="course_code" id="course_code" placeholder="Enter Course Code " required> 
                        </div>                        
                        <div class="col">
                           <label for="units" class="form-label">Units</label>
                            <input class="form-control"  name="units" id="units" placeholder="Enter units" required>         
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                           <label for="lecture" class="form-label">Lec. hours per week</label>
                            <input class="form-control" name="lecture" id="lecture" placeholder="Enter hours " required> 
                        </div>                        
                        <div class="col">
                           <label for="rle" class="form-label">RLE hours per week</label>
                            <input class="form-control"  name="rle" id="rle" placeholder="Enter hours" required>         
                        </div>
                        <div class="col">
                           <label for="lab" class="form-label">Lab. hours per week</label>
                            <input class="form-control"  name="lab" id="lab" placeholder="Enter hours" required>         
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
                <form id="delete_course">
                    <div class="modal-header">
                    
                    <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
                </div>
                <div class="modal-body" id="deleteModal-body">
                    

                Are you sure to delete this information?<input type="" name="course_id" id="delete_id" style="height: 0px; width:0px" hidden>
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
                <form method="POST" action="../php/add_courses.php">
                    <div class="modal-header">                      
                        <h4 class="modal-title">Add New Course</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">                    
                        <div class="form-group">

                            <label for="course_name" class="form-label">Course Name</label><input type="text" name="department_id" value="<?php echo $department_id?>" style="width: 0px;height: 0px;" hidden>
                            <input class="form-control"  name="course_name" id="course_name" placeholder="Enter course name" required>


                        </div>
                        
                        <div class="form-group">
                            <div class="row">
                                  <div class="col">
                                   <label for="course_code" class="form-label">Course Code</label>
                                    <input class="form-control" name="course_code" id="course_code" placeholder="Enter Course Code" required>
                                 
                                </div>
                                <div class="col">
                                   <label for="units" class="form-label">Units</label>
                                    <input class="form-control" name="units" id="units" placeholder="Enter Units" required>                        
                                </div>                            
                            </div>
                        </div>
                         <div class="form-group">
                            <div class="row">
                                  <div class="col">
                                   <label for="lecture" class="form-label">Lec. hours per week</label>
                                    <input class="form-control" name="lecture" id="lecture" placeholder="Enter lecture hours per week" required>
                                 
                                </div>
                                <div class="col">
                                   <label for="rle" class="form-label">RLE hours per week</label>
                                    <input class="form-control" name="rle" id="rle" placeholder="Enter rle hours per week" required>                        
                                </div>
                                <div class="col">
                                   <label for="lab" class="form-label">Lab. hours per week</label>
                                    <input class="form-control" name="lab" id="lab" placeholder="Enter laboratory hours per week" required>                        
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
   $("#delete_course").submit(function(e) {
      e.preventDefault(); // Prevent the form from submitting normally

      // Get the form data
      var formData = new FormData(this);

      // Send the AJAX request
      $.ajax({
         url: "../php/delete_course.php", // PHP file to handle the insertion
         type: "POST",
         data: formData,
         processData: false,
         contentType: false,
         success: function(response) {
            // Handle the response from the PHP file
            
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
   $("#update_courses").submit(function(e) {
      e.preventDefault(); // Prevent the form from submitting normally

      // Get the form data
      var formData = new FormData(this);

      // Send the AJAX request
      $.ajax({
         url: "../php/update_courses.php", // PHP file to handle the insertion
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
<script type="text/javascript">


                                        function table() {
                                        setInterval(function() {
                                            var xhttp = new XMLHttpRequest();
                                            xhttp.onreadystatechange = function() {
                                                if (this.readyState == 4 && this.status == 200) {
                                                    document.getElementById('table_row').innerHTML = this.responseText;
                                                }
                                            };
                                            xhttp.open("GET", "get_table_data.php", true);
                                            xhttp.send();
                                        }, 1000);
                                    }

                                    table();

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