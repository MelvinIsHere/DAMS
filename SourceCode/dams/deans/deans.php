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
        $term_id = $_SESSION['term_id'];



?><!DOCTYPE html>
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
                    <div class="row">
                        
                    </div>
                    <div class="d-sm-flex align-items-center justify-content-between mb-4 row">
                       <b> <h1 class="h3 text-gray-800 col-md-8 fw-bold">Dashboard</h1></b>
                        <!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i-->
                        <!--        class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
                        <h6 class="col-md-4 fw-bold"><b><?php 
                        include "../config.php";
                        $acad_id = $_SESSION['acad_id'];
                        $query = mysqli_query($conn,"SELECT acad_year FROM academic_year WHERE acad_year_id = '$acad_id'");
                        if($query){
                            if(mysqli_num_rows($query) > 0){
                                $row = mysqli_fetch_assoc($query);
                                $acad_year = $row['acad_year'];
                                echo $acad_year;
                            }else{

                            }   
                        }else{

                        }
                        ?>
                        </b>
                        <?php
                        $semester_id = $_SESSION['semester_id'];

                        $semester = mysqli_query($conn,"SELECT sem_description FROM semesters WHERE semester_id = '$semester_id'");
                        if($semester){
                            if(mysqli_num_rows($semester)){
                            $row = mysqli_fetch_assoc($semester);
                                echo " ".$row['sem_description'] . " Semester";
                            }else{

                            }
                        }else{

                        }

                         ?><a href="#addEmployeeModal" data-toggle="modal"><i class="fas fa-filter"></i></a></h6>
                        
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                <h6><strong>Pending Task</strong></h6>
                                                </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <center>
                                                    <?php
                                                     $sem_id =$_SESSION['semester_id'];
                                                     $acad_id =$_SESSION['acad_id'];
                                                        $pending_count = mysqli_query($conn,"  SELECT
                                                            COUNT(*)
                                                            FROM tasks tt
                                                            LEFT JOIN task_status_deans ts ON tt.task_id=ts.`task_id`
                                                            LEFT JOIN departments dp ON ts.`office_id`=dp.`department_id`
                                                            WHERE tt.for_deans = 1 AND ts.user_id = '$id' AND ts.`is_completed` = '1'
                                                            AND tt.term_id = '$term_id'");
                                                            $result = mysqli_fetch_assoc($pending_count);
                                                        if($result){
                                                            echo $result['COUNT(*)'];
                                                        }
                                                        else{
                                                            echo "0";
                                                        }

                                                     ?>

                                                        </center>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-book fa-2x text-danger"></i>
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
                                                <h6><strong>Completed Task</strong></h6>
                                                </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <center>
                                                    <?php
                                                    $sem_id =$_SESSION['semester_id'];
                                                    $acad_id =$_SESSION['acad_id'];
                                                        $pending_count = mysqli_query($conn,"  SELECT
                                                        COUNT(*)
                                                        FROM tasks tt
                                                        LEFT JOIN task_status_deans ts ON tt.task_id=ts.`task_id`
                                                        LEFT JOIN departments dp ON ts.`office_id`=dp.`department_id`
                                                        WHERE tt.for_deans = 1 AND ts.user_id = '$id' AND ts.`is_completed` = '0' 
                                                        AND tt.term_id = '$term_id'");
                                                        $result = mysqli_fetch_assoc($pending_count);
                                                        if($result){
                                                            echo $result['COUNT(*)'];
                                                        }
                                                        else{
                                                            echo "0";
                                                        }
                                                        
                                                     ?></center></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-check fa-2x text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                     

                       
                    </div>

                    <!-- Content Row -->

                    <div class="row">

                   
                         <!-- Content Column -->
                       <div class="container-fluid">
    <div class="row" >
        <div class="col-lg-12 mb-12" > <!-- Change the column width to occupy the full width -->
            <!-- Project Card Example -->
            <div class="card shadow mb-12" >
                <div class="card-header py-3" style="background-color:#A52A2A">
                    <h6 class="m-0 font-weight-bold" style="color:white">All Tasks Completion</h6>
                </div>
                <div class="card-body">
                    <h4 class="small font-weight-bold"> <span class="float-right">
                        <?php
                        // // Create a MySQLi connection
                        // $conn = new mysqli("localhost", "root", "", "dams2");

                        // // Check connection
                        // if ($conn->connect_error) {
                        //     die("Connection failed: " . $conn->connect_error);
                        // }
                        include "../config.php";
                        $sem_id = $_SESSION['semester_id'];
                        $acad_id = $_SESSION['acad_id'];
                        $query = "SELECT 
                                    ROUND((COUNT(CASE WHEN tsd.is_completed = 0 THEN 1 END) / COUNT(*)) * 100) 
                                    AS percentage_completed

                                FROM tasks t
                                LEFT JOIN task_status_deans tsd ON tsd.task_id = t.task_id
                                WHERE tsd.office_id = '$department_id'
                               AND t.term_id = '$term_id'";

                        $result = $conn->query($query);
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            echo $row['percentage_completed'] . "%";
                        } else {
                            echo "0%";
                        }

                        $conn->close();
                        ?>
                        </span>
                    </h4>
                    <div class="progress mb-4" style="width: 100%;"> <!-- Set the width to 100% to span the entire container -->
                        <div class="progress-bar" role="progressbar" style="background-color:#00BFA5;width: <?php
                            // Create a MySQLi connection
                            // $conn = new mysqli("localhost", "root", "", "dams2");

                            // // Check connection
                            // if ($conn->connect_error) {
                            //     die("Connection failed: " . $conn->connect_error);
                            // }
                            include "../config.php";

                            $query = "SELECT 
                                    ROUND((COUNT(CASE WHEN tsd.is_completed = 0 THEN 1 END) / COUNT(*)) * 100) 
                                    AS percentage_completed

                                FROM tasks t
                                LEFT JOIN task_status_deans tsd ON tsd.task_id = t.task_id
                                WHERE tsd.office_id = '$department_id'
                                AND t.sem_id = '$sem_id' AND t.acad_year_id = '$acad_id'";

                            $result = $conn->query($query);
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                echo $row['percentage_completed'] . "%";
                            } else {
                                echo "0%";
                            }

                            $conn->close();
                            ?>;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

   
  


    <!-- Edit Modal HTML -->
    <div id="addEmployeeModal" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form method="post" action="../php/filter.php">
                    <div class="modal-header">                      
                        <h5 class="modal-title">School year filter</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">                    
                        <div class="form-group">

                            <label for="academic_year" class="form-label">Academic Year</label>                            
                            <select id="academic_year" name="academic_year" class="form-control">
                                <?php 
                                    include "../php/config.php";
                                    $query = mysqli_query($conn,"SELECT acad_year FROM academic_year");
                                    if($query){
                                        if(mysqli_num_rows($query)>0){
                                            while($row = mysqli_fetch_assoc($query)){
                                                $acad_year = $row['acad_year'];
                                ?>

                                    <option><?php echo $acad_year;?></option>

                                <?php

                                            }
                                        }
                                    }else{

                                    }
                                ?>
                                
                            </select>


                        </div>
                        <div class="form-group">

                            <label for="semester" class="form-label">Semester</label>
                            <select id="semester_list" name="semester" class="form-control">
                                <?php 
                                    include "../php/config.php";
                                    $query = mysqli_query($conn,"SELECT sem_description FROM semesters");
                                    if($query){
                                        if(mysqli_num_rows($query)>0){
                                            while($row = mysqli_fetch_assoc($query)){
                                                $sem_description = $row['sem_description'];
                                ?>

                                    <option><?php echo $sem_description;?></option>

                                <?php

                                            }
                                        }
                                    }else{

                                    }
                                ?>
                                
                            </select>


                        </div>
                        
                      
                         
                                  
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <button  class="btn btn-primary btn-sm" type="submit" name="submit">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</body>

</html>
<?php 

}}else{
    header("Location: ../index.php");
}?>