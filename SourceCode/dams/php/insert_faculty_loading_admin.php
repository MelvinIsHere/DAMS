  <?php 
  session_start();
  include "config.php";
  include "functions.php";
    $users_id = $_SESSION['unique_id'];
    $department_name = $_SESSION['dept_name'];
    $primary_id = $_SESSION['user_id'];
    


 
    


                            



                            if(isset($_POST['submit'])){

                            	$course_code = $_POST['course_code'];
                                $section = $_POST['section'];
                                $faculty = $_POST['faculty'];
                                $semester = $_POST['semester'];
                                $acad_year = $_POST['acad'];
                                
                                

                                //get the id of the faculty member
                                $faculty_id = getFacultyId($faculty);                               
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

						    								$insert = mysqli_query($conn,"INSERT INTO faculty_loadings(faculty_id,course_id,section_id,acad_year_id,sem_id,dept_id) VALUES('$faculty_id','$course_data','$section_data','$acad_year','$semester_data','$dept_id')");

						    								if($insert){	
						    									
						    									header('Location: ../admin/faculty_loading_ui.php?Success : Insert Success');
									    					}
									    					else{
									    						header('Location: ../admin/faculty_loading_ui.php?Error : Insert Failed');
									    					}
						    						}
						    						else{
						    					header('Location: ../admin/faculty_loading_ui.php?Error : Insert Failed');
						    					}

								    					}
							    					
						    					
						    					
						    				}
						    				else{
						    					header('Location: ../admin/faculty_loading_ui.php?Error : Insert Failed');
						    				}
						    				
						    			}
						    			else{
						    					header('Location: ../admin/faculty_loading_ui.php?Error : Insert Failed');
						    				}

						    		}
						    		else{
						    					header('Location: ../admin/faculty_loading_ui.php?Error : Insert Failed');
						    				}

						    	


						    	}
						    	else{
						    		header('Location: ../admin/faculty_loading_ui.php?Error : Insert Failed');
						    	}
						    



                              


                                
                            }
                            else{
                            	header('Location: ../admin/faculty_loading_ui.php?Error : Insert Failed');
                                // header("Location: ../deans/deans.php?Error :There are no " . $faculty . "in the faculty list" );
                            }
                        
}
else{
						    					header('Location: ../admin/faculty_loading_ui.php?Error : Insert Failed');
						    				}
                            ?>