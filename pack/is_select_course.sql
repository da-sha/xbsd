DELIMITER $$

/*
 * 判断学生是否选修了一门课程
 */
DROP FUNCTION IF EXISTS `tsmsv3`.`is_select_course`$$

CREATE DEFINER=`root`@`%` FUNCTION `is_select_course`(
	userid INT,
	teachid int
) RETURNS boolean
BEGIN
	DECLARE b_selected boolean;
	set b_selected = false;
	
	SELECT true INTO b_selected 
	FROM select_course
	WHERE select_course.userid = userid
			and select_course.teachid = teachid;
	
	RETURN b_selected;
END$$

DELIMITER ;