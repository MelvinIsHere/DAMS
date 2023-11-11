<?php
session_start();
$user_id = $_SESSION['user_id'];

include "../config.php";
$sql = "UPDATE user_notifications SET status = 1 WHERE user_id = '$user_id'";
if ($conn->query($sql) === TRUE) {
    echo "Notification status updated successfully";
} else {
    echo "Error updating notification status: " . $conn->error;
}

$conn->close();
?>
