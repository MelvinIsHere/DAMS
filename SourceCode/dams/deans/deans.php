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
                        <a class="collapse-item tablinks" onclick="openCity(event, 'createTask')" href="#">Create Task</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Monitoring</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Monitoring :</h6>
                        <a class="collapse-item tablinks" onclick="openCity(event, 'documentTracking')"  href="#">Documents Tracking</a>
                        <a class="collapse-item tablinks" onclick="openCity(event, 'userLoginMonitoring')" href="#">User Login History</a>
                        <a class="collapse-item tablinks"  onclick="openCity(event, 'viewMonitoring')" href="#">Submission Monitoring</a>
                        
                    </div>
                </div>
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

                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

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
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 12, 2019</div>
                                        <span class="font-weight-bold">A new monthly report is ready to download!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-donate text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 7, 2019</div>
                                        $290.29 has been deposited into your account!
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 2, 2019</div>
                                        Spending Alert: We've noticed unusually high spending for your account.
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter">7</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_1.svg"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                            problem I've been having.</div>
                                        <div class="small text-gray-500">Emily Fowler · 58m</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_2.svg"
                                            alt="...">
                                        <div class="status-indicator"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">I have the photos that you ordered last month, how
                                            would you like them sent to you?</div>
                                        <div class="small text-gray-500">Jae Chun · 1d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_3.svg"
                                            alt="...">
                                        <div class="status-indicator bg-warning"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Last month's report looks great, I am very happy with
                                            the progress so far, keep up the good work!</div>
                                        <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                                            told me that people say this to all dogs, even if they aren't good...</div>
                                        <div class="small text-gray-500">Chicken the Dog · 2w</div>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Deans of Colleges1</span>
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
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">5</div>
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
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">10</div>
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
                                            class="float-right">20%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 20%"
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
               <div class="container-fluid tabcontent" id="createDocu" style="display: none;">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Document Templates</h1>

                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="container">
                                         <img src="business-requirements-document-template-09.jpg" width="100%" height="auto">
                                    </div>
                                </div>                            
                                <div class="text-xs font-weight-bold text-hello mb-1" onclick="openCity(event,'viewDocument')">OPCR</div>
                            </div>                                    
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <img src="business-requirements-document-template-09.jpg" width="100%" height="auto">
                                </div>                            
                                <div class="text-xs font-weight-bold text-hello text-uppercase mb-1">IPCR</div>
                            </div>                                    
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <img src="business-requirements-document-template-09.jpg" width="100%" height="auto">
                                </div>                            
                                <div class="text-xs font-weight-bold text-hello text-uppercase mb-1">IPCR</div>
                            </div>                                    
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
                                    <img src="business-requirements-document-template-09.jpg" width="100%" height="auto">
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
                                    <a href="#" class="btn btn-primary btn-sm" style="margin-top: 40px; margin-right: 50px;">View</a>
                                </div>
                            </div>

                        </div>
                    </div>
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
                                    <a href="#" class="btn btn-primary btn-sm" style="margin-top: 40px; margin-right: 50px;">View</a>
                                </div>
                            </div>
                            
                        </div>
                    </div>
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
                                    <a href="#" class="btn btn-primary btn-sm" style="margin-top: 40px; margin-right: 50px;">View</a>
                                </div>
                            </div>
                            
                        </div>
                    </div>
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
                                    <a href="#" class="btn btn-primary btn-sm" style="margin-top: 40px; margin-right: 50px;">View</a>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
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
                                    <a href="#" class="btn btn-primary btn-sm" style="margin-top: 40px; margin-right: 50px;">View</a>
                                </div>
                            </div>
                            
                        </div>
                    </div>
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

                 <div class="container-fluid tabcontent" id="createTask" style="display:none;">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-1 text-gray-800">Create Tasks</h1>
                

                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="container">
                                         <img src="business-requirements-document-template-09.jpg" width="100%" height="auto">
                                    </div>
                                </div>                            
                                <a class="text-xs font-weight-bold text-hello mb-1" href="#fill-Up"  onclick="openCity(event, 'fill-Up')">OPCR</a>
                            </div>                                    
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <img src="business-requirements-document-template-09.jpg" width="100%" height="auto">
                                </div>                            
                                <div class="text-xs font-weight-bold text-hello mb-1">Faculty Schedule</div>
                            </div>                                    
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <img src="business-requirements-document-template-09.jpg" width="100%" height="auto">
                                </div>                            
                                <div class="text-xs font-weight-bold text-hello text-uppercase mb-1">IPCR</div>
                            </div>                                    
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
                                    <img src="business-requirements-document-template-09.jpg" width="100%" height="auto">
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

                 <div class="container-fluid tabcontent" id="documentTracking" style="display:none;">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-1 text-gray-800">Documents Tracking</h1>
                        <div class="card-body">
                            <div class="table-responsive">
                                <!-- <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name"> -->
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>Full Name</th>
                                            <th>Position</th>
                                            <th>Department</th>
                                            <th>Documents</th>
                                            <th>Document Status</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Tiger Woods</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>Accomplishment Report </td>
                                            <td class="ns">Not Submitted</td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>Faculty Loading</td>
                                            <td class="submitted">Submitted</td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>Accomplishment Report</td>
                                            <td class="submitted" >Submitted</td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>Faculty Schedule</t>
                                            <td class="submitted" >Submitted</td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>Room Schedule</td>
                                            <td class="submitted" >Submitted</td>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>Room Schedule</td>
                                            <td class="submitted" >Submitted</td>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>Room Schedule</td>
                                            <td class="submitted" >Submitted</td>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>Room Schedule</td>
                                            <td class="submitted" >Submitted</td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>OPCR</td>
                                            <td class="submitted" >Submitted</td>
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
                                            <td>OPCR</td>
                                            <td class="ns" >Not Submitted</td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>OPCR</td>
                                            <td class="ns" >Not Submitted</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                   

                </div>
               <div class="container-fluid tabcontent" id="userLoginMonitoring" style="display:none;">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">User Login Monitoring</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>Full Name</th>
                                            <th>Position</th>
                                            <th>Department</th>
                                            <th>Login</th>
                                            <th>Logout</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>

                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                         <tr>
                                            <td>1</td>
                                            <td>Tiger Nixon</td>
                                            <td>Staff</td>
                                            <td>CICS</td>
                                            <td>2023/04/25 00 00 00 </td>
                                            <td>2023/04/25 00 00 00 </td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            <!-- </div> -->



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
                    <a class="btn btn-primary" href="login.html">Logout</a>
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