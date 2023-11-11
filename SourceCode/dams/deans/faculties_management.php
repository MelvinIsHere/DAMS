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
                    <h1 class="h3 mb-1 text-gray-800">Faculty  Management</h1>

                    <div class="card-body">
                            <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-xs-6">
                            <h2>Faculties  in <?php echo $department_name;?></b></h2>
                        </div>
                        <div class="col-xs-6">
                            <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Faculty</span></a>
                              
                                                
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-hover" id="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Faculty Name</th>
                            <th>Type</th>
                            <th>Designation</th>
                            <th>Position</th>
                           
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

                            $result_count = mysqli_query($conn, "SELECT
                            COUNT(*)  AS total_records, firstname,middlename,lastname FROM faculties WHERE department_id = '$department_id'
                             AND CONCAT(firstname,middlename,lastname) LIKE '%$search%'");
                            $total_records = mysqli_fetch_array($result_count);
                            $total_records = $total_records['total_records'];
                            $total_no_of_page = ceil($total_records / $total_records_per_page);
                            $second_last = $total_no_of_page - 1;

                            $sql = "SELECT faculty_id, firstname, lastname, middlename, suffix, is_permanent, is_guest, is_partTime
                                FROM faculties
                                WHERE department_id = '$department_id'
                                AND CONCAT(firstname,middlename,lastname) LIKE '%$search%';";
                            $results = $conn->query($sql);
                            if(!$results){
                                die("Query failed: " . mysqli_error($conn));
                            }
                            $results->data_seek($off_set);
                            $count = 1;
                            while ($row = mysqli_fetch_array($results)) {
                                $id = $row['faculty_id'];
                                $first_name = $row['firstname'];
                                $last_name = $row['lastname'];
                                $middle_name = $row['middlename'];
                                $suffix = $row['suffix'];
                                $is_permanent = $row['is_permanent'];
                                $is_guest = $row['is_guest'];
                                $is_partTime = $row['is_partTime'];

                                $count++;
                            

                         ?>
                        <tr>
                            <td class="faculty_id"><?php echo $id;?></td>
                            <td><?php echo $first_name . " " . $middle_name . " " . $last_name . " " . $suffix;?></td>
                            <td><?php
                            if($is_permanent == 1){
                                echo "Permanent";
                            }
                            elseif($is_guest == 1){
                                echo "Temporary";
                            }
                            else{
                                echo "Part Time";
                            }



                            ;?></td>
                            
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

                            $result_count = mysqli_query($conn, "SELECT
                            COUNT(*)  AS total_records FROM faculties WHERE department_id = '$department_id'");
                            $total_records = mysqli_fetch_array($result_count);
                            $total_records = $total_records['total_records'];
                            $total_no_of_page = ceil($total_records / $total_records_per_page);
                            $second_last = $total_no_of_page - 1;

                            $sql = "

                                    SELECT 
                                        f.faculty_id,
                                        f.`firstname`,
                                        f.`middlename`,
                                        f.`lastname`,
                                        f.`suffix`,
                                        f.is_permanent,
                                        f.is_guest,
                                        f.is_partTime,
                                        d.designation,
                                        t.`title_description`
                                        
                                        
                                    FROM faculties f
                                    LEFT JOIN designation d ON d.designation_id = f.`designation_id`
                                    
                                    LEFT JOIN titles t ON t.`title_id` = f.position_id
                                    WHERE department_id = '$department_id'";
                            $results = $conn->query($sql);
                            if(!$results){
                                die("Query failed: " . mysqli_error($conn));
                            }
                            $results->data_seek($off_set);
                            $count = 1;
                            while ($row = mysqli_fetch_array($results)) {
                                $id = $row['faculty_id'];
                                $first_name = $row['firstname'];
                                $last_name = $row['lastname'];
                                $middle_name = $row['middlename'];
                                $suffix = $row['suffix'];
                                $is_permanent = $row['is_permanent'];
                                $is_guest = $row['is_guest'];
                                $is_partTime = $row['is_partTime'];
                                $designation = $row['designation'];
                                $position = $row['title_description'];

                                $count++;
                            

                         ?>
                        <tr>
                            <td class="faculty_id"><?php echo $id;?></td>
                            <td><?php echo $first_name . " " . $middle_name . " " . $last_name . " " . $suffix;?></td>
                            <td><?php
                            if($is_permanent == 1){
                                echo "Permanent";
                            }
                            elseif($is_guest == 1){
                                echo "Temporary";
                            }
                            else{
                                echo "Part Time";
                            }



                            ;?></td>
                            <td><?php echo $designation;?></td>
                            <td><?php echo $position;?></td>
                            
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
                    }}?>
                        
                    </tbody>
                </table>
                
            </div>
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
                var faculty_id  = $(this).closest('tr').find('.faculty_id').text();
                console.log(faculty_id);
                $('#faculty_id').val(faculty_id);;
                $('#faculty_name').val(data[1]);
                $('#type').val(data[2]);
                $('#designation_edit_input').val(data[3]);
                $('#position_edit_input').val(data[4]);                                                                        
                });
            });
                $(document).ready(function() {
                $('.delete').on('click',function(e){
                e.preventDefault();
                var faculty_id  = $(this).closest('tr').find('.faculty_id').text();
                console.log(faculty_id);
                $('#delete_id').val(faculty_id);;
                $('#deleteModal').modal('show');  
                });
            });
    </script>
                      
        </div>


                    

            </div>

                <!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form id="update_faculties" action="../php/update_faculties.php" method="POST">                
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Faculty</h5>
                </div>
                <div class="modal-body" id="editModal-body">
                    <div class="form-group">
                        <label for="faculty_name" class="form-label"> Faculty Name</label>
                        <input type="text" name="faculty_id" id="faculty_id" hidden style="height: 0px; width:0px">
                        <input class="form-control"  name="faculty_name" id="faculty_name" placeholder="Enter Faculty Name ">
                        
                    </div>                                  
                     <div class="form-group">
                        <label for="type" class="form-label">Type</label>
                        <input class="form-control" list="browsers" name="type" id="type" placeholder="Choose a faculty type" required>
                            <datalist id="browsers">                                       
                                <option value="Permanent">
                                <option value="Temporary">
                                <option value="Part Time">
                            </datalist>
                    </div>
                    <div class="form-group">
                        <label for="designation_edit_input" class="form-label">Designation</label>
                        <input type="text" name="designation" list="designation_edit" id="designation_edit_input" class="form-control" placeholder="Choose a Designation">
                        <datalist id="designation_edit" >
                                    <?php 
                                        include "../php/config.php";
                                        $query = mysqli_query($conn,"SELECT * FROM designation");
                                        if($query){
                                            if(mysqli_num_rows($query) > 0){
                                                while($row = mysqli_fetch_assoc($query)){

                                                    $designation = $row['designation'];
                                                    ?>
                                                         <option><?php echo $designation;?></option>

                                                    <?php
                                                }                                                
                                            }else{

                                            }
                                        }else{

                                        }
                                    ?>
                                </datalist>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Position</label>
                        <input type="text" name="position" list="position_edit" id="position_edit_input" class="form-control" placeholder="Choose a position">
                         <datalist id="position_edit">
                                    <?php 
                                        include "../php/config.php";
                                        $query = mysqli_query($conn,"SELECT * FROM titles");
                                        if($query){
                                            if(mysqli_num_rows($query) > 0){
                                                while($row = mysqli_fetch_assoc($query)){

                                                    $title_description = $row['title_description'];
                                                    ?>
                                                         <option><?php echo $title_description;?></option>

                                                    <?php
                                                }                                                
                                            }else{

                                            }
                                        }else{

                                        }
                                    ?>
                                </datalist>
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
                <form id="delete_loading" action="../php/delete_faculties.php" method="POST">
                    <div class="modal-header">
                    
                    <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
                </div>
                <div class="modal-body" id="deleteModal-body">
                    <input type="" name="faculty_id" id="delete_id" style="width:0px;height:0px" hidden>

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
                <form method="POST" action="../php/insert_faculties.php">
                    <div class="modal-header">                      
                        <h4 class="modal-title">Add Faculty Member</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body"> 
                        <input name="department_id" value="<?php echo $department_id ?>" hidden>                   
                      <div class="form-group">
                                           
                                   <label for="lname" class="form-label">last Name</label>
                                    <input class="form-control" name="last_name" id="lname" placeholder="Enter last name" required>
                                                      
                        </div>
                         <div class="form-group">
                           <label for="fname" class="form-label">First Name</label>
                                    <input class="form-control" name="first_name" id="fname" placeholder="Enter first name" required>
                        </div>
                        <div class="row">
                            <div class="col">
                               <div class="form-group">
                            
                                    <label for="mi" class="form-label">Middle Initial</label>
                                        <input class="form-control" name="middle_name" id="mi" placeholder="Enter middle initial">
                                </div>
                                    
                            </div>
                             <div class="col">
                               <div class="form-group">
                                    <label for="suffix" class="form-label">Suffix</label>
                                    <input class="form-control" name="suffix" id="suffix" placeholder="Enter suffix">
                                </div>
                            </div>
                        </div>
                     
                     

                       
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="browser" class="form-label">Type</label>
                                    <input class="form-control" list="browsers" name="type" id="browser" placeholder="Choose a faculty type" required>
                                    <datalist id="browsers">
                                       
                                      <option value="Permanent">
                                      <option value="Temporary">
                                      <option value="Part time">
                                    </datalist>
                            </div>
                            <div class="col-md-6">
                                  <label class="form-label">General education</label>
                                <select class="form-control" name="gened">
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                             
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="designation_in">Designation</label>
                            <input type="text" name="designation"list="designation" class="form-control" id="designation_in" placeholder="Insert Designation">
                                <datalist id="designation" >
                                    <?php 
                                        include "../php/config.php";
                                        $query = mysqli_query($conn,"SELECT * FROM designation");
                                        if($query){
                                            if(mysqli_num_rows($query) > 0){
                                                while($row = mysqli_fetch_assoc($query)){

                                                    $designation = $row['designation'];
                                                    ?>
                                                         <option><?php echo $designation;?></option>

                                                    <?php
                                                }                                                
                                            }else{

                                            }
                                        }else{

                                        }
                                    ?>
                                </datalist>

                                   
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="position_input">Position</label>
                            <input type="text" name="position" list="position_list" class="form-control" id="position_input" placeholder="Choose a position">
                            <datalist id="position_list" >
                                    <?php 
                                        include "../php/config.php";
                                        $query = mysqli_query($conn,"SELECT * FROM titles");
                                        if($query){
                                            if(mysqli_num_rows($query) > 0){
                                                while($row = mysqli_fetch_assoc($query)){

                                                    $title_description = $row['title_description'];
                                                    ?>
                                                         <option><?php echo $title_description;?></option>

                                                    <?php
                                                }                                                
                                            }else{

                                            }
                                        }else{

                                        }
                                    ?>
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



      <script>
          let table = new DataTable('#table');
      </script>


<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>-->
<!-- <script type="text/javascript">
$(document).ready(function() {
   $("#delete_loading").submit(function(e) {
      e.preventDefault(); // Prevent the form from submitting normally

      // Get the form data
      var formData = new FormData(this);

      // Send the AJAX request
      $.ajax({
         url: "../php/delete_faculties.php", // PHP file to handle the insertion
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
</script> -->
<!-- 
<script type="text/javascript">
$(document).ready(function() {
   $("#update_faculties").submit(function(e) {
      e.preventDefault(); // Prevent the form from submitting normally

      // Get the form data
      var formData = new FormData(this);

      // Send the AJAX request
      $.ajax({
         url: "../php/update_faculties.php", // PHP file to handle the insertion
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

 -->


      


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
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

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
}else{
header("Location: ../index.php");
}?>
</body>
</html>