<?php 
    session_start();
    include_once "config.php";
    $email =  $_POST['email'];
    $password = $_POST['password'];
    if(!empty($email) && !empty($password)){
        $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
        if(mysqli_num_rows($sql) > 0){
            $row = mysqli_fetch_assoc($sql);
            $user_pass = md5($password);
            $enc_pass = $row['password'];
            $type = $row['type'];
            if($user_pass === $enc_pass){
                $status = "Active now";
                $sql2 = mysqli_query($conn, "UPDATE users SET status = '$status' WHERE unique_id = {$row['unique_id']}");
                if($sql2){
                    $_SESSION['unique_id'] = $row['unique_id'];
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['fname'] = $row['fname'];
                    $_SESSION['lname'] = $row['lname'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['type'] = $row['type'];
                    if($_SESSION['type'] === "admin"){
                        header("Location: admin.php");
                    }
                    else if($_SESSION['type'] === "staffs"){
                        header("Location: staffs.php");
                    }
                    
                    
                }else{
                    header("Location: /Something went wrong. Please try again!");
                }





            }
   





            else{
                echo "Email or Password is Incorrect!";
            }
        }else{
            echo $email . " - This email not Exist!";
        }
    }else{
        echo "All input fields are required!";
    }
?>