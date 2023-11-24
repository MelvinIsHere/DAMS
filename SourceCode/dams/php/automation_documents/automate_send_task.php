<?php 
include "../config.php";
$faculty_loading_due_date = $_POST['faculty_loading_due_date'];
$class_schedule_due_date = $_POST['class_schedule_due_date'];
$faculty_schedule_due_date = $_POST['faculty_schedule_due_date'];
$room_utilization_matrix_due_date = $_POST['room_utilization_matrix_due_date'];
$opcr_target_due_date = $_POST['opcr_target_due_date'];
$opcr_accomplishment_due_date = $_POST['opcr_accomplishment_due_date'];
$ipcr_target_due_date = $_POST['ipcr_target_due_date'];
$ipcr_accomplishment_due_date = $_POST['ipcr_accomplishment_due_date'];



//for faculty_schedule

$term_id =  get_active_term_id(); //term id that will be used for term_id in the task table
$active_term =  get_active_term_for_task_description(); //this will be used as a description for the task
$document_id = get_document_id_in_document_templates_table("Faculty Loading");//document id of faculty loading in the document templates
$task_description = "Faculty Loading $active_term";

$query = mysqli_query($conn,"INSERT INTO tasks (task_name,task_desc,document_id,due_date,for_deans,term_id)VALUES('Faculty Loading','$task_description','$document_id','$faculty_loading_due_date',1,'$term_id')");

//this is the id of the latest inserted id in the tasks table which is the task id of the faculty  loading
$tasK_id_of_faculty_loading = $conn->insert_id;


if($query){

	//here is the function for creating a notifcation content and getting its id
	$notification_id = notification_content("Faculty Loading");


	//here i will query all the deans user info  or all the user info of all the users where the account type is dean
	$dean_query = mysqli_query($conn,"SELECT 
											u.`user_id`,
											dp.`department_id`

										FROM users u
										LEFT JOIN faculties f ON f.`faculty_id` = u.`faculty_id`
										LEFT JOIN departments dp ON dp.`department_id` = f.`department_id`
										WHERE TYPE = 'Dean'");//here is the query
	if($dean_query){ //check if the query run -- this condition means it runs
		while($row = mysqli_fetch_assoc($dean_query)){//here i will loop to every id and fetch it, while there is id it will not stop
			
			$user_id = $row['user_id']; //asign the variable
			$department_id = $row['department_id'];


			//now that i have my data to insert task status deans i will  now insert it
			$insert_task = insert_task_status_deans($user_id,$department_id,$tasK_id_of_faculty_loading);
			if($insert_task){ // if the insert task became true then send notification to the user
				$notifcation_to_users = user_notif($user_id,$notification_id); // send notifcation to the user

			}else{
				//else do nothing
			}


			

		}
	}else{//this means the query has some errors
		//do nothing and just do nothing
	}
}else{
	echo mysqli_error($conn);
}




//now i will do the same to the class schedule
//because i already got the term id and active term i wont repeat it again --> please check at line 23 and 24
$document_id = get_document_id_in_document_templates_table("Class Schedule");//document id of Class Schedule in the document templates
$task_description = "Class Schedule $active_term";

$query = mysqli_query($conn,"INSERT INTO tasks (task_name,task_desc,document_id,due_date,for_deans,term_id)VALUES('Class Schedule','$task_description','$document_id','$class_schedule_due_date',1,'$term_id')");

//this is the id of the latest inserted id in the tasks table which is the task id of the class_schedule
$tasK_id_of_class_schedule = $conn->insert_id;


if($query){ // if the query works then this will be executed

	//here is the function for creating a notifcation content and getting its id
	$notification_id = notification_content("Class Schedule");


	//here i will query all the deans user info  or all the user info of all the users where the account type is dean
	$dean_query = mysqli_query($conn,"SELECT 
											u.`user_id`,
											dp.`department_id`

										FROM users u
										LEFT JOIN faculties f ON f.`faculty_id` = u.`faculty_id`
										LEFT JOIN departments dp ON dp.`department_id` = f.`department_id`
										WHERE TYPE = 'Dean'");//here is the query
	if($dean_query){ //check if the query run -- this condition means it runs
		while($row = mysqli_fetch_assoc($dean_query)){//here i will loop to every id and fetch it, while there is id it will not stop
			
			$user_id = $row['user_id']; //asign the variable
			$department_id = $row['department_id'];


			//now that i have my data to insert task status deans i will  now insert it
			$insert_task = insert_task_status_deans($user_id,$department_id,$tasK_id_of_class_schedule);
			if($insert_task){ // if the insert task became true then send notification to the user
				$notifcation_to_users = user_notif($user_id,$notification_id); // send notifcation to the user

			}else{
				//else do nothing
			}


			

		}
	}else{//this means the query has some errors
		//do nothing and just do nothing
	}
}else{
	echo mysqli_error($conn);
}









//now i will do the same to the faculty schedule
//because i already got the term id and active term i wont repeat it again --> please check at line 23 and 24
$document_id = get_document_id_in_document_templates_table("Class Schedule");//document id of faculty Schedule in the document templates
$task_description = "Faculty Schedule $active_term";

$query = mysqli_query($conn,"INSERT INTO tasks (task_name,task_desc,document_id,due_date,for_deans,term_id)VALUES('Faculty Schedule','$task_description','$document_id','$faculty_schedule_due_date',1,'$term_id')");

//this is the id of the latest inserted id in the tasks table which is the task id of the faculty schedule
$tasK_id_of_faculty_schedule = $conn->insert_id;


if($query){ // if the query works then this will be executed

	//here is the function for creating a notifcation content and getting its id
	$notification_id = notification_content("Faculty Schedule");


	//here i will query all the deans user info  or all the user info of all the users where the account type is dean
	$dean_query = mysqli_query($conn,"SELECT 
											u.`user_id`,
											dp.`department_id`

										FROM users u
										LEFT JOIN faculties f ON f.`faculty_id` = u.`faculty_id`
										LEFT JOIN departments dp ON dp.`department_id` = f.`department_id`
										WHERE TYPE = 'Dean'");//here is the query
	if($dean_query){ //check if the query run -- this condition means it runs
		while($row = mysqli_fetch_assoc($dean_query)){//here i will loop to every id and fetch it, while there is id it will not stop
			
			$user_id = $row['user_id']; //asign the variable
			$department_id = $row['department_id'];


			//now that i have my data to insert task status deans i will  now insert it
			$insert_task = insert_task_status_deans($user_id,$department_id,$tasK_id_of_faculty_schedule);
			if($insert_task){ // if the insert task became true then send notification to the user
				$notifcation_to_users = user_notif($user_id,$notification_id); // send notifcation to the user

			}else{
				//else do nothing
			}


			

		}
	}else{//this means the query has some errors
		//do nothing and just do nothing
	}
}else{
	echo mysqli_error($conn);
}








//now i will do the same to the room utilization matrix
//because i already got the term id and active term i wont repeat it again --> please check at line 23 and 24

//document id of room utilization matrix in the document templates
$document_id = get_document_id_in_document_templates_table("Room Utilization Matrix");
$task_description = "Room Utilization Matrix $active_term";

$query = mysqli_query($conn,"INSERT INTO tasks (task_name,task_desc,document_id,due_date,for_deans,term_id)VALUES('Room Utilization Matrix','$task_description','$document_id','$room_utilization_matrix_due_date',1,'$term_id')");

//this is the id of the latest inserted id in the tasks table which is the task id of the Room Utilization Matrix
$tasK_id_of_room_utilization_matrix = $conn->insert_id;


if($query){ // if the query works then this will be executed

	//here is the function for creating a notifcation content and getting its id
	$notification_id = notification_content("Room Utilization Matrix");


	//here i will query all the deans user info  or all the user info of all the users where the account type is dean
	$dean_query = mysqli_query($conn,"SELECT 
											u.`user_id`,
											dp.`department_id`

										FROM users u
										LEFT JOIN faculties f ON f.`faculty_id` = u.`faculty_id`
										LEFT JOIN departments dp ON dp.`department_id` = f.`department_id`
										WHERE TYPE = 'Dean'");//here is the query
	if($dean_query){ //check if the query run -- this condition means it runs
		while($row = mysqli_fetch_assoc($dean_query)){//here i will loop to every id and fetch it, while there is id it will not stop
			
			$user_id = $row['user_id']; //asign the variable
			$department_id = $row['department_id'];


			//now that i have my data to insert task status deans i will  now insert it
			$insert_task = insert_task_status_deans($user_id,$department_id,$tasK_id_of_room_utilization_matrix);
			if($insert_task){ // if the insert task became true then send notification to the user
				$notifcation_to_users = user_notif($user_id,$notification_id); // send notifcation to the user

			}else{
				//else do nothing
			}


			

		}
	}else{//this means the query has some errors
		//do nothing and just do nothing
	}
}else{
	echo mysqli_error($conn);
}









//opcr target

//now the procedures will be kinda different because now were inserting to all the deans and heads type accounts



//now i will do the same to the room utilization matrix
//because i already got the term id and active term i wont repeat it again --> please check at line 23 and 24

//document id of room utilization matrix in the document templates
$document_id = get_document_id_in_document_templates_table("OPCR");
$task_description = "Office Performance Commitment and Review Target $active_term";


//like for the other sending of task like only deans---here i added a column and a new values which is the column "for heads" and value of 1
$query = mysqli_query($conn,"INSERT INTO tasks (task_name,task_desc,document_id,due_date,for_deans,for_heads,term_id)VALUES('Office Performance Commitment and Review Target','$task_description','$document_id','$opcr_target_due_date',1,1,'$term_id')");

//this is the id of the latest inserted id in the tasks table which is the task id of the OPCR target
$tasK_id_of_opcr_target = $conn->insert_id;


if($query){ // if the query works then this will be executed

	//here is the function for creating a notifcation content and getting its id
	$notification_id = notification_content("Office Performance Commitment and Review Target");


	//here i will query all the deans user info  or all the user info of all the users where the account type is dean and head
	$dean_query = mysqli_query($conn,"SELECT 
											u.`user_id`,
											dp.`department_id`
											

										FROM users u
										LEFT JOIN faculties f ON f.`faculty_id` = u.`faculty_id`
										LEFT JOIN departments dp ON dp.`department_id` = f.`department_id`
										WHERE u.type = 'Dean' OR u.type = 'Head'");//here is the query
	if($dean_query){ //check if the query run -- this condition means it runs
		while($row = mysqli_fetch_assoc($dean_query)){//here i will loop to every id and fetch it, while there is id it will not stop
			
			$user_id = $row['user_id']; //asign the variable
			$department_id = $row['department_id'];
			


			//now that i have my data to insert task status deans i will  now insert it
			$insert_task = insert_task_status_deans($user_id,$department_id,$tasK_id_of_opcr_target);
			if($insert_task){ // if the insert task became true then send notification to the user

				
				$notifcation_to_users = user_notif($user_id,$notification_id); // send notifcation to the user

			}else{
				//else do nothing
			}


			

		}
	}else{//this means the query has some errors
		//do nothing and just do nothing
	}
}else{
	echo mysqli_error($conn);
}








//opcr accomplishment

//now the procedures will be kinda different because now were inserting to all the deans and heads type accounts




//because i already got the term id and active term i wont repeat it again --> please check at line 23 and 24

//document id of room utilization matrix in the document templates
$document_id = get_document_id_in_document_templates_table("OPCR");
$task_description = "Office Performance Commitment and Review Accomplishment $active_term";


//like for the other sending of task like only deans---here i added a column and a new values which is the column "for heads" and value of 1
$query = mysqli_query($conn,"INSERT INTO tasks (task_name,task_desc,document_id,due_date,for_deans,for_heads,term_id)VALUES('Office Performance Commitment and Review Accomplishment','$task_description','$document_id','$opcr_accomplishment_due_date',1,1,'$term_id')");

//this is the id of the latest inserted id in the tasks table which is the task id of the OPCR target
$tasK_id_of_opcr_accomplishment = $conn->insert_id;


if($query){ // if the query works then this will be executed

	//here is the function for creating a notifcation content and getting its id
	$notification_id = notification_content("Office Performance Commitment and Review Accomplishment");


	//here i will query all the deans user info  or all the user info of all the users where the account type is dean and head
	$dean_query = mysqli_query($conn,"SELECT 
											u.`user_id`,
											dp.`department_id`
											

										FROM users u
										LEFT JOIN faculties f ON f.`faculty_id` = u.`faculty_id`
										LEFT JOIN departments dp ON dp.`department_id` = f.`department_id`
										WHERE u.type = 'Dean' OR u.type = 'Head'");//here is the query
	if($dean_query){ //check if the query run -- this condition means it runs
		while($row = mysqli_fetch_assoc($dean_query)){//here i will loop to every id and fetch it, while there is id it will not stop
			
			$user_id = $row['user_id']; //asign the variable
			$department_id = $row['department_id'];
			


			//now that i have my data to insert task status deans i will  now insert it
			$insert_task = insert_task_status_deans($user_id,$department_id,$tasK_id_of_opcr_accomplishment);
			if($insert_task){ // if the insert task became true then send notification to the user

				
				$notifcation_to_users = user_notif($user_id,$notification_id); // send notifcation to the user

			}else{
				//else do nothing
			}


			

		}
	}else{//this means the query has some errors
		//do nothing and just do nothing
	}
}else{
	echo mysqli_error($conn);
}





























//ipcr target

//now this will be different to all sending task --> here we will send it to all users except the admin




//because i already got the term id and active term i wont repeat it again --> please check at line 23 and 24

//document id of ipcr in the document templates
$document_id = get_document_id_in_document_templates_table("IPCR");
$task_description = "Individual Performance Commitment and Review Target $active_term";


//now i will add all the columns of the selections
$query = mysqli_query($conn,"INSERT INTO tasks (task_name,task_desc,document_id,due_date,for_deans,for_heads,for_staffs,for_faculty,term_id)VALUES('Individual Performance Commitment and Review Target','$task_description','$document_id','$ipcr_target_due_date',1,1,1,1,'$term_id')");

//this is the id of the latest inserted id in the tasks table which is the task id of the Individual Performance Commitment and Review target
$tasK_id_of_ipcr_target = $conn->insert_id;


if($query){ // if the query works then this will be executed

	//here is the function for creating a notifcation content and getting its id
	$notification_id = notification_content("Individual Performance Commitment and Review Target");


	//here i will query all the deans user info  or all the user info of all the users where the account type is dean
	$users_query = mysqli_query($conn,"SELECT 
											u.`user_id`,
											dp.`department_id`

										FROM users u
										LEFT JOIN faculties f ON f.`faculty_id` = u.`faculty_id`
										LEFT JOIN departments dp ON dp.`department_id` = f.`department_id`
										WHERE u.type != 'Admin'");//here is the query
	if($users_query){ //check if the query run -- this condition means it runs
		while($row = mysqli_fetch_assoc($users_query)){//here i will loop to every id and fetch it, while there is id it will not stop
			
			$user_id = $row['user_id']; //asign the variable
			$department_id = $row['department_id'];


			//now that i have my data to insert task status deans i will  now insert it
			$insert_task = insert_task_status_deans($user_id,$department_id,$tasK_id_of_ipcr_target);
			if($insert_task){ // if the insert task became true then send notification to the user
				$notifcation_to_users = user_notif($user_id,$notification_id); // send notifcation to the user

			}else{
				//else do nothing
			}


			

		}
	}else{//this means the query has some errors
		//do nothing and just do nothing
	}
}else{
	echo mysqli_error($conn);
}






//ipcr accomplishment

//now this will be different to all sending task --> here we will send it to all users except the admin




//because i already got the term id and active term i wont repeat it again --> please check at line 23 and 24

//document id of ipcr in the document templates
$document_id = get_document_id_in_document_templates_table("IPCR");
$task_description = "Individual Performance Commitment and Review Accomplishment $active_term";


//now i will add all the columns of the selections
$query = mysqli_query($conn,"INSERT INTO tasks (task_name,task_desc,document_id,due_date,for_deans,for_heads,for_staffs,for_faculty,term_id)VALUES('Individual Performance Commitment and Review Accomplishment','$task_description','$document_id','$ipcr_accomplishment_due_date',1,1,1,1,'$term_id')");

//this is the id of the latest inserted id in the tasks table which is the task id of the Individual Performance Commitment and Review target
$tasK_id_of_ipcr_accomplishment= $conn->insert_id;


if($query){ // if the query works then this will be executed

	//here is the function for creating a notifcation content and getting its id
	$notification_id = notification_content("Individual Performance Commitment and Review Accomplishment");


	//here i will query all the deans user info  or all the user info of all the users where the account type is dean
	$users_query = mysqli_query($conn,"SELECT 
											u.`user_id`,
											dp.`department_id`

										FROM users u
										LEFT JOIN faculties f ON f.`faculty_id` = u.`faculty_id`
										LEFT JOIN departments dp ON dp.`department_id` = f.`department_id`
										WHERE u.type != 'Admin'");//here is the query
	if($users_query){ //check if the query run -- this condition means it runs
		while($row = mysqli_fetch_assoc($users_query)){//here i will loop to every id and fetch it, while there is id it will not stop
			
			$user_id = $row['user_id']; //asign the variable
			$department_id = $row['department_id'];


			//now that i have my data to insert task status deans i will  now insert it
			$insert_task = insert_task_status_deans($user_id,$department_id,$tasK_id_of_ipcr_accomplishment);
			if($insert_task){ // if the insert task became true then send notification to the user
				$notifcation_to_users = user_notif($user_id,$notification_id); // send notifcation to the user

			}else{
				//else do nothing
			}


			

		}
	}else{//this means the query has some errors
		//do nothing and just do nothing
	}
}else{
	echo mysqli_error($conn);
}






































function get_active_term_id(){
	include '../config.php';

	$query = mysqli_query($conn,"SELECT term_id FROM terms WHERE status = 'ACTIVE'");
	if($query){
		if(mysqli_num_rows($query)>0){
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


function get_active_term_for_task_description(){
	include '../config.php';

	$query = mysqli_query($conn,"SELECT term FROM terms WHERE status = 'ACTIVE'");
	if($query){
		if(mysqli_num_rows($query)>0){
			$row = mysqli_fetch_assoc($query);
			$term = $row['term'];

			return $term;
		}else{
			return false;
		}
	}else{
		return false;
	}
}
//getting the document id
function get_document_id_in_document_templates_table($task_name){
	include '../config.php';

	$query = mysqli_query($conn,"SELECT doc_template_id FROM document_templates WHERE template_title = '$task_name'");
	if($query){
		if(mysqli_num_rows($query)>0){
			$row = mysqli_fetch_assoc($query);
			$doc_template_id = $row['doc_template_id'];

			return $doc_template_id;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

//for inserting task status in the task status deans
function insert_task_status_deans($user_id,$department_id,$task_id){
	include "../config.php";

	$query = mysqli_query($conn,"INSERT INTO task_status_deans(task_id,office_id,user_id,is_completed)VALUES('$task_id','$department_id','$user_id',1)");
	if($query){
		return true;
	}else{
		return false;
	}
}


//create a notification content that are to be send
function notification_content($task_name){
	include "../config.php";
	//this is the content of the notification
	$content = "Office of Vice Chancellor of Academic Affairs Uploaded a task ".$task_name;
	
	
	$sql = mysqli_query($conn,"INSERT INTO notifications(content,is_task) VALUES('$content','yes')");
	if($sql){
		return $conn->insert_id;
		
	}
	else{
		return false;
	}



}
//here is the functions that will send notifcations to the users
function user_notif($user_id,$notif_id){
	include "../config.php";
	
	 $sql = mysqli_query($conn, "INSERT INTO user_notifications(status, notif_id, user_id) VALUES(0, '$notif_id', '$user_id')");
	 if($sql){
	 	return true;
	 }else{
	 	return false;
	 }
    

}


?>