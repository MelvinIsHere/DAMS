  <?php 
session_start();
include "config.php";
include "functions.php";
$users_id = $_SESSION['unique_id'];
$department_name = $_SESSION['dept_name'];
$primary_id = $_SESSION['user_id'];
$faculty = $_POST['faculty'];
$error = "error";
$success = "success";

if(isset($_POST['submit']) && !empty($faculty)){
	$course_code = $_POST['course_code'];
    $section = $_POST['section'];
    $faculty = $_POST['faculty'];
    $semester = $_POST['semester'];
    $acad_year = $_POST['acad'];
                              
	//get the id of the faculty member
    $faculty_id = getFacultyId($faculty); 
	$verify = verify_faculty_loading_data($faculty_id);  //just remove this tommorow
	if($verify){
		//reach max limit
		$message = "Faculty reach max units!";
    	$_SESSION['alert'] = $error; 
    	$_SESSION['message'] =  $message;   //failed to insert
		header("Location: ../deans/faculty_loading_ui.php");
		

	}else{
		if($faculty_id != ""){
			$insert_with_faculty = withFacultyinsert($faculty_id,$course_code,$section,$semester,$primary_id,$department_name,$acad_year,$conn);
			if($insert_with_faculty){
				$message = "Faculty has been successfully loaded!";
				$_SESSION['alert'] = $success; 
    			$_SESSION['message'] =  $message;   
				header("Location: ../deans/faculty_loading_ui.php");
			}else{
				$message = "Faculty loading failed!1";
				$_SESSION['alert'] = $error; 
				$_SESSION['message'] =  $message;   //failed to insert
				header("Location: ../deans/faculty_loading_ui.php");
			}
		}else{
			$insert = insertwithoutfaculty($course_code,$section,$acad_year,$semester,$primary_id,$department_name,$conn);
			if($insert){
				$message = "Faculty has been successfully loaded!";
				$_SESSION['alert'] = $success; 
    			$_SESSION['message'] =  $message;   
				header("Location: ../deans/faculty_loading_ui.php");
			}else{
				$message = "Faculty loading failed!1";
				$_SESSION['alert'] = $error; 
				$_SESSION['message'] =  $message;   //failed to insert
				header("Location: ../deans/faculty_loading_ui.php");
			}
		}
	}
	
                        
}elseif(isset($_POST['submit']) && empty($_POST['faculty'])){
    $course_code = $_POST['course_code'];
   	$section = $_POST['section'];                              
	$semester = $_POST['semester'];
    $acad_year = $_POST['acad'];
    $needed = 'Needed for lecturer';
	//get the id of the courses
	$insert = insertwithoutfaculty($course_code,$section,$acad_year,$semester,$primary_id,$department_name,$conn);
	if($insert == "success"){
		$message = "Faculty has been successfully loaded!";
		$_SESSION['alert'] = $success; 
    	$_SESSION['message'] =  $message;   
		header("Location: ../deans/faculty_loading_ui.php");
	}else{
		$message = "Faculty loading failed!";
		$_SESSION['alert'] = $error; 
		$_SESSION['message'] =  $message;   //failed to insert
		// header("Location: ../deans/faculty_loading_ui.php");
	}
	                                           
}else{
	$message = mysqli_error($conn);
    $_SESSION['alert'] = $error; 
    $_SESSION['message'] =  $message;   //failed to insert
	// header("Location: ../deans/faculty_loading_ui.php");
	
}
             

function insertwithoutfaculty($course_code,$section,$acad_year,$semester,$primary_id,$department_name,$conn){

	$course_data = getCourseData($course_code);						    		
	if($course_data != ""){
		$section_data = getSectionData($section);						 		    			
		if($section_data != ""){
			//get academic year id
			$acad_year = getAcadYear($acad_year);						    					
			if($acad_year != ""){
				//get semester id
				$semester_data = getSemesterId($semester);						    				
				if($section_data != ""){
					$department_name = getName($primary_id);
					if($department_name != ""){
						$dept_id = getDeptId($department_name);
						if($dept_id != ""){
							$insert = mysqli_query($conn,"INSERT INTO faculty_loadings(faculty_id,course_id,section_id,acad_year_id,sem_id,dept_id,needed) VALUES(NULL,'$course_data','$section_data','$acad_year','$semester_data','$dept_id','$needed')");
							if($insert){							    									
						    	$message = "Faculty has been successfully loaded!";
    							$_SESSION['alert'] = $success; 
    							$_SESSION['message'] =  $message;   //failed to insert
								return "success";
							}else{
								$message = "Faculty loading failed1!";
    							$_SESSION['alert'] = $error; 
    							$_SESSION['message'] =  $message;   //failed to insert
								header("Location: ../deans/faculty_loading_ui.php");
							}
						}else{
							$message = "Faculty loading failed!2";
						    $_SESSION['alert'] = $error; 
						    $_SESSION['message'] =  $message;   //failed to insert
							header("Location: ../deans/faculty_loading_ui.php");
						}

					}	    					
				}else{
					$message = "Faculty loading failed!3";
				    $_SESSION['alert'] = $error; 
				    $_SESSION['message'] =  $message;   //failed to insert
					header("Location: ../deans/faculty_loading_ui.php");
				}
						    				
			}else{
				$message = "Faculty loading failed!4";
			    $_SESSION['alert'] = $error; 
			    $_SESSION['message'] =  $message;   //failed to insert
				header("Location: ../deans/faculty_loading_ui.php");
			}
		}else{
			$message = "Faculty loading failed!5";
		    $_SESSION['alert'] = $error; 
		    $_SESSION['message'] =  $message;   //failed to insert
			header("Location: ../deans/faculty_loading_ui.php");
		}

	}else{
		$message = "Faculty loading failed!6";
    	$_SESSION['alert'] = $error; 
    	$_SESSION['message'] =  $message;   //failed to insert
		header("Location: ../deans/faculty_loading_ui.php");
	}  
}









function withFacultyinsert($faculty_id,$course_code,$section,$semester,$primary_id,$department_name,$acad_year,$conn){
	if($faculty_id != ""){
		//get the id of the courses
		$course_data = getCourseData($course_code);						    		
		if($course_data != ""){
			$section_data = getSectionData($section);						 		    			
				if($section_data != ""){
					//get academic year id
					$acad_year = getAcadYear($acad_year);						    					
					if($acad_year != ""){
						//get semester id
						$semester_data = getSemesterId($semester);						    				
						if($section_data != ""){
							$department_name = getName($primary_id);
							if($department_name != ""){
								$dept_id = getDeptId($department_name);
							    if($dept_id != ""){
						    		$insert = mysqli_query($conn,"INSERT INTO faculty_loadings(faculty_id,course_id,section_id,acad_year_id,sem_id,dept_id,needed) VALUES('$faculty_id','$course_data','$section_data','$acad_year','$semester_data','$dept_id',NULL)");
						    		if($insert){	
						    			
										return true;
									 }else{
									  	$message = "Faculty has been successfully loaded!";
										$_SESSION['alert'] = $success; 
						    			$_SESSION['message'] =  $message;   
										header("Location: ../deans/faculty_loading_ui.php");
										return false;
									 }
						    	}else{
						    		$message = "Faculty loading failed!";
				$_SESSION['alert'] = $error; 
				$_SESSION['message'] =  $message;   //failed to insert
				header("Location: ../deans/faculty_loading_ui.php");

						    	}
							}
							    					
						}else{
						   $message = "Faculty loading failed!";
				$_SESSION['alert'] = $error; 
				$_SESSION['message'] =  $message;   //failed to insert
				header("Location: ../deans/faculty_loading_ui.php");
						}
						    				
			}else{
				$message = "Faculty loading failed!";
    			$_SESSION['alert'] = $error; 
    			$_SESSION['message'] =  $message;   //failed to insert
				header("Location: ../deans/faculty_loading_ui.php");
				return false;
			}

		}else{
			$message = "Faculty loading failed!";
				$_SESSION['alert'] = $error; 
				$_SESSION['message'] =  $message;   //failed to insert
				header("Location: ../deans/faculty_loading_ui.php");
		}
	}else{
		$message = "Faculty loading failed!";
				$_SESSION['alert'] = $error; 
				$_SESSION['message'] =  $message;   //failed to insert
				header("Location: ../deans/faculty_loading_ui.php");
	}
						    
}else{
	$message = "Faculty loading failed!";
				$_SESSION['alert'] = $error; 
				$_SESSION['message'] =  $message;   //failed to insert
				header("Location: ../deans/faculty_loading_ui.php");
}
}                            
                        ?>