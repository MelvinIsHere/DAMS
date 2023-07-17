<?php
include "config.php";
// Fetch data from the database
         $sql = "SELECT
fl.fac_load_id AS 'Loading Id',
getFullName_surnameFirst(fc.firstname,fc.middlename,fc.lastname,fc.`suffix`) 'Name of Faculty',
cs.course_code 'Course Code',
getProg_sec(pr.`program_abbrv`,sc.`section_name`) 'Section',
sc.no_of_students 'No. of Students',
cs.`units` 'Total Units',
cs.`lec_hrs_wk` 'Lec. hrs/wk',
cs.`lab_hrs_wk` 'Lab. hrs/wk',
SUM(cs.`lec_hrs_wk`+cs.`lab_hrs_wk`) 'Total hrs/wk',
cs.`course_description` 'Course Description'

FROM
faculty_loadings fl
LEFT JOIN faculties fc ON fl.`faculty_id`=fc.`faculty_id`
LEFT JOIN courses cs ON fl.`course_id`=cs.`course_id`
LEFT JOIN sections sc ON fl.`section_id`=sc.`section_id`
LEFT JOIN programs pr ON sc.`program_id`=pr.`program_id`
LEFT JOIN departments dp ON dp.`department_id`=fl.`dept_id`
WHERE fl.`dept_id` = 8 # insert dept_id
GROUP BY fl.`fac_load_id`
";
$result = mysqli_query($conn, $sql);

// Prepare an array to hold the fetched data
$data = array();

// Loop through the result set and fetch the data
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Close the database connection
mysqli_close($conn);

// Return the fetched data as JSON
echo json_encode($data);
?>
