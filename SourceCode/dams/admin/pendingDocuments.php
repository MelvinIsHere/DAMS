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
     <div class="container-fluid tabcontent" id="pendingDocu">
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
                $sql = " SELECT
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
                    WHERE dp.`department_name` = '$department_name' AND is_completed = 0
                    AND tt.for_ovcaa = 1";
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
                        <a href="pending.php?id=<?php echo $taskid; ?>" class="btn btn-primary btn-sm" style="margin-top: 40px; margin-right: 30px;">View</a>
                    </div>
                </div>

            </div>
        
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