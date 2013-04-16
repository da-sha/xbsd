delimiter //
drop function if exists get_student_name;
//
create function get_student_name(
	in_field_id int
)
returns varchar(100) charset utf8
begin
	declare v_field_name varchar(100);
	select name into v_field_name 
	 from teach.student 
	where userid = in_field_id;
	if(v_field_name is null) then
		set v_field_name = '未知';
	end if;
	return v_field_name;
end;
//
delimiter ;