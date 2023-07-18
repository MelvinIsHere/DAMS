<?php
include "config.php";

$id = $_GET['id'];

// Perform any necessary validation and security checks

$sql = mysqli_query($conn, "DELETE FROM faculty_loadings WHERE fac_load_id = $id");

if ($sql) {
  // Row deleted successfully
  echo "success";
} else {
  // Failed to delete the row
  echo "error";
}
?>
