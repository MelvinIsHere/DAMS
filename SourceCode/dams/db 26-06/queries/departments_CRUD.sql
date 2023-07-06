# create SP INSERT
DELIMITER $$

CREATE PROCEDURE `dams2`.`new_department_sp`(
	IN in_name VARCHAR(50),
	IN in_abbrv VARCHAR(12)
)
	BEGIN
		INSERT INTO `departments` (
			`department_name`,
			`department_abbrv`
			)
		VALUES (in_name,
			in_abbrv
		);
	END$$

DELIMITER ;

# run SP INSERT
CALL new_department_sp(
	`department_name`,
	`department_abbrv`
)

# create SP UPDATE
DELIMITER $$

CREATE PROCEDURE `dams2`.`update_department_sp`(
	IN in_id INT(8),
	IN in_name VARCHAR(50),
	IN in_abbrv VARCHAR(12)
)
	BEGIN
		UPDATE `departments` 
		SET 
			`department_name` = in_name,
			`department_abbrv` = in_abbrv
		WHERE department_id = in_id;
	END$$

DELIMITER ;

# run SP UPDATE
CALL update_department_sp(
	`department_name`,
	`department_abbrv`
)

# create SP DELETE
DELIMITER $$

CREATE PROCEDURE `dams2`.`delete_department_sp`(
	IN in_id INT(8)
)
	BEGIN
		DELETE FROM `departments`
		WHERE department_id = in_id;
	END$$

DELIMITER ;

# run SP UPDATE
CALL delete_department_sp(
	`department_id`,
)