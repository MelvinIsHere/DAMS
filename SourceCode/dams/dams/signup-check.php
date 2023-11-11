<?php 
session_start(); 
include "config.php";

if (isset($_POST['uname']) && isset($_POST['password'])
    && isset($_POST['Re_password'])) {

	function validate($data){
       $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}

	$uname = validate($_POST['uname']);
	$pass = validate($_POST['password']);

	$re_pass = validate($_POST['Re_password']);
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	// $email = $_POST['email'];

	$user_data = 'uname='. $uname. '&name='. $name;


	if (empty($uname)) {
		header("Location: login.php?error=User Name is required&$user_data");
	    exit();
	}else if(empty($pass)){
        header("Location: login.php?error=Password is required&$user_data");
	    exit();
	}
	else if(empty($re_pass)){
        header("Location: login.php?error=Re Password is required&$user_data");
	    exit();
	}

	else if(empty($first_name)){
        header("Location: login.php?error=First Name is required&$user_data");
	    exit();
	}
	else if(empty($last_name)){
        header("Location: login.php?error=Last Name is required&$user_data");
	    exit();
	}
	// 	else if(empty($email)){
 //        header("Location: login.php?error=Email is required&$user_data");
	//     exit();
	// }

	else if($pass !== $re_pass){
        header("Location: login.php?error=The confirmation password  does not match&$user_data");
	    exit();
	}

	else{

		
        $pass = md5($pass);

	    $sql = "SELECT * FROM accounts WHERE username='$uname' ";
		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) {
			header("Location: login.php?error=The username is taken try another&$user_data");
	        exit();
		}else {
           $sql2 = "INSERT INTO accounts(first_name,last_name,username, password) VALUES('$first_name','$last_name','$uname', '$pass')";
           $result2 = mysqli_query($conn, $sql2);
           if ($result2) {
           	 header("Location: login.php?success=Your account has been created successfully");
	         exit();
           }else {
	           	header("Location: login.php?error=unknown error occurred&$user_data");
		        exit();
           }
		}
	}
	
}else{
	header("Location: sig.php");
	exit();
}