<?php 
    session_start();
    include_once "config.php";
    $email =  $_POST['username'];
    $password = $_POST['password'];
    
    if(!empty($email) && !empty($password)){
          $sql = mysqli_query($conn,"SELECT
            *
            
            FROM users 
            WHERE email = '{$email}'");

        
        if(mysqli_num_rows($sql) > 0){
            $row = mysqli_fetch_assoc($sql);
            // $user_pass = md5($password);
            $enc_pass = $row['password'];
            
            $type = $row['type'];
            if($enc_pass === $password){

                $sem_id = getSemId();
                $acad_id = getAcadId();
                $term_id = getactiveTerm();

                if(!$acad_id && !$sem_id){
                     header("Location: ../index.php?error=Something went wrong please try again!");
                }
                $status = "Active now";
                $sql2 = mysqli_query($conn, "UPDATE users SET status = '{$status}' WHERE unique_id = {$row['unique_id']}");
                if($sql2){

                    if($type === "Admin"){
                        header("Location: ../admin/admin.php");
                          $_SESSION['unique_id'] = $row['unique_id'];
                    $_SESSION['user_id'] = $row['user_id'];
                   
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['type'] = $row['type'];
                      $_SESSION['semester_id'] = $sem_id;
                    $_SESSION['acad_id'] = $acad_id;
                    $_SESSION['term_id'] = $term_id;
                    
                    }
                    else if($type === "Head"){
                         $_SESSION['unique_id'] = $row['unique_id'];
                    $_SESSION['user_id'] = $row['user_id'];
                   
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['type'] = $row['type'];
                    $_SESSION['dept_name'] = $row['department_name'];
                      $_SESSION['semester_id'] = $sem_id;
                    $_SESSION['acad_id'] = $acad_id;
                    $_SESSION['term_id'] = $term_id;
                        header("Location: ../heads/heads.php");
                    }
                     else if($type === "Dean"){
                    $_SESSION['unique_id'] = $row['unique_id'];
                    $_SESSION['user_id'] = $row['user_id'];
                   
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['type'] = $row['type'];
                    $_SESSION['dept_name'] = $row['department_name'];
                      $_SESSION['semester_id'] = $sem_id;
                    $_SESSION['acad_id'] = $acad_id;
                    $_SESSION['term_id'] = $term_id;
                        header("Location: ../deans/deans.php");
                    }
                    else if($type === "Staff" || $type === "Faculty"){
                        $_SESSION['term_id'] = $term_id;
                    $_SESSION['unique_id'] = $row['unique_id'];
                    $_SESSION['user_id'] = $row['user_id'];
                   
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['type'] = $row['type'];
                    $_SESSION['dept_name'] = $row['department_name'];
                      $_SESSION['semester_id'] = $sem_id;
                    $_SESSION['acad_id'] = $acad_id;
                        header("Location: ../staffs/staffs.php");
                    }
                    
                    
                }else{
                    header("Location: ../index.php?error=Something went wrong. Please try again!");
                }





            }else{
                 header("Location: ../index.php?error=Email or Password is Incorrect!");
            }






        }else{
             header("Location: ../index.php?error=" . "$email" . " - This email not Exist!asd");
        }
    }else{
         header("Location: ../index.php?error=All input fields are required!");
    }

function getSemId(){
    include "config.php";
    $query = mysqli_query($conn,"SELECT semester_id FROM semesters WHERE status = 'ACTIVE'");
    if($query){
        if(mysqli_num_rows($query) > 0){
            $row = mysqli_fetch_assoc($query);
            $semester_id = $row['semester_id'];
            return $semester_id;
        }else{
            return false;
        }
    }else{
        return false;
    }
}
function getAcadId(){
    include "config.php";
    $query = mysqli_query($conn,"SELECT acad_year_id FROM academic_year WHERE status = 'ACTIVE'");
    if($query){
        if(mysqli_num_rows($query) > 0){
            $row = mysqli_fetch_assoc($query);
            $acad_id = $row['acad_year_id'];
            return $acad_id;
        }else{
            return false;
        }
    }else{
        return false;
    }
}
function getactiveTerm(){
    include "config.php";
    $query = mysqli_query($conn,"SELECT term_id FROM terms WHERE status = 'ACTIVE'");
    if($query){
        if(mysqli_num_rows($query)>0){
            $row = mysqli_fetch_assoc($query);
            $term_id = $row['term_id'];

            return $term_id;
        }else{
            return "false";
        }
    }else{
        return "false";
    }   
}

?>