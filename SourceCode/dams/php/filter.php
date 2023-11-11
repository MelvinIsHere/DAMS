
  <?php
  session_start();
// Check if the form has been submitted
include "config.php";
    // Process form data here
    $sem_description = $_POST['semester'];
    $acad_year = $_POST['academic_year'];


    $acad_year = mysqli_query($conn, "SELECT acad_year_id FROM academic_year WHERE acad_year = '$acad_year'");
    if($acad_year){
        if(mysqli_num_rows($acad_year)>0){
            $row = mysqli_fetch_assoc($acad_year);
            $acad_id = $row['acad_year_id'];
            $_SESSION['acad_id'] = $acad_id;
            header("Location: ../deans/deans.php");
        }else{

        }
    }else{

    }


    $semester = mysqli_query($conn,"SELECT semester_id FROM semesters WHERE sem_description = '$sem_description'");
    if($semester){
        if(mysqli_num_rows($semester) > 0){
            $row = mysqli_fetch_assoc($semester);
            $semester_id = $row['semester_id'];
            $_SESSION['semester_id'] = $semester_id;
            header("Location: ../deans/deans.php");
        }else{

        }
    }else{

    }

    

?>