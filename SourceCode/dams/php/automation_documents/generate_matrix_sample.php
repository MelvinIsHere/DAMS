<?php 


//call the autoload

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


// Load an existing Excel file
$reader = IOFactory::createReader('Xlsx');
$spreadsheet = $reader->load("../../templates/BatStateU-FO-COL-28_Room Utilization_Rev. 03.xlsx");


$conn = new mysqli("localhost", "root", "", "dams2");
if(!$conn){
    exit("database connection error");
}







$department_id = $_GET['dept_id'];
$department_name = $_GET['department_name'];
$room_names = []; //this is the array for the room names
         $sql = " SELECT 
                    r.room_name
                               
                    FROM class_schedule cs
                    LEFT JOIN faculty_loadings fl ON fl.`fac_load_id` = cs.`faculty_schedule_id`
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
                    WHERE pr.`department_id` = '$department_id'
                    AND s.status = 'ACTIVE'
                    AND ay.status = 'ACTIVE'
                                      
                                    
                                    
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
    // $plot_monday = plot_double_col('Monday',$department_id,$conn,$spreadsheet,$tabName,'B','C','D');
    $plot_room_cell = plot_room_name($tabName,$spreadsheet,$department_name,$conn);
    $get_data = get_data("Monday",$department_id,$conn,$tabName,$spreadsheet);
    $monday_plot = plot_monday($get_data,$spreadsheet,'B','D');




}







// Remove the original 'BLANK' sheet if needed
$spreadsheet->removeSheetByIndex($spreadsheet->getIndex($spreadsheet->getSheetByName('BLANK FORM')));











function plot_monday($data,$spreadsheet,$col_sched_1,$col_section){

    foreach ($data as $row) {
    $facultyname = $row["faculty_name"];
    $course_code = $row["course_code"];
    $text_output_start = $row["text_output_start"];
    $time_start = $row["time_s"];
    $time_end = $row["time_end"];
    $text_output_end = $row["text_output_end"];
    $ampm_start = $row["ampm_start"];
    $ampm_end = $row["ampm_end"];
    $room_name = $row["room_name"];
    $needed = $row["needed"];
    $section = $row["section"];
    $currentContentRow = $row["index"];




    $time_parts = explode(" ", $time_start); //when the start is like half an hour
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
                                                             

        if(!empty($facultyname)){
            $spreadsheet->getActiveSheet()
                ->setCellValue($col_sched_1 .$new_content_row, $course_code.$facultyname)
                                                      ->setCellValue($col_section.$new_content_row, $section);
                                                     
        }else{
            $spreadsheet->getActiveSheet()
                ->setCellValue($col_sched_1 .$new_content_row, $course_code.$needed)
                ->setCellValue($col_section.$new_content_row, $section);
                                                  
        }
                                                    
    }else{
         $spreadsheet->getActiveSheet()
                ->setCellValue($col_sched_1 .$currentContentRow, $course_code.$needed)
                ->setCellValue($col_section.$currentContentRow, $section);
    }

    // Now you can use these variables as needed within your loop
}


}




function get_data($day,$dept_id,$conn,$room_name,$spreadsheet){


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
                                LEFT JOIN faculty_loadings fl ON fl.`fac_load_id` = cs.`faculty_schedule_id`
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





$monday_plot = mysqli_query($conn, $monday_plot_query);

$data =[];
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
    $section = 'BS'.$row['program_abbrv']." ".$row['section_name'];
    $currentContentRow=6;
    $row = current_row($text_output_start,$spreadsheet,$currentContentRow);
    if($row != -1){
        $currentContentRow = $row;
    }
   
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
        "section" => $section,
        "index" => $currentContentRow
    );
  


}

return $data;

}


  





function current_row($text_output_start, $spreadsheet, $currentContentRow) {
    while ($currentContentRow <= 40) { // Add an upper limit to avoid infinite loop
        $cellValueCurrent = trim($spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $currentContentRow)->getCalculatedValue());

        if ($cellValueCurrent == $text_output_start) {
            return $currentContentRow;
            break;
        } else {
            $currentContentRow++;
        }
    }
    // Return an error value or handle the case when the text_output_start is not found
    return -1; // For example, returning -1 to indicate that the value was not found
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


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

//make it an attachment so we can define filename
header('Content-Disposition: attachment;filename="BatStateU-FO-COL-28_Room Utilization_Rev. 03.xlsx"');

//create IOFactory object
$writer = IOFactory::createWriter($spreadsheet, 'Xls');
//save into php output

$writer->save('php://output');


?>