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
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Deans - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="deans.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
   
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Deans of Colleges<sup></sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link tablinks" onclick="openCity(event, 'dashboard')" href="#">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Tasks</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item tablinks" href="#" onclick="openCity(event, 'createDocu')">Create Documents</a>
                        <a class="collapse-item tablinks" onclick="openCity(event, 'pendingDocu')" href="#">Pending Task</a>
                      
                    </div>
                </div>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed tablinks" href="#"  onclick="openCity(event, 'viewMonitoring')">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Submission Monitoring</span>
                </a>
              
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Addons
            </div>
             <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#automation"
                    aria-expanded="true" aria-controls="automation">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Automation</span>
                </a>
                <div id="automation" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Automation :</h6>
                        <a class="collapse-item tablinks" onclick="openCity(event, 'automated-documents')"  href="#">Documents</a>
            
                    </div>
                </div>
            </li>


            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Forecasting</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Login Screens:</h6>
                        <a class="collapse-item" href="#">Predict</a>
                       
                    </div>
                </div>
            </li>

          

         
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

           

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                 

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="upNotif()">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter" id="count_notif"></span>
                            </a>


                            
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in "
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Notifications
                                </h6>
                                <div class="notif">
                                    
                                </div>
                               
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>
                        <script type="text/javascript">
                         $(document).ready(function () {
                              $("#alertsDropdown").click(function (event) {
                                event.preventDefault(); // Prevent the default link behavior
                                
                                $.ajax({
                                  type: 'POST',
                                  url: 'upNotif.php',
                                  success: function(response) {
                                    // Handle the success response from the server
                                    console.log(response);
                                    // Optionally, you can perform additional actions or update the UI
                                  },
                                  error: function(xhr, status, error) {
                                    // Handle any errors that occur during the request
                                    console.error(error);
                                  }
                                });
                              });
                            });


                        </script>
                        <script type="text/javascript">
                                $(document).ready(function(){
                                        notif();
                                    });

                          


                                function notif(){
                                        $.ajax({
                                        type: "GET",
                                        url: "show_notif.php",
                                        success: function(response){
                                            console.log(response);
                                        $.each(response,function (key,value){
                                            // console.log(value['first_name']);
                                            $('.notif').append(
                                                    ' <a class="dropdown-item d-flex align-items-center" href="#">\
                                                        <div class="mr-3">\
                                                            <div class="icon-circle bg-success">\
                                                                <i class="fas fa-donate text-white"></i>\
                                                            </div>\
                                                        </div>\
                                                        <div>\
                                                            <div class="small text-gray-500">'+value['date']+'</div>\
                                                            '+value['content']+'\
                                                        </div>\
                                                    </a>'
                                            );
                                        });
                                        }


                                    });
                                }
                        </script>
                                <script type="text/javascript">


                                        function loadDoc() {
                                        setInterval(function() {
                                            var xhttp = new XMLHttpRequest();
                                            xhttp.onreadystatechange = function() {
                                                if (this.readyState == 4 && this.status == 200) {
                                                    document.getElementById('count_notif').innerHTML = this.responseText;
                                                }
                                            };
                                            xhttp.open("GET", "get_notif.php", true);
                                            xhttp.send();
                                        }, 1000);
                                    }

                                    loadDoc();



                                    </script>
                        
                       

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $department_name;?></span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid tabcontent" id="dashboard" >

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Pending Task</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                
                                                    <?php
                                                        $pending_count = mysqli_query($conn," SELECT
                                                        COUNT(*)


                                                        FROM departments dp
                                                        LEFT JOIN users u ON u.user_id = dp.user_id
                                                        LEFT JOIN task_status ts ON ts.`office_id` = dp.`department_id`
                                                        LEFT JOIN tasks t ON t.`task_id` = ts.`task_id`
                                                        WHERE dp.user_id = '$id' AND is_completed = 1 AND for_deans = 1");
                                                        $result = mysqli_fetch_assoc($pending_count);
                                                        if($result){
                                                            echo $result['COUNT(*)'];
                                                        }
                                                        else{
                                                            echo "0";
                                                        }

                                                     ?>


                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Completed Task</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    <?php
                                                        $pending_count = mysqli_query($conn,"SELECT
                                                        COUNT(*)


                                                        FROM departments dp
                                                        LEFT JOIN users u ON u.user_id = dp.user_id
                                                        LEFT JOIN task_status ts ON ts.`office_id` = dp.`department_id`
                                                        LEFT JOIN tasks t ON t.`task_id` = ts.`task_id`
                                                        WHERE dp.user_id = '$id' AND is_completed = 0
                                                        AND t.for_deans = 1
                                                        ");
                                                        $result = mysqli_fetch_assoc($pending_count);
                                                        if($result){
                                                            echo $result['COUNT(*)'];
                                                        }
                                                        else{
                                                            echo "0";
                                                        }

                                                     ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                     

                       
                    </div>

                    <!-- Content Row -->

                    <div class="row">

                   

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Task Partition Report</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-primary"></i> Direct
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-success"></i> Social
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-info"></i> Referral
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <!-- Content Column -->
                        <div class="col-lg-6 mb-4">

                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">All Tasks Completion</h6>
                                </div>
                                <div class="card-body">
                                    <h4 class="small font-weight-bold">OPCR <span
                                            class="float-right">90%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo 90 . "%";?>"
                                            aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Room Utilization Matrix <span
                                            class="float-right">40%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 40%"
                                            aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Schedule <span
                                            class="float-right">60%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar" role="progressbar" style="width: 60%"
                                            aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">IPCR <span
                                            class="float-right">80%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 80%"
                                            aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Account Setup <span
                                            class="float-right">Complete!</span></h4>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                       

                        <div class="col-lg-6 mb-4">

                        

                          

                        </div>
                    </div>

                    <!-- Footer -->
                    <footer class="sticky-footer bg-white">
                        <div class="container my-auto">
                            <div class="copyright text-center my-auto">
                                <span>Copyright &copy; Your Website 2021</span>
                            </div>
                        </div>
                    </footer>
                    <!-- End of Footer -->

                </div>







                  <div class="container-fluid tabcontent" id="insertData" style="display:none;">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-1 text-gray-800">Insert Data for Faculty Loading</h1>
                        <div class="card-body container">
                            <div class="row">
                                <div class="col">
                                    <a class=" btn btn-primary" style="top:100%;float: right;" href="generateReport.php">Generate Document</a>
                   
                                </div>
                             
                        
                                       
                                
                            </div>
                            <br><br><br>
                            <form method="POST" action="../php/insert_faculty_loading.php">
                              <div class="row">
                                   <div class="col">
                                   <label for="lname" class="form-label">Faculty</label>
                                    <input class="form-control" list="lnames" name="faculty" id="lname" placeholder="Enter faculty name">
                                    <datalist id="lnames">
                                        <?php 
                                            $sql = "SELECT DISTINCT firstname,lastname,middlename FROM faculties";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                                $name = $row['firstname'] .' ' . $row['middlename'] .' '. $row['lastname'];
                                            
                                        ?>
                                      <option value="<?php echo $name ?>">
                                      <?php }?>
                                    </datalist>
                                </div>
                            </div>
                            <div class="row">
                                 <div class="col">
                                   <label for="browser" class="form-label">Course Code</label>
                                    <input class="form-control" list="browsers" name="course_code" id="browser" placeholder="Choose a Course Code">
                                    <datalist id="browsers">
                                        <?php 
                                            $sql = "SELECT DISTINCT course_code FROM courses";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                                $code = $row['course_code'];
                                            
                                        ?>
                                      <option value="<?php echo $code ?>">
                                      <?php }?>
                                    </datalist>
                                </div>
                            </div>
                            <div class="row">
                                     <div class="col">
                                   <label for="ini" class="form-label">Section</label>
                                    <input class="form-control" list="inis" name="section" id="ini" placeholder="Ente Section ">
                                    <datalist id="inis">
                                        <?php 
                                            $sql = "SELECT DISTINCT section_name FROM sections";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                                $md = $row['section_name'];
                                            
                                        ?>
                                      <option value="<?php echo $md ?>">
                                      <?php }?>
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
                            </div>
                                      
                                
                              

                              

                               
                                 
                                 <div style="display: block; position:absolute;top:70%;right:8%;">
                            <button class="btn btn-success" type="submit" name="submit"style="width: 200px;">Insert data</button>
                        </div>
                        
                              </form>

                            
                        </div>
                        <br><br><br>
                        

                            
                          
                    
                </div>





               <div class="container-fluid tabcontent" id="createDocu" style="display: none;">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Document Templates</h1>

                    <div class="row">

                                <div class="col-xl-3 col-md-6 mb-4">
                               <a href="#" onclick="openCity(event,'insertData')">
                                    <div class="card border-left-primary shadow h-100 py-2">
                                        <div class="card-body">
                                            <div class="container">
                                                <img src="sample.jpg" width="100%" height="auto">
                                            </div>
                                        </div>                            
                                        <div class="text-xs font-weight-bold text-hello mb-1" onclick="openCity(event,'viewDocument')">Faculty Loading</div>
                                    </div>   
                                    </a>                                 
                                </div>
                            


                       

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <img src="sample.jpg" width="100%" height="auto">
                                </div>                            
                                <div class="text-xs font-weight-bold text-hello text-uppercase mb-1">IPCR</div>
                            </div>                                    
                        </div>


                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <img src="sample.jpg" width="100%" height="auto">
                                </div>                            
                                <div class="text-xs font-weight-bold text-hello mb-1">Faculty Loading</div>
                            </div>                                    
                        </div>


                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <img src="sample.jpg" width="100%" height="auto">
                                </div>                            
                                <div class="text-xs font-weight-bold text-hello mb-1">Accomplishment Report</div>
                            </div>                                    
                        </div>
                    </div>
                </div>


             <div class="container-fluid tabcontent" id="pendingDocu" style="display: none;">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h1">Pending Task</h1>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3">
        <?php 
             $conn = new mysqli("localhost","root","","dams2");
            if ($conn->connect_error) {
                    die("Connection failed : " . $conn->connect_error);
            }

            // $dept_name_sql = mysqli_query($conn,"SELECT department_abbrv FROM departments WHERE department_name = '$department_name'");
            // $dept_res = mysqli_fetch_assoc($dept_name_sql);

            // $dept_name = $dept_res['department_abbrv'];

            // echo $dept_name;




                $sql = "SELECT
                    tt.`task_id`,
                    tt.task_name,
                    tt.`task_desc`,
                    tt.date_posted,
                    tt.due_date,
                    tt.for_ovcaa,
                    tt.for_deans,
                    ts.`is_completed`,
                    ts.`office_id`,
                    dp.`department_name`
                    FROM tasks tt
                    LEFT JOIN task_status ts ON tt.task_id=ts.`task_id`
                    LEFT JOIN departments dp ON ts.`office_id`=dp.`department_id`
                    WHERE dp.`department_name` = 'Computer of Informatics and Computing Science' AND is_completed = 1
                    AND tt.for_deans = 1";
                $result = $conn->query($sql);
                while($row = mysqli_fetch_array($result)){
                    $taskid = $row['task_id'];
                    $taskName = $row['task_name'];
                    $posted = $row['date_posted'];
                    $deadline = $row['due_date'];
                    
                    
                
            ?>
        <div class="col">
            <div class="card" style="margin-top:20px">
                <div class="card-body d-flex justify-content-between" style="height: 140px;  width: 400px;">
                    <div class="task_info" style="white-space: nowrap;">
                        <p class="task_title"><b><?php echo $taskName; ?></b></p>
                        
                        <p class="task_info_text">
                            <span><?php echo $posted; ?></span>&nbsp;&nbsp;&nbsp;<span><?php echo $deadline; ?></span></p>
                        
                    </div>
                     <div class="d-flex flex-column">
                                    <a href="pending.php?id=<?php echo $taskid; ?>"  class="btn btn-primary btn-sm" style="margin-top: 40px; margin-right: 50px;" name="task">View</a>

                                </div>
                                <script type="text/javascript">


                                </script>
                </div>

            </div>
               <script type="text/javascript">

                function console_log(link){
                     console.log('Clicked Link:', link);
    }
    
    </script>
        
        </div>
       <?php }?>
    
        <script>
    const taskTitle = document.querySelectorAll('.task_title');
    taskTitle.forEach((taskTitle) => {
        const text = taskTitle.textContent;
        if (text.length > 15) {
            taskTitle.textContent = text.slice(0, 30) + '...';
        }
    });
    const taskOwner = document.querySelectorAll('.task_owner');
    taskOwner.forEach((taskOwner) => {
        const text = taskOwner.textContent;
        if (text.length > 15) {
            taskOwner.textContent = text.slice(0, 40) + '...';
        }
    });

</script>

    </div>
</div>
<div class="container-fluid tabcontent" id="pending-Details" style="display:none;">
            <h1 class="h3 mb-1 text-gray-800">Pending Documents | Faculty Loading</h1><br>

            <form>
                <div class="row">
                    <div class="col-xs-12 col-sm-7">
                        
                        <h3>TASK</h3>
                        <h6>June 27, 2023</h6>
                        <hr class="hr">
                        <p class="due-date">Due Date: June 30, 2023</p>
                        <p>From your Google Developer Profile, kindly attach a screenshot of your<br>
                            1. account (showing the account name and # of badge/s)<br>
                            2. actual badge/s<br>
                            <br>
                            See google doc for reference.
                        </p>
                    
                    </div>
                    <div class="col-xs-12 col-sm-4" id="upload">
                        <h5>Your Document</h5>
                        <div>
                            <h2 id="docuStatus"><span><i class="fa fa-file-text-o" aria-hidden="true"></i></span></h2>
                        </div>
                        <a type="btn" id="submit-btn-upload" class="btn btn-primary btn-sm">Submit</a>
                    </div>
                </div>
            </form>


        </div>

            <div class="container-fluid tabcontent" id="viewDocument" style="display: none;">
                <h1 class="h3 mb-1 text-gray-800">Document Name</h1>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Full Name</th>
                                            <th>Full Name</th>
                                            <th>Course</th>
                                            <th>Section</th>
                                            <th>No of. Students</th>
                                            <th>Total Units</th>
                                            <th>Lec hrs/per Week</th>
                                            <th>Rle hrs/per Week</th>
                                            <th>Lab hrs/per Week</th>
                                            <th>Total hrs/per Week</th>
                                            <th>Course Title</th>
                                            <th>Regular hrs</th>
                                            <th>Overload</th>
                                            <th>No. of Prep</th>
                                            <th>Type</th>
                                            <th>College</th>
                                            <th>Semester</th>
                                            <th>Academic Year</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <tr>
                                            <td>Bryan Russel Rosel</td>
                                            <td>IT 311</td>
                                            <td>BSIT 3301 BA</td>
                                            <td>38</td>
                                            <td>15</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>15</td>
                                            <td>Fundamentals of Business Analytics</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>1</td>
                                            <td>Permanent</td>
                                            <td>CICS</td>
                                            <td>ARASOF</td>
                                            <td>1</td>
                                            <td>2023-2024   </td>
                                        </tr>
                                        <tr>
                                            <td>Bryan Russel Rosel</td>
                                            <td>IT 311</td>
                                            <td>BSIT 3301 BA</td>
                                            <td>38</td>
                                            <td>15</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>15</td>
                                            <td>Fundamentals of Business Analytics</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>1</td>
                                            <td>Permanent</td>
                                            <td>CICS</td>
                                            <td>ARASOF</td>
                                            <td>1</td>
                                            <td>2023-2024</td>
                                        </tr>                                         
                                        <tr>
                                            <td>Bryan Russel Rosel</td>
                                            <td>IT 311</td>
                                            <td>BSIT 3301 BA</td>
                                            <td>38</td>
                                            <td>15</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>15</td>
                                            <td>Fundamentals of Business Analytics</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>1</td>
                                            <td>Permanent</td>
                                            <td>CICS</td>
                                            <td>ARASOF</td>
                                            <td>1</td>
                                            <td>2023-2024</td>
                                        </tr> 
                                        <tr>
                                            <td>Bryan Russel Rosel</td>
                                            <td>IT 311</td>
                                            <td>BSIT 3301 BA</td>
                                            <td>38</td>
                                            <td>15</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>15</td>
                                            <td>Fundamentals of Business Analytics</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>1</td>
                                            <td>Permanent</td>
                                            <td>CICS</td>
                                            <td>ARASOF</td>
                                            <td>1</td>
                                            <td>2023-2024</td>
                                        </tr>
                                        <tr>
                                            <td>Bryan Russel Rosel</td>
                                            <td>IT 311</td>
                                            <td>BSIT 3301 BA</td>
                                            <td>38</td>
                                            <td>15</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>15</td>
                                            <td>Fundamentals of Business Analytics</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>1</td>
                                            <td>Permanent</td>
                                            <td>CICS</td>
                                            <td>ARASOF</td>
                                            <td>1</td>
                                            <td>2023-2024</td>
                                        </tr>
                                        <tr>
                                            <td>Bryan Russel Rosel</td>
                                            <td>IT 311</td>
                                            <td>BSIT 3301 BA</td>
                                            <td>38</td>
                                            <td>15</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>15</td>
                                            <td>Fundamentals of Business Analytics</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>1</td>
                                            <td>Permanent</td>
                                            <td>CICS</td>
                                            <td>ARASOF</td>
                                            <td>1</td>
                                            <td>2023-2024</td>
                                        </tr>
                                        <tr>
                                            <td>Bryan Russel Rosel</td>
                                            <td>IT 311</td>
                                            <td>BSIT 3301 BA</td>
                                            <td>38</td>
                                            <td>15</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>15</td>
                                            <td>Fundamentals of Business Analytics</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>1</td>
                                            <td>Permanent</td>
                                            <td>CICS</td>
                                            <td>ARASOF</td>
                                            <td>1</td>
                                            <td>2023-2024</td>
                                        </tr>
                                        <tr>
                                            <td>Bryan Russel Rosel</td>
                                            <td>IT 311</td>
                                            <td>BSIT 3301 BA</td>
                                            <td>38</td>
                                            <td>15</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>15</td>
                                            <td>Fundamentals of Business Analytics</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>1</td>
                                            <td>Permanent</td>
                                            <td>CICS</td>
                                            <td>ARASOF</td>
                                            <td>1</td>
                                            <td>2023-2024</td>
                                        </tr>
                                        <tr>
                                            <td>Bryan Russel Rosel</td>
                                            <td>IT 311</td>
                                            <td>BSIT 3301 BA</td>
                                            <td>38</td>
                                            <td>15</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>15</td>
                                            <td>Fundamentals of Business Analytics</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>1</td>
                                            <td>Permanent</td>
                                            <td>CICS</td>
                                            <td>ARASOF</td>
                                            <td>1</td>
                                            <td>2023-2024</td>
                                        </tr>
                                        <tr>
                                            <td>Bryan Russel Rosel</td>
                                            <td>IT 311</td>
                                            <td>BSIT 3301 BA</td>
                                            <td>38</td>
                                            <td>15</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>15</td>
                                            <td>Fundamentals of Business Analytics</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>1</td>
                                            <td>Permanent</td>
                                            <td>CICS</td>
                                            <td>ARASOF</td>
                                            <td>1</td>
                                            <td>2023-2024</td>
                                        </tr>
                                        <tr>
                                            <td>Bryan Russel Rosel</td>
                                            <td>IT 311</td>
                                            <td>BSIT 3301 BA</td>
                                            <td>38</td>
                                            <td>15</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>15</td>
                                            <td>Fundamentals of Business Analytics</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>1</td>
                                            <td>Permanent</td>
                                            <td>CICS</td>
                                            <td>ARASOF</td>
                                            <td>1</td>
                                            <td>2023-2024</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

            </div>
            


            <div class="container-fluid tabcontent" id="viewMonitoring" style="display:none">
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


                <div class="container-fluid tabcontent" id="fill-Up" style="display:none;">
                    <h1 class="h3 mb-1 text-gray-800">Create Task | OPCR</h1>
                    <div>
                        <br>
                        <label>Description</label><br>
                        <input type="text" id="input-description" class="input-description">
                    </div>

                    <div>
                        <br>
                        <label>Date Start</label><br>
                        <input type="date" id="input-description" class="input-description">
                    </div>

                    <div>
                        <br>
                        <label>Date End</label><br>
                        <input type="date" id="input-description" class="input-description">
                    </div>

                    <br>
                    <label class="checkbox-text">Faculty
                      <input type="checkbox" checked="checked">
                      <span class="checkmark"></span>
                    </label>
<!--                     <label class="container">Something Else
                      <input type="checkbox">
                      <span class="checkmark"></span>
                    </label> -->

                    <div class="createTask-submit">
                        <div class="d-flex flex-column">
                            <a href="#" class="btn btn-primary btn-sm" id="TaskSubmit" style="margin-top: 40px; margin-right: 50px;">Submit</a>
                        <p style="text-align: center ;">Or</p>
                        <a href="#" class="btn btn-primary btn-sm" id="TaskImport" style="margin-top: 40px; margin-right: 50px;">Import</a>
                        </div>
                    </div>

                </div>



            <div class="container-fluid tabcontent" id="submissionMonitoring" style="display:none;">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-1 text-gray-800">Submission Monitoring</h1>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3">
                        <div class="col">
                            <div class="card" style="margin-top:20px">
                                <div class="card-body d-flex justify-content-between" style="height: 140px;  width: 400px;">
                                    <div class="task_info" style="white-space: nowrap;">
                                        <p class="task_title"><b>OPCR</b></p>
                                        <p class="task_owner">Office of Vice Chancellor of Academic Affairs</p>
                                        <p class="task_info_text">
                                            <span>Posted: June 10, 2023</span>&nbsp;&nbsp;&nbsp;<span>Due Date: June 30, 2023</span></p>
                                        
                                    </div>
                                    <div class="d-flex flex-column">
                                        <a href="#" onclick="openCity(event, 'viewMonitoring')" class="btn btn-primary btn-sm" style="margin-top: 40px; margin-right: 50px;">View</a>
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
                                            <td>06-21-2023</t>
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


            </div>

            <!-- End of Main Content -->
            
            <!-- Footer -->
<!--             <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer> -->
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger" href="../php/logout.php?logout_id=<?php echo $users_id; ?>" target="_blank" style="margin-right: 10px;">Logout</a>
                </div>
            </div>
        </div>
    </div>
  

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
    <script src="js/demo/datatables-demo2.js"></script>
    <script src="js/demo/viewTask_details.js"></script>
    <script src="js/demo/faculty_table.js"></script>
    <script src="js/demo/faculty_sched_table.js"></script>
     <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

</body>

</html>
<?php 

}}else{
    header("Location: ../index.php");
}?>