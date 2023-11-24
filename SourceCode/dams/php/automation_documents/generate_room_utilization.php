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



ob_start();
$task_id = get_task_id();
$task_id_all = ob_get_clean();
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
    $plot_monday = plot_double_col('Monday',$department_id,$conn,$spreadsheet,$tabName,'B','C','D',$term_id);
    $plot_tuesday = plot('Tuesday',$department_id,$conn,$spreadsheet,$tabName,'E','F',$term_id);
    $plot_wednesday = plot('Wednesday',$department_id,$conn,$spreadsheet,$tabName,'G','H',$term_id);
    $plot_Thursday = plot('Thursday',$department_id,$conn,$spreadsheet,$tabName,'I','J',$term_id);
    // $plot_friday = plot_double_col('Friday',$department_id,$conn,$spreadsheet,$tabName,'K','L','M',$term_id);
    // $plot_saturday = plot_double_col('Saturday',$department_id,$conn,$spreadsheet,$tabName,'N','O','P',$term_id);
    $plot_sunday = plot('Sunday',$department_id,$conn,$spreadsheet,$tabName,'Q','R',$term_id);
    $plot_header = plot_room_name($tabName,$spreadsheet,$department_name,$conn,$term_id);
    $plot_room_info =plot_subject_info($department_id,$conn,$spreadsheet,$tabName,$term_id);

    



    //set program chair


       $query = mysqli_query($conn, "SELECT 
                                  d.designation,
                                  CONCAT(f.firstname,' ', f.middlename,' ', f.lastname,' ', f.suffix) AS 'facultyname'
                              FROM faculties f
                              LEFT JOIN departments dp ON dp.`department_id` = f.department_id
                              LEFT JOIN faculty_designation fd ON fd.faculty_id = f.faculty_id    
                              LEFT JOIN designation d ON d.designation_id = fd.designation_id   
                              WHERE d.designation = 'Program Chair' AND dp.department_id = '$department_id'");

       if ($query) {
           if (mysqli_num_rows($query) > 0) {
               $row = mysqli_fetch_assoc($query);
               $designations = $row['designation'];
               $facultyname = $row['facultyname'];

               // Use $facultyname as needed, e.g., set it in the spreadsheet
               $spreadsheet->getActiveSheet()->setCellValue("S21", $facultyname);
           } else {
               // Handle the case when there are no results
           }
       } else {
           // Handle the case when the query fails
       }

       //set vice chancellor
       $query = mysqli_query($conn, "SELECT 
                                   d.designation,
                                  CONCAT(f.firstname,
                                   f.middlename,
                                   f.lastname,
                                   f.suffix) AS 'facultyname'
                            FROM faculties f
                            LEFT JOIN departments dp ON dp.`department_id` = f.department_id
                            LEFT JOIN faculty_designation fd ON fd.faculty_id = f.faculty_id    
                            LEFT JOIN designation d ON d.designation_id = fd.designation_id   
                            WHERE d.designation = 'Vice Chancellor for Academic Affairs'");

       if ($query) {
           if (mysqli_num_rows($query) > 0) {
               $row = mysqli_fetch_assoc($query);
               $designations = $row['designation'];
               $facultyname = $row['facultyname'];

               // Use $facultyname as needed, e.g., set it in the spreadsheet
               $spreadsheet->getActiveSheet()->setCellValue("S31", $facultyname);
           } else {
               // Handle the case when there are no results
           }
       } else {
           // Handle the case when the query fails
       }


       //set dean or head
       $query = mysqli_query($conn, "SELECT 
                                   d.designation,
                                  CONCAT(f.firstname,
                                   f.middlename,
                                   f.lastname,
                                   f.suffix) AS 'facultyname'
                            FROM faculties f
                            LEFT JOIN departments dp ON dp.`department_id` = f.department_id
                            LEFT JOIN faculty_designation fd ON fd.faculty_id = f.faculty_id    
                            LEFT JOIN designation d ON d.designation_id = fd.designation_id   
                            WHERE d.designation = 'Dean' OR d.designation ='Head' AND dp.department_id = '$department_id'");

       if ($query) {
           if (mysqli_num_rows($query) > 0) {
               $row = mysqli_fetch_assoc($query);
               $designations = $row['designation'];
               $facultyname = $row['facultyname'];

               // Use $facultyname as needed, e.g., set it in the spreadsheet
               $spreadsheet->getActiveSheet()->setCellValue("S26", $facultyname);
           } else {
               // Handle the case when there are no results
           }
       } else {
           // Handle the case when the query fails
       }


       //set ovcaa or deans


    
    echo clear_redundant($spreadsheet);
    // // if($tabName === "HEB LAB 2"){
    //     $spreadsheet->getActiveSheet()->setCellValue('B6', $tabName);
    // }else{

    // }
        
}

   
function clear_redundant($spreadsheet){
       $row=6;
       while($row != 40){
               $spreadsheet->getActiveSheet()->setCellValue("V".$row," ");
                     
              $row++;
       }
}


function plot($day,$dept_id,$conn,$spreadsheet,$room_name,$col_sched,$col_room,$term_id){





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
                                    LEFT JOIN tasks tt ON tt.task_id = cs.task_id
                                    WHERE pr.`department_id` = '$dept_id'  
                                    AND tt.term_id = '$term_id'
                                    AND cs.day = '$day'
                                    AND r.room_name = '$room_name'
                                    GROUP BY cs.class_sched_id";
$data = [];


$plot = mysqli_query($conn, $plot_query);

while ($row = mysqli_fetch_array($plot)) {
    $firstname = $row['firstname'];
    $firstLettersFirstName = "";
    $facultyname ="";
    if(!empty($firstname)){
        $first_name_first_letter = explode(" ", $row['firstname']);
       foreach ($first_name_first_letter as $part) {
               $firstLettersFirstName .= $part[0] . ". ";
       }
       $facultyname = $firstLettersFirstName . " " . $row['lastname'];
    }else{
       $facultyname = "Need Lecturer";
    }

    
      
                                
    
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
    $faculty_id = $row['faculty_id'];

    // Append the current row's data to the $data array
    $data[] = array(
        "faculty_id" => $faculty_id,
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

       foreach ($data as $row) {
           
                
                $text_output_start = $row['text_output_start'];
                $text_output_end = $row['text_output_end'];
                $course_code = $row["course_code"];
                $arr_ampm_start = $row['ampm_start'];
                $arr_ampm_end = $row['ampm_end'];
                $inserted =$row['inserted'];
                $facultyname = $row['faculty_name'];
                $room_name = $row['room_name'];
                $needed = "NeedLecturer";
                $section_name = $row['section_name'];
                $col_start = 0;
                $col_end = 0;
                $faculty_id = $row['faculty_id'];
                //for getting the col start
               $row_index = 6;
                 //class start minutes if the minutes is 30 just add one index
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

                     // Finding col_start
                     while ($row_index < 40) {
                         $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_index)->getCalculatedValue();
                         $ampm_cell = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(22, $row_index)->getCalculatedValue();
                         
                         if ($cellValueCurrent == $text_output_start) {
                            if($ampm_cell == $arr_ampm_start){
                                   $col_start = $row_index;
                                   break; // Exit the loop once the condition is met  
                            }
                           
                         }

                         $row_index++;
                     }
                            //class end minutes if the minutes is 30 just add one index
                       $time_string_end  = $row['time_end'];
                       $time_parts_end = explode(" ", $time_string_end); //when the start is like half an hour
                       // Extract the time part
                       $time_end = $time_parts_end[0];
                       // Split the time by ":" to get hours and minutes
                       $time_parts_end = explode(":", $time_end);
                       // Extract the minutes portion
                       $minutes_end = $time_parts_end[1];
                       // Convert the minutes to an integer if needed
                       $minutes_end = intval($minutes_end);
                     // Reset row_index for finding col_end
                     $row_index = 6;

                     // Finding col_end
                     while ($row_index < 40) {
                         $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_index)->getCalculatedValue();
                         $ampm_cell = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(23, $row_index)->getCalculatedValue();
                         
                         if ($cellValueCurrent == $text_output_end) {
                            if($ampm_cell == $arr_ampm_end){
                                        
                             $col_end = $row_index;
                             break; // Exit the loop once the condition is met
                            }
                       
                         }

                         $row_index++;
                     }

                    if($minutes == 30){
                            $col_start = $col_start +1;
                     }
                     
                     if($minutes_end == 0){
                            $col_end = $col_end +1;      
                     }

                      //nilagay ko to kasi nag sasala pag transition sa 11 -12 yung sa Am - PM
                    if($text_output_start == '11:00 - 12:00' && $text_output_end == '11:00 - 12:00'){
                        $col_start = 16;
                        $col_end = 17;
                    }
                    if($text_output_start == '11:00 - 12:00'){
                        $col_start = 16;
                        
                    }
                     if($text_output_end == '11:00 - 12:00'){
                        $col_end = 17;
                        
                    }
                    if(empty($faculty_id)){
                        $spreadsheet->getActiveSheet()->setCellValue("$col_sched$col_start",$course_code."\n Need Lecturer")
                     ->setCellValue("$col_room$col_start",$section_name);
                    }else{
                        $spreadsheet->getActiveSheet()->setCellValue("$col_sched$col_start",$course_code."\n".$facultyname)
                     ->setCellValue("$col_room$col_start",$section_name); 
                    }
                     
                     
                     $spreadsheet->getActiveSheet()->mergeCells("$col_sched$col_start:$col_sched$col_end");
                     $spreadsheet->getActiveSheet()->mergeCells("$col_room$col_start:$col_room$col_end");
                     $spreadsheet->getActiveSheet()->getStyle("$col_sched$col_start")->getAlignment()->setHorizontal('center');
                     $spreadsheet->getActiveSheet()->getStyle("$col_room$col_start")->getAlignment()->setHorizontal('center');
                     $spreadsheet->getActiveSheet()->getStyle($col_sched.$col_start)->getAlignment()->setWrapText(true); 
                     $spreadsheet->getActiveSheet()->getStyle($col_room.$col_start)->getAlignment()->setWrapText(true);
                     //set border to the sched - fac name course
                     $spreadsheet->getActiveSheet()->getStyle($col_sched.$col_start)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                     $spreadsheet->getActiveSheet()->getStyle($col_sched.$col_end)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                     //set border to section cell
                     $spreadsheet->getActiveSheet()->getStyle($col_room.$col_start)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                     $spreadsheet->getActiveSheet()->getStyle($col_room.$col_end)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                  
                           
                   
              }

 
  

 
}














function plot_double_col($day,$dept_id,$conn,$spreadsheet,$room_name,$col_sched_1,$col_sched_2,$col_section,$term_id){




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
                                    LEFT JOIN tasks tt ON tt.task_id = cs.task_id
                                    WHERE pr.`department_id` = '$dept_id'  
                                    AND tt.term_id = '$term_id'
                                    AND cs.day = '$day'
                                    AND r.room_name = '$room_name'
                                    GROUP BY cs.class_sched_id";
$data = [];


$plot = mysqli_query($conn, $plot_query);

while ($row = mysqli_fetch_array($plot)) {
    $firstname = $row['firstname'];
    $firstLettersFirstName = "";
    $facultyname ="";
    if(!empty($firstname)){
        $first_name_first_letter = explode(" ", $row['firstname']);
       foreach ($first_name_first_letter as $part) {
               $firstLettersFirstName .= $part[0] . ". ";
       }
       $facultyname = $firstLettersFirstName . " " . $row['lastname'];
    }else{
       $facultyname = "Need Lecturer";
    }

    
      
                                
    
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
        "time_e" => $time_end,
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

 





$currentContentRow = 6;

     $alreadyInserted = []; // Initialize an array to keep track of inserted $text_output_data values
        // Fill the cell with data
$start= [];
$course = [];
$sample = [];

       foreach ($data as $row) {
           
                
                $text_output_start = $row['text_output_start'];
                $text_output_end = $row['text_output_end'];
                $course_code = $row["course_code"];
                $arr_ampm_start = $row['ampm_start'];
                $arr_ampm_end = $row['ampm_end'];
                $inserted =$row['inserted'];
                $facultyname = $row['faculty_name'];
                $room_name = $row['room_name'];
                $needed = "NeedLecturer";
                $section_name = $row['section_name'];
                $col_start = 0;
                $col_end = 0;
                //for getting the col start
               
                //class start minutes if the minutes is 30 just add one index
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

                     $row_index = 6;


                     // Finding col_start
                     while ($row_index < 40) {
                         $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_index)->getCalculatedValue();
                         $ampm_cell = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(22, $row_index)->getCalculatedValue();
                         
                         if ($cellValueCurrent == $text_output_start) {
                            if($ampm_cell == $arr_ampm_start){
                                   $col_start = $row_index;
                                  
                                   
                                   break; // Exit the loop once the condition is met  
                            }
                           
                         }

                         $row_index++;
                     }

                        //class end minutes if the minutes is 30 just add one index
                       $time_string_end  = $row['time_end'];
                       $time_parts_end = explode(" ", $time_string_end); //when the start is like half an hour
                       // Extract the time part
                       $time_end = $time_parts_end[0];
                       // Split the time by ":" to get hours and minutes
                       $time_parts_end = explode(":", $time_end);
                       // Extract the minutes portion
                       $minutes_end = $time_parts_end[1];
                       // Convert the minutes to an integer if needed
                       $minutes_end = intval($minutes_end);
                     // Reset row_index for finding col_end
                     $row_index = 6;

                     // Finding col_end
                     while ($row_index < 40) {
                         $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_index)->getCalculatedValue();
                         $ampm_cell = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(23, $row_index)->getCalculatedValue();
                         
                         if ($ampm_cell == $arr_ampm_end && $cellValueCurrent == $text_output_end) {
                            //     $spreadsheet->getActiveSheet()->setCellValue("X".$row_index,$ampm_cell);
                            // if($cellValueCurrent == $text_output_end){
                            //      $spreadsheet->getActiveSheet()->setCellValue("X".$row_index,$cellValueCurrent);
                             $col_end = $row_index; 
                             
                                   
                             // echo "<script>console.log('$room_name,$day,$cellValueCurrent,,$row_index,$ampm_cell')</script>";
                             break; // Exit the loop once the condition is met
                            // }
                            
                         }

                         $row_index++;
                     }

                    
                    if($text_output_start == '11:00 - 12:00'){
                        $col_start = 16;
                        
                    }
                     if($text_output_end == '11:00 - 12:00'){
                        $col_end = 17;
                        
                    }
                       //nilagay ko to kasi nag sasala pag transition sa 11 -12 yung sa Am - PM
                    if($text_output_start == '11:00 - 12:00' && $text_output_end == '11:00 - 12:00'){
                        $col_start = 16;
                        $col_end = 17;
                    }
                     if($minutes == 30){
                            $col_start = $col_start +1;
                     }
                     if($minutes_end == 0){
                            $col_end = $col_end + 1;
                     }
                    
                     $spreadsheet->getActiveSheet()->setCellValue($col_sched_1.$col_start,$course_code."\n".$facultyname)
                     ->setCellValue("$col_section$col_start",$section_name);
                     
                     
                     // $spreadsheet->getActiveSheet()->setCellValue($col_sched_1.$col_start,$col_start);
                     // $spreadsheet->getActiveSheet()->setCellValue($col_sched_2.$col_end,$text_output_end." ".$arr_ampm_end);
                     $spreadsheet->getActiveSheet()->mergeCells("$col_sched_1$col_start:$col_sched_2$col_end");
                     $spreadsheet->getActiveSheet()->mergeCells("$col_section$col_start:$col_section$col_end");
                     $spreadsheet->getActiveSheet()->getStyle("$col_sched_1$col_start")->getAlignment()->setHorizontal('center');
                     $spreadsheet->getActiveSheet()->getStyle($col_sched_1.$col_start)->getAlignment()->setWrapText(true); 
                     //set border to the sched - fac name course
                     $spreadsheet->getActiveSheet()->getStyle($col_sched_1.$col_start.":".$col_sched_2.$col_start)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                     $spreadsheet->getActiveSheet()->getStyle($col_sched_1.$col_end.":".$col_sched_2.$col_end)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                     //set border to section cell
                     $spreadsheet->getActiveSheet()->getStyle($col_section.$col_start)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                     $spreadsheet->getActiveSheet()->getStyle($col_section.$col_end)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                  
                                                       
                    
                                                    
                     // $currentContentRow++;     |                           
                   
              }
      

}

function plot_room_name($room_name,$spreadsheet,$department_name,$conn,$term_id){ 
    $roomCell = 4;
       // Fill the cell with data
    $spreadsheet->getActiveSheet()->setCellValue('B'.$roomCell,$room_name);
    $dept_name_Cell = 3;
    // Fill the cell with data
    $spreadsheet->getActiveSheet()->setCellValue('B'.$dept_name_Cell,$department_name);


    $active_semester = mysqli_query($conn, "SELECT s.sem_description FROM terms t 
                                            LEFT JOIN semesters s ON s.semester_id = t.semester_id
                                            WHERE t.term_id = '$term_id'");
    if(mysqli_num_rows($active_semester) > 0){
        $row = mysqli_fetch_array($active_semester);
        $semester = $row['sem_description'];
        $semseterWhere = 4;
        $semesterCell = 4;
        // Fill the cell with data
        $spreadsheet->getActiveSheet()->setCellValue('O'.$semesterCell,$semester . " Semester");
            // ... set other cell values
    }

    $active_acad_year = mysqli_query($conn,"SELECT a.acad_year FROM terms t 
                                            LEFT JOIN academic_year a ON a.acad_year_id = t.acad_year_id
                                            WHERE t.term_id = '$term_id'");
    if(mysqli_num_rows($active_acad_year) > 0){
        $row = mysqli_fetch_array($active_acad_year);
        $academic_year = $row['acad_year'];
        $academic_year_cell = 4;
        // Fill the cell with data
        $spreadsheet->getActiveSheet()->setCellValue('Q'.$academic_year_cell,$academic_year);
        // ... set other cell values

    }
}



function plot_subject_info($dept_id,$conn,$spreadsheet,$room_name,$term_id){


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
                                    LEFT JOIN tasks tt ON tt.task_id = cs.task_id
                                    WHERE pr.`department_id` = '$dept_id'  
                                    AND tt.term_id = '$term_id'
                                    
                                    
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

function getprogramchair($spreadsheet,$department_id){
       include '../config.php';
       $query = mysqli_query($conn,"          
              
                            SELECT 
                                   d.designation,
                                  CONCAT(f.firstname,
                                   f.middlename,
                                   f.lastname,
                                   f.suffix) AS 'facultyname'
                            FROM faculties f
                            LEFT JOIN departments dp ON dp.`department_id` = f.department_id
                            LEFT JOIN faculty_designation fd ON fd.faculty_id = f.faculty_id    
                            LEFT JOIN designation d ON d.designation_id = fd.designation_id   
                            WHERE d.designation = 'Program Chair' AND dp.department_id = '$department_id'");
       if($query){
              if(mysqli_num_rows($query) > 0){
                     $row = mysqli_fetch_assoc($query);
                     ob_start();
                     
                     $facultyname = $row['facultyname'];
                     $facultyname = ob_get_clean();
                     $spreadsheet->getActiveSheet()->setCellValue("S21",$facultyname);
                     // echo $department_id;

              }else{
                     var_dump(mysql_error($conn));
              }
       }else{
              var_dump(mysql_error($conn));
       }

}
function get_task_id(){
    include "../config.php";
    $query = mysqli_query($conn,"SELECT 
                                    t.term_id,
                                    t.status,
                                    tt.task_name,
                                    tt.`task_id` AS task_id

                                FROM tasks tt 
                                LEFT JOIN terms t ON t.term_id = tt.`term_id`  
                                WHERE t.status = 'ACTIVE'  AND tt.`task_name` = 'Class Schedule'");
    if($query){
        if(mysqli_num_rows($query) >0){
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
header('Content-Disposition: attachment;filename="Room Utilization Matrix.xlsx"');

//create IOFactory object
$writer = IOFactory::createWriter($spreadsheet, 'Xls');
//save into php output
$writer->save('php://output');




?>