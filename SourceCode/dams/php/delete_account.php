<?php
    session_start();
    include "config.php";
    include "functions.php";
  
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $error = "error";
    $success = "success";
    $type = $_POST['type'];
    if(!empty($user_id)){

        $sql = mysqli_query($conn,"DELETE FROM users WHERE user_id = '$user_id'");
        if($sql){
            $message = "The account has been successfully deleted!";  
            $_SESSION['alert'] = $success; 
            $_SESSION['message'] =  $message;   //failed to insert
            if($type == 'Admin'){
                header("Location: ../admin/account_management.php");
            }elseif($type == 'Dean'){   
                header("Location: ../deans/account_management.php");
            }elseif($type == 'Head'){   
                header("Location: ../heads/account_management.php");
            } 
            
        }
        else{
            $message = "There are something wrong deleting the account!";
            

            $_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            if($type == 'Admin'){                                               
                header("Location: ../admin/account_management.php");
            }elseif($type == 'Dean'){   
                header("Location: ../deans/account_management.php");
            }elseif($type == 'Head'){   
                header("Location: ../heads/account_management.php");
            } 
        }
       

    }
    else{
        $message = "There are something wrong deleting the account!";
        $_SESSION['alert'] = $error; 
        $_SESSION['message'] =  $message;   //failed to insert
         if($type == 'Admin'){
            header("Location: ../admin/account_management.php");
        }elseif($type == 'Dean'){   
            header("Location: ../deans/account_management.php");
        }elseif($type == 'Head'){   
            header("Location: ../heads/account_management.php");
        } 
    }
    
?>
