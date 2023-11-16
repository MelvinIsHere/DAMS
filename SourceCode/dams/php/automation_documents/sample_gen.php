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
$spreadsheet = $reader->load("../../templates/BatStateU-FO-COL-28_Room Utilization_Rev. 03.xlsx");

//add the content
//data from database

$conn = new mysqli("localhost", "root", "", "dams2");
if(!$conn){
    exit("database connection error");
}
// ...
$term_id = $_SESSION['term_id'];
$department_id = $_GET['dept_id'];
$department_name = $_GET['department_name'];
$room_names = []; //this is the array for the room names
         $sql = " SELECT DISTINCT
                    r.room_name
                               
                    FROM class_schedule cs
                    LEFT JOIN faculty_loadings fl ON fl.`fac_load_id` = cs.`faculty_loading_id`
                    LEFT JOIN faculties fc ON fl.`faculty_id`=fc.`faculty_id`
                    LEFT JOIN courses c ON fl.`course_id`=c.`course_id`
                    LEFT JOIN sections sc ON fl.`section_id`=sc.`section_id`
                    LEFT JOIN programs pr ON sc.`program_id`=pr.`program_id`
                    LEFT JOIN departments dp ON dp.`department_id`=fl.`dept_id`
                    LEFT JOIN semesters s ON s.semester_id = fl.sem_id
                    LEFT JOIN academic_year ay ON ay.acad_year_id = fl.acad_year_id
                    LEFT JOIN rooms r ON r.`room_id` = cs.room_id
                    LEFT JOIN `time` t1 ON t1.`time_id` = cs.time_start_id
                    LEFT JOIN `time` t2 ON t2.`time_id` = cs.time_end_id
                    LEFT JOIN tasks tt ON tt.task_id = cs.task_id
                    WHERE pr.`department_id` = '$department_id'
                    
                                      
                                    
                                    
                    GROUP BY cs.class_sched_id";
        
                    $execute = mysqli_query($conn,$sql);
                    if($execute){
                        if(mysqli_num_rows($execute) > 0){
                            while($row = mysqli_fetch_array($execute)){
                                $room_name = $row['room_name'];
                                $room_names[] = $room_name;

                            }
                
                        }
                    }
       
// $room_names = ['MAC LAB','HEB LAB 1'];
foreach ($room_names as $tabName) {
    // Clone the 'BLANK' sheet
    $clonedWorksheet = clone $spreadsheet->getSheetByName('BLANK FORM');
    $clonedWorksheet->setTitle($tabName); // Set a meaningful title for the cloned sheet
    
    // Add the cloned sheet to the spreadsheet
    $spreadsheet->addSheet($clonedWorksheet);
    // Set the newly added sheet as the active sheet
    $spreadsheet->setActiveSheetIndexByName($tabName);
      // Fill the cell with data
    $plot_monday = plot_double_col('Monday',$department_id,$conn,$spreadsheet,$tabName,'B','C','D');
    $plot_tuesday = plot('Tuesday',$department_id,$conn,$spreadsheet,$tabName,'E','F');
    $plot_wednesday = plot('Wednesday',$department_id,$conn,$spreadsheet,$tabName,'G','H');
    $plot_Thursday = plot('Thursday',$department_id,$conn,$spreadsheet,$tabName,'I','J');
    $plot_friday = plot_double_col('Friday',$department_id,$conn,$spreadsheet,$tabName,'K','L','M');
    $plot_saturday = plot_double_col('Saturday',$department_id,$conn,$spreadsheet,$tabName,'N','O','P');
    $plot_sunday = plot('Sunday',$department_id,$conn,$spreadsheet,$tabName,'Q','R');
    $plot_header = plot_room_name($tabName,$spreadsheet,$department_name,$conn);
    $plot_room_info =plot_subject_info($department_id,$conn,$spreadsheet,$tabName);
    // if($tabName === "HEB LAB 2"){
    //     $spreadsheet->getActiveSheet()->setCellValue('B6', $tabName);
    // }else{

    // }
        
}

   














function plot_double_col($day,$dept_id,$conn,$spreadsheet,$room_name,$col_sched_1,$col_sched_2,$col_section){


    $monday_plot_query = " SELECT 
                                cs.class_sched_id,
                                fl.faculty_id,
                                cs.day,
                                fc.firstname,
                                fc.lastname,
                                fc.middlename,
                                fc.suffix,
                                c.course_code,
                                pr.`program_abbrv`,
                                sc.section_name,
                                sc.`no_of_students`,
                                r.room_name,
                                t1.time_s,
                                t2.time_e,
                                t1.text_output AS 'class_start',
                                t1.time_s,
                                t1.ampm_start,
                                t2.time_e,
                                t2.text_output AS 'class_end',
                                t2.ampm_end,
                                dp.department_name,
                                s.`sem_description`,
                                ay.`acad_year`,
                                fl.needed
                                FROM class_schedule cs
                                LEFT JOIN faculty_loadings fl ON fl.`fac_load_id` = cs.`faculty_loading_id`
                              LEFT JOIN faculties fc ON fl.`faculty_id`=fc.`faculty_id`
                                    LEFT JOIN courses c ON fl.`course_id`=c.`course_id`
                                    LEFT JOIN sections sc ON fl.`section_id`=sc.`section_id`
                                    LEFT JOIN programs pr ON sc.`program_id`=pr.`program_id`
                                    LEFT JOIN departments dp ON dp.`department_id`=fl.`dept_id`
                                    LEFT JOIN semesters s ON s.semester_id = fl.sem_id
                                    LEFT JOIN academic_year ay ON ay.acad_year_id = fl.acad_year_id
                                    LEFT JOIN rooms r ON r.`room_id` = cs.room_id
                                    LEFT JOIN `time` t1 ON t1.`time_id` = cs.time_start_id
                                    LEFT JOIN `time` t2 ON t2.`time_id` = cs.time_end_id
                                    WHERE pr.`department_id` = '$dept_id'  
                                    AND s.status = 'ACTIVE'
                                    AND ay.status = 'ACTIVE'
                                    AND cs.day = '$day'
                                    AND r.room_name = '$room_name'
                                    GROUP BY cs.class_sched_id";


$data = [];


$monday_plot = mysqli_query($conn, $monday_plot_query);

while ($row = mysqli_fetch_array($monday_plot)) {
    $firstname = $row['firstname'];
    $firstLettersFirstName = "";
    if(!empty($firstname)){
        $first_name_first_letter = explode(" ", $firstname);
    
    
    foreach ($first_name_first_letter as $part) {
        $firstLettersFirstName .= $part[0] . ". ";
    }
    }

    $facultyname = $firstLettersFirstName . " " . $row['lastname'];
    $course_code = $row['course_code'];
    $text_output_start = $row['class_start'];
    $time_start = $row['time_s'];
    $time_end = $row['time_e'];
    $text_output_end = $row['class_end'];
    $ampm_start = $row['ampm_start'];
    $ampm_end = $row['ampm_end'];
    $room_name = $row['room_name'];
    $needed = $row['needed'];
    $section_name = "BS".$row['program_abbrv']." ".$row['section_name'];

    // Append the current row's data to the $data array
    $data[] = array(
        "faculty_name" => $facultyname,
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
        "needed" => $needed,
        "section_name" => $section_name
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
                $facultyname = $row['faculty_name'];
                $room_name = $row['room_name'];
                $needed = "Need Lecturer";
                $section_name = $row['section_name'];

                
                         
                                

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
                                               
                                                    $b = $data[$index]['col_start'];
                                                     

                                                    if(!empty($facultyname)){
                                                          $spreadsheet->getActiveSheet()
                                                      ->setCellValue($col_sched_1 .$new_content_row, $search_course_code.$facultyname)
                                                      ->setCellValue($col_section.$new_content_row, $section_name);
                                                    }else{
                                                          $spreadsheet->getActiveSheet()
                                                      ->setCellValue($col_sched_1 .$new_content_row, $search_course_code.$needed)
                                                      ->setCellValue($col_section.$new_content_row, $section_name);
                                                    }
                                                    
                                                }
                                                else{
                                                     $data[$index]['col_start'] = $currentContentRow;
                                               
                                                $b = $data[$index]['col_start'];

                                                    if(!empty($facultyname)){
                                                          $spreadsheet->getActiveSheet()
                                                      ->setCellValue($col_sched_1 .$currentContentRow, $search_course_code.$facultyname)
                                                      ->setCellValue($col_section.$currentContentRow, $section_name);
                                                    }else{
                                                          $spreadsheet->getActiveSheet()
                                                      ->setCellValue($col_sched_1 .$currentContentRow, $search_course_code.$needed)
                                                      ->setCellValue($col_section.$currentContentRow, $section_name);
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
                $facultyname = "\n". $row['faculty_name'];

                
                         
                                

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
                          $spreadsheet->getActiveSheet()->mergeCells("$col_section$col_start:$col_section$col_end");
                          $spreadsheet->getActiveSheet()->getStyle($col_section.$col_end)->getAlignment()->setHorizontal('center');
                          $spreadsheet->getActiveSheet()->getStyle($col_section.$col_start)->getAlignment()->setWrapText(true); 
                          $spreadsheet->getActiveSheet()->getStyle("$col_section$col_start:$col_section$col_end")->applyFromArray($styleArray);
    }
}



        
        
        
  
      

}




function plot($day,$dept_id,$conn,$spreadsheet,$room_name,$col_sched,$col_room){





$plot_query = "SELECT 
                                cs.class_sched_id,
                                fl.faculty_id,
                                cs.day,
                                fc.firstname,
                                fc.lastname,
                                fc.middlename,
                                fc.suffix,
                                c.course_code,
                                pr.`program_abbrv`,
                                sc.section_name,
                                sc.`no_of_students`,
                                r.room_name,
                                t1.time_s,
                                t2.time_e,
                                t1.text_output AS 'class_start',
                                t1.time_s,
                                t1.ampm_start,
                                t2.time_e,
                                t2.text_output AS 'class_end',
                                t2.ampm_end,
                                dp.department_name,
                                s.`sem_description`,
                                ay.`acad_year`,
                                fl.needed
                                FROM class_schedule cs
                                LEFT JOIN faculty_loadings fl ON fl.`fac_load_id` = cs.`faculty_loading_id`
                              LEFT JOIN faculties fc ON fl.`faculty_id`=fc.`faculty_id`
                                    LEFT JOIN courses c ON fl.`course_id`=c.`course_id`
                                    LEFT JOIN sections sc ON fl.`section_id`=sc.`section_id`
                                    LEFT JOIN programs pr ON sc.`program_id`=pr.`program_id`
                                    LEFT JOIN departments dp ON dp.`department_id`=fl.`dept_id`
                                    LEFT JOIN semesters s ON s.semester_id = fl.sem_id
                                    LEFT JOIN academic_year ay ON ay.acad_year_id = fl.acad_year_id
                                    LEFT JOIN rooms r ON r.`room_id` = cs.room_id
                                    LEFT JOIN `time` t1 ON t1.`time_id` = cs.time_start_id
                                    LEFT JOIN `time` t2 ON t2.`time_id` = cs.time_end_id
                                    WHERE pr.`department_id` = '$dept_id'  
                                    AND s.status = 'ACTIVE'
                                    AND ay.status = 'ACTIVE'
                                    AND cs.day = '$day'
                                    AND r.room_name = '$room_name'
                                    GROUP BY cs.class_sched_id";
$data = [];


$plot = mysqli_query($conn, $plot_query);

while ($row = mysqli_fetch_array($plot)) {
    $firstname = $row['firstname'];
    $firstLettersFirstName = "";
    if(!empty($firstname)){
        $first_name_first_letter = explode(" ", $row['firstname']);
    
    
    foreach ($first_name_first_letter as $part) {
        $firstLettersFirstName .= $part[0] . ". ";
    }
    }

    

    $facultyname = $firstLettersFirstName . " " . $row['lastname'];
    $needed = $row['needed'];
    $course_code = $row['course_code'];
    $text_output_start = $row['class_start'];
    $time_start = $row['time_s'];
    $time_end = $row['time_e'];
    $text_output_end = $row['class_end'];
    $ampm_start = $row['ampm_start'];
    $ampm_end = $row['ampm_end'];
    $room_name = $row['room_name'];
    $section_name = "BS".$row['program_abbrv']." ".$row['section_name'];

    // Append the current row's data to the $data array
    $data[] = array(
        "faculty_name" => $facultyname,
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
        "needed" => $needed,
        "section_name" =>$section_name
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
                $facultyname = $row['faculty_name'];
                $room_name = $row['room_name'];
                $needed = "NeedLecturer";
                $section_name = $row['section_name'];

                if ($cellValueCurrent == $text_output_data && !in_array($text_output_data, $start) && !in_array($search_course_code, $course)) {
                    $start[] = $text_output_data; // Add the inserted value to the tracking array
                    $course[] = $search_course_code;
                    $index = -1; // Initialize with -1 to indicate not found
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
                        if(!empty($facultyname)){
                            $spreadsheet->getActiveSheet()
                                ->setCellValue($col_sched .$new_content_row, $search_course_code.$facultyname)
                                ->setCellValue($col_room.$new_content_row, $section_name);
                        }else{
                            $spreadsheet->getActiveSheet()
                                ->setCellValue($col_sched .$new_content_row, $search_course_code.$needed)
                                ->setCellValue($col_room.$new_content_row, $section_name);
                        }
                                                    
                    }else{
                        $data[$index]['col_start'] = $currentContentRow;
                        $b = $data[$index]['col_start'];
                        if(!empty($needed)){
                            $spreadsheet->getActiveSheet()
                                ->setCellValue($col_sched .$currentContentRow, $search_course_code.$needed)
                                ->setCellValue($col_room.$currentContentRow, $section_name);
                        }else{
                            $spreadsheet->getActiveSheet()
                                ->setCellValue($col_sched .$currentContentRow, $search_course_code.$facultyname)
                                ->setCellValue($col_room.$currentContentRow, $section_name);
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
                $facultyname = "\n". $row['faculty_name'];
                
                         
                                

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
                                                      $spreadsheet->getActiveSheet()
                                                      ->setCellValue($col_sched.$new_content_row, $search_course_code.$facultyname)
                                                      ->setCellValue($col_room.$new_content_row, $section_name);
                                                    
                                                }
                                                else{
                                                      $new_content_row = $currentContentRow + 1;
                                                    $data[$index]['col_end'] = $new_content_row;
                                               
                                               
                                                $b = $data[$index]['col_end'];
                                                 $spreadsheet->getActiveSheet()
                                                 ->setCellValue($col_sched.$currentContentRow, $search_course_code.$facultyname)
                                                 ->setCellValue($col_room.$currentContentRow, $section_name);
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



function plot_room_name($room_name,$spreadsheet,$department_name,$conn){ 
    $roomCell = 4;
       // Fill the cell with data
    $spreadsheet->getActiveSheet()->setCellValue('B'.$roomCell,$room_name);
    $dept_name_Cell = 3;
    // Fill the cell with data
    $spreadsheet->getActiveSheet()->setCellValue('B'.$dept_name_Cell,$department_name);


    $active_semester = mysqli_query($conn, "SELECT sem_description FROM semesters WHERE status = 'ACTIVE'");
    if(mysqli_num_rows($active_semester) > 0){
        $row = mysqli_fetch_array($active_semester);
        $semester = $row['sem_description'];
        $semseterWhere = 4;
        $semesterCell = 4;
        // Fill the cell with data
        $spreadsheet->getActiveSheet()->setCellValue('O'.$semesterCell,$semester . " Semester");
            // ... set other cell values
    }

    $active_acad_year = mysqli_query($conn,"SELECT acad_year FROM academic_year WHERE status = 'ACTIVE'");
    if(mysqli_num_rows($active_acad_year) > 0){
        $row = mysqli_fetch_array($active_acad_year);
        $academic_year = $row['acad_year'];
        $academic_year_cell = 4;
        // Fill the cell with data
        $spreadsheet->getActiveSheet()->setCellValue('Q'.$academic_year_cell,$academic_year);
        // ... set other cell values

    }
}







function plot_subject_info($dept_id,$conn,$spreadsheet,$room_name){


    $sql = " SELECT 
                                cs.class_sched_id,
                                fl.faculty_id,
                                cs.day,
                                fc.firstname,
                                fc.lastname,
                                fc.middlename,
                                fc.suffix,
                                c.course_code,
                                pr.`program_abbrv`,
                                sc.section_name,
                                sc.`no_of_students`,
                                r.room_name,
                                t1.time_s,
                                t2.time_e,
                                t1.text_output AS 'class_start',
                                t1.time_s,
                                t1.ampm_start,
                                t2.time_e,
                                t2.text_output AS 'class_end',
                                t2.ampm_end,
                                dp.department_name,
                                s.`sem_description`,
                                ay.`acad_year`,
                                fl.needed
                                FROM class_schedule cs
                                LEFT JOIN faculty_loadings fl ON fl.`fac_load_id` = cs.`faculty_loading_id`
                              LEFT JOIN faculties fc ON fl.`faculty_id`=fc.`faculty_id`
                                    LEFT JOIN courses c ON fl.`course_id`=c.`course_id`
                                    LEFT JOIN sections sc ON fl.`section_id`=sc.`section_id`
                                    LEFT JOIN programs pr ON sc.`program_id`=pr.`program_id`
                                    LEFT JOIN departments dp ON dp.`department_id`=fl.`dept_id`
                                    LEFT JOIN semesters s ON s.semester_id = fl.sem_id
                                    LEFT JOIN academic_year ay ON ay.acad_year_id = fl.acad_year_id
                                    LEFT JOIN rooms r ON r.`room_id` = cs.room_id
                                    LEFT JOIN `time` t1 ON t1.`time_id` = cs.time_start_id
                                    LEFT JOIN `time` t2 ON t2.`time_id` = cs.time_end_id
                                    WHERE pr.`department_id` = '$dept_id'  
                                    AND s.status = 'ACTIVE'
                                    AND ay.status = 'ACTIVE'
                                    
                                    AND r.room_name = '$room_name'
                                    GROUP BY cs.class_sched_id
";




$data = [];


$result = mysqli_query($conn,$sql);

while ($row = mysqli_fetch_array($result)) {
    $firstname = $row['firstname'];
    $firstLettersFirstName = "";
    if(!empty($firstname)){
        $first_name_first_letter = explode(" ", $firstname);
    
    
    foreach ($first_name_first_letter as $part) {
        $firstLettersFirstName .= $part[0] . ". ";
    }
    }

    $facultyname = $firstLettersFirstName . " " . $row['lastname'];

    $course_code = $row['course_code'];
   
    $total_studs = $row['no_of_students'];
    $needed = $row['needed'];
    $section_name = "BS".$row['program_abbrv']." ".$row['section_name'];

    // Append the current row's data to the $data array
    $data[] = array(
        "faculty_name" => $facultyname,
        "course_code" => $course_code,
        "students" => $total_studs,
        "needed" => $needed,
        "section" => $section_name
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
                $facultyname = $row['faculty_name'];
                $studs = $row['students'];
                $needed = $row['needed'];
                $section_name = $row['section'];
                     
                         $spreadsheet->getActiveSheet()
                      ->setCellValue('S'.$currentContentRow, $course_code)
                      ->setCellValue('T'.$currentContentRow, $section_name)
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
header('Content-Disposition: attachment;filename="BatStateU-FO-COL-29_Class.xlsx"');

//create IOFactory object
$writer = IOFactory::createWriter($spreadsheet, 'Xls');
//save into php output
$writer->save('php://output');



?>