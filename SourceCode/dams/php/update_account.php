<?php
    session_start();
    include_once "db_con.php";
    include_once "functions.php";
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    
    

    
        if(!empty($email) && !empty($password) && !empty($type)){
                if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                    
                    
                         $encrypt_pass = md5($password);
                         $update_account = mysqli_query($conn, "UPDATE users SET email = '$email',password = '$encrypt_pass',
                                                                type = '$type' WHERE user_id = '$user_id'");
                        if($update_account){
                                header("Location: ../admin/account_management.php?Message : The account has been updated!");                                            
                                        }else{
                                            header("Location: ../admin/account_management.php?Message : Something went wrong. Please try again!");
                                            
                                        }
                  
                }else{
                    header("Location: ../admin/account_management.php?Message : " . $email . " is not a valid email!");
                }
            }else{
                header("Location: ../admin/account_management.php?Message : All input fields are required!");
            } 
   
    
?>
