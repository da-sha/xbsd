DELIMITER $$

DROP VIEW IF EXISTS `tsmsv3`.`undergraduate_major_course`$$

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `undergraduate_major_course` AS (select `major_course`.`majorid` AS `majorid`,`major_course`.`semester` AS `semester`,`major_course`.`grade` AS `grade`,`course`.`courseid` AS `courseid`,`course`.`name` AS `name`,`course`.`level` AS `level`,`course`.`property` AS `property`,`course`.`cou_type` AS `cou_type`,`course`.`cou_category` AS `cou_category`,`course`.`exam_type` AS `exam_type`,`course`.`planweektime` AS `planweektime`,`course`.`expweektime` AS `expweektime`,`course`.`expcourseid` AS `expcourseid`,`course`.`week` AS `week`,`course`.`totaltime` AS `totaltime`,`course`.`credit` AS `credit`,`course`.`syllabus` AS `syllabus`,`course`.`operator` AS `operator`,`course`.`update_date` AS `update_date` from (`major_course` join `course`) where ((`course`.`level` = 1) and (`course`.`courseid` = `major_course`.`courseid`)))$$

DELIMITER ;