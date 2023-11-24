<?php 

$user_id = $_POST['user_id'];
$department_name = $_POST['department_name'];


$monday_start_morning = $_POST['Official_start_morning_mon'];
$monday_end_morning = $_POST['Official_end_morning_mon'];
// $monday_start_morning = date("h:i A", strtotime($monday_start_morning));
// $monday_end_morning = date("h:i A", strtotime($monday_end_morning));

$monday_start_noon = $_POST['Official_start_noon_mon'];
$monday_end_noon = $_POST['Official_end_noon_mon'];
// $monday_start_noon = date("h:i A", strtotime($monday_start_noon));
// $monday_end_noon = date("h:i A", strtotime($monday_end_noon));





$tues_start_morning = $_POST['Official_start_morning_tues'];
$tues_end_morning = $_POST['Official_end_morning_tues'];
// $tues_start_morning = date("h:i A", strtotime($tues_start_morning));
// $tues_end_morning = date("h:i A", strtotime($tues_end_morning));

$tues_start_noon = $_POST['Official_start_noon_tues'];
$tues_end_noon = $_POST['Official_end_noon_tues'];
// $tues_start_noon = date("h:i A", strtotime($tues_start_noon));
// $tues_end_noon = date("h:i A", strtotime($tues_end_noon));




$wed_start_morning = $_POST['Official_start_morning_wed'];
$wed_end_morning = $_POST['Official_end_morning_wed'];
// $wed_start_morning = date("h:i A", strtotime($wed_start_morning));
// $wed_end_morning = date("h:i A", strtotime($wed_end_morning));

$wed_start_noon = $_POST['Official_start_noon_wed'];
$wed_end_noon = $_POST['Official_end_noon_wed'];
// $wed_start_noon = date("h:i A", strtotime($wed_start_noon));
// $wed_end_noon = date("h:i A", strtotime($wed_end_noon));



$thurs_start_morning = $_POST['Official_start_morning_thurs'];
$thurs_end_morning = $_POST['Official_end_morning_thurs'];
// $thurs_start_morning = date("h:i A", strtotime($thurs_start_morning));
// $thurs_end_morning = date("h:i A", strtotime($thurs_end_morning));

$thurs_start_noon = $_POST['Official_start_noon_thurs'];
$thurs_end_noon = $_POST['Official_end_noon_thurs'];
// $thurs_start_noon = date("h:i A", strtotime($thurs_start_noon));
// $thurs_end_noon = date("h:i A", strtotime($thurs_end_noon));

$fri_start_morning = $_POST['Official_start_morning_fri'];
$fri_end_morning = $_POST['Official_end_morning_fri'];
// $fri_start_morning = date("h:i A", strtotime($fri_start_morning));
// $fri_end_morning = date("h:i A", strtotime($fri_end_morning));

$fri_start_noon = $_POST['Official_start_noon_fri'];
$fri_end_noon = $_POST['Official_end_noon_fri'];
// $fri_start_noon = date("h:i A", strtotime($fri_start_noon));
// $fri_end_noon = date("h:i A", strtotime($fri_end_noon));





$sat_start_morning = $_POST['Official_start_morning_sat'];
$sat_end_morning = $_POST['Official_end_morning_sat'];
// $sat_start_morning = date("h:i A", strtotime($sat_start_morning));
// $sat_end_morning = date("h:i A", strtotime($sat_end_morning));

$sat_start_noon = $_POST['Official_start_noon_sat'];
$sat_end_noon = $_POST['Official_end_noon_sat'];
// $sat_start_noon = date("h:i A", strtotime($sat_start_noon));
// $sat_end_noon = date("h:i A", strtotime($sat_end_noon));



$sun_start_morning = $_POST['Official_start_morning_sun'];
$sun_end_morning = $_POST['Official_end_morning_sun'];
// $sat_start_morning = date("h:i A", strtotime($sat_start_morning));
// $sat_end_morning = date("h:i A", strtotime($sat_end_morning));

$sun_start_noon = $_POST['Official_start_noon_sun'];
$sun_end_noon = $_POST['Official_end_noon_sun'];
// $sat_start_noon = date("h:i A", strtotime($sat_start_noon));
// $sat_end_noon = date("h:i A", strtotime($sat_end_noon));



header("Location: automation_documents/generate_rcot.php?start_morning_mon=$monday_start_morning&end_morning_mon=$monday_end_morning&department_name=$department_name&start_noon_mon=$monday_start_noon&end_noon_mon=$monday_end_noon&start_morning_tues=$tues_start_morning&end_morning_tues=$tues_end_morning&start_noon_tues=$tues_start_noon&end_noon_tues=$tues_end_noon&start_morning_wed=$wed_start_morning&end_morning_wed=$wed_end_morning&start_noon_wed=$wed_start_noon&end_noon_wed=$wed_end_noon&start_morning_thurs=$thurs_start_morning&end_morning_thurs=$thurs_end_morning&start_noon_thurs=$thurs_start_noon&end_noon_thurs=$thurs_end_noon&start_morning_fri=$fri_start_morning&end_morning_fri=$fri_end_morning&start_noon_fri=$fri_start_noon&end_noon_fri=$fri_end_noon&start_morning_sat=$sat_start_morning&end_morning_sat=$sat_end_morning&start_noon_sat=$sat_start_noon&end_noon_sat=$sat_end_noon&start_morning_sun=$sun_start_morning&end_morning_sun=$sun_end_morning&start_noon_sun=$sun_start_noon&end_noon_sun=$sun_end_noon&user_id=$user_id");
?>