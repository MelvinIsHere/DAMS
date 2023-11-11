<?php 
session_start();
$user_id = $_SESSION['user_id'];
//        $conn = new mysqli("localhost", "root", "", "dams2");

// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
include "../config.php";

$sql = "SELECT COUNT(*) AS notification_count
FROM user_notifications un
LEFT JOIN notifications n ON n.notif_id = un.notif_id
WHERE un.user_id = '$user_id'
AND status = 0
;";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Access the data in each row using column names
        echo $row["notification_count"];
    }
} else {
    echo "Query failed: " . $conn->error;
}

$conn->close();


?>