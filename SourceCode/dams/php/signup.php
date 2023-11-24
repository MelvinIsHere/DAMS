<?php
session_start();
include "config.php";    
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);
$type = mysqli_real_escape_string($conn, $_POST['type']);
// $password = mysqli_real_escape_string($conn, $_POST['password']);

$facultyname = mysqli_real_escape_string($conn,$_POST['faculty']);
$type_of_creator = mysqli_real_escape_string($conn,$_POST['type_of_creator']);

$faculty_id = getfacultyid($facultyname);
$conflict = false;


if(!$faculty_id){
    $conflict =  true;
}
if($conflict){
    $message = 'Something went wrong!';
    $_SESSION['alert'] = $error; 
    $_SESSION['message'] =  $message;   //failed to insert
    header("Location: ../admin/account_management.php");
}

$error = "error";
$success = "success";
$exist = "exist";

    if(!empty($email) && !empty($password) && !empty($type)){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
            if(mysqli_num_rows($sql) > 0){                        
                $message = 'Account already exist!';
                $_SESSION['alert'] = $error; 
                $_SESSION['message'] =  $message;   //failed to insert
                header("Location: ../admin/account_management.php");
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
                                // $encrypt_pass = md5($password);
                                $insert_query = mysqli_query($conn, "INSERT INTO users (unique_id, email, password, img, status,type,faculty_id)VALUES ({$ran_id},'{$email}','{$password}', '{$new_img_name}', '{$status}','{$type}','{$faculty_id}')");
                                if($insert_query){
                                    $select_sql2 = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
                                    if(mysqli_num_rows($select_sql2) > 0){
                                        $result = mysqli_fetch_assoc($select_sql2);
                                            $message = 'Account successfully created!';
                                            $_SESSION['alert'] = $success; 
                                            $_SESSION['message'] =  $message;
                                        if($type_of_creator == 'Admin'){
                                               
                                             header("Location: ../admin/account_management.php");
                                        }elseif($type_of_creator == 'Dean'){   
                                            header("Location: ../deans/account_management.php");
                                        }elseif($type_of_creator == 'Head'){   
                                            header("Location: ../heads/account_management.php");
                                        }                                                                            
                                       
                                    }else{
                                        $message =  "This email address not Exist!";
                                        $_SESSION['alert'] = $error;                                             
                                        $_SESSION['message'] =  $message;   //failed to insert
                                        if($type_of_creator == 'Admin'){
                                            //failed to insert
                                             header("Location: ../admin/account_management.php");
                                        }elseif($type_of_creator == 'Dean'){   
                                            header("Location: ../deans/account_management.php");
                                        }elseif($type_of_creator == 'Head'){   
                                            header("Location: ../heads/account_management.php");
                                        }                                                 
                                    }
                                }else{
                                    $message =  "Something went wrong. Please try againaa!";
                                    $_SESSION['alert'] = $error; 
                                    $_SESSION['message'] =  $message;   //failed to insert
                                    if($type_of_creator == 'Admin'){
                                        //failed to insert
                                        header("Location: ../admin/account_management.php");
                                    }elseif($type_of_creator == 'Dean'){   
                                        header("Location: ../deans/account_management.php");
                                    }elseif($type_of_creator == 'Head'){   
                                        header("Location: ../heads/account_management.php");
                                    }                                     
                                }
                            }
                        }else{
                            $message = "Please upload an image file - jpeg, png, jpg";
                            $_SESSION['alert'] = $error; 
                            $_SESSION['message'] =  $message;   //failed to insert
                             if($type_of_creator == 'Admin'){
                                //failed to insert
                                header("Location: ../admin/account_management.php");
                            }elseif($type_of_creator == 'Dean'){   
                                header("Location: ../deans/account_management.php");
                            }elseif($type_of_creator == 'Head'){   
                                header("Location: ../heads/account_management.php");
                            }     
                        }
                    }else{
                        $message =  "Please upload an image file - jpeg, png, jpg";
                        $_SESSION['alert'] = $error; 
                        $_SESSION['message'] =  $message;   //failed to insert
                         if($type_of_creator == 'Admin'){
                            //failed to insert
                            header("Location: ../admin/account_management.php");
                        }elseif($type_of_creator == 'Dean'){   
                            header("Location: ../deans/account_management.php");
                        }elseif($type_of_creator == 'Head'){   
                            header("Location: ../heads/account_management.php");
                        }     
                    }
                }else{
                    $message =  "Please upload an image file - jpeg, png, jpg";
                    $_SESSION['alert'] = $error; 
                    $_SESSION['message'] =  $message;   //failed to insert
                     if($type_of_creator == 'Admin'){
                        //failed to insert
                        header("Location: ../admin/account_management.php");
                    }elseif($type_of_creator == 'Dean'){   
                        header("Location: ../deans/account_management.php");
                    }elseif($type_of_creator == 'Head'){   
                        header("Location: ../heads/account_management.php");
                    }     
                }
            }
        }else{
            $message =  "$email is not a valid email!";
            $_SESSION['alert'] = $error; 
            $_SESSION['message'] =  $message;   //failed to insert
             if($type_of_creator == 'Admin'){
                //failed to insert
                header("Location: ../admin/account_management.php");
            }elseif($type_of_creator == 'Dean'){   
                header("Location: ../deans/account_management.php");
            }elseif($type_of_creator == 'Head'){   
                header("Location: ../heads/account_management.php");
            }     
        }
    }else{
        $message =  "All input fields are required!";
        $_SESSION['alert'] = $error; 
        $_SESSION['message'] =  $message;   //failed to insert
         if($type_of_creator == 'Admin'){
            //failed to insert
            header("Location: ../admin/account_management.php");
        }elseif($type_of_creator == 'Dean'){   
            header("Location: ../deans/account_management.php");
        }elseif($type_of_creator == 'Head'){   
            header("Location: ../heads/account_management.php");
        }     
    } 
    
    


function getfacultyid($faculty_name){
    include 'config.php';
    $query = mysqli_query($conn,"SELECT faculty_id FROM faculties WHERE CONCAT(firstname,' ',middlename,' ',lastname,' ',suffix) = '$faculty_name'");
    if($query){
        if(mysqli_num_rows($query) > 0){
            $row = mysqli_fetch_assoc($query);
            $id = $row['faculty_id'];
            return $id;
        }else{
            return false;
        }
    }else{
        return false;        
    }
 }
// function getDepid($department_name){
//     include 'config.php';
//     $query = mysqli_query($conn,"SELECT department_id FROM departments WHERE department_name = '$department_name'");
//     if($query){
//         if(mysqli_num_rows($query)>0){
//             $row = mysqli_fetch_assoc($query);
//             $department_id = $row['department_id'];
//             return $department_id;
//         }else{
//             return false;
//         }
//     }else{
//         return false;
//     }
// }
?>
