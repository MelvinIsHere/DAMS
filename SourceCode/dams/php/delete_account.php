<?php
    session_start();
    include "config.php";
    include "functions.php";
  
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $error = "error";
    $success = "success";
    
    if(!empty($user_id)){

        $sql = mysqli_query($conn,"DELETE FROM users WHERE user_id = '$user_id'");
        if($sql){
            $message = "The account has been successfully deleted!";  
            $_SESSION['alert'] = $success; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../admin/account_management.php");
        }
        else{
            $message = "There are something wrong deleting the account!";
            header("Location: ../admin/account_management.php?alert=$error&message=$message");

            $_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
            header("Location: ../admin/account_management.php");
        }
       

    }
    else{
        $message = "There are something wrong deleting the account!";
        $_SESSION['alert'] = $error; 
        $_SESSION['message'] =  $message;   //failed to insert
        header("Location: ../admin/account_management.php");
    }
    
?>
