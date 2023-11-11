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
                    <h1 class="h3 mb-1 text-gray-800">Create Documents | Faculty Loading</h1>

                    <div class="card-body">
                            <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-xs-6">
                            <h2>Faculty Loading</b></h2>
                        </div>
                        <div class="col-xs-6">
                            <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Faculty Loading</span></a>
                            <a href="../php/automation_documents/generate_loading.php?dept_id=<?php echo $department_id?>&dept_abbrv=<?php echo $department_abbrv?>" class="btn btn-success"><i class="material-icons">&#xE147;</i> <span>Create Document</span></a> 

                            
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
                            <th>No. of Students</th>
                            <th>Lec. hrs/wk</th>
                            <th>Lab. hrs/wk</th>
                            <th>Total hrs/wk</th>
                            <th>Course Description</th>
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
                            COUNT(*) AS total_records,fc.firstname,fc.middlename,fc.lastname
                            FROM
                            faculty_loadings fl
                            LEFT JOIN faculties fc ON fl.`faculty_id`=fc.`faculty_id`
                            LEFT JOIN courses cs ON fl.`course_id`=cs.`course_id`
                            LEFT JOIN sections sc ON fl.`section_id`=sc.`section_id`
                            LEFT JOIN programs pr ON sc.`program_id`=pr.`program_id`
                            LEFT JOIN departments dp ON dp.`department_id`=fl.`dept_id`
                            LEFT JOIN semesters s ON s.semester_id = fl.sem_id
                            LEFT JOIN academic_year ay ON ay.acad_year_id = fl.acad_year_id
                            WHERE fl.`dept_id` = '$department_id'  AND CONCAT(fc.firstname,fc.middlename,fc.lastname) LIKE '%$search%'
                            AND s.status = 'ACTIVE'
                            AND ay.status = 'ACTIVE'
                            
                            ");
                            $total_records = mysqli_fetch_array($result_count);
                            $total_records = $total_records['total_records'];
                            $total_no_of_page = ceil($total_records / $total_records_per_page);
                            $second_last = $total_no_of_page - 1;

                            $sql = "SELECT
                                    getFullName_surnameFirst(fc.firstname,fc.middlename,fc.lastname,fc.`suffix`) 'Name of Faculty',
                                    cs.course_code 'Course Code',
                                    getProg_sec(pr.`program_abbrv`,sc.`section_name`) 'Section',
                                    sc.no_of_students 'No. of Students',
                                    cs.`units` 'Total Units',
                                    cs.`lec_hrs_wk` 'Lec. hrs/wk',
                                    cs.`lab_hrs_wk` 'Lab. hrs/wk',
                                    SUM(cs.`lec_hrs_wk`+cs.`lab_hrs_wk`) 'Total hrs/wk',
                                    cs.`course_description` 'Course Description',
                                    fl.fac_load_id AS 'Loading Id'
                                    FROM
                                    faculty_loadings fl
                                    LEFT JOIN faculties fc ON fl.`faculty_id`=fc.`faculty_id`
                                    LEFT JOIN courses cs ON fl.`course_id`=cs.`course_id`
                                    LEFT JOIN sections sc ON fl.`section_id`=sc.`section_id`
                                    LEFT JOIN programs pr ON sc.`program_id`=pr.`program_id`
                                    LEFT JOIN departments dp ON dp.`department_id`=fl.`dept_id`
                                    LEFT JOIN semesters s ON s.semester_id = fl.sem_id
                                    LEFT JOIN academic_year ay ON ay.acad_year_id = fl.acad_year_id
                                    WHERE fc.`department_id` = '$department_id'   AND CONCAT(fc.firstname,fc.middlename,fc.lastname) LIKE '%$search%'
                                    AND s.status = 'ACTIVE'
                                    AND ay.status = 'ACTIVE'
                                    GROUP BY fl.`fac_load_id`
                                    ";
                            $results = $conn->query($sql);
                            if(!$results){
                                die("Query failed: " . mysqli_error($conn));
                            }
                            $results->data_seek($off_set);
                            $count = 1;
                            while ($row = mysqli_fetch_array($results)) {
                                $id = $row['Loading Id'];
                                $name = $row['Name of Faculty'];
                                $course_code = $row['Course Code'];
                                $section = $row['Section'];
                                $studs = $row['No. of Students'];
                                $lec = $row['Lec. hrs/wk'];
                                $lab = $row['Lab. hrs/wk'];
                                $total = $row['Total hrs/wk'];
                                $course_description = $row['Course Description'];

                                $count++;
                            

                         ?>
                        <tr>
                            <td class="loading_id"><?php echo $id;?></td>
                            <td><?php echo $name;?></td>
                            <td><?php echo $course_code;?></td>
                            <td><?php echo $section;?></td>
                            <td><?php echo $studs;?></td>
                            <td><?php echo $lec; ?></td>
                            <td><?php echo $lab; ?></td>
                            <td><?php echo $total; ?></td>
                            <td><?php echo $course_description; ?></td>
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
                                     
                                    


                            
                            ");
                            $total_records = mysqli_fetch_array($result_count);
                            $total_records = $total_records['total_records'];
                            $total_no_of_page = ceil($total_records / $total_records_per_page);
                            $second_last = $total_no_of_page - 1;

                            $sql = "
                       SELECT
                                    getFullName_surnameFirst(fc.firstname,fc.middlename,fc.lastname,fc.`suffix`) 'Name of Faculty',
                                    cs.course_code 'Course Code',
                                    getProg_sec(pr.`program_abbrv`,sc.`section_name`) 'Section',
                                    sc.no_of_students 'No. of Students',
                                    cs.`units` 'Total Units',
                                    cs.`lec_hrs_wk` 'Lec. hrs/wk',
                                    cs.`lab_hrs_wk` 'Lab. hrs/wk',
                                    SUM(cs.`lec_hrs_wk`+cs.`lab_hrs_wk`) 'Total hrs/wk',
                                    cs.`course_description` 'Course Description',
                                    fl.fac_load_id AS 'Loading Id',
                                    fl.needed
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
                                    OR fc.`faculty_id` = NULL  
                                    GROUP BY fl.`fac_load_id`
                                    ";
                            $results = $conn->query($sql);
                            if(!$results){
                                die("Query failed: " . mysqli_error($conn));
                            }
                            $results->data_seek($off_set);
                            $count = 1;
                            while ($row = mysqli_fetch_array($results)) {
                                $id = $row['Loading Id'];
                                $name = $row['Name of Faculty'];
                                $course_code = $row['Course Code'];
                                $section = $row['Section'];
                                $studs = $row['No. of Students'];
                                $lec = $row['Lec. hrs/wk'];
                                $lab = $row['Lab. hrs/wk'];
                                $total = $row['Total hrs/wk'];
                                $course_description = $row['Course Description'];
                                $needed = $row['needed'];
                                $count++;
                            

                         ?>
                        <tr>
                            <td class="loading_id"><?php echo $id;?></td>
                            <td><?php
                            if(empty($name)){
                                echo "Needed Lecturer";
                            }else{
                                echo $name;
                            }

                             ?></td>
                            <td><?php echo $course_code;?></td>
                            <td><?php echo $section;?></td>
                            <td><?php echo $studs;?></td>
                            <td><?php echo $lec; ?></td>
                            <td><?php echo $lab; ?></td>
                            <td><?php echo $total; ?></td>
                            <td><?php echo $course_description; ?></td>
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

                                     var loading_id  = $(this).closest('tr').find('.loading_id').text();
                                    console.log(loading_id)
                                    $('#loading_id').val(loading_id);;
                                    $('#faculty_name').val(data[1]);
                                    $('#course_code').val(data[2]);
                                    $('#section').val(data[3]);
                                    

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
                <form id="update_loading" method="POST" action="../php/update_faculty_loading.php">
                    




                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Form</h5>
                </div>
                <div class="modal-body" id="editModal-body">

                   <label for="faculty_name" class="form-label"> Faculty Name</label>
                   <input type="text" name="loading_id" id="loading_id" hidden style="height: 0px; width:0px">
                                    <input class="form-control" list="faculty_names" name="faculty_name" id="faculty_name" placeholder="Ente Faculty Name ">
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

<!-- ########################################################################################################################## -->


                    <label for="course_code" class="form-label">Course Code</label>
                                    <input class="form-control" list="course_codes" name="course_code" id="course_code" placeholder="Ente Course Code ">
                                    <datalist id="course_codes">
                                        <?php 
                                            $sql = "SELECT DISTINCT course_code FROM courses WHERE department_id = '$department_id'";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                                $course_code = $row['course_code'];
                                            
                                        ?>
                                      <option value="<?php echo $course_code ?>">
                                      <?php }?>
                                    </datalist>


<!-- ########################################################################################################################## -->                                    
                    
                        
                             <label for="section" class="form-label">Section</label>
                                    <input class="form-control" list="sections" name="section" id="section" placeholder="Enter Section ">
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
                        



<!-- ########################################################################################################################## -->                    <!--     
                        <div class="col-xs-12 col-sm-8">
                         <label for="semester" class="form-label">Semester</label>
                                    <input class="form-control" list="semesters" name="semester" id="semester" placeholder="Enter Semester ">
                                    <datalist id="semesters">
                                        <?php 
                                            $sql = "SELECT DISTINCT sem_description FROM semesters";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                                $sem_description = $row['sem_description'];
                                            
                                        ?>
                                      <option value="<?php echo $sem_description ?>">
                                      <?php }?>
                                    </datalist>
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
                <form id="delete_loading" action="../php/delete_faculty_loading.php" method="POST">
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
                <form method="POST" action="../php/insert_faculty_loading.php">
                    <div class="modal-header">                      
                        <h4 class="modal-title">Add Faculty Loading</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">                    
                        <div class="form-group">
                            <label for="lname" class="form-label">Faculty</label>
                                    <input class="form-control" list="lnames" name="faculty" id="lname" placeholder="Enter faculty name">
                                    <datalist id="lnames">
                                        <?php 
                                            $sql = "SELECT DISTINCT faculty_id,firstname,lastname,middlename FROM faculties WHERE is_gen_ed = 'Yes' OR department_id = '$department_id'";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                                $id = $row['faculty_id'];
                                                $name =   $row['lastname'] .' ' . $row['firstname'] .' '.$row['middlename'] ;
                                            
                                        ?>
                                      <option value="<?php echo $name?>">
                                      <?php }?>
                                    </datalist>
                        </div>
                       <!--   <div class="form-group">
                             <label for="descriptions" class="form-label">description</label>
                                     <select id="descriptions" name="description">
                                        <option value="Class Schedule">Class Schedule</option>
                                        <option value="Consultation">Consultation</option>
                                        <option value="Research">Research</option>
                                        <option value="Administrative">Administrative</option>
                                        
                                    </select>
                        </div> -->
                        <div class="form-group">
                             <label for="browser" class="form-label">Course Code</label>
                                    <input class="form-control" list="browsers" name="course_code" id="browser" placeholder="Choose a Course Code">
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
                        <div class="form-group">
                            <div class="row">
                                  <div class="col">
                                   <label for="ini" class="form-label">Section</label>
                                    <input class="form-control" list="inis" name="section" id="ini" placeholder="Enter Section ">
                                                                        
                                    <datalist id="inis">
                                        <?php 
                                        
                                        $sql = "SELECT  s.section_name, d.department_name ,p.program_abbrv FROM sections s
                                                LEFT JOIN programs p ON p.program_id = s.program_id
                                                LEFT JOIN departments d ON d.department_id = p.department_id
                                                WHERE d.department_id = '$department_id'";
                                        $result = mysqli_query($conn, $sql);

                                        while($row = mysqli_fetch_array($result)) {
                                            $section_name = 'BS'.$row['program_abbrv'] . ' ' .$row['section_name'];
                                        ?>
                                        <option value="<?php echo $section_name; ?>">
                                        <?php 
                                        }
                                        ?>


                                    </datalist>
                                </div>
                                <div class="col">
                                   <label for="sem" class="form-label">Semester</label>
                                    <input class="form-control" list="sems" name="semester" id="sem" placeholder="Semester">
                                    <datalist id="sems">
                                        <?php 
                                            $sql = "SELECT DISTINCT sem_description FROM semesters";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                                $md = $row['sem_description'];
                                            
                                        ?>
                                      <option value="<?php echo $md ?>">
                                      <?php }?>
                                    </datalist>
                                </div>
                                <div class="col">
                                   <label for="acads" class="form-label">Semester</label>
                                    <input class="form-control" list="academ" name="acad" id="acads" placeholder="Academic year">
                                    <datalist id="academ">
                                        <?php 
                                            $sql = "SELECT DISTINCT acad_year FROM academic_year";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                                $acad = $row['acad_year'];
                                            
                                        ?>
                                      <option value="<?php echo $acad ?>">
                                      <?php }?>
                                    </datalist>
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
    function toggleCourseCodeInput() {
        var descriptionsSelect = document.getElementById('descriptions');
        var courseCodeInput = document.getElementById('browser');
        var sectionInput = document.getElementById('ini');

        if (descriptionsSelect.value === 'Class Schedule') {
            courseCodeInput.disabled = false;
            sectionInput.disabled = false;
        } else {
            courseCodeInput.disabled = true;
            sectionInput.disabled = true;
        }
    }
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