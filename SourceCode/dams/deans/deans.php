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
<html lang="en">
<?php include "../header/header_deans.php"; ?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

       <?php include "../sidebar/sidebar_deans.php"; ?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include "../topbar/topbar_deans.php"?>
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
                                                        $pending_count = mysqli_query($conn,"  SELECT
                        COUNT(*)
                        FROM tasks tt
                        LEFT JOIN task_status_deans ts ON tt.task_id=ts.`task_id`
                        LEFT JOIN departments dp ON ts.`office_id`=dp.`department_id`
                        WHERE tt.for_deans = 1 AND dp.`department_abbrv`='CICS' AND ts.`is_completed` = 1");
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
                                                        $pending_count = mysqli_query($conn,"  SELECT
                        COUNT(*)
                        FROM tasks tt
                        LEFT JOIN task_status_deans ts ON tt.task_id=ts.`task_id`
                        LEFT JOIN departments dp ON ts.`office_id`=dp.`department_id`
                        WHERE tt.for_deans = 1 AND dp.`department_abbrv`='CICS' AND ts.`is_completed` = 0");
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
                                    <h4 class="small font-weight-bold">Faculty Loading <span
                                            class="float-right"><?php
                                            // Create a MySQLi connection
                                            $conn = new mysqli("localhost", "root", "", "dams2");

                                            // Check connection
                                            if ($conn->connect_error) {
                                                die("Connection failed: " . $conn->connect_error);
                                            }

                                            $query = "SELECT ROUND((COUNT(CASE WHEN is_completed = 0 THEN 1 END) / COUNT(*)) * 100) AS percentage_completed
                                                      FROM task_status_deans
                                                      WHERE office_id = 8";

                                            $result = $conn->query($query);
                                            if ($result->num_rows > 0) {
                                                $row = $result->fetch_assoc();
                                                echo $row['percentage_completed'] . "%";
                                            } else {
                                                echo "0%";
                                            }

                                            $conn->close();
                                            ?></span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width:<?php
                                                // Create a MySQLi connection
                                                $conn = new mysqli("localhost", "root", "", "dams2");

                                                // Check connection
                                                if ($conn->connect_error) {
                                                    die("Connection failed: " . $conn->connect_error);
                                                }

                                                $query = "SELECT ROUND((COUNT(CASE WHEN is_completed = 0 THEN 1 END) / COUNT(*)) * 100) AS percentage_completed
                                                          FROM task_status_deans
                                                          WHERE office_id = 8";

                                                $result = $conn->query($query);
                                                if ($result->num_rows > 0) {
                                                    $row = $result->fetch_assoc();
                                                    echo $row['percentage_completed'] . "%";
                                                } else {
                                                    echo "0%";
                                                }

                                                $conn->close();
                                                ?>
                                                "

                                            aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
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
                                            $sql = "SELECT DISTINCT faculty_id,firstname,lastname,middlename FROM faculties";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                                $id = $row['faculty_id'];
                                                $name = $row['firstname'] .' ' . $row['middlename'] .' '. $row['lastname'];
                                            
                                        ?>
                                      <option value="<?php  echo $name ?>">
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