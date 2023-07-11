SELECT
getFullName_surnameFirst(fc.firstname,fc.middlename,fc.lastname,fc.`suffix`) "Name of Faculty",
cs.course_code "Course Code",
getProg_sec(pr.`program_abbrv`,sc.`section_name`) "Section",
sc.no_of_students "No. of Students",
cs.`units` "Total Units",
cs.`lec_hrs_wk` "Lec. hrs/wk",
cs.`lab_hrs_wk` "Lab. hrs/wk",
SUM(cs.`lec_hrs_wk`+cs.`lab_hrs_wk`) "Total hrs/wk",
cs.`course_description` "Course Description"
FROM
faculty_loadings fl
LEFT JOIN faculties fc ON fl.`faculty_id`=fc.`faculty_id`
LEFT JOIN courses cs ON fl.`course_id`=cs.`course_id`
LEFT JOIN sections sc ON fl.`section_id`=sc.`section_id`
LEFT JOIN programs pr ON sc.`program_id`=pr.`program_id`
GROUP BY fl.`fac_load_id`

GROUP BY cs.`course_id`

SELECT
getProg_sec(pr.`program_abbrv`,sc.`section_name`) "Section"
FROM sections sc
LEFT JOIN programs pr ON pr.`program_id`=sc.`program_id`

SELECT * FROM vw_facultyloadings;