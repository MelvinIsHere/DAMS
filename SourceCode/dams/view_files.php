<?php 

session_start();



$id = $_REQUEST['id'];

// Database Connection 
$conn = new mysqli('localhost', 'root', '', 'dams2');
//Check for connection error
$select = "SELECT * FROM `file_table` where file_id = '$id'";
$result = $conn->query($select);
while($row = $result->fetch_object()){
  
  $path = $row->directory . "";
  
  $file = $path;
}
// Add header to load pdf file
header('Content-type: application/xlsx'); 
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Transfer-Encoding: binary'); 
header('Accept-Ranges: bytes'); 
@readfile($file);  
?>
