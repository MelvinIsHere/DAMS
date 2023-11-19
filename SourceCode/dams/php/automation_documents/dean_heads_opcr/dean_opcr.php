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
$higher_education = false;
$row_start_admin_strat = $row_start + 1;
$opcrt_query_strat = "SELECT * FROM opcr WHERE dean_id = '$dean_id' AND category = 'ADMINISTRATIV/STRATEGIC FUNCTION' 
AND description = 'MFO 1 HIGHER EDUCATION' AND task_id = '$task_id'";
$data = [];
$opcr_strat_admin = mysqli_query($conn, $opcrt_query_strat);
if(mysqli_num_rows($opcr_strat_admin) > 0){
       $higher_education = true;
       echo setHigherEducationAsTitle($row_start_admin_strat,$spreadsheet); //set the title as research  
       $row_start_admin_strat++;
       while ($row = mysqli_fetch_array($opcr_strat_admin)) {
              $mfo = $row['mfo_ppa'];
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
              
              $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_start_admin_strat+1)->getCalculatedValue();
              if($cellValueCurrent == "CORE FUNCTIONS"){
                     $spreadsheet->getActiveSheet()->insertNewRowBefore($row_start_admin_strat + 1, 1);
                    
              }
               $spreadsheet->getActiveSheet()
                     ->setCellValue('A'.$row_start_admin_strat, $mfo)
                     ->setCellValue('C'.$row_start_admin_strat, $success_indicator);
              
              $row_start_admin_strat++;
              

       }

             

                     
      
    
}else{
      
                     

}


//formatting
$formattng_end = $row_start_admin_strat;
$formatting_start = $row_start +2;

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
    $spreadsheet->getActiveSheet()->getStyle('C' . $formatting_start)->getAlignment()->setWrapText(true);

    $formatting_start++;

}



 


















$row_start_research = $row_start_admin_strat;

$research = false;

$opcr_query_research = "SELECT * FROM opcr WHERE dean_id = '$dean_id' AND category = 'ADMINISTRATIV/STRATEGIC FUNCTION' AND description = 'MFO 2 RESEARCH' AND task_id = '$task_id'";
$data = [];
$opcr_strat_research = mysqli_query($conn, $opcr_query_research);
if(mysqli_num_rows($opcr_strat_research) > 0){
       //for research
       $research = true;
       $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_start_research+1)->getCalculatedValue();
       if($cellValueCurrent == "CORE FUNCTIONS"){
              $spreadsheet->getActiveSheet()->insertNewRowBefore($row_start_research, 1);
              echo setResearchAsTitle($row_start_research,$spreadsheet); //set the title as research  
              $row_start_research++;
       }
       while ($row = mysqli_fetch_array($opcr_strat_research)) {
              $mfo = $row['mfo_ppa'];
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
              
              $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_start_research+1)->getCalculatedValue();
              if($cellValueCurrent == "CORE FUNCTIONS"){
                     $spreadsheet->getActiveSheet()->insertNewRowBefore($row_start_research + 1, 1);
                    
              }
               $spreadsheet->getActiveSheet()
                     ->setCellValue('A'.$row_start_research, $mfo)
                     ->setCellValue('C'.$row_start_research, $success_indicator);
              
              $row_start_research++;
              

       }

             

                     
      
    
}else{
      
                     

}


//formatting
$formattng_end = $row_start_research;
$formatting_start = $row_start_admin_strat +1;

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
    $spreadsheet->getActiveSheet()->getStyle('C' . $formatting_start)->getAlignment()->setWrapText(true);

    $formatting_start++;

}



 



//EXTENSION 

$row_start_extension = $row_start_research;
$row_start_extension_content = $row_start_research +1;
$opcr_query_extension = "SELECT * FROM opcr WHERE dean_id = '$dean_id' AND category = 'ADMINISTRATIV/STRATEGIC FUNCTION' AND description = 'MFO 3 EXTENSION' AND task_id = '$task_id'";

$extension = false;
$data = [];
$opcr_strat_extension = mysqli_query($conn, $opcr_query_extension);
if(mysqli_num_rows($opcr_strat_extension) > 0){
       $extension = true;
       //for research
       $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_start_extension+1)->getCalculatedValue();
       if($cellValueCurrent == "CORE FUNCTIONS"){
              $spreadsheet->getActiveSheet()->insertNewRowBefore($row_start_extension, 1);
              echo setExtensionAsTitle($row_start_extension,$spreadsheet); //set the title as research  
              $row_start_extension++;
       }
       while ($row = mysqli_fetch_array($opcr_strat_extension)) {
              $mfo = $row['mfo_ppa'];
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
              
              $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_start_extension+1)->getCalculatedValue();
              if($cellValueCurrent == "CORE FUNCTIONS"){
                     $spreadsheet->getActiveSheet()->insertNewRowBefore($row_start_extension + 1, 1);
                    
              }
               $spreadsheet->getActiveSheet()
                     ->setCellValue('A'.$row_start_extension, $mfo)
                     ->setCellValue('C'.$row_start_extension, $success_indicator);
              
              $row_start_extension++;
              

       }

             

                     
      
    
}else{
      
                     

}


//formatting
$formattng_end = $row_start_extension;
$formatting_start = $row_start_extension_content;

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
    $spreadsheet->getActiveSheet()->getStyle('C' . $formatting_start)->getAlignment()->setWrapText(true);

    $formatting_start++;

}






//SUPPORT / CORE FUNCTION
$cellvalue = "";
$row_start_strat_supp = $row_start_extension;
$row_start_strat_supp_content = $row_start_extension;
while($cellvalue != "CORE FUNCTIONS"){
     $cellvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_start_strat_supp)->getCalculatedValue();
       $spreadsheet->getActiveSheet()
                    ->setCellValue('P'.$row_start_strat_supp, $row_start_strat_supp);
     $row_start_strat_supp++;
     $row_start_strat_supp_content++;
}

$support = false;
$opcr_query_core_function_support="SELECT * FROM opcr WHERE dean_id = '$dean_id' AND category = 'CORE FUNCTION' AND task_id = '$task_id'";



$data = [];
$opcr_support = mysqli_query($conn, $opcr_query_core_function_support);
if(mysqli_num_rows($opcr_support) > 0){
     $support = true;
       while ($row = mysqli_fetch_array($opcr_support)) {
              $mfo = $row['mfo_ppa'];
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
              
              $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_start_strat_supp+2)->getCalculatedValue();
              if($cellValueCurrent == "Average Rating"){
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

    $spreadsheet->getActiveSheet()->mergeCells('C' . $formatting_start . ':E' . $formatting_start);
    $spreadsheet->getActiveSheet()->getStyle('C' . $formatting_start)->getAlignment()->setWrapText(true);

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



function setResearchAsTitle($row_start_research,$spreadsheet){

       $spreadsheet->getActiveSheet()
                     ->setCellValue('A'.$row_start_research, 'MFO 2 RESEARCH');
       $spreadsheet->getActiveSheet()->getStyle('A'.$row_start_research)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFC000');
       // $spreadsheet->getActiveSheet()->unmergeCells('A' .$row_start_strat_extension . ':B' . $row_start_strat_extension);
       $spreadsheet->getActiveSheet()->getCell('A'.$row_start_research)->getStyle()->getAlignment()->setIndent(0);
       $spreadsheet->getActiveSheet()->mergeCells('A' .$row_start_research . ':P' . $row_start_research);
       $spreadsheet->getActiveSheet()->getCell('A'.$row_start_research)->getStyle()->getFont()->setSize(12);
       
       
       // $spreadsheet->getActiveSheet()->mergeCells('A' .($row_start_strat_research-1) . ':N' . ($row_start_strat_research-1));
       // $spreadsheet->getActiveSheet()->getStyle('A' .($row_start_strat_research-1))->getAlignment()
       //               ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
       
       
       

       $spreadsheet->getActiveSheet()->getCell('A'.$row_start_research)->getStyle()->getFont()->setBold(true);



}
        
  
function setExtensionAsTitle($row_start_extension,$spreadsheet){

       $spreadsheet->getActiveSheet()
                     ->setCellValue('A'.$row_start_extension, 'MFO 3 EXTENSION');
       $spreadsheet->getActiveSheet()->getStyle('A'.$row_start_extension)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFC000');
       // $spreadsheet->getActiveSheet()->unmergeCells('A' .$row_start_strat_extension . ':B' . $row_start_strat_extension);
       $spreadsheet->getActiveSheet()->getCell('A'.$row_start_extension)->getStyle()->getAlignment()->setIndent(0);
       $spreadsheet->getActiveSheet()->mergeCells('A' .$row_start_extension . ':P' . $row_start_extension);
       $spreadsheet->getActiveSheet()->getCell('A'.$row_start_extension)->getStyle()->getFont()->setSize(12);
       
       
       // $spreadsheet->getActiveSheet()->mergeCells('A' .($row_start_strat_research-1) . ':N' . ($row_start_strat_research-1));
       // $spreadsheet->getActiveSheet()->getStyle('A' .($row_start_strat_research-1))->getAlignment()
       //               ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
       
       
       

       $spreadsheet->getActiveSheet()->getCell('A'.$row_start_extension)->getStyle()->getFont()->setBold(true);



}      








function setHigherEducationAsTitle($row_start_admin_strat,$spreadsheet){
       $spreadsheet->getActiveSheet()
                     ->setCellValue('A'.($row_start_admin_strat), 'MFO 1 HIGHER EDUCATION');
       $spreadsheet->getActiveSheet()->getStyle('A'.$row_start_admin_strat)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFC000');
       $spreadsheet->getActiveSheet()->mergeCells('A' .$row_start_admin_strat . ':P' . $row_start_admin_strat);
                     // $spreadsheet->getActiveSheet()->mergeCells('A' .$row_start_admin_strat . ':D' . $row_start_admin_strat);
       $spreadsheet->getActiveSheet()->getCell('A'.$row_start_admin_strat)->getStyle()->getAlignment()->setIndent(1);
       // $spreadsheet->getActiveSheet()->mergeCells('E' .$row_start_admin_strat . ':P' . $row_start_admin_strat);
       $spreadsheet->getActiveSheet()->getCell('A'.$row_start_admin_strat)->getStyle()->getFont()->setSize(12);
       
       
       // $spreadsheet->getActiveSheet()->mergeCells('A' .($row_start_strat_research-1) . ':N' . ($row_start_strat_research-1));
       // $spreadsheet->getActiveSheet()->getStyle('A' .($row_start_strat_research-1))->getAlignment()
       //               ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
       
       
       

       $spreadsheet->getActiveSheet()->getCell('A'.$row_start_admin_strat)->getStyle()->getFont()->setBold(true);
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

if($higher_education){
       // Loop through all rows
for ($row = 25; $row <= $highestRow; $row++) {
       $cellvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row)->getValue();
       if($cellvalue == "MFO 2 RESEARCH"){
              $spreadsheet->getActiveSheet()->insertNewRowBefore($row, 1);
               $spreadsheet->getActiveSheet()
                     ->setCellValue('A'.$row, "AVERAGE FOR HIGHER EDUCATION");

               $spreadsheet->getActiveSheet()->getCell('A'.$row)->getStyle()->getAlignment()->setIndent(0);
              $spreadsheet->getActiveSheet()->mergeCells('A' .$row . ':P' . $row);
              $spreadsheet->getActiveSheet()->getCell('A'.$row)->getStyle()->getFont()->setSize(10);

              $cellValue = $spreadsheet->getActiveSheet()->getCell('A' . $row)->getValue();
           $textLength = strlen($cellValue);
           // Calculate approximate pixel height (adjust the multiplier as needed based on your font size and style)
           $rowHeight = ceil($textLength / 10) * 8; // Assuming 10 characters per line and 15 pixels per line height

           // Set the row height
           $spreadsheet->getActiveSheet()->getRowDimension($row)->setRowHeight($rowHeight);

              break;      
       }
 
}
}
if($research){
       // Loop through all rows
for ($row = 25; $row <= $highestRow; $row++) {
       $cellvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row)->getValue();
       if($cellvalue == "MFO 3 EXTENSION"){
              $spreadsheet->getActiveSheet()->insertNewRowBefore($row, 1);
               $spreadsheet->getActiveSheet()
                     ->setCellValue('A'.$row, "AVERAGE FOR RESEARCH");

               $spreadsheet->getActiveSheet()->getCell('A'.$row)->getStyle()->getAlignment()->setIndent(0);
              $spreadsheet->getActiveSheet()->mergeCells('A' .$row . ':P' . $row);
              $spreadsheet->getActiveSheet()->getCell('A'.$row)->getStyle()->getFont()->setSize(10);

              $cellValue = $spreadsheet->getActiveSheet()->getCell('A' . $row)->getValue();
           $textLength = strlen($cellValue);
           // Calculate approximate pixel height (adjust the multiplier as needed based on your font size and style)
           $rowHeight = ceil($textLength / 10) * 8; // Assuming 10 characters per line and 15 pixels per line height

           // Set the row height
           $spreadsheet->getActiveSheet()->getRowDimension($row)->setRowHeight($rowHeight);

              break;      
       }
 
}
}
if($extension){
       // Loop through all rows
for ($row = 25; $row <= $highestRow; $row++) {
       $cellvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row)->getValue();
       if($cellvalue == "CORE FUNCTIONS"){
              $spreadsheet->getActiveSheet()->insertNewRowBefore($row, 1);
               $spreadsheet->getActiveSheet()
                     ->setCellValue('A'.$row, "AVERAGE FOR EXTENSION");

               $spreadsheet->getActiveSheet()->getCell('A'.$row)->getStyle()->getAlignment()->setIndent(0);
              $spreadsheet->getActiveSheet()->mergeCells('A' .$row . ':P' . $row);
              $spreadsheet->getActiveSheet()->getCell('A'.$row)->getStyle()->getFont()->setSize(10);

              $cellValue = $spreadsheet->getActiveSheet()->getCell('A' . $row)->getValue();
           $textLength = strlen($cellValue);
           // Calculate approximate pixel height (adjust the multiplier as needed based on your font size and style)
           $rowHeight = ceil($textLength / 10) * 8; // Assuming 10 characters per line and 15 pixels per line height

           // Set the row height
           $spreadsheet->getActiveSheet()->getRowDimension($row)->setRowHeight($rowHeight);

              break;      
       }
 
}
}
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
           $rowHeight = ceil($textLength / 10) * 8; // Assuming 10 characters per line and 15 pixels per line height

           // Set the row height
           $spreadsheet->getActiveSheet()->getRowDimension(($row-2))->setRowHeight($rowHeight);

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