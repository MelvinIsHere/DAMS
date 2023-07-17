DELIMITER $$

CREATE PROCEDURE `dams2`.`new_fac_titles`(
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
			department_id`,
			`units`,
			`lec_hrs_wk`,
			`rle_hrs_wk`,
			`lab_hrs_wk`
			)
		values (in_code,
			in_desc,
			(select department_id
			from departments
			where department_abbrv = in_dept),
			in_units,
			in_lec,
			in_rle,
			in_lab);
	END$$

DELIMITER ;