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

$data = mysqli_query($conn, "


SELECT 
CONCAT(fc.lastname,',',fc.firstname,' ',fc.middlename) 'Name of Faculty',
fc.is_permanent AS 'permanent',
fc.is_guest AS 'Guest',
fc.is_partTime AS 'part_time',
cs.course_code 'Course Code',
CONCAT(pr.`program_abbrv`,' ',sc.`section_name`)'Section',
sc.no_of_students 'No. of Students',
cs.`units` 'Total Units',
cs.`lec_hrs_wk` 'Lec. hrs/wk',
cs.`lab_hrs_wk` 'Lab. hrs/wk',
cs.`rle_hrs_wk` 'Rle. hrs/wk',
SUM(cs.`lec_hrs_wk`+cs.`lab_hrs_wk`) 'Total hrs/wk',
cs.`course_description` 'Course Description'
FROM
faculty_loadings fl
LEFT JOIN faculties fc ON fl.`faculty_id`=fc.`faculty_id`
LEFT JOIN courses cs ON fl.`course_id`=cs.`course_id`
LEFT JOIN sections sc ON fl.`section_id`=sc.`section_id`
LEFT JOIN programs pr ON sc.`program_id`=pr.`program_id`

GROUP BY fl.`fac_load_id`
ORDER BY  fc.firstname
");





$collegeWhereStart = 3;
$collegeCell = 3;

    
        // Fill the cell with data
        $spreadsheet->getActiveSheet()
          
            ->setCellValue('B'.$collegeCell,'CICS');
            // ... set other cell values

        //add more 1 more row
        
        //increment value
        
    




$campusWhereCell = 4;
$campusCell = 4;

    
        // Fill the cell with data
        $spreadsheet->getActiveSheet()
          
            ->setCellValue('B'.$campusCell,'ARASOF');
            // ... set other cell values

        //add more 1 more row
        
        //increment value
        
    


$semseterWhere = 4;
$semesterCell = 4;

    
        // Fill the cell with data
        $spreadsheet->getActiveSheet()
          
            ->setCellValue('K'.$semesterCell,'2nd Semester');
            // ... set other cell values

        //add more 1 more row
        
        //increment value
        
 
 $academicYear = 4;
$academicYearCell = 4;

    
        // Fill the cell with data
        $spreadsheet->getActiveSheet()
          
            ->setCellValue('N'.$academicYearCell,'2023 - 2024');
            // ... set other cell values

        //add more 1 more row
        
        //increment value
        
    







// Loop for permanent items ----- permanent faculty
// Loop for permanent items ----- permanent faculty
$contentStartRow = 7;
$currentContentRow = 7;
$cellValueOld = "";
$worksheet = $spreadsheet->getActiveSheet();
$mergeStart = 7;
$loopCount = 0;

// Loop through all the data for permanent faculty
while ($item = mysqli_fetch_array($data)) {
    if ($item['permanent'] == 1) {
        // Fill the cell with data
        $currentCellValue = $item['Name of Faculty'];
        
        $spreadsheet->getActiveSheet()
            ->setCellValue('A' . $currentContentRow, $item['Name of Faculty'])
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


    // Remove empty rows within the data range
    $dataRange = 'A6:C' . ($currentContentRow - 1); // Adjust the range as per your requirement
    foreach ($worksheet->getRowIterator(6, $currentContentRow - 1) as $row) {
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
            $currentContentRow--; // Decrement the row counter
        }
    }
$spreadsheet->getActiveSheet()->insertNewRowBefore($currentContentRow + 1, 1);
    
    $currentContentRow++;
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