<?php
session_start();

$id = $_REQUEST['id'];
$due_date = $_REQUEST['due_date'];
$status_id = $_GET['status_id'];
// Database Connection 
// $conn = new mysqli("localhost", "root", "", "dams2");
// // Check for connection error
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
include "php/config.php";
$select = "SELECT 
    ft.`file_id`,
    ft.directory,
    ft.`file_owner_id` ,
    u.`email`,
    u.`type`,
    dp.`department_name` ,
    ts.`is_completed`,  
    ts.`status_id`,
    t.`due_date`,
    t.`task_name`
FROM `file_table` ft
LEFT JOIN `users` u  ON u.`user_id` = ft.`file_owner_id`
LEFT JOIN departments dp ON dp.`department_id` = u.`department_id`
LEFT JOIN task_status_deans ts ON ts.`office_id` = dp.`department_id`
LEFT JOIN tasks t ON t.`task_id` = ts.`task_id`
LEFT JOIN document_templates dt ON dt.`doc_template_id` = t.`document_id`
WHERE t.`task_name` = 'Class Schedule' AND u.`type` != 'Staff'
AND ts.`is_completed` = 0
AND t.due_date = '$due_date'
AND ts.status_id = '$status_id'


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
