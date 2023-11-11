


                            <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-xs-6">
                          
                        </div>
                        <div class="col-xs-6">
                          
                            <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New administrative hours</span></a>

                            <!-- <a href="../php/automation_documents/generate_class_schedule.php?dept_id=<?php echo $department_id?>&dept_abbrv=<?php echo $department_abbrv?>" class="btn btn-success"><i class="material-icons">&#xE147;</i> <span>Create Document</span></a>  -->

                            
                        </div>
                                                   
                    </div>
                </div>
                <table class="table table-striped table-hover" id="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name of Faculty</th>
                            <th>Course Code</th>
                            <th>Section</th>
                            <th>Day</th>
                            <th>Schedule hours</th>
                            <th>Room</th>
                            
                            
                        
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
                                    AND CONCAT(f.lastname,' ',f.firstname,' ',f.middlename,' ',f.suffix) = '$faculty_name'
                            
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
                                        fs.day
                                        
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
                                    AND CONCAT(f.lastname,' ',f.firstname,' ',f.middlename,' ',f.suffix) = '$faculty_name'
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
                                

                                $count++;
                            

                         ?>
                        <tr>
                            <td class="loading_id"><?php echo $id;?></td>
                            <td><?php echo $faculty_name;?></td> 
                            <td><?php echo $course_code;?></td>     
                                                          
                            <td><?php echo $section;?></td>  
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
                    ?>
                        
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
        
