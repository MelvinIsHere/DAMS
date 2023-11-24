<?php
//call the autoload
session_start();

require 'vendor/autoload.php';
include "../class_schedule_functions.php";


use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
//load phpspreadsheet class using namespaces
use PhpOffice\PhpSpreadsheet\Spreadsheet;
//call iofactory instead of xlsx writer
use PhpOffice\PhpSpreadsheet\IOFactory;
       use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use PhpOffice\PhpSpreadsheet\Worksheet\ColumnDimension;






$user_id = $_GET['user_id'];

$department_name = $_GET['department_name'];
$mon_start_morning = $_GET['start_morning_mon'];
$mon_end_morning = $_GET['end_morning_mon'];
$mon_start_noon = $_GET['start_noon_mon'];
$mon_end_noon = $_GET['end_noon_mon'];

$tues_start_morning = $_GET['start_morning_tues'];
$tues_end_morning = $_GET['end_morning_tues'];
$tues_start_noon = $_GET['start_noon_tues'];
$tues_end_noon = $_GET['end_noon_tues'];

$wed_start_morning = $_GET['start_morning_wed'];
$wed_end_morning = $_GET['end_morning_wed'];
$wed_start_noon = $_GET['start_noon_wed'];
$wed_end_noon = $_GET['end_noon_wed'];

$thurs_start_morning = $_GET['start_morning_thurs'];
$thurs_end_morning = $_GET['end_morning_thurs'];
$thurs_start_noon = $_GET['start_noon_thurs'];
$thurs_end_noon = $_GET['end_noon_thurs'];

$fri_start_morning = $_GET['start_morning_fri'];
$fri_end_morning = $_GET['end_morning_fri'];
$fri_start_noon = $_GET['start_noon_fri'];
$fri_end_noon = $_GET['end_noon_fri'];

$sat_start_morning = $_GET['start_morning_sat'];
$sat_end_morning = $_GET['end_morning_sat'];
$sat_start_noon = $_GET['start_noon_sat'];
$sat_end_noon = $_GET['end_noon_sat'];

$sun_start_morning = $_GET['start_morning_sun'];
$sun_end_morning = $_GET['end_morning_sun'];
$sun_start_noon = $_GET['start_noon_sun'];
$sun_end_noon = $_GET['end_noon_sun'];

$reader = IOFactory::createReader('Xlsx');
$spreadsheet = $reader->load("../../templates/rcot_templates.xlsx");

//add the content
//data from database

$conn = new mysqli("localhost", "root", "", "dams2");
if(!$conn){
    exit("database connection error");
}


$spreadsheet->getActiveSheet()->setCellValue('D2',$department_name);
$spreadsheet->getActiveSheet()->mergeCells('D2:E2');
$spreadsheet->getActiveSheet()->getStyle('D2')->getAlignment()->setWrapText(true); 
$spreadsheet->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal('center');
$cellValue = $spreadsheet->getActiveSheet()->getCell('D2')->getValue();

// Get the row index from the cell address 'D2'
$rowIndex = $spreadsheet->getActiveSheet()->getCell('D2')->getRow();

$textLength = strlen($cellValue);

// Calculate approximate pixel height (adjust the multiplier as needed based on your font size and style)
$rowHeight = ceil($textLength / 10) * 15; // Assuming 10 characters per line and 15 pixels per line height

// Set the row height for the row containing cell D2
$spreadsheet->getActiveSheet()->getRowDimension($rowIndex)->setRowHeight($rowHeight);



$query = mysqli_query($conn,"SELECT 
	ot.`official_id`,
	ot.`time_start`,
	ot.`time_end`,
	ot.`day`,
	ot.`day_sched`	

FROM official_time ot
LEFT JOIN tasks tt ON tt.`task_id` = ot.`task_id`
LEFT JOIN faculties f ON f.faculty_id = ot.faculty_id
LEFT JOIN users u ON u.faculty_id = f.faculty_id
WHERE tt.`term_id` = 9 AND u.user_id = '$user_id'");
if($query){
	while($row = mysqli_fetch_assoc($query)){
		
		$day_sched = $row['day_sched'];
		$time_start = $row['time_start'];
		$time_end = $row['time_end'];
		$day = $row['day'];
		if($day_sched == 'Official time morning'){
				if($day == "Monday"){
					$spreadsheet->getActiveSheet()->setCellValue('B6',$time_start);
					$spreadsheet->getActiveSheet()->setCellValue('C6',$time_end);
				}elseif($day == 'Tuesday'){
					$spreadsheet->getActiveSheet()->setCellValue('B7',$time_start);
					$spreadsheet->getActiveSheet()->setCellValue('C7',$time_end);
				}elseif($day == 'Wednesday'){
					$spreadsheet->getActiveSheet()->setCellValue('B8',$time_start);
					$spreadsheet->getActiveSheet()->setCellValue('C8',$time_end);
				}elseif($day == 'Thursday'){
					$spreadsheet->getActiveSheet()->setCellValue('B9',$time_start);
					$spreadsheet->getActiveSheet()->setCellValue('C9',$time_end);
				}elseif($day == 'Friday'){
					$spreadsheet->getActiveSheet()->setCellValue('B10',$time_start);
					$spreadsheet->getActiveSheet()->setCellValue('C10',$time_end);
				}elseif($day == 'Saturday'){
					$spreadsheet->getActiveSheet()->setCellValue('B11',$time_start);
					$spreadsheet->getActiveSheet()->setCellValue('C11',$time_end);
				}elseif($day == 'Sunday'){
					$spreadsheet->getActiveSheet()->setCellValue('B12',$time_start);
					$spreadsheet->getActiveSheet()->setCellValue('C12',$time_end);
				}
				
		}else{
			if($day == 'Monday'){
				$spreadsheet->getActiveSheet()->setCellValue('D6',$time_start);
				$spreadsheet->getActiveSheet()->setCellValue('E6',$time_end);
			}elseif($day == 'Tuesday'){
					$spreadsheet->getActiveSheet()->setCellValue('D7',$time_start);
					$spreadsheet->getActiveSheet()->setCellValue('E7',$time_end);
			}elseif($day == 'Wednesday'){
					$spreadsheet->getActiveSheet()->setCellValue('D8',$time_start);
					$spreadsheet->getActiveSheet()->setCellValue('E8',$time_end);
			}elseif($day == 'Thursday'){
					$spreadsheet->getActiveSheet()->setCellValue('D9',$time_start);
					$spreadsheet->getActiveSheet()->setCellValue('E9',$time_end);
			}elseif($day == 'Friday'){
					$spreadsheet->getActiveSheet()->setCellValue('D10',$time_start);
					$spreadsheet->getActiveSheet()->setCellValue('E10',$time_end);
			}elseif($day == 'Saturday'){
					$spreadsheet->getActiveSheet()->setCellValue('D11',$time_start);
					$spreadsheet->getActiveSheet()->setCellValue('E11',$time_end);
			}elseif($day == 'Sunday'){
					$spreadsheet->getActiveSheet()->setCellValue('D12',$time_start);
					$spreadsheet->getActiveSheet()->setCellValue('E12',$time_end);
			}
	
		}
	
		
	}
}else{

}


//insertion of requested change of official time plotting
//monday
$spreadsheet->getActiveSheet()->setCellValue('B16',$mon_start_morning);
$spreadsheet->getActiveSheet()->setCellValue('C16',$mon_end_morning);
$spreadsheet->getActiveSheet()->setCellValue('D16',$mon_start_noon);
$spreadsheet->getActiveSheet()->setCellValue('E16',$mon_end_noon);
//tuesday
$spreadsheet->getActiveSheet()->setCellValue('B17',$tues_start_morning);
$spreadsheet->getActiveSheet()->setCellValue('C17',$tues_end_morning);
$spreadsheet->getActiveSheet()->setCellValue('D17',$tues_start_noon);
$spreadsheet->getActiveSheet()->setCellValue('E17',$tues_end_noon);
//wednesday
$spreadsheet->getActiveSheet()->setCellValue('B18',$wed_start_morning);
$spreadsheet->getActiveSheet()->setCellValue('C18',$wed_end_morning);
$spreadsheet->getActiveSheet()->setCellValue('D18',$wed_start_noon);
$spreadsheet->getActiveSheet()->setCellValue('E18',$wed_end_noon);
//thursday
$spreadsheet->getActiveSheet()->setCellValue('B19',$thurs_start_morning);
$spreadsheet->getActiveSheet()->setCellValue('C19',$thurs_end_morning);
$spreadsheet->getActiveSheet()->setCellValue('D19',$thurs_start_noon);
$spreadsheet->getActiveSheet()->setCellValue('E19',$thurs_end_noon);
//friday
$spreadsheet->getActiveSheet()->setCellValue('B20',$fri_start_morning);
$spreadsheet->getActiveSheet()->setCellValue('C20',$fri_end_morning);
$spreadsheet->getActiveSheet()->setCellValue('D20',$fri_start_noon);
$spreadsheet->getActiveSheet()->setCellValue('E20',$fri_end_noon);
//saturday
$spreadsheet->getActiveSheet()->setCellValue('B21',$sat_start_morning);
$spreadsheet->getActiveSheet()->setCellValue('C21',$sat_end_morning);
$spreadsheet->getActiveSheet()->setCellValue('D21',$sat_start_noon);
$spreadsheet->getActiveSheet()->setCellValue('E21',$sat_end_noon);
//sunday
$spreadsheet->getActiveSheet()->setCellValue('B22',$sun_start_morning);
$spreadsheet->getActiveSheet()->setCellValue('C22',$sun_end_morning);
$spreadsheet->getActiveSheet()->setCellValue('D22',$sun_start_noon);
$spreadsheet->getActiveSheet()->setCellValue('E22',$sun_end_noon);


//set the header first, so the result will be treated as an xlsx file.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

//make it an attachment so we can define filename
header('Content-Disposition: attachment;filename="Request of Change of Official Time.xlsx"');

//create IOFactory object
$writer = IOFactory::createWriter($spreadsheet, 'Xls');
//save into php output
$writer->save('php://output');



?>