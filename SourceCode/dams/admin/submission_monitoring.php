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
            d.department_abbrv
            FROM users u
            LEFT JOIN departments d ON u.department_id = d.department_id
           WHERE user_id = '$id' 
    
            ");
    

   

    while($row = mysqli_fetch_array($data)){
         $department_name = $row['department_name'];
        $img = $row['img'];

        $type =$row['type'];

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
        <div class="container-fluid tabcontent" id="viewMonitoring">
                <div>
                                        <!-- Page Heading -->
                    <h1 class="h3 mb-1 text-gray-800">Submission Monitoring</h1>

                    <div class="col">
                        <div class="card" id="cardbtn" style="margin-top:20px">
                            <div class="card-body" id="card-body" style="height: 140px;  width: auto;">
                                <div class="task_info" style="white-space: nowrap;">
                                    <h3 class="h3 mb-1 text-gray-800">Accomplishment Report</h3>
                                    <p class="task_info_text"><span>Due Date: June 30, 2023</span></p>
                                    
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="#" class="btn btn-primary btn-sm" id="viewTaskbtn" onclick="openCity(event, 'viewTask-details')" style="margin-top: 40px; margin-right: 50px;">View Assigned Task</a>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                    <div class="col">
                        <div class="card" style="margin-top:20px">
                            <div class="card-body" id="card-body" style="height: 140px;  width:auto;">
                                <div class="task_info" style="white-space: nowrap;">
                                    <h3 class="h3 mb-1 text-gray-800">Faculty Loading</h3>
                                    <p class="task_info_text"><span>Due Date: June 30, 2023</span></p>
                                    
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="#" class="btn btn-primary btn-sm" id="viewTaskbtn" onclick="openCity(event, 'viewFacultyLoading')" style="margin-top: 40px; margin-right: 50px;">View Assigned Task</a>
                                </div>
                            </div>
                            
                        </div>
                    </div>

                    <div class="col">
                        <div class="card" style="margin-top:20px">
                            <div class="card-body" id="card-body"
                             style="height: 140px;  width: auto;">
                                <div class="task_info" style="white-space: nowrap;">
                                    <h3 class="h3 mb-1 text-gray-800">Faculty Schedule</h3>
                                    <p class="task_info_text"><span>Due Date: June 30, 2023</span></p>
                                    
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="#" class="btn btn-primary btn-sm" id="viewTaskbtn" onclick="openCity(event, 'viewFacultySched')" style="margin-top: 40px; margin-right: 50px;">View Assigned Task</a>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <!-- Faculty Loading -->
                <div class="container-fluid tabcontent" id="viewFacultyLoading" style="display: none;">
    <h1 class="h3 mb-1 text-gray-800">Program Management</h1>
    <div class="card-body">
        <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-xs-6">
                            <h2>Programs</b></h2>
                        </div>
                        <div class="col-xs-6">
                            <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Program</span></a>                                                                              
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-hover">
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
                                    FROM programs");
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
                                                    WHERE task_name = 'Faculty Loading'
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
                                <a href="../return.php?id=<?php echo $status_id ?>&task_name=Accomplishment%20Report" class="btn-warning btn">Return</a>
                            </td>
                        </tr>
                    <?php 

                         // Break the loop if the desired limit is reached
    if ($count > $total_records_per_page) {
        break;
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
</div>
                                <!-- Faculty Sched -->
                <div class="container-fluid tabcontent" id="viewFacultySched" style="display:none;">
                    <h1 class="h3 mb-1 text-gray-800">Documents Tracking| Faculty Schedule</h1>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="facutySched" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>Full Name</th>
                                            <th>Position</th>
                                            <th>Department</th>
                                            <th>Due Date</th>
                                            <th>Document Status</th>
                                            <th>File</th>
                                        </tr>
                                    </thead>
                                    
                                 <tbody>
                                         <?php 
                                             $conn = new mysqli("localhost","root","","dams2");
                                            if ($conn->connect_error) {
                                                    die("Connection failed : " . $conn->connect_error);
                                            }

                                                $sql = "

                                                SELECT DISTINCT
                                                    dp.`department_name`,
                                                    dp.`department_abbrv`,
                                                    u.user_id,
                                                    u.type,
                                                    u.email,
                                                    ts.is_completed,
                                                    ts.status_id AS 'status_id',
                                                    t.due_date,
                                                    t.task_name,
                                                    f.file_id

                                                    FROM departments dp
                                                    LEFT JOIN users u ON u.department_id = dp.department_id
                                                    LEFT JOIN task_status_deans ts ON ts.`office_id` = dp.`department_id`
                                                    LEFT JOIN tasks t ON t.`task_id` = ts.`task_id`
                                                    LEFT JOIN file_table f ON f.file_owner_id = u.user_id
                                                    WHERE task_name = 'Faculty Schedule'
                                                    GROUP BY dp.department_id";
                                                $result = $conn->query($sql);
                                                while($row = mysqli_fetch_array($result)){
                                                   $user_id = $row['user_id'];
                                                   $email = $row['email'];
                                                   $position = $row['type'];
                                                   $abbrv = $row['department_abbrv'];
                                                   $due_date = $row['due_date'];
                                                   $status = $row['is_completed'];
                                                   $file_id = $row['file_id'];
                                                   $status_id = $row['status_id'];


                                                    
                                                    
                                                
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
                                             <td><a class="btn btn-primary" href="../view_files.php?id=<?php echo $file_id?>">View<a><a class="btn btn-primary" href="../return.php?id=<?php echo $status_id ?>&task_name=Accomplishment%20Report">Return</a>
</td>

                                        </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                   
                    <div class="d-flex flex-column">
                        <a href="#" class="btn btn-warning btn-sm" id="viewTask-back" onclick="openCity(event, 'viewMonitoring')" style="margin-top: 20px;margin-bottom: 20px; margin-right: 50px;">Back</a>
                    </div>
                </div> 

                      <!-- Accomplishment Report -->
                 <div class="container-fluid tabcontent" id="viewTask-details" style="display:none;">

                    <h1 class="h3 mb-1 text-gray-800">Documents Tracking | Accomplishment Report</h1>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                                     <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>Full Name</th>
                                            <th>Position</th>
                                            <th>Department</th>
                                            <th>Due Date</th>
                                            <th>Document Status</th>
                                            <th>File</th>
                                        </tr>
                                    </thead>
                                    
                                  <tbody>
                                         <?php 
                                             $conn = new mysqli("localhost","root","","dams2");
                                            if ($conn->connect_error) {
                                                    die("Connection failed : " . $conn->connect_error);
                                            }

                                                $sql = "

                                                SELECT DISTINCT
                                                    dp.`department_name`,
                                                    dp.`department_abbrv`,
                                                    u.user_id,
                                                    u.type,
                                                    u.email,
                                                    ts.is_completed,
                                                    ts.status_id AS 'status_id',
                                                    t.due_date,
                                                    t.task_name,
                                                    f.file_id

                                                    FROM departments dp
                                                    LEFT JOIN users u ON u.department_id = dp.department_id
                                                    LEFT JOIN task_status_deans ts ON ts.`office_id` = dp.`department_id`
                                                    LEFT JOIN tasks t ON t.`task_id` = ts.`task_id`
                                                    LEFT JOIN file_table f ON f.file_owner_id = u.user_id
                                                    WHERE task_name = 'Accomplishment Report'
                                                    GROUP BY dp.user_id";
                                                $result = $conn->query($sql);
                                                while($row = mysqli_fetch_array($result)){
                                                   $user_id = $row['user_id'];
                                                   $email = $row['email'];
                                                   $position = $row['type'];
                                                   $abbrv = $row['department_abbrv'];
                                                   $due_date = $row['due_date'];
                                                   $status = $row['is_completed'];
                                                   $file_id = $row['file_id'];
                                                   $status_id = $row['status_id'];


                                                    
                                                    
                                                
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
                                             <td><a class="btn btn-primary" href="../view_files.php?id=<?php echo $file_id?>">View<a><a class="btn btn-primary" href="../return.php?id=<?php echo $status_id ?>&task_name=Accomplishment%20Report">Return</a>
</td>

                                        </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                   
                    <div class="d-flex flex-column">
                        <a href="#" class="btn btn-warning btn-sm" id="viewTask-back" onclick="openCity(event, 'viewMonitoring')" style="margin-top: 20px;margin-bottom: 20px; margin-right: 50px;">Back</a>
                    </div>
                </div>
                <!-- /.container-fluid -->

                  <script>
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}
</script>

         
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
 
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

    <script src="js/demo/datatables-demo.js"></script>
    <script src="js/demo/viewTask_details.js"></script>
    <script src="js/demo/faculty_loading.js"></script>
    <script src="js/demo/faculty_sched_table.js"></script>
     <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>


<?php }
}?>
</body>
</html>