  <?php 
  session_start();
  include "db_con.php";
    $users_id = $_SESSION['unique_id'];
    


 
    


                            



                            if(isset($_POST['submit'])){
                            	$course_code = $_POST['course_code'];
                                $section = $_POST['section'];
                                $faculty = $_POST['faculty'];
                                $semester = $_POST['semester'];
                                echo $faculty;
                                
                                


						    
						    	$sql = mysqli_query($conn,"SELECT faculty_id FROM faculties WHERE CONCAT(firstname,' ',middlename, ' ',lastname) = '$faculty'");
						    	$result = mysqli_fetch_assoc($sql);
						    	


						    	if($sql){
						    		$dept_id = $result['faculty_id'];
						    		$course_data = mysqli_query($conn,"SELECT * FROM courses WHERE course_code = '$course_code'");
						    		$code = mysqli_fetch_assoc($course_data);
						    		$course_id = $code['course_id'];
						    		if($course_data){
						    			$section_data = mysqli_query($conn,"SELECT * FROM sections");
						    			$section = mysqli_fetch_assoc($section_data);
						    			$section_id = $section['section_id'];
						    			if($section_data){
						    					$acad_year = mysqli_query($conn,"SELECT acad_year_id FROM academic_year WHERE start_year = (SELECT MAX(start_year) FROM academic_year);");
						    					$year = mysqli_fetch_assoc($acad_year);
						    					$current_acad = $year['acad_year_id'];
						    			if($acad_year){
						    				$sem = mysqli_query($conn,"SELECT semester_id FROM semesters WHERE sem_description = '$semester'");
						    				$sems = mysqli_fetch_assoc($sem);
						    				$sem_id = $sems['semester_id'];
						    				if($sems){
						    					$insert = mysqli_query($conn,"INSERT INTO faculty_loadings(faculty_id,course_id,section_id,acad_year_id,sem_id) VALUES('$dept_id','$course_id','$section_id','$current_acad','$sem_id')");
						    					if($insert){
						    						header('Location: ../deans/dataFillUp.php?Success : Insert Success');
						    					}
						    					else{
						    						echo mysqli_error($conn);
						    					}
						    				}
						    				else{
						    					echo mysqli_error($conn);
						    				}
						    				
						    			}

						    		}

						    	


						    	}
						    



                              


                                
                            }
                            else{
                                header("Location: ../deans/deans.php?Error :There are no " . $faculty . "in the faculty list" );
                            }
                        
}
                            ?>