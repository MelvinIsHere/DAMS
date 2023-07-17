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
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Access the data in each row using column names
        echo "<tr>
                            <td class='course_id'><?php echo $id;?></td>
                            <td><?php echo $course_code;?></td>
                            <td><?php echo $course_description;?></td>
                            <td><?php echo $units;?></td>
                            <td><?php echo $lec;?></td>
                            <td><?php echo $rle;?></td>
                            <td><?php echo $lab;?></td>
                            
                            <td>
                                <a href='#editEmployeeModal' class='edit' data-toggle='modal'><i class='material-icons' data-toggle='tooltip' title='Edit'>&#xE254;</i></a>
                                <a href='#deleteEmployeeModal' class='delete' data-toggle='modal'><i class='material-icons' data-toggle='tooltip' title='Delete'>&#xE872;</i></a>
                            </td>
                        </tr>";

    }
} else {
    echo "Query failed: " . $conn->error;
}

$conn->close();


?>