# INSERT NEW
INSERT INTO faculties(
	`firstname`,
	`lastname`,
	`middlename`,
	`department_id`,
	`is_permanent`,
	`is_guest`,
	`is_partTime`
)
VALUES( 'Noelyn',
	'De Jesus',
	'M.',
	(SELECT department_id
	FROM departments
	WHERE department_abbrv='CICS'),
	1,
	0,
	0
);

# Read
SELECT *
FROM faculties fc
LEFT JOIN departments dp ON dp.`department_id` = fc.`department_id`

# 