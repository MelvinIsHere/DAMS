<?php
session_start(); 
include "db_conn.php";

if (isset($_POST['uname']) && isset($_POST['password'])) {

	function validate($data){
       $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}

	$uname = validate($_POST['uname']);
	$pass = validate($_POST['password']);

	if (empty($uname)) {
		header("Location: login.php?error=User Name is required");
	    exit();
	    }else if(empty($pass)){
        header("Location: login.php?error=Password is required");
	    exit();
	}else{
		// hashing the password
        $pass = md5($pass);

        
		$sql = "SELECT * FROM accounts WHERE username='$uname' AND password='$pass'";

		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) === 1) {
			$row = mysqli_fetch_assoc($result);
			
            if ($row['username'] === $uname && $row['password'] === $pass && $row['account_type'] === "customer") {
            	$_SESSION['username'] = $row['username'];
            	$_SESSION['first_name'] = $row['first_name'];
            	$_SESSION['last_name'] = $row['last_name'];
            	$_SESSION['id'] = $row['id'];
            	
            	
            
            	header("Location: customer-final.php");
		        exit();
		      }
            	
		      // else if ($row['username'] === $uname && $row['password'] === $pass && $row['account_type'] ===  "artist") {
        //     	$_SESSION['username'] = $row['username'];
        //     	$_SESSION['first_name'] = $row['first_name'];
        //     	$_SESSION['last_name'] = $row['last_name'];
        //     	$_SESSION['id'] = $row['id'];
            	
        //     	$_SESSION['email'] = $row['email'];
            	


        //     	header("Location: account.php");
		      //   exit();
        //     	}

            else if ($row['username'] === "admin" && $row['password'] === $pass && $row['account_type'] ===  "admin") {
            	$_SESSION['username'] = $row['username'];
            	$_SESSION['name'] = $row['name'];
            	$_SESSION['id'] = $row['id'];
            	


            	header("Location: admin.php");
		        exit();
            	}
            	
            
             
            else{
				header("Location: login.php?error=Incorect User name or password");
		        exit();
			}
		}
		}
	}
	
else{
	header("Location: login.php");
	exit();
}
?>