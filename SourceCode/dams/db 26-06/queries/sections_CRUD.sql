# create SP INSERT
DELIMITER $$

CREATE PROCEDURE `dams2`.`new_section_sp`(
	IN in_course VARCHAR(12),
	IN in_program VARCHAR(12),
	IN in_name VARCHAR(12),
	IN in_sem VARCHAR(12),
	IN in_stdnts INT(4)
)
	BEGIN
		INSERT INTO `sections` (
			`course_id`,
			`program_id`,
			`section_name`,
			`semester_id`,
			`no_of_students`
			)
		VALUES ((SELECT course_id
			FROM courses
			WHERE course_abbrv = in_course),
			(SELECT program_id
			FROM programs
			WHERE program_abbrv = in_program),
			in_name,
			(SELECT semester_id
			FROM semesters
			WHERE sem_description = in_sem),
			in_stdnts
		);
	END$$

DELIMITER ;

# run SP INSERT
CALL new_section_sp(
	`course_abbrv`,
	`program_abbrv`,
	`section_name`,
	`semester`,
	`no_of_students`
)

# create SP UPDATE
DELIMITER $$

CREATE PROCEDURE `dams2`.`update_section_sp`(
	IN in_id INT(8),
	IN in_program VARCHAR(12),
	IN in_name VARCHAR(12),
	IN in_sem VARCHAR(12),
	IN in_stdnts INT(4)
)
	BEGIN
		UPDATE `sections` 
		SET 
			`course_id` = (SELECT course_id
				FROM courses
				WHERE course_abbrv = in_course),
			`program_id` = (SELECT program_id
				FROM programs
				WHERE program_abbrv = in_program),
			`section_name` = in_name,
			`semester_id` = (SELECT semester_id
				FROM semesters
				WHERE sem_description = in_sem),
			`no_of_students` = in_stdnts
		WHERE section_id = in_id;
	END$$

DELIMITER ;

# run SP UPDATE
CALL update_section_sp(
	`section_id`,
	`course_abbrv`,
	`program_abbrv`,
	`section_name`,
	`semester`,
	`no_of_students`
)

# create SP DELETE
DELIMITER $$

CREATE PROCEDURE `dams2`.`delete_section_sp`(
	IN in_id INT(8)
)
	BEGIN
		DELETE FROM `sections`
		WHERE section_id = in_id;
	END$$

DELIMITER ;

# run SP UPDATE
CALL delete_section_sp(
	`section_id`,
)