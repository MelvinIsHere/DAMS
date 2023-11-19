
  <?php
  session_start();
// Check if the form has been submitted
include "../php/config.php";
    // Process form data here
    $term = $_POST['terms'];
    


    $query = mysqli_query($conn, "SELECT 
                                t.term_id,
                                t.`term`,
                                t.`year`,
                                ay.`acad_year`,
                                s.`sem_description`
                            FROM terms t
                            LEFT JOIN academic_year ay ON ay.`acad_year_id` = t.acad_year_id
                            LEFT JOIN semesters s ON s.`semester_id` = t.semester_id
                            WHERE CONCAT(t.term,' ',t.year,' ',s.sem_description)  = '$term'");
    if($query){
        if(mysqli_num_rows($query)>0){
            $row = mysqli_fetch_assoc($query);
            $term_id = $row['term_id'];
            $_SESSION['term_id'] = $term_id;
            header("Location: heads.php");
        }else{

        }
    }else{

    }


    

?>