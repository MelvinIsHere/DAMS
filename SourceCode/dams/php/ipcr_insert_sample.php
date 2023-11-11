<?php
session_start();
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $mfo = $_POST['mfo'];
    $success_indicators = $_POST['success_indicator'];
    $categories = $_POST['category'];
    $descriptions = $_POST['description'];

    // Create an empty array to store IPCR data
    $ipcr_arrays = [];

    // Iterate through the 'mfo' array and create an associative array
    foreach($mfo as $key => $value) {
        // Check if all required fields are set
        if(isset($mfo[$key], $success_indicators[$key], $categories[$key], $descriptions[$key])) {
            // Create an associative array with 'mfo', 'success_indicator', 'category', and 'description' fields
            $ipcr_data = [
                'mfo' => $mfo[$key],
                'success_indicator' => $success_indicators[$key],
                'category' => $categories[$key],
                'description' => $descriptions[$key]
            ];
            // Add the associative array to the $ipcr_arrays
            $ipcr_arrays[] = $ipcr_data;
        } else {
            // Handle the case when any of the required fields are missing for a specific 'mfo'
            echo "Error: Missing data for 'mfo' with index $key";
        }
    }

    // Now, $ipcr_arrays contains the data for 'mfo', 'success_indicator', 'category', and 'description' in the same array.
    // You can use $ipcr_arrays to perform further processing, such as inserting data into the database, etc.

    // Print the received data for testing purposes
    print_r($ipcr_arrays);



    
} else {
    echo "Invalid request";
}
?>
