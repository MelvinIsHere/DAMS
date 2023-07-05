# create SP INSERT
DELIMITER $$

CREATE PROCEDURE `dams2`.`new_semester_sp`(
	IN in_desc VARCHAR(50)
)
	BEGIN
		INSERT INTO `semesters` (
			`sem_description`
			)
		VALUES (in_desc
		);
	END$$

DELIMITER ;

# run SP INSERT
CALL new_semester_sp(
	`semester_desc`
)

# create SP UPDATE
DELIMITER $$

CREATE PROCEDURE `dams2`.`update_semester_sp`(
	IN in_id INT(8),
	IN in_desc VARCHAR(50)
)
	BEGIN
		UPDATE `semesters` 
		SET 
			`sem_description` = in_desc
		WHERE semester_id = in_id;
	END$$

DELIMITER ;

# run SP UPDATE
CALL update_semester_sp(
	`sem_id`,
	`semester_desc`
)

# create SP DELETE
DELIMITER $$

CREATE PROCEDURE `dams2`.`delete_semester_sp`(
	IN in_id INT(8)
)
	BEGIN
		DELETE FROM `semesters`
		WHERE semester_id = in_id;
	END$$

DELIMITER ;

# run SP UPDATE
CALL delete_semester_sp(
	`semester_id`,
)