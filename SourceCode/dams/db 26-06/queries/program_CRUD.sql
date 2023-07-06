# create SP INSERT
DELIMITER $$

CREATE PROCEDURE `dams2`.`new_program_sp`(
	IN in_title VARCHAR(50),
	IN in_dept VARCHAR(12),
	IN in_abbrv VARCHAR(12)
)
	BEGIN
		INSERT INTO `programs` (
			`program_name`,
			`program_abbrv`
			)
		VALUES (in_title,
			(SELECT department_id
			FROM departments
			WHERE department_abbrv = in_dept),
			in_abbrv
		);
	END$$

DELIMITER ;

# run SP INSERT
CALL new_program_sp(
	`program_title`,
	`department_abbrv`,
	`program_abbrv`
)

# create SP UPDATE
DELIMITER $$

CREATE PROCEDURE `dams2`.`update_program_sp`(
	IN in_id INT(8),
	IN in_title VARCHAR(50),
	IN in_dept VARCHAR(12),
	IN in_abbrv VARCHAR(12)
)
	BEGIN
		UPDATE `programs` 
		SET 
			`program_name` = in_title,
			`department_id` = (SELECT department_id
					FROM departments
					WHERE department_abbrv = in_dept),
			`program_abbrv` = in_abbrv
		WHERE program_id = in_id;
	END$$

DELIMITER ;

# run SP UPDATE
CALL update_program_sp(
	`program_id`,
	`program_title`,
	`department_abbrv`,
	`program_abbrv`
)

# create SP DELETE
DELIMITER $$

CREATE PROCEDURE `dams2`.`delete_program_sp`(
	IN in_id INT(8)
)
	BEGIN
		DELETE FROM `programs`
		WHERE program_id = in_id;
	END$$

DELIMITER ;

# run SP UPDATE
CALL delete_program_sp(
	`program_id`,
)