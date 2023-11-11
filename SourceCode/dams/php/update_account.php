<?php
    session_start();
    include_once "config.php";
    include_once "functions.php";
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $error = "error";
    $success = "success";
    
    

    
        if(!empty($email) && !empty($password) && !empty($type)){
                if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                    
                    
                         $encrypt_pass = md5($password);
                         $update_account = mysqli_query($conn, "UPDATE users SET email = '$email',password = '$encrypt_pass',
                                                                type = '$type' WHERE user_id = '$user_id'");
                        if($update_account){
                                $message = "The account has been successfully updated!";
                                

                                            $_SESSION['alert'] = $success; 
                                             $_SESSION['message'] =  $message;   //failed to insert
                                             header("Location: ../admin/account_management.php");                                            
                                        }else{
                                            $message = "Something went wrong updating the account information!";
                                            
                                             
                                            $_SESSION['alert'] = $error; 
                                             $_SESSION['message'] =  $message;   //failed to insert
                                             header("Location: ../admin/account_management.php");
                                            
                                        }
                  
                }else{
                    $message = $email . " is not a valid email!";
                    $_SESSION['alert'] = $error; 
                    $_SESSION['message'] =  $message;   //failed to insert
                    header("Location: ../admin/account_management.php");
                }
            }else{
                $message = "All input fields are required!";
                $_SESSION['alert'] = $error; 
                $_SESSION['message'] =  $message;   //failed to insert
                header("Location: ../admin/account_management.php");
            } 
   
    
?>
