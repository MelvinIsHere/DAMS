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
            WHERE unique_id = '$users_id' 
    
            ");
    $data_result = mysqli_fetch_assoc($data);
    $department_name = $data_result['department_name'];

    if($data_result){


?>
<!DOCTYPE html>
<html>
<?php  include "../header/header_heads.php"?>
<body>
    <div id="wrapper">
    <?php
     include "../sidebar/sidebar_heads.php";
     ?>
  <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
    <?php include "../topbar/topbar_heads.php"; ?>
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


           
        <!-- Faculty Loading -->
                <div class="container-fluid tabcontent" id="viewFacultyLoading" style="display:none;">
                    <h1 class="h3 mb-1 text-gray-800">Documents Tracking</h1>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="faculty_loading_table" width="100%" cellspacing="0">
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

// function myFunction() {
//   var input, filter, table, tr, td, i, txtValue;
//   input = document.getElementById("myInput");
//   filter = input.value.toUpperCase();
//   table = document.getElementById("dataTable");
//   tr = table.getElementsByTagName("tr");
//   for (i = 0; i < tr.length; i++) {
//     td = tr[i].getElementsByTagName("td")[0];
//     if (td) {
//       txtValue = td.textContent || td.innerText;
//       if (txtValue.toUpperCase().indexOf(filter) > -1) {
//         tr[i].style.display = "";
//       } else {
//         tr[i].style.display = "none";
//       }
//     }       
//   }
// }


</script>
                <!-- /.container-fluid -->

          
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
    <script src="js/demo/admin_faculty_loading.js"></script>
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