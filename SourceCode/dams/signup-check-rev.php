<?php
    session_start();
    include_once "config.php";
    
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $dept_name = mysqli_real_escape_string($conn, $_POST['department']);
    $abbv = mysqli_real_escape_string($conn, $_POST['abbv']);




    if(!empty($email) && !empty($password)){

        if($type == 'Dean'){
           
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
            if(mysqli_num_rows($sql) > 0){
                echo "$email - This email already exist!";
            }else{
                if(isset($_FILES['image'])){
                    $img_name = $_FILES['image']['name'];
                    $img_type = $_FILES['image']['type'];
                    $tmp_name = $_FILES['image']['tmp_name'];
                    
                    $img_explode = explode('.',$img_name);
                    $img_ext = end($img_explode);
    
                    $extensions = ["jpeg", "png", "jpg"];
                    if(in_array($img_ext, $extensions) === true){
                        $types = ["image/jpeg", "image/jpg", "image/png"];
                        if(in_array($img_type, $types) === true){
                            $time = time();
                            $new_img_name = $time.$img_name;
                            if(move_uploaded_file($tmp_name,"images/".$new_img_name)){
                                $ran_id = rand(time(), 100000000);
                                $status = "Active now";
                                $encrypt_pass = md5($password);
                                $insert_query = mysqli_query($conn, "INSERT INTO users (unique_id,email, password, img, status,type)
                                VALUES ({$ran_id}, '{$email}', '{$encrypt_pass}', '{$new_img_name}', '{$status}','{$type}')");
                                if($insert_query){

                                   $users_id = mysqli_query($conn,"SELECT user_id FROM users WHERE email = '$email'");
                                   if(mysqli_num_rows($users_id) > 0){
                                        $users_id_result = mysqli_fetch_assoc($users_id);
                                        $id = $users_id_result['user_id'];
                                        $insert_dept = "INSERT INTO departments(department_name,department_abbrv,user_id) VALUES('$dept_name','$abbv','$id')";
                                            $inserted = mysqli_query($conn,$insert_dept);

                                        if($inserted){
                                             $select_sql2 = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
                                    if(mysqli_num_rows($select_sql2) > 0){
                                        $result = mysqli_fetch_assoc($select_sql2);
                                        $_SESSION['unique_id'] = $result['unique_id'];
                                        echo "success";
                                    }






                                    else{
                                        echo "This email address not Exist!";
                                    }
                                        }
                                   }
                                   else{

                                   }



                                   
                                }else{
                                    echo "Something went wrong. Please try again";
                                    
                                }
                            }
                        }else{
                            echo "Please upload an image file - jpeg, png, jpg";
                        }
                    }else{
                        echo "Please upload an image file - jpeg, png, jpg";
                    }
                }
            }
        }else{
            echo "$email is not a valid email!";
        }
   
}






     if($type == 'Heads'){
           
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
            if(mysqli_num_rows($sql) > 0){
                echo "$email - This email already exist!";
            }else{
                if(isset($_FILES['image'])){
                    $img_name = $_FILES['image']['name'];
                    $img_type = $_FILES['image']['type'];
                    $tmp_name = $_FILES['image']['tmp_name'];
                    
                    $img_explode = explode('.',$img_name);
                    $img_ext = end($img_explode);
    
                    $extensions = ["jpeg", "png", "jpg"];
                    if(in_array($img_ext, $extensions) === true){
                        $types = ["image/jpeg", "image/jpg", "image/png"];
                        if(in_array($img_type, $types) === true){
                            $time = time();
                            $new_img_name = $time.$img_name;
                            if(move_uploaded_file($tmp_name,"images/".$new_img_name)){
                                $ran_id = rand(time(), 100000000);
                                $status = "Active now";
                                $encrypt_pass = md5($password);
                                $insert_query = mysqli_query($conn, "INSERT INTO users (unique_id,email, password, img, status,type)
                                VALUES ({$ran_id}, '{$email}', '{$encrypt_pass}', '{$new_img_name}', '{$status}','{$type}')");
                                if($insert_query){

                                   $users_id = mysqli_query($conn,"SELECT user_id FROM users WHERE email = '$email'");
                                   if(mysqli_num_rows($users_id) > 0){
                                        $users_id_result = mysqli_fetch_assoc($users_id);
                                        $id = $users_id_result['user_id'];
                                        $insert_dept = "INSERT INTO departments(department_name,department_abbrv,user_id) VALUES('$dept_name','$abbv','$id')";
                                            $inserted = mysqli_query($conn,$insert_dept);

                                        if($inserted){
                                             $select_sql2 = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
                                    if(mysqli_num_rows($select_sql2) > 0){
                                        $result = mysqli_fetch_assoc($select_sql2);
                                        $_SESSION['unique_id'] = $result['unique_id'];
                                        echo "success";
                                    }






                                    else{
                                        echo "This email address not Exist!";
                                    }
                                        }
                                   }
                                   else{

                                   }



                                   
                                }else{
                                    echo "Something went wrong. Please try again";
                                    
                                }
                            }
                        }else{
                            echo "Please upload an image file - jpeg, png, jpg";
                        }
                    }else{
                        echo "Please upload an image file - jpeg, png, jpg";
                    }
                }
            }
        }else{
            echo "$email is not a valid email!";
        }
   
}





    else{
        echo "All input fields are required!";
    }
?>