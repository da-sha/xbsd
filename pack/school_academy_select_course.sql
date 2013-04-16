DELIMITER $$

/*注意：学校平台课程中的选修课将不会更新teach_class表*/
DROP PROCEDURE IF EXISTS `tsmsv3`.`arrange_school_select_course`$$

CREATE DEFINER=`root`@`%` PROCEDURE `arrange_school_select_course`(t_year INT,quarter INT)

    BEGIN
		declare fetchSeqOk boolean;
		declare _courseid int(10);
		declare _classid int(10);
		declare _teachid int(10);
		declare _coursename varchar(32);
		declare b_inserted boolean;
		
		declare fetchSeqCursor cursor for
			SELECT 	course.courseid as courseid	/*授课状态为待审*/
			FROM 	course
			WHERE   course.cou_type != 1			/*不是必修课*/
				and course.cou_category = 3		/*学校平台课*/
				and course.level = 1			/*本科生*/
				;
		
		declare continue handler for NOT FOUND set fetchSeqOk = false;
		set fetchSeqOk = true;
		open fetchSeqCursor;
			fetch_loop:
				WHILE fetchSeqOk DO
					fetch fetchSeqCursor into _courseid;
					
					IF fetchSeqOk = false THEN
						leave fetch_loop;
					END IF;
				
					set _teachid = 0;
					
					/*检查是否已插入teach_task表中*/
					select teachid into _teachid
					from 	teach_task
					where 	courseid = _courseid;

					/*表面没有插入*/
					IF _teachid = 0 THEN
						/*向teach_task表中插入数据*/
						INSERT INTO teach_task(courseid,teach_year,teach_quarter,status)
							values (_courseid,t_year,quarter,1);		/*授课状态为待审*/
					END IF;
				END WHILE;
		close fetchSeqCursor;

    END$$

DELIMITER ;