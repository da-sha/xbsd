delimiter //
drop function if exists get_thesis_name;
//
create function get_thesis_name(
	in_field_id int
)
returns varchar(100) charset utf8
begin
	declare v_field_name varchar(100);
	select name into v_field_name 
	 from tsms.thesis_title 
	where thesisid = in_field_id;
	if(v_field_name is null) then
		set v_field_name = '未知';
	end if;
	return v_field_name;
end
//
delimiter ;