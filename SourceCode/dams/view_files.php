<?php
session_start();

$id = $_REQUEST['id'];
$due_date = $_REQUEST['due_date'];
$status_id = $_GET['status_id'];
$task_name = $_GET['task_name'];
$file_id = $_GET['file_id'];
// Database Connection 
// $conn = new mysqli("localhost", "root", "", "dams2");
// // Check for connection error
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
include "php/config.php";
$select = "SELECT
  dp.`department_name`,
  dp.`department_abbrv`,
  u.user_id,
  u.type,
  u.email,
  tsd.is_completed,
  tsd.`status_id`,
  t.due_date,
  ft.file_id,
  ft.`directory`
FROM
  file_table ft
  LEFT JOIN users u
    ON u.user_id = ft.`file_owner_id`
  LEFT JOIN faculties f
    ON f.`faculty_id` = u.`faculty_id`
  LEFT JOIN departments dp
    ON dp.department_id = f.`department_id`
  LEFT JOIN task_status_deans tsd
    ON tsd.`status_id` = ft.`task_status_id`
  LEFT JOIN tasks t
    ON t.`task_id` = tsd.`task_id`
WHERE ft.file_id = '$file_id';
";
$result = $conn->query($select);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_object();
    $path = $row->directory;
    $file = $path . "";

    // Check if the file exists
    if (file_exists($file)) {
        // Add header to load pdf file
        header('Content-type: application/xlsx');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');

        // Disable output buffering to avoid interference with file downloads
        ob_end_clean();

        // Output the file
        readfile($file);
        exit;
    } else {
        header("Location: admin/submission_monitoring.php? Message : File not found.");
    }
} else {
    header("Location: admin/submission_monitoring.php? Message : File not found.");
}

$conn->close();
?>
