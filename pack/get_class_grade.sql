DELIMITER //
DROP FUNCTION IF EXISTS get_class_grade;
//
CREATE FUNCTION get_class_grade(
	in_field_id INT
)
RETURNS VARCHAR(100) CHARSET utf8
BEGIN
	DECLARE v_field_name VARCHAR(100);
	SELECT register_date INTO v_field_name 
	FROM class
	WHERE classid = in_field_id;
	IF(v_field_name IS NULL) THEN
		SET v_field_name = '0000';
	END IF;
	RETURN v_field_name;
END;
//
DELIMITER ;