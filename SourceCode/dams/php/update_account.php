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
                                echo "The account has been updated!";                                            
                                        }else{
                                            // echo "Something went wrong. Please try again!";
                                            echo mysqli_error($conn);
                                        }
                  
                }else{
                    echo "$email is not a valid email!";
                }
            }else{
                echo "All input fields are required!";
            } 
   
    
?>
