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
     
            <!-- Faculty Loading -->
                <div class="container-fluid tabcontent" id="viewFacultyLoading">
    <center><h1 class="h3 mb-1 text-gray-800"><strong>OPCR Monitoring</strong></h1></center>
    <div class="card-body">
        <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                       <!--  <div class="col-xs-6">
                            <h2>Faculty Loading</b></h2>
                        </div> -->
                        <div class="col-xs-6" style="height: 30px;">
                            <!-- <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Faculty Loading</span></a>                                                                               -->
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-hover" id="table">
                    <thead>
                        <tr>
                                            <th>id</th>
                                            <th>Email</th>
                                            <th>Position</th>
                                            <th>Department</th>
                                            <th>Due Date</th>
                                            <th>Document Status</th>
                                            <th>Action</th>
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
                                                   
                                            

                                                    FROM departments
                                                    WHERE CONCAT(department_name,department_abbrv
                                                    ) LIKE '%$search%'
                                                    GROUP BY department_name");
                            $total_records = mysqli_fetch_array($result_count);
                            $total_records = $total_records['total_records'];
                            $total_no_of_page = ceil($total_records / $total_records_per_page);
                            $second_last = $total_no_of_page - 1;

                            $sql = "SELECT DISTINCT
                                                    dp.`department_name`,
                                                    dp.`department_abbrv`,
                                                    u.user_id,
                                                    u.type,
                                                    u.email,
                                                    ts.is_completed,
                                                    ts.status_id AS 'status_id',
                                                    t.due_date,
                                                    
                                                    f.file_id, 
                                                    dt.template_title AS 'task_name'

                                                     FROM departments dp
                                                    LEFT JOIN users u ON u.department_id = dp.department_id 
                                                    LEFT JOIN task_status_deans ts ON ts.`office_id` = dp.`department_id`
                                                    LEFT JOIN tasks t ON t.`task_id` = ts.`task_id`
                                                    LEFT JOIN file_table f ON f.file_owner_id = u.user_id
                                                    LEFT JOIN document_templates dt ON dt.doc_template_id = t.document_id
                                                    WHERE task_name = 'OPCR' AND u.type != 'Staff'
                                                    AND CONCAT(dp.department_name,dp.department_abbrv,u.type,u.email) LIKE '%$search%' AND u.type != 'Staff'
                                                    GROUP BY u.user_id";
                            $results = $conn->query($sql);
                            if(!$results){
                                die("Query failed: " . mysqli_error($conn));
                            }
                            $results->data_seek($off_set);
                            $count = 1;
                            while ($row = mysqli_fetch_array($results)) {
                               $user_id = $row['user_id'];
                                                   $email = $row['email'];
                                                   $position = $row['type'];
                                                   $abbrv = $row['department_abbrv'];
                                                   $due_date = $row['due_date'];
                                                   $status = $row['is_completed'];
                                                   $file_id = $row['file_id'];
                                                   $status_id = $row['status_id'];

                                $count++;
                            

                         ?>
                        <tr>
                            <td><?php echo $user_id;?></td>
                                            <td><?php echo $email;?></td>
                                            <td><?php echo $position;?></td>
                                            <td><?php echo $abbrv;?></td>
                                            <td><?php echo $due_date;?></td>
                                            <td class="<?php if ($status == 1) {
                                                echo 'ns';
                                            }
                                            else{
                                                echo "submitted";
                                            }?>">
                                                <?php
                                                if ($status == 1 || $status == null) {
                                                    echo "Not Submitted";
                                                }
                                                else{
                                                    echo "Submitted";
                                                }

                                             ?></td>
                            
                            <td style="display: inline-flex; ">
                                <a href="../view_files.php?id=<?php echo $file_id?>" class="btn btn-success" style="margin-right: 10px">View</a>
                                <a href="../return.php?id=<?php echo $status_id ?>&task_name=Accomplishment%20Report&user_id=<?php echo $user_id; ?>" class="btn-warning btn">Return</a>
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
                            $sem_id = $_SESSION['semester_id'];
                            $acad_id = $_SESSION['acad_id'];
                            $result_count = mysqli_query($conn, "SELECT 
                                    tsd.status_id,
                                    tsd.`is_completed`,
                                    tsd.`task_id`,
                                    t.`task_name`,
                                    t.due_date,
                                    u.`email`,
                                    u.user_id,
                                    u.type,
                                    dp.department_name,
                                    dp.department_abbrv,
                                    ft.`file_id`
                                FROM task_status_deans tsd 
                                LEFT JOIN file_table ft ON ft.`task_status_id` = tsd.`status_id`
                                LEFT JOIN users u ON u.`user_id` = tsd.`user_id`
                                LEFT JOIN tasks t ON t.`task_id` = tsd.`task_id`
                                LEFT JOIN departments dp ON dp.`department_id` = tsd.`office_id`
                                WHERE t.task_name = 'OPCR'
                                GROUP BY u.user_id");
                            // $total_records = mysqli_fetch_array($result_count);
                            $total_records = mysqli_num_rows($result_count);
                            $total_no_of_page = ceil($total_records / $total_records_per_page);
                            $second_last = $total_no_of_page - 1;

                            $sql = "SELECT 
                                    tsd.status_id,
                                    tsd.`is_completed`,
                                    tsd.`task_id`,
                                    t.`task_name`,
                                    t.due_date,
                                    u.`email`,
                                    u.user_id,
                                    u.type,
                                    dp.department_name,
                                    dp.department_abbrv,
                                    ft.`file_id`
                                FROM task_status_deans tsd 
                                LEFT JOIN file_table ft ON ft.`task_status_id` = tsd.`status_id`
                                LEFT JOIN users u ON u.`user_id` = tsd.`user_id`
                                LEFT JOIN tasks t ON t.`task_id` = tsd.`task_id`
                                LEFT JOIN departments dp ON dp.`department_id` = tsd.`office_id`
                                WHERE t.task_name = 'OPCR'
                                GROUP BY u.user_id";
                            $results = $conn->query($sql);
                            if(!$results){
                                die("Query failed: " . mysqli_error($conn));
                            }
                            $results->data_seek($off_set);
                            $count = 1;
                            while ($row = mysqli_fetch_array($results)) {
                                $file_id = $row['file_id'];
                               $user_id = $row['user_id'];
                                                   $email = $row['email'];
                                                   $position = $row['type'];
                                                   $abbrv = $row['department_abbrv'];
                                                   $due_date = $row['due_date'];
                                                   $status = $row['is_completed'];
                                                   $file_id = $row['file_id'];
                                                   $status_id = $row['status_id'];

                                $count++;
                            

                         ?>
                        <tr>
                            <td><?php echo $user_id;?></td>
                                            <td><?php echo $email;?></td>
                                            <td><?php echo $position;?></td>
                                            <td><?php echo $abbrv;?></td>
                                            <td><?php echo $due_date;?></td>
                                            <td class="<?php if ($status == 1) {
                                                echo 'ns';
                                            }
                                            else{
                                                echo "submitted";
                                            }?>">
                                                <?php
                                                if ($status == 1 || $status == null) {
                                                    echo "Not Submitted";
                                                }
                                                else{
                                                    echo "Submitted";
                                                }

                                             ?></td>
                            
                            <td style="display: inline-flex; ">
                                <a href="../view_files.php?file_id=<?php echo $file_id?>" class="btn btn-success" style="margin-right: 10px">View</a>
                                <a href="../return.php?id=<?php echo $status_id ?>&task_name=Accomplishment%20Report&user_id=<?php echo $user_id; ?>&file_id=<?php $file_id;?>" class="btn-warning btn">Return</a>
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

                                     var program_id  = $(this).closest('tr').find('.program_id').text();
                                    console.log(program_id)
                                    $('#program_id').val(program_id);;
                                    $('#program_name').val(data[1]);
                                    $('#program_abbrv').val(data[2]);
                                    
                                    

                                });
                                });



                                $(document).ready(function() {
                                $('.delete').on('click',function(e){
                                    e.preventDefault();

                                    var program_id  = $(this).closest('tr').find('.program_id').text();
                                    console.log(program_id)
                                    $('#delete_id').val(program_id);;
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



            
                <!-- /.container-fluid -->

                
    <!-- Bootstrap core JavaScript-->
    <!--<script src="vendor/jquery/jquery.min.js"></script>-->
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
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
    <!--<script src="js/demo/datatables-demo2.js"></script>-->
    <!--<script src="js/demo/viewTask_details.js"></script>-->
    <!--<script src="js/demo/faculty_table.js"></script>-->
    <!--<script src="js/demo/faculty_sched_table.js"></script>-->
     <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <!--<script src="vendor/datatables/jquery.dataTables.min.js"></script>-->
    <!--<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>-->


<?php }
}
else{

    header("Location: ../index.php");
}?>
</body>
</html>