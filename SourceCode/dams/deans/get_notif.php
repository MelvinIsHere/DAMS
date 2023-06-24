<?php 
       $conn = new mysqli("localhost", "root", "", "dams");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT COUNT(*) FROM announcement";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Access the data in each row using column names
        echo $row["COUNT(*)"];
    }
} else {
    echo "Query failed: " . $conn->error;
}

$conn->close();


?>