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
        $faculty_name = $_GET['faculty_name'];
        $term_id = $_SESSION['term_id'];



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
                    <center>
                            <h1 class="h3 mb-1 text-gray-800"> FACULTY SCHEDULE</h1>
                    </center>

                <div class="card-body">
                    <div class="table-responsive">
                        <div class="table-wrapper">
                                <div class="table-title">
                    <div class="row">
                        <div class="col d-flex justify-content-start">
                              <a href="../php/automation_documents/generate_faculty_schedule.php?dept_id=<?php echo $department_id;?>&dept_abbrv=<?php echo $department_abbrv;?>&department_name=<?php echo $department_name; ?>" class="btn btn-success"><i class="material-icons">&#xE147;</i> <span>Create Document</span></a> 
                              <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Faculty Schedule</span></a>
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
                            <th>Name of Faculty</th>
                            <th>Description</th>
                            <th>Day</th>
                            <th>Schedule hours</th>
                            <th>Room</th>
                            
                            
                        
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                                include "../config.php";
                                
                            if(isset($_GET['search']) && isset($_GET['faculty_name'])){
                                $search = $_GET['search'];
                                $faculty_name = $_GET['faculty_name'];
                                $faculty_name_rplc = str_replace("+",'%20',$faculty_name);
                                
                            

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
                                    LEFT JOIN courses c ON c.course_id = fs.course_id
                                    LEFT JOIN rooms r ON r.room_id = fs.room_id
                                    LEFT JOIN semesters sm ON sm.semester_id = fs.semester_id
                                    LEFT JOIN academic_year ay ON ay.acad_year_id = fs.acad_year_id
                                    LEFT JOIN sections s ON s.section_id = fs.section_id
                                    LEFT JOIN programs p ON p.program_id = s.program_id

                                    

                                    WHERE fs.department_id = '$department_id'
                                    AND CONCAT(f.lastname,' ',f.firstname,' ',f.middlename,' ',f.suffix) = '$faculty_name_rplc'
                                    AND CONCAT(fs.day) LIKE '%$search%'
                            
                            ");
                            $total_records = mysqli_fetch_array($result_count);
                            $total_records = $total_records['total_records'];
                            $total_no_of_page = ceil($total_records / $total_records_per_page);
                            $second_last = $total_no_of_page - 1;

                            $sql = "  SELECT 
                                        fs.faculty_sched_id,
                                        f.firstname,
                                        f.middlename,
                                        f.lastname,
                                        f.suffix,
                                        d.department_name,
                                        c.course_code,
                                        r.room_name,
                                        ay.acad_year,
                                        sm.sem_description,
                                        s.section_name,
                                        p.program_abbrv,
                                        t.time_s,
                                        
                                        t2.time_e,
                                        fs.day,
                                        fs.description
                                        
                                    FROM faculty_schedule fs
                                    LEFT JOIN faculties f ON f.faculty_id = fs.faculty_id
                                    LEFT JOIN departments d ON d.department_id = fs.department_id
                                    LEFT JOIN courses c ON c.course_id = fs.course_id
                                    LEFT JOIN rooms r ON r.room_id = fs.room_id
                                    LEFT JOIN semesters sm ON sm.semester_id = fs.semester_id
                                    LEFT JOIN academic_year ay ON ay.acad_year_id = fs.acad_year_id
                                    LEFT JOIN sections s ON s.section_id = fs.section_id
                                    LEFT JOIN programs p ON p.program_id = s.program_id
                                    LEFT JOIN `time` t ON t.time_id = fs.time_start_id
                                    LEFT JOIN `time` t2 ON t2.time_id = fs.time_end_id


                                    

                                    WHERE fs.department_id = '$department_id'
                                    -- AND CONCAT(f.lastname,' ',f.firstname,' ',f.middlename,' ',f.suffix) = '$faculty_name'
                                    AND CONCAT(f.lastname,' ',f.firstname,' ',f.middlename,' ',f.suffix) = '$faculty_name_rplc'
                                    AND fs.day LIKE '%$search%'
                                    ";
                            $results = $conn->query($sql);
                            if(!$results){
                                die("Query failed: " . mysqli_error($conn));
                            }
                            $results->data_seek($off_set);
                            $count = 1;
                            while ($row = mysqli_fetch_array($results)) {
                                $id = $row['faculty_sched_id'];
                                $faculty_name = $row['lastname'] . " " . $row['firstname'] . " " . $row['middlename'] . " " . $row['suffix'];
                                $course_code = $row['course_code'];
                               
                                
                                
                                
                                $room_name = $row['room_name'];
                                $course_code = $row['course_code'];
                                $section = "BS".$row['program_abbrv']." ".$row['section_name'];
                                $room = $row['room_name'];
                                // $time_start = date("h:i A", strtotime($row['start']));
                                // $time_end = date("h:i A", strtotime($row['end']));
                                
                                $sched_hours = $row['time_s']." - " .$row['time_e'];
                                $day = $row['day'];
                                
                                $description = $row['description'];

                                $count++;
                            

                         ?>
                         <tr>
                            <td class="loading_id"><?php echo $id;?></td>
                            <td><?php echo $faculty_name;?></td> 
                            <td><?php
                                if($description == "Class Schedule"){
                                    echo $course_code ." - " . $section ;
                                }else{
                                    echo $description;
                                }

                                ?></td>     
                                                          
                             
                            <td><?php echo $day;?></td>  
                            <td><?php echo $sched_hours;?></td>   
                            <td><?php echo $room;?></td>     
                            
                            
                            
                            
                            
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
                                    LEFT JOIN courses c ON c.course_id = fs.course_id
                                    LEFT JOIN rooms r ON r.room_id = fs.room_id
                                    LEFT JOIN semesters sm ON sm.semester_id = fs.semester_id
                                    LEFT JOIN academic_year ay ON ay.acad_year_id = fs.acad_year_id
                                    LEFT JOIN sections s ON s.section_id = fs.section_id
                                    LEFT JOIN programs p ON p.program_id = s.program_id
                                    LEFT JOIN tasks tt ON tt.task_id = fs.task_id
                                    WHERE fs.department_id = '$department_id'
                                    AND CONCAT(f.lastname,' ',f.firstname,' ',f.middlename,' ',f.suffix) = '$faculty_name'
                                    AND tt.term_id = '$term_id'
                            ");
                            $total_records = mysqli_fetch_array($result_count);
                            $total_records = $total_records['total_records'];
                            $total_no_of_page = ceil($total_records / $total_records_per_page);
                            $second_last = $total_no_of_page - 1;

                            $sql = "  
                                    SELECT 
                                        fs.faculty_sched_id,
                                        f.firstname,
                                        f.middlename,
                                        f.lastname,
                                        f.suffix,
                                        d.department_name,
                                        c.course_code,
                                        r.room_name,
                                        ay.acad_year,
                                        sm.sem_description,
                                        s.section_name,
                                        p.program_abbrv,
                                        t.time_s,
                                        
                                        t2.time_e,
                                        fs.day,
                                        fs.description
                                        
                                    FROM faculty_schedule fs
                                    LEFT JOIN faculties f ON f.faculty_id = fs.faculty_id
                                    LEFT JOIN departments d ON d.department_id = fs.department_id
                                    LEFT JOIN courses c ON c.course_id = fs.course_id
                                    LEFT JOIN rooms r ON r.room_id = fs.room_id
                                    LEFT JOIN semesters sm ON sm.semester_id = fs.semester_id
                                    LEFT JOIN academic_year ay ON ay.acad_year_id = fs.acad_year_id
                                    LEFT JOIN sections s ON s.section_id = fs.section_id
                                    LEFT JOIN programs p ON p.program_id = s.program_id
                                    LEFT JOIN `time` t ON t.time_id = fs.time_start_id
                                    LEFT JOIN `time` t2 ON t2.time_id = fs.time_end_id
                                    LEFT JOIN tasks tt ON tt.task_id = fs.task_id


                                    

                                    WHERE fs.department_id = '$department_id'
                                    AND CONCAT(f.lastname,' ',f.firstname,' ',f.middlename,' ',f.suffix) = '$faculty_name'
                                    AND tt.term_id = '$term_id'
                                    ";
                            $results = $conn->query($sql);
                            if(!$results){
                                die("Query failed: " . mysqli_error($conn));
                            }
                            $results->data_seek($off_set);
                            $count = 1;
                            while ($row = mysqli_fetch_array($results)) {
                                $id = $row['faculty_sched_id'];
                                $faculty_name = $row['lastname'] . " " . $row['firstname'] . " " . $row['middlename'] . " " . $row['suffix'];
                                $course_code = $row['course_code'];
                               
                                
                                
                                
                                $room_name = $row['room_name'];
                                $course_code = $row['course_code'];
                                $section = "BS".$row['program_abbrv']." ".$row['section_name'];
                                $room = $row['room_name'];
                                // $time_start = date("h:i A", strtotime($row['start']));
                                // $time_end = date("h:i A", strtotime($row['end']));
                                
                                $sched_hours = $row['time_s']." - " .$row['time_e'];
                                $day = $row['day'];
                                
                                $description = $row['description'];
                                $count++;
                            

                         ?>
                        <tr>
                            <td class="loading_id"><?php echo $id;?></td>
                            <td><?php echo $faculty_name;?></td> 
                            <td><?php
                                if(empty($description)){
                                    echo $course_code ." - " . $section ;
                                }else{
                                    echo $description;
                                }

                                ?></td>     
                                                          
                             
                            <td><?php echo $day;?></td>  
                            <td><?php echo $sched_hours;?></td>   
                            <td><?php echo $room;?></td>     
                            
                            
                            
                            
                            
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
                                    $('#faculty_name').val(data[1]);                                    
                                    $('#course').val(data[2]);
                                    $('#room').val(data[5]);
                                    // $('#section').val(data[3]);
                                    
                                    $('#day').val(data[3]);
                                    


                                    

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
                <form id="update_loading" method="POST" action="../php/update_faculty_schedule.php?faculty_name=<?php echo $_GET['faculty_name'] ?>">            
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Class Schedule</h5>
                </div>
                <div class="modal-body" id="editModal-body">
                   <label for="faculty_name" class="form-label"> Faculty Name</label>
                   <input type="text" name="loading_id" id="loading_id" hidden style="height: 0px; width:0px">
                   <input type="text" name="department_id" value="<?php echo $department_id?>" hidden style="height: 0px; width:0px">
                   <div class="form-group">                       
                        <input class="form-control" list="faculty_names" name="faculty_name" id="faculty_name" placeholder="Ente Faculty Name " disabled>
                                 
                   </div>
                   <div class="form-group">                           
                             <label for="course" class="form-label">Descriptions</label>
                                    <input class="form-control" list="sections" name="course_code" id="course"  disabled>
                                  
                        

                   </div>
                                   

                <!--     <div class="form-group">
                             <label for="section" class="form-label">Section</label>
                                    <input class="form-control"  name="section" id="section" disabled>
                                   
                        </div> -->

                        <div class="row">

                         
                            <div class="col-xs-12 col-sm-6">
                                <div  class="form-group">
                             <label for="browser" class="form-label">Class Hours Start</label>
                                    <input class="form-control"  name="class_hours_start" id="students" placeholder="Class Hours" type="time" >
                                  
                            </div>
                                
                            </div>
                               <div class="col-xs-12 col-sm-6">
                                <div  class="form-group">
                             <label for="browser" class="form-label">Class Hours End</label>
                                    <input class="form-control"  name="class_hours_end" id="students" placeholder="Class Hours" type="time" >
                                  
                            </div>
                                
                            </div>
                               <div class="col-xs-12 col-sm-6">
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

                                
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                   <div class=" form-group">
                  
                         <label for="semester" class="form-label">Room</label>
                                    <input class="form-control" list="rooms" name="room" id="room" placeholder="Enter room " required>
                                    <datalist id="rooms">
                                        <?php 
                                            $sql = "SELECT room_name FROM rooms";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                                $room = $row['room_name'];
                                            
                                        ?>
                                      <option value="<?php echo $room ?>">
                                      <?php }?>
                                    </datalist>
  
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
                <form id="delete_loading" action="../php/delete_faculty_schedule.php?faculty_name=<?php echo $_GET['faculty_name'] ?>" method="POST">
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
                <form method="POST" action="../php/insert_new_faculty_schedule.php?faculty_name=<?php echo $_GET['faculty_name'] ?>">
                    <div class="modal-header">                      
                        <h4 class="modal-title">Add Faculty Schedule</h4>
                        <input list="lnames" name="faculty" id="lname" value="<?php echo $_GET['faculty_name'] ?>" style="width: 0px;height: 0px; display: none;">
                        <input list="lnames" name="department_id" hidden value="<?php echo $department_id; ?>" style="width: 0px;height: 0px; ">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">     
                     <div class="form-group">
                             <label for="descriptions" class="form-label">description</label>
                                     <select id="descriptions" name="description" onchange="toggleCourseCodeInput()" class="form-control">
                                        <!-- <option value="Class Schedule">Class Schedule</option> -->
                                        <option value="Consultation">Consultation</option>
                                        <option value="Research">Research</option>
                                        <option value="Administrative">Administrative</option>
                                        <option value="RCOT">RCOT</option>
                                        
                                    </select>
                        </div>               
                      
                     <!-- <div class="form-group">
    <label for="course_code" class="form-group" id="course_code_label">Choose a course:</label>

    <select name="course_code" id="course_code" class="form-control" >
        <option value="">Choose a course</option>
        <?php 
        $faculty_name = $_GET['faculty_name']; // Get the faculty name from the URL parameter
        $sql = "SELECT cs.course_code AS 'Course Code'
                FROM faculty_loadings fl
                LEFT JOIN faculties fc ON fl.faculty_id = fc.faculty_id
                LEFT JOIN courses cs ON fl.course_id = cs.course_id
                LEFT JOIN sections sc ON fl.section_id = sc.section_id
                LEFT JOIN programs pr ON sc.program_id = pr.program_id
                LEFT JOIN departments dp ON dp.department_id = fl.dept_id
                LEFT JOIN semesters s ON s.semester_id = fl.sem_id
                LEFT JOIN academic_year ay ON ay.acad_year_id = fl.acad_year_id
                WHERE CONCAT(fc.lastname, ' ', fc.firstname, ' ', fc.middlename, ' ', fc.suffix) = '$faculty_name'
                    AND s.status = 'ACTIVE'
                    AND ay.status = 'ACTIVE'
                GROUP BY cs.course_code";
        $result = mysqli_query($conn, $sql);

        while($row = mysqli_fetch_array($result)){
            $course_code = $row['Course Code'];
        ?>
        
        <option value="<?php echo $course_code; ?>"><?php echo $course_code; ?></option>
        <?php }?>
    </select>                        
</div> -->
<!-- <div class="form-group">
    <label for="section" class="form-label" id="section_dis_label">Section</label>
    <select name="section" id="section_dis" class="form-control" >
        <option value="">Choose a section</option>
        <?php 
            $sql = "SELECT DISTINCT
                        p.`program_abbrv`,
                        s.`section_name`
                    FROM faculty_loadings fl
                    LEFT JOIN sections s ON s.`section_id` = fl.`section_id`
                    LEFT JOIN programs p ON p.`program_id` = s.`program_id`
                    LEFT JOIN faculties f ON f.`faculty_id` = fl.`faculty_id`
                    WHERE fl.dept_id = '$department_id'
                    AND CONCAT(f.lastname,' ',f.firstname,' ',f.middlename,' ',f.suffix) = '$faculty_name';
                    ";
            $result = mysqli_query($conn, $sql);

            while($row = mysqli_fetch_array($result)) {
                $section_name = "BS".$row['program_abbrv']. " " .$row['section_name'];
            ?>
            
            <option value="<?php echo $section_name; ?>"><?php echo $section_name; ?></option>
            <?php 
            }
        ?>
    </select>
</div> -->
<div class="form-group">
    <label for="browser" class="form-label" id="room_add_label">Room</label>
    <input class="form-control" list="rooms" name="room_name" id="room_add_input" placeholder="Choose a room" required>
        <datalist id="rooms">
            <?php 
                $sql = "SELECT *  FROM rooms";
                $result = mysqli_query($conn,$sql);

                while($row = mysqli_fetch_array($result)){
                    $room = $row['room_name'];
                                            
                ?>
                <option value="<?php echo $room ?>">
            <?php }?>
        </datalist>
</div>
<div class="form-group">
    <label for="days" class="form-group">Choose a day:</label>
        <select name="days" id="days" class="form-control" required> 
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                    <option value="Friday">Friday</option>
                                    <option value="Saturday">Saturday</option>
                                    <option value="Sunday">Sunday</option>
                                </select>                        
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
    <script src="js/sb-admin-2.min.js"></script>



<?php }
}?>
</body>
</html>