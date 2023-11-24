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
$spreadsheet = $reader->load("../../templates/BatStateU-FO-COL-29_Class Schedule.xlsx");

//add the content
//data from database

$conn = new mysqli("localhost", "root", "", "dams2");
if(!$conn){
    exit("database connection error");
}
// ...


$dept_id = $_GET['dept_id'];
$dept_abbrv = $_GET['dept_abbrv'];
// $semester_id = $_SESSION['semester_id'];
// $acad_id = $_SESSION['acad_id'];
$department_name = $_GET['department_name'];
$term_id = $_GET['term_id'];


// here is the first query for the insertion


















$sections = []; // Array for room names


$sql = "

SELECT DISTINCT                            
    pr.`program_abbrv`,
    sc.section_name
    FROM class_schedule cs
    LEFT JOIN faculty_loadings fl ON fl.`fac_load_id` = cs.`faculty_loading_id`
    LEFT JOIN sections sc ON fl.`section_id`=sc.`section_id`
    LEFT JOIN programs pr ON sc.`program_id`=pr.`program_id`
    LEFT JOIN departments dp ON dp.`department_id`=fl.`dept_id`
    LEFT JOIN tasks tt ON tt.task_id = cs.task_id

    WHERE fl.dept_id = '$dept_id'  AND tt.term_id = '$term_id'";

$execute = mysqli_query($conn, $sql);

if ($execute) {
    if (mysqli_num_rows($execute) > 0) {
        while ($row = mysqli_fetch_array($execute)) {
            // Create a section name like "BSProgramAbbrv SectionName"
            $section = 'BS'.$row['program_abbrv'] . " " . $row['section_name'];
            $sections[] = $section;
        }
    }
}

// Assuming $spreadsheet is your PhpSpreadsheet instance



foreach ($sections as $tabName) {
    // Clone the 'BLANK' sheet
    $clonedWorksheet = clone $spreadsheet->getSheetByName('BLANK FORM');
    $clonedWorksheet->setTitle($tabName); // Set a meaningful title for the cloned sheet
    
    // // Add the cloned sheet to the spreadsheet
    $spreadsheet->addSheet($clonedWorksheet);
    // // Set the newly added sheet as the active sheet
    $spreadsheet->setActiveSheetIndexByName($tabName);

    $plot_monday = plot_double_col("Monday",$dept_id,$conn,$spreadsheet,$tabName,"B","C","D",$term_id);

    $plot_tuesday = plot("Tuesday",$dept_id,$conn,$spreadsheet,$tabName,"E","F",$term_id);
    $plot_wednesday = plot("Wednesday",$dept_id,$conn,$spreadsheet,$tabName,"G","H",$term_id);
    $plot_Thursday = plot("Thursday",$dept_id,$conn,$spreadsheet,$tabName,"I","J",$term_id);
    $plot_friday =  plot_double_col("Friday",$dept_id,$conn,$spreadsheet,$tabName,"K","L","M",$term_id);
    $plot_saturday =  plot_double_col("Saturday",$dept_id,$conn,$spreadsheet,$tabName,"N","O","P",$term_id);
    $plot_sunday = plot("Sunday",$dept_id,$conn,$spreadsheet,$tabName,"Q","R",$term_id);
    $plot_subject_info = plot_subject_info($dept_id,$conn,$spreadsheet,$tabName,$term_id);
    $plot_header_info = plot_header_info($conn,$spreadsheet,$tabName,$term_id,$department_name);
    $plot_people = people($conn,$dept_id,$spreadsheet,$tabName);


    
        
}



function plot_header_info($conn,$spreadsheet,$section,$term_id,$department_name){
        $spreadsheet->getActiveSheet()
            ->setCellValue("B3",$department_name);
        $spreadsheet->getActiveSheet()
            ->setCellValue("B4",$section);
        $spreadsheet->getActiveSheet()
            ->setCellValue("O3","ARASOF");

        $semester_query = mysqli_query($conn,"SELECT 
                                                    s.sem_description 
                                            FROM terms t 
                                            LEFT JOIN semesters s ON s.semester_id = t.semester_id
                                            WHERE t.term_id = '$term_id'");
        if($semester_query){
            if(mysqli_num_rows($semester_query) >0){
                $row = mysqli_fetch_assoc($semester_query);
                $sem_des = $row['sem_description'];
                 $spreadsheet->getActiveSheet()
                    ->setCellValue("O4",$sem_des);

            }else{

            }
        }else{

        }         
        $acad_query = mysqli_query($conn,"SELECT 
                                                a.acad_year 
                                        FROM terms t  
                                        LEFT JOIN academic_year a ON a.acad_year_id = t.acad_year_id
                                        WHERE t.term_id = '$term_id'");
        if($acad_query){
            if(mysqli_num_rows($acad_query) >0){
                $row = mysqli_fetch_assoc($acad_query);
                $acad = $row['acad_year'];
                 $spreadsheet->getActiveSheet()
                    ->setCellValue("S4",$acad);

            }else{

            }
        }else{

        }            
}


function people($conn,$dept_id,$spreadsheet,$section){
    $vice_chancellor = mysqli_query($conn,"SELECT 
                                        f.`firstname`,
                                        f.`middlename`,
                                        f.`lastname`,
                                        f.`suffix`,
                                        d.`designation`
                                    FROM faculties f 
                                    LEFT JOIN designation d ON d.`designation_id` = f.`designation_id`
                                    LEFT JOIN departments dp ON dp.department_id = f.`department_id`
                                    WHERE dp.department_id = '$dept_id' 
                                    AND d.designation = 'Vice Chancellor for Academic Affairs'");
    if($vice_chancellor){
        if(mysqli_num_rows($vice_chancellor) > 0){
            $row = mysqli_fetch_assoc($vice_chancellor);
            $person = $row['firstname']." ".$row['middlename']." ".$row['lastname']." ".$row['suffix'];

             $spreadsheet->getActiveSheet()
                    ->setCellValue("S31",$person);

        }
    }else{

    }



    //program chair

     $program_chair = mysqli_query($conn,"SELECT 
                                        f.`firstname`,
                                        f.`middlename`,
                                        f.`lastname`,
                                        f.`suffix`,
                                        d.`designation`
                                    FROM faculties f 
                                    LEFT JOIN designation d ON d.`designation_id` = f.`designation_id`
                                    LEFT JOIN departments dp ON dp.department_id = f.`department_id`
                                    WHERE dp.department_id = '$dept_id' 
                                    AND d.designation = 'Program Chair'");
    if($program_chair){
        if(mysqli_num_rows($program_chair) > 0){
            $row = mysqli_fetch_assoc($program_chair);
            $person = $row['firstname']." ".$row['middlename']." ".$row['lastname']." ".$row['suffix'];

             $spreadsheet->getActiveSheet()
                    ->setCellValue("S16",$person);

        }
    }else{

    }

    //adviser

    

     $adviser_query = mysqli_query($conn,"SELECT                                                
                                                f.firstname,
                                                f.middlename,
                                                f.lastname,
                                                f.suffix
                                        FROM sections s 
                                        LEFT JOIN programs p ON p.`program_id` = s.`program_id`
                                        LEFT JOIN faculties f ON f.faculty_id = s.adviser_id
                                        WHERE p.`department_id` = '$dept_id'
                                        AND CONCAT('BS',p.program_abbrv,' ',s.section_name ) = '$section'");
    if($adviser_query){
        if(mysqli_num_rows($adviser_query) > 0){
            $row = mysqli_fetch_assoc($adviser_query);
            $adviser = $row['firstname']." ".$row['middlename']." ".$row['lastname']." ".$row['suffix'];

             $spreadsheet->getActiveSheet()
                    ->setCellValue("S26",$adviser);

        }
    }else{

    }




    //dean
    $dean_query = mysqli_query($conn,"SELECT 
                                        f.`firstname`,
                                        f.`middlename`,
                                        f.`lastname`,
                                        f.`suffix`
                                    FROM faculties f 
                                    LEFT JOIN titles t ON t.title_id = f.`position_id`
                                    WHERE t.title_description = 'Dean'
                                    AND f.department_id = '$dept_id'");
    if($dean_query){
        if(mysqli_num_rows($dean_query) > 0){
            $row = mysqli_fetch_assoc($dean_query);
            $dean = $row['firstname']." ".$row['middlename']." ".$row['lastname']." ".$row['suffix'];

             $spreadsheet->getActiveSheet()
                    ->setCellValue("S21",$dean);

        }
    }else{

    }

}















// $friday = "Monday";
// $tuesday = "Tuesday";

// $course_codes = [];

function plot($day,$dept_id,$conn,$spreadsheet,$section,$col_sched,$col_room,$term_id){




$plot_query = "  SELECT 
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
                                    
                                    AND CONCAT('BS',pr.program_abbrv,' ',sc.section_name) ='$section'
                                    AND cs.day = '$day'
                                    GROUP BY cs.class_sched_id";
$data = [];


$plot = mysqli_query($conn, $plot_query);

while ($row = mysqli_fetch_array($plot)) {
    $firstname = $row['firstname'];
    $firstLettersFirstName = "";
    if(!empty($firstname)){
        $first_name_first_letter = explode(" ", $firstname);
    
    
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
        "room_name" => $room_name
      
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
                $faculty_id = $row['faculty_id'];
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
                            if(empty($faculty_id)){
                                $spreadsheet->getActiveSheet()
                                    ->setCellValue($col_sched .$new_content_row, $search_course_code."\nNeed Lecturer")
                                    ->setCellValue($col_room.$new_content_row, $room_name);
                            }else{
                                $spreadsheet->getActiveSheet()
                                    ->setCellValue($col_sched .$new_content_row, $search_course_code."\n".$facultyname)
                                    ->setCellValue($col_room.$new_content_row, $room_name);
                            }
                                                    
                                                    
                        }else{
                            $data[$index]['col_start'] = $currentContentRow;                                               
                            $b = $data[$index]['col_start'];
                            if(empty($facultyname)){
                                $spreadsheet->getActiveSheet()
                                    ->setCellValue($col_sched .$currentContentRow, $search_course_code."\nNeed Lecturer")
                                    ->setCellValue($col_room.$currentContentRow, $room_name);
                            }else{
                                $spreadsheet->getActiveSheet()
                                    ->setCellValue($col_sched .$currentContentRow, $search_course_code."\n".$facultyname)
                                    ->setCellValue($col_room.$currentContentRow, $room_name);
                            }
                                              
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
                                                      // $spreadsheet->getActiveSheet()
                                                      // ->setCellValue($col_sched.$new_content_row, $search_course_code.$facultyname)
                                                      // ->setCellValue($col_room.$new_content_row, $room_name);
                                                    
                                                }
                                                else{
                                                      $new_content_row = $currentContentRow + 1;
                                                    $data[$index]['col_end'] = $new_content_row;
                                               
                                               
                                                $b = $data[$index]['col_end'];
                                                 // $spreadsheet->getActiveSheet()
                                                 // ->setCellValue($col_sched.$currentContentRow, $search_course_code.$facultyname)
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




function plot_double_col($day,$dept_id,$conn,$spreadsheet,$section,$col_sched_1,$col_sched_2,$col_room,$term_id){



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
                                    LEFT JOIN tasks tt ON tt.task_id = cs.task_id
                                    WHERE pr.`department_id` = '$dept_id'  
                                    AND tt.term_id = '$term_id'
                                    AND cs.day = '$day'
                                    AND CONCAT('BS',pr.program_abbrv,' ',sc.section_name) ='$section'
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
        "room_name" => $room_name
      
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
                $faculty_id = $row['faculty_id'];
                // $needed = "\n"."Needed Lecturer";
                
                         
                                

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
                                                     

                                                    if(empty($faculty_id)){
                                                          $spreadsheet->getActiveSheet()
                                                      ->setCellValue($col_sched_1 .$new_content_row, $search_course_code."\nNeed Lecturer")
                                                      ->setCellValue($col_room.$new_content_row, $room_name);
                                                    }else{
                                                          $spreadsheet->getActiveSheet()
                                                      ->setCellValue($col_sched_1 .$new_content_row, $search_course_code."\n".$facultyname)
                                                      ->setCellValue($col_room.$new_content_row, $room_name);
                                                    }
                                                    
                                                }
                                                else{
                                                     $data[$index]['col_start'] = $currentContentRow;
                                               
                                                $b = $data[$index]['col_start'];

                                                    if(!empty($facultyname)){
                                                          $spreadsheet->getActiveSheet()
                                                      ->setCellValue($col_sched_1 .$currentContentRow, $search_course_code.
                                                        "\n".$facultyname)
                                                      ->setCellValue($col_room.$currentContentRow, $room_name);
                                                    }else{
                                                          $spreadsheet->getActiveSheet()
                                                      ->setCellValue($col_sched_1 .$currentContentRow, $search_course_code."\n Need Lecturer")
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
                          $spreadsheet->getActiveSheet()->mergeCells("$col_room$col_start:$col_room$col_end");
                          $spreadsheet->getActiveSheet()->getStyle($col_room.$col_end)->getAlignment()->setHorizontal('center');
                          $spreadsheet->getActiveSheet()->getStyle($col_room.$col_start)->getAlignment()->setWrapText(true); 
                          $spreadsheet->getActiveSheet()->getStyle("$col_room$col_start:$col_room$col_end")->applyFromArray($styleArray);
    }
}



        
        
        
  
      

}

function plot_subject_info($dept_id,$conn,$spreadsheet,$section,$term_id){
//     $sql = "SELECT DISTINCT
// fs.faculty_id,
// f.firstname,
// f.lastname,
// c.course_code,
// p.`program_abbrv`,
// s.section_name,
// s.`no_of_students`,

// d.department_name,
// sm.`sem_description`,
// ay.`acad_year`
// FROM class_schedule cs
// LEFT JOIN faculty_schedule fs ON fs.faculty_sched_id = cs.faculty_schedule_id
// LEFT JOIN faculties f ON f.faculty_id = fs.faculty_id
// LEFT JOIN courses c ON c.course_id = fs.course_id
// LEFT JOIN sections s ON s.section_id = fs.section_id
// LEFT JOIN rooms r ON r.room_id = fs.room_id
// LEFT JOIN `time` t1 ON t1.time_id = fs.time_start_id
// LEFT JOIN `time` t2 ON t2.time_id = fs.time_end_id
// LEFT JOIN departments d ON d.department_id = fs.department_id
// LEFT JOIN programs p ON p.`program_id` = s.`program_id`
// LEFT JOIN semesters sm ON sm.`semester_id` = fs.`semester_id`
// LEFT JOIN academic_year ay ON ay.`acad_year_id` = fs.`acad_year_id`
// WHERE fs.department_id = 8 AND sm.status = 'ACTIVE' 
// AND CONCAT('BS',p.program_abbrv,' ',s.section_name) = 'BSIT 2203'
// ORDER BY t1.time_id;";

    $sql = "SELECT 
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
                                    
                                    AND CONCAT('BS',pr.program_abbrv,' ',sc.section_name) ='$section'
                                    GROUP BY fl.`fac_load_id`
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
    // Append the current row's data to the $data array
    $data[] = array(
        "faculty_name" => $facultyname,
        "course_code" => $course_code,
        "students" => $total_studs,
        "needed" => $needed
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
                     if(empty($needed)){
                         $spreadsheet->getActiveSheet()
                      ->setCellValue('S'.$currentContentRow, $course_code)
                      ->setCellValue('T'.$currentContentRow, $facultyname)
                      ->setCellValue('U'.$currentContentRow, $studs);
                     }else{
                        $spreadsheet->getActiveSheet()
                      ->setCellValue('S'.$currentContentRow, $course_code)
                      ->setCellValue('T'.$currentContentRow,$needed)
                      ->setCellValue('U'.$currentContentRow, $studs);
                     }
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
header('Content-Disposition: attachment;filename="Faculty Schedule '.$dept_abbrv.' .xlsx"');

//create IOFactory object
$writer = IOFactory::createWriter($spreadsheet, 'Xls');
//save into php output
$writer->save('php://output');



?>