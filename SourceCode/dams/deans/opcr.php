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

<div class="container-fluid " id="createfill-Up">
    <center><b><h1 class="h3 mb-1 text-gray-800">Office Performance Commitment and Review</h1></b></center>
      

        <div class="card-body">
            <div class="table-responsive">
                <div class="table-wrapper">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-xs-6">
                                <h2>Office Performance Commitment and Review</b></h2>
                            </div>
                            <div class="col-xs-6">
                                <a href="#addEmployeeModal" class="btn btn-success" data-toggle ="modal" ><i class="material-icons">&#xE147;</i> <span>Add output</span></a>
                                <a href="../php/automation_documents/generate_opcr.php?dept_id=<?php echo $department_id;?>" class="btn btn-success"><i class="material-icons">&#xE147;</i> <span>Create Document</span></a>
                                                
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped table-hover" style="table-layout: fixed;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Major Final Output</th>
                                <th>Success Indicator</th>
                                <th>Budget</th>
                                <th>Category</th>
                                <th>Description</th>
                                
                                <th>Actual Accomplishment</th>
                                <th>Rating</th>
                                <th>Remarks</th>                            
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
                                        COUNT(*) AS total_records
                                        FROM ipcr_table i
                                        LEFT JOIN departments dp ON dp.`department_id`=i.`department_id`
                                        WHERE i.`department_id` = '$department_id' # insert dept_id
                                        AND major_output LIKE '%$search%'");
                                    $total_records = mysqli_fetch_array($result_count);
                                    $total_records = $total_records['total_records'];
                                    $total_no_of_page = ceil($total_records / $total_records_per_page);
                                    $second_last = $total_no_of_page - 1;
                                    $sql = "SELECT
                                                i.ipcr_id,
                                                i.major_output,
                                                i.success_indicator,
                                                i.actual_accomplishment,
                                                i.rating,
                                                i.remarks,
                                                i.category,
                                                i.description,
                                                dp.department_name
                                            FROM ipcr_table i
                                            LEFT JOIN departments dp ON dp.`department_id`=i.`department_id`
                                            WHERE i.`department_id` = '$department_id'
                                            AND major_output LIKE '%$search%'";                                    
                                    $results = $conn->query($sql);
                                    if(!$results){
                                        die("Query failed: " . mysqli_error($conn));
                                    }
                                    $results->data_seek($off_set);
                                    $count = 1;
                                    while ($row = mysqli_fetch_array($results)) {
                                        $id = $row['ipcr_id'];
                                        $major_output = $row['major_output'];
                                        $success_indicator = $row['success_indicator'];
                                        $actual_accomplishment = $row['actual_accomplishment'];
                                        $rating = $row['rating'];
                                        $remarks = $row['remarks'];
                                        $department_name = $row['department_name'];   
                                        $category = $row['category'];
                                        $description = $row['description'];                          
                                        $count++;                            
                            ?>
                                        <tr>
                                            <td class="loading_id"><?php echo $id;?></td>
                                            
                                            <td><?php echo $major_output;?></td>
                                            <td><?php echo $success_indicator;?></td>
                                            <td><?php echo $category;?></td>
                                            <td><?php echo $description;?></td>
                                            <td><?php echo $actual_accomplishment;?></td>
                                            <td><?php echo $rating; ?></td>
                                            <td><?php echo $remarks; ?></td>                           
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
                                }else{                    
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
                                        COUNT(*) AS total_records
                                        FROM opcr i
                                        LEFT JOIN departments dp ON dp.`department_id`=i.`department_id`
                                        WHERE i.`department_id` = '$department_id'");
                                    $total_records = mysqli_fetch_array($result_count);
                                    $total_records = $total_records['total_records'];
                                    $total_no_of_page = ceil($total_records / $total_records_per_page);
                                    $second_last = $total_no_of_page - 1;
                                    $sql = "SELECT 
                                                o.opcr_id,
                                                u.user_id,
                                                o.mfo_ppa,
                                                o.success_indicator,
                                                o.budgets,
                                                o.disipline,
                                                o.actual_accomplishment,
                                                o.rating,
                                                o.remarks,
                                                o.category,
                                                o.description
                                                    
                                            FROM opcr o
                                            LEFT JOIN users u ON u.user_id = o.dean_id
                                            LEFT JOIN departments dp ON dp.`department_id` = o.department_id
                                            WHERE o.`department_id` = '$department_id'";                                    
                                    $results = $conn->query($sql);
                                    if(!$results){
                                        die("Query failed: " . mysqli_error($conn));
                                    }
                                    $results->data_seek($off_set);
                                    $count = 1;
                                    while ($row = mysqli_fetch_array($results)) {
                                        $id = $row['opcr_id'];
                                        $major_output = $row['mfo_ppa'];
                                        $success_indicator = $row['success_indicator'];
                                        $budget = $row['budgets'];
                                        $actual_accomplishment = $row['actual_accomplishment'];
                                        $rating = $row['rating'];
                                        $remarks = $row['remarks'];
                                        $category = $row['category'];
                                        $description = $row['description'];
                                           
                                        
                                                                  
                                        $count++;                            
                            ?>
                                        <tr>
                                            <td class="loading_id"><?php echo $id;?></td>
                                            
                                            <td><?php echo $major_output;?></td>
                                            <td><?php echo $success_indicator;?></td>
                                            <td><?php echo $budget;?></td>
                                            <td><?php echo $category; ?></td>
                                            <td><?php echo $description; ?></td>
                                            
                                            
                                            <td><?php echo $actual_accomplishment;?></td>
                                            <td><?php echo $rating; ?></td>
                                            <td><?php echo $remarks; ?></td>                           
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
                $('#loading_id').val(loading_id);
                $('#mfo_update').val(data[1]);

                $('#success_indicator').val(data[2]);    
                $('#budget_update').val(data[3]);            
                $('#category_update').val(data[4]);
                $('#description_update').val(data[5]);
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
</div>


                    

            </div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="update_loading" action="../php/update_opcr.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Form</h5>
                </div>
                <div class="modal-body" id="editModal-body">
                    <div class="form-group">
                           <label for="faculty_name" class="form-label">Major Final Output</label>
                            <input type="text" name="loading_id" id="loading_id" hidden style="height: 0px; width:0px">
                            <input class="form-control" name="mfo" id="mfo_update" > 
                    </div>
                
                    <div class="form-group">
                         <label for="course_code" class="form-label">Success Indicators</label>
                         <textarea class="form-control" name="success_indicators" id="success_indicator" placeholder="Success Indicators"></textarea>

                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label  for="category_update"class="form-label">Category</label>
                            <select name="category" id="category_update" class="form-control">
                                <option value="ADMINISTRATIV/STRATEGIC FUNCTION">Aministrative/ Strategic Functions</option>
                                <option value="CORE FUNCTION">Core functions</option>                                
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label  for="budget_update"class="form-label">Budget</label>
                            <input type="text" name="budget" class="form-control" id="budget_update" placeholder="Indicate budget">
                        </div>                           
                    </div>
                        <div class="form-group">
                            <label class="form-label" for="description">Description</label>
                            <!-- <input class="form-control" id="description" name="description" placeholder="Description"> -->
                            <select class="form-control" name="description" id="description_update">
                                <option>HIGHER EDUCATION</option>
                                <option>RESEARCH</option>
                            </select>
                        </div>

                   
                    
                    

<!-- ########################################################################################################################## -->                                   </div>
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
                <form id="delete_loading" action="../php/delete_opcr.php" method="POST">
                    <div class="modal-header">
                    
                    <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
                </div>
                <div class="modal-body" id="deleteModal-body">
                    <input type="" name="loading_id" id="delete_id" hidden>

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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="../php/insert_opcr.php">
                    <div class="modal-header">                      
                        <h4 class="modal-title">Add New Course</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">                    
                        <div class="form-group">

                            <label for="mfo_input" class="form-label">Major Final Output</label>
                            <input type="text" name="department_id" value="<?php echo $department_id?>" style="width: 0px;height: 0px;" hidden>
                            <input class="form-control"  name="mfo" id="mfo_input" placeholder="Enter major final output" required>


                        </div>
                        
                        <div class="form-group">
                            <label  for="textarea"class="form-label">Success Indicator</label>
                            <textarea id="textarea" class="form-control" name="success_indicator" placeholder="Success Indicator">
                                
                            </textarea>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label  for="category"class="form-label">Category</label>
                                <select name="category" id="category" class="form-control">
                                    <option value="ADMINISTRATIV/STRATEGIC FUNCTION">Aministrative/ Strategic Functions</option>
                                    <option value="CORE FUNCTION">Core functions</option>                                
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label  for="budget"class="form-label">Budget</label>
                                <input type="text" name="budget" class="form-control" id="budget" placeholder="Indicate budget">                                
                                
                            </div>
                           
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="description">Description</label>
                            <!-- <input class="form-control" id="description" name="description" placeholder="Description"> -->
                            <select class="form-control" name="description">
                                <option>HIGHER EDUCATION</option>
                                <option>RESEARCH</option>
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

<!--   <script>
    const successIndicators = [];
    function next() {
        var page = document.getElementById('page').innerHTML;
        var pageInt = parseInt(page);
      
        openTab(pageInt, event); // Pass event object as a parameter
        document.getElementById("next").style.display = "none";
        document.getElementById("submit").style.display = "block";
        
        
        
    }

    function openTab(tabId,event) {
        var tabId = tabId;
        var prev_page = "tab".concat(tabId);
        var next_page = "tab".concat(tabId+1)
        document.getElementById(prev_page).style.display="none";
        document.getElementById(next_page).style.display="block";
        document.getElementById('previous').disabled = false;
        document.getElementById('page').innerHTML = tabId+1;


    }
     function prev() {
        var page = document.getElementById('page').innerHTML;
        var pageInt = parseInt(page);
        
        prevpage(pageInt, event); // Pass event object as a parameter
        document.getElementById("next").style.display = "block";
        document.getElementById("submit").style.display = "none";
    }

    function prevpage(tabId,event) {
        var tabId = tabId;
        var currentPage = "tab".concat(tabId);
        var prev_page = "tab".concat(tabId-1)
        document.getElementById(prev_page).style.display="block";
        document.getElementById(currentPage).style.display="none";

        document.getElementById('page').innerHTML = tabId-1;


    }
  function addSuccessIndicator() {
    var success_indicator = document.getElementById("success_indicator_input");
    var success_indicators_textarea = document.getElementById("input_success_indicator");
// Get the value from the input field
    var edited_indicator = success_indicator.value;

    // Add the value to the successIndicators array
    successIndicators.push(edited_indicator);

    // Clear the input field
    success_indicator.value = "";

    // Update the textarea with newline-separated values
    success_indicators_textarea.value = successIndicators.join('\n');
}

</script> -->




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

    <!-- Custom scripts for all pages-->
    <!-- <script src="js/sb-admin-2.min.js"></script> -->



<?php }
}
else{

    header("Location: ../index.php");
}?>
</body>
</html>