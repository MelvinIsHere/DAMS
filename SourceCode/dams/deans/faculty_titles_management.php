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
    <h1 class="h3 mb-1 text-gray-800">Faculty Title Management</h1>
    <div class="card-body">
        <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        
                        <div class="col d-flex justify-content-start">
                            <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Title to Faculty Member</span></a>                                                                              
                        </div>
                         <div class="col d-flex justify-content-start">
                            <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                                <div class="input-group">
                                    <input type="text" name="search" value="<?php if(isset($_GET['search'])){echo $_GET['search']; } ?>" class="form-control bg-light " placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                                
                             
                                    <div class="input-group-append">
                                        <button class="btn " type="submit" style="color:#A52A2A;background-color:white">
                                            <i class="fas fa-search fa-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                                    
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-hover" id="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Faculty Name</th>
                            <th>Title</th>
                           
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                                include "../config.php";
                                 
                             if(isset($_GET['search'])){
                                $search = $_GET['search'];

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

                            $result_count = mysqli_query($conn, "SELECT DISTINCT
                                    COUNT(*) AS total_records,
                                    ft.fac_title_id,
                                    f.firstname,
                                    f.lastname,
                                    f.middlename,
                                    f.suffix,
                                    f.department_id,
                                    t.title_description
                                    FROM faculty_titles ft
                                    LEFT JOIN faculties f ON f.`faculty_id` = ft.faculty_id
                                    LEFT JOIN titles t ON t.`title_id` = ft.title_id
                                    WHERE f.department_id = '$department_id' 
                                    AND CONCAT(f.firstname,f.middlename,f.lastname,f.suffix) LIKE '%$search%'
                                    AND t.title_description LIKE '%search%'");
                            $total_records = mysqli_fetch_array($result_count);
                            $total_records = $total_records['total_records'];
                            $total_no_of_page = ceil($total_records / $total_records_per_page);
                            $second_last = $total_no_of_page - 1;

                            $sql = "SELECT
                                    ft.fac_title_id,
                                    f.firstname,
                                    f.lastname,
                                    f.middlename,
                                    f.suffix,
                                    f.department_id,
                                    t.title_description
                                    FROM faculty_titles ft
                                    LEFT JOIN faculties f ON f.`faculty_id` = ft.faculty_id
                                    LEFT JOIN titles t ON t.`title_id` = ft.title_id
                                    WHERE department_id = '$department_id'
                                    AND CONCAT(f.firstname,f.middlename,f.lastname,f.suffix, t.title_description) LIKE '%$search%'
                                   ";
                            $results = $conn->query($sql);
                            if(!$results){
                                die("Query failed: " . mysqli_error($conn));
                            }
                            $results->data_seek($off_set);
                            $count = 1;
                            while ($row = mysqli_fetch_array($results)) {
                                $id = $row['fac_title_id'];
                                $first_name = $row['firstname'];
                                $last_name = $row['lastname'];
                                $middle_name = $row['middlename'];
                                $suffix = $row['suffix'];
                                $title = $row['title_description'];

                                $count++;
                            

                         ?>
                        <tr>
                            <td class="faculty_title_id"><?php echo $id;?></td>
                            <td><?php echo  $last_name . " " .$first_name . " " .  $middle_name . " " . $suffix;?></td>
                            <td><?php echo $title;?></td>
                            
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
                    }}else{
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

                            $result_count = mysqli_query($conn, "SELECT DISTINCT
                                    COUNT(*) AS total_records
                                    FROM faculty_titles ft
                                    LEFT JOIN faculties f ON f.`faculty_id` = ft.faculty_id
                                    LEFT JOIN titles t ON t.`title_id` = ft.title_id
                                    WHERE f.department_id = '$department_id'");
                            $total_records = mysqli_fetch_array($result_count);
                            $total_records = $total_records['total_records'];
                            $total_no_of_page = ceil($total_records / $total_records_per_page);
                            $second_last = $total_no_of_page - 1;

                            $sql = "SELECT
                                    ft.fac_title_id,
                                    f.firstname,
                                    f.lastname,
                                    f.middlename,
                                    f.suffix,
                                    f.department_id,
                                    t.title_description
                                    FROM faculty_titles ft
                                    LEFT JOIN faculties f ON f.`faculty_id` = ft.faculty_id
                                    LEFT JOIN titles t ON t.`title_id` = ft.title_id
                                    WHERE department_id = '$department_id'";
                            $results = $conn->query($sql);
                            if(!$results){
                                die("Query failed: " . mysqli_error($conn));
                            }
                            $results->data_seek($off_set);
                            $count = 1;
                            while ($row = mysqli_fetch_array($results)) {
                                $id = $row['fac_title_id'];
                                $first_name = $row['firstname'];
                                $last_name = $row['lastname'];
                                $middle_name = $row['middlename'];
                                $suffix = $row['suffix'];
                                $title = $row['title_description'];

                                $count++;
                            

                         ?>
                        <tr>
                            <td class="faculty_title_id"><?php echo $id;?></td>
                            <td><?php echo  $last_name . " " .$first_name . " " .  $middle_name . " " . $suffix;?></td>
                            <td><?php echo $title;?></td>
                            
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
                    }
                    }?>
                        
                    </tbody>
                </table>
                
        </div>
        <!-- end of table wrapper -->
     <?php 
    if(isset($_GET['search'])){

    ?>
        <!-- end of table wrapper -->
     <ul class="pagination pull-right">
    <li class="pull-left btn btn-default disabled">showing page <?php echo $page_no . " of " . $total_no_of_page; ?></li>
    <li <?php if ($page_no <= 1) { echo "class='disabled page-item'"; } ?>>
        <a <?php if ($page_no > 1) { echo "href='?page_no=$previous_page&search={$_GET["search"]}'"; } ?>>Previous</a>
    </li>

    <?php
    if ($total_no_of_page <= 10) {
        for ($counter = 1; $counter <= $total_no_of_page; $counter++) {
            if ($counter == $page_no) {
                echo "<li class='active page-item'><a>$counter</a></li>";
            } else {
                echo "<li><a href='?page_no=$counter&search={$_GET["search"]}'>$counter</a></li>";

            }
        }
    } elseif ($total_no_of_page > 10) {
        if ($page_no <= 4) {
            for ($counter = 1; $counter <= 8; $counter++) {
                if ($counter == $page_no) {
                    echo "<li class='active page-item'><a>$counter</a></li>";
                } else {
                    echo "<li><a href='?page_no=$counter&search={$_GET["search"]}'>$counter</a></li>";
                }
            }
            echo "<li class='page-item'><a>...</a></li>";
            echo "<li class='page-item'><a href='?page_no=$second_lastr&search={$_GET["search"]}'>$second_last</a></li>";
            echo "<li class='page-item'><a href='?page_no=$total_no_of_page&search={$_GET["search"]}'>$total_no_of_page</a></li>";
        } elseif ($page_no > 4 && $page_no < $total_no_of_page - 4) {
            echo "<li class='page-item'><a href='?page_no=1'>1</a></li>";
            echo "<li class='page-item'><a href='?page_no=2'>2</a></li>";
            echo "<li class='page-item'><a>...</a></li>";

            for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {
                if ($counter == $page_no) {
                    echo "<li class='active page-item'><a>$counter</a></li>";
                } else {
                    echo "<li><a href='?page_no=$counter&search={$_GET["search"]}'>$counter</a></li>";
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
                    echo "<li><a href='?page_no=$counter&search={$_GET["search"]}'>$counter</a></li>";
                }
            }
        }
    }
    ?>
    <li <?php if ($page_no >= $total_no_of_page) { echo "class='disabled page-item'"; } ?>>
        <a <?php if ($page_no < $total_no_of_page) { echo "href='?page_no=$next_page&search={$_GET["search"]}'"; } ?>>Next</a>
    </li>
    <?php
    if ($page_no < $total_no_of_page) {
        echo "<li class = 'page-item'><a href='?page_no=$total_no_of_page&search={$_GET["search"]}'>Last &rsquo;</a></li>";
    }
    ?>
</ul>

<?php }else{

?>
       
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
<?php 
}?>
<!-- end of table responsive -->
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

                                     var faculty_title_id  = $(this).closest('tr').find('.faculty_title_id').text();
                                    console.log(faculty_title_id)
                                    $('#faculty_title_id').val(faculty_title_id);;
                                    $('#faculty_name').val(data[1]);
                                    $('#faculty_title').val(data[2]);
                                    
                                    

                                });
                                });



                                $(document).ready(function() {
                                $('.delete').on('click',function(e){
                                    e.preventDefault();

                                    var faculty_title_id  = $(this).closest('tr').find('.faculty_title_id').text();
                                    console.log(faculty_title_id)
                                    $('#delete_id').val(faculty_title_id);;
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
                    

                     end of card bory -->
                    </div>


                    
<!-- end of container -->
</div>
</div>
</div>
</div>

                <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <form id="update_title" action="../php/update_faculty_titles.php" method="POST">
                    




                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Title</h5>
                </div>
                <div class="modal-body" id="editModal-body">

                   <label for="faculty_name" class="form-label"> Faculty Name</label>
                   <input type="text" name="faculty_title_id" id="faculty_title_id" hidden style="height: 0px; width:0px">
                                    <input class="form-control" list="faculty_names" name="faculty_name" id="faculty_name" placeholder="Ente Faculty Name ">
                                    <datalist id="faculty_names">
                                        <?php 
                                            $sql = "SELECT DISTINCT firstname,lastname,middlename,suffix FROM faculties WHERE department_id = '$department_id'";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                                $faculty_name = $row['lastname'] . " " . $row['firstname'] . " " . $row['middlename'] . " " . $row['suffix'];
                                            
                                        ?>
                                      <option value="<?php echo $faculty_name ?>">
                                      <?php }?>
                                    </datalist>

<!-- ########################################################################################################################## -->


                  <label for="faculty_title" class="form-label">Faculty titles</label>
                            <select class="form-control" name="faculty_title" id="faculty_title">
                                <?php 
                                    $sql = "SELECT title_description FROM titles";
                                    $result = mysqli_query($conn, $sql);

                                    while ($row = mysqli_fetch_array($result)) {
                                        $title_description = $row['title_description'];
                                ?>
                                        <option value="<?php echo $title_description; ?>">
                                            <?php echo $title_description; ?>
                                        </option>
                                <?php
                                    }
                                ?>
                            </select>

                                    <!-- <input class="form-control" list="faculty_titles" name="faculty_title" id="faculty_title" placeholder="Ente Course Code "> -->
                                  <!--   <datalist id="faculty_titles">
                                        <?php 
                                            $sql = "SELECT DISTINCT title_description FROM titles";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                                $title_description = $row['title_description'];
                                            
                                        ?>
                                      <option value="<?php echo $title_description ?>">
                                      <?php }?>
                                    </datalist> -->


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
                <form id="delete_title" action="../php/remove_faculty_title.php" method="POST">
                    <div class="modal-header">
                    
                    <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
                </div>
                <div class="modal-body" id="deleteModal-body">
                    <input type="" name="faculty_title_id" id="delete_id" style="width:0px; height: 0px;" hidden>

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
                <form method="POST" action="../php/add_new_title_to_faculty_member.php">
                    <div class="modal-header">                      
                        <h4 class="modal-title">Add Title to a faculty member</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">                    
                        <div class="form-group">
                            <label for="lname" class="form-label">Faculty</label>
                                    <input class="form-control" list="lnames" name="faculty" id="lname" placeholder="Enter faculty name" required>
                                    <datalist id="lnames">
                                        <?php 
                                            $sql = "SELECT DISTINCT faculty_id,firstname,lastname,middlename FROM faculties WHERE department_id = '$department_id'";
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
                             <label for="title" class="form-label">Course Code</label>
                                   <select class="form-control" name="faculty_title" >
                                <?php 
                                    $sql = "SELECT title_description FROM titles";
                                    $result = mysqli_query($conn, $sql);

                                    while ($row = mysqli_fetch_array($result)) {
                                        $title_description = $row['title_description'];
                                ?>
                                        <option value="<?php echo $title_description; ?>">
                                            <?php echo $title_description; ?>
                                        </option>
                                <?php
                                    }
                                ?>
                            </select>
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
          let table = new DataTable('#table');
      </script>


<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>-->
<!-- <script type="text/javascript">
$(document).ready(function() {
   $("#delete_title").submit(function(e) {
      e.preventDefault(); // Prevent the form from submitting normally

      // Get the form data
      var formData = new FormData(this);

      // Send the AJAX request
      $.ajax({
         url: "../php/remove_faculty_title.php", // PHP file to handle the insertion
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
<!-- 
<script type="text/javascript">
$(document).ready(function() {
   $("#update_title").submit(function(e) {
      e.preventDefault(); // Prevent the form from submitting normally

      // Get the form data
      var formData = new FormData(this);

      // Send the AJAX request
      $.ajax({
         url: "../php/update_faculty_titles.php", // PHP file to handle the insertion
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


<?php }
}else{
    header("Location: ../index.php");
}?>
</body>
</html>