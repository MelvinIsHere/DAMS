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

$sem_id = $_SESSION['semester_id'];
$dept_id = $_GET['dept_id'];
$dept_abbrv = $_GET['dept_abbrv'];





$active_semester = mysqli_query($conn, "SELECT sem_description FROM semesters WHERE semester_id = '$sem_id'");
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


//set the header first, so the result will be treated as an xlsx file.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

//make it an attachment so we can define filename
header('Content-Disposition: attachment;filename="faculty loading.xlsx"');

//create IOFactory object
$writer = IOFactory::createWriter($spreadsheet, 'Xls');
//save into php output
$writer->save('php://output');



?>