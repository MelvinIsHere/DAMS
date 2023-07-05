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
            LEFT JOIN departments d ON u.user_id = d.user_id
            WHERE unique_id = '$users_id' 
    
            ");
    $data_result = mysqli_fetch_assoc($data);
    $department_name = $data_result['department_name'];

    if($data_result){


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
                <div class="container-fluid tabcontent" id="viewFacultyLoading" style="display:none;">
                    <h1 class="h3 mb-1 text-gray-800">Documents Tracking</h1>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="faculty_loading_table" width="100%" cellspacing="0">
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
                                                    t.due_date,
                                                    t.task_name,
                                                    f.file_id

                                                    FROM departments dp
                                                    LEFT JOIN users u ON u.user_id = dp.user_id
                                                    LEFT JOIN task_status ts ON ts.`office_id` = dp.`department_id`
                                                    LEFT JOIN tasks t ON t.`task_id` = ts.`task_id`
                                                    LEFT JOIN file_table f ON f.file_owner_id = u.user_id
                                                    WHERE t.task_id IS NOT NULL";
                                                $result = $conn->query($sql);
                                                while($row = mysqli_fetch_array($result)){
                                                   $user_id = $row['user_id'];
                                                   $email = $row['email'];
                                                   $position = $row['type'];
                                                   $abbrv = $row['department_abbrv'];
                                                   $due_date = $row['due_date'];
                                                   $status = $row['is_completed'];
                                                   $file_id = $row['file_id'];


                                                    
                                                    
                                                
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
                                             <td><a class="btn btn-primary" href="../view_files.php?id=<?php echo $file_id?>">View<a></td>
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
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>06-21-2023</td>
                                            <td class="ns">Not Submitted</td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>06-21-2023</td>
                                            <td class="ns">Not Submitted</td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>06-21-2023</td>
                                            <td class="ns">Not Submitted</td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>06-21-2023</td>
                                            <td class="ns">Not Submitted</td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>06-21-2023</td>
                                            <td class="ns">Not Submitted</td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>06-21-2023</td>
                                            <td class="ns">Not Submitted</td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>06-21-2023</td>
                                            <td class="ns" >Not Submitted</td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>06-21-2023</td>
                                            <td class="ns" >Not Submitted</td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>06-21-2023</td>
                                            <td class="ns" >Not Submitted</td>
                                        </tr>
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
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>06-21-2023</td>
                                            <td class="ns">Not Submitted</td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>06-21-2023</td>
                                            <td class="ns">Not Submitted</td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>06-21-2023</td>
                                            <td class="ns">Not Submitted</td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>06-21-2023</td>
                                            <td class="ns">Not Submitted</td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>06-21-2023</td>
                                            <td class="ns">Not Submitted</td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>06-21-2023</td>
                                            <td class="ns">Not Submitted</td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>OPCR</td>
                                            <td class="ns" >Not Submitted</td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>06-21-2023</td>
                                            <td class="ns" >Not Submitted</td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>06-21-2023</td>
                                            <td class="ns" >Not Submitted</td>
                                        </tr>
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