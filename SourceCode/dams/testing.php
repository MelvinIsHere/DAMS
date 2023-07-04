<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>testing</title>
</head>
<body>
       <div class="container-fluid tabcontent" id="pendingDocu">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h1">Pending Task</h1>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3">
        <?php 
             $conn = new mysqli("localhost","root","","dams2");
            if ($conn->connect_error) {
                    die("Connection failed : " . $conn->connect_error);
            }
                $sql = "SELECT
                tt.task_id,
                tt.task_name,
                tt.date_posted,
                tt.due_date,
                ts.is_completed
            FROM tasks tt
            LEFT JOIN task_status ts ON tt.task_id = ts.task_id
            LEFT JOIN departments dp ON ts.office_id = dp.department_id
            WHERE tt.for_deans = 0 AND dp.department_abbrv = 'CICS'";
                $result = $conn->query($sql);
                while($row = mysqli_fetch_array($result)){
                    $task_id = $row['task_id'];
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
                        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                            <input type="text" value="<?php echo $task_id ;?>" name="task_id">
                            <button class="btn btn-primary btn-sm" style="margin-top: 40px; margin-right: 30px;" name = "submit" type ="submit">
                                view
                            </button>
                        <!-- <a href="#" class="btn btn-primary btn-sm" style="margin-top: 40px; margin-right: 30px;" name = "submit" type ="submit">view</a> -->
                    </form>
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
<div class="container-fluid tabcontent" id="pending-Details">
            <h1 class="h3 mb-1 text-gray-800">Pending Documents | Faculty Loading</h1><br>

            <form>
                <div class="row">
                    <div class="col-xs-12 col-sm-7"> -->
                         <?php 
                          $conn = new mysqli("localhost","root","","dams2");
                                if ($conn->connect_error) {
                                    die("Connection failed : " . $conn->connect_error);
                                }

                         if (isset($_POST['submit'])) {
                            
                                $taskid = $_POST['task_id'];
                                
                                // Retrieve task details from the database based on the task ID
                               

                                $sql = "SELECT * FROM tasks WHERE task_id = '$taskid' ";
                                $result = $conn->query($sql);
                                 while($row = mysqli_fetch_array($result)){
                                   
                                    $taskName = $row['task_name'];
                                    $posted = $row['date_posted'];
                                    $deadline = $row['due_date'];
                                    echo $taskName;
                                    }
                    
                    
                                
                               
                            }
                                                                    ?>
                     <h3>task</h3>
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
</body>
</html>