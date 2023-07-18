<?php 

$id = $_GET['id'];

$url = "example.php?id=" . $id;
header("Location: $url");

?>