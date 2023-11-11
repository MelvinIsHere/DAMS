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


// here is the first query for the insertion



$active_semester = mysqli_query($conn, "
SELECT sem_description FROM semesters WHERE status = 'ACTIVE'
");


if(mysqli_num_rows($active_semester) > 0){

$row = mysqli_fetch_array($active_semester);
$semester = $row['sem_description'];
$semseterWhere = 4;
$semesterCell = 4;

    
        // Fill the cell with data
        $spreadsheet->getActiveSheet()
          
            ->setCellValue('O'.$semesterCell,$semester . " Semester");
            // ... set other cell values


}





//department abbrv
//start of the cell

$collegeCell = 3;
   // Fill the cell with data
        $spreadsheet->getActiveSheet()
          
            ->setCellValue('B'.$collegeCell,$dept_abbrv);
            // ... set other cell values

 




//section insertion

$section = $_GET['section'];

$sectionCell = 4;


        $spreadsheet->getActiveSheet()
          
            ->setCellValue('B'.$sectionCell,$section);






//campus insertion

//department abbrv
//start of the cell

$campusCell = 3;
   // Fill the cell with data
        $spreadsheet->getActiveSheet()
          
            ->setCellValue('O'.$campusCell,'ARASOF');
            // ... set other cell values




$active_acad_year = mysqli_query($conn, "
SELECT acad_year FROM academic_year WHERE status = 'ACTIVE'
");


if(mysqli_num_rows($active_acad_year) > 0){

$row = mysqli_fetch_array($active_acad_year);
$academic_year = $row['acad_year'];

$academic_year_cell = 4;

    
        // Fill the cell with data
        $spreadsheet->getActiveSheet()
          
            ->setCellValue('Q'.$academic_year_cell,$academic_year);
            // ... set other cell values


}


// Monday plotting
$monday_plot_query = "

SELECT 
fs.faculty_id,
f.firstname,
f.lastname,
c.course_code,
p.`program_abbrv`,
s.section_name,
s.`no_of_students`,
r.room_name,
t1.text_output AS 'class_start',
t1.time_s,
t1.ampm_start,
t2.time_e,
t2.text_output AS 'class_end',
t2.ampm_end,
d.department_name,
sm.`sem_description`,
ay.`acad_year`,
fs.day
FROM class_schedule cs
LEFT JOIN faculty_schedule fs ON fs.faculty_sched_id = cs.faculty_schedule_id
LEFT JOIN faculties f ON f.faculty_id = fs.faculty_id
LEFT JOIN courses c ON c.course_id = fs.course_id
LEFT JOIN sections s ON s.section_id = fs.section_id
LEFT JOIN rooms r ON r.room_id = fs.room_id
LEFT JOIN `time` t1 ON t1.time_id = fs.time_start_id
LEFT JOIN `time` t2 ON t2.time_id = fs.time_end_id
LEFT JOIN departments d ON d.department_id = fs.department_id
LEFT JOIN programs p ON p.`program_id` = s.`program_id`
LEFT JOIN semesters sm ON sm.`semester_id` = fs.`semester_id`
LEFT JOIN academic_year ay ON ay.`acad_year_id` = fs.`acad_year_id`
WHERE fs.department_id = 8 AND sm.status = 'ACTIVE' AND fs.`day` = 'Friday'
ORDER BY t1.time_id;
";


$data = [];


$monday_plot = mysqli_query($conn, $monday_plot_query);

while ($row = mysqli_fetch_array($monday_plot)) {
    $first_name_first_letter = explode(" ", $row['firstname']);
    $firstLettersFirstName = "";
    
    foreach ($first_name_first_letter as $part) {
        $firstLettersFirstName .= $part[0] . ". ";
    }

    $facultyname = $firstLettersFirstName . " " . $row['lastname'];
    $course_code = $row['course_code'];
    $text_output_start = $row['class_start'];
    $time_start = $row['time_s'];
    $time_end = $row['time_e'];
    $text_output_end = $row['class_end'];
    $ampm_start = $row['ampm_start'];
    $ampm_end = $row['ampm_end'];

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
        "inserted" => NULL
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
                $facultyname = "\n". $row['faculty_name'];
                $spreadsheet->getActiveSheet()->setCellValue('G'.$currentContentRow, $ampm);
                         
                                

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
                                                $valueData = "Course code '$search_course_code' found at index: $index";
                                                

                                                // Update the 'col_end' field here with the correct value (e.g., $currentContentRow)
                                                $data[$index]['col_start'] = $currentContentRow;
                                               
                                                $b = $data[$index]['col_start'];
                                                $spreadsheet->getActiveSheet()->setCellValue('E'.$currentContentRow, $b);

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
                         if($cellValueCurrent ==$text_output_data){
                                

                                if ($cellValueCurrent == $text_output_data && !in_array($text_output_data, $end) && !in_array($search_course_code, $course_end)) {
                                    if ($arr_ampm_end == "AM" && $arr_ampm_end == $ampm) {
                                       
                                            $index = -1; // Initialize with -1 to indicate not found

                                            // Loop through the array to search for the course code
                                            foreach ($data as $keyrow => $value) {
                                                if ($value["course_code"] == $search_course_code) {
                                                    $index = $keyrow; // Set the index when found
                                                    
                                                    break; // Stop searching once found
                                                }
                                            }

                                            if ($index != -1) {
                                                $value = "Course code '$search_course_code' found at index: $index";
                                                $spreadsheet->getActiveSheet()->setCellValue('G6', $value);

                                                // Update the 'col_end' field here with the correct value (e.g., $currentContentRow)
                                                $data[$index]['col_end'] = $currentContentRow;
                                                $end[] = $text_output_data; // Add the inserted value to the tracking array
                                                $course_end[] = $search_course_code;
                                                $b = $data[$index]['col_end'];
                                                // $spreadsheet->getActiveSheet()->setCellValue('D6', $b);
                                            } else {
                                                $value = "Course code '$search_course_code' not found in the array";
                                                $spreadsheet->getActiveSheet()->setCellValue('D6', $value);
                                            }

                                    }
                                     elseif ($arr_ampm_end == "PM" && !in_array($text_output_data, $end) &&  !in_array($search_course_code, $course_end)) {
                                     
                                         $index = -1; // Initialize with -1 to indicate not found

                                            // Loop through the array to search for the course code
                                            foreach ($data as $keyrow => $value) {
                                                if ($value["course_code"] == $search_course_code) {
                                                    $index = $keyrow; // Set the index when found
                                                    $spreadsheet->getActiveSheet()->setCellValue('T6', $search_course_code);
                                                    break; // Stop searching once found
                                                }
                                            }

                                            if ($index != -1) {
                                                $value = "Course code '$search_course_code' found at index: $index";
                                                $spreadsheet->getActiveSheet()->setCellValue('G6', $value);

                                                // Update the 'col_end' field here with the correct value (e.g., $currentContentRow)
                                                $data[$index]['col_end'] = $currentContentRow;
                                                $end[] = $text_output_data; // Add the inserted value to the tracking array
                                                $course_end[] = $search_course_code;
                                                $b = $data[$index]['col_end'];
                                                $spreadsheet->getActiveSheet()->setCellValue('D6', $b);
                                            } else {
                                                $value = "Course code '$search_course_code' not found in the array";
                                                $spreadsheet->getActiveSheet()->setCellValue('D6', $value);
                                            }

                                    }
                                }else{

                                }
                                                                   


                         }
                         else{
                          
                         }
                                }
                                

                
                else{

                }

            }
    
}
  $currentContentRow++;


}      

 


  
$count = count($course_code);
// $count2 = count($course_end);
$spreadsheet->getActiveSheet()->setCellValue('D9', "array " . $count);
// $spreadsheet->getActiveSheet()->setCellValue('D10', "array " . $count2);
   
 // $data[] = array(
 //        "faculty_name" => $facultyname,
 //        "course_code" => $course_code,
 //        "text_output_start" => $text_output_start,
 //        "time_s" => $time_start,
 //        "time_end" => $time_end,
 //        "text_output_end" => $text_output_end,
 //        "col_start" => NULL,
 //        "col_end" => NULL,
 //        "ampm_start" => $ampm_start,
 //        "ampm_end" => $ampm_end,
 //        "inserted" => NULL
 //    );


$col_start = $data[0]['course_code'];
$col_start1 = $data[1]['course_code'];
// $col_start2 = $data[1]['col_start'];
$spreadsheet->getActiveSheet()->setCellValue('D15',$col_start);
$spreadsheet->getActiveSheet()->setCellValue('D16',$col_start1);
// $spreadsheet->getActiveSheet()->setCellValue('D16', $col_start2);
// $col_end1 = $data[0]['col_end'];
// $col_end2 = $data[1]['col_end'];
// $spreadsheet->getActiveSheet()->setCellValue('E15',$col_end1);
// $spreadsheet->getActiveSheet()->setCellValue('E16', $col_end2);

//  $spreadsheet->getActiveSheet()->setCellValue('G6', $col_start1 );
// $spreadsheet->getActiveSheet()->setCellValue('G7', $col_start2 );
//  $spreadsheet->getActiveSheet()->setCellValue('G8', $col_end1 );
// $spreadsheet->getActiveSheet()->setCellValue('G9', $col_end2 );

// $search_course_code = "CS 422"; // Replace with the course code you want to search for

// $index = -1; // Initialize with -1 to indicate not found

// // Loop through the array to search for the course code
// foreach ($data as $key => $value) {
//     if ($value["course_code"] == $search_course_code) {
//         $index = $key; // Set the index when found
//         break; // Stop searching once found
//     }
// }
// if ($index != -1) {
//     $value = "Course code '$search_course_code' found at index: $index";
//      $spreadsheet->getActiveSheet()->setCellValue('D6', $value );
    
// } else {
//     $value = "Course code '$search_course_code' not found in the array";
//     $spreadsheet->getActiveSheet()->setCellValue('D6', $value );
// }

// $worksheet = $spreadsheet->getActiveSheet();
// $rowCount = 6;
//  while($rowCount != 40){
//     foreach ($data as $row) {
//             foreach ($row as $key => $value) {
//                  if ($key === "text_output_end") { // Check if the current key is "text_output"
//                        $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $rowCount)->getCalculatedValue();
                         
//                          $text_output_data = $value;
                         
//                          $col_start = $row['col_start'];
//                          $col_end = $row['col_end'];
//                          $from = "B".$col_start;
//                          $to = "C".$col_end;
//                          if($cellValueCurrent ==$text_output_data ){
                           


//                                      $spreadsheet->getActiveSheet()->mergeCells("B$col_start:C$col_end");


//                                     $spreadsheet->getActiveSheet()->getStyle("$from")->getAlignment()->setHorizontal('center');
//                                     $spreadsheet->getActiveSheet()->getStyle('B'.$col_start)->getAlignment()->setWrapText(true); 
//                                     // $rowCount = 6;
                                    

//                          }
//                          else{
                          
//                          }

//                 }
//                 else{

//                 }

//             }
    
// }
// $rowCount++;
//  }  



        
              

        
        
        
  
      



// // Loop for permanent items ----- permanent faculty
// // Loop for permanent items ----- permanent faculty
// $contentStartRow = 6;
// $currentContentRow = 6;
// $cellValueOld = "";
// $worksheet = $spreadsheet->getActiveSheet();
// $mergeStart = 6;
// $loopCount = 0;

// // Loop through all the data for permanent faculty
// while ($item = mysqli_fetch_array($monday_plot)) {
//     if ($item['day'] == "Monday") {
//         // Fill the cell with data
//         $currentCellValue = $item['text_output'];
//         $class_hrs_start = $item['start'];
//         $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $currentContentRow)->getCalculatedValue();

//         $first_name_first_letter   = explode(" ", $item['firstname']);
//         $firstLettersFirstName = "";
//         foreach ($first_name_first_letter as $part) {
//             $firstLettersFirstName .= $part[0] . ". ";

//             $spreadsheet->getActiveSheet()
//             ->setCellValue('B' . $currentContentRow, $firstLettersFirstName . " " . $item['lastname']);
            
//         }
        
   
//         $cellValueOld = $currentCellValue;
//     }


 
// $spreadsheet->getActiveSheet()->insertNewRowBefore($currentContentRow + 1, 1);
    
//     $currentContentRow++;
// }

// }






//set the header first, so the result will be treated as an xlsx file.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

//make it an attachment so we can define filename
header('Content-Disposition: attachment;filename="BatStateU-FO-COL-29_Class.xlsx"');

//create IOFactory object
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
//save into php output
$writer->save('php://output');



?>