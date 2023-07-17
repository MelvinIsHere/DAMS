<?php
    session_start();
    include "db_con.php";
    include "functions.php";
  
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    
    if(!empty($user_id)){

        $sql = mysqli_query($conn,"DELETE FROM users WHERE user_id = '$user_id'");
        if($sql){
            header("Location: ../admin/account_management.php?Message : The account has been successfully deleted!");
        }
        else{
            header("Location: ../admin/account_management.php?Message : There are something wrong deleting the account!");
        }
       

    }
    else{
        header("Location: ../admin/account_management.php?Message : Something Went wrong!");
    }
    
?>
