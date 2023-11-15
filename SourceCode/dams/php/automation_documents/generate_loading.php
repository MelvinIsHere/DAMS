<?php

//call the autoload
require 'vendor/autoload.php';
session_start();
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
//load phpspreadsheet class using namespaces
use PhpOffice\PhpSpreadsheet\Spreadsheet;
//call iofactory instead of xlsx writer
use PhpOffice\PhpSpreadsheet\IOFactory;
       use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use \PhpOffice\PhpSpreadsheet\RichText\RichText;







$reader = IOFactory::createReader('Xlsx');
$spreadsheet = $reader->load("../../templates/loading.xlsx");
$worksheet = $spreadsheet->getActiveSheet();

//add the content
//data from database

$conn = new mysqli("localhost", "root", "", "dams2");
if(!$conn){
    exit("database connection error");
}
// ...
$acad_id = $_SESSION['acad_id'];
$semester_id = $_SESSION['semester_id'];
$dept_id = $_GET['dept_id'];
$dept_abbrv = $_GET['dept_abbrv'];





$active_semester = mysqli_query($conn, "SELECT sem_description FROM semesters WHERE semester_id = '$semester_id'");
if($active_semester){
    if(mysqli_num_rows($active_semester) > 0){
        $row = mysqli_fetch_assoc($active_semester);
        $semester = $row['sem_description'];

           // Fill the cell with data
        $spreadsheet->getActiveSheet()
          
            ->setCellValue('K4',$semester . " Semester");
    }else{

    }
}else{

}


$active_acad_year = mysqli_query($conn,"SELECT acad_year FROM academic_year WHERE acad_year_id = '$acad_id'");
if($active_acad_year){
    if(mysqli_num_rows($active_acad_year) > 0){
        $row = mysqli_fetch_assoc($active_acad_year);
        $acad_year = $row['acad_year'];

        $spreadsheet->getActiveSheet()
          
            ->setCellValue('N4',$acad_year);
    }else{
         
    }
}else{
    
    
    
}



//department
$spreadsheet->getActiveSheet()          
            ->setCellValue('B3',$dept_abbrv);
//CAMPUS
$spreadsheet->getActiveSheet()          
            ->setCellValue('B4','ARASOF');            



















$permanent = false;

//permanent faculty 


$permanent_query = mysqli_query($conn, "
SELECT 
fc.faculty_id,
CONCAT(fc.lastname,',',fc.firstname,' ',fc.middlename) AS 'Name of Faculty',
fc.is_permanent AS 'permanent',
cs.course_code AS 'Course Code',
CONCAT(pr.`program_abbrv`,' ',sc.`section_name`) AS 'Section',
sc.no_of_students AS 'No. of Students',
cs.`units` AS 'Total Units',
cs.`lec_hrs_wk` AS 'Lec. hrs/wk',
cs.`lab_hrs_wk` AS 'Lab. hrs/wk',
cs.`rle_hrs_wk` AS 'Rle. hrs/wk',
SUM(cs.`lec_hrs_wk` + cs.`lab_hrs_wk`) AS 'Total hrs/wk',
cs.`course_description` AS 'Course Description',
t.`title_description`
FROM
faculty_loadings fl
LEFT JOIN faculties fc ON fl.`faculty_id`=fc.`faculty_id`
LEFT JOIN courses cs ON fl.`course_id`=cs.`course_id`
LEFT JOIN sections sc ON fl.`section_id`=sc.`section_id`
LEFT JOIN programs pr ON sc.`program_id`=pr.`program_id`
LEFT JOIN faculty_titles ft ON ft.`faculty_id` = fc.`faculty_id`
LEFT JOIN titles t ON t.`title_id` = ft.`title_id`
LEFT JOIN departments d ON d.`department_id` = fc.`department_id`
LEFT JOIN semesters s ON s.semester_id = fl.sem_id
LEFT JOIN academic_year ay ON ay.acad_year_id = fl.acad_year_id
WHERE fc.is_permanent = 1 AND d.`department_id` = '$dept_id'
AND s.semester_id = '$semester_id'
AND ay.acad_year_id = '$acad_id'

GROUP BY fl.`fac_load_id`
ORDER BY  fc.firstname");


$row_start_permanent = 7;

$data_permanent = [];

if($permanent_query){
    
    if(mysqli_num_rows($permanent_query) > 0){
        $permanent = true;
        while ($row = mysqli_fetch_array($permanent_query)) {
              $faculty_id = $row['faculty_id'];
              $facultyname = $row['Name of Faculty'];
              $course_code =  $row['Course Code'];
              $section =  $row['Section'];
              $students =  $row['No. of Students'];
              $units =  $row['Total Units'];
              $lec_hrs =  $row['Lec. hrs/wk'];
              $rle_hrs =  $row['Rle. hrs/wk'];
              $lab_hrs =  $row['Lab. hrs/wk'];
              $total_hrs_wk =  $row['Total hrs/wk'];
              $course_description =  $row['Course Description'];
              $title_description = $row['title_description'];
                

            
              
              // Append the current row's data to the $data array
              $data_permanent[] = array(
                    "faculty_id" => $faculty_id,
                      "facultyname" => $facultyname,
                     "course_code" => $course_code,
                     "section" => $section,
                     "students" => $students,
                     "units" => $units,
                     "lec_hrs" => $lec_hrs,
                     "lab_hrs" => $lab_hrs,
                     "rle_hrs" =>$rle_hrs,
                     "total_hrs_wk" => $total_hrs_wk,
                     "course_description" => $course_description,
                     "title_description" => $title_description
                     
                     
              );
           
       }


 
        foreach($data_permanent as $row){
            //insert it all to the array
            $faculty_id = $row['faculty_id'];
            $facultyname = $row['facultyname'];
            $course_code =  $row['course_code'];
            $section =  $row['section'];
            $students =  $row['students'];
            $units =  $row['units'];
            $lec_hrs =  $row['lec_hrs'];
            $rle_hrs =  $row['rle_hrs'];
            $lab_hrs =  $row['lab_hrs'];
            $total_hrs_wk =  $row['total_hrs_wk'];
            $course_description =  $row['course_description'];
            $title_description = $row['title_description'];

            ob_start();
            $designations=  getdesignation($faculty_id);
            $designationOutput = ob_get_clean();
            $designations_all = str_replace(',',"\n",$designations);
         
            

            $cellValuenext = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_start_permanent+1)->getCalculatedValue();
            if($cellValuenext == " TEMPORARY FACULTY"){
                $spreadsheet->getActiveSheet()->insertNewRowBefore($row_start_permanent, 1);
                
            }
            
            $spreadsheet->getActiveSheet()
                    ->setCellValue('A'.$row_start_permanent, $facultyname ."\n" . $title_description."\n".$designations_all)
                    ->setCellValue('D'.$row_start_permanent, $course_code)
                    ->setCellValue('E'.$row_start_permanent, $section)
                    ->setCellValue('F'.$row_start_permanent, $students)
                    ->setCellValue('G'.$row_start_permanent, $units)
                    ->setCellValue('H'.$row_start_permanent, $lec_hrs)
                    ->setCellValue('I'.$row_start_permanent, $rle_hrs)
                    ->setCellValue('J'.$row_start_permanent, $lab_hrs)
                    ->setCellValue('K'.$row_start_permanent, $rle_hrs)
                    ->setCellValue('L'.$row_start_permanent, $course_description);

                $cell = $spreadsheet->getActiveSheet()->getCell('A'.$row_start_permanent);
                $richText = $cell->getRichText();

                // Create a new text run
                $textRun = $richText->createTextRun($designations_all);

                // Set the italic property for the text run
                $textRun->getFont()->setItalic(true);
          
               
                    
              
              $row_start_permanent++;

                       

              

       }
    }else{

    }
}else{

}
$spreadsheet->getActiveSheet()->removeRow($row_start_permanent, 1);






$rowStart = 7; //the start of the counting
$oldval = ""; //the current old val
//varibales to increment
$totalUnits = 0;
$total_lec_hrs = 0;
$total_rle_hrs = 0;
$total_lab_hrs = 0;
$total_hrs_wk = 0;
//looping
while($row_start_permanent >= $rowStart){

    //get the current value or the faculty name
    $cellValue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $rowStart)->getCalculatedValue();
    //get the next row index
     $nextRow = $rowStart + 1;
     //get the next row index value
    $nextvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $nextRow)->getCalculatedValue();
    //get the cell value of the current loop for the units
    $units = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(7, $rowStart)->getCalculatedValue();
    //get the cell value of the current loop for the lec hrs
    $lecture_hrs = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(8, $rowStart)->getCalculatedValue();
    //get the cell value of the current loop for the rle hrs
    $rle_hrs = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(9, $rowStart)->getCalculatedValue();
    //get the cell value of the current loop for the lab hrs
    $lab_hrs = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(10, $rowStart)->getCalculatedValue();
    //get the cell value of the current loop for the hrs per week
    $hrs_wk = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(11, $rowStart)->getCalculatedValue();
    //just add it all
    $totalUnits = $totalUnits + $units;
    $total_lec_hrs = $total_lec_hrs + $lecture_hrs;
    $total_rle_hrs = $total_rle_hrs + $rle_hrs;
    $total_lab_hrs = $total_lab_hrs + $lab_hrs;
    $total_hrs_wk = $total_hrs_wk + $hrs_wk;
    //if same value for next and current just increment the rowstart so it can still continue to count or add
    if ($cellValue ==  $nextvalue) {                    
        $rowStart++;                                        
    }elseif($cellValue == " TEMPORARY FACULTY"){
        break;
    }else{

        //else add new row
        $spreadsheet->getActiveSheet()->insertNewRowBefore($rowStart + 1, 1);
        //increment row
        $rowStart++;
        // insert data into the new row - total
        $spreadsheet->getActiveSheet()
            ->setCellValue('A' . $rowStart, $cellValue)
            ->setCellValue('D' . $rowStart, "TOTAL")
            ->setCellValue('G' . $rowStart, $totalUnits)
            ->setCellValue('H' . $rowStart, $total_lec_hrs)
            ->setCellValue('I' . $rowStart, $total_rle_hrs)
            ->setCellValue('J' . $rowStart, $total_lab_hrs)
            ->setCellValue('K' . $rowStart, $total_hrs_wk);
        //merge the cells
        $spreadsheet->getActiveSheet()->mergeCells('D'.$rowStart.':F'.$rowStart);
        //re assign it or reset the values                                    
        $new = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $rowStart)->getCalculatedValue();
        //increment the row start permanent so it will be updated
        $row_start_permanent++;
        //increment the row start
        $rowStart++;
        $totalUnits = 0;
        $total_lec_hrs = 0;
        $total_rle_hrs = 0;
        $total_lab_hrs = 0;
        $total_hrs_wk = 0;              
    }
      
}





$row = 7;
$rowCount =1;
$cRow = 7;
while($row <= $row_start_permanent){
    //get the current cell value
    $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row)->getCalculatedValue();
    //the next row index
    $nextRow = $row + 1;
    //get the next cell value
    $nextvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $nextRow)->getCalculatedValue();
    //if true break the loop
    if($cellValueCurrent ==  " TEMPORARY FACULTY"){
        break;
    }
    //if the current row is row index 7 just increment
    if($cRow == 7){
        if ($cellValueCurrent ==  $nextvalue) {                
            $row++;
            $rowCount++;
        }else{      
            //else merge cells for the line              
            $spreadsheet->getActiveSheet()->mergeCells('A'.$cRow.':C'.$row);
            // Get style for the merged cells // Set vertical alignment to center
            $spreadsheet->getActiveSheet()->getStyle('A'.$cRow)->getAlignment()->setHorizontal('center');
            //set the line wrap to be true
            $spreadsheet->getActiveSheet()->getStyle('A'.$cRow)->getAlignment()->setWrapText(true); 
            //set the starting merge to be the last merging + 1 so we will start on the next value
            $cRow = $cRow + $rowCount; 
            //increment the row
            $row++;
            //re assign the row count
            $rowCount = 1;
        }
    } 
    elseif ($cellValueCurrent ==  $nextvalue) {
        //if same value then increment the row and the row count
            $row++;
            $rowCount++;
    }else{
            //merge cells for the a to c of the current row with the end of the same value and the row start merging
           $spreadsheet->getActiveSheet()->mergeCells('A'.$cRow.':C'.$row);
           $spreadsheet->getActiveSheet()->getStyle('A'.$cRow)->getAlignment()->setHorizontal('center');
            // Set wrapText for merged cells
            $spreadsheet->getActiveSheet()->getStyle('A'.$cRow)->getAlignment()->setWrapText(true);
           $cRow = $cRow + $rowCount;
           $row++;
           $rowCount = 1;
            

        }

    }
       





















$cellvalue = "";
$row_start_guest = $row_start_permanent;
$row_start_content_guest= $row_start_permanent;
while($cellvalue != " TEMPORARY FACULTY"){
     $cellvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_start_guest)->getCalculatedValue();
       $spreadsheet->getActiveSheet()
                    ->setCellValue('P'.$row_start_guest, $row_start_guest);
     $row_start_guest++;
     $row_start_content_guest++;
}

$guest = false;


//temporary faculty

$guest_query = mysqli_query($conn, "
SELECT 
CONCAT(fc.lastname,',',fc.firstname,' ',fc.middlename) AS 'Name of Faculty',
fc.is_permanent AS 'permanent',
cs.course_code AS 'Course Code',
CONCAT(pr.`program_abbrv`,' ',sc.`section_name`) AS 'Section',
sc.no_of_students AS 'No. of Students',
cs.`units` AS 'Total Units',
cs.`lec_hrs_wk` AS 'Lec. hrs/wk',
cs.`lab_hrs_wk` AS 'Lab. hrs/wk',
cs.`rle_hrs_wk` AS 'Rle. hrs/wk',
SUM(cs.`lec_hrs_wk` + cs.`lab_hrs_wk`) AS 'Total hrs/wk',
cs.`course_description` AS 'Course Description',
t.`title_description`
FROM
faculty_loadings fl
LEFT JOIN faculties fc ON fl.`faculty_id`=fc.`faculty_id`
LEFT JOIN courses cs ON fl.`course_id`=cs.`course_id`
LEFT JOIN sections sc ON fl.`section_id`=sc.`section_id`
LEFT JOIN programs pr ON sc.`program_id`=pr.`program_id`
LEFT JOIN faculty_titles ft ON ft.`faculty_id` = fc.`faculty_id`
LEFT JOIN titles t ON t.`title_id` = ft.`title_id`
LEFT JOIN departments d ON d.`department_id` = fc.`department_id`
LEFT JOIN semesters s ON s.semester_id = fl.sem_id
LEFT JOIN academic_year ay ON ay.acad_year_id = fl.acad_year_id
WHERE fc.is_guest = 1 AND d.`department_id` = '$dept_id'
AND s.semester_id = '$semester_id'
AND ay.acad_year_id = '$acad_id'

GROUP BY fl.`fac_load_id`
ORDER BY  fc.firstname");




$data_guest = [];

if($guest_query){
    
    if(mysqli_num_rows($guest_query) > 0){
        $guest = true;
   
        while ($row = mysqli_fetch_array($guest_query)) {
              $facultyname = $row['Name of Faculty'];
              $course_code =  $row['Course Code'];
              $section =  $row['Section'];
              $students =  $row['No. of Students'];
              $units =  $row['Total Units'];
              $lec_hrs =  $row['Lec. hrs/wk'];
              $rle_hrs =  $row['Rle. hrs/wk'];
              $lab_hrs =  $row['Lab. hrs/wk'];
              $total_hrs_wk =  $row['Total hrs/wk'];
              $course_description =  $row['Course Description'];
              $title_description = $row['title_description'];
              // Append the current row's data to the $data array
              $data_guest[] = array(
                      "facultyname" => $facultyname,
                     "course_code" => $course_code,
                     "section" => $section,
                     "students" => $students,
                     "units" => $units,
                     "lec_hrs" => $lec_hrs,
                     "lab_hrs" => $lab_hrs,
                     "rle_hrs" =>$rle_hrs,
                     "total_hrs_wk" => $total_hrs_wk,
                     "course_description" => $course_description,
                     "title_description" => $title_description
                     
              );
           
       }


        foreach($data_guest as $row){
            //insert it all to the array
            $facultyname = $row['facultyname'];
            $course_code =  $row['course_code'];
            $section =  $row['section'];
            $students =  $row['students'];
            $units =  $row['units'];
            $lec_hrs =  $row['lec_hrs'];
            $rle_hrs =  $row['rle_hrs'];
            $lab_hrs =  $row['lab_hrs'];
            $total_hrs_wk =  $row['total_hrs_wk'];
            $course_description =  $row['course_description'];
            $title_description = $row['title_description'];
            $cellValuenext = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_start_guest+1)->getCalculatedValue();
            if($cellValuenext == " PART-TIME FACULTY"){
                $spreadsheet->getActiveSheet()->insertNewRowBefore($row_start_guest, 1);
                
            }
            $spreadsheet->getActiveSheet()
                    ->setCellValue('A'.$row_start_guest, $facultyname ."\n" . $title_description)
                    ->setCellValue('D'.$row_start_guest, $course_code)
                    ->setCellValue('E'.$row_start_guest, $section)
                    ->setCellValue('F'.$row_start_guest, $students)
                    ->setCellValue('G'.$row_start_guest, $units)
                    ->setCellValue('H'.$row_start_guest, $lec_hrs)
                    ->setCellValue('I'.$row_start_guest, $rle_hrs)
                    ->setCellValue('J'.$row_start_guest, $lab_hrs)
                    ->setCellValue('K'.$row_start_guest, $rle_hrs)
                    ->setCellValue('L'.$row_start_guest, $course_description);
                    
              
              $row_start_guest++;

                       

              

       }





$rowStart = $row_start_content_guest;; //the start of the counting
$oldval = ""; //the current old val
//varibales to increment
$totalUnits = 0;
$total_lec_hrs = 0;
$total_rle_hrs = 0;
$total_lab_hrs = 0;
$total_hrs_wk = 0;
//looping
while($row_start_guest >= $rowStart){

    //get the current value or the faculty name
    $cellValue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $rowStart)->getCalculatedValue();
    //get the next row index
     $nextRow = $rowStart + 1;
     //get the next row index value
    $nextvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $nextRow)->getCalculatedValue();
    //get the cell value of the current loop for the units
    $units = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(7, $rowStart)->getCalculatedValue();
    //get the cell value of the current loop for the lec hrs
    $lecture_hrs = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(8, $rowStart)->getCalculatedValue();
    //get the cell value of the current loop for the rle hrs
    $rle_hrs = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(9, $rowStart)->getCalculatedValue();
    //get the cell value of the current loop for the lab hrs
    $lab_hrs = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(10, $rowStart)->getCalculatedValue();
    //get the cell value of the current loop for the hrs per week
    $hrs_wk = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(11, $rowStart)->getCalculatedValue();
    //just add it all
    $totalUnits = $totalUnits + $units;
    $total_lec_hrs = $total_lec_hrs + $lecture_hrs;
    $total_rle_hrs = $total_rle_hrs + $rle_hrs;
    $total_lab_hrs = $total_lab_hrs + $lab_hrs;
    $total_hrs_wk = $total_hrs_wk + $hrs_wk;
    //if same value for next and current just increment the rowstart so it can still continue to count or add
    if ($cellValue ==  $nextvalue) {                    
        $rowStart++;                                        
    }elseif($cellValue == " PART-TIME FACULTY"){
        break;
    }else{

        //else add new row
        $spreadsheet->getActiveSheet()->insertNewRowBefore($rowStart + 1, 1);
        //increment row
        $rowStart++;
        // insert data into the new row - total
        $spreadsheet->getActiveSheet()
            ->setCellValue('A' . $rowStart, $cellValue)
            ->setCellValue('D' . $rowStart, "TOTAL")
            ->setCellValue('G' . $rowStart, $totalUnits)
            ->setCellValue('H' . $rowStart, $total_lec_hrs)
            ->setCellValue('I' . $rowStart, $total_rle_hrs)
            ->setCellValue('J' . $rowStart, $total_lab_hrs)
            ->setCellValue('K' . $rowStart, $total_hrs_wk);
        //merge the cells
        $spreadsheet->getActiveSheet()->mergeCells('D'.$rowStart.':F'.$rowStart);
        //re assign it or reset the values                                    
        $new = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $rowStart)->getCalculatedValue();
        //increment the row start permanent so it will be updated
        $row_start_guest++;
        //increment the row start
        $rowStart++;
        $totalUnits = 0;
        $total_lec_hrs = 0;
        $total_rle_hrs = 0;
        $total_lab_hrs = 0;
        $total_hrs_wk = 0;              
    }
}










$row = $row_start_content_guest;
$rowCount =1;
$cRow = $row_start_content_guest;
while($row <= $row_start_guest){
    //get the current cell value
    $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row)->getCalculatedValue();
    //the next row index
    $nextRow = $row + 1;
    //get the next cell value
    $nextvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $nextRow)->getCalculatedValue();
    //if true break the loop
    if($cellValueCurrent ==  " PART-TIME FACULTY"){
        break;
    }
   if ($cellValueCurrent ==  $nextvalue) {
        //if same value then increment the row and the row count
            $row++;
            $rowCount++;
    }else{
            //merge cells for the a to c of the current row with the end of the same value and the row start merging
           $spreadsheet->getActiveSheet()->mergeCells('A'.$cRow.':C'.$row);
           $spreadsheet->getActiveSheet()->getStyle('A'.$cRow)->getAlignment()->setHorizontal('center');
            // Set wrapText for merged cells
            $spreadsheet->getActiveSheet()->getStyle('A'.$cRow)->getAlignment()->setWrapText(true);
           $cRow = $cRow + $rowCount;
           $row++;
           $rowCount = 1;
            

        }

    }
       

       $spreadsheet->getActiveSheet()->removeRow($row_start_guest, 1);
    }else{
             
    }
}else{

}




































//part time faculty

$cellvalue = "";
$row_start_part_time = $row_start_guest;
$row_start_content_part_time = $row_start_guest;
while($cellvalue != " PART-TIME FACULTY"){
     $cellvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_start_part_time)->getCalculatedValue();
       $spreadsheet->getActiveSheet()
                    ->setCellValue('P'.$row_start_part_time, $row_start_part_time);
     $row_start_part_time++;
     $row_start_content_part_time++;
}



// if($guest){
//     $row_start_content_part_time = $row_start_guest +1;
//     $row_start_part_time = $row_start_guest +1;
// }elseif(!$guest){
//     $row_start_content_part_time = $row_start_permanent + 5;
//     $row_start_part_time = $row_start_permanent + 5;
//     $spreadsheet->getActiveSheet()
//                     ->setCellValue('P'.$row_start_guest,"aaa");
// }elseif($guest && !$permanent){
//      $row_start_part_time = $row_start_guest +1;
      
// }
// else{
//     $row_start_part_time = 15;
// }





//temporary faculty

$part_time_query = mysqli_query($conn, "
SELECT 
CONCAT(fc.lastname,',',fc.firstname,' ',fc.middlename) AS 'Name of Faculty',
fc.is_permanent AS 'permanent',
cs.course_code AS 'Course Code',
CONCAT(pr.`program_abbrv`,' ',sc.`section_name`) AS 'Section',
sc.no_of_students AS 'No. of Students',
cs.`units` AS 'Total Units',
cs.`lec_hrs_wk` AS 'Lec. hrs/wk',
cs.`lab_hrs_wk` AS 'Lab. hrs/wk',
cs.`rle_hrs_wk` AS 'Rle. hrs/wk',
SUM(cs.`lec_hrs_wk` + cs.`lab_hrs_wk`) AS 'Total hrs/wk',
cs.`course_description` AS 'Course Description',
t.`title_description`
FROM
faculty_loadings fl
LEFT JOIN faculties fc ON fl.`faculty_id`=fc.`faculty_id`
LEFT JOIN courses cs ON fl.`course_id`=cs.`course_id`
LEFT JOIN sections sc ON fl.`section_id`=sc.`section_id`
LEFT JOIN programs pr ON sc.`program_id`=pr.`program_id`
LEFT JOIN faculty_titles ft ON ft.`faculty_id` = fc.`faculty_id`
LEFT JOIN titles t ON t.`title_id` = ft.`title_id`
LEFT JOIN departments d ON d.`department_id` = fc.`department_id`
LEFT JOIN semesters s ON s.semester_id = fl.sem_id
LEFT JOIN academic_year ay ON ay.acad_year_id = fl.acad_year_id
WHERE fc.is_partTime = 1 AND d.`department_id` = '$dept_id'
AND s.semester_id = '$semester_id'
AND ay.acad_year_id = '$acad_id'

GROUP BY fl.`fac_load_id`
ORDER BY  fc.firstname");




$data_partTime = [];

if($part_time_query){
    $part = true;
    if(mysqli_num_rows($part_time_query) > 0){
        
        while ($row = mysqli_fetch_array($part_time_query)) {
              $facultyname = $row['Name of Faculty'];
              $course_code =  $row['Course Code'];
              $section =  $row['Section'];
              $students =  $row['No. of Students'];
              $units =  $row['Total Units'];
              $lec_hrs =  $row['Lec. hrs/wk'];
              $rle_hrs =  $row['Rle. hrs/wk'];
              $lab_hrs =  $row['Lab. hrs/wk'];
              $total_hrs_wk =  $row['Total hrs/wk'];
              $course_description =  $row['Course Description'];
              $title_description = $row['title_description'];
              // Append the current row's data to the $data array
              $data_partTime[] = array(
                      "facultyname" => $facultyname,
                     "course_code" => $course_code,
                     "section" => $section,
                     "students" => $students,
                     "units" => $units,
                     "lec_hrs" => $lec_hrs,
                     "lab_hrs" => $lab_hrs,
                     "rle_hrs" =>$rle_hrs,
                     "total_hrs_wk" => $total_hrs_wk,
                     "course_description" => $course_description,
                     "title_description" => $title_description
                     
              );
           
       }


        foreach($data_partTime as $row){
            //insert it all to the array
            $facultyname = $row['facultyname'];
            $course_code =  $row['course_code'];
            $section =  $row['section'];
            $students =  $row['students'];
            $units =  $row['units'];
            $lec_hrs =  $row['lec_hrs'];
            $rle_hrs =  $row['rle_hrs'];
            $lab_hrs =  $row['lab_hrs'];
            $total_hrs_wk =  $row['total_hrs_wk'];
            $course_description =  $row['course_description'];
            $title_description = $row['title_description'];
            $cellValuenext = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row_start_part_time+1)->getCalculatedValue();
            if($cellValuenext == " PART-TIME FACULTY"){
                $spreadsheet->getActiveSheet()->insertNewRowBefore($row_start_part_time, 1);
                
            }
            $spreadsheet->getActiveSheet()
                    ->setCellValue('A'.$row_start_part_time, $facultyname ."\n" . $title_description)
                    ->setCellValue('D'.$row_start_part_time, $course_code)
                    ->setCellValue('E'.$row_start_part_time, $section)
                    ->setCellValue('F'.$row_start_part_time, $students)
                    ->setCellValue('G'.$row_start_part_time, $units)
                    ->setCellValue('H'.$row_start_part_time, $lec_hrs)
                    ->setCellValue('I'.$row_start_part_time, $rle_hrs)
                    ->setCellValue('J'.$row_start_part_time, $lab_hrs)
                    ->setCellValue('K'.$row_start_part_time, $rle_hrs)
                    ->setCellValue('L'.$row_start_part_time, $course_description);
                    
              
              $row_start_part_time++;

                       

              

       }
    }else{

    }
}else{

}
$spreadsheet->getActiveSheet()->removeRow($row_start_part_time, 1);










$rowStart = $row_start_content_part_time; //the start of the counting
$oldval = ""; //the current old val
//varibales to increment
$totalUnits = 0;
$total_lec_hrs = 0;
$total_rle_hrs = 0;
$total_lab_hrs = 0;
$total_hrs_wk = 0;
//looping
while($row_start_part_time >= $rowStart){

    //get the current value or the faculty name
    $cellValue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $rowStart)->getCalculatedValue();
    //get the next row index
     $nextRow = $rowStart + 1;
     //get the next row index value
    $nextvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $nextRow)->getCalculatedValue();
    //get the cell value of the current loop for the units
    $units = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(7, $rowStart)->getCalculatedValue();
    //get the cell value of the current loop for the lec hrs
    $lecture_hrs = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(8, $rowStart)->getCalculatedValue();
    //get the cell value of the current loop for the rle hrs
    $rle_hrs = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(9, $rowStart)->getCalculatedValue();
    //get the cell value of the current loop for the lab hrs
    $lab_hrs = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(10, $rowStart)->getCalculatedValue();
    //get the cell value of the current loop for the hrs per week
    $hrs_wk = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(11, $rowStart)->getCalculatedValue();
    //just add it all
    $totalUnits = $totalUnits + $units;
    $total_lec_hrs = $total_lec_hrs + $lecture_hrs;
    $total_rle_hrs = $total_rle_hrs + $rle_hrs;
    $total_lab_hrs = $total_lab_hrs + $lab_hrs;
    $total_hrs_wk = $total_hrs_wk + $hrs_wk;
    //if same value for next and current just increment the rowstart so it can still continue to count or add
    if ($cellValue ==  $nextvalue) {                    
        $rowStart++;                                        
    }elseif(empty($cellValue)){
        break;
    }else{

        //else add new row
        $spreadsheet->getActiveSheet()->insertNewRowBefore($rowStart + 1, 1);
        //increment row
        $rowStart++;
        // insert data into the new row - total
        $spreadsheet->getActiveSheet()
            ->setCellValue('A' . $rowStart, $cellValue)
            ->setCellValue('D' . $rowStart, "TOTAL")
            ->setCellValue('G' . $rowStart, $totalUnits)
            ->setCellValue('H' . $rowStart, $total_lec_hrs)
            ->setCellValue('I' . $rowStart, $total_rle_hrs)
            ->setCellValue('J' . $rowStart, $total_lab_hrs)
            ->setCellValue('K' . $rowStart, $total_hrs_wk);
        //merge the cells
        $spreadsheet->getActiveSheet()->mergeCells('D'.$rowStart.':F'.$rowStart);
        //re assign it or reset the values                                    
        $new = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $rowStart)->getCalculatedValue();
        //increment the row start permanent so it will be updated
        $row_start_part_time++;
        //increment the row start
        $rowStart++;
        $totalUnits = 0;
        $total_lec_hrs = 0;
        $total_rle_hrs = 0;
        $total_lab_hrs = 0;
        $total_hrs_wk = 0;              
    }
}






$row = $row_start_content_part_time;
$rowCount =1;
$cRow = $row_start_content_part_time;
while($row <= $row_start_part_time){
    //get the current cell value
    $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row)->getCalculatedValue();
    //the next row index
    $nextRow = $row + 1;
    //get the next cell value
    $nextvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $nextRow)->getCalculatedValue();
    //if true break the loop
    if(empty($cellValueCurrent)){
        break;
    }
   if ($cellValueCurrent ==  $nextvalue) {
        //if same value then increment the row and the row count
            $row++;
            $rowCount++;
    }else{
            //merge cells for the a to c of the current row with the end of the same value and the row start merging
           $spreadsheet->getActiveSheet()->mergeCells('A'.$cRow.':C'.$row);
           $spreadsheet->getActiveSheet()->getStyle('A'.$cRow)->getAlignment()->setHorizontal('center');
            // Set wrapText for merged cells
            $spreadsheet->getActiveSheet()->getStyle('A'.$cRow)->getAlignment()->setWrapText(true);
           $cRow = $cRow + $rowCount;
           $row++;
           $rowCount = 1;
            

        }

    }



function getdesignation($faculty_id){
    include "../config.php";

    $sql = "
            SELECT 
                f.`firstname`,
                f.`middlename`,
                f.`lastname`,
                f.`suffix`,
             
                GROUP_CONCAT(d.`designation` SEPARATOR ', ') AS `all_designations`
                
            FROM faculties f

            LEFT JOIN faculty_designation fd ON f.`faculty_id` = fd.`faculty_id`
            LEFT JOIN designation d ON d.designation_id = fd.`designation_id`
            WHERE f.`faculty_id` = '$faculty_id'
            GROUP BY f.`faculty_id`
            ";
    $result = mysqli_query($conn,$sql);
    if($result){
        if(mysqli_num_rows($result) >0){
            $row = mysqli_fetch_assoc($result);
            $designations = $row['all_designations'];
            return $designations;
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
header('Content-Disposition: attachment;filename="faculty loading.xlsx"');

//create IOFactory object
$writer = IOFactory::createWriter($spreadsheet, 'Xls');
//save into php output
$writer->save('php://output');


?>