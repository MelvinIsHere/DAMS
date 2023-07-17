<?php 
session_start();
$user_id = $_SESSION['user_id'];
       $conn = new mysqli("localhost", "root", "", "dams2");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT
                                    course_id,
                                    course_code,
                                    course_description,
                                    units,
                                    lec_hrs_wk,
                                    rle_hrs_wk,
                                    lab_hrs_wk 
                                    FROM courses
                                    WHERE department_id = 8";
 $result = $conn->query($sql);      //EXCUTION
                                            $result_array = [];
                                            if(mysqli_num_rows($result) > 0){
                                                        foreach($result as $row){
                                                                array_push($result_array, $row);
                                                        }
                                                        header('Content-type: application/json');
                                                        echo json_encode($result_array);
                                            }
                                            else{
                                                echo "<h4>Nothing Found</h4>";
                                            }
$conn->close();


?>