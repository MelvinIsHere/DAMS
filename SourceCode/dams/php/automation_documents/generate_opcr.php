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
$spreadsheet = $reader->load("../../templates/opcr.xlsx");
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
$term_id =$_GET['term_id'];




$row_start = 25;

///strategic

$row_start_admin_strat = $row_start + 2;
$opcrt_query_strat = "SELECT * FROM opcr WHERE dean_id = '$dean_id' AND category = 'ADMINISTRATIV/STRATEGIC FUNCTION' AND description = 'HIGHER EDUCATION'";
$data = [];
$opcr_strat_admin = mysqli_query($conn, $opcrt_query_strat);
if(mysqli_num_rows($opcr_strat_admin) > 0){
       echo setHigherEducationAsTitle($row_start_admin_strat,$spreadsheet); //set the title as research  
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



 


















$row_start_research = $row_start_admin_strat;


//for research
$cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_start_research+1)->getCalculatedValue();
if($cellValueCurrent == "CORE FUNCTIONS"){
       $spreadsheet->getActiveSheet()->insertNewRowBefore($row_start_research, 1);
       echo setResearchAsTitle($row_start_research,$spreadsheet); //set the title as research  
       $row_start_research++;
}
$opcr_query_research = "SELECT * FROM opcr WHERE dean_id = '$dean_id' AND category = 'ADMINISTRATIV/STRATEGIC FUNCTION' AND description = 'RESEARCH'";
$data = [];
$opcr_strat_research = mysqli_query($conn, $opcr_query_research);
if(mysqli_num_rows($opcr_strat_research) > 0){
       
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
$formatting_start = $row_start_admin_strat;

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



 




















function setResearchAsTitle($row_start_research,$spreadsheet){

       $spreadsheet->getActiveSheet()
                     ->setCellValue('A'.$row_start_research, 'RESEARCH');
       // $spreadsheet->getActiveSheet()->unmergeCells('A' .$row_start_strat_extension . ':B' . $row_start_strat_extension);
       $spreadsheet->getActiveSheet()->getCell('A'.$row_start_research)->getStyle()->getAlignment()->setIndent(0);
       $spreadsheet->getActiveSheet()->mergeCells('B' .$row_start_research . ':N' . $row_start_research);
       $spreadsheet->getActiveSheet()->getCell('A'.$row_start_research)->getStyle()->getFont()->setSize(12);
       
       
       // $spreadsheet->getActiveSheet()->mergeCells('A' .($row_start_strat_research-1) . ':N' . ($row_start_strat_research-1));
       // $spreadsheet->getActiveSheet()->getStyle('A' .($row_start_strat_research-1))->getAlignment()
       //               ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
       
       
       

       $spreadsheet->getActiveSheet()->getCell('A'.$row_start_research)->getStyle()->getFont()->setBold(true);



}
        
        








function setHigherEducationAsTitle($row_start_admin_strat,$spreadsheet){
       $spreadsheet->getActiveSheet()
                     ->setCellValue('A'.($row_start_admin_strat-1), 'HIGHER EDUCATION');
       // $spreadsheet->getActiveSheet()->unmergeCells('A' .($row_start_admin_strat-1) . ':B' . ($row_start_admin_strat-1));
       $spreadsheet->getActiveSheet()->getCell('A'.($row_start_admin_strat-1))->getStyle()->getAlignment()->setIndent(0);
       $spreadsheet->getActiveSheet()->mergeCells('B' .($row_start_admin_strat-1) . ':P' . ($row_start_admin_strat-1));
       $spreadsheet->getActiveSheet()->getCell('A'.($row_start_admin_strat-1))->getStyle()->getFont()->setSize(12);
       
       
       // $spreadsheet->getActiveSheet()->mergeCells('A' .($row_start_strat_research-1) . ':N' . ($row_start_strat_research-1));
       // $spreadsheet->getActiveSheet()->getStyle('A' .($row_start_strat_research-1))->getAlignment()
       //               ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
       
       
       

       $spreadsheet->getActiveSheet()->getCell('A'.($row_start_admin_strat-1))->getStyle()->getFont()->setBold(true);
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