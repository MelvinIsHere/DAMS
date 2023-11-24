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
                                f.faculty_id,
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
        $faculty_id = $row['faculty_id'];



?>
<!DOCTYPE html>
<html>
<?php  include "../header/header_deans.php"?>
<body>
    <div id="wrapper">
    <?php include "../sidebar/sidebar_deans.php";?>
  <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <?php include "../topbar/topbar_deans.php"; ?>
                <div class="container-fluid tabcontent" id="createfill-Up">
                   

                <div class="card-body">
                    <div class="table-responsive">
                        <div class="table-wrapper">
                                <div class="table-title">
                    <div class="row">
                        <div class="col d-flex justify-content-start">
                            
                              <a href="#rcot"  class="btn btn-success" id="rcot_open_modal" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Request Change of Official Time</span></a>
                        </div>
                        <div class="col d-flex justify-content-start">
                            <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                                <div class="input-group">
                                    <input type="text" name="search" value="<?php if(isset($_GET['search'])){echo $_GET['search']; } ?>" class="form-control bg-light " placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                                <?php 
                                    if(isset($_GET['faculty_name'])){ ?>
                                      <input type="text" name="faculty_name" style="width:0px;height:0px;display: none;" value="<?php  if(isset($_GET['faculty_name'])){echo $_GET['faculty_name']; } ?>">
                                <?php }

                                ?>
                                <?php

                                    if(isset($_GET['section_name'])){?>
                                        <input type="text" name="section_name" style="width:0px;height:0px;display: none;" value="<?php 
                                            if(isset($_GET['section_name'])){echo $_GET['section_name']; } ?>">
                                <?php }?>
                             
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
                            
                            <th>start</th>
                            <th>End</th>
                            <th>Day</th>
                            <th>Schedule</th>
                            
                            
                        
                            <!-- <th>Actions</th> -->
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


                                        

                                    FROM official_time ot
                                    LEFT JOIN tasks tt ON tt.`task_id` = ot.task_id
                                    LEFT JOIN faculties f ON f.`faculty_id` = ot.faculty_id
                                    LEFT JOIN users u ON u.faculty_id = f.faculty_id
                                    WHERE f.department_id = '$department_id'
                                    AND u.user_id = '$id'
                                    AND tt.term_id = '$term_id'
                                    AND CONCAT(ot.day) LIKE '%$search%'
                            
                            ");
                            $total_records = mysqli_fetch_array($result_count);
                            $total_records = $total_records['total_records'];
                            $total_no_of_page = ceil($total_records / $total_records_per_page);
                            $second_last = $total_no_of_page - 1;

                            $sql = "  SELECT
                                        ot.official_id,
                                        ot.time_start,
                                        ot.time_end,
                                        ot.day,
                                        ot.day_sched
                                        
                                    FROM official_time ot
                                    LEFT JOIN tasks tt ON tt.`task_id` = ot.task_id
                                    LEFT JOIN faculties f ON f.`faculty_id` = ot.faculty_id
                                    LEFT JOIN users u ON u.faculty_id = f.faculty_id
                                    WHERE f.department_id = '$department_id'
                                    AND u.user_id = '$id'
                                    
                                    AND tt.term_id = '$term_id'
                                    AND CONCAT(ot.day) LIKE '%$search%'
                                    ";
                            $results = $conn->query($sql);
                            if(!$results){
                                die("Query failed: " . mysqli_error($conn));
                            }
                            $results->data_seek($off_set);
                            $count = 1;
                            while ($row = mysqli_fetch_array($results)) {
                                $official_id = $row['official_id'];
                               
                                $time_start = $row['time_start'];
                               
                                
                                
                                
                                $time_end = $row['time_end'];
                                $day = $row['day'];
                                $day_sched = $row['day_sched'];
                               
                                $count++;
                            

                         ?>
                        <tr>
                            <td class="loading_id"><?php echo $official_id;?></td>
                            
                            <td><?php echo $time_start;?></td>     
                                                          
                             
                            <td><?php echo $time_end;?></td>  
                            <td><?php echo $day;?></td>   
                            <td><?php echo $day_sched;?></td>     
                            
                            
                            
                          <!--   
                            <td style="display: inline-flex;">
                                 
                                <a href="#editEmployeeModal" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                                <a href="#deleteEmployeeModal" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                            </td> -->
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
                                  COUNT(*) AS total_records


                                        

                                    
                                       
                                    FROM official_time ot
                                    LEFT JOIN tasks tt ON tt.`task_id` = ot.task_id
                                    LEFT JOIN faculties f ON f.`faculty_id` = ot.faculty_id
                                    LEFT JOIN users u ON u.faculty_id = f.faculty_id
                                    WHERE f.department_id = '$department_id'
                                    AND u.user_id = '$id'
                                    AND tt.term_id = '$term_id'
                            ");
                            $total_records = mysqli_fetch_array($result_count);
                            $total_records = $total_records['total_records'];
                            $total_no_of_page = ceil($total_records / $total_records_per_page);
                            $second_last = $total_no_of_page - 1;

                            $sql = "  
                                    SELECT
                                        ot.official_id,
                                        ot.time_start,
                                        ot.time_end,
                                        ot.day,
                                        ot.day_sched
                                        
                                   FROM official_time ot
                                    LEFT JOIN tasks tt ON tt.`task_id` = ot.task_id
                                    LEFT JOIN faculties f ON f.`faculty_id` = ot.faculty_id
                                    LEFT JOIN users u ON u.faculty_id = f.faculty_id
                                    WHERE f.department_id = '$department_id'
                                    AND u.user_id = '$id'
                                    AND tt.term_id = '$term_id'
                                    ";
                            $results = $conn->query($sql);
                            if(!$results){
                                die("Query failed: " . mysqli_error($conn));
                            }
                            $results->data_seek($off_set);
                            $count = 1;
                            while ($row = mysqli_fetch_array($results)) {
                                $official_id = $row['official_id'];
                               
                                $time_start = $row['time_start'];
                               
                                
                                
                                
                                $time_end = $row['time_end'];
                                $day = $row['day'];
                                $day_sched = $row['day_sched'];
                               
                                $count++;
                            

                         ?>
                        <tr>
                            <td class="loading_id"><?php echo $official_id;?></td>
                            
                            <td><?php echo $time_start;?></td>     
                                                          
                             
                            <td><?php echo $time_end;?></td>  
                            <td><?php echo $day;?></td>   
                            <td><?php echo $day_sched;?></td>     
                            
                            
                            
                            
                            
                            <!-- <td style="display: inline-flex;">
                                 
                                <a href="#editEmployeeModal" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                                <a href="#deleteEmployeeModal" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                            </td> -->
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
        <a <?php if ($page_no > 1) { echo "href='?page_no=$previous_page&faculty_name={$_GET["faculty_name"]}'"; } ?>>Previous</a>
    </li>

    <?php
    if ($total_no_of_page <= 10) {
        for ($counter = 1; $counter <= $total_no_of_page; $counter++) {
            if ($counter == $page_no) {
                echo "<li class='active page-item'><a>$counter</a></li>";
            } else {
                echo "<li><a href='?page_no=$counter&faculty_name={$_GET["faculty_name"]}'>$counter</a></li>";

            }
        }
    } elseif ($total_no_of_page > 10) {
        if ($page_no <= 4) {
            for ($counter = 1; $counter <= 8; $counter++) {
                if ($counter == $page_no) {
                    echo "<li class='active page-item'><a>$counter</a></li>";
                } else {
                    echo "<li><a href='?page_no=$counter&faculty_name={$_GET["faculty_name"]}'>$counter</a></li>";
                }
            }
            echo "<li class='page-item'><a>...</a></li>";
            echo "<li class='page-item'><a href='?page_no=$second_lastr&faculty_name={$_GET["faculty_name"]}'>$second_last</a></li>";
            echo "<li class='page-item'><a href='?page_no=$total_no_of_page&faculty_name={$_GET["faculty_name"]}'>$total_no_of_page</a></li>";
        } elseif ($page_no > 4 && $page_no < $total_no_of_page - 4) {
            echo "<li class='page-item'><a href='?page_no=1'>1</a></li>";
            echo "<li class='page-item'><a href='?page_no=2'>2</a></li>";
            echo "<li class='page-item'><a>...</a></li>";

            for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {
                if ($counter == $page_no) {
                    echo "<li class='active page-item'><a>$counter</a></li>";
                } else {
                    echo "<li><a href='?page_no=$counter&faculty_name={$_GET["faculty_name"]}'>$counter</a></li>";
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
                    echo "<li><a href='?page_no=$counter&faculty_name={$_GET["faculty_name"]}'>$counter</a></li>";
                }
            }
        }
    }
    ?>
    <li <?php if ($page_no >= $total_no_of_page) { echo "class='disabled page-item'"; } ?>>
        <a <?php if ($page_no < $total_no_of_page) { echo "href='?page_no=$next_page&faculty_name={$_GET["faculty_name"]}'"; } ?>>Next</a>
    </li>
    <?php
    if ($page_no < $total_no_of_page) {
        echo "<li class = 'page-item'><a href='?page_no=$total_no_of_page&faculty_name={$_GET["faculty_name"]}'>Last &rsquo;</a></li>";
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

                                     var loading_id  = $(this).closest('tr').find('.loading_id').text();
                                    console.log(loading_id)
                                    $('#loading_id').val(loading_id);;
                                    $('#start').val(data[1]);                                    
                                    $('#end').val(data[2]);
                                    $('#day').val(data[3]);
                                    
                                    


                                    

                                });
                                });
                                $(document).ready(function() {
                                $('#rcot_open_modal').on('click',function(){
                                    $('#rcot').modal('show');


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


                                 $(document).ready(function() {
                                $('.administrative_hours').on('click',function(){
                                    $('#administrative_hours').modal('show');

                               
                                    


                                    

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
    <!-- Edit Modal HTML -->
    <div id="rcot" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="../php/rcot_pointer.php">
                    <div class="modal-header">                      
                        <h4 class="modal-title">Request of change of official time</h4>
                        <input name="user_id" id="lname" value="<?php echo $id;?>" hidden> 
                        
                        <input  name="department_name"  value="<?php echo $department_name; ?>" hidden>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body"> 
                        <label class="form-label"><h5>Monday</h5></label><br>
                        <div class="form-group">
                                
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label mt-2"><h6>Official time morning</h6></label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Official Start</label>
                                                <input type="text" name="Official_start_morning_mon" class="form-control">
                                    
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Official end</label>
                                                <input type="text" name="Official_end_morning_mon" class="form-control">
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label mt-2"><h6>Official time afternoon</h6></label>
                                        <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label">Official Start</label>
                                                    <input type="text" name="Official_start_noon_mon" class="form-control">
                                    
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Official end</label>
                                                    <input type="text" name="Official_end_noon_mon" class="form-control">
                                                </div>
                                        </div>
                                        
                                         
                                    </div>
                                        
                                       
                                </div>


                        </div>  

                        <label class="form-label"><h5>Tuesday</h5></label><br>
                        <div class="form-group">
                                
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label mt-2"><h6>Official time morning</h6></label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Official Start</label>
                                                <input type="text" name="Official_start_morning_tues" class="form-control">
                                    
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Official end</label>
                                                <input type="text" name="Official_end_morning_tues" class="form-control">
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label mt-2"><h6>Official time afternoon</h6></label>
                                        <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label">Official Start</label>
                                                    <input type="text" name="Official_start_noon_tues" class="form-control">
                                    
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Official end</label>
                                                    <input type="text" name="Official_end_noon_tues" class="form-control">
                                                </div>
                                        </div>                                                                                
                                    </div>                                                                               
                                </div>
                        </div> 


                        <label class="form-label"><h5>Wednesday</h5></label><br>
                        <div class="form-group">
                                
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label mt-2"><h6>Official time morning</h6></label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Official Start</label>
                                                <input type="text" name="Official_start_morning_wed" class="form-control">
                                    
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Official end</label>
                                                <input type="text" name="Official_end_morning_wed" class="form-control">
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label mt-2"><h6>Official time afternoon</h6></label>
                                        <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label">Official Start</label>
                                                    <input type="text" name="Official_start_noon_wed" class="form-control">
                                    
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Official end</label>
                                                    <input type="text" name="Official_end_noon_wed" class="form-control">
                                                </div>
                                        </div>                                                                                
                                    </div>                                                                               
                                </div>
                        </div>         
                        <label class="form-label"><h5>Thursday</h5></label><br>
                        <div class="form-group">
                                
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label mt-2"><h6>Official time morning</h6></label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Official Start</label>
                                                <input type="text" name="Official_start_morning_thurs" class="form-control">
                                    
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Official end</label>
                                                <input type="text" name="Official_end_morning_thurs" class="form-control">
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label mt-2"><h6>Official time afternoon</h6></label>
                                        <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label">Official Start</label>
                                                    <input type="text" name="Official_start_noon_thurs" class="form-control">
                                    
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Official end</label>
                                                    <input type="text" name="Official_end_noon_thurs" class="form-control">
                                                </div>
                                        </div>                                                                                
                                    </div>                                                                               
                                </div>
                        </div>       
                        <label class="form-label"><h5>Friday</h5></label><br>
                        <div class="form-group">
                                
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label mt-2"><h6>Official time morning</h6></label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Official Start</label>
                                                <input type="text" name="Official_start_morning_fri" class="form-control">
                                    
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Official end</label>
                                                <input type="text" name="Official_end_morning_fri" class="form-control">
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label mt-2"><h6>Official time afternoon</h6></label>
                                        <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label">Official Start</label>
                                                    <input type="text" name="Official_start_noon_fri" class="form-control">
                                    
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Official end</label>
                                                    <input type="text" name="Official_end_noon_fri" class="form-control">
                                                </div>
                                        </div>                                                                                
                                    </div>                                                                               
                                </div>
                        </div>
                        <label class="form-label"><h5>Saturday</h5></label><br>
                        <div class="form-group">
                                
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label mt-2"><h6>Official time morning</h6></label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Official Start</label>
                                                <input type="text" name="Official_start_morning_sat" class="form-control">
                                    
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Official end</label>
                                                <input type="text" name="Official_end_morning_sat" class="form-control">
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label mt-2"><h6>Official time afternoon</h6></label>
                                        <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label">Official Start</label>
                                                    <input type="text" name="Official_start_noon_sat" class="form-control">
                                    
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Official end</label>
                                                    <input type="text" name="Official_end_noon_sat" class="form-control">
                                                </div>
                                        </div>                                                                                
                                    </div>                                                                               
                                </div>
                        </div>      
                         <label class="form-label"><h5>Sunday</h5></label><br>
                        <div class="form-group">
                                
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label mt-2"><h6>Official time morning</h6></label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Official Start</label>
                                                <input type="text" name="Official_start_morning_sun" class="form-control">
                                    
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Official end</label>
                                                <input type="text" name="Official_end_morning_sun" class="form-control">
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label mt-2"><h6>Official time afternoon</h6></label>
                                        <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label">Official Start</label>
                                                    <input type="text" name="Official_start_noon_sun" class="form-control">
                                    
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Official end</label>
                                                    <input type="text" name="Official_end_noon_sun" class="form-control">
                                                </div>
                                        </div>                                                                                
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
                <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <form id="update_loading" method="POST" action="../php/update_official_time.php">            
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change official time</h5>
                </div>
                <div class="modal-body" id="editModal-body">
                   <label for="faculty_name" class="form-label">Official start</label>

                   <input type="text" name="loading_id" id="loading_id" hidden style="height: 0px; width:0px">
                   <input type="text" name="faculty_name" id="faculty_name" value ="<?php echo $faculty_name;?>">
                   <input type="text" name="department_id" value="<?php echo $department_id?>" hidden style="height: 0px; width:0px">
                   <input type="text" name="day" id="day">
                   <div class="form-group">                       
                        <input type="time"class="form-control" name="start" id="start" placeholder="Enter Official start" >
                                 
                   </div>
                   <div class="form-group">                           
                             <label for="end" class="form-label">Official end</label>
                                    <input type="time" class="form-control" name="end" id="end"  placeholder="Enter Official end">
                                  
                        

                   </div>
                                   

                <!--     <div class="form-group">
                             <label for="section" class="form-label">Section</label>
                                    <input class="form-control"  name="section" id="section" disabled>
                                   
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
                <form id="delete_loading" action="../php/delete_official_time.php?faculty_name=<?php echo $_GET['faculty_name'] ?>" method="POST">
                    <div class="modal-header">
                    
                    <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
                </div>
                <div class="modal-body" id="deleteModal-body">
                    <input type="text" name="loading_id" id="delete_id" style="width:0px;height:0px" hidden>

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





    <script>
document.getElementById("time_s").addEventListener("change", function() {
    var inputTime = this.value;
    var date = new Date("2000-01-01T" + inputTime + ":00");
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var period = hours >= 12 ? "PM" : "AM";
    hours = hours % 12 || 12; // Convert to 12-hour format
    var formattedTime = hours + ":" + (minutes < 10 ? "0" : "") + minutes + " " + period;
    document.getElementById("time_s_12hr").value = formattedTime;
});
</script>
<script>
    function toggleCourseCodeInput() {
        var descriptionsSelect = document.getElementById('descriptions');
        var courseCodeInput = document.getElementById('course_code');
        var sectionCodeInput = document.getElementById('section_dis');
        var seclabel = document.getElementById('section_dis_label');
        var courselabel = document.getElementById('course_code_label');
        var roomlabel = document.getElementById('room_add_label');
        var room_input = document.getElementById('room_add_input');
        var room = document.getElementById('rooms');

        

        if (descriptionsSelect.value === 'Class Schedule') {
                       courseCodeInput.style.display = 'block';
            sectionCodeInput.style.display = 'block';
            seclabel.style.display = 'block';
            courselabel.style.display = 'block';
             roomlabel.style.display = 'block';
            room.style.display = 'block';
        }else if(descriptionsSelect.value === 'RCOT'){
                courseCodeInput.style.display = 'none';
            sectionCodeInput.style.display = 'none';
            seclabel.style.display = 'none';
            courselabel.style.display = 'none';
            roomlabel.style.display = 'none';
            room.style.display = 'none';
            room_input.style.display = 'none';
        } 
        else {
            courseCodeInput.style.display = 'none';
            sectionCodeInput.style.display = 'none';
            seclabel.style.display = 'none';
            courselabel.style.display = 'none';
        }
    }
</script>








      <script>
          let table = new DataTable('#table');
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
     <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>



<?php }
}?>
</body>
</html>