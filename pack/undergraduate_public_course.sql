DELIMITER $$

DROP VIEW IF EXISTS `tsmsv3`.`undergraduate_public_course`$$

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `undergraduate_public_course` AS (select `public_course`.`semester` AS `semester`,`public_course`.`grade` AS `grade`,`course`.`courseid` AS `courseid`,`course`.`name` AS `name`,`course`.`level` AS `level`,`course`.`property` AS `property`,`course`.`cou_type` AS `cou_type`,`course`.`cou_category` AS `cou_category`,`course`.`exam_type` AS `exam_type`,`course`.`planweektime` AS `planweektime`,`course`.`expweektime` AS `expweektime`,`course`.`expcourseid` AS `expcourseid`,`course`.`week` AS `week`,`course`.`totaltime` AS `totaltime`,`course`.`credit` AS `credit`,`course`.`syllabus` AS `syllabus`,`course`.`operator` AS `operator`,`course`.`update_date` AS `update_date` from (`public_course` join `course`) where ((`course`.`level` = 1) and (`course`.`courseid` = `public_course`.`courseid`)))$$

DELIMITER ;