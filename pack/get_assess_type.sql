DELIMITER $$

USE `tsms`$$

DROP FUNCTION IF EXISTS `get_assess_type`$$

CREATE DEFINER=``@`` FUNCTION `get_assess_type`(
	in_field_id INT
) RETURNS VARCHAR(100) CHARSET utf8
BEGIN
	DECLARE v_field_name VARCHAR(100);
	SELECT get_field_dict_name("assessment","type",TYPE) INTO v_field_name 
	 FROM assessment
	WHERE assessmentid = in_field_id;
	IF(v_field_name IS NULL) THEN
		SET v_field_name = '未知';
	END IF;
	RETURN v_field_name;
END$$

DELIMITER ;