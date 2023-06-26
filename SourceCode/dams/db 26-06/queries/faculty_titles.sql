SELECT
CONCAT(fc.lastname,',',fc.firstname,' ',fc.middlename) "Name of Faculty",
GROUP_CONCAT(title_description)
FROM faculty_titles ft
LEFT JOIN titles tt ON ft.`title_id`=tt.`title_id`
LEFT JOIN faculties fc ON ft.`faculty_id`=fc.`faculty_id`

SELECT
CASE tt.`title_description`
	WHEN 'Dean' THEN CONCAT(tt.`title_description`,' ',dp.department_abbrv)
	ELSE tt.`title_description`
END "Titles"
FROM faculty_titles ft
LEFT JOIN titles tt ON ft.`title_id`=tt.`title_id`
LEFT JOIN faculties fc ON ft.`faculty_id`=fc.`faculty_id`
LEFT JOIN departments dp ON fc.`department_id`=dp.department_id
WHERE fc.`faculty_id` = 1 # 1 is ID of faculty