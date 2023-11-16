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
$term_id = $_GET['term_id'];
ob_start();
$task_id = get_task_id($term_id);
$task_id_new = ob_get_clean();
$query = "

SELECT f.firstname,
                 f.lastname,
                 f.middlename,
                 f.suffix,
                 dp.department_name
          FROM faculties f
          LEFT JOIN departments dp ON dp.department_id = f.department_id
          LEFT JOIN users u ON u.faculty_id = f.`faculty_id`
          WHERE u.user_id = '$user_id' ";
$user_text = mysqli_query($conn,$query);
if(mysqli_num_rows($user_text)>0){
       $row = mysqli_fetch_assoc($user_text);
       $name = $row['firstname']." ".$row['middlename']." ".$row['lastname']." ".$row['suffix'];
       $dept_name = $row['department_name'];


       $spreadsheet->getActiveSheet()
                     ->setCellValue('A5', "      I, $name  faculty member of the $dept_name commit to deliver and agree to be rated on the attainment of the following targets in accordance with the indicated measures for the period ___________________ to ________________");
}


//ratee
$query = "SELECT f.firstname,
                 f.lastname,
                 f.middlename,
                 f.suffix,
                 dp.department_name
          FROM faculties f
          LEFT JOIN departments dp ON dp.department_id = f.department_id
          LEFT JOIN users u ON u.faculty_id = f.`faculty_id`
          WHERE u.user_id = '$user_id'";
$user_text = mysqli_query($conn,$query);
if(mysqli_num_rows($user_text)>0){
       $row = mysqli_fetch_assoc($user_text);
       $name = $row['firstname']." ".$row['middlename']." ".$row['lastname']." ".$row['suffix'];
       $name = strtoupper($name);
       


       $spreadsheet->getActiveSheet()
                     ->setCellValue('L7',$name);
}


//imidiate superviser

$query = " SELECT 
       f.`firstname`,
       f.lastname,
       f.middlename,
       f.suffix,
       dp.department_name,
       d.`designation`
 FROM faculties f
 LEFT JOIN users u ON u.faculty_id = f.`faculty_id`   
 LEFT JOIN departments dp ON dp.department_id = f.`department_id`
 LEFT JOIN faculty_designation fd ON fd.`faculty_id` = f.faculty_id
 LEFT JOIN designation d ON d.designation_id = fd.`designation_id`
 WHERE d.`designation` = 'Head' OR d.`designation`='Dean'
 AND dp.department_id = '$dept_id'";
 $result = mysqli_query($conn,$query);
 if($result){
       if(mysqli_num_rows($result)>0){
              $row = mysqli_fetch_assoc($result);
              $firstname = $row['firstname'];
              $last_name = $row['lastname'];
              $middle_name = $row['middlename'];
              $suffix = $row['suffix'];
              $fullname = $firstname." ".$middle_name." ".$last_name." ".$suffix;



              $spreadsheet->getActiveSheet()
                     ->setCellValue('A19',$fullname)
                     ->setCellValue('H19',$fullname);

       }else{

       }
 }else{

 }







$instruction = false;

$row_start = 25;
$row_start_content = 25;





$ipcr_query =  "SELECT
                    it.ipcr_id, 
                    it.major_output,
                    it.`success_indicator`,
                    it.`actual_accomplishment`,
                    it.`remarks`,
                    it.`description`,
                    it.`category`,
                    it.quality,
                    it.efficiency,
                    it.timeliness,
                    it.average,
                    tt.`task_id`,
                    t.`term`,
                    t.`status`                                                
              FROM ipcr_table it 
              LEFT JOIN tasks tt ON tt.task_id = it.task_id
              LEFT JOIN terms t ON t.`term_id` = tt.`term_id`
              WHERE it.user_id = '$user_id' AND tt.task_id = '$task_id' AND  it.category = 'Instruction'";
$data = [];
$ipcr = mysqli_query($conn, $ipcr_query);
if(mysqli_num_rows($ipcr) > 0){
       $instruction = true;
       while ($row = mysqli_fetch_array($ipcr)) {
              $mfo = $row['major_output'];
              $success_indicator =  $row['success_indicator'];
              $actual_accomplishment =  $row['actual_accomplishment'];
              $remarks = $row['remarks'];
              $quality = $row['quality'];
              $timeliness = $row['timeliness'];
              
              $efficiency =  $row['efficiency'];
              $description =  $row['description'];
              $category =  $row['category'];
              // Append the current row's data to the $data array
              $data[] = array(
                      "mfo" => $mfo,
                     "success_indicator" => $success_indicator,
                     "actual_accomplishment" => $actual_accomplishment,
                     "remarks" => $remarks,
                     "quality" => $quality,
                     "efficiency" => $efficiency,
                     "timeliness" => $timeliness,
                     "description" => $description,
                     "category" => $category
              );
           
       }
       
       foreach($data as $row){
              $mfo = $row['mfo'];
              $success_indicator = $row['success_indicator'];
              $actual_accomplishment =  $row['actual_accomplishment'];
              $remarks = $row['remarks'];
              
              $quality =  $row['quality'];
              $efficiency =  $row['efficiency'];
              $timeliness =  $row['timeliness'];
              $description =  $row['description'];
              $category =  $row['category'];
              
              $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_start+1)->getCalculatedValue();
              if($cellValueCurrent == "STRATEGIC"){
                     $spreadsheet->getActiveSheet()->insertNewRowBefore($row_start + 1, 1);
                    
              }
              // $count_to_average = 0;
              // if(!empty($quality)){
              //        $count_to_average++;
              // }
              // if(!empty($efficiency)){
              //        $count_to_average++;
              // }
              // if(!empty($timeliness)){
              //        $count_to_average++;
              // }
           
            $spreadsheet->getActiveSheet()
                  ->setCellValue('A'.$row_start, $mfo)
                  ->setCellValue('C'.$row_start, $success_indicator)
                  ->setCellValue('F'.$row_start, $actual_accomplishment)
                  ->setCellValue('I'.$row_start, $quality)
                  ->setCellValue('J'.$row_start, $efficiency)
                  ->setCellValue('K'.$row_start, $timeliness)
                  
                  ->setCellValue('M'.$row_start, $remarks);
              $quality_cell = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(9, $row_start)->getCalculatedValue();
              $efficiency_cell = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(10, $row_start)->getCalculatedValue();
              $timeliness_cell = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(11, $row_start)->getCalculatedValue();
              if(!empty($quality_cell) || !empty($efficiency_cell) || !empty($timeliness_cell)){
                     $spreadsheet->getActiveSheet()->setCellValue('L'.$row_start, "=AVERAGE(I$row_start:K$row_start)");
              }
              
              $row_start++;
              

       }

       


       $spreadsheet->getActiveSheet()->removeRow($row_start, 1);
       $merge_end = $row_start;
       $mergeStart = $row_start_content;

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
           $spreadsheet->getActiveSheet()->getCell('A'.$mergeStart)->getStyle()->getFont()->setSize(10);
           $spreadsheet->getActiveSheet()->getCell('C'.$mergeStart)->getStyle()->getFont()->setSize(10);

           $mergeStart++;
       }

          
    

}else{
     
}




















$cellvalue = "";
$row_start_strat_research = 33;
$row_start_strat_research_content = 33;
$highestRow = $spreadsheet->getActiveSheet()->getHighestRow();



$cellvalue = "";

if($instruction){
       for ($row = 25; $row <= $highestRow; $row++) {
              $cellvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row)->getValue();
              if($cellvalue == "STRATEGIC"){
              $row_start_strat_research = $row+2;
              $row_start_strat_research_content = $row+2;
              break;      
       }
 
}
}


///strategic

$research = false;
$ipcr_query_strat = "SELECT
                    it.ipcr_id, 
                    it.major_output,
                    it.`success_indicator`,
                    it.`actual_accomplishment`,
                    it.`remarks`,
                    it.`description`,
                    it.`category`,
                    it.quality,
                    it.efficiency,
                    it.timeliness,
                    it.average,
                    tt.`task_id`,
                    t.`term`,
                    t.`status`                                                
              FROM ipcr_table it 
              LEFT JOIN tasks tt ON tt.task_id = it.task_id
              LEFT JOIN terms t ON t.`term_id` = tt.`term_id`
              WHERE it.user_id = '$user_id' AND tt.task_id = '$task_id' AND category = 'Strategic' AND description = 'Research'";
$data = [];
$ipcr_strat = mysqli_query($conn, $ipcr_query_strat);
if(mysqli_num_rows($ipcr_strat) > 0){
       $research = true;
       echo setResearchAsTitle($row_start_strat_research,$spreadsheet); //set the title as research  
       while ($row = mysqli_fetch_array($ipcr_strat)) {
               $mfo = $row['major_output'];
              $success_indicator =  $row['success_indicator'];
              $actual_accomplishment =  $row['actual_accomplishment'];
              $remarks = $row['remarks'];
              $quality = $row['quality'];
              $timeliness = $row['timeliness'];
              
              $efficiency =  $row['efficiency'];
              $description =  $row['description'];
              $category =  $row['category'];
              // Append the current row's data to the $data array
              $data[] = array(
                      "mfo" => $mfo,
                     "success_indicator" => $success_indicator,
                     "actual_accomplishment" => $actual_accomplishment,
                     "remarks" => $remarks,
                     "quality" => $quality,
                     "efficiency" => $efficiency,
                     "timeliness" => $timeliness,
                     "description" => $description,
                     "category" => $category
              );
           
       }
       
       foreach($data as $row){
              $mfo = $row['mfo'];
              $success_indicator = $row['success_indicator'];
              $actual_accomplishment =  $row['actual_accomplishment'];
              $remarks = $row['remarks'];
              
              $quality =  $row['quality'];
              $efficiency =  $row['efficiency'];
              $timeliness =  $row['timeliness'];
              $description =  $row['description'];
              $category =  $row['category'];
              
              $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_start_strat_research+1)->getCalculatedValue();
              if($cellValueCurrent == "SUPPORT"){
                     $spreadsheet->getActiveSheet()->insertNewRowBefore($row_start_strat_research + 1, 1);
                    
              }
                $spreadsheet->getActiveSheet()
                  ->setCellValue('A'.$row_start_strat_research, $mfo)
                  ->setCellValue('C'.$row_start_strat_research, $success_indicator)
                  ->setCellValue('F'.$row_start_strat_research, $actual_accomplishment)
                  ->setCellValue('I'.$row_start_strat_research, $quality)
                  ->setCellValue('J'.$row_start_strat_research, $efficiency)
                  ->setCellValue('K'.$row_start_strat_research, $timeliness)
                  
                  ->setCellValue('M'.$row_start_strat_research, $remarks);
                $quality_cell = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(9, $row_start_strat_research)->getCalculatedValue();
              $efficiency_cell = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(10, $row_start_strat_research)->getCalculatedValue();
              $timeliness_cell = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(11, $row_start_strat_research)->getCalculatedValue();
              if(!empty($quality_cell) || !empty($efficiency_cell) || !empty($timeliness_cell)){
                     $spreadsheet->getActiveSheet()->setCellValue('L'.$row_start_strat_research, "=AVERAGE(I$row_start_strat_research:K$row_start_strat_research)");
              }
              
              $row_start_strat_research++;
              

       }

       
       //formatting
       $formattng_end = $row_start_strat_research;
       $formatting_start = $row_start_strat_research_content;

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
           $spreadsheet->getActiveSheet()->getCell('A'.$formatting_start)->getStyle()->getFont()->setSize(10);
           $spreadsheet->getActiveSheet()->getCell('C'.$formatting_start)->getStyle()->getFont()->setSize(10);

           $formatting_start++;

       }  

       
    
}else{
      
                     

}





 









$row_start_strat_extension = $row_start_strat_research;
$row_start_strat_extension_content = $row_start_strat_research;

$cellvalue = "";

// if($research){
//        for ($row = 25; $row <= $highestRow; $row++) {
//               $cellvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row)->getValue();
//               if($cellvalue == "SUPPORT"){
//               $row_start_strat_research = $row+1;
//               $row_start_strat_research_content = $row+1;
//               break;      
//        }
 
// }
// }

$extension = false;
$ipcr_query_strat_exten = "SELECT
                    it.ipcr_id, 
                    it.major_output,
                    it.`success_indicator`,
                    it.`actual_accomplishment`,
                    it.`remarks`,
                    it.`description`,
                    it.`category`,
                    it.quality,
                    it.efficiency,
                    it.timeliness,
                    it.average,
                    tt.`task_id`,
                    t.`term`,
                    t.`status`                                                
              FROM ipcr_table it 
              LEFT JOIN tasks tt ON tt.task_id = it.task_id
              LEFT JOIN terms t ON t.`term_id` = tt.`term_id`
              WHERE it.user_id = '$user_id' AND tt.task_id = '$task_id' AND category = 'Strategic' AND description = 'Extension'";
$data = [];
$ipcr_strat_exten = mysqli_query($conn, $ipcr_query_strat_exten);
if(mysqli_num_rows($ipcr_strat_exten) > 0){
      $extension = true;
      $spreadsheet->getActiveSheet()->insertNewRowBefore($row_start_strat_extension+1, 1);
       echo setExtensionAsTitle($row_start_strat_extension,$spreadsheet); //set the title as research 
       $row_start_strat_extension++;
        
       while ($row = mysqli_fetch_array($ipcr_strat_exten)) {
               $mfo = $row['major_output'];
              $success_indicator =  $row['success_indicator'];
              $actual_accomplishment =  $row['actual_accomplishment'];
              $remarks = $row['remarks'];
              $quality = $row['quality'];
              $timeliness = $row['timeliness'];
              
              $efficiency =  $row['efficiency'];
              $description =  $row['description'];
              $category =  $row['category'];
              // Append the current row's data to the $data array
              $data[] = array(
                      "mfo" => $mfo,
                     "success_indicator" => $success_indicator,
                     "actual_accomplishment" => $actual_accomplishment,
                     "remarks" => $remarks,
                     "quality" => $quality,
                     "efficiency" => $efficiency,
                     "timeliness" => $timeliness,
                     "description" => $description,
                     "category" => $category
              );
           
       } 

       foreach($data as $row){
               $mfo = $row['mfo'];
              $success_indicator = $row['success_indicator'];
              $actual_accomplishment =  $row['actual_accomplishment'];
              $remarks = $row['remarks'];
              
              $quality =  $row['quality'];
              $efficiency =  $row['efficiency'];
              $timeliness =  $row['timeliness'];
              $description =  $row['description'];
              $category =  $row['category'];
              
              $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_start_strat_extension+1)->getCalculatedValue();
              if($cellValueCurrent == "SUPPORT"){
                     $spreadsheet->getActiveSheet()->insertNewRowBefore($row_start_strat_extension + 1, 1);
                    
              }
              $spreadsheet->getActiveSheet()
                  ->setCellValue('A'.$row_start_strat_extension, $mfo)
                  ->setCellValue('C'.$row_start_strat_extension, $success_indicator)
                  ->setCellValue('F'.$row_start_strat_extension, $actual_accomplishment)
                  ->setCellValue('I'.$row_start_strat_extension, $quality)
                  ->setCellValue('J'.$row_start_strat_extension, $efficiency)
                  ->setCellValue('K'.$row_start_strat_extension, $timeliness)
                  
                  ->setCellValue('M'.$row_start_strat_extension, $remarks);
                $quality_cell = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(9, $row_start_strat_extension)->getCalculatedValue();
              $efficiency_cell = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(10, $row_start_strat_extension)->getCalculatedValue();
              $timeliness_cell = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(11, $row_start_strat_extension)->getCalculatedValue();
              if(!empty($quality_cell) || !empty($efficiency_cell) || !empty($timeliness_cell)){
                     $spreadsheet->getActiveSheet()->setCellValue('L'.$row_start_strat_extension, "=AVERAGE(I$row_start_strat_extension:K$row_start_strat_extension)");
              }
              
              $row_start_strat_extension++;
              

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
    $spreadsheet->getActiveSheet()->getCell('A'.$formatting_start_ex)->getStyle()->getFont()->setSize(10);
       $spreadsheet->getActiveSheet()->getCell('C'.$formatting_start_ex)->getStyle()->getFont()->setSize(10);

    $formatting_start_ex++;

}
    
}else{
      
                     

}
























///strategic



$cellvalue = "";
$row_start_strat_supp = $row_start_strat_extension;
$row_start_strat_supp_content = $row_start_strat_extension;
while($cellvalue != "SUPPORT"){
     $cellvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_start_strat_supp)->getCalculatedValue();
       $spreadsheet->getActiveSheet()
                    ->setCellValue('P'.$row_start_strat_supp, $row_start_strat_supp);
     $row_start_strat_supp++;
     $row_start_strat_supp_content++;
}

 
$support = false;

$ipcr_query_strat = "SELECT
                    it.ipcr_id, 
                    it.major_output,
                    it.`success_indicator`,
                    it.`actual_accomplishment`,
                    it.`remarks`,
                    it.`description`,
                    it.`category`,
                    it.quality,
                    it.efficiency,
                    it.timeliness,
                    it.average,
                    tt.`task_id`,
                    t.`term`,
                    t.`status`                                                
              FROM ipcr_table it 
              LEFT JOIN tasks tt ON tt.task_id = it.task_id
              LEFT JOIN terms t ON t.`term_id` = tt.`term_id`
              WHERE it.user_id = '$user_id' AND tt.task_id = '$task_id' AND category = 'Support'";
$data = [];
$ipcr_strat = mysqli_query($conn, $ipcr_query_strat);
if(mysqli_num_rows($ipcr_strat) > 0){
      
       $support = true;
        
       while ($row = mysqli_fetch_array($ipcr_strat)) {
                $mfo = $row['major_output'];
              $success_indicator =  $row['success_indicator'];
              $actual_accomplishment =  $row['actual_accomplishment'];
              $remarks = $row['remarks'];
              $quality = $row['quality'];
              $timeliness = $row['timeliness'];
              
              $efficiency =  $row['efficiency'];
              $description =  $row['description'];
              $category =  $row['category'];
              // Append the current row's data to the $data array
              $data[] = array(
                      "mfo" => $mfo,
                     "success_indicator" => $success_indicator,
                     "actual_accomplishment" => $actual_accomplishment,
                     "remarks" => $remarks,
                     "quality" => $quality,
                     "efficiency" => $efficiency,
                     "timeliness" => $timeliness,
                     "description" => $description,
                     "category" => $category
              );
           
       } foreach($data as $row){
              $mfo = $row['mfo'];
              $success_indicator = $row['success_indicator'];
              $actual_accomplishment =  $row['actual_accomplishment'];
              $remarks = $row['remarks'];
              
              $quality =  $row['quality'];
              $efficiency =  $row['efficiency'];
              $timeliness =  $row['timeliness'];
              $description =  $row['description'];
              $category =  $row['category'];
              
              $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_start_strat_supp+1)->getCalculatedValue();
              if($cellValueCurrent == "Final Average Rating"){
                     $spreadsheet->getActiveSheet()->insertNewRowBefore($row_start_strat_supp + 1, 1);
                    
              }
               $spreadsheet->getActiveSheet()
                  ->setCellValue('A'.$row_start_strat_supp, $mfo)
                  ->setCellValue('C'.$row_start_strat_supp, $success_indicator)
                  ->setCellValue('F'.$row_start_strat_supp, $actual_accomplishment)
                  ->setCellValue('I'.$row_start_strat_supp, $quality)
                  ->setCellValue('J'.$row_start_strat_supp, $efficiency)
                  ->setCellValue('K'.$row_start_strat_supp, $timeliness)
                  
                  ->setCellValue('M'.$row_start_strat_supp, $remarks);
                $quality_cell = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(9, $row_start_strat_supp)->getCalculatedValue();
              $efficiency_cell = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(10, $row_start_strat_supp)->getCalculatedValue();
              $timeliness_cell = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(11, $row_start_strat_supp)->getCalculatedValue();
              if(!empty($quality_cell) || !empty($efficiency_cell) || !empty($timeliness_cell)){
                     $spreadsheet->getActiveSheet()->setCellValue('L'.$row_start_strat_supp, "=AVERAGE(I$row_start_strat_supp:K$row_start_strat_supp)");
              }
              $row_start_strat_supp++;
              

       }

       
       

       //formatting
       $formattng_end_supp = $row_start_strat_supp;
       $formatting_start_supp = $row_start_strat_supp_content;

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
           $spreadsheet->getActiveSheet()->getCell('A'.$formatting_start_supp)->getStyle()->getFont()->setSize(10);
           $spreadsheet->getActiveSheet()->getCell('C'.$formatting_start_supp)->getStyle()->getFont()->setSize(10);

           $formatting_start_supp++;

       }




    
}else{
      
                     

}



$highestRow = $spreadsheet->getActiveSheet()->getHighestRow();

if($instruction){
       // Loop through all rows
for ($row = 25; $row <= $highestRow; $row++) {
       $cellvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row)->getValue();
       if($cellvalue == "STRATEGIC"){
              $spreadsheet->getActiveSheet()->insertNewRowBefore($row, 1);
               $spreadsheet->getActiveSheet()
                     ->setCellValue('A'.$row, "AVERAGE FOR INSTRUCTION");

               $spreadsheet->getActiveSheet()->getCell('A'.$row)->getStyle()->getAlignment()->setIndent(0);
              $spreadsheet->getActiveSheet()->mergeCells('A' .$row . ':N' . $row);
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
       if($cellvalue == "EXTENSION ACTIVITIES" || $cellvalue == "SUPPORT"){
              $spreadsheet->getActiveSheet()->insertNewRowBefore($row, 1);
               $spreadsheet->getActiveSheet()
                     ->setCellValue('A'.$row, "AVERAGE FOR RESEARCH");

               $spreadsheet->getActiveSheet()->getCell('A'.$row)->getStyle()->getAlignment()->setIndent(0);
              $spreadsheet->getActiveSheet()->mergeCells('A' .$row . ':N' . $row);
              $spreadsheet->getActiveSheet()->getCell('A'.$row)->getStyle()->getFont()->setSize(10);

              $cellValue = $spreadsheet->getActiveSheet()->getCell('A' . $row)->getValue();
           $textLength = strlen($cellValue);
           // Calculate approximate pixel height (adjust the multiplier as needed based on your font size and style)
           $rowHeight = ceil($textLength / 10) * 10; // Assuming 10 characters per line and 15 pixels per line height

           // Set the row height
           $spreadsheet->getActiveSheet()->getRowDimension($row)->setRowHeight($rowHeight);

              break;      
       }
 
}
}

if($extension){
       for ($row = 25; $row <= $highestRow; $row++) {
       $cellvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row)->getValue();
       if($cellvalue == "SUPPORT"){
              $spreadsheet->getActiveSheet()->insertNewRowBefore($row, 1);
               $spreadsheet->getActiveSheet()
                     ->setCellValue('A'.$row, "AVERAGE FOR EXTENSION");

               $spreadsheet->getActiveSheet()->getCell('A'.$row)->getStyle()->getAlignment()->setIndent(0);
              $spreadsheet->getActiveSheet()->mergeCells('A' .$row . ':N' . $row);
              $spreadsheet->getActiveSheet()->getCell('A'.$row)->getStyle()->getFont()->setSize(10);

              $cellValue = $spreadsheet->getActiveSheet()->getCell('A' . $row)->getValue();
           $textLength = strlen($cellValue);
           // Calculate approximate pixel height (adjust the multiplier as needed based on your font size and style)
           $rowHeight = ceil($textLength / 10) * 7; // Assuming 10 characters per line and 15 pixels per line height

           // Set the row height
           $spreadsheet->getActiveSheet()->getRowDimension($row)->setRowHeight($rowHeight);

              break;      
       }
 
}
}


if($support){
       for ($row = 25; $row <= $highestRow; $row++) {
       $cellvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row)->getValue();
       if($cellvalue == "Final Average Rating"){
              $spreadsheet->getActiveSheet()->insertNewRowBefore($row, 1);
               $spreadsheet->getActiveSheet()
                     ->setCellValue('A'.$row, "AVERAGE FOR SUPPORT");

               $spreadsheet->getActiveSheet()->getCell('A'.$row)->getStyle()->getAlignment()->setIndent(0);
              $spreadsheet->getActiveSheet()->mergeCells('A' .$row . ':N' . $row);
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

       // $spreadsheet->getActiveSheet()->insertNewRowBefore($row_start_strat_research, 1);                    
       // $row_start_strat_research++;
       
      
      





for ($row = 25; $row <= $highestRow; $row++) {
       $cellvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row)->getValue();
       
           if (empty($cellvalue)) {
               // Remove the current row and then do not increment $row
               $spreadsheet->getActiveSheet()->removeRow($row, 1);
           }
 
}



function setResearchAsTitle($row_start_strat_research,$spreadsheet){
       $spreadsheet->getActiveSheet()
                     ->setCellValue('A'.($row_start_strat_research-1), 'RESEARCH');
       // $spreadsheet->getActiveSheet()->unmergeCells('A' .($row_start_strat_research-1) . ':B' . ($row_start_strat_research-1));
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
        
        
        
function get_task_id($term_id){
       include "../config.php";
       $query = mysqli_query($conn,"
                            SELECT 
                                   tt.task_id,
                                   tt.`task_name`
                            FROM tasks tt 
                            LEFT JOIN terms t ON t.`term_id` = tt.`term_id`
                            WHERE tt.`task_name` =  'IPCR' AND t.term_id = '$term_id'");
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
      



//set the header first, so the result will be treated as an xlsx file.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

//make it an attachment so we can define filename
header('Content-Disposition: attachment;filename="BatStateU-FO-COL-29_Class.xlsx"');

//create IOFactory object
$writer = IOFactory::createWriter($spreadsheet, 'Xls');
//save into php output
$writer->save('php://output');

?>