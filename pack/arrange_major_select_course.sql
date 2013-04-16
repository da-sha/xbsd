DELIMITER $$

/*将某年某学期的课程加入到teach_task，并更新teach_class*/
DROP PROCEDURE IF EXISTS `tsmsv3`.`arrange_major_select_course`$$

CREATE DEFINER=`root`@`%` PROCEDURE `arrange_major_select_course`(t_year INT,quarter INT)

    BEGIN
		declare fetchSeqOk boolean;
		declare _courseid int(10);
		declare _classid int(10);
		declare _teachid int(10);
		declare _coursename varchar(32);
		declare b_inserted boolean;
		
		declare fetchSeqCursor cursor for
			SELECT 	course.courseid as courseid,class.classid as classid,course.name as coursename		/*授课状态为待审*/
			FROM 	course,class
			WHERE   course.cou_type != 1			/*不是必修课*/
				and course.cou_category = 1		/*专业平台课*/
				and course.level = 1			/*本科生*/
				and course.majorid = class.majorid	
				and class.register_date = course.grade
				and course.semester = (t_year - course.grade) * 2 + quarter - 1
				order by course.majorid
				;
		
		declare continue handler for NOT FOUND set fetchSeqOk = false;
		set fetchSeqOk = true;
		open fetchSeqCursor;
			fetch_loop:
				WHILE fetchSeqOk DO
					fetch fetchSeqCursor into _courseid, _classid,_coursename;
					
					IF fetchSeqOk = false THEN
						leave fetch_loop;
					END IF;
					
					set b_inserted = false;
					
					set _teachid = 0;
					
					/*检查是否已插入teach_task表中*/
					select teach_task.teachid into _teachid
					from 	teach_task,course
					where 	teach_task.courseid = course.courseid
							and course.cou_type != 1			/*不是必修课*/
							and course.cou_category = 1		/*专业平台课*/
							and course.level = 1			/*本科生*/
							and course.name = _coursename
							and teach_task.teach_year = t_year;
					
					/*表面没有插入*/
					IF _teachid = 0 THEN
						/*向teach_task表中插入数据*/
						INSERT INTO teach_task(courseid,teach_year,teach_quarter,status,finish_status)
							values (_courseid,t_year,quarter,1,3);		/*授课状态为待审*/
						/*保存最后插入的teachid*/
						set _teachid = LAST_INSERT_ID();
					END IF;
					
					/*检查teach_class表中是否已被插入*/
					select true into b_inserted
					from 	teach_class,teach_task
					where 	teach_task.teachid = teach_class.teachid
						and teach_task.teachid = _teachid
						and teach_class.classid = _classid;
						
					/*当上一条执行过后发现没有数据，则重新标志*/
					set fetchSeqOk = true;
					IF b_inserted = false THEN
						INSERT INTO teach_class(teachid,classid)
							values (_teachid,_classid);		/*授课状态为待审*/
					END IF;
				END WHILE;
		close fetchSeqCursor;

    END$$

DELIMITER ;