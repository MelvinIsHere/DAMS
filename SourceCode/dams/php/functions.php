<?php

	
function insertQuery($fileName,$path,$users_id){
include "config.php";
 $query = "INSERT INTO file_table(file_name,directory,file_owner_id) VALUES ('$fileName','$path','$users_id')";
 $run = mysqli_query($conn,$query);
if (!$run) {
		$vars = mysql_error($conn);
	}else{
		return "success";
	}
}
function updateTaskStats($office_id,$task_id){
	include "config.php";
	$update = mysqli_query($conn,"UPDATE task_status_deans SET is_completed = '0' WHERE task_id = '$task_id' AND office_id = '$office_id'");
	if (!$update) {
		return "";
	}else{
		return "success";
	}

}

function getName($user_id){
	include "config.php";
	$sql = mysqli_query($conn,"SELECT department_name FROM departments WHERE $user_id = '$user_id'");
	$info = mysqli_fetch_assoc($sql);
	if ($info) {
		$name =  $info['department_name'];
		return $name;
	}
	else{
		
		return "";
	}

}

function getDeptId($dept_name){
	include "config.php";
	$sql = mysqli_query($conn,"SELECT department_id FROM departments WHERE department_name = '$dept_name'");
	$info = mysqli_fetch_assoc($sql);
	if ($info) {
		$id =  $info['department_id'];
		return $id;
	}
	else{
		$null = "";
		
		return $null;
	}

}
function  getTaskName($task_id){
	include "config.php";
	$id = $task_id;
	$sql = mysqli_query($conn,"SELECT task_name FROM tasks WHERE task_id = '$id'");
	$task = mysqli_fetch_assoc($sql);
	$task_name = $task['task_name'];
	if(!$sql){
		return "";
	}
	else{
		return $task_name;
		echo "getTaskName";
	}
}
function notifications($name,$task_name){
	include "config.php";
	$content = $name . " Submitted a file in " . $task_name . "!";
	$sql = mysqli_query($conn,"INSERT INTO notifications(content,is_task) VALUES('$content','no')");
	if(!$sql){
		return "";
		
	}
	else{
		return $conn->insert_id;
	}

}
function user_notif_dean($users_id,$notif_id,$type){
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



function user_notif_ovcaa($users_id,$notif_id,$type){
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

function user_notif_dean_heads($users_id,$notif_id,$type1,$type2){
	include "config.php";
	// Assuming you have already established the database connection using $conn

		$user_ids_query = "SELECT user_id FROM users WHERE type = '$type1' OR type = '$type2'";

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


function user_notif_dean_ovcaa($users_id,$notif_id,$type1,$type2){
	include "config.php";
	// Assuming you have already established the database connection using $conn

		$user_ids_query = "SELECT user_id FROM users WHERE type = '$type1' OR type = '$type2'";

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

function user_notif_all($users_id,$notif_id,$type1,$type2,$type3){
	include "config.php";
	// Assuming you have already established the database connection using $conn

		$user_ids_query = "SELECT user_id FROM users WHERE type = '$type1' OR type = '$type2' OR type = '$type3'";

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

function user_notif_dean_only($users_id,$notif_id,$type1){
	include "config.php";
	// Assuming you have already established the database connection using $conn

		$user_ids_query = "SELECT user_id FROM users WHERE type = '$type1'";

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



//tasks
function insertTask($task_name,$description,$dateStart,$dateEnd,$ovcaa,$deans,$department){
	include "config.php";
	  $conn -> query("INSERT INTO tasks (task_name, task_desc, date_posted, due_date, for_ovcaa,for_deans, for_heads) VALUES ('$task_name','$description', '$dateStart', '$dateEnd','$ovcaa', '$deans', '$department')");
                // $result = $conn->query($sql);
                // Print auto-generated id
                $id = $conn -> insert_id;

           if(!empty($id)){
           	return $id;
           }
           else{
           		return "Not inserted";
           }
}
function  getDept_id($task_id){
				include "config.php";
                $sql = "SELECT department_id FROM departments WHERE department_abbrv != 'OVCAA'";
                $result = mysqli_query($conn,$sql);
                if ($result) {
                    while($row = mysqli_fetch_array($result)){
                        $dept_id = $row['department_id'];
                        $sql = mysqli_query($conn,"INSERT INTO task_status(task_id,office_id,is_completed)VALUES('$id','$dept_id',1)");
                    }
                }
            }
function adminInsertTask($task_name,$description,$doc_temp_id,$dateStart,$dateEnd,$ovcaa,$deans,$heads){
	include "config.php";
	   $conn -> query("INSERT INTO tasks (task_name, task_desc,document_id, date_posted, due_date, for_ovcaa,for_deans,for_heads) VALUES ('$task_name','$description','$doc_temp_id', '$dateStart', '$dateEnd','$ovcaa', '$deans','$heads')");
                // $result = $conn->query($sql);
                // Print auto-generated id
                $id = $conn -> insert_id;


            return $id;
                
}
function insertTaskNotification($task_name){
	include "config.php";
	$content = "Office of Vice Chancellor of Academic Affairs Uploaded a task ".$task_name;
	
	
	$sql = mysqli_query($conn,"INSERT INTO notifications(content,is_task) VALUES('$content','yes')");
	if(!$sql){
		return mysqli_error($conn);
		
	}
	else{
		return $conn->insert_id;
	}



}
function getEmail($users_id){
	include "config.php";
	$getName = mysqli_query($conn,"SELECT email FROM users WHERE user_id = '$users_id'");
	$emailArray = mysqli_fetch_assoc($getName);
	$email = $emailArray['email'];

	return $email;
}


function activity_log($users_id){
	include "config.php";
	$email = getEmail($users_id);
	$activity = $email . "  Uploaded a task";   
	$act_log = mysqli_query($conn,"INSERT INTO activity_log(activity,user_id) VALUES('$activity','$users_id')")	;



}

function activity_log_submitted_documents($users_id,$task_name){
	include "config.php";
	$email = getEmail($users_id);
	$activity = $email . "  Submitted a document in " . $task_name;   
	$act_log = mysqli_query($conn,"INSERT INTO activity_log(activity,user_id) VALUES('$activity','$users_id')")	;
	if(!$act_log){
			return "";
	}
	else{
		return "success";
	}



}
function returnTaskAccomplishment($file_owner_id){
	include "config.php";
	$sql = mysqli_query($conn,"UPDATE task_status SET status
                                                    WHERE f.file_owner_id = '$file_owner_id'");
	$file = mysqli_fetch_assoc();
}
function notifications_return($name,$task_name){
	include "config.php";
	$content = $name . "Return your task " . $task_name . "your file is wrong";
	$sql = mysqli_query($conn,"INSERT INTO notifications(content,is_task) VALUES('$content','yes')");
	if(!$sql){
		return mysqli_error($conn);
		
	}
	else{
		return $conn->insert_id;
	}

}
function user_notif_update($users_id,$notif_id){
	include "config.php";
	$sql = mysqli_query($conn,"INSERT INTO user_notifications(status,notif_id,user_id) VALUES(0,'$notif_id','$users_id')");
	if(!$sql){
		return mysqli_error($conn);
	}
	else{
		return "success";
	}
}
function getTemplateId($task_name){

		include "config.php";
	$sql = mysqli_query($conn,"SELECT doc_template_id FROM document_templates WHERE template_title = '$task_name'");
	$array = mysqli_fetch_assoc($sql);
	$temp_id = $array['doc_template_id'];
	if(!$sql){
		return mysqli_error($conn);
	}
	else{
		return $temp_id;
	}

}

function getFacultyId($faculty){
	include "config.php";
	$sql = mysqli_query($conn,"SELECT faculty_id FROM faculties WHERE CONCAT(lastname,' ',firstname, ' ',middlename ) = '$faculty'");
		if(mysqli_num_rows($sql) > 0){
			
				$result = mysqli_fetch_assoc($sql);
				$fac_id = $result['faculty_id'];
				if(!$sql){

					$null = "";
					return $null;

				}
				else{
					return $fac_id;
				}

		}
		else{
			return "";
		}
		
}
function getCourseData($course_code){
	include "config.php";
	$course_data = mysqli_query($conn,"SELECT * FROM courses WHERE course_code = '$course_code'");
	$code = mysqli_fetch_assoc($course_data);
	$course_id = $code['course_id'];

	if(!$course_data){
		$null ="";
		return $null;
	}
	else{
		return $course_id;
	}

}
function getSectionData($section){
	include "config.php";
	$section_data = mysqli_query($conn,"SELECT section_id FROM sections WHERE section_name = '$section'");
	$section = mysqli_fetch_assoc($section_data);
	$section_id = $section['section_id'];
	if(!$section_data){
		$null ="";
		return $null;
	}
	else{
		return $section_id;
	}
}
function getAcadYear($acad_year){
	include "config.php";
	$acad_year = mysqli_query($conn,"SELECT acad_year_id FROM academic_year WHERE acad_year = '$acad_year'");
	$year = mysqli_fetch_assoc($acad_year);
	$current_acad = $year['acad_year_id'];
	if(!$acad_year){
		$null ="";
		return $null;
	}
	else{
		return $current_acad;
	}
}
function getSemesterId($semester){
	include "config.php";
	$sem = mysqli_query($conn,"SELECT semester_id FROM semesters WHERE sem_description = '$semester'");
	$sems = mysqli_fetch_assoc($sem);
	$sem_id = $sems['semester_id'];
	if(!$sem){
		$null ="";
		return $null;
	}
	else{
		return $sem_id;
	}
}
function getTemplateName($template){
	include "config.php";
	
	$template = mysqli_real_escape_string($conn, $template); // Escape the input to prevent SQL injection
	
	$sql = mysqli_query($conn, "SELECT template_title FROM document_templates WHERE template_title = '$template'");
	
	if(mysqli_num_rows($sql) > 0){
		$row = mysqli_fetch_assoc($sql);
		$template_name = $row['template_title'];
		return $template_name;
	} 
	else{
		return ""; // Return an appropriate value when no rows are found
	}
}
function getSectionId($section){

	include "config.php";
	
	
	
	$sql = mysqli_query($conn, "SELECT 
								sc.section_id AS 'section_id',
								CONCAT(pr.`program_abbrv`,' ',sc.`section_name`) 'Section'
                                FROM sections sc LEFT JOIN programs pr ON sc.`program_id`=pr.`program_id`
                                WHERE CONCAT(pr.`program_abbrv`,' ',sc.`section_name`) = '$section'");
		if(mysqli_num_rows($sql) > 0){
			$row = mysqli_fetch_assoc($sql);

			$section_id = $row['section_id'];

			
			if(!$sql){
				return ""; // Return an appropriate value when no rows are found
			
				
			} 
			else{
				return $section_id;
			}

		}
		else{
			return "";
		}
		

}
function getProgramId($programs){

	include "config.php";
	
	$program = mysqli_real_escape_string($conn, $programs); // Escape the input to prevent SQL injection
	
	$sql = mysqli_query($conn, "SELECT program_id FROM programs WHERE program_title = '$programs'");
	
	if(mysqli_num_rows($sql) > 0){
		$row = mysqli_fetch_assoc($sql);
		$program_id = $row['program_id'];
		return $program_id;
	} 
	else{
		return "error"; // Return an appropriate value when no rows are found
	}

}
function getTitleId($title){
	include "config.php";
		$title = mysqli_real_escape_string($conn, $title); // Escape the input to prevent SQL injection
	
	$sql = mysqli_query($conn, "SELECT title_id FROM titles WHERE title_description = '$title'");
	
	if(mysqli_num_rows($sql) > 0){
		$row = mysqli_fetch_assoc($sql);
		$title_id = $row['title_id'];
		return $title_id;
	} 
	else{
		return "error"; // Return an appropriate value when no rows are found
	}



}

 ?>

