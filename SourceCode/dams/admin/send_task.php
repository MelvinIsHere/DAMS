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
            d.department_abbrv,
            d.department_id
            FROM users u
            LEFT JOIN departments d ON u.department_id = d.department_id
           WHERE user_id = '$id'");
    
    while($row = mysqli_fetch_array($data)){
        $department_name = $row['department_name'];
        $img = $row['img'];
        $type =$row['type'];
        $department_id = $row['department_id'];
        $department_abbrv = $row['department_abbrv'];

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <style type="text/css">
        body{
            background-color: #f5f5f5;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .topbar{
            min-height: 100px;
            background-color: #A52A2A;
            
        }
        .title{
            color: white;
        }
        .form-control{
            height: 50px;
        }

        
        .btn{
            
            width: 50%;
            height: 50px;
            position: relative;
            border-radius: 50px;
        }
        .break{
            margin-top: 50px;
        }
        .submit{
            
            width: 150px;
            height: 50px;
            position: relative;



        }
     .container.footer {
        margin-top: auto;
        }
    


    </style>
</head>
<body>
  <div class="container-fluid topbar">
    
    <h2 class="title mt-4"><center>Generate Task</center></h2>
</div>

<div class="container border-0"> 
    <form>
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="lname" class="form-label">Task name</label>
                    <input class="form-control" list="task_names" name="task_name" id="task_name" placeholder="Enter task name">
                        <datalist id="task_names">
                            <?php 
                                include "../config.php";
                                $sql = "SELECT template_title FROM document_templates";
                                $result = mysqli_query($conn,$sql);
                                while($row = mysqli_fetch_array($result)){
                                    $task_names = $row['template_title'];                                            
                            ?>
                            <option value="<?php echo $task_names?>">
                            <?php }?>
                        </datalist>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="description">Description</label>
                    <input type="text" name="description" class="form-control" id="description">
                </div>                
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Due date</label>
                    <input type="date" name="due_date" class="form-control">
                </div>
                <div class="col-md-6">
                    
                </div>
            </div>
         
            
        </div>
    </form>
        
</div>

</div>






<?php }
}
else{

    header("Location: ../index.php");
}?>
</body>
</html>