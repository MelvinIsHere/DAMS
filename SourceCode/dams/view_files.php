<?php
session_start();

$file_id = $_GET['file_id'];

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
  ft.`directory`,
  ft.file_name
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
    $file_name = $row->file_name;

    // Check if the file exists
    if (file_exists($file)) {
        // Add header to load pdf file
        header('Content-type: application/xlsx');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
        header('Content-Disposition: attachment; filename="' . $file_name . '"'); // Set the filename in the header

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
