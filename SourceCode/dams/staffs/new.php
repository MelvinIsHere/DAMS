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

$connection =  mysqli_connect('localhost','root','','dams');
if(!$connection){
    exit("database connection error");
}
// ...

$data = mysqli_query($connection, "SELECT * FROM data_start WHERE college = 'CICS' AND campus = 'ARASOF' AND academic_year_start = '2023' AND academic_year_end = '2024' ORDER BY first_name,last_name");

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
    if ($item['type'] === 'permanent') {
        // Fill the cell with data
        $currentCellValue = $item['first_name'] . ' ' . $item['last_name'];
        $spreadsheet->getActiveSheet()
            ->setCellValue('A' . $currentContentRow, $currentCellValue)
            ->setCellValue('D' . $currentContentRow, $item['course_code'])
            ->setCellValue('E' . $currentContentRow, $item['section'])
            ->setCellValue('F' . $currentContentRow, $item['no_of_students'])
            ->setCellValue('G' . $currentContentRow, $item['total_units'])
            ->setCellValue('H' . $currentContentRow, $item['lec_hrs_wk'])
            ->setCellValue('I' . $currentContentRow, $item['rle_hrs_wk'])
            ->setCellValue('J' . $currentContentRow, $item['lab_hrs_wk'])
            ->setCellValue('K' . $currentContentRow, $item['total_hrs_wk'])
            ->setCellValue('L' . $currentContentRow, $item['course_title'])
            ->setCellValue('N' . $currentContentRow, $item['regular_hrs'])
            ->setCellValue('O' . $currentContentRow, $item['overload'])
            ->setCellValue('P' . $currentContentRow, $item['no_of_prep']);
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
    $counter = 6;
    while($counter != $currentContentRow){


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
    if ($item['type'] === 'temporary') {
        $spreadsheet->getActiveSheet()->insertNewRowBefore($currentContentRow_temp + 1, 1);
        // Fill the cell with data
        $currentCellValue = $item['first_name'] . ' ' . $item['last_name'];
        $spreadsheet->getActiveSheet()
            ->setCellValue('A' . $currentContentRow_temp, $currentCellValue)
            ->setCellValue('D'.$currentContentRow_temp,$item['course_code'])
            ->setCellValue('E'.$currentContentRow_temp,$item['section'])
            ->setCellValue('F'.$currentContentRow_temp,$item['no_of_students'])
            ->setCellValue('G'.$currentContentRow_temp,$item['total_units'])
            ->setCellValue('H'.$currentContentRow_temp,$item['lec_hrs_wk'])
            ->setCellValue('I'.$currentContentRow_temp,$item['rle_hrs_wk'])
            ->setCellValue('J'.$currentContentRow_temp,$item['lab_hrs_wk'])
            ->setCellValue('K'.$currentContentRow_temp,$item['total_hrs_wk'])
            ->setCellValue('L'.$currentContentRow_temp,$item['course_title'])
            ->setCellValue('N'.$currentContentRow_temp,$item['regular_hrs'])
            ->setCellValue('O'.$currentContentRow_temp,$item['overload'])
            ->setCellValue('P'.$currentContentRow_temp,$item['no_of_prep']);
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
    if ($item['type'] === 'part time') {
        $spreadsheet->getActiveSheet()->insertNewRowBefore($currentContentRow_part + 1, 1);
        // Fill the cell with data
        $spreadsheet->getActiveSheet()
            ->setCellValue('A' . $currentContentRow_part, $item['first_name'] . ' ' . $item['last_name'])
            ->setCellValue('D'.$currentContentRow_part,$item['course_code'])
            ->setCellValue('E'.$currentContentRow_part,$item['section'])
            ->setCellValue('F'.$currentContentRow_part,$item['no_of_students'])
            ->setCellValue('G'.$currentContentRow_part,$item['total_units'])
            ->setCellValue('H'.$currentContentRow_part,$item['lec_hrs_wk'])
            ->setCellValue('I'.$currentContentRow_part,$item['rle_hrs_wk'])
            ->setCellValue('J'.$currentContentRow_part,$item['lab_hrs_wk'])
            ->setCellValue('K'.$currentContentRow_part,$item['total_hrs_wk'])
            ->setCellValue('L'.$currentContentRow_part,$item['course_title'])
            ->setCellValue('N'.$currentContentRow_part,$item['regular_hrs'])
            ->setCellValue('O'.$currentContentRow_part,$item['overload'])
            ->setCellValue('P'.$currentContentRow_part,$item['no_of_prep']);
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