<?php 
//call the autoload
session_start();
$dept_name = $_SESSION['dept_name'];
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



$reader = IOFactory::createReader('Xlsx');
$spreadsheet = $reader->load("../../templates/BatStateU-DOC-AF-03_Individual Performance Commitment and Review (IPCR)_Rev 01.xlsx");
$worksheet = $spreadsheet->getActiveSheet();

//add the content
//data from database

$conn = new mysqli("localhost", "root", "", "dams2");
if(!$conn){
    exit("database connection error");
}
// ...


$dept_id = $_GET['dept_id'];
$user_id = $_GET['user_id'];


$query = "SELECT f.firstname,
                 f.lastname,
                 f.middlename,
                 f.suffix,
                 dp.department_name
          FROM faculties f
          LEFT JOIN departments dp ON dp.department_id = f.department_id
          WHERE user_id = '$user_id'";
$user_text = mysqli_query($conn,$query);
if(mysqli_num_rows($user_text)>0){
       $row = mysqli_fetch_assoc($user_text);
       $name = $row['firstname']." ".$row['middlename']." ".$row['lastname']." ".$row['suffix'];
       $dept_name = $row['department_name'];


       $spreadsheet->getActiveSheet()
                     ->setCellValue('A5', "      I, $name  faculty member of the $dept_name commit to deliver and agree to be rated on the attainment of the following targets in accordance with the indicated measures for the period ___________________ to ________________, 20 ______.");
}












$row_start = 25;
$ipcr_query = "SELECT * FROM ipcr_table WHERE user_id = '$user_id' AND category = 'Instruction'";
$data = [];
$ipcr = mysqli_query($conn, $ipcr_query);
if(mysqli_num_rows($ipcr) > 0){
       while ($row = mysqli_fetch_array($ipcr)) {
              $mfo = $row['major_output'];
              $success_indicator =  $row['success_indicator'];
              $actual_accomplishment =  $row['actual_accomplishment'];
              $rating =  $row['rating'];
              $remarks =  $row['remarks'];
              $description =  $row['description'];
              $category =  $row['category'];
              // Append the current row's data to the $data array
              $data[] = array(
                      "mfo" => $mfo,
                     "success_indicator" => $success_indicator,
                     "actual_accomplishment" => $actual_accomplishment,
                     "rating" => $rating,
                     "remarks" => $remarks,
                     "description" => $description,
                     "category" => $category
              );
           
       }
       
       foreach($data as $row){
              $mfo = $row['mfo'];
              $success_indicator = $row['success_indicator'];
              $actual_accomplishment =  $row['actual_accomplishment'];
              $rating =  $row['rating'];
              $remarks =  $row['remarks'];
              $description =  $row['description'];
              $category =  $row['category'];
              
              $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_start+1)->getCalculatedValue();
              if($cellValueCurrent == "STRATEGIC"){
                     $spreadsheet->getActiveSheet()->insertNewRowBefore($row_start + 1, 1);
                    
              }
               $spreadsheet->getActiveSheet()
                     ->setCellValue('A'.$row_start, $mfo)
                     ->setCellValue('C'.$row_start, $success_indicator)
                     ->setCellValue('G'.$row_start, $row_start);
              
              $row_start++;
              

       }

    $spreadsheet->getActiveSheet()->removeRow($row_start, 1);
}else{
       echo "a";
}

$merge_end = $row_start;
$mergeStart = 25;

while ($mergeStart <= $merge_end) {
    // Calculate the height based on the content in column A
    $cellValue = $spreadsheet->getActiveSheet()->getCell('A' . $mergeStart)->getValue();
    $textLength = strlen($cellValue);
    // Calculate approximate pixel height (adjust the multiplier as needed based on your font size and style)
    $rowHeight = ceil($textLength / 10) * 15; // Assuming 10 characters per line and 15 pixels per line height

    // Set the row height
    $spreadsheet->getActiveSheet()->getRowDimension($mergeStart)->setRowHeight($rowHeight);

    // Merge cells and set text wrapping
    $spreadsheet->getActiveSheet()->mergeCells('A' . $mergeStart . ':B' . $mergeStart);
    $spreadsheet->getActiveSheet()->getStyle('A' . $mergeStart)->getAlignment()->setWrapText(true);

    $spreadsheet->getActiveSheet()->mergeCells('C' . $mergeStart . ':E' . $mergeStart);
    $spreadsheet->getActiveSheet()->getStyle('C' . $mergeStart)->getAlignment()->setWrapText(true);

    $mergeStart++;
}


























///strategic

$row_start_strat_research = $row_start + 2;
$ipcr_query_strat = "SELECT * FROM ipcr_table WHERE user_id = '$user_id' AND category = 'Strategic' AND description = 'Research'";
$data = [];
$ipcr_strat = mysqli_query($conn, $ipcr_query_strat);
if(mysqli_num_rows($ipcr) > 0){
       echo setResearchAsTitle($row_start_strat_research,$spreadsheet); //set the title as research  
       while ($row = mysqli_fetch_array($ipcr_strat)) {
              $mfo = $row['major_output'];
              $success_indicator =  $row['success_indicator'];
              $actual_accomplishment =  $row['actual_accomplishment'];
              $rating =  $row['rating'];
              $remarks =  $row['remarks'];
              $description =  $row['description'];
              $category =  $row['category'];
              // Append the current row's data to the $data array
              $data[] = array(
                      "mfo" => $mfo,
                     "success_indicator" => $success_indicator,
                     "actual_accomplishment" => $actual_accomplishment,
                     "rating" => $rating,
                     "remarks" => $remarks,
                     "description" => $description,
                     "category" => $category
              );
           
       }
       
       foreach($data as $row){
              $mfo = $row['mfo'];
              $success_indicator = $row['success_indicator'];
              $actual_accomplishment =  $row['actual_accomplishment'];
              $rating =  $row['rating'];
              $remarks =  $row['remarks'];
              $description =  $row['description'];
              $category =  $row['category'];
              
              $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_start_strat_research+1)->getCalculatedValue();
              if($cellValueCurrent == "SUPPORT"){
                     $spreadsheet->getActiveSheet()->insertNewRowBefore($row_start_strat_research + 1, 1);
                    
              }
               $spreadsheet->getActiveSheet()
                     ->setCellValue('A'.$row_start_strat_research, $mfo)
                     ->setCellValue('C'.$row_start_strat_research, $success_indicator);
              
              $row_start_strat_research++;
              

       }

             

                     
      
    
}else{
      
                     

}


//formatting
$formattng_end = $row_start_strat_research;
$formatting_start = $row_start + 2;

while ($formatting_start <= $formattng_end) {
    // Calculate the height based on the content in column A
    $cellValue = $spreadsheet->getActiveSheet()->getCell('A' . $formatting_start)->getValue();
    $textLength = strlen($cellValue);
    // Calculate approximate pixel height (adjust the multiplier as needed based on your font size and style)
    $rowHeight = ceil($textLength / 10) * 15; // Assuming 10 characters per line and 15 pixels per line height

    // Set the row height
    $spreadsheet->getActiveSheet()->getRowDimension($formatting_start)->setRowHeight($rowHeight);

    // Merge cells and set text wrapping
    $spreadsheet->getActiveSheet()->mergeCells('A' . $formatting_start . ':B' . $formatting_start);
    $spreadsheet->getActiveSheet()->getStyle('A' . $formatting_start)->getAlignment()->setWrapText(true);

    $spreadsheet->getActiveSheet()->mergeCells('C' . $formatting_start . ':E' . $formatting_start);
    $spreadsheet->getActiveSheet()->getStyle('C' . $formatting_start)->getAlignment()->setWrapText(true);

    $formatting_start++;

}



 













///strategic

$row_start_strat_extension = $row_start_strat_research;


$cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_start_strat_extension+1)->getCalculatedValue();
if($cellValueCurrent == "SUPPORT"){
       $spreadsheet->getActiveSheet()->insertNewRowBefore($row_start_strat_extension, 1);
       echo setExtensionAsTitle($row_start_strat_extension,$spreadsheet); //set the title as research 
       $row_start_strat_extension++;
}




$ipcr_query_strat = "SELECT * FROM ipcr_table WHERE user_id = '$user_id' AND category = 'Strategic' AND description = 'Extension'";
$data = [];
$ipcr_strat = mysqli_query($conn, $ipcr_query_strat);
if(mysqli_num_rows($ipcr) > 0){
      
       
        
       while ($row = mysqli_fetch_array($ipcr_strat)) {
              $mfo = $row['major_output'];
              $success_indicator =  $row['success_indicator'];
              $actual_accomplishment =  $row['actual_accomplishment'];
              $rating =  $row['rating'];
              $remarks =  $row['remarks'];
              $description =  $row['description'];
              $category =  $row['category'];
              // Append the current row's data to the $data array
              $data[] = array(
                      "mfo" => $mfo,
                     "success_indicator" => $success_indicator,
                     "actual_accomplishment" => $actual_accomplishment,
                     "rating" => $rating,
                     "remarks" => $remarks,
                     "description" => $description,
                     "category" => $category
              );
           
       } foreach($data as $row){
              $mfo = $row['mfo'];
              $success_indicator = $row['success_indicator'];
              $actual_accomplishment =  $row['actual_accomplishment'];
              $rating =  $row['rating'];
              $remarks =  $row['remarks'];
              $description =  $row['description'];
              $category =  $row['category'];
              
              $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_start_strat_extension+1)->getCalculatedValue();
              if($cellValueCurrent == "SUPPORT"){
                     $spreadsheet->getActiveSheet()->insertNewRowBefore($row_start_strat_extension + 1, 1);
                    
              }
               $spreadsheet->getActiveSheet()
                     ->setCellValue('A'.$row_start_strat_extension, $mfo)
                     ->setCellValue('C'.$row_start_strat_extension, $success_indicator);
              
              $row_start_strat_extension++;
              

       }

       
       

    
}else{
      
                     

}


//formatting
$formattng_end_ex = $row_start_strat_extension;
$formatting_start_ex = $row_start_strat_research;

while ($formatting_start_ex <= $formattng_end_ex) {
    // Calculate the height based on the content in column A
    $cellValue = $spreadsheet->getActiveSheet()->getCell('A' . $formatting_start_ex)->getValue();
    $textLength = strlen($cellValue);
    // Calculate approximate pixel height (adjust the multiplier as needed based on your font size and style)
    $rowHeight = ceil($textLength / 10) * 15; // Assuming 10 characters per line and 15 pixels per line height

    // Set the row height
    $spreadsheet->getActiveSheet()->getRowDimension($formatting_start_ex)->setRowHeight($rowHeight);

    // Merge cells and set text wrapping
    $spreadsheet->getActiveSheet()->mergeCells('A' . $formatting_start_ex . ':B' . $formatting_start_ex);
    $spreadsheet->getActiveSheet()->getStyle('A' . $formatting_start_ex)->getAlignment()->setWrapText(true);

    $spreadsheet->getActiveSheet()->mergeCells('C' . $formatting_start_ex . ':E' . $formatting_start_ex);
    $spreadsheet->getActiveSheet()->getStyle('C' . $formatting_start_ex)->getAlignment()->setWrapText(true);

    $formatting_start_ex++;

}





















///strategic

$row_start_strat_supp = $row_start_strat_extension+2;

$ipcr_query_strat = "SELECT * FROM ipcr_table WHERE user_id = '$user_id' AND category = 'Support'";
$data = [];
$ipcr_strat = mysqli_query($conn, $ipcr_query_strat);
if(mysqli_num_rows($ipcr) > 0){
      
       
        
       while ($row = mysqli_fetch_array($ipcr_strat)) {
              $mfo = $row['major_output'];
              $success_indicator =  $row['success_indicator'];
              $actual_accomplishment =  $row['actual_accomplishment'];
              $rating =  $row['rating'];
              $remarks =  $row['remarks'];
              $description =  $row['description'];
              $category =  $row['category'];
              // Append the current row's data to the $data array
              $data[] = array(
                      "mfo" => $mfo,
                     "success_indicator" => $success_indicator,
                     "actual_accomplishment" => $actual_accomplishment,
                     "rating" => $rating,
                     "remarks" => $remarks,
                     "description" => $description,
                     "category" => $category
              );
           
       } foreach($data as $row){
              $mfo = $row['mfo'];
              $success_indicator = $row['success_indicator'];
              $actual_accomplishment =  $row['actual_accomplishment'];
              $rating =  $row['rating'];
              $remarks =  $row['remarks'];
              $description =  $row['description'];
              $category =  $row['category'];
              
              $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_start_strat_supp+1)->getCalculatedValue();
              if($cellValueCurrent == "Final Average Rating"){
                     $spreadsheet->getActiveSheet()->insertNewRowBefore($row_start_strat_supp + 1, 1);
                    
              }
               $spreadsheet->getActiveSheet()
                     ->setCellValue('A'.$row_start_strat_supp, $mfo)
                     ->setCellValue('C'.$row_start_strat_supp, $success_indicator);
              
              $row_start_strat_supp++;
              

       }

       
       

    
}else{
      
                     

}


//formatting
$formattng_end_supp = $row_start_strat_supp;
$formatting_start_supp = $row_start_strat_extension+1;

while ($formatting_start_supp <= $formattng_end_supp) {
    // Calculate the height based on the content in column A
    $cellValue = $spreadsheet->getActiveSheet()->getCell('A' . $formatting_start_supp)->getValue();
    $textLength = strlen($cellValue);
    // Calculate approximate pixel height (adjust the multiplier as needed based on your font size and style)
    $rowHeight = ceil($textLength / 10) * 15; // Assuming 10 characters per line and 15 pixels per line height

    // Set the row height
    $spreadsheet->getActiveSheet()->getRowDimension($formatting_start_supp)->setRowHeight($rowHeight);

    // Merge cells and set text wrapping
    $spreadsheet->getActiveSheet()->mergeCells('A' . $formatting_start_supp . ':B' . $formatting_start_supp);
    $spreadsheet->getActiveSheet()->getStyle('A' . $formatting_start_supp)->getAlignment()->setWrapText(true);

    $spreadsheet->getActiveSheet()->mergeCells('C' . $formatting_start_supp . ':E' . $formatting_start_supp);
    $spreadsheet->getActiveSheet()->getStyle('C' . $formatting_start_supp)->getAlignment()->setWrapText(true);

    $formatting_start_supp++;

}








function setResearchAsTitle($row_start_strat_research,$spreadsheet){
       $spreadsheet->getActiveSheet()
                     ->setCellValue('A'.($row_start_strat_research-1), 'RESEARCH');
       $spreadsheet->getActiveSheet()->unmergeCells('A' .($row_start_strat_research-1) . ':B' . ($row_start_strat_research-1));
       $spreadsheet->getActiveSheet()->getCell('A'.($row_start_strat_research-1))->getStyle()->getAlignment()->setIndent(0);
       $spreadsheet->getActiveSheet()->mergeCells('B' .($row_start_strat_research-1) . ':N' . ($row_start_strat_research-1));
       $spreadsheet->getActiveSheet()->getCell('A'.($row_start_strat_research-1))->getStyle()->getFont()->setSize(12);
       
       
       // $spreadsheet->getActiveSheet()->mergeCells('A' .($row_start_strat_research-1) . ':N' . ($row_start_strat_research-1));
       // $spreadsheet->getActiveSheet()->getStyle('A' .($row_start_strat_research-1))->getAlignment()
       //               ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
       
       
       

       $spreadsheet->getActiveSheet()->getCell('A'.($row_start_strat_research-1))->getStyle()->getFont()->setBold(true);
}
function setExtensionAsTitle($row_start_strat_extension,$spreadsheet){

       $spreadsheet->getActiveSheet()
                     ->setCellValue('A'.$row_start_strat_extension, 'EXTENSION ACTIVITIES');
       // $spreadsheet->getActiveSheet()->unmergeCells('A' .$row_start_strat_extension . ':B' . $row_start_strat_extension);
       $spreadsheet->getActiveSheet()->getCell('A'.$row_start_strat_extension)->getStyle()->getAlignment()->setIndent(0);
       $spreadsheet->getActiveSheet()->mergeCells('B' .$row_start_strat_extension . ':N' . $row_start_strat_extension);
       $spreadsheet->getActiveSheet()->getCell('A'.$row_start_strat_extension)->getStyle()->getFont()->setSize(12);
       
       
       // $spreadsheet->getActiveSheet()->mergeCells('A' .($row_start_strat_research-1) . ':N' . ($row_start_strat_research-1));
       // $spreadsheet->getActiveSheet()->getStyle('A' .($row_start_strat_research-1))->getAlignment()
       //               ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
       
       
       

       $spreadsheet->getActiveSheet()->getCell('A'.$row_start_strat_extension)->getStyle()->getFont()->setBold(true);



}
        
        
        
  
      



//set the header first, so the result will be treated as an xlsx file.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

//make it an attachment so we can define filename
header('Content-Disposition: attachment;filename="BatStateU-FO-COL-29_Class.xlsx"');

//create IOFactory object
$writer = IOFactory::createWriter($spreadsheet, 'Xls');
//save into php output
$writer->save('php://output');

?>