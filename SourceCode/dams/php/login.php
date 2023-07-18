<?php 
    session_start();
    include_once "db_con.php";
    $email =  $_POST['username'];
    $password = $_POST['password'];
    
    if(!empty($email) && !empty($password)){
          $sql = mysqli_query($conn,"SELECT
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
            LEFT JOIN departments d ON u.department_id = d.department_id WHERE email = '{$email}' ");

        
        if(mysqli_num_rows($sql) > 0){
            $row = mysqli_fetch_assoc($sql);
            $user_pass = md5($password);
            $enc_pass = $row['password'];
            $dept_name = $row['department_name'];
            $type = $row['type'];
            if($user_pass === $enc_pass){
                $status = "Active now";
                $sql2 = mysqli_query($conn, "UPDATE users SET status = '{$status}' WHERE unique_id = {$row['unique_id']}");
                if($sql2){

                    if($type === "Admin"){
                        header("Location: ../admin/admin.php");
                          $_SESSION['unique_id'] = $row['unique_id'];
                    $_SESSION['user_id'] = $row['user_id'];
                   
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['type'] = $row['type'];
                    $_SESSION['dept_name'] = $row['department_name'];
                    }
                    else if($type === "Heads"){
                         $_SESSION['unique_id'] = $row['unique_id'];
                    $_SESSION['user_id'] = $row['user_id'];
                   
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['type'] = $row['type'];
                    $_SESSION['dept_name'] = $row['department_name'];
                        header("Location: ../heads/heads.php");
                    }
                     else if($type === "Dean"){
                    $_SESSION['unique_id'] = $row['unique_id'];
                    $_SESSION['user_id'] = $row['user_id'];
                   
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['type'] = $row['type'];
                    $_SESSION['dept_name'] = $row['department_name'];
                        header("Location: ../deans/deans.php");
                    }
                    
                    
                }else{
                    header("Location: ../index.php?error=Something went wrong. Please try again!");
                }





            }
   





            else{
                 header("Location: ../index.php?error=Email or Password is Incorrect!");
            }
        }else{
             header("Location: ../index.php?error=" . "$email" . " - This email not Exist!");
        }
    }else{
         header("Location: ../index.php?error=All input fields are required!");
    }
?>