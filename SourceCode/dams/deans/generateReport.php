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

$connection =  mysqli_connect('localhost','root','','dams2');
if(!$connection){
    exit("database connection error");
}
// ...

$data = mysqli_query($connection, "


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
$contentStartRow = 7;
$currentContentRow = 7;
$cellValueOld = "";
$worksheet = $spreadsheet->getActiveSheet();
// Loop through all the data for permanent faculty
while ($item = mysqli_fetch_array($data)) {
    if ($item['permanent'] == 1) {
        // Fill the cell with data
        $currentCellValue = $item['Name of Faculty'];
        $spreadsheet->getActiveSheet()
        ->setCellValue('A' . $currentContentRow, $item['Name of Faculty'])
            ->setCellValue('D'.$currentContentRow,$item['Course Code'])
            ->setCellValue('E'.$currentContentRow,$item['Section'])
            ->setCellValue('F'.$currentContentRow,$item['No. of Students'])
            ->setCellValue('G'.$currentContentRow,$item['Total Units'])
            ->setCellValue('H'.$currentContentRow,$item['Lec. hrs/wk'])
            ->setCellValue('I'.$currentContentRow,$item['Rle. hrs/wk'])
            ->setCellValue('J'.$currentContentRow,$item['Lab. hrs/wk'])
            ->setCellValue('K'.$currentContentRow,$item['Total hrs/wk'])
            ->setCellValue('L'.$currentContentRow,$item['Course Description']);

            // ... set other cell values




      
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

            // Increment value
            $currentContentRow++;
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
                 $spreadsheet->getActiveSheet()
                    ->setCellValue('Q' . $row, " same");
                $row++;
                $rowCount++;

                }   
              else{
                    $spreadsheet->getActiveSheet()->unmergeCells('A'.$cRow.':C'.$cRow);
                    
                   $spreadsheet->getActiveSheet()->mergeCells('A'.$row.':C'.$cRow);
                   $spreadsheet->getActiveSheet()->mergeCells('A'.$row.':C'.$cRow);

                   // Get style for the merged cells
                   $spreadsheet->getActiveSheet()->getStyle('A'.$cRow)->getAlignment()->setHorizontal('center');

                    // Set vertical alignment to center
                   $cRow = $cRow + $rowCount;
                   $row++;
                   $rowCount = 1;
                }
        } 
        else if ($cellValueCurrent ==  $nextvalue) {
                 $spreadsheet->getActiveSheet()
        ->setCellValue('Q' . $row, " same");
            $row++;
            $rowCount++;
             
        }

        else{
        //      $spreadsheet->getActiveSheet()
        // ->setCellValue('Q' . $row, "not the same");
         
           $spreadsheet->getActiveSheet()->mergeCells('A'.$row.':C'.$cRow);
           $spreadsheet->getActiveSheet()->getStyle('A'.$cRow)->getAlignment()->setHorizontal('center');
           $cRow = $cRow + $rowCount;
           $row++;
           $rowCount = 1;
            

        }
       


      


    

}



// Remove rows for the permanent faculty
$spreadsheet->getActiveSheet()->removeRow($currentContentRow, 3);




//here are just the same process------ just look in the $item type---thats the type of faculty that are being plotted

//we take the value of the current row when we are plotting the permanent and passing it to a new variable for temporaty type

$contentStartRow_temp = $currentContentRow + 1;
$currentContentRow_temp = $currentContentRow + 1;

// Reset the result set pointer
mysqli_data_seek($data, 0);

// Loop for permanent items
while ($item = mysqli_fetch_array($data)) {
    if ($item['Guest'] == 1) {
        $spreadsheet->getActiveSheet()->insertNewRowBefore($currentContentRow_temp + 1, 1);
        // Fill the cell with data
        $currentCellValue = $item['Name of Faculty'];
        $spreadsheet->getActiveSheet()
            ->setCellValue('A' . $currentContentRow_temp,$item['Name of Faculty'])
            ->setCellValue('D'.$currentContentRow_temp,$item['Course Code'])
            ->setCellValue('E'.$currentContentRow_temp,$item['Section'])
            ->setCellValue('F'.$currentContentRow_temp,$item['No. of Students'])
            ->setCellValue('G'.$currentContentRow_temp,$item['Total Units'])
            ->setCellValue('H'.$currentContentRow_temp,$item['Lec. hrs/wk'])
            ->setCellValue('I'.$currentContentRow_temp,$item['Lab. hrs/wk'])
            ->setCellValue('k'.$currentContentRow_temp,$item['Total hrs/wk'])
            ->setCellValue('L'.$currentContentRow_temp,$item['Course Description']);
            // ... set other cell values

        $currentContentRow_temp++;
    }
    // Remove empty rows within the data range
    $dataRange = 'A'.$contentStartRow_temp.':C' . ($currentContentRow_temp - 1); // Adjust the range as per your requirement
    foreach ($worksheet->getRowIterator(6, $currentContentRow_temp - 1) as $row) {
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

}

$counter = $contentStartRow_temp;
    while($counter <= $currentContentRow_temp){


        // Update the old cell value
        $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $counter)->getCalculatedValue();
        // $cellValueNew = $currentCellValue;
        if ($cellValueCurrent ===  $cellValueOld) {
            $counters = $counter -1;
            $spreadsheet->getActiveSheet()->mergeCells('A'.$counter.':C'.$counters, Worksheet::MERGE_CELL_CONTENT_HIDE);       } 

        else{
            $spreadsheet->getActiveSheet()->mergeCells('A'.$counter.':C'.($counter));

        }



        // Update the old cell value
        $cellValueOld = $cellValueCurrent;
   


    $counter++;
}
// //remove row for the temporaty faculty
$spreadsheet->getActiveSheet()->removeRow($currentContentRow_temp,3);





$contentStartRow_part = $currentContentRow_temp + 1;
$currentContentRow_part = $currentContentRow_temp + 1;

// Reset the result set pointer
mysqli_data_seek($data, 0);

// Loop for permanent items
while ($item = mysqli_fetch_array($data)) {
    if ($item['part_time'] == 1) {
        $spreadsheet->getActiveSheet()->insertNewRowBefore($currentContentRow_part + 1, 1);
        // Fill the cell with data
        $spreadsheet->getActiveSheet()
            ->setCellValue('A' . $currentContentRow_part, $item['first_name'] . ' ' . $item['last_name'])
            ->setCellValue('A' . $currentContentRow_part, $item['Name of Faculty'])
            ->setCellValue('D'.$currentContentRow_part,$item['Course Code'])
            ->setCellValue('E'.$currentContentRow_part,$item['Section'])
            ->setCellValue('F'.$currentContentRow_part,$item['No. of Students'])
            ->setCellValue('G'.$currentContentRow_part,$item['Total Units'])
            ->setCellValue('H'.$currentContentRow_part,$item['Lec. hrs/wk'])
            ->setCellValue('I'.$currentContentRow_part,$item['rle_hrs_wk'])
            ->setCellValue('J'.$currentContentRow_part,$item['Lab. hrs/wk'])
            ->setCellValue('K'.$currentContentRow_part,$item['Total hrs/wk'])
            ->setCellValue('L'.$currentContentRow_part,$item['Course Description']);
            // ... set other cell values

        $currentContentRow_part++;
    }
     // Remove empty rows within the data range
    $dataRange = 'A'.$currentContentRow_part.':C' . ($currentContentRow_part - 1); // Adjust the range as per your requirement
    foreach ($worksheet->getRowIterator(6, $currentContentRow_part - 1) as $row) {
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

}

$counter = $contentStartRow_part;
    while($counter <= $currentContentRow_part){


        // Update the old cell value
        $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $counter)->getCalculatedValue();
        // $cellValueNew = $currentCellValue;
        if ($cellValueCurrent ===  $cellValueOld) {
            $counters = $counter -1;
            $spreadsheet->getActiveSheet()->mergeCells('A'.$counter.':C'.$counters, Worksheet::MERGE_CELL_CONTENT_HIDE);       } 

        else{
            $spreadsheet->getActiveSheet()->mergeCells('A'.$counter.':C'.($counter));

        }



        // Update the old cell value
        $cellValueOld = $cellValueCurrent;
   


    $counter++;
}
//remove row for the part time faculty
$spreadsheet->getActiveSheet()->removeRow($currentContentRow_part,4);
// ...




//set the header first, so the result will be treated as an xlsx file.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

//make it an attachment so we can define filename
header('Content-Disposition: attachment;filename="result.xlsx"');

//create IOFactory object
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
//save into php output
$writer->save('php://output');



?>