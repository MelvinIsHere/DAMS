<?php
    session_start();
    include_once "db_con.php";
    include_once "functions.php";
  
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    
    if(!empty($user_id)){

        $sql = mysqli_query($conn,"DELETE FROM users WHERE user_id = '$user_id'");
        if($sql){
            echo "The account has been successfully deleted!";
        }
        else{
            echo "There are something wrong deleting the account!";
        }
       

    }
    else{
        echo "Something Went wrong!";
    }
    
?>
