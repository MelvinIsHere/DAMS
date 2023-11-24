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
                                f.faculty_id,
                                f.`firstname`,
                                f.`middlename`,
                                f.`lastname`,
                                f.`suffix`,
                                f.no_deloading,
                                f.no_of_research,
                                GROUP_CONCAT(d.`designation`) AS designations,
                                f.`position`,
                                dp.`department_abbrv`,
                                dp.`department_name`,
                                dp.`department_id`
                                

                            FROM users u 
                            LEFT JOIN faculties f ON f.`faculty_id` = u.faculty_id
                            LEFT JOIN departments dp ON dp.`department_id` = f.`department_id`
                            LEFT JOIN faculty_designation fd ON fd.faculty_id = f.`faculty_id`
                            LEFT JOIN designation d ON d.designation_id = fd.designation_id
                                                  
                            
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
        $deloading = $row['no_deloading'];
        $research = $row['no_of_research'];
        $designations = $row['designations'];
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];
        $middlename = $row['middlename'];
        $suffix = $row['suffix'];
        $faculty_id = $row['faculty_id'];
        




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


                <div class="container-fluid tabcontent" id="createfill-Up">
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <h1 class="h3 mb-2 text-gray-800">User Profile</h1>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-5">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
                                </div>
                                <div class="card-body text-center">
                                    <div class="row d-flex justify-content-center">
                                            <img src="<?php echo '../php/images/' . $img ?>"
                                        class="img-fluid rounded-circle mb-4" alt="User Image"
                                        style="max-height: 300px; max-width: 250px;">        
                                    </div>
                                    <div class="row">
                                        <p class="lead "><strong>Email:</strong> <?php echo $email; ?></p>
                                      
                                    </div>
                                     <div class="row">
                                        
                                        <p class="lead"><strong>User Type:</strong> <?php echo $type; ?></p><br>
                                        
                                    </div>
                                     <div class="row">
                                   
                                        <p class="lead"><strong>Designations:</strong> <?php echo $designations; ?></p><br> 
                                    </div>
                                     <div class="row">
                                        <?php 
                                            $query = mysqli_query($conn,"SELECT 
                                                                        GROUP_CONCAT(t.`title_description`) AS positions
                                                                    FROM users u 
                                                                    LEFT JOIN faculties f ON f.`faculty_id` = u.`faculty_id`
                                                                    LEFT JOIN faculty_titles ft ON ft.`faculty_id` = f.`faculty_id`
                                                                    LEFT JOIN titles t ON t.`title_id` = ft.`title_id`
                                                                    WHERE u.`user_id` = '$id'");
                                            if($query){
                                                if(mysqli_num_rows($query) > 0){
                                                    $row = mysqli_fetch_assoc($query);
                                                    $positions = $row['positions'];

                                                    ?>
                                                    <p class="lead"><strong>Position:</strong> <?php echo $positions; ?></p><br> 


                                            <?php 
                                                }else{
                                                    ?>

                                                    <p class="lead"><strong>Position:</strong>None</p><br> 
                                                    <?php 
                                                }

                                            }else{
                                                ?>
                                                <p class="lead"><strong>Position:</strong>None</p><br> 
                                                <?php
                                            }

                                        ?>
                                        
                                    </div>
                                
                                   
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
                                </div>
                                <div class="card-body d-flex justify-content-start">
                                    <form style="width:100%" action="../php/update_profile.php" method="POST">
                                        <input type="text" name="faculty_id" hidden value="<?php echo $faculty_id;?>"  >
                                        <input type="text" name="type" hidden value="<?php echo $type;?>"  >
                                        <div class="form-group">
                                             <div class="row">
                                                <div class="col-md-9">
                                                        <label class="form-label"><h5>First name</h5></label><br>
                                                    <input type="text" name="firstname" class="form-control"value="<?php echo $firstname;?>" style="height: 50px;"> 
                                                </div>
                                                  <div class="col-md-3">
                                                        <label class="form-label"><h5>Middle Initial</h5></label><br>
                                                    <input type="text" name="middlename" class="form-control"value="<?php echo $middlename;?>" style="height: 50px;"> 
                                                </div>
                           
                                             </div>
                                        </div>
                                         <div class="form-group">
                                             <div class="row">
                                                <div class="col-md-9">
                                                        <label class="form-label"><h5>Last name</h5></label><br>
                                                    <input type="text" name="lastname" class="form-control"value="<?php echo $lastname;?>" style="height: 50px;"> 
                                                </div>
                                                  <div class="col-md-3">
                                                        <label class="form-label"><h5>Suffix</h5></label><br>
                                                    <input type="text" name="suffix" class="form-control"value="<?php echo $suffix;?>" style="height: 50px;"> 
                                                </div>
                           
                                             </div>
                                        </div>
                                       
                                       
                                    
                                  
                                   
                                     
                                
                                   
                                </div>
                                <div class="card-footer">
                                            <button class="btn float-right btn-success" name="submit" type="submit">update</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        
                            
                                <div class="col-md-6">
                                    <div class="card shadow">
                                        <div class="card-header py-3">
                                            <h6 class="m-0 font-weight-bold text-primary">Department Information</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="lead ml-3" style="word-wrap: break-word;"><strong>Department Name:</strong> <?php echo $department_name; ?>
                                            </p>
                                            <p class="lead ml-3"><strong>Department Abbreviation:</strong>
                                                <?php echo $department_abbrv; ?></p>
                                        </div>
                                       
                                    </div>
                                </div>
                            
                            <!-- Add more rows or customize the layout based on your needs -->
                            
                                <div class="col-md-6">
                                    <div class="card shadow">
                                        <div class="card-header py-3">
                                            <h6 class="m-0 font-weight-bold text-primary">Load information</h6>
                                        </div>
                                        <div class="card-body">
                                            <form action="../php/update_profile_loading_info.php" method="POST">
                                            <div class="form-group">
                                                <input type="text" hidden name="faculty_id" value="<?php echo $faculty_id;?>">
                                                <input type="text" hidden name="type" value="<?php echo $type;?>"  >
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label class="form-label" for="deloading"><h4>No. of deloading</h4></label>    
                                                    </div>
                                                    <div class="col-md-9">
                                                        <input class="form-control" id="deloading" type="number" name="deloading" value="<?php echo $deloading;?>">
                                                    </div>
                                                    
                                                    
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label class="form-label" for="deloading"><h4>No. of research :</h4></label>    
                                                    </div>
                                                    <div class="col-md-9">
                                                        <input class="form-control" id="deloading" type="number" name="research" value="<?php echo $research;?>">
                                                    </div>
                                                    
                                                    
                                                </div>
                                            </div>
                                            
                                            
                                        </div>
                                         <div class="card-footer">
                                            <button  class="btn float-right btn-success">update</button>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                            
                        
                    </div>



  

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php 
if(isset($_SESSION['alert'])){
    $value = $_SESSION['alert'];
    if($value == "success"){
        $message = $_SESSION['message'];
        echo "
        <script type='text/javascript'>
            swal({
                title: 'Success!',
                text: '$message',
                icon: 'success'
            });
        </script>";
    } elseif($value == "error"){
        $message = $_SESSION['message'];
        echo "
        <script type='text/javascript'>
            swal({
                title: 'Error!',
                text: '$message',
                icon: 'error'
            });
        </script>";
    }
    // Clear the session alert and message after displaying
    unset($_SESSION['alert']);
    unset($_SESSION['message']);
}
?>                
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
 
    <script src="js/sb-admin-2.min.js"></script>



<?php }
}?>
</body>
</html>