<?php

session_start();
include "../config.php";


$title = $_POST['titles'];

if(empty($title)){
	echo "Title not Inserted";	
}
else{
	$sql = mysqli_query($conn,"INSERT INTO titles(title_description) VALUES('$title')");
	if($sql){
	echo "Title Inserted";	
}
else{
	echo "error";		
}


}




 ?>