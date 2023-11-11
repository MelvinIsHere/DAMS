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
GROUP BY c.`course_code`
ORDER BY t1.time_id;
";

$monday_plot = mysqli_query($conn, $monday_plot_query);









// Loop for permanent items ----- permanent faculty
// Loop for permanent items ----- permanent faculty
$contentStartRow = 6;
$currentContentRow = 6;
$cellValueOld = "";
$worksheet = $spreadsheet->getActiveSheet();
$mergeStart = 6;
$loopCount = 0;

// Loop through all the data for permanent faculty
while ($item = mysqli_fetch_array($monday_plot)) {
        $cellValueCurrent = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $currentContentRow)->getCalculatedValue();
        if($item['class_start'] == $cellValueCurrent){
        	 // Fill the cell with data
           $first_name_first_letter = explode(" ", $item['firstname']);
    		$firstLettersFirstName = "";
    		foreach ($first_name_first_letter as $part) {
		        $firstLettersFirstName .= $part[0] . ". ";
		    }
		        $currentCellValue = $item['course_code'];
		        $facultyname = "\n" . $firstLettersFirstName;
		        $spreadsheet->getActiveSheet()
		            ->setCellValue('B' . $currentContentRow,$item['course_code']);
		            $currentContentRow++;
            
        }else{
        	$currentContentRow++;
        }	
                         
        
    }



//set the header first, so the result will be treated as an xlsx file.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

//make it an attachment so we can define filename
header('Content-Disposition: attachment;filename="BatStateU-FO-COL-29_Class.xlsx"');

//create IOFactory object
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
//save into php output
$writer->save('php://output');
?>