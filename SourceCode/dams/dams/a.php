<?php 
include "php/config.php";
include "php/class_schedule_functions.php";

$monday_plot = mysqli_query($conn, "

SELECT
cs.class_sched_id,
f.firstname,
f.lastname,
f.middlename,
f.suffix,
d.department_name,
p.program_abbrv,
s.section_name,
s.no_of_students,
c.course_code,
r.room_name,
ay.acad_year,
cs.day,
t.start,
t2.end,
t.text_output,
sm.status,
t.text_value


    

FROM class_schedule cs
LEFT JOIN faculties f ON f.faculty_id = cs.faculty_id
LEFT JOIN departments d ON d.department_id = cs.dept_id
LEFT JOIN sections s ON s.section_id = cs.section_id
LEFT JOIN programs p ON p.program_id = s.program_id
LEFT JOIN courses c ON c.course_id = cs.course_id
LEFT JOIN rooms r ON r.room_id = cs.room_id
LEFT JOIN academic_year ay ON ay.acad_year_id = cs.academic_year_id
LEFT JOIN `time` t ON t.time_id = cs.time_start_id 
LEFT JOIN `time` t2 ON t2.time_id = cs.time_end_id 
LEFT JOIN semesters sm ON sm.semester_id = cs.sem_id

WHERE cs.dept_id = 8 AND sm.status = 'ACTIVE' AND `day` = 'Monday'


");

if(mysqli_num_rows($monday_plot) > 0){

$contentStartRow = 6;
$currentContentRow = 6;
$cellValueOld = "";

$mergeStart = 6;
$loopCount = 0;

// Loop through all the data for permanent faculty
while ($item = mysqli_fetch_array($monday_plot)) {
    if ($item['day'] == "Monday") {
        $time_start = $item['start'];
        $text_value = get_text_output_time_start($time_start);
        

        echo "time start = $time_start   ";
        echo "text_value = $text_value   \n";

        
    }


}


}

?>
