DELIMITER $$

DROP FUNCTION IF EXISTS `tsmsv3`.`get_teach_workload`$$

CREATE DEFINER=`root`@`%` FUNCTION `get_teach_workload`(
	in_teacherid int,
	in_year int,
	in_quarter int
) RETURNS varchar(100) CHARSET utf8
begin
	declare v_workload varchar(100);
	select sum(course.totaltime) into v_workload 
	from (`course` join `teach_task`)
	where (`course`.`courseid` = `teach_task`.`courseid`
		and `teach_task`.`teach_year` = in_year
		and `teach_task`.`teach_quarter` = in_quarter
		and `teach_task`.`userid` = in_teacherid
		)
	group by `teach_task`.`userid`;
	if(v_workload is null) then
		set v_workload = 'δ֪';
	end if;
	return v_workload;
end$$

DELIMITER ;