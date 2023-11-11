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
            d.department_abbrv,
            d.department_id
            FROM users u
            LEFT JOIN departments d ON u.department_id = d.department_id
           WHERE user_id = '$id' 
    
            ");
    

   
    
    if($data){
        $row = mysqli_fetch_array($data);
         $department_name = $row['department_name'];
        $img = $row['img'];
        $type =$row['type'];
        $department_id = $row['department_id'];
        $department_abbrv = $row['department_abbrv'];
        $faculty_name = $_GET['faculty_name'];
     



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
    <center>
                            <h1 class="h3 mb-1 text-gray-800"><b> FACULTY ADMNISTRATIVE HOURS </b></h1>
        
    </center>

                    <div class="card-body">
                            <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-xs-6">
                            <h2><?php echo $_GET['faculty_name'];?> Schedule</b></h2>
                        </div>
                        <div class="col-xs-6">
                      
                            <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Set new administrative hours</span></a>

                            <!-- <a href="../php/automation_documents/generate_class_schedule.php?dept_id=<?php echo $department_id?>&dept_abbrv=<?php echo $department_abbrv?>" class="btn btn-success"><i class="material-icons">&#xE147;</i> <span>Create Document</span></a>  -->

                            
                        </div>
                                                   
                    </div>
                </div>
                <table class="table table-striped table-hover" id="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Day</th>
                            <th>schedule</th>
                           
                            
                            
                        
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


                                        

                                   FROM class_schedule cs
                                    LEFT JOIN faculties f ON f.faculty_id = cs.faculty_id
                                    LEFT JOIN departments d ON d.department_id = cs.dept_id
                                    LEFT JOIN sections s ON s.section_id = cs.section_id
                                    LEFT JOIN programs p ON p.program_id = s.program_id
                                    LEFT JOIN courses c ON c.course_id = cs.course_id
                                    LEFT JOIN rooms r ON r.room_id = cs.room_id
                                    LEFT JOIN academic_year ay ON ay.acad_year_id = cs.academic_year_id
                                    LEFT JOIN `time` t ON t.time_id = cs.time_start_id 
                                    LEFT JOIN `time` t2 ON t2.time_id = cs.time_end_id 
                                    

                                    WHERE cs.dept_id = '$department_id' AND CONCAT(f.firstname,f.middlename,f.lastname,f.suffix,r.room_name,cs.day,c.course_code) LIKE '%$search%'
                            
                            ");
                            $total_records = mysqli_fetch_array($result_count);
                            $total_records = $total_records['total_records'];
                            $total_no_of_page = ceil($total_records / $total_records_per_page);
                            $second_last = $total_no_of_page - 1;

                            $sql = "SELECT
                                    cs.class_sched_id,                                   
                                    f.firstname,
                                    f.lastname,
                                    f.middlename,
                                    f.suffix,
                                    d.department_name,
                                    p.program_abbrv,
                                    s.section_name,
                                    s.no_of_students AS 'No. of Students',
                                    c.course_code,
                                    r.room_name,
                                    ay.acad_year,
                                    cs.day,
                                    t.start,
                                    t2.end


                                        

                                    FROM class_schedule cs
                                    LEFT JOIN faculties f ON f.faculty_id = cs.faculty_id
                                    LEFT JOIN departments d ON d.department_id = cs.dept_id
                                    LEFT JOIN sections s ON s.section_id = cs.section_id
                                    LEFT JOIN programs p ON p.program_id = s.program_id
                                    LEFT JOIN courses c ON c.course_id = cs.course_id
                                    LEFT JOIN rooms r ON r.room_id = cs.room_id
                                    LEFT JOIN academic_year ay ON ay.acad_year_id = cs.academic_year_id
                                    LEFT JOIN `time` t ON t.time_id = cs.time_start_id 
                                    LEFT JOIN `time` t2 ON t2.time_id = cs.time_end_id 
                                    

                                    WHERE cs.dept_id = '$department_id' AND CONCAT(f.firstname,f.middlename,f.lastname,f.suffix,r.room_name,cs.day,c.course_code) LIKE '%$search%'
                                    ";
                            $results = $conn->query($sql);
                            if(!$results){
                                die("Query failed: " . mysqli_error($conn));
                            }
                            $results->data_seek($off_set);
                            $count = 1;
                            while ($row = mysqli_fetch_array($results)) {
                                $id = $row['class_sched_id'];
                                $faculty_name = $row['firstname'] . " " . $row['middlename'] . " " . $row['lastname'] . " " . $row['suffix'];
                                $course_code = $row['course_code'];
                                $section = "BS".$row['program_abbrv'] . " " . $row['section_name'];
                                $studs = $row['No. of Students'];
                                $lecture_hours = $row['time_start'] . " - " . $row['time_end'];
                                $day = $row['day'];
                                $room_name = $row['room_name'];

                                $count++;
                            

                         ?>
                        <tr>
                           <td class="loading_id"><?php echo $id;?></td>
                            <td><?php echo $faculty_name;?></td>


                            
                            <td><?php echo $section;?></td>
                            <td><?php echo $room_name; ?></td>
                            <td><?php echo $course_code;?></td>
                            <td><?php echo $studs; ?></td>
                            <td><?php echo $lecture_hours; ?></td>
                            <td><?php echo $day;?></td>
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
                                  COUNT(*) AS total_records


                                        

                                  FROM faculty_schedule fs
                                LEFT JOIN faculties f ON f.faculty_id = fs.faculty_id
                                LEFT JOIN departments d ON d.department_id = fs.department_id

                                LEFT JOIN semesters sm ON sm.semester_id = fs.semester_id
                                LEFT JOIN academic_year ay ON ay.acad_year_id = fs.acad_year_id


                                LEFT JOIN administrative_hours ah ON ah.`faculty_id`= fs.`faculty_id`
                                LEFT JOIN `time` t3 ON t3.time_id = ah.time_start_id
                                WHERE  CONCAT(f.lastname,' ',f.firstname,' ',f.middlename,' ',f.suffix) = '$faculty_name'
                                 AND d.department_id = '$department_id'
                            
                            ");
                            $total_records = mysqli_fetch_array($result_count);
                            $total_records = $total_records['total_records'];
                            $total_no_of_page = ceil($total_records / $total_records_per_page);
                            $second_last = $total_no_of_page - 1;

                            $sql = "  
                                  SELECT 
                                    ah.administrative_hrs_id,
                                    f.faculty_id,
                                    f.firstname,
                                    f.middlename,
                                    f.lastname,
                                    f.suffix,
                                    d.department_name,
                                    
                                    ay.acad_year,
                                    sm.sem_description,
                                    

                                    
                                    fs.day,
                                    t3.`time_s`,
                                    t4.`time_e`
                                    
                                    
                                FROM faculty_schedule fs
                                LEFT JOIN faculties f ON f.faculty_id = fs.faculty_id
                                LEFT JOIN departments d ON d.department_id = fs.department_id

                                LEFT JOIN semesters sm ON sm.semester_id = fs.semester_id
                                LEFT JOIN academic_year ay ON ay.acad_year_id = fs.acad_year_id


                                LEFT JOIN administrative_hours ah ON ah.`faculty_id`= fs.`faculty_id`
                                LEFT JOIN `time` t3 ON t3.time_id = ah.time_start_id
                                LEFT JOIN `time` t4 ON t4.time_id = ah.time_end_id
                                WHERE  CONCAT(f.lastname,' ',f.firstname,' ',f.middlename,' ',f.suffix) = '$faculty_name'
                                AND d.department_id = '$department_id';
                                    ";
                            $results = $conn->query($sql);
                            if(!$results){
                                die("Query failed: " . mysqli_error($conn));
                            }
                            $results->data_seek($off_set);
                            $count = 1;
                            while ($row = mysqli_fetch_array($results)) {
                                $id = $row['administrative_hrs_id'];
                                $faculty_name = $row['lastname'] . " " . $row['firstname'] . " " . $row['middlename'] . " " . $row['suffix'];
                                
                                
                                $sched_hours = $row['time_s']." - " .$row['time_e'];
                                $day = $row['day'];
                                

                                $count++;
                            

                         ?>
                        <tr>
                            <td class="loading_id"><?php echo $id;?></td>
                            <td><?php echo $day;?></td>  
                            <td><?php echo $sched_hours;?></td>   
                            
                            
                            
                            
                            
                            
                            <td style="display: inline-flex;">
                                 
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
                              ;
                                    
                                    $('#day').val(data[1]);
                                    


                                    

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

                <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="update_loading" method="POST" action="../php/update_administrative_hrs.php?faculty_name=<?php echo $_GET['faculty_name'] ?>">            
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Class Schedule</h5>
                </div>
                <div class="modal-body" id="editModal-body">
                   
                   <input type="text" name="loading_id" id="loading_id" hidden style="height: 0px; width:0px">
                   <input type="text" name="department_id" value="<?php echo $department_id?>" hidden style="height: 0px; width:0px">
                   <div class="form-group">                       
                       
                                 
                   </div>
                  
                             <div class="form-group">
                            <label for="days" class="form-label">Select day:</label>
                                    <input class="form-control" list="days" name="day" id="day" placeholder="Select Day" required>
                                    <datalist id="days">
                                        
                                    
                                      <option value="Monday">
                                      <option value="Tuesday">
                                      <option value="Wednesday">
                                      <option value="Thursday">
                                       <option value="Friday">
                                       <option value="Saturday">
                                        <option value="Sunday">
                                    </datalist>                      
                        </div>       

                

                        <div class="row">

                         
                            <div class="col-xs-12 col-sm-6">
                                <div  class="form-group">
                             <label for="browser" class="form-label">Class Hours Start</label>
                                    <input class="form-control"  name="time_s" id="students" placeholder="Class Hours" type="time" required>
                                  
                            </div>
                                
                            </div>
                               <div class="col-xs-12 col-sm-6">
                                <div  class="form-group">
                             <label for="browser" class="form-label">Class Hours End</label>
                                    <input class="form-control"  name="time_e" id="students" placeholder="Class Hours" type="time" required>
                                  
                            </div>
                                
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
                <form id="delete_loading" action="../php/delete_administrative_hours.php?faculty_name=<?php echo $_GET['faculty_name'] ?>" method="POST">
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
        <div class="modal-dialog modal-fullscreen-sm-down">
            <div class="modal-content">
                <form method="POST" action="../php/set_new_administrative_hours.php?faculty_name=<?php echo $_GET['faculty_name']; ?>">
                    <div class="modal-header">                      
                        <h4 class="modal-title">Add Class Schedule</h4>
                        <input list="lnames" name="faculty" id="lname" value="<?php echo $_GET['faculty_name'] ?>" style="width: 0px;height: 0px; display: none;">
                        <input list="lnames" name="department_id" hidden value="<?php echo $department_id; ?>" style="width: 0px;height: 0px; ">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">                    
                      
                       <div class="form-group">
                            <label for="days" class="form-label">Select day:</label>
                                    <input class="form-control" list="days" name="day" id="day" placeholder="Select Day" required>
                                    <datalist id="days">
                                        
                                    
                                      <option value="Monday">
                                      <option value="Tuesday">
                                      <option value="Wednesday">
                                      <option value="Thursday">
                                       <option value="Friday">
                                       <option value="Saturday">
                                        <option value="Sunday">
                                    </datalist>                      
                        </div>


                          
                     

                        <div class="form-group">
                            <div class="row">
                                
                               
                              
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div  class="form-group">
                                    <label for="time_s" class="form-label">Time Start</label>
                                    <input class="form-control"  name="time_start" id="time_s" placeholder="Time Start" type="time" required>
                                  
                                 </div>

                            </div>
                            <div class="col-xs-12 col-sm-6">
                                 <div class="form-group">
                                    <label for="time_e" class="form-label">Time End:</label>
                                    <input class="form-control"  name="time_end" id="time_e" placeholder="Time End" type="time" id="time_s" required>
                                                      
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





 <!-- administrative hours Modal HTML -->
    <!-- <div id="administrative_hours" class="modal fade">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                
            </div>
        </div>
    </div> -->
    <!-- Delete Modal -->
  


<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>-->
<!--<script type="text/javascript">-->
<!--$(document).ready(function() {-->
<!--   $("#delete_loading").submit(function(e) {-->
      <!--e.preventDefault(); // Prevent the form from submitting normally-->

      <!--// Get the form data-->
<!--      var formData = new FormData(this);-->

      <!--// Send the AJAX request-->
<!--      $.ajax({-->
         <!--url: "../php/delete_faculty_loading.php", // PHP file to handle the insertion-->
<!--         type: "POST",-->
<!--         data: formData,-->
<!--         processData: false,-->
<!--         contentType: false,-->
<!--         success: function(response) {-->
            <!--// Handle the response from the PHP file-->
<!--            $("#delete_loading").trigger('reset');-->
<!--            alert(response);-->
<!--            $('#deleteModal').modal('hide'); -->

<!--         },-->
<!--         error: function(xhr, status, error) {-->
<!--            // Handle errors-->
<!--            console.error(error); // Log the error message-->
<!--         }-->
<!--      });-->
<!--   });-->
<!--});-->
<!--</script>-->

<!--<script type="text/javascript">-->
<!--$(document).ready(function() {-->
<!--   $("#update_loading").submit(function(e) {-->
      <!--e.preventDefault(); // Prevent the form from submitting normally-->

      <!--// Get the form data-->

<!--      var formData = new FormData(this);-->

      <!--// Send the AJAX request-->
<!--      $.ajax({-->
<!--         url: "../php/update_faculty_loading.php", // PHP file to handle the insertion-->
<!--         type: "POST",-->
<!--         data: formData,-->
<!--         processData: false,-->
<!--         contentType: false,-->
<!--         success: function(response) {-->
<!--            // Handle the response from the PHP file-->
            
<!--            alert(response);-->
<!--            $('#editModal').modal('hide'); -->

<!--         },-->
<!--         error: function(xhr, status, error) {-->
<!--            // Handle errors-->
<!--            console.error(error); // Log the error message-->
<!--         }-->
<!--      });-->
<!--   });-->
<!--});-->
<!--</script>-->




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
    <script src="js/sb-admin-2.min.js"></script>



<?php }
}?>
</body>
</html>