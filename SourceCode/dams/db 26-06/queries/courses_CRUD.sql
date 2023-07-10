# create SP INSERT
DELIMITER $$

CREATE PROCEDURE `dams2`.`new_course_sp`(
	IN in_code VARCHAR(12),
	IN in_desc VARCHAR(50),
	IN in_dept VARCHAR(12),
	IN in_units INT(4),
	IN in_lec DOUBLE(4,2),
	IN in_rle DOUBLE(4,2),
	IN in_lab DOUBLE(4,2)
)
	BEGIN
		INSERT INTO `courses` (
			`course_code`,
			`course_description`, 
			`department_id`,
			`units`,
			`lec_hrs_wk`,
			`rle_hrs_wk`,
			`lab_hrs_wk`
			)
		VALUES (in_code,
			in_desc,
			(SELECT department_id
			FROM departments
			WHERE department_abbrv = in_dept),
			in_units,
			in_lec,
			in_rle,
			in_lab);
	END$$

DELIMITER ;

# run SP INSERT
CALL new_course_sp(
	`course_code`,
	`course_description`, 
	`department_abbrv`,
	`units`,
	`lec_hrs_wk`,
	`rle_hrs_wk`,
	`lab_hrs_wk`
)

# create SP UPDATE
DELIMITER $$

CREATE PROCEDURE `dams2`.`update_course_sp`(
	IN in_id INT(8),
	IN in_code VARCHAR(12),
	IN in_desc VARCHAR(50),
	IN in_dept VARCHAR(12),
	IN in_units INT(4),
	IN in_lec DOUBLE(4,2),
	IN in_rle DOUBLE(4,2),
	IN in_lab DOUBLE(4,2)
)
	BEGIN
		UPDATE `courses` 
		SET `course_code` = in_code,
			`course_description` = in_desc, 
			`department_id` = (SELECT department_id
					FROM departments
					WHERE department_abbrv = in_dept),
			`units` = in_units,
			`lec_hrs_wk` = in_lec,
			`rle_hrs_wk` = in_rle,
			`lab_hrs_wk` = in_lab
		WHERE course_id = in_id;
	END$$

DELIMITER ;

# run SP UPDATE
CALL update_course_sp(
	`course_id`,
	`course_code`,
	`course_description`, 
	`department_abbrv`,
	`units`,
	`lec_hrs_wk`,
	`rle_hrs_wk`,
	`lab_hrs_wk`
)

# create SP DELETE
DELIMITER $$

CREATE PROCEDURE `dams2`.`delete_course_sp`(
	IN in_id INT(8)
)
	BEGIN
		DELETE FROM `courses`
		WHERE course_id = in_id;
	END$$

DELIMITER ;

# run SP UPDATE
CALL delete_course_sp(
	`course_id`,
)