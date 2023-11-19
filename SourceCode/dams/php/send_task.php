<?php 
 session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

 $users_id = $_SESSION['user_id'];
 include "config.php";
// $conn = new mysqli("localhost", "root", "", "dams2");  
 include "functions.php";
$due_date = $_POST['due_date'];
$task_name = $_POST['task_name'];
$description = $_POST['description'];
$category = $_POST['category'];
// $term = $_POST['term'];

$staff = 0;
$deans = 0;
$ovcaa = 0;
$heads = 0;
$faculty = 0;
if($category == "Staff"){
	$staff = 1;
}elseif($category == "Dean"){
	$deans = 1;
}elseif($category == "Admin"){
	$ovcaa = 1;
}elseif ($category == "Heads") {
	$heads = 1;
}elseif($category == "Faculty"){
	$faculty = 1;
}

$term_id = gettermid();

 $doc_temp_id = getTemplateId($task_name);
 if($doc_temp_id == ""){
 	echo "none";
 	header("Location: ../admin/admin.php");
 }else{
 	$id = insert_task($task_name,$description,$doc_temp_id,$due_date,$ovcaa,$deans,$heads,$staff,$faculty,$term_id);//task_id
 	if($id){

 		$sql = "SELECT 					
            		u.user_id,
            		d.department_id
        		FROM users u
        		LEFT JOIN faculties f ON f.faculty_id = u.faculty_id
        		LEFT JOIN departments d ON d.department_id = f.department_id
        		WHERE u.type = '$category'";
		$result = mysqli_query($conn,$sql);
		if ($result) {

			while($row = mysqli_fetch_array($result)){
				$dept_id = $row['department_id'];
        		$user_id = $row['user_id'];
        		$sql = mysqli_query($conn,"INSERT INTO task_status_deans(task_id,office_id,user_id,is_completed)VALUES('$id','$dept_id','$user_id',1)");
    		}
	    	$notif_id = insertTaskNotification($task_name); //insert task notifications to others
	    	if($notif_id == ""){

	    		echo "none1";
	    	}else{

	    		$user_notif = user_notif($users_id,$notif_id,$category); // insert thee status
	    		if($user_notif != ""){

	    				$_SESSION['alert'] = "success"; 
	    				$message = "Task has been successfully ditributed!";  
						$_SESSION['message'] =  $message;   //failed to insert
	    	header("Location: ../admin/admin.php?Success");
	    		}else{
	    				$_SESSION['alert'] = "error"; 
	    				$message = "Task failed!";  
						$_SESSION['message'] =  $message; 
	    		}
	    	
	    
	    	}
	    
		}else{
			echo mysqli_error($conn);
		}

 	}else{
 		echo "error";
 	}
 }
//  $id = adminInsertTask($task_name,$description,$doc_temp_id,$dateStart,$dateEnd,$ovcaa,$deans,$heads,$staffs); //insert task into the deans

function user_notif($users_id,$notif_id,$type){
	include "config.php";
	// Assuming you have already established the database connection using $conn
	$user_ids_query = "SELECT user_id FROM users WHERE type = '$type'";
	$user_ids_result = mysqli_query($conn, $user_ids_query);
	if ($user_ids_result) {
    	$success = true; // Variable to track the success status
    	while ($row = mysqli_fetch_array($user_ids_result)) {
        	$id = $row['user_id'];
        	$sql = mysqli_query($conn, "INSERT INTO user_notifications(status, notif_id, user_id) VALUES(0, '$notif_id', '$id')");
        	if (!$sql) {
            	$success = false; // If any insert fails, set success to false
            	break; // Exit the loop early since there's no need to continue
        	}
    	}

    	if ($success) {
        	return "success";
    	} else {
        	return "";
    	}
	} else {
    	// Handle the case when the query execution fails
    	return "";
	}

}

function insert_task($task_name,$description,$doc_temp_id,$dateEnd,$ovcaa,$deans,$heads,$staffs,$faculty,$term_id){
	include "config.php";
	$acad_id = getActiveAcadyear();
	if($acad_id){
		$sem_id = getactivesem();
		if($sem_id){
			$query = mysqli_query($conn,"INSERT INTO tasks (task_name, task_desc,document_id, due_date, for_ovcaa,for_deans,for_heads,for_staffs,for_faculty,term_id) VALUES ('$task_name','$description','$doc_temp_id', '$dateEnd','$ovcaa', '$deans','$heads','$staffs','$faculty','$term_id')");
			 if($query){
	 		// Print auto-generated id
         		$id = $conn -> insert_id;
		
				return $id;
	 		}else{
	 		
	 			return false;
	 		}
		}else{
			
			return  false;
		}

	}else{
		 
		return false;
	}
	
              
                
}

function getactivesem(){
	include "config.php";
	$query = mysqli_query($conn,"SELECT semester_id FROM semesters WHERE status = 'ACTIVE'");
	if($query){
		if(mysqli_num_rows($query) > 0){
			$row = mysqli_fetch_assoc($query);
			$semester_id = $row['semester_id'];
			return $semester_id;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

function getActiveAcadyear(){
	include "config.php";
	$query = mysqli_query($conn,"SELECT acad_year_id FROM academic_year WHERE status = 'ACTIVE'");
	if($query){
		if(mysqli_num_rows($query) > 0){
			$row = mysqli_fetch_assoc($query);
			$acad_year_id = $row['acad_year_id'];
			return $acad_year_id;
		}else{
			return alse;
		}
	}else{
	return false;
	}
}
function gettermid(){
	include "config.php";
	$query = mysqli_query($conn,"SELECT term_id FROM terms WHERE status = 'ACTIVE'");
	if($query){
		if(mysqli_num_rows($query) >0){
			$row = mysqli_fetch_assoc($query);
			$term_id = $row['term_id'];

			return $term_id;
		}else{
			return false;
		}
	}else{
		return false;
	}
}
?>