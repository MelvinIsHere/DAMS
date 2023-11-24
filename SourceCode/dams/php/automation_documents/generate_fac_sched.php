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
ob_start();
$task_id = get_task_id();
$task_id_all = ob_get_clean();
$term_id = $_GET['term_id'];
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
    $monday_plot = faculty_plot_double_col('Monday',$dept_id,$conn,$spreadsheet,$tabName,'B','C','D',$term_id);
    $plot_tuesday = plot("Tuesday",$dept_id,$conn,$spreadsheet,$tabName,"E","F",$term_id);
    $plot_wednesday = plot("Wednesday",$dept_id,$conn,$spreadsheet,$tabName,"G","H",$term_id);
    $plot_Thursday = plot("Thursday",$dept_id,$conn,$spreadsheet,$tabName,"I","J",$term_id);

    $friday_plot = faculty_plot_double_col('Friday',$dept_id,$conn,$spreadsheet,$tabName,'K','L','M',$term_id);
    $saturday_plot = faculty_plot_double_col('Saturday',$dept_id,$conn,$spreadsheet,$tabName,'N','O','P',$term_id);
    $sunday_plot = plot("Sunday",$dept_id,$conn,$spreadsheet,$tabName,"Q","R",$term_id);
    
    $sched_info_plot = plot_sched_info($dept_id,$conn,$spreadsheet,$tabName);
    $plot_sched_header = plot_header_info($tabName,$spreadsheet,$department_name,$conn,$term_id);
        
    
  
        
}








function plot($day,$dept_id,$conn,$spreadsheet,$faculty,$col_sched,$col_room,$term_id){


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
            LEFT JOIN tasks tt ON tt.task_id = fs.task_id                             
            WHERE fs.department_id = '$dept_id'
            AND CONCAT(f.lastname,' ',f.firstname,' ',f.middlename,' ',f.suffix) = '$faculty'
            AND fs.day = '$day'  AND tt.term_id = '$term_id' AND fs.description != 'Official time morning'
            AND fs.description != 'Official time afternoon'
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
        "ampm_start" => $ampm_start,
        "ampm_end" => $ampm_end,
        "room_name" => $room_name,
        "description" => $description,
        "section" => $section
    );
}

foreach($data as $row){
    $text_output_start = $row['text_output_start'];
    $text_output_end = $row['text_output_end'];
    $course_code = $row["course_code"];
    $arr_ampm_start = $row['ampm_start'];
    $arr_ampm_end = $row['ampm_end'];
    $description = $row['description'];

    
    
    $room_name = $row['room_name'];
    
    $section = $row['section'];
    $col_start = 0;
    $col_end = 0;
    $row_index = 6;

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
                

                break; // Exit the loop once   the condition is met  
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
    // Finding col_end
    $row_index = 6;
    while ($row_index < 40) {
        $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_index)->getCalculatedValue();
        $ampm_cell = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(22, $row_index)->getCalculatedValue();
        if ($cellValueCurrent == $text_output_end) {
            if($ampm_cell == $arr_ampm_end){                                        
                $col_end = $row_index;
                
                break; // Exit the loop once the condition is met
            }                       
        }
        $row_index++;
    }


       if ($minutes == 30) {
        $col_start += 1;
    } elseif ($minutes_end == 0) {
        $col_end += 1;
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
    if ($description == "Class Schedule") {
        $spreadsheet->getActiveSheet()
            ->setCellValue($col_sched . $col_start, $course_code . "\n" . $section)
            ->setCellValue($col_room . $col_start, $room_name);
        $spreadsheet->getActiveSheet()->mergeCells("$col_sched$col_start:$col_sched$col_end");
        $spreadsheet->getActiveSheet()->mergeCells("$col_room$col_start:$col_room$col_end");
        $spreadsheet->getActiveSheet()->getStyle("$col_sched$col_start")->getAlignment()->setHorizontal('center');
        $spreadsheet->getActiveSheet()->getStyle("$col_room$col_start")->getAlignment()->setHorizontal('center');
        $spreadsheet->getActiveSheet()->getStyle($col_sched.$col_start)->getAlignment()->setWrapText(true); 
        $spreadsheet->getActiveSheet()->getStyle($col_room.$col_start)->getAlignment()->setWrapText(true);
         //set border to the sched - fac name course
        $spreadsheet->getActiveSheet()->getStyle($col_sched.$col_start)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
        $spreadsheet->getActiveSheet()->getStyle($col_sched.$col_end)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
          //set border to Room cell
        $spreadsheet->getActiveSheet()->getStyle($col_room.$col_start)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
        $spreadsheet->getActiveSheet()->getStyle($col_room.$col_end)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);  
        
    } else {
        
        $spreadsheet->getActiveSheet()->setCellValue($col_sched . $col_start, $description);
        $spreadsheet->getActiveSheet()->mergeCells("$col_sched$col_start:$col_room$col_end");
        //set text center
         $spreadsheet->getActiveSheet()->getStyle("$col_sched$col_start")->getAlignment()->setHorizontal('center');        
         //set border to the sched - fac name course
        $spreadsheet->getActiveSheet()->getStyle($col_sched.$col_start)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
        $spreadsheet->getActiveSheet()->getStyle($col_sched.$col_end)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
          //set border to Room cell
        $spreadsheet->getActiveSheet()->getStyle($col_room.$col_start)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
        $spreadsheet->getActiveSheet()->getStyle($col_room.$col_end)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);  
        

       
    }
    
}



          
        
  
}







function faculty_plot_double_col($day,$dept_id,$conn,$spreadsheet,$faculty,$col_sched_1,$col_sched_2,$col_room,$term_id){

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
            LEFT JOIN tasks tt ON tt.task_id = fs.task_id                             
            WHERE fs.department_id = '$dept_id'
            AND CONCAT(f.lastname,' ',f.firstname,' ',f.middlename,' ',f.suffix) = '$faculty'
            AND fs.day = '$day'  AND tt.term_id = '$term_id' AND fs.description != 'Official time morning'
            AND fs.description != 'Official time afternoon'
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
        "ampm_start" => $ampm_start,
        "ampm_end" => $ampm_end,
        "room_name" => $room_name,
        "description" => $description,
        "section" => $section
    );
}



foreach($data as $row){
    $text_output_start = $row['text_output_start'];
    $text_output_end = $row['text_output_end'];
    $course_code = $row["course_code"];
    $arr_ampm_start = $row['ampm_start'];
    $arr_ampm_end = $row['ampm_end'];
    $description = $row['description'];

    
    
    $room_name = $row['room_name'];
    
    $section = $row['section'];
    $col_start = 0;
    $col_end = 0;
    $row_index = 6;

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
                

                break; // Exit the loop once   the condition is met  
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
    // Finding col_end
    $row_index = 6;
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


    if($text_output_start == '11:00 - 12:00'){
        $col_start = 16;                    
    }
    if($text_output_end == '11:00 - 12:00'){
        $col_end = 17;                        
    }
    if($text_output_start == '11:00 - 12:00' && $text_output_end == '11:00 - 12:00'){
        $col_start = 16;
        $col_end = 17;
    }
    if ($minutes == 30) {
        $col_start = $col_start +1;
    }elseif ($minutes_end == 0) {
        $col_end = $col_end + 1;
        
    }
    


    if ($description == "Class Schedule") {
        $spreadsheet->getActiveSheet()
            ->setCellValue($col_sched_1 . $col_start, $course_code . "\n" . $section)
            ->setCellValue($col_room . $col_start, $room_name);
        $spreadsheet->getActiveSheet()->mergeCells("$col_sched_1$col_start:$col_sched_2$col_end");
        $spreadsheet->getActiveSheet()->mergeCells("$col_room$col_start:$col_room$col_end");
        $spreadsheet->getActiveSheet()->getStyle("$col_sched_1$col_start")->getAlignment()->setHorizontal('center');
        $spreadsheet->getActiveSheet()->getStyle("$col_room$col_start")->getAlignment()->setHorizontal('center');
        $spreadsheet->getActiveSheet()->getStyle($col_sched_1.$col_start)->getAlignment()->setWrapText(true); 
        $spreadsheet->getActiveSheet()->getStyle($col_room.$col_start)->getAlignment()->setWrapText(true);
         //set border to the sched - fac name course
        $spreadsheet->getActiveSheet()->getStyle($col_sched_1.$col_start.":".$col_room.$col_start)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                $spreadsheet->getActiveSheet()->getStyle($col_sched_1.$col_end.":".$col_room.$col_end)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        
    } else {
        
        $spreadsheet->getActiveSheet()->setCellValue($col_sched_1 . $col_start, $description);
        $spreadsheet->getActiveSheet()->mergeCells("$col_sched_1$col_start:$col_room$col_end");
        //set text center
         $spreadsheet->getActiveSheet()->getStyle("$col_sched_1$col_start")->getAlignment()->setHorizontal('center');        
         //set border to the sched - fac name course
        $spreadsheet->getActiveSheet()->getStyle($col_sched_1.$col_start.":".$col_room.$col_start)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
        $spreadsheet->getActiveSheet()->getStyle($col_sched_1.$col_end.":".$col_room.$col_end)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
      

       
    }
    
}


}









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
            GROUP BY s.section_name,p.program_abbrv
            
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
function get_task_id(){
    include "../config.php";
    $query = mysqli_query($conn,"SELECT 
                                    t.term_id,
                                    t.status,
                                    tt.task_name,
                                    tt.`task_id` AS task_id

                                FROM tasks tt 
                                LEFT JOIN terms t ON t.term_id = tt.`term_id`  
                                WHERE t.status = 'ACTIVE'  AND tt.`task_name` = 'Faculty Schedule'");
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


function plot_header_info($faculty,$spreadsheet,$department_name,$conn,$term_id){
    $spreadsheet->getActiveSheet()
        ->setCellValue("B3",$department_name)
        ->setCellValue("B4", $faculty);
    $query = mysqli_query($conn,"SELECT s.sem_description FROM terms t
                                LEFT JOIN semesters s ON s.semester_id = t.semester_id
                                WHERE t.term_id = '$term_id'");
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

    $acad_query = mysqli_query($conn, "SELECT a.acad_year 
                                        FROM terms t 
                                        LEFT JOIN academic_year a ON a.acad_year_id = t.acad_year_id
                                        WHERE t.term_id = '$term_id'");
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



//set the header first, so the result will be treated as an xlsx file.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

//make it an attachment so we can define filename
header('Content-Disposition: attachment;filename="BatStateU-FO-COL-27_Faculty-Schedule_Rev.-01.xlsx"');

//create IOFactory object
$writer = IOFactory::createWriter($spreadsheet, 'Xls');
//save into php output
$writer->save('php://output');



?>