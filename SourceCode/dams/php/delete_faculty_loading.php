<?php

include "config.php";

$loading_id = $_POST['loading_id'];



$sql = mysqli_query($conn,"DELETE FROM faculty_loadings WHERE fac_load_id = '$loading_id'");
if($sql){

	echo "Faculty Loading data has been deleted!";
}
else{
	echo "Faculty Loading data was not deleted!";
}

 ?>