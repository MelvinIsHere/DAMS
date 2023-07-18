<?php 
include "../config.php";
session_start();

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
WHERE user_id = '$id' 
    
            ");
    

   

    while($row = mysqli_fetch_array($data)){
         $department_name = $row['department_name'];
        $img = $row['img'];
        $type =$row['type'];

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

<div class="container-fluid tabcontent" id="createfill-Up">
                    <h1 class="h3 mb-1 text-gray-800">Account Management</h1>

                    <div class="card-body">
                            <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-xs-6">
                            <h2>All Faculties</b></h2>
                        </div>
                        <div class="col-xs-6">
                            <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Account</span></a>
                              
                                                
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Email</th>\
                            <th>Password</th>
                            <th>Position</th>
                            <th>Department</th>
                           
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
                            COUNT(*)  AS total_records FROM users");
                            $total_records = mysqli_fetch_array($result_count);
                            $total_records = $total_records['total_records'];
                            $total_no_of_page = ceil($total_records / $total_records_per_page);
                            $second_last = $total_no_of_page - 1;

                            $sql = "SELECT
                                    u.user_id,
                                    u.unique_id,
                                    u.email,
                                    u.password,
                                    u.type,
                                    d.`department_name`
                                FROM users u
                                LEFT JOIN departments d ON u.`department_id` = d.`department_id`";
                            $results = $conn->query($sql);
                            if(!$results){
                                die("Query failed: " . mysqli_error($conn));
                            }
                            $results->data_seek($off_set);
                            $count = 1;
                            while ($row = mysqli_fetch_array($results)) {
                                $id = $row['user_id'];
                                
                                $email = $row['email'];
                                $password = $row['password'];
                                $type = $row['type'];
                                $department_name = $row['department_name'];
                             
                                $count++;
                            

                         ?>
                        <tr>
                            <td class="user_id"><?php echo $id;?></td>
                            <td><?php echo $email?></td>
                            <td><?php echo $password;?></td>
                            <td><?php echo $type;?></td>
                            <td><?php echo $department_name;?></td>
                            
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

                                     var user_id  = $(this).closest('tr').find('.user_id').text();
                                    console.log(user_id);
                                    $('#user_id').val(user_id);;
                                    $('#email').val(data[1]);
                                    $('#password').val(data[2]);
                                    $('#type').val(data[3]);
                                    
                                    

                                });
                                });



                                $(document).ready(function() {
                                $('.delete').on('click',function(e){
                                    e.preventDefault();

                                    var user_id  = $(this).closest('tr').find('.user_id').text();
                                    console.log(user_id);
                                    $('#delete_id').val(user_id);;
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
                <form id="update_account" action="../php/update_account.php" method="POST">
                    
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Account</h5>
                    </div>
                <div class="modal-body" id="editModal-body">
                    <div class="form-group">
                            <label for="email" class="form-label"> Email</label>
                                    <input type="text" name="user_id" id="user_id" hidden style="height: 0px; width:0px">
                                    <input class="form-control"  name="email" id="email" placeholder="Enter email address">
                        
                    </div>
                     <div class="form-group">
                            <label for="faculty_name" class="form-label"> Password</label>
                                    
                                    <input class="form-control" type="password"  name="password" id="password" placeholder="Enter password">
                        
                    </div>
                                  
<!-- ########################################################################################################################## -->
                     <div class="form-group">
                             <label for="type" class="form-label">Type</label>
                                    <input class="form-control" list="browsers" name="type" id="type" placeholder="Choose a faculty type" required>
                                    <datalist id="browsers">
                                       
                                      <option value="Dean">
                                      <option value="Heads">
                                      <option value="Admin">
                                    </datalist>
                        </div>

                    
<!-- ########################################################################################################################## -->
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
                <form id="delete_account" action="../php/delete_account.php" method="POST">
                    <div class="modal-header">
                    
                    <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
                </div>
                <div class="modal-body" id="deleteModal-body">
                    <input type="" name="user_id" id="delete_id" style="width:0px;height:0px" hidden>

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
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form method="POST" id="account">
                    <div class="modal-header">                      
                        <h4 class="modal-title">Add new account</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body"> 
                                           
                      <div class="form-group">
                                           
                                   <label for="email" class="form-label">Email Address</label>
                                    <input class="form-control" type="email" name="email" id="email" placeholder="Enter email" required>
                                                      
                        </div>
                         <div class="form-group">
                           <label for="password" class="form-label">Password</label>
                                    <input class="form-control" name="password" type="password"id="password" placeholder="Enter password" required>
                        </div>
                        <div class="row">
                            <div class="col">
                               <div class="form-group">
                            
                                    <label for="image" class="form-label">Insert image</label>
                                        <input class="form-control" name="image" id="mi" type="file">
                                </div>
                                    
                            </div>
                             <div class="col">
                               <div class="form-group">

                                    <label for="department" class="form-label">Account Type</label>
                                    <input class="form-control"  list="types" name="type" id="type" placeholder="Enter account type" required>
                                      <datalist id="types">                                            
                                              <option value="Dean">
                                              <option value="Heads">
                                              <option value="Admin">                                              
                                    </datalist>


                                </div>
                            </div>
                        </div>
                        <div class="form-group">

                            <label for="department" class="form-label">Department Name</label>
                            <input class="form-control"  list="departments" name="department_name" id="department" placeholder="Enter department name" required>
                              <datalist id="departments">
                                        <?php 
                                            $sql = "SELECT DISTINCT department_name FROM departments";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                                $department_name = $row['department_name'];
                                            
                                        ?>
                                      <option value="<?php echo $department_name ?>">
                                      <?php }?>
                                    </datalist>


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





<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script><!-- 
<script type="text/javascript">
$(document).ready(function() {
   $("#delete_account").submit(function(e) {
      e.preventDefault(); // Prevent the form from submitting normally

      // Get the form data
      var formData = new FormData(this);

      // Send the AJAX request
      $.ajax({
         url: "../php/delete_account.php", // PHP file to handle the insertion
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
</script> -->

<!-- <script type="text/javascript">
$(document).ready(function() {
   $("#update_account").submit(function(e) {
      e.preventDefault(); // Prevent the form from submitting normally

      // Get the form data
      var formData = new FormData(this);

      // Send the AJAX request
      $.ajax({
         url: "../php/update_account.php", // PHP file to handle the insertion
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
</script> -->

<script type="text/javascript">
$(document).ready(function() {
   $("#account").submit(function(e) {
      e.preventDefault(); // Prevent the form from submitting normally

      // Get the form data
      var formData = new FormData(this);

      // Send the AJAX request
      $.ajax({
         url: "../php/signup.php", // PHP file to handle the insertion
         type: "POST",
         data: formData,
         processData: false,
         contentType: false,
         success: function(response) {
            // Handle the response from the PHP file
            
            alert(response);
            $('#account').modal('hide'); 

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