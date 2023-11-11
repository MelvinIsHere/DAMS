<?php
//call the autoload
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
//load phpspreadsheet class using namespaces
use PhpOffice\PhpSpreadsheet\Spreadsheet;
//call iofactory instead of xlsx writer
use PhpOffice\PhpSpreadsheet\IOFactory;
       use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;











$reader = IOFactory::createReader('Xlsx');
$spreadsheet = $reader->load("loading.xlsx");

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
SELECT 
CONCAT(fc.lastname,',',fc.firstname,' ',fc.middlename) AS 'Name of Faculty',
fc.is_permanent AS 'permanent',
fc.is_guest AS 'Guest',
fc.is_partTime AS 'part_time',
cs.course_code AS 'Course Code',
CONCAT(pr.`program_abbrv`,' ',sc.`section_name`) AS 'Section',
sc.no_of_students AS 'No. of Students',
cs.`units` AS 'Total Units',
cs.`lec_hrs_wk` AS 'Lec. hrs/wk',
cs.`lab_hrs_wk` AS 'Lab. hrs/wk',
cs.`rle_hrs_wk` AS 'Rle. hrs/wk',
SUM(cs.`lec_hrs_wk` + cs.`lab_hrs_wk`) AS 'Total hrs/wk',
cs.`course_description` AS 'Course Description',
t.`title_description`,
s.sem_description
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
AND s.status = 'ACTIVE'
AND ay.status = 'ACTIVE'

GROUP BY fl.`fac_load_id`
ORDER BY  fc.firstname;

");


if(mysqli_num_rows($active_semester) > 0){

$row = mysqli_fetch_array($active_semester);
$semester = $row['sem_description'];
$semseterWhere = 4;
$semesterCell = 4;

    
        // Fill the cell with data
        $spreadsheet->getActiveSheet()
          
            ->setCellValue('K'.$semesterCell,$semester . " Semester");
            // ... set other cell values


}

        //add more 1 more row
        
        //increment value
        
 





$active_academic_year = mysqli_query($conn, "
SELECT 
CONCAT(fc.lastname,',',fc.firstname,' ',fc.middlename) AS 'Name of Faculty',
fc.is_permanent AS 'permanent',
fc.is_guest AS 'Guest',
fc.is_partTime AS 'part_time',
cs.course_code AS 'Course Code',
CONCAT(pr.`program_abbrv`,' ',sc.`section_name`) AS 'Section',
sc.no_of_students AS 'No. of Students',
cs.`units` AS 'Total Units',
cs.`lec_hrs_wk` AS 'Lec. hrs/wk',
cs.`lab_hrs_wk` AS 'Lab. hrs/wk',
cs.`rle_hrs_wk` AS 'Rle. hrs/wk',
SUM(cs.`lec_hrs_wk` + cs.`lab_hrs_wk`) AS 'Total hrs/wk',
cs.`course_description` AS 'Course Description',
t.`title_description`,
ay.acad_year
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
AND s.status = 'ACTIVE'
AND ay.status = 'ACTIVE'

GROUP BY fl.`fac_load_id`
ORDER BY  fc.firstname;

");

if(mysqli_num_rows($active_academic_year)){
    $row = mysqli_fetch_array($active_academic_year);
    $acad_year = $row['acad_year'];

     $academicYear = 4;
    $academicYearCell = 4;

    
        // Fill the cell with data
        $spreadsheet->getActiveSheet()
          
            ->setCellValue('N'.$academicYearCell,$acad_year);
            // ... set other cell values

        //add more 1 more row
        
        //increment value
        

}

    


$collegeWhereStart = 3;
$collegeCell = 3;

    
        // Fill the cell with data
        $spreadsheet->getActiveSheet()
          
            ->setCellValue('B'.$collegeCell,$dept_abbrv);
                

$campusWhereCell = 4;
$campusCell = 4;

    
        // Fill the cell with data
        $spreadsheet->getActiveSheet()
          
            ->setCellValue('B'.$campusCell,'ARASOF');
            // ... set other cell values

        



$data = mysqli_query($conn, "
SELECT 
CONCAT(fc.lastname,',',fc.firstname,' ',fc.middlename) AS 'Name of Faculty',
fc.is_permanent AS 'permanent',
fc.is_guest AS 'Guest',
fc.is_partTime AS 'part_time',
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
AND s.status = 'ACTIVE'
AND ay.status = 'ACTIVE'

GROUP BY fl.`fac_load_id`
ORDER BY  fc.firstname;

");



if(mysqli_num_rows($data) > 0){





// Loop for permanent items ----- permanent faculty
// Loop for permanent items ----- permanent faculty

// Loop through all the data for permanent faculty
    $contentStartRow = 7;
$currentContentRow = 7;
$cellValueOld = "";
$worksheet = $spreadsheet->getActiveSheet();
$mergeStart = 7;
$loopCount = 0;
while ($item = mysqli_fetch_array($data)) {
    if ($item['permanent'] == 1) {
        // Fill the cell with data
        $currentCellValue = $item['Name of Faculty'];
        
        $spreadsheet->getActiveSheet()
            ->setCellValue('A' . $currentContentRow, $item['Name of Faculty'] ."\n". " " . $item['title_description'] )
            ->setCellValue('D'.$currentContentRow, $item['Course Code'])
            ->setCellValue('E'.$currentContentRow, $item['Section'])
            ->setCellValue('F'.$currentContentRow, $item['No. of Students'])
            ->setCellValue('G'.$currentContentRow, $item['Total Units'])
            ->setCellValue('H'.$currentContentRow, $item['Lec. hrs/wk'])
            ->setCellValue('I'.$currentContentRow, $item['Rle. hrs/wk'])
            ->setCellValue('J'.$currentContentRow, $item['Lab. hrs/wk'])
            ->setCellValue('K'.$currentContentRow, $item['Total hrs/wk'])
            ->setCellValue('L'.$currentContentRow, $item['Course Description']);
            

            

        // ... set other cell values

     
       

       
        

        // Update the cellValueOld with the currentCellValue for the next iteration
        $cellValueOld = $currentCellValue;
    }


    // // Remove empty rows within the data range
    // $dataRange = 'A7:C' . ($currentContentRow - 1); // Adjust the range as per your requirement
    // foreach ($worksheet->getRowIterator(7, $currentContentRow - 1) as $row) {
    //     $rowIndex = $row->getRowIndex();
    //     $isEmpty = true;
    //     foreach ($worksheet->getColumnIterator('A', 'C') as $column) {
    //         $cellValue = $worksheet->getCell($column->getColumnIndex() . $rowIndex)->getValue();
    //         if (!empty($cellValue)) {
    //             $isEmpty = false;
    //             break;
    //         }
    //     }
    //     if ($isEmpty) {
    //         $worksheet->removeRow($rowIndex, 1);
    //         $currentContentRow--; // Decrement the row counter
    //     }
    // }
$spreadsheet->getActiveSheet()->insertNewRowBefore($currentContentRow + 1, 1);
    
    $currentContentRow++;
}






//for inserting the total
$rowStart = 7;
$oldval = "";
$totalUnits = 0;
$total_lec_hrs = 0;
$total_rle_hrs = 0;
$total_lab_hrs = 0;
$total_hrs_wk = 0;
while($currentContentRow >= $rowStart){
    $cellValue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $rowStart)->getCalculatedValue();
     $nextRow = $rowStart + 1;
    $nextvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $nextRow)->getCalculatedValue();
    $units = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(7, $rowStart)->getCalculatedValue();
    $lecture_hrs = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(8, $rowStart)->getCalculatedValue();
    $rle_hrs = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(9, $rowStart)->getCalculatedValue();
    $lab_hrs = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(10, $rowStart)->getCalculatedValue();
    $hrs_wk = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(11, $rowStart)->getCalculatedValue();
    $totalUnits = $totalUnits + $units;
    $total_lec_hrs = $total_lec_hrs + $lecture_hrs;
    $total_rle_hrs = $total_rle_hrs + $rle_hrs;
    $total_lab_hrs = $total_lab_hrs + $lab_hrs;
    $total_hrs_wk = $total_hrs_wk + $hrs_wk;
  if ($cellValue ==  $nextvalue) {
                     // $spreadsheet->getActiveSheet()
                     //    ->setCellValue('Q' . $rowStart, " same");
                    $rowStart++;
                    
                    

                    }   
              else{

                    $spreadsheet->getActiveSheet()->insertNewRowBefore($rowStart + 1, 1);
                    $rowStart++;

                      //insert data into the new row - total
                  // ...

                        // insert data into the new row - total
                        $spreadsheet->getActiveSheet()
                            ->setCellValue('A' . $rowStart, $cellValue)
                            ->setCellValue('D' . $rowStart, "TOTAL")
                            ->setCellValue('G' . $rowStart, $totalUnits)
                            ->setCellValue('H' . $rowStart, $total_lec_hrs)
                            ->setCellValue('I' . $rowStart, $total_rle_hrs)
                            ->setCellValue('J' . $rowStart, $total_lab_hrs)
                            ->setCellValue('K' . $rowStart, $total_hrs_wk);
                            $spreadsheet->getActiveSheet()->mergeCells('D'.$rowStart.':F'.$rowStart);
                

                    
                    $new = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $rowStart)->getCalculatedValue();
                    $currentContentRow++;
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
    while($row <= $currentContentRow){
        // Update the old cell value
        $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row)->getCalculatedValue();
        // $cellValueNew = $currentCellValue;
        $nextRow = $row + 1;
         $nextvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $nextRow)->getCalculatedValue();

          if($cRow == 7){
                 if ($cellValueCurrent ==  $nextvalue) {
                 // $spreadsheet->getActiveSheet()
                 //    ->setCellValue('Q' . $row, " same");
                $row++;
                $rowCount++;

                }   
              else{
                    $spreadsheet->getActiveSheet()->unmergeCells('A'.$cRow.':C'.$cRow);
                    
                   $spreadsheet->getActiveSheet()->mergeCells('A'.$row.':C'.$cRow);
                   $spreadsheet->getActiveSheet()->mergeCells('A'.$row.':C'.$cRow);

                   // Get style for the merged cells
                   $spreadsheet->getActiveSheet()->getStyle('A'.$cRow)->getAlignment()->setHorizontal('center');
                   $spreadsheet->getActiveSheet()->getStyle('A'.$cRow)->getAlignment()->setWrapText(true); 

                    // Set vertical alignment to center
                   $cRow = $cRow + $rowCount;
                   $row++;
                   $rowCount = 1;
                }
        } 
        else if ($cellValueCurrent ==  $nextvalue) {
        //          $spreadsheet->getActiveSheet()
        // ->setCellValue('Q' . $row, " same");
            $row++;
            $rowCount++;
             
        }

        else{
        //      $spreadsheet->getActiveSheet()
        // ->setCellValue('Q' . $row, "not the same");
         
           $spreadsheet->getActiveSheet()->mergeCells('A'.$row.':C'.$cRow);
           $spreadsheet->getActiveSheet()->getStyle('A'.$cRow)->getAlignment()->setHorizontal('center');
           
    $spreadsheet->getActiveSheet()->getStyle('A'.$cRow)->getAlignment()->setWrapText(true); // Set wrapText for merged cells
           $cRow = $cRow + $rowCount;
           $row++;
           $rowCount = 1;
            

        }
       


      


    

}



// // Remove rows for the permanent faculty
// $spreadsheet->getActiveSheet()->removeRow($currentContentRow, 3);




}else{

}






$data_guest = mysqli_query($conn, "

SELECT 
CONCAT(fc.lastname,',',fc.firstname,' ',fc.middlename) AS 'Name of Faculty',
fc.is_permanent AS 'permanent',
fc.is_guest AS 'Guest',
fc.is_partTime AS 'part_time',
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
AND s.status = 'ACTIVE'
AND ay.status = 'ACTIVE'
GROUP BY fl.`fac_load_id`
ORDER BY  fc.firstname;

");

if(mysqli_num_rows($data_guest) > 0 ){



$contentStartRow_temp = $currentContentRow + 1;
$currentContentRow_temp = $currentContentRow + 1;
$cellValueOld = "";
$worksheet = $spreadsheet->getActiveSheet();
$mergeStart = $currentContentRow + 1;
$loopCount = 0;

// Loop through all the data for permanent faculty
while ($item_guest = mysqli_fetch_array($data_guest)) {
    if ($item_guest['Guest'] == 1) {
        // Fill the cell with data
        $currentCellValue = $item_guest['Name of Faculty'];
        
        $spreadsheet->getActiveSheet()
            ->setCellValue('A' . $currentContentRow_temp, $item_guest['Name of Faculty']  ."\n". " " . $item_guest['title_description'] )
            ->setCellValue('D'.$currentContentRow_temp, $item_guest['Course Code'])
            ->setCellValue('E'.$currentContentRow_temp, $item_guest['Section'])
            ->setCellValue('F'.$currentContentRow_temp, $item_guest['No. of Students'])
            ->setCellValue('G'.$currentContentRow_temp, $item_guest['Total Units'])
            ->setCellValue('H'.$currentContentRow_temp, $item_guest['Lec. hrs/wk'])
            ->setCellValue('I'.$currentContentRow_temp, $item_guest['Rle. hrs/wk'])
            ->setCellValue('J'.$currentContentRow_temp, $item_guest['Lab. hrs/wk'])
            ->setCellValue('K'.$currentContentRow_temp, $item_guest['Total hrs/wk'])
            ->setCellValue('L'.$currentContentRow_temp, $item_guest['Course Description']);
            

            

        // ... set other cell values

     
       

       
        

        // Update the cellValueOld with the currentCellValue for the next iteration
        $cellValueOld = $currentCellValue;
    }

  // Remove empty rows within the data range
    $dataRange = 'A'.$contentStartRow_temp.':C' . ($currentContentRow_temp - 1); // Adjust the range as per your requirement
    foreach ($worksheet->getRowIterator($contentStartRow_temp, $currentContentRow_temp - 1) as $row) {
        $rowIndex = $row->getRowIndex();
        $isEmpty = true;
        foreach ($worksheet->getColumnIterator('A', 'C') as $column) {
            $cellValue = $worksheet->getCell($column->getColumnIndex() . $rowIndex)->getValue();
            if (!empty($cellValue)) {
                $isEmpty = false;
                break;
            }
        }
        if ($isEmpty) {
            $worksheet->removeRow($rowIndex, 1);
            $currentContentRow_temp--; // Decrement the row counter
        }
    }


  $spreadsheet->getActiveSheet()->insertNewRowBefore($currentContentRow_temp+1, 1);
    
    $currentContentRow_temp++;
}



//for inserting the total
$rowStart = $contentStartRow_temp;
$oldval = "";
$totalUnits = 0;
$total_lec_hrs = 0;
$total_rle_hrs = 0;
$total_lab_hrs = 0;
$total_hrs_wk = 0;
while($currentContentRow_temp >= $rowStart){






    $cellValue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $rowStart)->getCalculatedValue();
     $nextRow = $rowStart + 1;
    $nextvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $nextRow)->getCalculatedValue();
    $units = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(7, $rowStart)->getCalculatedValue();
    $lecture_hrs = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(8, $rowStart)->getCalculatedValue();
    $rle_hrs = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(9, $rowStart)->getCalculatedValue();
    $lab_hrs = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(10, $rowStart)->getCalculatedValue();
    $hrs_wk = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(11, $rowStart)->getCalculatedValue();
    $totalUnits = $totalUnits + $units;
    $total_lec_hrs = $total_lec_hrs + $lecture_hrs;
    $total_rle_hrs = $total_rle_hrs + $rle_hrs;
    $total_lab_hrs = $total_lab_hrs + $lab_hrs;
    $total_hrs_wk = $total_hrs_wk + $hrs_wk;
  if ($cellValue ==  $nextvalue) {
                     // $spreadsheet->getActiveSheet()
                     //    ->setCellValue('Q' . $rowStart, " same");
                    $rowStart++;
                    
                    

                    }   
              else{

                    $spreadsheet->getActiveSheet()->insertNewRowBefore($rowStart + 1, 1);
                    $rowStart++;

                      //insert data into the new row - total
                  // ...

                        // insert data into the new row - total
                        $spreadsheet->getActiveSheet()
                            ->setCellValue('A' . $rowStart, $cellValue)
                            ->setCellValue('D' . $rowStart, "TOTAL")
                            ->setCellValue('G' . $rowStart, $totalUnits)
                            ->setCellValue('H' . $rowStart, $total_lec_hrs)
                            ->setCellValue('I' . $rowStart, $total_rle_hrs)
                            ->setCellValue('J' . $rowStart, $total_lab_hrs)
                            ->setCellValue('K' . $rowStart, $total_hrs_wk);
                            $spreadsheet->getActiveSheet()->mergeCells('D'.$rowStart.':F'.$rowStart);
                

                    
                    $new = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $rowStart)->getCalculatedValue();
                    $currentContentRow_temp++;
                    $rowStart++;
                    $totalUnits = 0;
                    $total_lec_hrs = 0;
                    $total_rle_hrs = 0;
                    $total_lab_hrs = 0;
                    $total_hrs_wk = 0;
              


}

  
}










    $row = $contentStartRow_temp;
    $rowCount =1;
    $cRow = $contentStartRow_temp;
    while($row <= $currentContentRow_temp){
        // Update the old cell value
        $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row)->getCalculatedValue();
        // $cellValueNew = $currentCellValue;
        $nextRow = $row + 1;
         $nextvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $nextRow)->getCalculatedValue();

          if($cRow == $contentStartRow_temp){
                 if ($cellValueCurrent ==  $nextvalue) {
                 // $spreadsheet->getActiveSheet()
                 //    ->setCellValue('Q' . $row, " same");
                $row++;
                $rowCount++;

                }   
              else{
                    $spreadsheet->getActiveSheet()->unmergeCells('A'.$cRow.':C'.$cRow);
                    
                   $spreadsheet->getActiveSheet()->mergeCells('A'.$row.':C'.$cRow);
                   $spreadsheet->getActiveSheet()->mergeCells('A'.$row.':C'.$cRow);

                   // Get style for the merged cells
                   $spreadsheet->getActiveSheet()->getStyle('A'.$cRow)->getAlignment()->setHorizontal('center');
                   $spreadsheet->getActiveSheet()->getStyle('A'.$cRow)->getAlignment()->setWrapText(true); 

                    // Set vertical alignment to center
                   $cRow = $cRow + $rowCount;
                   $row++;
                   $rowCount = 1;
                }
        } 
        else if ($cellValueCurrent ==  $nextvalue) {
        //          $spreadsheet->getActiveSheet()
        // ->setCellValue('Q' . $row, " same");
            $row++;
            $rowCount++;
             
        }

        else{
        //      $spreadsheet->getActiveSheet()
        // ->setCellValue('Q' . $row, "not the same");
         
           $spreadsheet->getActiveSheet()->mergeCells('A'.$row.':C'.$cRow);
           $spreadsheet->getActiveSheet()->getStyle('A'.$cRow)->getAlignment()->setHorizontal('center');
           $spreadsheet->getActiveSheet()->getStyle('A'.$cRow)->getAlignment()->setWrapText(true); 
           $cRow = $cRow + $rowCount;
           $row++;
           $rowCount = 1;
            

        }
       


      


    

}


// $spreadsheet->getActiveSheet()->removeRow($currentContentRow_temp, 3);








}else{

}







$data_partTime = mysqli_query($conn, "

SELECT 
CONCAT(fc.lastname,',',fc.firstname,' ',fc.middlename) AS 'Name of Faculty',
fc.is_permanent AS 'permanent',
fc.is_guest AS 'Guest',
fc.is_partTime AS 'part_time',
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
AND s.status = 'ACTIVE'
AND ay.status = 'ACTIVE'
GROUP BY fl.`fac_load_id`
ORDER BY  fc.firstname;

");


if(mysqli_num_rows($data_partTime) > 0){


$contentStartRow_part = $currentContentRow_temp + 1;
$currentContentRow_part = $currentContentRow_temp + 1;
$cellValueOld = "";
$worksheet = $spreadsheet->getActiveSheet();
$mergeStart = $currentContentRow + 1;
$loopCount = 0;

// Loop through all the data for permanent faculty
while ($item_partTime = mysqli_fetch_array($data_partTime)) {
    if ($item_partTime['part_time'] == 1) {
        // Fill the cell with data
        $currentCellValue = $item_partTime['Name of Faculty'];
        
        $spreadsheet->getActiveSheet()
            ->setCellValue('A' . $currentContentRow_part, $item_partTime['Name of Faculty']  ."\n". " " . $item_partTime['title_description'] )
            ->setCellValue('D'.$currentContentRow_part, $item_partTime['Course Code'])
            ->setCellValue('E'.$currentContentRow_part, $item_partTime['Section'])
            ->setCellValue('F'.$currentContentRow_part, $item_partTime['No. of Students'])
            ->setCellValue('G'.$currentContentRow_part, $item_partTime['Total Units'])
            ->setCellValue('H'.$currentContentRow_part, $item_partTime['Lec. hrs/wk'])
            ->setCellValue('I'.$currentContentRow_part, $item_partTime['Rle. hrs/wk'])
            ->setCellValue('J'.$currentContentRow_part, $item_partTime['Lab. hrs/wk'])
            ->setCellValue('K'.$currentContentRow_part, $item_partTime['Total hrs/wk'])
            ->setCellValue('L'.$currentContentRow_part, $item_partTime['Course Description']);
            

            

        // ... set other cell values

     
       

       
        

        // Update the cellValueOld with the currentCellValue for the next iteration
        $cellValueOld = $currentCellValue;
    }

  // Remove empty rows within the data range
    $dataRange = 'A'.$contentStartRow_part.':C' . ($contentStartRow_part - 1); // Adjust the range as per your requirement
    foreach ($worksheet->getRowIterator($contentStartRow_part, $currentContentRow_part - 1) as $row) {
        $rowIndex = $row->getRowIndex();
        $isEmpty = true;
        foreach ($worksheet->getColumnIterator('A', 'C') as $column) {
            $cellValue = $worksheet->getCell($column->getColumnIndex() . $rowIndex)->getValue();
            if (!empty($cellValue)) {
                $isEmpty = false;
                break;
            }
        }
        if ($isEmpty) {
            $worksheet->removeRow($rowIndex, 1);
            $currentContentRow_part--; // Decrement the row counter
        }
    }


  $spreadsheet->getActiveSheet()->insertNewRowBefore($currentContentRow_part+1, 1);
    
    $currentContentRow_part++;
}


//for inserting the total
$rowStart = $contentStartRow_part;
$oldval = "";
$totalUnits = 0;
$total_lec_hrs = 0;
$total_rle_hrs = 0;
$total_lab_hrs = 0;
$total_hrs_wk = 0;
while($currentContentRow_part >= $rowStart){






    $cellValue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $rowStart)->getCalculatedValue();
     $nextRow = $rowStart + 1;
    $nextvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $nextRow)->getCalculatedValue();
    $units = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(7, $rowStart)->getCalculatedValue();
    $lecture_hrs = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(8, $rowStart)->getCalculatedValue();
    $rle_hrs = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(9, $rowStart)->getCalculatedValue();
    $lab_hrs = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(10, $rowStart)->getCalculatedValue();
    $hrs_wk = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(11, $rowStart)->getCalculatedValue();
    $totalUnits = $totalUnits + $units;
    $total_lec_hrs = $total_lec_hrs + $lecture_hrs;
    $total_rle_hrs = $total_rle_hrs + $rle_hrs;
    $total_lab_hrs = $total_lab_hrs + $lab_hrs;
    $total_hrs_wk = $total_hrs_wk + $hrs_wk;
  if ($cellValue ==  $nextvalue) {
                     // $spreadsheet->getActiveSheet()
                     //    ->setCellValue('Q' . $rowStart, " same");
                    $rowStart++;
                    
                    

                    }   
              else{

                    $spreadsheet->getActiveSheet()->insertNewRowBefore($rowStart + 1, 1);
                    $rowStart++;

                      //insert data into the new row - total
                  // ...

                        // insert data into the new row - total
                        $spreadsheet->getActiveSheet()
                            ->setCellValue('A' . $rowStart, $cellValue)
                            ->setCellValue('D' . $rowStart, "TOTAL")
                            ->setCellValue('G' . $rowStart, $totalUnits)
                            ->setCellValue('H' . $rowStart, $total_lec_hrs)
                            ->setCellValue('I' . $rowStart, $total_rle_hrs)
                            ->setCellValue('J' . $rowStart, $total_lab_hrs)
                            ->setCellValue('K' . $rowStart, $total_hrs_wk);
                            $spreadsheet->getActiveSheet()->mergeCells('D'.$rowStart.':F'.$rowStart);
                

                    
                    $new = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $rowStart)->getCalculatedValue();
                    $currentContentRow_part++;
                    $rowStart++;
                    $totalUnits = 0;
                    $total_lec_hrs = 0;
                    $total_rle_hrs = 0;
                    $total_lab_hrs = 0;
                    $total_hrs_wk = 0;
              


}

  
}










    $row = $contentStartRow_part;
    $rowCount =1;
    $cRow = $contentStartRow_part;
    while($row <= $currentContentRow_part){
        // Update the old cell value
        $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row)->getCalculatedValue();
        // $cellValueNew = $currentCellValue;
        $nextRow = $row + 1;
         $nextvalue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $nextRow)->getCalculatedValue();

          if($cRow == $contentStartRow_part){
                 if ($cellValueCurrent ==  $nextvalue) {
                 // $spreadsheet->getActiveSheet()
                 //    ->setCellValue('Q' . $row, " same");
                $row++;
                $rowCount++;

                }   
              else{
                    $spreadsheet->getActiveSheet()->unmergeCells('A'.$cRow.':C'.$cRow);
                    
                   $spreadsheet->getActiveSheet()->mergeCells('A'.$row.':C'.$cRow);
                   // $spreadsheet->getActiveSheet()->mergeCells('A'.$row.':C'.$cRow);

                   // Get style for the merged cells
                   $spreadsheet->getActiveSheet()->getStyle('A'.$cRow)->getAlignment()->setHorizontal('center');
                   $spreadsheet->getActiveSheet()->getStyle('A'.$cRow)->getAlignment()->setWrapText(true); 

                    // Set vertical alignment to center
                   $cRow = $cRow + $rowCount;
                   $row++;
                   $rowCount = 1;
                }
        } 
        else if ($cellValueCurrent ==  $nextvalue) {
        //          $spreadsheet->getActiveSheet()
        // ->setCellValue('Q' . $row, " same");
            $row++;
            $rowCount++;
             
        }

        else{
        //      $spreadsheet->getActiveSheet()
        // ->setCellValue('Q' . $row, "not the same");
         
           $spreadsheet->getActiveSheet()->mergeCells('A'.$row.':C'.$cRow);
           $spreadsheet->getActiveSheet()->getStyle('A'.$cRow)->getAlignment()->setHorizontal('center');
           $spreadsheet->getActiveSheet()->getStyle('A'.$cRow)->getAlignment()->setWrapText(true); 
           $cRow = $cRow + $rowCount;
           $row++;
           $rowCount = 1;
            

        }
       


      


    

}
$spreadsheet->getActiveSheet()->removeRow($currentContentRow_part, 4);



}else{

}


//set the header first, so the result will be treated as an xlsx file.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

//make it an attachment so we can define filename
header('Content-Disposition: attachment;filename="result.xlsx"');

//create IOFactory object
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
//save into php output
$writer->save('php://output');



?>