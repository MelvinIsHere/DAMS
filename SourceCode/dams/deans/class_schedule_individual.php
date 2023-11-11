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
        $section_name = $_GET['section_name'];



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
                    <center><b><h1 class="h3 mb-1 text-gray-800"><?php echo $_GET['section_name'];?> Class Schedule</h1></b></center>

                    <div class="card-body">
                            <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-xs-6">
                            <h2>Class Schedule</b></h2>
                        </div>
                        <div class="col-xs-6">
                            <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Class Schedule</span></a>
                            <a href="../php/automation_documents/generate_class_schedule.php?dept_id=<?php echo $department_id?>&dept_abbrv=<?php echo $department_abbrv?>&section=<?php echo $_GET['section_name']?>" class="btn btn-success"><i class="material-icons">&#xE147;</i> <span>Create Document</span></a> 

                            
                        </div>
                                                   
                    </div>
                </div>
                <table class="table table-striped table-hover" id="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name of Faculty</th>
                            
                            <th>Section</th>
                            <th>Room</th>
                            <th>Subject</th>
                            <th>No. of Students</th>
                            <th>Day</th>
                            <th>Class hours</th>
                        
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                                include "../config.php";
                                
                            if(isset($_GET['search']) && isset($_GET['section_name'])){
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
                                LEFT JOIN faculty_schedule fs ON fs.faculty_sched_id = cs.faculty_schedule_id
                                LEFT JOIN faculties f ON f.faculty_id = fs.faculty_id
                                LEFT JOIN courses c ON c.course_id = fs.course_id
                                LEFT JOIN sections s ON s.section_id = fs.section_id
                                LEFT JOIN rooms r ON r.room_id = fs.room_id
                                LEFT JOIN `time` t1 ON t1.time_id = fs.time_start_id
                                LEFT JOIN `time` t2 ON t2.time_id = fs.time_end_id
                                LEFT JOIN departments d ON d.department_id = fs.department_id
                                LEFT JOIN programs p ON p.`program_id` = s.`program_id`
                                LEFT JOIN semesters sm ON sm.`semester_id` = fs.`semester_id`
                                LEFT JOIN academic_year ay ON ay.`acad_year_id` = fs.`acad_year_id`

                                    

                                    WHERE fs.department_id = '$department_id' AND ay.status = 'ACTIVE' AND sm.status = 'ACTIVE'
                                AND CONCAT(r.room_name,fs.day) LIKE '%$search%'
                            
                            
                            ");
                            $total_records = mysqli_fetch_array($result_count);
                            $total_records = $total_records['total_records'];
                            $total_no_of_page = ceil($total_records / $total_records_per_page);
                            $second_last = $total_no_of_page - 1;

                            $sql = " SELECT 
                                cs.class_sched_id,
                                fs.faculty_id,
                                fs.day,
                                f.firstname,
                                f.lastname,
                                f.middlename,
                                f.suffix,
                                c.course_code,
                                p.`program_abbrv`,
                                s.section_name,
                                s.`no_of_students`,
                                r.room_name,
                                t1.time_s,
                                t2.time_e,
                                d.department_name,
                                sm.`sem_description`,
                                ay.`acad_year`
                                FROM class_schedule cs
                                LEFT JOIN faculty_schedule fs ON fs.faculty_sched_id = cs.faculty_schedule_id
                                LEFT JOIN faculties f ON f.faculty_id = fs.faculty_id
                                LEFT JOIN courses c ON c.course_id = fs.course_id
                                LEFT JOIN sections s ON s.section_id = fs.section_id
                                LEFT JOIN rooms r ON r.room_id = fs.room_id
                                LEFT JOIN `time` t1 ON t1.time_id = fs.time_start_id
                                LEFT JOIN `time` t2 ON t2.time_id = fs.time_end_id
                                LEFT JOIN departments d ON d.department_id = fs.department_id
                                LEFT JOIN programs p ON p.`program_id` = s.`program_id`
                                LEFT JOIN semesters sm ON sm.`semester_id` = fs.`semester_id`
                                LEFT JOIN academic_year ay ON ay.`acad_year_id` = fs.`acad_year_id`

                                    

                                    WHERE fs.department_id = '$department_id' AND ay.status = 'ACTIVE' AND sm.status = 'ACTIVE'
                                    AND CONCAT('BS',p.program_abbrv,' ',s.section_name) = '{$_GET['section_name']}'
                                    AND CONCAT(r.room_name,fs.day) LIKE '%$search%'
                                    ";
                            $results = $conn->query($sql);
                            if(!$results){
                                die("Query failed: " . mysqli_error($conn));
                            }
                            $results->data_seek($off_set);
                            $count = 1;
                            while ($row = mysqli_fetch_array($results)) {
                                $id = $row['class_sched_id'];
                                $faculty_name = $row['lastname'] . " " . $row['firstname'] . " " . $row['middlename'] . " " . $row['suffix'];
                                $course_code = $row['course_code'];
                                $section = "BS".$row['program_abbrv'] . " " . $row['section_name'];
                                $studs = $row['no_of_students'];
                                
                                $day = $row['day'];
                                $room_name = $row['room_name'];
                                $class_hours = $row['time_s']. " - " .$row['time_e'];

                                $count++;
                            

                         ?>
                          <tr>
                            <td class="loading_id"><?php echo $id;?></td>
                            <td><?php echo $faculty_name;?></td>


                            
                            <td><?php echo $section;?></td>
                            <td><?php echo $room_name; ?></td>
                            <td><?php echo $course_code;?></td>
                            <td><?php echo $studs; ?></td>
                            <td><?php echo $day;?></td>
                            <td><?php echo $class_hours; ?></td>
                            
                            
                            
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


                                        

                                      FROM class_schedule cs
                                 LEFT JOIN faculty_loadings fl ON fl.`fac_load_id` = cs.`faculty_schedule_id`
                              LEFT JOIN faculties fc ON fl.`faculty_id`=fc.`faculty_id`
                                    LEFT JOIN courses c ON fl.`course_id`=c.`course_id`
                                    LEFT JOIN sections sc ON fl.`section_id`=sc.`section_id`
                                    LEFT JOIN programs pr ON sc.`program_id`=pr.`program_id`
                                    LEFT JOIN departments dp ON dp.`department_id`=fl.`dept_id`
                                    LEFT JOIN semesters s ON s.semester_id = fl.sem_id
                                    LEFT JOIN academic_year ay ON ay.acad_year_id = fl.acad_year_id
                                    LEFT JOIN rooms r ON r.`room_id` = cs.room_id
                                    LEFT JOIN `time` t1 ON t1.`time_id` = cs.time_start_id
                                    LEFT JOIN `time` t2 ON t2.`time_id` = cs.time_end_id
                                    WHERE pr.`department_id` = '$department_id'
                                    AND s.status = 'ACTIVE'
                                    AND ay.status = 'ACTIVE'
                                    
                                    
                            
                            ");
                            $total_records = mysqli_fetch_array($result_count);
                            $total_records = $total_records['total_records'];
                            $total_no_of_page = ceil($total_records / $total_records_per_page);
                            $second_last = $total_no_of_page - 1;

                            $sql = "  
                                SELECT 
                                cs.class_sched_id,
                                fl.faculty_id,
                                cs.day,
                                fc.firstname,
                                fc.lastname,
                                fc.middlename,
                                fc.suffix,
                                c.course_code,
                                pr.`program_abbrv`,
                                sc.section_name,
                                sc.`no_of_students`,
                                r.room_name,
                                t1.time_s,
                                t2.time_e,
                                dp.department_name,
                                s.`sem_description`,
                                ay.`acad_year`,
                                fl.needed
                                FROM class_schedule cs
                                LEFT JOIN faculty_loadings fl ON fl.`fac_load_id` = cs.`faculty_schedule_id`
                              LEFT JOIN faculties fc ON fl.`faculty_id`=fc.`faculty_id`
                                    LEFT JOIN courses c ON fl.`course_id`=c.`course_id`
                                    LEFT JOIN sections sc ON fl.`section_id`=sc.`section_id`
                                    LEFT JOIN programs pr ON sc.`program_id`=pr.`program_id`
                                    LEFT JOIN departments dp ON dp.`department_id`=fl.`dept_id`
                                    LEFT JOIN semesters s ON s.semester_id = fl.sem_id
                                    LEFT JOIN academic_year ay ON ay.acad_year_id = fl.acad_year_id
                                    LEFT JOIN rooms r ON r.`room_id` = cs.room_id
                                    LEFT JOIN `time` t1 ON t1.`time_id` = cs.time_start_id
                                    LEFT JOIN `time` t2 ON t2.`time_id` = cs.time_end_id
                                    WHERE pr.`department_id` = '$department_id'
                                    AND s.status = 'ACTIVE'
                                    AND ay.status = 'ACTIVE'
                                      
                                    AND CONCAT('BS',pr.program_abbrv,' ',sc.section_name) ='$section_name'
                                    
                                    
                                    

                                    
                                    

                                    

                                    ";
                            $results = $conn->query($sql);
                            if(!$results){
                                die("Query failed: " . mysqli_error($conn));
                            }
                            $results->data_seek($off_set);
                            $count = 1;
                            while ($row = mysqli_fetch_array($results)) {
                                $id = $row['class_sched_id'];
                                $faculty_name = $row['lastname'] . " " . $row['firstname'] . " " . $row['middlename'] . " " . $row['suffix'];
                                $course_code = $row['course_code'];
                                $section = "BS".$row['program_abbrv'] . " " . $row['section_name'];
                                $studs = $row['no_of_students'];
                                
                                $day = $row['day'];
                                $room_name = $row['room_name'];
                                $class_hours = $row['time_s']. " - " .$row['time_e'];
                                $needed = $row['needed'];

                                $count++;
                            

                         ?>
                        <tr>
                            <td class="loading_id"><?php echo $id;?></td>
                            <td><?php

                               if(!empty($needed)){
                                echo $needed;
                               }else{
                                echo $faculty_name;
                               }
                            ?></td>


                            
                            <td><?php echo $section;?></td>
                            <td><?php echo $room_name; ?></td>
                            <td><?php echo $course_code;?></td>
                            <td><?php echo $studs; ?></td>
                            <td><?php echo $day;?></td>
                            <td><?php echo $class_hours; ?></td>
                            
                            
                            
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
        <a <?php if ($page_no > 1) { echo "href='?page_no=$previous_page&section_name=$section_name'"; } ?>>Previous</a>
    </li>

    <?php
    if ($total_no_of_page <= 10) {
        for ($counter = 1; $counter <= $total_no_of_page; $counter++) {
            if ($counter == $page_no) {
                echo "<li class='active page-item'><a>$counter</a></li>";
            } else {
                echo "<li><a href='?page_no=$counter&section_name=$section_name'>$counter</a></li>";

            }
        }
    } elseif ($total_no_of_page > 10) {
        if ($page_no <= 4) {
            for ($counter = 1; $counter <= 8; $counter++) {
                if ($counter == $page_no) {
                    echo "<li class='active page-item'><a>$counter</a></li>";
                } else {
                    echo "<li><a href='?page_no=$counter&section_name=$section_name'>$counter</a></li>";
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
                    echo "<li><a href='?page_no=$counter&section_name=$section_name'>$counter</a></li>";
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
        <a <?php if ($page_no < $total_no_of_page) { echo "href='?page_no=$next_page&section_name=$section_name'"; } ?>>Next</a>
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
                                jQuery(document).ready(function() {
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
                                    $('#section').val(data[2]);
                                    $('#room').val(data[3]);
                                    $('#course_code').val(data[4]);
                                    $('#students').val(data[5]);
                                    $('#day').val(data[6]);
                                   
                                    




                                    

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
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="update_loading" method="POST" action="../php/update_class_schedule.php?section_name=<?php echo $section_name; ?>">            
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Class Schedule</h5>
                </div>
                <div class="modal-body" id="editModal-body">
                   <label for="faculty_name" class="form-label"> Faculty Name</label>
                   <input type="text" name="loading_id" id="loading_id" hidden style="height: 0px; width:0px">
                   <div class="form-group">                       
                        <input class="form-control" list="faculty_names" name="faculty_name" id="faculty_name" placeholder="Ente Faculty Name " disabled>
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
                   </div>
                   <div class="form-group">                           
                             <label for="section" class="form-label">Section</label>
                                    <input class="form-control" list="sections" name="section" id="section" placeholder="Enter Section " disabled>
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
                        

                   </div>
                                   

                    <div class="form-group">
                             <label for="browser" class="form-label">Subject</label>
                                    <input class="form-control" list="browsers" name="course_code" id="course_code" placeholder="Choose a Course Code">
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

                        <div class="row">

                         
                            <div class="col-xs-12 col-sm-6">
                                <div  class="form-group">
                             <label for="browser" class="form-label">Class Hours Start</label>
                                    <input class="form-control"  name="class_hours_start" id="time_start" placeholder="Class Hours" type="time">
                                  
                            </div>
                                
                            </div>
                               <div class="col-xs-12 col-sm-6">
                                <div  class="form-group">
                             <label for="browser" class="form-label">Class Hours End</label>
                                    <input class="form-control"  name="class_hours_end" id="students" placeholder="Class Hours" type="time">
                                  
                            </div>
                                
                            </div>
                               <div class="col-xs-12 col-sm-6">
                                 <div class="form-group">
                            <label for="days" class="form-label">Select day:</label>
                                    <input class="form-control" list="days" name="day" id="day" placeholder="Select Day">
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
                                    <input class="form-control" list="rooms" name="room" id="room" placeholder="Enter room ">
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
                <form id="delete_loading" action="../php/delete_class_schedule.php?section_name=<?php echo $_GET['section_name']?>" method="POST">
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
                <form method="POST" action="../php/insert_new_class_schedule.php">
                    <div class="modal-header">                      
                        <h4 class="modal-title">Load Schedule</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">                    
                        
                  <div class="form-group">
                <label for="course_code" class="form-label">Course Code</label>
                <input type="text" name="section_name" value="<?php echo $_GET['section_name']?>" style="width: 0px; height:0px" hidden>
                <input type="text" name="department_id" value="<?php echo $department_id;?>" style="width: 0px; height:0px" hidden>
                <select class="form-control" name="course_code" id="course_code">
        <option value="" selected disabled>Choose a Course Code</option>
        <?php 
            $sectionName = mysqli_real_escape_string($conn, $_GET['section_name']); // Sanitize the input

            $sql = "
           
                                    
                                      SELECT DISTINCT
                                  
                                    cs.course_code 'Course Code'
                                    
                                    FROM
                                    faculty_loadings fl
                                    LEFT JOIN faculties fc ON fl.`faculty_id`=fc.`faculty_id`
                                    LEFT JOIN courses cs ON fl.`course_id`=cs.`course_id`
                                    LEFT JOIN sections sc ON fl.`section_id`=sc.`section_id`
                                    LEFT JOIN programs pr ON sc.`program_id`=pr.`program_id`
                                    LEFT JOIN departments dp ON dp.`department_id`=fl.`dept_id`
                                    LEFT JOIN semesters s ON s.semester_id = fl.sem_id
                                    LEFT JOIN academic_year ay ON ay.acad_year_id = fl.acad_year_id
                                    WHERE pr.`department_id` = '$department_id'  
                                    AND s.status = 'ACTIVE'
                                    AND ay.status = 'ACTIVE'
                                     
                                    AND CONCAT('BS',pr.program_abbrv,' ',sc.section_name) = '$sectionName'
                                    GROUP BY fl.`fac_load_id`
            ";
            $result = mysqli_query($conn, $sql);

            while($row = mysqli_fetch_array($result)){
                $code = $row['Course Code'];
        ?>
        <option value="<?php echo $code ?>"><?php echo $code ?></option>
        <?php }?>
    </select>
</div>

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
  <script src="js/sb-admin-2.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>



<?php }
}?>
</body>
</html>