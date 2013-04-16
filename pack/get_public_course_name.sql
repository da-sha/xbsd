DELIMITER $$

DROP FUNCTION IF EXISTS `tsmsv3`.`get_course_name`$$

CREATE FUNCTION `get_public_course_name`(
	in_field_id int
) RETURNS varchar(100) CHARSET utf8
begin
	declare v_field_name varchar(100);
	select name into v_field_name 
	 from public_course 
	where id = in_field_id;
	if(v_field_name is null) then
		set v_field_name = '未知';
	end if;
	return v_field_name;
end$$

DELIMITER ;