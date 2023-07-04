# create SP INSERT
DELIMITER $$

CREATE PROCEDURE `dams2`.`new_academicYear_sp`(
	IN in_desc VARCHAR(9)
)
	BEGIN
		INSERT INTO `academic_year` (
			`acad_year`
			)
		VALUES (in_desc
		);
	END$$

DELIMITER ;

# run SP INSERT
CALL new_academicYear_sp(
	`academic_year`
)

# create SP UPDATE
DELIMITER $$

CREATE PROCEDURE `dams2`.`update_academicYear_sp`(
	IN in_id INT(8),
	IN in_desc VARCHAR(9)
)
	BEGIN
		UPDATE `academic_year` 
		SET 
			`acad_year` = in_desc
		WHERE acad_year_id = in_id;
	END$$

DELIMITER ;

# run SP UPDATE
CALL update_academicYear_sp(
	`acad_year_id`,
	`academic_year`
)

# create SP DELETE
DELIMITER $$

CREATE PROCEDURE `dams2`.`delete_academicYear_sp`(
	IN in_id INT(8)
)
	BEGIN
		DELETE FROM `academic_year`
		WHERE acad_year_id = in_id;
	END$$

DELIMITER ;

# run SP UPDATE
CALL delete_academicYear_sp(
	`acad_year_id`,
)