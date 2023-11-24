<?php 
//call the autoload
session_start();
$dept_name = $_SESSION['dept_name'];
require 'vendor/autoload.php';
// include "../class_schedule_functions.php";


use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
//load phpspreadsheet class using namespaces
use PhpOffice\PhpSpreadsheet\Spreadsheet;
//call iofactory instead of xlsx writer
use PhpOffice\PhpSpreadsheet\IOFactory;
       use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use PhpOffice\PhpSpreadsheet\Worksheet\ColumnDimension;



$reader = IOFactory::createReader('Xlsx');
$spreadsheet = $reader->load("../../../templates/opcr.xlsx");
$worksheet = $spreadsheet->getActiveSheet();

//add the content
//data from database

$conn = new mysqli("localhost", "root", "", "dams2");
if(!$conn){
    exit("database connection error");
}
// ...


$dept_id = $_GET['dept_id'];
$dean_id = $_SESSION['user_id'];
$term_id = $_GET['term_id'];
ob_start();
$task_id = get_task_id($term_id);
$start_of_term = get_start_of_term($term_id);
$end_of_term = get_end_of_term($term_id);
$user_id = $_GET['user_id'];
$type = $_GET['type'];
$task_id_new = ob_get_clean();


$supervisor = $_GET['supervisor'];


//plot supervisor
$spreadsheet->getActiveSheet()->setCellValue('B12',$supervisor);

$query = mysqli_query($conn,"
          SELECT f.firstname,
                 f.lastname,
                 f.middlename,
                 f.suffix,
                 dp.department_name
          FROM faculties f
          LEFT JOIN departments dp ON dp.department_id = f.department_id
          LEFT JOIN users u ON u.faculty_id = f.`faculty_id`
          WHERE u.user_id = '$user_id'");
if($query){
       if(mysqli_num_rows($query)>0){
              $row = mysqli_fetch_assoc($query);
              $fullname = $row['firstname']." ".$row['middlename']." ".$row['lastname']." ".$row['suffix'];
              $unit_head = strtoupper($fullname);

              $dept_name = $row['department_name'];
              $spreadsheet->getActiveSheet()->setCellValue('A4',"I, $fullname, $type of $dept_name, commit to deliver and agree to be rated on the attainment of the following targets in accordance with the indicated measures for the period $start_of_term to $end_of_term.")
              ->setCellValue('J6',$unit_head);

       }else{
            
       }
}else{
       
}















$row_start = 25;

///strategic
$administrative = false;
$row_start_admin_strat = $row_start + 1;
$opcrt_query_strat = "SELECT * FROM opcr WHERE dean_id = '$dean_id' AND category = 'ADMINISTRATIV/STRATEGIC FUNCTION' 
                     AND task_id = '$task_id' AND dean_id = '$user_id'";
$data = [];
$opcr_strat_admin = mysqli_query($conn, $opcrt_query_strat);
if(mysqli_num_rows($opcr_strat_admin) > 0){
       $administrative = true;
        
       $row_start_admin_strat++;
       while ($row = mysqli_fetch_array($opcr_strat_admin)) {
              $mfo = $row['mfo_ppa'];
              $success_indicator =  $row['success_indicator'];
              $actual_accomplishment =  $row['actual_accomplishment'];
              $quality =  $row['quality'];
              $efficiency =  $row['efficiency'];
              $timeliness =  $row['timeliness'];
              $remarks =  $row['remarks'];
              $description =  $row['description'];
              $category =  $row['category'];
              $budget = $row['budgets'];
              $accountable = $row['accountable'];
              // Append the current row's data to the $data array
              $data[] = array(
                      "mfo" => $mfo,
                     "success_indicator" => $success_indicator,
                     "actual_accomplishment" => $actual_accomplishment,
                     "quality" => $quality,
                     "efficiency" => $efficiency,
                     "timeliness" => $timeliness,
                     "remarks" => $remarks,
                     "description" => $description,
                     "category" => $category,
                     "budget" => $budget,
                     "accountable" => $accountable
              );
           
       }
       
       foreach($data as $row){
              $mfo = $row['mfo'];
              $success_indicator = $row['success_indicator'];
              $actual_accomplishment =  $row['actual_accomplishment'];
              $quality =  $row['quality'];
              $efficiency =  $row['efficiency'];
              $timeliness =  $row['timeliness'];
              $remarks =  $row['remarks'];
              $description =  $row['description'];
              $category =  $row['category'];
              $budget = $row['budget'];
              $accountable = $row['accountable'];
              
              $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_start_admin_strat+1)->getCalculatedValue();
              if($cellValueCurrent == "CORE FUNCTIONS"){
                     $spreadsheet->getActiveSheet()->insertNewRowBefore($row_start_admin_strat + 1, 1);
                    
              }
               $spreadsheet->getActiveSheet()
                     ->setCellValue('A'.$row_start_admin_strat, $mfo)
                     ->setCellValue('C'.$row_start_admin_strat, $success_indicator)
                     ->setCellValue('F'.$row_start_admin_strat, $budget)
                     ->setCellValue('G'.$row_start_admin_strat, $accountable)
                     ->setCellValue('I'.$row_start_admin_strat, $actual_accomplishment)
                     ->setCellValue('L'.$row_start_admin_strat, $quality)
                     ->setCellValue('M'.$row_start_admin_strat, $efficiency)
                     ->setCellValue('N'.$row_start_admin_strat, $timeliness);
              $quality_cell = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(12, $row_start_admin_strat)->getCalculatedValue();
              $efficiency_cell = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(13, $row_start_admin_strat)->getCalculatedValue();
              $timeliness_cell = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(14, $row_start_admin_strat)->getCalculatedValue();
               if(!empty($quality_cell) || !empty($efficiency_cell) || !empty($timeliness_cell)){
                     $spreadsheet->getActiveSheet()->setCellValue('O'.$row_start_admin_strat, "=AVERAGE(L$row_start_admin_strat:N$row_start_admin_strat)");
              }
                     
              
              $row_start_admin_strat++;
              

       }

             

                     
      
    
}else{
      
                     

}


//formatting
$formattng_end = $row_start_admin_strat;
$formatting_start = $row_start + 1;

while ($formatting_start <= $formattng_end) {
    // Calculate the height based on the content in column A
    $cellValue = $spreadsheet->getActiveSheet()->getCell('C' . $formatting_start)->getValue();
    $textLength = strlen($cellValue);
    // Calculate approximate pixel height (adjust the multiplier as needed based on your font size and style)
    $rowHeight = ceil($textLength / 10) * 6; // Assuming 10 characters per line and 15 pixels per line height

    // Set the row height
    $spreadsheet->getActiveSheet()->getRowDimension($formatting_start)->setRowHeight($rowHeight);

    // Merge cells and set text wrapping
    $spreadsheet->getActiveSheet()->mergeCells('A' . $formatting_start . ':B' . $formatting_start);
    $spreadsheet->getActiveSheet()->getStyle('A' . $formatting_start)->getAlignment()->setWrapText(true);

    $spreadsheet->getActiveSheet()->mergeCells('C' . $formatting_start . ':E' . $formatting_start);
        //actual accomplishment
       $spreadsheet->getActiveSheet()->mergeCells('I' . $formatting_start . ':K' . $formatting_start);
    $spreadsheet->getActiveSheet()->getStyle('C' . $formatting_start)->getAlignment()->setWrapText(true);
    $spreadsheet->getActiveSheet()->getStyle('F'.$formatting_start)->getAlignment()->setHorizontal('center');
    $spreadsheet->getActiveSheet()->getStyle('I'.$formatting_start)->getAlignment()->setHorizontal('center');

    $formatting_start++;

}



 

















//SUPPORT / CORE FUNCTION
$cellvalue = "";
$row_start_strat_supp = $row_start_admin_strat;
$row_start_strat_supp_content = $row_start_admin_strat;
while($cellvalue != "CORE FUNCTIONS"){
     $cellvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_start_strat_supp)->getCalculatedValue();
       $spreadsheet->getActiveSheet()
                    ->setCellValue('P'.$row_start_strat_supp, $row_start_strat_supp);
     $row_start_strat_supp++;
     $row_start_strat_supp_content++;
}

$support = false;
$opcr_query_core_function_support="SELECT * FROM opcr WHERE dean_id = '$dean_id' AND category = 'CORE FUNCTION' AND task_id = '$task_id' AND dean_id = '$user_id'";



$data = [];
$opcr_support = mysqli_query($conn, $opcr_query_core_function_support);
if(mysqli_num_rows($opcr_support) > 0){
     $support = true;
       while ($row = mysqli_fetch_array($opcr_support)) {
              $mfo = $row['mfo_ppa'];
              $success_indicator =  $row['success_indicator'];
              $actual_accomplishment =  $row['actual_accomplishment'];
               $quality =  $row['quality'];
              $efficiency =  $row['efficiency'];
              $timeliness =  $row['timeliness'];
              $remarks =  $row['remarks'];
              $description =  $row['description'];
              $category =  $row['category'];
              $accountable = $row['accountable'];
              // Append the current row's data to the $data array
              $data[] = array(
                      "mfo" => $mfo,
                     "success_indicator" => $success_indicator,
                     "actual_accomplishment" => $actual_accomplishment,
                      "quality" => $quality,
                     "efficiency" => $efficiency,
                     "timeliness" => $timeliness,
                     "remarks" => $remarks,
                     "description" => $description,
                     "category" => $category,
                     "accountable" => $accountable
              );
           
       }
       
       foreach($data as $row){
              $mfo = $row['mfo'];
              $success_indicator = $row['success_indicator'];
              $actual_accomplishment =  $row['actual_accomplishment'];
               $quality =  $row['quality'];
              $efficiency =  $row['efficiency'];
              $timeliness =  $row['timeliness'];
              $remarks =  $row['remarks'];
              $description =  $row['description'];
              $category =  $row['category'];
              $accountable = $row['accountable'];
              
              $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_start_strat_supp+2)->getCalculatedValue();
              if($cellValueCurrent == "Average Rating"){
                     $spreadsheet->getActiveSheet()->insertNewRowBefore($row_start_strat_supp + 1, 1);
                    
              }
               $spreadsheet->getActiveSheet()
                     ->setCellValue('A'.$row_start_strat_supp, $mfo)
                     ->setCellValue('C'.$row_start_strat_supp, $success_indicator)
                      ->setCellValue('F'.$row_start_strat_supp, $budget)
                      ->setCellValue('G'.$row_start_strat_supp, $accountable)
                     ->setCellValue('I'.$row_start_strat_supp, $actual_accomplishment)

                     ->setCellValue('L'.$row_start_strat_supp, $quality)
                     ->setCellValue('M'.$row_start_strat_supp, $efficiency)
                     ->setCellValue('N'.$row_start_strat_supp, $timeliness);

              $quality_cell = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(12, $row_start_strat_supp)->getCalculatedValue();
              $efficiency_cell = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(13, $row_start_strat_supp)->getCalculatedValue();
              $timeliness_cell = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(14, $row_start_strat_supp)->getCalculatedValue();
               if(!empty($quality_cell) || !empty($efficiency_cell) || !empty($timeliness_cell)){
                     $spreadsheet->getActiveSheet()->setCellValue('O'.$row_start_strat_supp, "=AVERAGE(L$row_start_strat_supp:N$row_start_strat_supp)");
              }
                     
              
              $row_start_strat_supp++;
              

       }

             

                     
      
    
}else{
      
                     

}
//formatting
$formattng_end = $row_start_strat_supp;
$formatting_start = $row_start_strat_supp_content;

while ($formatting_start <= $formattng_end) {
    // Calculate the height based on the content in column A
    $cellValue = $spreadsheet->getActiveSheet()->getCell('C' . $formatting_start)->getValue();
    $textLength = strlen($cellValue);
    // Calculate approximate pixel height (adjust the multiplier as needed based on your font size and style)
    $rowHeight = ceil($textLength / 10) * 6; // Assuming 10 characters per line and 15 pixels per line height

    // Set the row height
    $spreadsheet->getActiveSheet()->getRowDimension($formatting_start)->setRowHeight($rowHeight);

    // Merge cells and set text wrapping
    $spreadsheet->getActiveSheet()->mergeCells('A' . $formatting_start . ':B' . $formatting_start);
    $spreadsheet->getActiveSheet()->getStyle('A' . $formatting_start)->getAlignment()->setWrapText(true);
     //actual accomplishment
       $spreadsheet->getActiveSheet()->mergeCells('I' . $formatting_start . ':K' . $formatting_start);
    $spreadsheet->getActiveSheet()->mergeCells('C' . $formatting_start . ':E' . $formatting_start);
    $spreadsheet->getActiveSheet()->getStyle('C' . $formatting_start)->getAlignment()->setWrapText(true);
     $spreadsheet->getActiveSheet()->getStyle('F'.$formatting_start)->getAlignment()->setHorizontal('center');
    $spreadsheet->getActiveSheet()->getStyle('I'.$formatting_start)->getAlignment()->setHorizontal('center');

    $formatting_start++;

}




//setting header height
$highestRow = $spreadsheet->getActiveSheet()->getHighestRow();
for($row = 26; $row <= $highestRow; $row++){
       $cellValue = $spreadsheet->getActiveSheet()->getCell('A' . $row)->getValue();
       if($cellValue == "MFO 2 RESEARCH"){
              $spreadsheet->getActiveSheet()->setCellValue('X'.$row,"RESEARCH");
               $textLength = strlen($cellValue);
              // Calculate approximate pixel height (adjust the multiplier as needed based on your font size and style)
              $rowHeight = ceil($textLength / 10) * 10; // Assuming 10 characters per line and 15 pixels per line height
              $spreadsheet->getActiveSheet()->getRowDimension($row)->setRowHeight($rowHeight);
       }
        elseif($cellValue == "MFO 3 EXTENSION"){
              $spreadsheet->getActiveSheet()->setCellValue('X'.$row,"RESEARCH");
               $textLength = strlen($cellValue);
              // Calculate approximate pixel height (adjust the multiplier as needed based on your font size and style)
              $rowHeight = ceil($textLength / 10) * 10; // Assuming 10 characters per line and 15 pixels per line height
              $spreadsheet->getActiveSheet()->getRowDimension($row)->setRowHeight($rowHeight);
       }
}




   





       
        
        
function get_task_id($term_id){
       include "../../config.php";
       $query = mysqli_query($conn,"
                            SELECT 
                                   tt.task_id,
                                   tt.`task_name`
                            FROM tasks tt 
                            LEFT JOIN terms t ON t.`term_id` = tt.`term_id`
                            WHERE tt.`task_name` =  'OPCR' AND t.term_id = '$term_id'");
       if($query){
              if(mysqli_num_rows($query)>0){
                     $row = mysqli_fetch_assoc($query);
                     $task_id = $row['task_id'];

                     return $task_id; 
              }else{
                     return false;
              }
       }else{
              return false;
       }
}       
function get_start_of_term($term_id){
       include "../../config.php";
       $query = mysqli_query($conn,"SELECT start,year FROM terms WHERE term_id = '$term_id'");
       if($query){
              if(mysqli_num_rows($query)>0){
                     $row = mysqli_fetch_assoc($query);
                     $start = $row['start'] ." ".$row['year'];

                     return $start; 
              }else{
                     return false;
              }
       }else{
              return false;
       }
}
function get_end_of_term($term_id){
       include "../../config.php";
       $query = mysqli_query($conn,"SELECT end,year FROM terms WHERE term_id = '$term_id'");
       if($query){
              if(mysqli_num_rows($query)>0){
                     $row = mysqli_fetch_assoc($query);
                     $end = $row['end'] . " ".$row['year'];

                     return $end; 
              }else{
                     return false;
              }
       }else{
              return false;
       }
}

$highestRow = $spreadsheet->getActiveSheet()->getHighestRow();

if($support){
       // Loop through all rows
for ($row = 25; $row <= $highestRow; $row++) {
       $cellvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row)->getValue();
       if($cellvalue == "Average Rating"){
              $spreadsheet->getActiveSheet()->insertNewRowBefore($row-2, 1);
               $spreadsheet->getActiveSheet()
                     ->setCellValue('A'.($row-2), "AVERAGE FOR CORE FUNCTIONS");


               $spreadsheet->getActiveSheet()->getCell('A'.($row-2))->getStyle()->getAlignment()->setIndent(0);
              $spreadsheet->getActiveSheet()->mergeCells('A' .($row-2) . ':P' . ($row-2));
              $spreadsheet->getActiveSheet()->getCell('A'.$row)->getStyle()->getFont()->setSize(10);

              $cellValue = $spreadsheet->getActiveSheet()->getCell('A' .($row-2))->getValue();
           $textLength = strlen($cellValue);
           // Calculate approximate pixel height (adjust the multiplier as needed based on your font size and style)
           $rowHeight = ceil($textLength / 10) * 6; // Assuming 10 characters per line and 15 pixels per line height

           // Set the row height
           $spreadsheet->getActiveSheet()->getRowDimension(($row-2))->setRowHeight($rowHeight);

              break;      
       }
 
}
}
if($administrative){
       // Loop through all rows
for ($row = 25; $row <= $highestRow; $row++) {
       $cellvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row)->getValue();
       if($cellvalue == "CORE FUNCTIONS"){
              $spreadsheet->getActiveSheet()->insertNewRowBefore($row, 1);
               $spreadsheet->getActiveSheet()
                     ->setCellValue('A'.$row, "AVERAGE FOR ADMINISTRATIVE/STRATEGIC FUNCTIONS");


               $spreadsheet->getActiveSheet()->getCell('A'.$row)->getStyle()->getAlignment()->setIndent(0);
              $spreadsheet->getActiveSheet()->mergeCells('A' .$row . ':P' . $row);
              $spreadsheet->getActiveSheet()->getCell('A'.$row)->getStyle()->getFont()->setSize(10);

              $cellValue = $spreadsheet->getActiveSheet()->getCell('A' .$row)->getValue();
           $textLength = strlen($cellValue);
           // Calculate approximate pixel height (adjust the multiplier as needed based on your font size and style)
           $rowHeight = ceil($textLength / 10) * 4; // Assuming 10 characters per line and 15 pixels per line height

           // Set the row height
           $spreadsheet->getActiveSheet()->getRowDimension($row)->setRowHeight($rowHeight);

              break;      
       }
 
}
}

//set the header first, so the result will be treated as an xlsx file.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

//make it an attachment so we can define filename
header('Content-Disposition: attachment;filename="Office Performance Commitment Review'.$dept_name.'.xlsx"');

//create IOFactory object
$writer = IOFactory::createWriter($spreadsheet, 'Xls');
//save into php output
$writer->save('php://output');

?>