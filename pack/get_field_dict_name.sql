/*
file:get_field_dict_name.sql
author:xiaoqing
create:2010/08/31
remark:����ֵ䷭��
*/
DELIMITER //
DROP FUNCTION IF EXISTS get_field_dict_name;
//
CREATE FUNCTION get_field_dict_name(
	in_table_name VARCHAR(50),
	in_field_name VARCHAR(50),
	in_field_value VARCHAR(50)
)
RETURNS VARCHAR(100) CHARSET utf8
BEGIN
	DECLARE v_field_mean VARCHAR(100);
	SELECT fd_mean INTO v_field_mean 
	 FROM data_dict 
	WHERE tb_name = in_table_name 
	  AND fd_name = in_field_name 
	  AND fd_value = in_field_value;
	IF(v_field_mean IS NULL) THEN
		SET v_field_mean = '未知';
	END IF;
	RETURN v_field_mean;
END;
//
DELIMITER ;