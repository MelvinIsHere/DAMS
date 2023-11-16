
  <?php
  session_start();
// Check if the form has been submitted
include "../php/config.php";
    // Process form data here
    $term = $_POST['terms'];
    


    $query = mysqli_query($conn, "SELECT term_id FROM terms WHERE CONCAT(term,' ',year) = '$term'");
    if($query){
        if(mysqli_num_rows($query)>0){
            $row = mysqli_fetch_assoc($query);
            $term_id = $row['term_id'];
            $_SESSION['term_id'] = $term_id;
            header("Location: staffs.php");
        }else{

        }
    }else{

    }


    

?>