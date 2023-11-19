<?php 
session_start();
include 'config.php';


$academic_year = $_POST['academic_year'];
$term = $_POST['term'];
$semester = $_POST['semester'];
$year = $_POST['year'];
// $start_january = "January";//start of the 2nd and summer 
// $end_july = "July"; //end of the 2nd and summer 
// $start_july = "july"; //start of the 1st term
// $end_dec

$verify = verify_academic_year($academic_year);//here i am verifying if there is already acad year and return the id
$acad_id = $verify; //assigning it 
$semester_id = get_semester_id($semester);
echo $acad_id;
echo deactivate_previous_term(); //deactivate the previous term
if($verify){
	echo "already exist not inserting new acad year";
}else{

}

if($semester == "First Semester" && $term == "July - December"){
	echo "applicable";
}elseif($semester == "Second Semester" && $term == "January - July"){
	echo insert_term_for_january_july($term,$acad_id,$semester_id,$year);
}elseif($semester == "Summer Class" && $term == "January - July"){
	echo insert_term_for_january_july($term,$acad_id,$semester_id,$year);
}else{
	echo "not";
}



function verify_academic_year($academic_year){
	include "config.php";

	$sql = "SELECT acad_year,acad_year_id FROM academic_year WHERE acad_year = '$academic_year'";
	$query = mysqli_query($conn,$sql);
	if($query){
		if(mysqli_num_rows($query) >0){
			$row = mysqli_fetch_assoc($query);
			$acad_year_id = $row['acad_year_id'];

			return $acad_year_id;
		}else{
			return  mysqli_error($conn);
		}
	}else{
		return  mysqli_error($conn);
	}

}
function get_semester_id($semester){
	include 'config.php';
	$query = mysqli_query($conn,"SELECT semester_id FROM semesters WHERE sem_description = '$semester'");
	if($query){
		if(mysqli_num_rows($query)>0){
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
function deactivate_previous_term(){
	include "config.php";
	$query = mysqli_query($conn,"UPDATE terms SET status = 'INACTIVE' WHERE status = 'ACTIVE'");
}
function insert_term_for_january_july($term,$acad_id,$semester_id,$year){
	include "config.php";
	$verify_term = verify_term($term,$acad_id,$semester_id);
	if(!$verify_term){
		//if there are no same term
			$query = mysqli_query($conn,"INSERT INTO terms(start, `end`, term, status, year, acad_year_id, semester_id)
			VALUES ('January', 'July', '$term', 'ACTIVE','$year', '$acad_id', '$semester_id')");
			if($query){
				$term_id =  $conn->insert_id;
				$_SESSION['term_id'] = $term_id;
				return true;
			}else{
				return false;
			}
	}else{
		return false;
	}

}
function verify_term($term,$acad_id,$semester_id){
	include "config.php";

	$query = mysqli_query($conn,"SELECT * FROM terms WHERE term = '$term' AND acad_year_id = '$acad_id' AND semester_id = '$semester_id'");
	if($query){
		if(mysqli_num_rows($query)>0){
			return true; //mearning it already exist and should not execute the insertion of terms

		}else{
			return false;
		}
	}else{
		return false;
	}
}

?>