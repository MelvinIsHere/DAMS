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
$spreadsheet = $reader->load("../../templates/BatStateU-FO-COL-27_Faculty-Schedule_Rev.-01.xlsx");

//add the content
//data from database


// ...
$conn = new mysqli("localhost", "root", "", "dams2");
if(!$conn){
    exit("database connection error");
}




// here is the first query for the insertion





$dept_id = $_GET['dept_id'];
$department_name = $_GET['department_name'];
$department_abbrv = $_GET['dept_abbrv'];
$semester_id = $_SESSION['semester_id'];
$acad_id = $_SESSION['acad_id'];



$faculty_names = []; //this is the array for the room names
         $sql = "SELECT DISTINCT
              
                f.firstname,
                f.middlename,
                f.lastname,
                f.suffix
                                        
            FROM faculty_schedule fs
            LEFT JOIN faculties f ON f.faculty_id = fs.faculty_id
            LEFT JOIN departments d ON d.department_id = fs.department_id
                                     
            WHERE fs.department_id = '$dept_id'";
        
                    $execute = mysqli_query($conn,$sql);
                    if($execute){
                        if(mysqli_num_rows($execute) > 0){
                            while($row = mysqli_fetch_array($execute)){
                                $facultyname = $row['lastname'] . " ". $row['firstname'] . " " .$row['middlename'] . " ".$row['suffix'];
                                $faculty_names[] = $facultyname;

                            }
                
                        }
                    }
       
// $room_names = ['MAC LAB','HEB LAB 1'];
foreach ($faculty_names as $tabName) {
    // Clone the 'BLANK' sheet
    $clonedWorksheet = clone $spreadsheet->getSheetByName('BLANK FORM');
    $clonedWorksheet->setTitle($tabName); // Set a meaningful title for the cloned sheet
    
    // Add the cloned sheet to the spreadsheet
    $spreadsheet->addSheet($clonedWorksheet);
    // Set the newly added sheet as the active sheet
    $spreadsheet->setActiveSheetIndexByName($tabName);
      // Fill the cell with data
    $monday_plot = faculty_plot_double_col('Monday',$dept_id,$conn,$spreadsheet,$tabName,'B','C','D');
    $plot_tuesday = plot("Tuesday",$dept_id,$conn,$spreadsheet,$tabName,"E","F");
    $plot_wednesday = plot("Wednesday",$dept_id,$conn,$spreadsheet,$tabName,"G","H");
    $plot_Thursday = plot("Thursday",$dept_id,$conn,$spreadsheet,$tabName,"I","J");

    $friday_plot = faculty_plot_double_col('Friday',$dept_id,$conn,$spreadsheet,$tabName,'K','L','M');
    $saturday_plot = faculty_plot_double_col('Saturday',$dept_id,$conn,$spreadsheet,$tabName,'N','O','P');
    $sunday_plot = plot("Sunday",$dept_id,$conn,$spreadsheet,$tabName,"Q","R");

    $sched_info_plot = plot_sched_info($dept_id,$conn,$spreadsheet,$tabName);
    $plot_sched_header = plot_header_info($tabName,$spreadsheet,$department_name,$conn,$semester_id,$acad_id);
        
    
  
        
}








function plot_header_info($faculty,$spreadsheet,$department_name,$conn,$semester_id,$acad_id){
    $spreadsheet->getActiveSheet()
        ->setCellValue("B3",$department_name)
        ->setCellValue("B4", $faculty);
    $query = mysqli_query($conn,"SELECT sem_description FROM semesters WHERE semester_id = '$semester_id'");
    if($query){
        if(mysqli_num_rows($query) > 0){
            $row = mysqli_fetch_assoc($query);
            $semester_des = $row['sem_description'];
            $spreadsheet->getActiveSheet()
                ->setCellValue("O4",$semester_des);
        }else{

        }
    }else{

    }

    $acad_query = mysqli_query($conn, "SELECT acad_year FROM academic_year WHERE acad_year_id = '$acad_id'");
    if($acad_query){
        if(mysqli_num_rows($acad_query) > 0){
            $row = mysqli_fetch_assoc($acad_query);
            $acad_year = $row['acad_year'];
            $spreadsheet->getActiveSheet()
                ->setCellValue("O3",$acad_year);


        }else{

        }
    }else{

    }

    

}



function plot($day,$dept_id,$conn,$spreadsheet,$faculty,$col_sched,$col_room){


    $data_to_plot = "  
            SELECT 
                fs.faculty_sched_id,
                f.firstname,
                f.middlename,
                f.lastname,
                f.suffix,
                d.department_name,
                c.course_code,
                r.room_name,
                ay.acad_year,
                sm.sem_description,
                s.section_name,
                p.program_abbrv,
                t1.text_output AS 'class_start',
                t1.time_s,
                t1.ampm_start,
                t2.time_e,
                t2.text_output AS 'class_end',
                t2.ampm_end,
                fs.day,
                fs.description
                                        
            FROM faculty_schedule fs
            LEFT JOIN faculties f ON f.faculty_id = fs.faculty_id
            LEFT JOIN departments d ON d.department_id = fs.department_id
            LEFT JOIN courses c ON c.course_id = fs.course_id
            LEFT JOIN rooms r ON r.room_id = fs.room_id
            LEFT JOIN semesters sm ON sm.semester_id = fs.semester_id
            LEFT JOIN academic_year ay ON ay.acad_year_id = fs.acad_year_id
            LEFT JOIN sections s ON s.section_id = fs.section_id
            LEFT JOIN programs p ON p.program_id = s.program_id
            LEFT JOIN `time` t1 ON t1.time_id = fs.time_start_id
            LEFT JOIN `time` t2 ON t2.time_id = fs.time_end_id                                
            WHERE fs.department_id = '$dept_id'
            AND CONCAT(f.lastname,' ',f.firstname,' ',f.middlename,' ',f.suffix) = '$faculty'
            AND fs.day = '$day' AND fs.description != 'Official time morning' AND fs.description != 'Official time afternoon'
                ";


$data = [];


$plot = mysqli_query($conn, $data_to_plot);



while ($row = mysqli_fetch_array($plot)) {
  

    
    $course_code = $row['course_code'];
    $text_output_start = $row['class_start'];
    $time_start = $row['time_s'];
    $time_end = $row['time_e'];
    $text_output_end = $row['class_end'];
    $ampm_start = $row['ampm_start'];
    $ampm_end = $row['ampm_end'];
    $room_name = $row['room_name'];
    $description = $row['description'];
    $section = $row['program_abbrv'] . " ".$row['section_name'];

    // Append the current row's data to the $data array
    $data[] = array(
        
        "course_code" => $course_code,
        "text_output_start" => $text_output_start,
        "time_s" => $time_start,
        "time_end" => $time_end,
        "text_output_end" => $text_output_end,
        "col_start" => NULL,
        "col_end" => NULL,
        "ampm_start" => $ampm_start,
        "ampm_end" => $ampm_end,
        "inserted" => NULL,
        "room_name" => $room_name,
        "description" => $description,
        "section" => $section
    );
}




// Accessing elements
// echo $data[0]["faculty_name"]; // John

// if(mysqli_num_rows($monday_plot) > 0){
// $course_code = $data[0]["course_code"];
//  $spreadsheet->getActiveSheet()->setCellValue('L8',$course_code);




$currentContentRow = 6;

     $alreadyInserted = []; // Initialize an array to keep track of inserted $text_output_data values
        // Fill the cell with data
$start= [];
$course = [];
$sample = [];
while($currentContentRow != 40){

 

      foreach ($data as &$row) {
            foreach ($row as $key => $value) {
                 if ($key === "text_output_start") { // Check if the current key is "text_output"
                     
                $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $currentContentRow)->getCalculatedValue();
                $ampm = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(22, $currentContentRow)->getCalculatedValue();
                $text_output_data = $value;
                $search_course_code = $row["course_code"];
                $arr_ampm_start = $row['ampm_start'];
                $arr_ampm_end = $row['ampm_end'];
                $inserted =$row['inserted'];
                $description = $row['description'];
                $room_name = $row['room_name'];
                $section = $row['section'];
                
                         
                                

                                if ($cellValueCurrent == $text_output_data && !in_array($text_output_data, $start) && !in_array($search_course_code, $course)) {
                                     $start[] = $text_output_data; // Add the inserted value to the tracking array
                                    $course[] = $search_course_code;
                                            $index = -1; // Initialize with -1 to indicate not found

                                            // Loop through the array to search for the course code
                                            foreach ($data as $keyrow => $rowData) {
                                                if ($rowData["course_code"] == $search_course_code) {
                                                    $index = $keyrow; // Set the index when found
                                                    
                                                    break; // Stop searching once found
                                                }
                                            }

                                            if ($index != -1) {
                                             

                                               $time_string  = $row['time_s'];

                                                $time_parts = explode(" ", $time_string); //when the start is like half an hour
                                                // Extract the time part
                                                $time = $time_parts[0];
                                                // Split the time by ":" to get hours and minutes
                                                $time_parts = explode(":", $time);
                                                // Extract the minutes portion
                                                $minutes = $time_parts[1];
                                                // Convert the minutes to an integer if needed
                                                $minutes = intval($minutes);
                                                    
                                                   
                                                if($minutes == 30){
                                                    //if start half way decrement the contentrow
                                                    $new_content_row = $currentContentRow + 1;
                                                    $data[$index]['col_start'] = $new_content_row;
                                                    $course_codes[] = $search_course_code;
                                                    
                                               
                                                    $b = $data[$index]['col_start'];
                                                    if($description == 'Class Schedule'){
                                                        $spreadsheet->getActiveSheet()
                                                      ->setCellValue($col_sched .$new_content_row, $search_course_code."\n".$section)
                                                      ->setCellValue($col_room.$new_content_row, $room_name);
                                                    }else{
                                                        $spreadsheet->getActiveSheet()
                                                      ->setCellValue($col_sched .$new_content_row, $description)
                                                      ->setCellValue($col_room.$new_content_row, $room_name); 
                                                    }
                                                      
                                                    
                                                }
                                                else{
                                                     $data[$index]['col_start'] = $currentContentRow;
                                               
                                                $b = $data[$index]['col_start'];
                                                 if($description == 'Class Schedule'){
                                                        $spreadsheet->getActiveSheet()
                                                      ->setCellValue($col_sched .$currentContentRow, $search_course_code."\n".$section)
                                                      ->setCellValue($col_room.$currentContentRow, $room_name);
                                                    }else{
                                                        $spreadsheet->getActiveSheet()
                                                      ->setCellValue($col_sched .$currentContentRow, $description)
                                                      ->setCellValue($col_room.$currentContentRow, $room_name); 
                                                    }
                                                }


                                                // Update the 'col_end' field here with the correct value (e.g., $currentContentRow)
                                                

                                            }else{

                                            }
                                    
                                }else{
                                    
                                }
                               
                                                                   


                         
                       
                                }
                                

                
              

            }
    
}
  $currentContentRow++;
 


}      

 


$currentContentRow = 6;

$end = []; // Initialize an array to keep track of inserted $text_output_data values
        // Fill the cell with data
$course_end = [];
while($currentContentRow != 40){

 

      foreach ($data as &$row) {
            foreach ($row as $key => $value) {
                 if ($key === "text_output_end") { // Check if the current key is "text_output"
                     
                $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $currentContentRow)->getCalculatedValue();
                $ampm = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(22, $currentContentRow)->getCalculatedValue();
                $text_output_data = $value;
                $search_course_code = $row["course_code"];
                $arr_ampm_start = $row['ampm_start'];
                $arr_ampm_end = $row['ampm_end'];
                $inserted =$row['inserted'];
                
                
                         
                                

                                if ($cellValueCurrent == $text_output_data && !in_array($text_output_data, $end) && !in_array($search_course_code, $course_end)) {
                                     $end[] = $text_output_data; // Add the inserted value to the tracking array
                                    $course_end[] = $search_course_code;
                                            $index = -1; // Initialize with -1 to indicate not found

                                            // Loop through the array to search for the course code
                                            foreach ($data as $keyrow => $rowData) {
                                                if ($rowData["course_code"] == $search_course_code) {
                                                    $index = $keyrow; // Set the index when found
                                                    
                                                    break; // Stop searching once found
                                                }
                                            }

                                            if ($index != -1) {
                                             $time_string  = $row['time_s'];

                                                $time_parts = explode(" ", $time_string); //when the start is like half an hour
                                                // Extract the time part
                                                $time = $time_parts[0];
                                                // Split the time by ":" to get hours and minutes
                                                $time_parts = explode(":", $time);
                                                // Extract the minutes portion
                                                $minutes = $time_parts[1];
                                                // Convert the minutes to an integer if needed
                                                $minutes = intval($minutes);
                                                    
                                                   
                                                if($minutes == 30){
                                                    //if start half way decrement the contentrow
                                                    $new_content_row = $currentContentRow;
                                                    $data[$index]['col_end'] = $new_content_row;
                                               
                                                    $b = $data[$index]['col_end'];
                                                      // $spreadsheet->getActiveSheet()
                                                      // ->setCellValue($col_sched.$new_content_row, $search_course_code)
                                                      // ->setCellValue($col_room.$new_content_row, $room_name);
                                                    
                                                }
                                                else{
                                                      $new_content_row = $currentContentRow + 1;
                                                    $data[$index]['col_end'] = $new_content_row;
                                               
                                               
                                                $b = $data[$index]['col_end'];
                                                 // $spreadsheet->getActiveSheet()
                                                 // ->setCellValue($col_sched.$currentContentRow, $search_course_code)
                                                 // ->setCellValue($col_room.$currentContentRow, $room_name);
                                                }

                                            }else{

                                            }
                                    
                                }else{
                                    
                                }
                               
                                                                   


                         
                       
                                }
                                

                
              

            }
    
}
  $currentContentRow++;

}      

 
 


  



$styleArray = [
    'borders' => [
        'top' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
        'bottom' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
];


 foreach ($data as &$row) {
            foreach ($row as $key => $value) {
                      $col_start = $row['col_start'];
                         $col_end = $row['col_end'];
                         $from = "B".$col_start;
                         $to = "C".$col_end;

                        //merging the course and faculty column -- monday
                          $spreadsheet->getActiveSheet()->mergeCells("$col_sched$col_start:$col_sched$col_end");
                          $spreadsheet->getActiveSheet()->getStyle("$from")->getAlignment()->setHorizontal('center');
                          $spreadsheet->getActiveSheet()->getStyle($col_sched.$col_start)->getAlignment()->setWrapText(true); 
                          //style
                          $spreadsheet->getActiveSheet()->getStyle("$col_sched$col_start:$col_sched$col_end")->applyFromArray($styleArray);
                          //rm column
                          $spreadsheet->getActiveSheet()->mergeCells("$col_room$col_start:$col_room$col_end");
                          $spreadsheet->getActiveSheet()->getStyle($col_room.$col_end)->getAlignment()->setHorizontal('center');
                          $spreadsheet->getActiveSheet()->getStyle($col_room.$col_start)->getAlignment()->setWrapText(true); 
                          $spreadsheet->getActiveSheet()->getStyle("$col_room$col_start:$col_room$col_end")->applyFromArray($styleArray);
    }
}




        
        
  
      

}















function faculty_plot_double_col($day,$dept_id,$conn,$spreadsheet,$faculty,$col_sched_1,$col_sched_2,$col_room){


    $data_to_plot = "  
            SELECT 
                fs.faculty_sched_id,
                f.firstname,
                f.middlename,
                f.lastname,
                f.suffix,
                d.department_name,
                c.course_code,
                r.room_name,
                ay.acad_year,
                sm.sem_description,
                s.section_name,
                p.program_abbrv,
                t1.text_output AS 'class_start',
                t1.time_s,
                t1.ampm_start,
                t2.time_e,
                t2.text_output AS 'class_end',
                t2.ampm_end,
                fs.day,
                fs.description
                                        
            FROM faculty_schedule fs
            LEFT JOIN faculties f ON f.faculty_id = fs.faculty_id
            LEFT JOIN departments d ON d.department_id = fs.department_id
            LEFT JOIN courses c ON c.course_id = fs.course_id
            LEFT JOIN rooms r ON r.room_id = fs.room_id
            LEFT JOIN semesters sm ON sm.semester_id = fs.semester_id
            LEFT JOIN academic_year ay ON ay.acad_year_id = fs.acad_year_id
            LEFT JOIN sections s ON s.section_id = fs.section_id
            LEFT JOIN programs p ON p.program_id = s.program_id
            LEFT JOIN `time` t1 ON t1.time_id = fs.time_start_id
            LEFT JOIN `time` t2 ON t2.time_id = fs.time_end_id                                
            WHERE fs.department_id = '$dept_id'
            AND CONCAT(f.lastname,' ',f.firstname,' ',f.middlename,' ',f.suffix) = '$faculty'
            AND fs.day = '$day'
                ";


$data = [];


$plot = mysqli_query($conn, $data_to_plot);



while ($row = mysqli_fetch_array($plot)) {
  

    
    $course_code = $row['course_code'];
    $text_output_start = $row['class_start'];
    $time_start = $row['time_s'];
    $time_end = $row['time_e'];
    $text_output_end = $row['class_end'];
    $ampm_start = $row['ampm_start'];
    $ampm_end = $row['ampm_end'];
    $room_name = $row['room_name'];
    $description = $row['description'];
    $section = $row['program_abbrv']." ".$row['section_name'];

    // Append the current row's data to the $data array
    $data[] = array(
        
        "course_code" => $course_code,
        "text_output_start" => $text_output_start,
        "time_s" => $time_start,
        "time_end" => $time_end,
        "text_output_end" => $text_output_end,
        "col_start" => NULL,
        "col_end" => NULL,
        "ampm_start" => $ampm_start,
        "ampm_end" => $ampm_end,
        "inserted" => NULL,
        "room_name" => $room_name,
        "description" => $description,
        "section" => $section
    );
}



//insert subject



$currentContentRow = 6;

     $alreadyInserted = []; // Initialize an array to keep track of inserted $text_output_data values
        // Fill the cell with data
$start= [];
$course = [];
$sample = [];
while($currentContentRow != 40){

 

      foreach ($data as &$row) {
            foreach ($row as $key => $value) {
                 if ($key === "text_output_start") { // Check if the current key is "text_output"
                     
                $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $currentContentRow)->getCalculatedValue();
                $ampm = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(22, $currentContentRow)->getCalculatedValue();
                $text_output_data = $value;
                $search_course_code = $row["course_code"];
                $arr_ampm_start = $row['ampm_start'];
                $arr_ampm_end = $row['ampm_end'];
                $inserted =$row['inserted'];
                $description = $row['description'];
                $room_name = $row['room_name'];
                $section = $row['section'];
                
                         
                                

                                if ($cellValueCurrent == $text_output_data && !in_array($text_output_data, $start) && !in_array($search_course_code, $course)) {
                                     $start[] = $text_output_data; // Add the inserted value to the tracking array
                                    $course[] = $search_course_code;
                                            $index = -1; // Initialize with -1 to indicate not found

                                            // Loop through the array to search for the course code
                                            foreach ($data as $keyrow => $rowData) {
                                                if ($rowData["course_code"] == $search_course_code) {
                                                    $index = $keyrow; // Set the index when found
                                                    
                                                    break; // Stop searching once found
                                                }
                                            }

                                            if ($index != -1) {
                                             

                                               $time_string  = $row['time_s'];

                                                $time_parts = explode(" ", $time_string); //when the start is like half an hour
                                                // Extract the time part
                                                $time = $time_parts[0];
                                                // Split the time by ":" to get hours and minutes
                                                $time_parts = explode(":", $time);
                                                // Extract the minutes portion
                                                $minutes = $time_parts[1];
                                                // Convert the minutes to an integer if needed
                                                $minutes = intval($minutes);
                                                    
                                                   
                                                if($minutes == 30){
                                                    //if start half way decrement the contentrow
                                                    $new_content_row = $currentContentRow + 1;
                                                    $data[$index]['col_start'] = $new_content_row;
                                                    $course_codes[] = $search_course_code;
                                                    
                                               
                                                    $b = $data[$index]['col_start'];
                                                    if($description == 'Class Schedule'){
                                                          $spreadsheet->getActiveSheet()
                                                      ->setCellValue($col_sched_1 .$new_content_row, $search_course_code."\n".$section)
                                                      ->setCellValue($col_room.$new_content_row, $room_name);
                                                    }else{
                                                        $spreadsheet->getActiveSheet()
                                                      ->setCellValue($col_sched_1 .$new_content_row, $description)
                                                      ->setCellValue($col_room.$new_content_row, $room_name);
                                                    }
                                                    
                                                    
                                                }
                                                else{
                                                     $data[$index]['col_start'] = $currentContentRow;
                                               
                                                $b = $data[$index]['col_start'];
                                                 if($description == 'Class Schedule'){
                                                          $spreadsheet->getActiveSheet()
                                                      ->setCellValue($col_sched_1 .$currentContentRow, $search_course_code."\n".$section)
                                                      ->setCellValue($col_room.$currentContentRow, $room_name);
                                                    }else{
                                                        $spreadsheet->getActiveSheet()
                                                      ->setCellValue($col_sched_1 .$currentContentRow, $description)
                                                      ->setCellValue($col_room.$currentContentRow, $room_name);
                                                    }
                                                    
                                                }


                                                // Update the 'col_end' field here with the correct value (e.g., $currentContentRow)
                                                

                                            }else{

                                            }
                                    
                                }else{
                                    
                                }
                               
                                                                   


                         
                       
                                }
                                

                
              

            }
    
}
  $currentContentRow++;
 


}      

 

 //getting the end of row for merging 


$currentContentRow = 6;

$end = []; // Initialize an array to keep track of inserted $text_output_data values
        // Fill the cell with data
$course_end = [];
while($currentContentRow != 40){

 

      foreach ($data as &$row) {
            foreach ($row as $key => $value) {
                 if ($key === "text_output_end") { // Check if the current key is "text_output"
                     
                $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $currentContentRow)->getCalculatedValue();
                $ampm = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(22, $currentContentRow)->getCalculatedValue();
                $text_output_data = $value;
                $search_course_code = $row["course_code"];
                $arr_ampm_start = $row['ampm_start'];
                $arr_ampm_end = $row['ampm_end'];
                $inserted =$row['inserted'];
                
                
                         
                                

                                if ($cellValueCurrent == $text_output_data && !in_array($text_output_data, $end) && !in_array($search_course_code, $course_end)) {
                                     $end[] = $text_output_data; // Add the inserted value to the tracking array
                                    $course_end[] = $search_course_code;
                                            $index = -1; // Initialize with -1 to indicate not found

                                            // Loop through the array to search for the course code
                                            foreach ($data as $keyrow => $rowData) {
                                                if ($rowData["course_code"] == $search_course_code) {
                                                    $index = $keyrow; // Set the index when found
                                                    
                                                    break; // Stop searching once found
                                                }
                                            }

                                            if ($index != -1) {
                                             $time_string  = $row['time_s'];

                                                $time_parts = explode(" ", $time_string); //when the start is like half an hour
                                                // Extract the time part
                                                $time = $time_parts[0];
                                                // Split the time by ":" to get hours and minutes
                                                $time_parts = explode(":", $time);
                                                // Extract the minutes portion
                                                $minutes = $time_parts[1];
                                                // Convert the minutes to an integer if needed
                                                $minutes = intval($minutes);
                                                    
                                                   
                                                if($minutes == 30){
                                                    //if start half way decrement the contentrow
                                                    $new_content_row = $currentContentRow;
                                                    $data[$index]['col_end'] = $new_content_row;
                                               
                                                    // $b = $data[$index]['col_end'];
                                                    //   $spreadsheet->getActiveSheet()
                                                    //   ->setCellValue($col_sched_1.$new_content_row, $search_course_code.$facultyname)
                                                    //   ->setCellValue($col_room.$new_content_row, $room_name);
                                                    
                                                }
                                                else{
                                                      $new_content_row = $currentContentRow + 1;
                                                    $data[$index]['col_end'] = $new_content_row;
                                               
                                               
                                                // $b = $data[$index]['col_end'];
                                                //  $spreadsheet->getActiveSheet()
                                                //  ->setCellValue($col_sched_1.$currentContentRow, $search_course_code.$facultyname)
                                                //  ->setCellValue($col_room.$currentContentRow, $room_name);
                                                }

                                            }else{

                                            }
                                    
                                }else{
                                    
                                }
                               
                                                                   


                         
                       
                                }
                                

                
              

            }
    
}
  $currentContentRow++;


}      




$styleArray = [
    'borders' => [
        'top' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
        'bottom' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
];


 foreach ($data as &$row) {
            foreach ($row as $key => $value) {
                      $col_start = $row['col_start'];
                         $col_end = $row['col_end'];
                         $from = $col_sched_1.$col_start;
                         $to = $col_sched_2.$col_end;

                        //merging the course and faculty column -- monday
                          $spreadsheet->getActiveSheet()->mergeCells("$col_sched_1$col_start:$col_sched_2$col_end");
                          $spreadsheet->getActiveSheet()->getStyle("$from")->getAlignment()->setHorizontal('center');
                          $spreadsheet->getActiveSheet()->getStyle($col_sched_1.$col_start)->getAlignment()->setWrapText(true); 
                          //style
                          $spreadsheet->getActiveSheet()->getStyle("$col_sched_1$col_start:$col_sched_2$col_end")->applyFromArray($styleArray);
                          //rm column
                          $spreadsheet->getActiveSheet()->mergeCells("$col_room$col_start:$col_room$col_end");
                          $spreadsheet->getActiveSheet()->getStyle($col_room.$col_end)->getAlignment()->setHorizontal('center');
                          $spreadsheet->getActiveSheet()->getStyle($col_room.$col_start)->getAlignment()->setWrapText(true); 
                          $spreadsheet->getActiveSheet()->getStyle("$col_room$col_start:$col_room$col_end")->applyFromArray($styleArray);
    }
}




 // $count = count($data);
 //   $spreadsheet->getActiveSheet()
 //                ->setCellValue('F6', $count);
                                                    

  






}
//end of function






function plot_sched_info($dept_id,$conn,$spreadsheet,$faculty){
    $sql = " SELECT DISTINCT
                fs.faculty_sched_id,
                f.firstname,
                f.middlename,
                f.lastname,
                f.suffix,
                d.department_name,
                c.course_code,
                r.room_name,
                ay.acad_year,
                sm.sem_description,
                s.section_name,
                s.no_of_students,
                p.program_abbrv,
                t1.text_output AS 'class_start',
                t1.time_s,
                t1.ampm_start,
                t2.time_e,
                t2.text_output AS 'class_end',
                t2.ampm_end,
                fs.day,
                fs.description
                                        
            FROM faculty_schedule fs
            LEFT JOIN faculties f ON f.faculty_id = fs.faculty_id
            LEFT JOIN departments d ON d.department_id = fs.department_id
            LEFT JOIN courses c ON c.course_id = fs.course_id
            LEFT JOIN rooms r ON r.room_id = fs.room_id
            LEFT JOIN semesters sm ON sm.semester_id = fs.semester_id
            LEFT JOIN academic_year ay ON ay.acad_year_id = fs.acad_year_id
            LEFT JOIN sections s ON s.section_id = fs.section_id
            LEFT JOIN programs p ON p.program_id = s.program_id
            LEFT JOIN `time` t1 ON t1.time_id = fs.time_start_id
            LEFT JOIN `time` t2 ON t2.time_id = fs.time_end_id                                
            WHERE fs.department_id = '$dept_id'
            AND CONCAT(f.lastname,' ',f.firstname,' ',f.middlename,' ',f.suffix) = '$faculty'
            AND fs.description = 'Class Schedule'
            
                ";




$data = [];


$result = mysqli_query($conn,$sql);

while ($row = mysqli_fetch_array($result)) {
   

    
    $course_code = $row['course_code'];
    $section = "BS".$row['program_abbrv'] . " " . $row['section_name'];
    $total_studs = $row['no_of_students'];
    $description = $row['description'];

    // Append the current row's data to the $data array
    $data[] = array(
        "section" => $section,
        "course_code" => $course_code,
        "students" => $total_studs,
        "description" => $description
    );


        
}
 $styleArray = [
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Center vertically
        
    ],
];

$currentContentRow = 6;
 foreach ($data as &$row) {
            foreach ($row as $key => $value) {
                $course_code = $row['course_code'];
                $section = $row['section'];
                $studs = $row['students'];
                      $spreadsheet->getActiveSheet()
                      ->setCellValue('S'.$currentContentRow, $course_code)
                      ->setCellValue('T'.$currentContentRow, $section)
                      ->setCellValue('U'.$currentContentRow, $studs);
                    // Calculate and set the column width for column A (for example)
                    $spreadsheet->getActiveSheet()->calculateColumnWidths();
                    $spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
                    $spreadsheet->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
                    
                      
            }
            $currentContentRow++;
    }



// $arrayLength = count($data);

// for ($i = 0; $i < $arrayLength; $i++) {
//     // Access the current element using $myArray[$i]
//     // echo "Element at index $i: " . $data[$i] . "\n";

//       $spreadsheet->getActiveSheet()->setCellValue('S'.$currentContentRow, $data[$i]);
//       $currentContentRow++;
// }
}


//set the header first, so the result will be treated as an xlsx file.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

//make it an attachment so we can define filename
header('Content-Disposition: attachment;filename="BatStateU-FO-COL-27_Faculty-Schedule_Rev.-01.xlsx"');

//create IOFactory object
$writer = IOFactory::createWriter($spreadsheet, 'Xls');
//save into php output
$writer->save('php://output');



?>