-- xiaoqing SQL Backup
-- Time:2012-11-06 21:57:55
-- http://www.yourphp.cn 

--
-- 表的结构 `access`
-- 
DROP TABLE IF EXISTS `access`;
CREATE TABLE `access` (
  `role_id` smallint(6) unsigned NOT NULL COMMENT '角色',
  `node_id` smallint(6) unsigned NOT NULL COMMENT '节点',
  `level` tinyint(1) NOT NULL COMMENT '级别',
  `module` varchar(50) DEFAULT NULL COMMENT '模块',
  KEY `groupid` (`role_id`),
  KEY `nodeid` (`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1  COMMENT='权限表';
--
-- 表的结构 `assessment`
-- 
DROP TABLE IF EXISTS `assessment`;
CREATE TABLE `assessment` (
  `assessmentid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '考核代码',
  `teachid` int(10) unsigned NOT NULL COMMENT '课程代码',
  `name` varchar(64) DEFAULT NULL COMMENT '考核名称',
  `type` tinyint(3) unsigned NOT NULL COMMENT '考核类型',
  `weight` int(10) unsigned DEFAULT NULL COMMENT '考核所占比重',
  `seq` tinyint(2) unsigned DEFAULT NULL COMMENT '排序',
  `operator` int(10) unsigned DEFAULT NULL COMMENT '操作员',
  `update_date` date DEFAULT NULL COMMENT '操作日期',
  PRIMARY KEY (`assessmentid`),
  KEY `i_pk_teachid` (`teachid`),
  CONSTRAINT `FK_assessment_teach_task_teachid` FOREIGN KEY (`teachid`) REFERENCES `teach_task` (`teachid`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COMMENT='考核信息';

-- 
-- 导出`assessment`表中的数据 `assessment`
--
INSERT INTO `assessment` VALUES ('10','1','第一章练习','2','5','1','5','2012-11-06');
INSERT INTO `assessment` VALUES ('11','1','第二章练习','2','80','2','5','2012-11-06');
INSERT INTO `assessment` VALUES ('12','1','期末考试','1','40','12','5','2012-10-29');
INSERT INTO `assessment` VALUES ('13','1','点名','3','20','11','5','2012-10-30');
INSERT INTO `assessment` VALUES ('14','1','第三章练习','1','6','3','5','2012-10-29');
INSERT INTO `assessment` VALUES ('15','1','第四章练习','1','6','4','5','2012-10-29');
INSERT INTO `assessment` VALUES ('16','1','第五章练习','1','5','5','5','2012-10-29');
INSERT INTO `assessment` VALUES ('17','1','第七章练习','3','8','7','5','2012-10-30');
INSERT INTO `assessment` VALUES ('18','1','第八章练习','1','10','8','5','2012-10-29');
INSERT INTO `assessment` VALUES ('19','1','第九章练习','1','10','9','5','2012-10-29');
INSERT INTO `assessment` VALUES ('20','1','第十章练习','1','10','8','5','2012-10-29');
INSERT INTO `assessment` VALUES ('30','2','考核2','1','50','1','5','2012-10-30');
INSERT INTO `assessment` VALUES ('31','2','第二部分','1','20','2','5','2012-10-30');
INSERT INTO `assessment` VALUES ('32','2','2','1','2','3',NULL,NULL);
INSERT INTO `assessment` VALUES ('34','98','平时1','1','3','1','5','2012-11-06');
INSERT INTO `assessment` VALUES ('35','98','平时2','1','45','2','5','2012-11-06');
INSERT INTO `assessment` VALUES ('36','98','平时3','1','66','3','5','2012-11-06');
INSERT INTO `assessment` VALUES ('37','98','平时5','1','4','5','5','2012-11-06');
INSERT INTO `assessment` VALUES ('38','97','平时1','3','4','1','5','2012-11-06');
INSERT INTO `assessment` VALUES ('39','97','平时2','3','4','2','5','2012-11-06');
--
-- 表的结构 `award`
-- 
DROP TABLE IF EXISTS `award`;
CREATE TABLE `award` (
  `userid` int(10) unsigned NOT NULL COMMENT '学号',
  `year` year(4) NOT NULL COMMENT '学年',
  `semester` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '学期',
  `name` varchar(64) NOT NULL COMMENT '奖励名称',
  `money` int(10) unsigned DEFAULT NULL COMMENT '奖励金额',
  `award` varchar(64) DEFAULT NULL COMMENT '其他奖励',
  `type` tinyint(3) unsigned NOT NULL COMMENT '奖励类型',
  `operator` int(10) unsigned DEFAULT NULL COMMENT '操作员',
  `update_date` date DEFAULT NULL COMMENT '操作日期',
  PRIMARY KEY (`userid`,`year`,`semester`,`name`),
  CONSTRAINT `FK_award_student_userid` FOREIGN KEY (`userid`) REFERENCES `student` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='获奖记录';

-- 
-- 导出`award`表中的数据 `award`
--
INSERT INTO `award` VALUES ('1','2010','1','国家励志奖学金','5000','国家励志奖学金证书','1',NULL,'2012-10-10');
INSERT INTO `award` VALUES ('1','2011','1','学习优秀奖','3000','学习优秀奖证书','1',NULL,'2012-10-10');
--
-- 表的结构 `class`
-- 
DROP TABLE IF EXISTS `class`;
CREATE TABLE `class` (
  `classid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '班级代码',
  `name` varchar(128) NOT NULL COMMENT '班级名称',
  `head_teacher` int(10) unsigned DEFAULT NULL COMMENT '班主任',
  `instructor` int(10) unsigned DEFAULT NULL COMMENT '辅导员',
  `num` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '班级人数',
  `majorid` int(10) unsigned NOT NULL COMMENT '所属专业',
  `register_date` year(4) NOT NULL COMMENT '班级成立学年',
  `moniter` int(10) unsigned DEFAULT NULL COMMENT '班长学号',
  `operator` int(10) unsigned DEFAULT NULL COMMENT '操作员',
  `update_date` date DEFAULT NULL COMMENT '操作日期',
  PRIMARY KEY (`classid`),
  KEY `i_pk_major` (`majorid`),
  KEY `fk_class_teacher_head_teacher` (`head_teacher`),
  KEY `fk_class_teacher_instructor` (`instructor`),
  CONSTRAINT `fk_class_major_majorid` FOREIGN KEY (`majorid`) REFERENCES `major` (`majorid`),
  CONSTRAINT `fk_class_teacher_head_teacher` FOREIGN KEY (`head_teacher`) REFERENCES `teacher` (`userid`),
  CONSTRAINT `fk_class_teacher_instructor` FOREIGN KEY (`instructor`) REFERENCES `teacher` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COMMENT='班级';

-- 
-- 导出`class`表中的数据 `class`
--
INSERT INTO `class` VALUES ('1','计算机科学与技术（非师范）2班','7','7','30','1','2009',NULL,NULL,'2012-10-12');
INSERT INTO `class` VALUES ('2','计算机科学与技术（非师范）1班','7','7','30','1','2009',NULL,NULL,NULL);
INSERT INTO `class` VALUES ('3','计算机科学与技术（师范）1班','6','6','60','1','2012',NULL,NULL,NULL);
INSERT INTO `class` VALUES ('4','物联网班','6','6','30','2','2010',NULL,NULL,'2012-10-11');
INSERT INTO `class` VALUES ('9','计算机科学与技术（非师范）1班',NULL,NULL,'30','1','2010',NULL,NULL,NULL);
INSERT INTO `class` VALUES ('12','计算机科学与技术（非师范）1班',NULL,NULL,'30','1','2011',NULL,'4','2012-10-22');
INSERT INTO `class` VALUES ('13','计算机科学与技术（非师范）1班',NULL,NULL,'30','1','2012',NULL,'4','2012-10-22');
INSERT INTO `class` VALUES ('14','计算机科学与技术（非师范）2班',NULL,NULL,'30','1','2010',NULL,'4','2012-10-22');
INSERT INTO `class` VALUES ('15','计算机科学与技术（非师范）2班',NULL,NULL,'30','1','2011',NULL,'4','2012-10-22');
INSERT INTO `class` VALUES ('16','计算机科学与技术（非师范）2班',NULL,NULL,'30','1','2012',NULL,'4','2012-10-22');
INSERT INTO `class` VALUES ('17','计算机科学与技术（师范）1班','6','5','30','1','2009',NULL,'4','2012-10-22');
INSERT INTO `class` VALUES ('19','计算机科学与技术（师范）1班','5','6','30','1','2011',NULL,'4','2012-10-22');
INSERT INTO `class` VALUES ('22','物联网1班',NULL,NULL,'30','2','2009',NULL,'4','2012-10-22');
INSERT INTO `class` VALUES ('23','物联网2班','6','7','30','2','2010',NULL,'4','2012-10-22');
INSERT INTO `class` VALUES ('24','物联网1班',NULL,NULL,'30','2','2011',NULL,'4','2012-10-22');
INSERT INTO `class` VALUES ('25','物联网1班',NULL,NULL,'30','2','2012',NULL,'4','2012-10-22');
INSERT INTO `class` VALUES ('26','物联网2班',NULL,NULL,'30','2','2012',NULL,'4','2012-10-22');
INSERT INTO `class` VALUES ('27','物联网2班',NULL,NULL,'30','2','2010',NULL,'4','2012-10-22');
INSERT INTO `class` VALUES ('28','物联网2班',NULL,NULL,'30','2','2011',NULL,'4','2012-10-22');
INSERT INTO `class` VALUES ('29','网络信息安全','5','5','30','3','2009',NULL,'4','2012-10-22');
INSERT INTO `class` VALUES ('30','网络信息安全','7','6','30','3','2010',NULL,'4','2012-10-22');
INSERT INTO `class` VALUES ('31','网络信息安全','6','5','30','3','2011',NULL,'4','2012-10-22');
INSERT INTO `class` VALUES ('32','网络信息安全','7','6','30','3','2012',NULL,'4','2012-10-22');
INSERT INTO `class` VALUES ('33','人工智能','6','5','30','4','2012',NULL,'4','2012-10-22');
INSERT INTO `class` VALUES ('34','人工智能','5','6','30','4','2009',NULL,'4','2012-10-22');
INSERT INTO `class` VALUES ('35','人工智能','6','5','30','4','2010',NULL,'4','2012-10-22');
INSERT INTO `class` VALUES ('36','人工智能','6','5','30','4','2011',NULL,'4','2012-10-22');
INSERT INTO `class` VALUES ('37','软件工程','5','6','30','5','2009',NULL,'4','2012-10-22');
INSERT INTO `class` VALUES ('38','软件工程','5','6','30','5','2010',NULL,'4','2012-10-22');
INSERT INTO `class` VALUES ('39','软件工程','5','6','30','5','2011',NULL,'4','2012-10-22');
INSERT INTO `class` VALUES ('40','软件工程','7','6','30','5','2012',NULL,'4','2012-10-22');
--
-- 表的结构 `config`
-- 
DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `varname` varchar(64) NOT NULL COMMENT '配置名称',
  `info` varchar(64) NOT NULL DEFAULT '0' COMMENT '说明',
  `value` varchar(512) DEFAULT NULL COMMENT '系统配置值',
  `flag` tinyint(3) unsigned NOT NULL COMMENT '状态',
  PRIMARY KEY (`varname`),
  UNIQUE KEY `id_varname` (`varname`),
  UNIQUE KEY `NewIndex1` (`varname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统配置';

-- 
-- 导出`config`表中的数据 `config`
--
INSERT INTO `config` VALUES ('allow_add_graduation_thesis_title','允许添加毕业论文选题','1','1');
INSERT INTO `config` VALUES ('allow_add_term_thesis_title','允许添加学年论文选题','1','1');
INSERT INTO `config` VALUES ('autumn_semester','秋季学期','2','1');
INSERT INTO `config` VALUES ('default_start_week','默认起始授课周','1','1');
INSERT INTO `config` VALUES ('default_teach_week','默认授课周数','18','1');
INSERT INTO `config` VALUES ('graduation_thesis','毕业论文','2','1');
INSERT INTO `config` VALUES ('not_limit_admission','不限年级','0','1');
INSERT INTO `config` VALUES ('not_limit_major','不限专业','6','1');
INSERT INTO `config` VALUES ('role_type_teacher','教室角色','4','1');
INSERT INTO `config` VALUES ('spring_semester','春季学期','1','1');
INSERT INTO `config` VALUES ('term_thesis','学年论文','1','1');
INSERT INTO `config` VALUES ('thesis_result_failed','论文审核未通过','3','1');
INSERT INTO `config` VALUES ('thesis_result_pass','论文审核通过','2','1');
INSERT INTO `config` VALUES ('thesis_result_wait','论文待审核','1','1');
INSERT INTO `config` VALUES ('thesis_title_assign_teacher_model','论文题目指定教师模式','2','1');
INSERT INTO `config` VALUES ('thesis_title_free_select_model','论文题目自由选题模式','1','1');
INSERT INTO `config` VALUES ('user_title_select_model','使用的论文选题模式','1','1');
--
-- 表的结构 `course`
-- 
DROP TABLE IF EXISTS `course`;
CREATE TABLE `course` (
  `courseid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '课程代码',
  `name` varchar(32) NOT NULL COMMENT '课程名',
  `semester` int(3) unsigned DEFAULT NULL COMMENT '开课学期',
  `level` tinyint(3) unsigned DEFAULT NULL COMMENT '学生层次',
  `property` tinyint(3) unsigned NOT NULL COMMENT '课程性质',
  `cou_type` tinyint(3) unsigned NOT NULL COMMENT '课程类型',
  `cou_category` tinyint(3) unsigned DEFAULT NULL COMMENT '课程类别',
  `exam_type` tinyint(3) unsigned NOT NULL COMMENT '考试类型',
  `theweektime` tinyint(3) unsigned DEFAULT NULL COMMENT '理论周学时',
  `expweektime` tinyint(3) unsigned DEFAULT NULL COMMENT '实验周学时',
  `expcourseid` int(10) unsigned DEFAULT NULL COMMENT '对应实验课',
  `week` tinyint(3) unsigned DEFAULT NULL COMMENT '开设周数',
  `totaltime` smallint(5) unsigned DEFAULT NULL COMMENT '总学时',
  `credit` smallint(5) unsigned NOT NULL COMMENT '学分',
  `syllabus` text COMMENT '教学大纲',
  `operator` int(10) unsigned DEFAULT NULL COMMENT '操作员',
  `update_date` date DEFAULT NULL COMMENT '操作日期',
  PRIMARY KEY (`courseid`),
  KEY `i_pk_semester` (`semester`),
  KEY `FK_course_course_expcourseid` (`expcourseid`),
  CONSTRAINT `FK_course_course_expcourseid` FOREIGN KEY (`expcourseid`) REFERENCES `course` (`courseid`)
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=utf8 COMMENT='课程';

-- 
-- 导出`course`表中的数据 `course`
--
INSERT INTO `course` VALUES ('1','程序设计','1','2','1','1','2','1','4','10','1','18','100','0',NULL,'9','2012-11-04');
INSERT INTO `course` VALUES ('2','计算机网络','1','2','1','1','2','1','6','4','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('3','密码学','1','2','1','1','2','2','10','0','1','0','100','0',NULL,'4','2012-10-23');
INSERT INTO `course` VALUES ('4','微积分','1','1','1','1','2','1','10','0','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('5','网络信息安全','4','1','1','2','2','1','4','0',NULL,'0',NULL,'0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('6','思修','1','2','1','1','1','1','21','2','2',NULL,'12','1',NULL,NULL,NULL);
INSERT INTO `course` VALUES ('7','马克思','1','2','1','1','1','1','1','1','1','1','1','1',NULL,NULL,NULL);
INSERT INTO `course` VALUES ('8','形势与政策','1','2','1','1','1','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('9','马克思主义基本原理','1','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('10','大学体育Ⅰ','1','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('11','大学语文','1','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('12','大学英语Ⅰ','2','2','1','1','1','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('13','大学英语Ⅰ','2','2','1','1','1','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('14','计算机导论','1','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('15','计算机导论实验','1','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('16','电子技术基础','1','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('17','电子技术基础实验','1','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('18','数学分析Ⅰ','2','2','1','1','1','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('19','线性代数','1','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('20','大学英语A级Ⅰ','3','2','1','1','1','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('21','大学英语B级Ⅰ','3','2','1','1','1','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('22','军事理论','3','2','1','2','1','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('23','思想道德修养与法律基础','4','2','1','2','1','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('24','大学体育Ⅱ','4','2','1','2','1','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('25','大学英语Ⅱ','4','2','1','2','1','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('26','计算机组成原理','5','2','1','1','1','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('27','程序设计Ⅰ','5','2','1','1','1','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('28','程序设计Ⅰ实验','2','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('29','计算机组成原理实验','2','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('30','计算机专业英语','2','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('31','数学分析Ⅱ','2','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('32','概率统计','2','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('33','大学英语A级Ⅱ','5','2','1','1','1','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('34','大学英语B级Ⅱ','6','2','1','1','1','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('35','中国近现代史纲要','6','2','1','1','1','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('36','大学体育Ⅲ','6','2','1','1','1','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('37','大学英语Ⅲ','3','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('38','微机原理与汇编','3','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('39','数据结构','3','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('40','数据结构实验','3','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('41','微机原理与汇编实验','3','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('42','程序设计Ⅱ','3','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('43','程序设计Ⅱ','3','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('44','大学英语A级Ⅲ','6','2','1','1','1','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('45','大学英语B级Ⅲ','7','2','1','1','1','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('46','毛泽东思想和中国特色社会主义理论体系概论','7','2','1','1','1','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('47','毛泽东思想和中国特色社会主义理论体系概论（实践）','7','2','1','1','1','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('48','大学体育Ⅳ','4','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('49','大学英语Ⅳ','4','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('50','离散数学','4','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('51','操作系统','4','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('52','操作系统实验','4','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('60','编译原理','5','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('61','编译原理实验','5','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('62','数据库原理','5','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('63','计算机图形学','5','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('64','计算机图形学实验','5','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('65','多媒体技术','5','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('66','多媒体技术实验','5','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('67','数理逻辑实验','5','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('68','软件工程','5','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('69','软件工程实验','5','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('70','数值通信','5','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('71','数值通信实验','5','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('72','网络信息安全','5','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('73','网络信息安全实验','5','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('74','数理逻辑','5','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('75','计算机网络','5','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('76','计算机网络实验','5','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('77','学年论文','5','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('78','大学生就业指导','8','2','1','1','1','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('79','素质拓展创新','6','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('80','数据库应用技术','6','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('81','数据库应用技术实验','6','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('82','电子商务','6','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('83','电子商务实验','6','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('84','面向对象的程序设计语言','6','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('85','面向对象的程序设计语言实验','6','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('86','软件测试','6','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('87','软件测试实验','6','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('88','网络软件开发','6','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('89','网络软件开发实验','6','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('90','计算机网络工程/集成','6','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('91','计算机网络工程/集成实验','6','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('92','Linux系统','6','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('93','Linux系统实验 ','6','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('94','数学建模','8','2','1','1','1','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('95','微机操作与微机数据库','6','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('96','微机操作与微机数据库实验','6','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('97','高数选讲Ⅰ','8','2','1','1','1','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('98','密码学','6','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('99','XML与WEB服务','6','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('100','XML与WEB服务实验','6','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('101','程序设计方法学','6','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('102','程序设计方法学实验','6','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('103','计算机系统结构','6','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('104','计算机系统结构实验','6','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('105','管理信息系统','7','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('106','管理信息系统实验','7','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('107','图像处理与应用','7','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('108','图像处理与应用实验','7','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('109','算法分析与设计','7','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('110','信息系统架构(J2EE)','7','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('111','信息系统架构实验(J2EE)','7','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('112','新型网络技术','7','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('113','新型网络技术实验','7','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('114','计算机教学与CAI','7','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('115','计算机教学与CAI','7','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('116','CAD','7','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('117','CAD','7','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('118','高数选讲Ⅱ','7','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('119','电脑组装与维修','7','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('120','电脑组装与维修实验','7','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('121','人工智能','7','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('122','人工智能实验','7','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('123','计算复杂性导论','7','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('124','TCP/IP协议分析','7','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('125','TCP/IP协议分析实验','7','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('126','专业实习(含见习)','7','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('127','毕业论文(毕业设计)','7','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('128','网络操作系统','7','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('129','网络操作系统实验','7','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('130','分布式数据库','8','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('131','分布式数据库实验','8','2','1','1','1','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('132','面向对象程序设计方法学','8','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('133','CMS开发与设计','8','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('134','CMS开发与设计实验','8','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('135','工具软件','8','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
INSERT INTO `course` VALUES ('136','工具软件实验','8','2','1','1','2','1','4','10','1','18','100','0',NULL,NULL,'2012-10-11');
--
-- 表的结构 `course_change`
-- 
DROP TABLE IF EXISTS `course_change`;
CREATE TABLE `course_change` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '课程异动代码',
  `teachid` int(10) unsigned NOT NULL COMMENT '授课代码',
  `time_type` tinyint(3) unsigned DEFAULT '1' COMMENT '时间类别',
  `type` tinyint(3) unsigned NOT NULL COMMENT '类型',
  `teacherid` int(10) unsigned NOT NULL COMMENT '上课教师',
  `teach_building` tinyint(3) unsigned NOT NULL COMMENT '教学楼编号',
  `roomid` int(10) unsigned NOT NULL COMMENT '教室编号',
  `week` tinyint(3) unsigned NOT NULL COMMENT '星期',
  `seq` tinyint(3) unsigned NOT NULL COMMENT '课序',
  `reason` varchar(128) DEFAULT NULL COMMENT '原因',
  `operator` int(10) unsigned DEFAULT NULL COMMENT '操作员',
  `update_date` date DEFAULT NULL COMMENT '操作日期',
  PRIMARY KEY (`id`),
  KEY `fk_course_change_teach_task_teachid` (`teachid`),
  KEY `fk_course_change_teacher_teacherid` (`teacherid`),
  CONSTRAINT `fk_course_change_teacher_teacherid` FOREIGN KEY (`teacherid`) REFERENCES `teacher` (`userid`),
  CONSTRAINT `fk_course_change_teach_task_teachid` FOREIGN KEY (`teachid`) REFERENCES `teach_task` (`teachid`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='课程异动记录';

-- 
-- 导出`course_change`表中的数据 `course_change`
--
INSERT INTO `course_change` VALUES ('1','1','1','1','6','2','209','1','1','我肚子疼，不想上课','5','2012-11-02');
INSERT INTO `course_change` VALUES ('2','2','2','2','6','3','213','1','2',NULL,NULL,'2012-11-01');
INSERT INTO `course_change` VALUES ('3','97','1','3','7','7','231','1','3',NULL,NULL,'2012-11-01');
INSERT INTO `course_change` VALUES ('10','1','1','1','5','4','444','1','1','hello我要调课','5','2012-11-02');
INSERT INTO `course_change` VALUES ('11','1','2','2','7','6','444','3','4','hello','5','2012-11-02');
INSERT INTO `course_change` VALUES ('12','98','1','1','6','8','444','1','1','hello','5','2012-11-06');
INSERT INTO `course_change` VALUES ('13','98','1','1','7','5','404','1','1','hello','5','2012-11-06');
INSERT INTO `course_change` VALUES ('14','97','1','1','5','4','44','1','2','得到','5','2012-11-06');
--
-- 表的结构 `data_dict`
-- 
DROP TABLE IF EXISTS `data_dict`;
CREATE TABLE `data_dict` (
  `tb_name` varchar(32) NOT NULL COMMENT '数据表名',
  `fd_name` varchar(32) NOT NULL COMMENT '字段名',
  `fd_value` tinyint(3) unsigned NOT NULL COMMENT '字段值',
  `fd_mean` varchar(32) NOT NULL COMMENT '字段值含义',
  `type` tinyint(3) unsigned NOT NULL COMMENT '类别',
  `seq` tinyint(3) unsigned DEFAULT NULL COMMENT '序号',
  `remark` varchar(32) DEFAULT NULL COMMENT '备注',
  `operator` int(10) unsigned DEFAULT NULL COMMENT '操作员',
  `update_date` date DEFAULT NULL COMMENT '操作日期',
  PRIMARY KEY (`tb_name`,`fd_name`,`fd_value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='数据字典';

-- 
-- 导出`data_dict`表中的数据 `data_dict`
--
INSERT INTO `data_dict` VALUES ('assessment','type','1','考勤','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('assessment','type','2','课堂提问','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('assessment','type','3','平时作业','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('award','semester','1','春季','1','1','春季',NULL,NULL);
INSERT INTO `data_dict` VALUES ('award','semester','2','秋季','1','2','秋季',NULL,NULL);
INSERT INTO `data_dict` VALUES ('award','type','1','奖学金','1','1','奖学金',NULL,NULL);
INSERT INTO `data_dict` VALUES ('award','type','2','助学金','1','2','助学金奖励',NULL,NULL);
INSERT INTO `data_dict` VALUES ('award','type','3','基金','1','3','基金',NULL,NULL);
INSERT INTO `data_dict` VALUES ('award','type','4','学科竞赛','2','4','学科竞赛',NULL,NULL);
INSERT INTO `data_dict` VALUES ('course','cou_category','1','公共课','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('course','cou_category','2','专业课','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('course','cou_type','1','必修课','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('course','cou_type','2','限选课','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('course','cou_type','3','任选课','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('course','exam_type','1','考试','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('course','exam_type','2','考查','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('course','level','1','专科生','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('course','level','2','本科生','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('course','level','3','博士研究生','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('course','level','4','硕士研究生','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('course','level','5','成教生','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('course','property','1','理论课','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('course','property','2','实验课','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('course','property','3','术科课','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('course','property','4','课内实习','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('course','semester','1','第一学期','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('course','semester','2','第二学期','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('course','semester','3','第三学期','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('course','semester','4','第四学期','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('course','semester','5','第五学期','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('course','semester','6','第六学期','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('course','semester','7','第七学期','1','0','','4','2012-10-22');
INSERT INTO `data_dict` VALUES ('course','semester','8','第八学期','1','0','','4','2012-10-22');
INSERT INTO `data_dict` VALUES ('course_change','seq','1','早上1~2节','1','1','早上1~2节',NULL,NULL);
INSERT INTO `data_dict` VALUES ('course_change','seq','2','早上3~4节','1','2','早上3~4节',NULL,NULL);
INSERT INTO `data_dict` VALUES ('course_change','seq','3','下午5~6节','1','3','下午5~6节',NULL,NULL);
INSERT INTO `data_dict` VALUES ('course_change','seq','4','下午7~8节','1','4','下午7~8节',NULL,NULL);
INSERT INTO `data_dict` VALUES ('course_change','seq','5','晚上9~10节','1','5','晚上9~10节',NULL,NULL);
INSERT INTO `data_dict` VALUES ('course_change','time_type','1','临时','1','1','临时改动',NULL,NULL);
INSERT INTO `data_dict` VALUES ('course_change','time_type','2','长期','1','2','长期改动',NULL,NULL);
INSERT INTO `data_dict` VALUES ('course_change','type','1','调课','1','1','调课',NULL,NULL);
INSERT INTO `data_dict` VALUES ('course_change','type','2','补课','1','2','补课',NULL,NULL);
INSERT INTO `data_dict` VALUES ('course_change','type','3','不上课','1','3','不上课',NULL,NULL);
INSERT INTO `data_dict` VALUES ('course_change','week','1','星期一','1','1','星期一',NULL,NULL);
INSERT INTO `data_dict` VALUES ('course_change','week','2','星期二','1','2','星期二',NULL,NULL);
INSERT INTO `data_dict` VALUES ('course_change','week','3','星期三','1','3','星期三',NULL,NULL);
INSERT INTO `data_dict` VALUES ('course_change','week','4','星期四','1','4','星期四',NULL,NULL);
INSERT INTO `data_dict` VALUES ('course_change','week','5','星期五','1','5','星期五',NULL,NULL);
INSERT INTO `data_dict` VALUES ('course_change','week','6','星期六','1','6','星期六',NULL,NULL);
INSERT INTO `data_dict` VALUES ('course_change','week','7','星期日','1','7','星期日',NULL,NULL);
INSERT INTO `data_dict` VALUES ('data_dict','type','1','系统预设','1','1','该字段值为系统预设',NULL,NULL);
INSERT INTO `data_dict` VALUES ('data_dict','type','2','用户自定义','2','2','该字段值为用户自定义',NULL,NULL);
INSERT INTO `data_dict` VALUES ('exam','type','1','开卷','1','1','开卷考试',NULL,NULL);
INSERT INTO `data_dict` VALUES ('exam','type','2','闭卷','1','2','闭卷考试',NULL,NULL);
INSERT INTO `data_dict` VALUES ('major','Level','1','本科生','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('major','Level','2','硕士','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('major','Level','3','博士','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('major','School_system','1','本科四年制','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('major','School_system','2','专科三年制','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('major','School_system','3','普通三年制硕士','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('major','School_system','4','专业两年制硕士','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('major','type','1','工程硕士','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('major','type','2','教育硕士','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('major','type','3','学术型','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('major','type','4','普通本科','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('major','type','5','成人本科','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('notice','category','1','学生','1','1','学生',NULL,NULL);
INSERT INTO `data_dict` VALUES ('notice','category','2','教师','1','2','教师',NULL,NULL);
INSERT INTO `data_dict` VALUES ('notice','importance','1','一般','1','1','一般',NULL,NULL);
INSERT INTO `data_dict` VALUES ('notice','importance','2','重要','1','2','重要',NULL,NULL);
INSERT INTO `data_dict` VALUES ('notice','importance','3','很重要','1','3','很重要',NULL,NULL);
INSERT INTO `data_dict` VALUES ('notice','importance','4','非常重要','1','4','非常重要',NULL,NULL);
INSERT INTO `data_dict` VALUES ('notice','importance','5','置顶','1','5','置顶',NULL,NULL);
INSERT INTO `data_dict` VALUES ('notice','type','1','班级','1','1','班级',NULL,NULL);
INSERT INTO `data_dict` VALUES ('notice','type','2','学院','1','2','学院通知',NULL,NULL);
INSERT INTO `data_dict` VALUES ('organize','level','1','国家级','1','0','',NULL,NULL);
INSERT INTO `data_dict` VALUES ('organize','level','2','省级','1','0','',NULL,NULL);
INSERT INTO `data_dict` VALUES ('organize','level','3','校级','1','0','',NULL,NULL);
INSERT INTO `data_dict` VALUES ('organize','level','4','院级','1','0','',NULL,NULL);
INSERT INTO `data_dict` VALUES ('organize','property','1','教学部','1','1','教学部','4','2012-10-17');
INSERT INTO `data_dict` VALUES ('organize','property','2','非教学部','1','2','非教学部','4','2012-10-17');
INSERT INTO `data_dict` VALUES ('orgduty','level','1','处级','1','0','',NULL,NULL);
INSERT INTO `data_dict` VALUES ('orgduty','level','2','副处级','1','0','',NULL,NULL);
INSERT INTO `data_dict` VALUES ('orgduty','level','3','科级','1','0','',NULL,NULL);
INSERT INTO `data_dict` VALUES ('orgduty','level','4','副科级','1','0','',NULL,NULL);
INSERT INTO `data_dict` VALUES ('orgduty','name','1','正高','1','0','',NULL,NULL);
INSERT INTO `data_dict` VALUES ('orgduty','name','2','副高','1','0','',NULL,NULL);
INSERT INTO `data_dict` VALUES ('orgduty','name','3','中级','1','0','',NULL,NULL);
INSERT INTO `data_dict` VALUES ('orgduty','name','4','初级','1','0','',NULL,NULL);
INSERT INTO `data_dict` VALUES ('student','gender','1','男','1','1','男生',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('student','gender','2','女','1','2','女生',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('student','gender','3','未知','1','3','未知',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('teacher','degree','1','博士研究生','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('teacher','degree','2','硕士研究生','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('teacher','degree','3','本科','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('teacher','degree','4','专科','2','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('teacher','gender','1','男','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('teacher','gender','2','女','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('teacher','job_title','1','教授','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('teacher','job_title','2','副教授','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('teacher','job_title','3','讲师','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('teacher','job_title','4','助教','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('teacher','nationality','1','汉族','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('teacher','nationality','2','回族','2','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('teacher','nationality','3','蒙古族','2','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('teach_task','finish_status','1','已授','1','1','','5','2012-11-04');
INSERT INTO `data_dict` VALUES ('teach_task','finish_status','2','在授','1','2','','5','2012-11-04');
INSERT INTO `data_dict` VALUES ('teach_task','finish_status','3','拟授','1','3','','5','2012-11-04');
INSERT INTO `data_dict` VALUES ('teach_task','status','1','待同意','1','1','','5','2012-11-05');
INSERT INTO `data_dict` VALUES ('teach_task','status','2','同意','1','2','','5','2012-11-05');
INSERT INTO `data_dict` VALUES ('teach_task','status','3','不同意','1','3','','5','2012-11-05');
INSERT INTO `data_dict` VALUES ('teach_task','teach_semester','1','春季','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('teach_task','teach_semester','2','秋季','1','0','',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('thesis_title','category','1','学年论文','1','1','学年论文',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('thesis_title','category','2','毕业论文','1','2','毕业论文',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('thesis_title','level','1','本科生','1','1','本科生',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('thesis_title','level','2','硕士研究生','1','2','硕士研究生',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('thesis_title','level','3','博士研究生','1','3','博士研究生',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('thesis_title','level','4','成教生','1','4','成教生',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('thesis_title','result','1','待审核','1','1','审核','1','2012-10-26');
INSERT INTO `data_dict` VALUES ('thesis_title','result','2','通过','1','2','通过',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('thesis_title','result','3','未通过','1','3','未通过',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('thesis_title','type','1','理论','1','1','理论',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('thesis_title','type','2','实践','1','2','实践',NULL,'2012-10-11');
INSERT INTO `data_dict` VALUES ('thesis_title','type','3','理论结合实践','1','3','理论结合实践',NULL,'2012-10-11');
--
-- 表的结构 `exam`
-- 
DROP TABLE IF EXISTS `exam`;
CREATE TABLE `exam` (
  `teachid` int(10) unsigned NOT NULL COMMENT '授课编号',
  `type` tinyint(3) unsigned NOT NULL COMMENT '考试类型',
  `date` date NOT NULL COMMENT '考试日期',
  `time` time NOT NULL COMMENT '考试时间',
  `teach_building` tinyint(3) unsigned NOT NULL COMMENT '教学楼编号',
  `roomid` int(10) unsigned NOT NULL COMMENT '教室编号',
  `operator` int(10) unsigned DEFAULT NULL COMMENT '操作员',
  `update_date` date DEFAULT NULL COMMENT '操作日期',
  PRIMARY KEY (`teachid`),
  UNIQUE KEY `date` (`date`,`time`,`teach_building`,`roomid`),
  CONSTRAINT `fk_exam_teach_task_teachid` FOREIGN KEY (`teachid`) REFERENCES `teach_task` (`teachid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='考试安排';
--
-- 表的结构 `login`
-- 
DROP TABLE IF EXISTS `login`;
CREATE TABLE `login` (
  `userid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户编号',
  `user_code` varchar(16) NOT NULL COMMENT '用户名',
  `user_psd` varchar(32) NOT NULL COMMENT '密码',
  `face` varchar(128) DEFAULT NULL COMMENT '头像',
  `operator` int(10) unsigned DEFAULT NULL COMMENT '操作员',
  `update_date` date DEFAULT NULL COMMENT '操作日期',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `user_code` (`user_code`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='登录信息';

-- 
-- 导出`login`表中的数据 `login`
--
INSERT INTO `login` VALUES ('1','200971040210','96e79218965eb72c92a549dd5a330112',NULL,NULL,'2012-10-11');
INSERT INTO `login` VALUES ('2','200971040208','96e79218965eb72c92a549dd5a330112',NULL,NULL,'2012-10-11');
INSERT INTO `login` VALUES ('3','lisency','96e79218965eb72c92a549dd5a330112',NULL,NULL,'2012-10-11');
INSERT INTO `login` VALUES ('4','admin','96e79218965eb72c92a549dd5a330112',NULL,NULL,'2012-10-11');
INSERT INTO `login` VALUES ('5','teacher1','96e79218965eb72c92a549dd5a330112',NULL,NULL,'2012-10-11');
INSERT INTO `login` VALUES ('6','teacher2','96e79218965eb72c92a549dd5a330112',NULL,NULL,'2012-10-11');
INSERT INTO `login` VALUES ('7','teacher3','96e79218965eb72c92a549dd5a330112',NULL,NULL,'2012-10-11');
INSERT INTO `login` VALUES ('8','teacher4','96e79218965eb72c92a549dd5a330112',NULL,NULL,'2012-10-11');
INSERT INTO `login` VALUES ('9','jwadmin','96e79218965eb72c92a549dd5a330112',NULL,NULL,'2012-10-11');
INSERT INTO `login` VALUES ('10','banzhuren','96e79218965eb72c92a549dd5a330112',NULL,NULL,'2012-10-11');
INSERT INTO `login` VALUES ('11','jsjgcxzhuren','96e79218965eb72c92a549dd5a330112',NULL,NULL,'2012-10-11');
INSERT INTO `login` VALUES ('22','托尔斯泰','96e79218965eb72c92a549dd5a330112',NULL,'4','2012-11-04');
INSERT INTO `login` VALUES ('23','test3','96e79218965eb72c92a549dd5a330112',NULL,'4','2012-11-04');
INSERT INTO `login` VALUES ('24','主管院长','96e79218965eb72c92a549dd5a330112',NULL,'4','2012-11-04');
--
-- 表的结构 `major`
-- 
DROP TABLE IF EXISTS `major`;
CREATE TABLE `major` (
  `majorid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '专业代码',
  `name` varchar(32) NOT NULL COMMENT '专业名称',
  `orgid` int(10) unsigned NOT NULL COMMENT '所属机构',
  `level` tinyint(3) unsigned DEFAULT NULL COMMENT '层次',
  `type` tinyint(3) unsigned DEFAULT NULL COMMENT '类别',
  `School_system` tinyint(3) unsigned DEFAULT NULL COMMENT '学制',
  `introduction` text COMMENT '专业简介',
  `requirement` text COMMENT '毕业要求',
  `Training_plan` text COMMENT '培养方案',
  `operator` int(10) unsigned DEFAULT NULL COMMENT '操作员',
  `update_date` date DEFAULT NULL COMMENT '操作日期',
  PRIMARY KEY (`majorid`),
  KEY `i_pk_depid` (`orgid`),
  CONSTRAINT `FK_major_organize_orgid` FOREIGN KEY (`orgid`) REFERENCES `organize` (`orgid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='专业';

-- 
-- 导出`major`表中的数据 `major`
--
INSERT INTO `major` VALUES ('1','计算机科学与技术','2','1','5','4',NULL,NULL,NULL,NULL,NULL);
INSERT INTO `major` VALUES ('2','物联网工程','2','1','5','4',NULL,NULL,NULL,NULL,NULL);
INSERT INTO `major` VALUES ('3','网络与信息安全','2','1','5','4',NULL,NULL,NULL,NULL,NULL);
INSERT INTO `major` VALUES ('4','智能科学与技术','2','1','5',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `major` VALUES ('5','软件工程','2','1','5',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `major` VALUES ('6','不限专业','2','1','4',NULL,'','','','9','2012-10-28');
--
-- 表的结构 `major_course`
-- 
DROP TABLE IF EXISTS `major_course`;
CREATE TABLE `major_course` (
  `majorid` int(10) unsigned NOT NULL COMMENT '专业代码',
  `courseid` int(10) unsigned NOT NULL COMMENT '课程代码',
  `year` year(4) NOT NULL COMMENT '年级',
  `operator` int(10) unsigned DEFAULT NULL COMMENT '操作员',
  `update_date` date DEFAULT NULL COMMENT '操作日期',
  PRIMARY KEY (`majorid`,`courseid`,`year`),
  KEY `i_pk_majorid` (`majorid`),
  KEY `fk_major_course_course_courseid` (`courseid`),
  CONSTRAINT `fk_major_course_course_courseid` FOREIGN KEY (`courseid`) REFERENCES `course` (`courseid`),
  CONSTRAINT `FK_major_course_major_majorid` FOREIGN KEY (`majorid`) REFERENCES `major` (`majorid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='专业计划课程';

-- 
-- 导出`major_course`表中的数据 `major_course`
--
INSERT INTO `major_course` VALUES ('1','1','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','1','2011',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','1','2012',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','2','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','2','2011',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','2','2012',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','3','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','3','2011',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','3','2012',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','4','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','4','2011',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','4','2012',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','5','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','6','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','6','2011',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','7','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','7','2010',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','7','2011',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','8','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','8','2011',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','9','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','9','2011',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','10','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','10','2011',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','11','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','11','2011',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','12','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','12','2011',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','13','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','13','2011',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','14','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','14','2010',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','14','2011',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','15','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','15','2010',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','15','2011',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','16','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','16','2010',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','16','2011',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','17','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','17','2011',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','18','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','18','2011',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','19','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','19','2011',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','20','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','21','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','22','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','23','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','24','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','25','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','26','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','28','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','28','2011',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','29','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','29','2011',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','30','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','30','2011',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','31','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','31','2011',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','32','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','32','2011',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','37','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','38','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','39','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','40','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','41','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','42','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','43','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','48','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','49','2009',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','62','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','63','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','64','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','65','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','66','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','67','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','68','0000',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','68','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','69','0000',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','69','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','70','0000',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','70','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','71','0000',NULL,'2012-11-06');
INSERT INTO `major_course` VALUES ('1','100','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','101','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','102','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','103','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','104','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','105','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','106','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','107','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','108','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','121','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','122','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','123','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','124','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','125','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','126','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','132','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','133','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','134','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('1','135','2009','1','2012-11-06');
INSERT INTO `major_course` VALUES ('2','1','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','1','2012',NULL,'2012-10-12');
INSERT INTO `major_course` VALUES ('2','2','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','2','2012',NULL,'2012-10-12');
INSERT INTO `major_course` VALUES ('2','3','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','4','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','5','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','6','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','6','2012','4','2012-10-22');
INSERT INTO `major_course` VALUES ('2','7','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','7','2012','4','2012-10-22');
INSERT INTO `major_course` VALUES ('2','8','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','8','2011','4','2012-10-22');
INSERT INTO `major_course` VALUES ('2','9','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','9','2011','4','2012-10-22');
INSERT INTO `major_course` VALUES ('2','10','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','11','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','12','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','13','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','14','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','15','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','16','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','17','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','18','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','19','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','20','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','21','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','22','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','23','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','24','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','25','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','26','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','27','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','28','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','29','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','30','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','31','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','32','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','33','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','34','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','35','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','36','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','37','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','38','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','38','2011','4','2012-10-22');
INSERT INTO `major_course` VALUES ('2','39','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','39','2011','4','2012-10-22');
INSERT INTO `major_course` VALUES ('2','40','2010','4','2012-10-22');
INSERT INTO `major_course` VALUES ('2','41','2010','4','2012-10-22');
INSERT INTO `major_course` VALUES ('2','42','2010','4','2012-10-22');
INSERT INTO `major_course` VALUES ('2','43','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','44','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','45','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','46','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','47','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','48','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','49','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','50','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','51','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','52','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','60','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','61','2010','4','2012-10-22');
INSERT INTO `major_course` VALUES ('2','62','2009','4','2012-10-22');
INSERT INTO `major_course` VALUES ('2','62','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','63','2009','4','2012-10-22');
INSERT INTO `major_course` VALUES ('2','63','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','64','2009','4','2012-10-22');
INSERT INTO `major_course` VALUES ('2','64','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','65','2009','4','2012-10-22');
INSERT INTO `major_course` VALUES ('2','65','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','66','2009','4','2012-10-22');
INSERT INTO `major_course` VALUES ('2','66','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','67','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','68','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','69','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','70','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','71','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','72','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','73','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','74','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','75','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','76','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','77','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','78','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','79','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','80','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','81','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','82','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','83','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','84','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','85','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','86','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','87','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','88','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','89','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','90','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','91','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','92','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','93','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','94','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','95','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','96','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','97','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','98','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','99','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','100','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','101','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','102','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','103','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','104','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','105','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','106','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','107','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','108','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','109','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','110','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','111','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','112','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','113','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','114','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','115','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','116','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','117','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','118','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','119','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','120','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','121','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','122','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','123','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','124','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','125','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','126','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','127','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','128','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','129','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','130','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','131','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','132','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','133','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','134','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','135','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('2','136','2010','5','2012-11-06');
INSERT INTO `major_course` VALUES ('3','1','2012',NULL,'2012-10-12');
INSERT INTO `major_course` VALUES ('3','2','2012',NULL,'2012-10-12');
INSERT INTO `major_course` VALUES ('3','4','2012',NULL,'2012-10-12');
INSERT INTO `major_course` VALUES ('3','10','2012','4','2012-10-22');
INSERT INTO `major_course` VALUES ('3','11','2012','4','2012-10-22');
INSERT INTO `major_course` VALUES ('3','12','2012','4','2012-10-22');
INSERT INTO `major_course` VALUES ('3','13','2011','4','2012-10-22');
INSERT INTO `major_course` VALUES ('3','14','2011','4','2012-10-22');
INSERT INTO `major_course` VALUES ('3','15','2011','4','2012-10-22');
INSERT INTO `major_course` VALUES ('3','51','2010','4','2012-10-22');
INSERT INTO `major_course` VALUES ('3','52','2010','4','2012-10-22');
INSERT INTO `major_course` VALUES ('3','68','2010','4','2012-10-22');
INSERT INTO `major_course` VALUES ('3','70','2009','4','2012-10-22');
INSERT INTO `major_course` VALUES ('3','71','2009','4','2012-10-22');
INSERT INTO `major_course` VALUES ('3','72','2009','4','2012-10-22');
INSERT INTO `major_course` VALUES ('4','16','2012','4','2012-10-22');
INSERT INTO `major_course` VALUES ('4','17','2012','4','2012-10-22');
INSERT INTO `major_course` VALUES ('4','18','2012','4','2012-10-22');
INSERT INTO `major_course` VALUES ('4','19','2011','4','2012-10-22');
INSERT INTO `major_course` VALUES ('4','20','2011','4','2012-10-22');
INSERT INTO `major_course` VALUES ('4','74','2010','4','2012-10-22');
INSERT INTO `major_course` VALUES ('4','75','2010','4','2012-10-22');
INSERT INTO `major_course` VALUES ('4','76','2010','4','2012-10-22');
INSERT INTO `major_course` VALUES ('4','77','2009','4','2012-10-22');
INSERT INTO `major_course` VALUES ('4','78','2009','4','2012-10-22');
INSERT INTO `major_course` VALUES ('4','79','2009','4','2012-10-22');
INSERT INTO `major_course` VALUES ('4','80','2009','4','2012-10-22');
INSERT INTO `major_course` VALUES ('5','82','0000','4','2012-10-22');
INSERT INTO `major_course` VALUES ('5','83','2010','4','2012-10-22');
INSERT INTO `major_course` VALUES ('5','84','2010','4','2012-10-22');
INSERT INTO `major_course` VALUES ('5','85','2010','4','2012-10-22');
INSERT INTO `major_course` VALUES ('5','86','2011','4','2012-10-22');
INSERT INTO `major_course` VALUES ('5','87','2011','4','2012-10-22');
INSERT INTO `major_course` VALUES ('5','88','2011','4','2012-10-22');
INSERT INTO `major_course` VALUES ('5','89','2011','4','2012-10-22');
INSERT INTO `major_course` VALUES ('5','90','2012','4','2012-10-22');
INSERT INTO `major_course` VALUES ('5','91','2012','4','2012-10-22');
INSERT INTO `major_course` VALUES ('5','92','2009','4','2012-10-22');
INSERT INTO `major_course` VALUES ('5','93','2009','4','2012-10-22');
--
-- 表的结构 `major_year`
-- 
DROP TABLE IF EXISTS `major_year`;
CREATE TABLE `major_year` (
  `majorid` int(10) unsigned NOT NULL COMMENT '专业',
  `year` year(4) NOT NULL COMMENT '招生年',
  PRIMARY KEY (`majorid`,`year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='专业招生年';

-- 
-- 导出`major_year`表中的数据 `major_year`
--
INSERT INTO `major_year` VALUES ('1','2009');
INSERT INTO `major_year` VALUES ('1','2010');
INSERT INTO `major_year` VALUES ('1','2011');
INSERT INTO `major_year` VALUES ('1','2012');
INSERT INTO `major_year` VALUES ('1','2013');
INSERT INTO `major_year` VALUES ('2','2009');
INSERT INTO `major_year` VALUES ('2','2010');
INSERT INTO `major_year` VALUES ('2','2011');
INSERT INTO `major_year` VALUES ('2','2012');
INSERT INTO `major_year` VALUES ('3','2009');
INSERT INTO `major_year` VALUES ('3','2010');
INSERT INTO `major_year` VALUES ('3','2011');
INSERT INTO `major_year` VALUES ('3','2012');
INSERT INTO `major_year` VALUES ('4','2009');
INSERT INTO `major_year` VALUES ('4','2010');
INSERT INTO `major_year` VALUES ('4','2011');
INSERT INTO `major_year` VALUES ('4','2012');
INSERT INTO `major_year` VALUES ('5','2009');
INSERT INTO `major_year` VALUES ('5','2010');
INSERT INTO `major_year` VALUES ('5','2011');
INSERT INTO `major_year` VALUES ('5','2012');
INSERT INTO `major_year` VALUES ('6','2009');
INSERT INTO `major_year` VALUES ('6','2010');
INSERT INTO `major_year` VALUES ('6','2011');
INSERT INTO `major_year` VALUES ('6','2012');
INSERT INTO `major_year` VALUES ('6','2013');
--
-- 表的结构 `menu`
-- 
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '菜单编号',
  `parentid` int(10) unsigned DEFAULT '0' COMMENT '父菜单编号',
  `name` varchar(32) NOT NULL COMMENT '菜单名称',
  `groupid` tinyint(3) unsigned DEFAULT NULL COMMENT '用户组',
  `data` varchar(40) DEFAULT NULL COMMENT '参数',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态',
  `model` varchar(64) NOT NULL COMMENT '模块名',
  `action` varchar(30) DEFAULT NULL COMMENT '操作',
  `seq` tinyint(3) unsigned DEFAULT '1' COMMENT '顺序',
  `remark` text COMMENT '备注',
  `operator` int(10) unsigned DEFAULT NULL COMMENT '操作者',
  `update_date` date DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `i_pk_menuid` (`parentid`),
  KEY `i_pk_type` (`groupid`)
) ENGINE=InnoDB AUTO_INCREMENT=168 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 COMMENT='菜单';

-- 
-- 导出`menu`表中的数据 `menu`
--
INSERT INTO `menu` VALUES ('1','0','课程管理','3','','1','Course','','1','','5','2012-11-04');
INSERT INTO `menu` VALUES ('2','0','论文及实践','3','','1','Index','','2','',NULL,NULL);
INSERT INTO `menu` VALUES ('3','0','个人资料','3','','1','ix','','6','',NULL,NULL);
INSERT INTO `menu` VALUES ('4','0','工作量管理','3','','1','Workload','index','4','','5','2012-10-25');
INSERT INTO `menu` VALUES ('5','0','考试安排','3','','1','Index','','3','',NULL,NULL);
INSERT INTO `menu` VALUES ('6','0','教务资料','3','','1','Notice','index','5','','5','2012-10-25');
INSERT INTO `menu` VALUES ('7','10','在授课程','3','','1','TeacherCourse','now_course','1','','3','2012-11-06');
INSERT INTO `menu` VALUES ('8','10','已授课程','3','','1','TeacherCourse','past_course','2','','3','2012-11-06');
INSERT INTO `menu` VALUES ('9','10','拟授课程','3','','1','TeacherCourse','future_course','3','','3','2012-11-06');
INSERT INTO `menu` VALUES ('10','1','课程信息','3','','1','Index','','1','',NULL,NULL);
INSERT INTO `menu` VALUES ('11','0','课程信息','4','','1','Index','','1','','4','2012-11-03');
INSERT INTO `menu` VALUES ('12','0','论文及实践','4','','1','Index','','2','',NULL,NULL);
INSERT INTO `menu` VALUES ('13','0','考试安排','4','','1','Index','','3','',NULL,NULL);
INSERT INTO `menu` VALUES ('14','0','教务资料','4','','1','Index','','4','',NULL,NULL);
INSERT INTO `menu` VALUES ('15','0','个人资料','4','','1','Index','','5','',NULL,NULL);
INSERT INTO `menu` VALUES ('16','0','班级管理','4','','1','Index','','6','',NULL,NULL);
INSERT INTO `menu` VALUES ('17','11','我的课程','4','','1','Index','','1','',NULL,NULL);
INSERT INTO `menu` VALUES ('18','17','在修课程','4','','1','StudentCourse','now_course','1','','3','2012-11-06');
INSERT INTO `menu` VALUES ('19','17','已修课程','4','','1','StudentCourse','past_course','2','','3','2012-11-06');
INSERT INTO `menu` VALUES ('20','17','拟修课程','4','','1','StudentCourse','future_course','3','','3','2012-11-06');
INSERT INTO `menu` VALUES ('21','0','系统管理','1','','1','Index','','1','','5','2012-10-27');
INSERT INTO `menu` VALUES ('22','0','用户管理','1','','1','Index','','2','',NULL,NULL);
INSERT INTO `menu` VALUES ('23','0','班级管理','2','','1','Index','','6','','9','2012-10-28');
INSERT INTO `menu` VALUES ('24','0','课程管理','2','','1','Index','','1','','4','2012-10-26');
INSERT INTO `menu` VALUES ('25','0','考试安排','2','','1','Index','','3','',NULL,NULL);
INSERT INTO `menu` VALUES ('26','0','工作量管理','2','','1','Index','','4','',NULL,NULL);
INSERT INTO `menu` VALUES ('27','0','教务支持','2','','1','Index','','6','',NULL,NULL);
INSERT INTO `menu` VALUES ('28','0','个人资料','1','','1','Index','','8','',NULL,NULL);
INSERT INTO `menu` VALUES ('29','21','系统管理','1','','1','Index','','1','',NULL,NULL);
INSERT INTO `menu` VALUES ('30','29','站点配置','1','','1','Index','add','1','',NULL,NULL);
INSERT INTO `menu` VALUES ('31','29','数据库管理','1','','1','Database','index','2','','5','2012-11-06');
INSERT INTO `menu` VALUES ('32','29','菜单管理','1','roleid=1','1','Menu','index','3','',NULL,'2012-11-03');
INSERT INTO `menu` VALUES ('33','29','数据字典维护','1','','1','DataDict','index','3','','1','2012-11-04');
INSERT INTO `menu` VALUES ('34','117','学生管理','2','','1','Index','','1','',NULL,NULL);
INSERT INTO `menu` VALUES ('35','117','教师管理','2','','1','Index','','2','',NULL,NULL);
INSERT INTO `menu` VALUES ('37','22','用户管理','1','','1','Index','','4','','1','2012-11-03');
INSERT INTO `menu` VALUES ('38','34','本科生','2','','1','Index','ddd','1','','4','2012-10-22');
INSERT INTO `menu` VALUES ('39','34','研究生','2','','1','Index','','2','',NULL,NULL);
INSERT INTO `menu` VALUES ('40','34','博士生','2','','1','Index','','3','',NULL,NULL);
INSERT INTO `menu` VALUES ('41','35','管理教师','2','','1','Index','','1','',NULL,NULL);
INSERT INTO `menu` VALUES ('42','35','职称审评','2','','1','Index','','2','',NULL,NULL);
INSERT INTO `menu` VALUES ('45','37','用户组管理','1','','1','Role','index','2','','1','2012-11-03');
INSERT INTO `menu` VALUES ('46','37','权限节点管理','1','','1','Node','index','3','','1','2012-11-03');
INSERT INTO `menu` VALUES ('47','23','班级管理','2','','1','Index','','1','','4','2012-10-22');
INSERT INTO `menu` VALUES ('48','47','管理班级','2','','1','in','','1','','4','2012-10-22');
INSERT INTO `menu` VALUES ('49','47','奖学金评定','2','','1','in','','2','','4','2012-10-22');
INSERT INTO `menu` VALUES ('50','47','助学金评定','2','','1','in','','3','','4','2012-10-22');
INSERT INTO `menu` VALUES ('51','47','其它资助金','2','','1','in','','4','','4','2012-10-22');
INSERT INTO `menu` VALUES ('55','24','排课管理','2','','1','in','','2','',NULL,NULL);
INSERT INTO `menu` VALUES ('56','55','理论和实践课','2','','1','ArrangeCourse','','1','',NULL,NULL);
INSERT INTO `menu` VALUES ('57','132','学年论文','2','category=1&result=1','1','ThesisTitle','','2','','9','2012-11-06');
INSERT INTO `menu` VALUES ('58','132','毕业论文','2','category=2&result=1','1','ThesisTitle','','3','','9','2012-11-06');
INSERT INTO `menu` VALUES ('59','55','实习安排','2','','1','ThesisTitle','','4','','5','2012-10-25');
INSERT INTO `menu` VALUES ('60','25','考试安排','2','','1','Index','','1','',NULL,NULL);
INSERT INTO `menu` VALUES ('61','60','普通考试','2','','1','Index','','1','',NULL,NULL);
INSERT INTO `menu` VALUES ('62','60','成人考试','2','','1','Index','','2','',NULL,NULL);
INSERT INTO `menu` VALUES ('63','60','等级考试','2','','1','Index','','3','',NULL,NULL);
INSERT INTO `menu` VALUES ('64','60','补考','2','','1','Index','','4','',NULL,NULL);
INSERT INTO `menu` VALUES ('65','60','监考','2','','1','Exam','index','5','','5','2012-10-25');
INSERT INTO `menu` VALUES ('66','26','教学工作量','2','','1','Index','','1','',NULL,NULL);
INSERT INTO `menu` VALUES ('67','66','工作量参数','2','','1','Index','','1','',NULL,NULL);
INSERT INTO `menu` VALUES ('68','66','查看教师工作量','2','','1','Index','','2','',NULL,NULL);
INSERT INTO `menu` VALUES ('69','27','专业管理','2','','1','Index','','1','',NULL,NULL);
INSERT INTO `menu` VALUES ('70','27','院系公告','2','','1','Index','','2','',NULL,NULL);
INSERT INTO `menu` VALUES ('71','27','师资队伍','2','','1','Index','','3','',NULL,NULL);
INSERT INTO `menu` VALUES ('72','27','培养方案','2','','1','Index','','4','',NULL,NULL);
INSERT INTO `menu` VALUES ('73','28','个人资料','1','','1','tTeacher','info','1','','1','2012-11-04');
INSERT INTO `menu` VALUES ('74','69','管理专业','2','','1','Index','','1','',NULL,NULL);
INSERT INTO `menu` VALUES ('75','70','学院快讯','2','','1','Index','','1','',NULL,NULL);
INSERT INTO `menu` VALUES ('76','70','学术活动','2','','1','Index','','2','',NULL,NULL);
INSERT INTO `menu` VALUES ('77','71','教师规模','2','','1','Index','','1','',NULL,NULL);
INSERT INTO `menu` VALUES ('78','71','部门机构','2','','1','Index','','2','',NULL,NULL);
INSERT INTO `menu` VALUES ('79','71','工资福利','2','','1','Index','','3','',NULL,NULL);
INSERT INTO `menu` VALUES ('80','71','职称审评','2','','1','Index','','4','',NULL,NULL);
INSERT INTO `menu` VALUES ('81','71','进修访学','2','','1','Index','','5','',NULL,NULL);
INSERT INTO `menu` VALUES ('82','73','修改密码','1','','1','Index','','1','',NULL,NULL);
INSERT INTO `menu` VALUES ('83','2','论文管理','3','','1','Index','','1','','4','2012-10-26');
INSERT INTO `menu` VALUES ('84','83','学年论文','3','category=1&result=2','1','ThesisTitle','index','1','','5','2012-11-06');
INSERT INTO `menu` VALUES ('85','83','毕业论文','3','category=2&result=2','1','ThesisTitle','index','2','','5','2012-11-06');
INSERT INTO `menu` VALUES ('86','83','课堂论文','3','category=3','1','ThesisTitle','index','3','','4','2012-10-26');
INSERT INTO `menu` VALUES ('87','5','考试安排','3','','1','Exam','index','1','','5','2012-10-25');
INSERT INTO `menu` VALUES ('88','87','普通考试','3','','1','Exam','index','1','','5','2012-10-25');
INSERT INTO `menu` VALUES ('89','87','补考','3','','1','Exam','index','2','','5','2012-10-25');
INSERT INTO `menu` VALUES ('90','87','监考','3','','1','Exam','index','3','','5','2012-10-25');
INSERT INTO `menu` VALUES ('91','4','工作量管理','3','','1','Workload','index','1','','5','2012-10-25');
INSERT INTO `menu` VALUES ('92','91','工作量','3','','1','Workload','index','1','','5','2012-10-25');
INSERT INTO `menu` VALUES ('93','91','教学工作量','3','','1','Workload','index','2','','5','2012-10-25');
INSERT INTO `menu` VALUES ('94','91','监考工作量','3','','1','Workload','index','3','','5','2012-10-25');
INSERT INTO `menu` VALUES ('95','91','其它工作量','3','','1','Workload','index','4','','5','2012-10-25');
INSERT INTO `menu` VALUES ('96','3','个人资料','3','','1','tTeacher','info','1','','5','2012-10-25');
INSERT INTO `menu` VALUES ('97','96','基本资料','3','','1','tTeacher','info','1','','5','2012-10-25');
INSERT INTO `menu` VALUES ('98','96','修改密码','3','','1','tTeacher','pwd','2','','5','2012-10-25');
INSERT INTO `menu` VALUES ('99','96','个人简历','3','','1','tTeacher','info','3','','5','2012-10-26');
INSERT INTO `menu` VALUES ('100','96','在职信息','3','','1','tTeacher','info','3','','5','2012-10-26');
INSERT INTO `menu` VALUES ('101','12','论文及实践','4','','1','Index','','1','',NULL,NULL);
INSERT INTO `menu` VALUES ('102','101','学年论文','4','category=1','1','ThesisTitle','index','1','','5','2012-10-27');
INSERT INTO `menu` VALUES ('103','101','毕业论文','4','category=2','1','ThesisTitle','index','2','','9','2012-10-28');
INSERT INTO `menu` VALUES ('104','101','课堂论文','4','type=3','1','ThesisTitle','','3','','4','2012-10-26');
INSERT INTO `menu` VALUES ('105','13','考试安排','4','','1','Index','','1','',NULL,NULL);
INSERT INTO `menu` VALUES ('106','105','普通考试','4','','1','Index','','1','',NULL,NULL);
INSERT INTO `menu` VALUES ('107','105','等级考试','4','','1','Index','','2','',NULL,NULL);
INSERT INTO `menu` VALUES ('108','105','补考','4','','1','Index','','3','',NULL,NULL);
INSERT INTO `menu` VALUES ('109','16','我的班级','4','','1','Index','','1','',NULL,NULL);
INSERT INTO `menu` VALUES ('110','109','班级信息','4','','1','Index','','1','',NULL,NULL);
INSERT INTO `menu` VALUES ('111','109','班级动态','4','','1','Index','','2','',NULL,NULL);
INSERT INTO `menu` VALUES ('112','109','奖学金评定','4','','1','Index','','3','',NULL,NULL);
INSERT INTO `menu` VALUES ('113','109','助学金评定','4','','1','Index','','4','',NULL,NULL);
INSERT INTO `menu` VALUES ('114','109','其它资助','4','','1','Index','','4','',NULL,NULL);
INSERT INTO `menu` VALUES ('115','32','添加菜单','1','roleid=1','1','Menu','add','1','',NULL,'2012-11-03');
INSERT INTO `menu` VALUES ('116','46','添加权限节点','1','','1','Node','add','1','',NULL,NULL);
INSERT INTO `menu` VALUES ('117','0','用户管理','2','','1','Index','','5','','4','2012-10-22');
INSERT INTO `menu` VALUES ('118','0','个人资料','2','','1','tTeacher','info','7','','5','2012-10-28');
INSERT INTO `menu` VALUES ('119','118','个人资料','2','','1','tTeacher','info','1','','9','2012-10-28');
INSERT INTO `menu` VALUES ('120','119','修改密码','2','','1','TTeacher','pwd','2','','9','2012-10-28');
INSERT INTO `menu` VALUES ('121','6','通知公告','3','','1','Notice','index','1','','5','2012-10-25');
INSERT INTO `menu` VALUES ('122','121','院系公告','3','','1','Notice','index','1','','5','2012-10-25');
INSERT INTO `menu` VALUES ('123','84','论文指导','3','category=1','1','Thesis','index','1','','5','2012-11-06');
INSERT INTO `menu` VALUES ('124','85','论文指导','3','category=2','1','Thesis','','1','','4','2012-10-26');
INSERT INTO `menu` VALUES ('125','86','论文指导','3','','1','Thesis','','1','','5','2012-10-25');
INSERT INTO `menu` VALUES ('128','102','选修论文','4','category=1','1','ThesisTitle','select','1','','5','2012-10-27');
INSERT INTO `menu` VALUES ('129','103','选修论文','4','category=2','1','ThesisTitle','select','1','','9','2012-10-28');
INSERT INTO `menu` VALUES ('130','104','论文及实践','4','','1','ThesisTitle','add','1','','5','2012-10-26');
INSERT INTO `menu` VALUES ('131','0','论文及实践','2','','1','Index','','1','','4','2012-10-26');
INSERT INTO `menu` VALUES ('132','131','论文管理','2','','1','ThesisTitle','','1','','5','2012-10-27');
INSERT INTO `menu` VALUES ('133','131','实习实践','2','','1','Thies','','1','','4','2012-10-26');
INSERT INTO `menu` VALUES ('134','133','实习安排','2','','1','In','','1','','4','2012-10-26');
INSERT INTO `menu` VALUES ('135','133','学科竞赛','2','','1','In','','1','','4','2012-10-26');
INSERT INTO `menu` VALUES ('136','2','实习实践','3','','1','Index','','1','','4','2012-10-26');
INSERT INTO `menu` VALUES ('137','136','实习管理','3','','1','Index','','1','','4','2012-10-26');
INSERT INTO `menu` VALUES ('138','136','学科竞赛','3','','1','Index','','2','','4','2012-10-26');
INSERT INTO `menu` VALUES ('139','84','增加选题','3','category=1','1','ThesisTitle','add','1','','5','2012-10-27');
INSERT INTO `menu` VALUES ('140','85','增加选题','3','category=2','1','ThesisTitle','add','2','','5','2012-10-27');
INSERT INTO `menu` VALUES ('146','15','个人资料','4','','1','Student','edit','1','dsf','5','2012-10-27');
INSERT INTO `menu` VALUES ('147','146','基本资料','4','','1','Student','edit','1','','5','2012-10-27');
INSERT INTO `menu` VALUES ('148','146','修改密码','4','','1','Student','','2','','5','2012-10-27');
INSERT INTO `menu` VALUES ('149','119','个人资料','2','','1','tTeacher','info','1','','9','2012-10-28');
INSERT INTO `menu` VALUES ('150','45','添加用户组','1','','1','Role','add','1','','1','2012-11-02');
INSERT INTO `menu` VALUES ('151','0','班级管理','11','','1','Classroom','','4','','5','2012-11-03');
INSERT INTO `menu` VALUES ('152','109','奖学金申报','5','','1','Index','','1','',NULL,'2012-11-03');
INSERT INTO `menu` VALUES ('155','37','用户资料管理','1','','1','User','index','1','','1','2012-11-03');
INSERT INTO `menu` VALUES ('156','155','添加用户','1','','1','User','add','1','','1','2012-11-03');
INSERT INTO `menu` VALUES ('157','1','课程管理','10','','1','Course','now_course_manage','2','','5','2012-11-05');
INSERT INTO `menu` VALUES ('160','157','课程过渡','10','','1','Course','now_course_manage','1','','5','2012-11-05');
INSERT INTO `menu` VALUES ('161','151','我的班级','11','','1','Myclass','','1','','5','2012-11-04');
INSERT INTO `menu` VALUES ('162','161','奖学金评定','11','','1','Index','','1','','5','2012-11-04');
INSERT INTO `menu` VALUES ('163','157','课程管理','10','','1','Course','index','2','','5','2012-11-04');
INSERT INTO `menu` VALUES ('164','160','特殊设置','10','','1','Course','transition','1','','5','2012-11-05');
INSERT INTO `menu` VALUES ('165','24','课程管理','2','','1','Course','index','1','','5','2012-11-04');
INSERT INTO `menu` VALUES ('166','165','课程管理','2','','1','CourseManage','index','1','','5','2012-11-06');
INSERT INTO `menu` VALUES ('167','166','增设课程','2','','1','CourseManage','add','1','','5','2012-11-06');
--
-- 表的结构 `node`
-- 
DROP TABLE IF EXISTS `node`;
CREATE TABLE `node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '节点编号',
  `name` varchar(20) NOT NULL COMMENT '节点名称',
  `title` varchar(50) DEFAULT NULL COMMENT '标题',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `sort` smallint(6) unsigned DEFAULT NULL COMMENT '排序',
  `pid` smallint(6) unsigned NOT NULL COMMENT '父节点',
  `level` tinyint(1) unsigned NOT NULL COMMENT '级别',
  `groupid` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 COMMENT='节点';

-- 
-- 导出`node`表中的数据 `node`
--
INSERT INTO `node` VALUES ('5','index','全局操作','0','',NULL,'0','1','0');
--
-- 表的结构 `notice`
-- 
DROP TABLE IF EXISTS `notice`;
CREATE TABLE `notice` (
  `noticeid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '通知编号',
  `orgid` int(10) unsigned DEFAULT NULL COMMENT '发布单位',
  `source` varchar(64) DEFAULT NULL COMMENT '来源',
  `title` varchar(64) NOT NULL COMMENT '公告标题',
  `content` text NOT NULL COMMENT '公告内容',
  `type` tinyint(3) unsigned NOT NULL COMMENT '公告类型',
  `category` tinyint(3) unsigned NOT NULL COMMENT '公告对象',
  `importance` tinyint(3) unsigned DEFAULT NULL COMMENT '重要程度',
  `operator` int(11) DEFAULT NULL COMMENT '操作员',
  `update_date` date DEFAULT NULL COMMENT '操作日期',
  PRIMARY KEY (`noticeid`),
  KEY `FK_notice_organize_orgid` (`orgid`),
  CONSTRAINT `FK_notice_organize_orgid` FOREIGN KEY (`orgid`) REFERENCES `organize` (`orgid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='通知公告';

-- 
-- 导出`notice`表中的数据 `notice`
--
INSERT INTO `notice` VALUES ('1','2','学习部','奖学金','奖学金评定开始了，请同学们尽快提交申请','2','2','2',NULL,'2010-10-09');
INSERT INTO `notice` VALUES ('2','2','学习部','奖学金评定','奖学金评定已开始，请教师们做好评定工作','1','2','1',NULL,'2012-10-17');
--
-- 表的结构 `organize`
-- 
DROP TABLE IF EXISTS `organize`;
CREATE TABLE `organize` (
  `orgid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '机构代码',
  `name` varchar(128) NOT NULL COMMENT '机构名称',
  `level` tinyint(4) unsigned DEFAULT NULL COMMENT '级别',
  `property` tinyint(3) unsigned DEFAULT NULL COMMENT '机构性质',
  `high_org` int(10) unsigned DEFAULT NULL COMMENT '上属机构代码',
  `address` varchar(512) DEFAULT NULL COMMENT '地址',
  `telephone` varchar(16) DEFAULT NULL COMMENT '联系电话',
  `email` varchar(32) DEFAULT NULL COMMENT '电子邮箱',
  `operator` int(10) unsigned DEFAULT NULL COMMENT '操作员',
  `update_date` date DEFAULT NULL COMMENT '操作日期',
  PRIMARY KEY (`orgid`),
  KEY `i_pk_level` (`level`),
  KEY `i_pk_high_org` (`high_org`),
  CONSTRAINT `FK_organize_organize_high_org` FOREIGN KEY (`high_org`) REFERENCES `organize` (`orgid`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='机构';

-- 
-- 导出`organize`表中的数据 `organize`
--
INSERT INTO `organize` VALUES ('1','西北师范大学','2','2','1','',NULL,NULL,NULL,'2012-10-10');
INSERT INTO `organize` VALUES ('2','计算机科学与工程学院','3','1','1',NULL,NULL,NULL,NULL,NULL);
INSERT INTO `organize` VALUES ('3','计算机科学与技术系','4','1','2',NULL,NULL,NULL,NULL,NULL);
INSERT INTO `organize` VALUES ('4','物联网工程系','4','1','2',NULL,NULL,NULL,NULL,NULL);
INSERT INTO `organize` VALUES ('5','计算机基础教学部','4','2','2',NULL,NULL,NULL,NULL,NULL);
INSERT INTO `organize` VALUES ('6','实验中心','4','1','2',NULL,NULL,NULL,NULL,NULL);
INSERT INTO `organize` VALUES ('7','网络中心','4','1','2',NULL,NULL,NULL,NULL,NULL);
INSERT INTO `organize` VALUES ('8','现代远程教育中心','4','1','2',NULL,NULL,NULL,NULL,NULL);
INSERT INTO `organize` VALUES ('9','计算机应用研究所','4','2','2',NULL,NULL,NULL,NULL,NULL);
INSERT INTO `organize` VALUES ('10','信息安全研究所','4','2','2',NULL,NULL,NULL,NULL,NULL);
--
-- 表的结构 `orgduty`
-- 
DROP TABLE IF EXISTS `orgduty`;
CREATE TABLE `orgduty` (
  `userid` int(10) unsigned NOT NULL COMMENT '教工代码',
  `orgid` int(10) unsigned NOT NULL COMMENT '机构代码',
  `name` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '职务名称',
  `level` tinyint(3) unsigned DEFAULT NULL COMMENT '级别',
  `office` varchar(64) DEFAULT NULL COMMENT '办公室',
  `operator` int(11) DEFAULT NULL COMMENT '操作员',
  `update_date` date DEFAULT NULL COMMENT '操作日期',
  PRIMARY KEY (`userid`,`orgid`,`name`),
  KEY `FK_orgduty_organize_orgid` (`orgid`),
  CONSTRAINT `FK_orgduty_organize_orgid` FOREIGN KEY (`orgid`) REFERENCES `organize` (`orgid`),
  CONSTRAINT `FK_orgduty_teacher_userid` FOREIGN KEY (`userid`) REFERENCES `teacher` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='机构职务';

-- 
-- 导出`orgduty`表中的数据 `orgduty`
--
INSERT INTO `orgduty` VALUES ('5','2','2','1','',NULL,'2012-10-12');
INSERT INTO `orgduty` VALUES ('5','3','2','2','',NULL,NULL);
INSERT INTO `orgduty` VALUES ('6','1','1','1','',NULL,NULL);
INSERT INTO `orgduty` VALUES ('6','4','1','1','',NULL,NULL);
INSERT INTO `orgduty` VALUES ('7','1','1','1','',NULL,NULL);
INSERT INTO `orgduty` VALUES ('7','4','1','1','',NULL,'2012-10-11');
--
-- 表的结构 `role`
-- 
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色编号',
  `name` varchar(20) NOT NULL COMMENT '角色名称',
  `pid` smallint(6) DEFAULT NULL COMMENT '父编号',
  `status` tinyint(1) unsigned DEFAULT NULL COMMENT '状态',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 COMMENT='角色';

-- 
-- 导出`role`表中的数据 `role`
--
INSERT INTO `role` VALUES ('1','系统管理员','0','1',NULL);
INSERT INTO `role` VALUES ('2','教务管理员','0','1',NULL);
INSERT INTO `role` VALUES ('3','教师','0','1',NULL);
INSERT INTO `role` VALUES ('4','学生','0','1',NULL);
INSERT INTO `role` VALUES ('5','班长','4','1','班长');
INSERT INTO `role` VALUES ('6','学习委员','4','1','学习委员');
INSERT INTO `role` VALUES ('7','院长','3','1','');
INSERT INTO `role` VALUES ('8','副院长','3','1','');
INSERT INTO `role` VALUES ('10','系主任','3','1','');
INSERT INTO `role` VALUES ('11','班主任','3','1','');
--
-- 表的结构 `role_user`
-- 
DROP TABLE IF EXISTS `role_user`;
CREATE TABLE `role_user` (
  `role_id` mediumint(9) unsigned DEFAULT NULL COMMENT '角色',
  `user_id` char(32) DEFAULT NULL COMMENT '用户',
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 COMMENT='用户角色';

-- 
-- 导出`role_user`表中的数据 `role_user`
--
INSERT INTO `role_user` VALUES ('4','1');
INSERT INTO `role_user` VALUES ('4','2');
INSERT INTO `role_user` VALUES ('4','3');
INSERT INTO `role_user` VALUES ('1','4');
INSERT INTO `role_user` VALUES ('3','5');
INSERT INTO `role_user` VALUES ('3','6');
INSERT INTO `role_user` VALUES ('3','7');
INSERT INTO `role_user` VALUES ('2','9');
INSERT INTO `role_user` VALUES ('11','10');
INSERT INTO `role_user` VALUES ('10','11');
INSERT INTO `role_user` VALUES ('1','12');
INSERT INTO `role_user` VALUES ('6','13');
INSERT INTO `role_user` VALUES ('4','15');
INSERT INTO `role_user` VALUES ('4','16');
INSERT INTO `role_user` VALUES ('4','18');
INSERT INTO `role_user` VALUES ('4','22');
INSERT INTO `role_user` VALUES ('7','23');
INSERT INTO `role_user` VALUES ('7','24');
--
-- 表的结构 `score`
-- 
DROP TABLE IF EXISTS `score`;
CREATE TABLE `score` (
  `userid` int(10) unsigned NOT NULL COMMENT '学号',
  `assessmentid` int(10) unsigned NOT NULL COMMENT '考核代码',
  `mark` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '考核成绩',
  `operator` int(10) unsigned DEFAULT NULL COMMENT '操作员',
  `update_date` date DEFAULT NULL COMMENT '操作日期',
  PRIMARY KEY (`userid`,`assessmentid`),
  KEY `FK_score_assessment_assessmentid` (`assessmentid`),
  CONSTRAINT `FK_score_assessment_assessmentid` FOREIGN KEY (`assessmentid`) REFERENCES `assessment` (`assessmentid`),
  CONSTRAINT `FK_score_student_userid` FOREIGN KEY (`userid`) REFERENCES `student` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='成绩';

-- 
-- 导出`score`表中的数据 `score`
--
INSERT INTO `score` VALUES ('1','10','1',NULL,NULL);
INSERT INTO `score` VALUES ('1','11','2',NULL,NULL);
INSERT INTO `score` VALUES ('1','12','3',NULL,NULL);
INSERT INTO `score` VALUES ('1','13','4',NULL,NULL);
INSERT INTO `score` VALUES ('1','14','5',NULL,NULL);
INSERT INTO `score` VALUES ('1','15','6',NULL,NULL);
INSERT INTO `score` VALUES ('1','16','7',NULL,NULL);
INSERT INTO `score` VALUES ('1','17','100',NULL,NULL);
INSERT INTO `score` VALUES ('1','18','100',NULL,NULL);
INSERT INTO `score` VALUES ('1','19','100',NULL,NULL);
INSERT INTO `score` VALUES ('1','20','100',NULL,NULL);
INSERT INTO `score` VALUES ('1','34','5',NULL,NULL);
INSERT INTO `score` VALUES ('1','35','6',NULL,NULL);
INSERT INTO `score` VALUES ('1','36','6',NULL,NULL);
INSERT INTO `score` VALUES ('1','37','6',NULL,NULL);
INSERT INTO `score` VALUES ('2','10','1',NULL,NULL);
INSERT INTO `score` VALUES ('2','11','2',NULL,NULL);
INSERT INTO `score` VALUES ('2','12','2',NULL,NULL);
INSERT INTO `score` VALUES ('2','13','2',NULL,NULL);
INSERT INTO `score` VALUES ('2','14','2',NULL,NULL);
INSERT INTO `score` VALUES ('2','15','2',NULL,NULL);
INSERT INTO `score` VALUES ('2','16','2',NULL,NULL);
INSERT INTO `score` VALUES ('2','17','2',NULL,NULL);
INSERT INTO `score` VALUES ('2','18','2',NULL,NULL);
INSERT INTO `score` VALUES ('2','19','2',NULL,NULL);
INSERT INTO `score` VALUES ('2','20','2',NULL,NULL);
INSERT INTO `score` VALUES ('2','34','4',NULL,NULL);
INSERT INTO `score` VALUES ('2','35','4',NULL,NULL);
INSERT INTO `score` VALUES ('2','36','4',NULL,NULL);
INSERT INTO `score` VALUES ('2','37','4',NULL,NULL);
INSERT INTO `score` VALUES ('3','10','50',NULL,NULL);
INSERT INTO `score` VALUES ('3','11','3',NULL,NULL);
INSERT INTO `score` VALUES ('3','12','4',NULL,NULL);
INSERT INTO `score` VALUES ('3','13','5',NULL,NULL);
INSERT INTO `score` VALUES ('3','14','77',NULL,NULL);
INSERT INTO `score` VALUES ('3','15','56',NULL,NULL);
INSERT INTO `score` VALUES ('3','16','88',NULL,NULL);
INSERT INTO `score` VALUES ('3','17','66',NULL,NULL);
INSERT INTO `score` VALUES ('3','18','77',NULL,NULL);
INSERT INTO `score` VALUES ('3','19','66',NULL,NULL);
INSERT INTO `score` VALUES ('3','20','77',NULL,NULL);
INSERT INTO `score` VALUES ('3','34','4',NULL,NULL);
INSERT INTO `score` VALUES ('3','35','4',NULL,NULL);
INSERT INTO `score` VALUES ('3','36','4',NULL,NULL);
INSERT INTO `score` VALUES ('3','37','4',NULL,NULL);
--
-- 表的结构 `select_course`
-- 
DROP TABLE IF EXISTS `select_course`;
CREATE TABLE `select_course` (
  `selectid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '选课编号',
  `userid` int(10) unsigned NOT NULL COMMENT '学生编号',
  `teachid` int(10) unsigned NOT NULL COMMENT '授课编号',
  `regular_grad` float unsigned DEFAULT NULL COMMENT '平时成绩',
  `final_grad` float unsigned DEFAULT NULL COMMENT '期末成绩',
  `overall_grad` float unsigned DEFAULT NULL COMMENT '总评成绩',
  `operator` int(11) DEFAULT NULL COMMENT '操作员',
  `update_date` date DEFAULT NULL COMMENT '操作日期',
  PRIMARY KEY (`selectid`),
  UNIQUE KEY `userid` (`userid`,`teachid`),
  KEY `i_pk_teachid` (`teachid`),
  KEY `i_pk_userid` (`userid`),
  CONSTRAINT `FK_select_course_student_userid` FOREIGN KEY (`userid`) REFERENCES `student` (`userid`),
  CONSTRAINT `FK_select_course_teach_task_teachid` FOREIGN KEY (`teachid`) REFERENCES `teach_task` (`teachid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='选课';

-- 
-- 导出`select_course`表中的数据 `select_course`
--
INSERT INTO `select_course` VALUES ('1','1','1',NULL,NULL,NULL,NULL,NULL);
INSERT INTO `select_course` VALUES ('2','2','1',NULL,NULL,NULL,NULL,NULL);
INSERT INTO `select_course` VALUES ('3','3','1',NULL,NULL,NULL,NULL,NULL);
INSERT INTO `select_course` VALUES ('4','3','97',NULL,NULL,NULL,NULL,NULL);
INSERT INTO `select_course` VALUES ('5','3','2',NULL,NULL,NULL,NULL,NULL);
INSERT INTO `select_course` VALUES ('6','3','98',NULL,NULL,NULL,NULL,NULL);
INSERT INTO `select_course` VALUES ('7','2','98',NULL,NULL,NULL,NULL,NULL);
INSERT INTO `select_course` VALUES ('8','1','98',NULL,NULL,NULL,NULL,NULL);
--
-- 表的结构 `student`
-- 
DROP TABLE IF EXISTS `student`;
CREATE TABLE `student` (
  `userid` int(10) unsigned NOT NULL COMMENT '学号',
  `name` varchar(32) NOT NULL COMMENT '姓名',
  `gender` tinyint(3) unsigned NOT NULL DEFAULT '3' COMMENT '性别',
  `idno` varchar(32) DEFAULT NULL COMMENT '身份证号',
  `Politics_status` tinyint(3) unsigned DEFAULT NULL COMMENT '政治面貌',
  `classid` int(10) unsigned NOT NULL COMMENT '所属班级',
  `majorid` int(10) unsigned NOT NULL COMMENT '所属专业',
  `duty` tinyint(3) unsigned DEFAULT NULL COMMENT '学生职务',
  `admissiondate` year(4) NOT NULL COMMENT '入学年',
  `qq` varchar(16) DEFAULT NULL COMMENT ' QQ号码',
  `email` varchar(32) DEFAULT NULL COMMENT '电子邮件',
  `telephone` varchar(16) DEFAULT NULL COMMENT '联系电话',
  `introduction` varchar(512) DEFAULT NULL COMMENT '个人简历',
  `operator` int(10) unsigned DEFAULT NULL COMMENT '操作员',
  `update_date` date DEFAULT NULL COMMENT '操作日期',
  PRIMARY KEY (`userid`),
  KEY `FK_student_major_majorid` (`majorid`),
  KEY `ID_classid` (`classid`),
  CONSTRAINT `fk_student_class_classid` FOREIGN KEY (`classid`) REFERENCES `class` (`classid`),
  CONSTRAINT `FK_student_login_userid` FOREIGN KEY (`userid`) REFERENCES `login` (`userid`),
  CONSTRAINT `FK_student_major_majorid` FOREIGN KEY (`majorid`) REFERENCES `major` (`majorid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='学生';

-- 
-- 导出`student`表中的数据 `student`
--
INSERT INTO `student` VALUES ('1','李小青','2','622921199105017589','2','1','1','0','2009','1043977511','xiaoqing_nlp@qq.com','15002530863',NULL,NULL,'2012-10-11');
INSERT INTO `student` VALUES ('2','李磊','1','620522199007153117','2','1','1','0','2009','605351006','lilei.zh@qq.com','13893423589',NULL,NULL,NULL);
INSERT INTO `student` VALUES ('3','张彦升','1',NULL,NULL,'1','1',NULL,'2009',NULL,NULL,NULL,NULL,NULL,NULL);
--
-- 表的结构 `teach_class`
-- 
DROP TABLE IF EXISTS `teach_class`;
CREATE TABLE `teach_class` (
  `teachid` int(10) unsigned NOT NULL COMMENT '授课代码',
  `classid` int(10) unsigned NOT NULL COMMENT '授课班级',
  `operator` int(10) unsigned DEFAULT NULL COMMENT '操作员',
  `update_data` date DEFAULT NULL COMMENT '操作日期',
  PRIMARY KEY (`teachid`,`classid`),
  KEY `i_pk_classid` (`classid`),
  CONSTRAINT `FK_teach_class_class_classid` FOREIGN KEY (`classid`) REFERENCES `class` (`classid`),
  CONSTRAINT `FK_teach_class_teach_task_teachid` FOREIGN KEY (`teachid`) REFERENCES `teach_task` (`teachid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='授课班级';

-- 
-- 导出`teach_class`表中的数据 `teach_class`
--
INSERT INTO `teach_class` VALUES ('96','3',NULL,NULL);
INSERT INTO `teach_class` VALUES ('104','1',NULL,NULL);
INSERT INTO `teach_class` VALUES ('105','1',NULL,NULL);
INSERT INTO `teach_class` VALUES ('122','3',NULL,NULL);
INSERT INTO `teach_class` VALUES ('136','22',NULL,NULL);
INSERT INTO `teach_class` VALUES ('137','1',NULL,NULL);
INSERT INTO `teach_class` VALUES ('141','4',NULL,NULL);
INSERT INTO `teach_class` VALUES ('141','12',NULL,NULL);
INSERT INTO `teach_class` VALUES ('141','30',NULL,NULL);
INSERT INTO `teach_class` VALUES ('142','4',NULL,NULL);
--
-- 表的结构 `teach_plan`
-- 
DROP TABLE IF EXISTS `teach_plan`;
CREATE TABLE `teach_plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `teachid` int(10) unsigned NOT NULL COMMENT '授课代码',
  `seq` int(10) unsigned NOT NULL COMMENT '教学计划序号',
  `plan_title` varchar(128) NOT NULL COMMENT '计划标题',
  `week_time` tinyint(3) unsigned NOT NULL COMMENT '周学时',
  `plan_content` text NOT NULL COMMENT '计划内容',
  `homework` text COMMENT '课后作业',
  `homework_deadline` date DEFAULT NULL COMMENT '作业截至时间',
  `week_num` tinyint(2) DEFAULT NULL COMMENT '周次',
  `teach_method` varchar(100) DEFAULT NULL COMMENT '教学形式与手段',
  `publish_time` date DEFAULT NULL COMMENT '发布时间',
  `operator` int(10) unsigned DEFAULT NULL COMMENT '操作员',
  `update_date` date DEFAULT NULL COMMENT '操作日期',
  PRIMARY KEY (`id`),
  KEY `FK_teach_plan_teach_task` (`teachid`),
  CONSTRAINT `FK_teach_plan_teach_task` FOREIGN KEY (`teachid`) REFERENCES `teach_task` (`teachid`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='教学计划';

-- 
-- 导出`teach_plan`表中的数据 `teach_plan`
--
INSERT INTO `teach_plan` VALUES ('1','1','1','第一章','1','数据结构的原则\r\n抽象数据类型和数据结构\r\n问题、算法、程序\r\n深入学习导读\r\n习题','','2012-10-08','1','iii','2012-11-09','5','2012-11-06');
INSERT INTO `teach_plan` VALUES ('2','1','2','第二章','2','集合和关系\r\n常用数学用语\r\n对数\r\n递归\r\n级数求和与递归\r\n数学证明方法\r\n评估\r\n深入学习导读\r\n','','2012-10-16','2','222','2012-11-15','5','2012-11-06');
INSERT INTO `teach_plan` VALUES ('3','1','3','第三章','3','概述\r\n最佳、最差和平均的情况\r\n换一台更快的计算机还是换一种更快的算法\r\n渐进分析\r\n程序运行时间的计算\r\n问题的分析\r\n容易混淆的概念\r\n多参数问题\r\n空间代价\r\n实际操作中的一些因素\r\n深入学习导读','','2012-10-08','0','sdf','2012-11-21','5','2012-11-06');
INSERT INTO `teach_plan` VALUES ('4','1','4','第四章','5','线性表\r\n字典ADT\r\n栈\r\n队列\r\n深入学习导读','','2012-10-08','0','','2012-11-29','5','2012-11-06');
INSERT INTO `teach_plan` VALUES ('5','1','5','第五章','6','定义及主要特性\r\n周游二叉树\r\n二叉树的实现\r\n二叉查找树\r\n队与优先队列\r\nHuffman编码树\r\n深入学习导读\r\n','','2012-10-15','0','','2012-11-19','5','2012-11-06');
INSERT INTO `teach_plan` VALUES ('6','1','6','第六章','4','树的定义\r\n父指针表示法\r\n树的实现\r\nK叉数\r\n树的顺序表示法\r\n深入学习导读','','2012-10-02','4','','2012-11-13','5','2012-11-06');
INSERT INTO `teach_plan` VALUES ('7','1','7','第七章','6','插入排序\r\n气泡排序\r\n选择排序\r\nshell排序\r\n快速排序\r\n归并排序\r\n堆排序\r\n','','0000-00-00','6','sdff','2012-10-11','5','2012-11-06');
INSERT INTO `teach_plan` VALUES ('8','1','8','第八章','2','主存存储器和辅存存储器\r\n磁盘\r\n外部排序','','2012-10-08','0','sdfsdf','2012-11-12','5','2012-11-06');
INSERT INTO `teach_plan` VALUES ('9','1','9','第九章','2','检索已排序的数组\r\n自组织线性表\r\n集合的检索\r\n散列方法\r\n','','2012-10-17','6','dsfsd','2012-12-26','5','2012-11-06');
INSERT INTO `teach_plan` VALUES ('10','1','10','第十章','3','索引技术\r\n线性索引\r\nISAM\r\n属性索引\r\n','','0000-00-00','5','56','2012-12-29','5','2012-11-06');
INSERT INTO `teach_plan` VALUES ('11','1','11','图','3','术语和表示法\r\n图的实现\r\n图的周游\r\n最短路径问题\r\n最小支撑树','','2012-10-17','12','将授','2013-01-18','5','2012-11-06');
INSERT INTO `teach_plan` VALUES ('12','1','12','线性表和数组高级技术','1','跳跃表\r\n广义表\r\n矩阵的表示方法\r\n存储管理','','2012-10-25','10','讨论','2012-12-27','5','2012-11-06');
INSERT INTO `teach_plan` VALUES ('20','98','1','','4','斯蒂芬森的',NULL,NULL,'1','多媒体授课','2012-11-06','5','2012-11-06');
INSERT INTO `teach_plan` VALUES ('21','98','2','','0','	$(function() {\r\n		$(\"#homework_deadline_{$plan[\'seq\']}\").datepicker();\r\n	});\r\n',NULL,NULL,'4','讨论','2012-11-14','5','2012-11-06');
INSERT INTO `teach_plan` VALUES ('22','98','3','','4','	$(function() {\r\n		$(\"#homework_deadline_{$plan[\'seq\']}\").datepicker();\r\n	});\r\n',NULL,NULL,'3','辩论','2012-12-01','5','2012-11-06');
INSERT INTO `teach_plan` VALUES ('23','98','4','','6','	$(function() {\r\n		$(\"#homework_deadline_{$plan[\'seq\']}\").datepicker();\r\n	});\r\n',NULL,NULL,'5','将授','2012-12-14','5','2012-11-06');
--
-- 表的结构 `teach_task`
-- 
DROP TABLE IF EXISTS `teach_task`;
CREATE TABLE `teach_task` (
  `teachid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '授课代码',
  `userid` int(10) unsigned NOT NULL COMMENT '教师代码',
  `courseid` int(10) unsigned NOT NULL COMMENT '课程代码',
  `teach_year` year(4) NOT NULL COMMENT '授课学年',
  `teach_semester` tinyint(3) unsigned NOT NULL COMMENT '授课学期',
  `start_week` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '起始授课周',
  `week` tinyint(3) unsigned NOT NULL COMMENT '授课周数',
  `exam_week` tinyint(3) unsigned DEFAULT NULL COMMENT '考试周',
  `status` tinyint(3) unsigned NOT NULL COMMENT '授课安排状态',
  `suggestion` varchar(256) DEFAULT NULL COMMENT '授课安排建议',
  `textbook` varchar(256) DEFAULT NULL COMMENT '选用教材',
  `publisher` varchar(64) DEFAULT NULL COMMENT '出版社',
  `author` varchar(64) DEFAULT NULL COMMENT '作者',
  `publish_date` date DEFAULT NULL COMMENT '出版日期',
  `publish_num` tinyint(4) DEFAULT NULL COMMENT '出版印次',
  `creative` varchar(1024) DEFAULT NULL COMMENT '课程改革、创新',
  `reference` varchar(512) DEFAULT NULL COMMENT '参考资料',
  `assess` varchar(128) DEFAULT NULL COMMENT '授课评价',
  `comment` varchar(128) DEFAULT NULL COMMENT '机构评语',
  `summary` varchar(256) DEFAULT NULL COMMENT '教师总结',
  `adviser` varchar(256) DEFAULT NULL COMMENT '教学建议',
  `finish_status` tinyint(1) DEFAULT NULL COMMENT '授课完成状态',
  `operator` int(10) unsigned DEFAULT NULL COMMENT '操作员',
  `update_date` date DEFAULT NULL COMMENT '操作日期',
  PRIMARY KEY (`teachid`),
  KEY `i_pk_userid` (`userid`),
  KEY `FK_teach_task` (`courseid`),
  CONSTRAINT `FK_teach_task` FOREIGN KEY (`courseid`) REFERENCES `course` (`courseid`),
  CONSTRAINT `FK_teach_task_teacher_teachid` FOREIGN KEY (`userid`) REFERENCES `teacher` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=144 DEFAULT CHARSET=utf8 COMMENT='授课任务';

-- 
-- 导出`teach_task`表中的数据 `teach_task`
--
INSERT INTO `teach_task` VALUES ('1','5','1','2012','1','1','18',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2',NULL,NULL);
INSERT INTO `teach_task` VALUES ('2','5','2','2012','1','1','18',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2',NULL,NULL);
INSERT INTO `teach_task` VALUES ('96','5','2','2013','1','1','18',NULL,'2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'3','9','2012-10-22');
INSERT INTO `teach_task` VALUES ('97','5','3','2012','2','2','17',NULL,'1',NULL,'程序设计','高等教育出版社','张晨曦','2008-06-01','1','与以往以往并无创新','程序设计艺术',NULL,NULL,NULL,NULL,'2',NULL,NULL);
INSERT INTO `teach_task` VALUES ('98','5','3','2008','1','3','18',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL);
INSERT INTO `teach_task` VALUES ('104','6','48','2013','1','1','18',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'3','9','2012-10-27');
INSERT INTO `teach_task` VALUES ('105','6','49','2013','1','1','18',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'3','9','2012-10-27');
INSERT INTO `teach_task` VALUES ('118','6','23','2013','1','1','18',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'3','9','2012-10-27');
INSERT INTO `teach_task` VALUES ('122','6','39','2013','1','1','18',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'3','9','2012-10-28');
INSERT INTO `teach_task` VALUES ('125','7','13','2013','1','1','18',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2',NULL,NULL,NULL,NULL,'3',NULL,'2012-10-29');
INSERT INTO `teach_task` VALUES ('136','5','48','2013','1','1','18',NULL,'2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'3',NULL,'2012-10-29');
INSERT INTO `teach_task` VALUES ('137','7','48','2013','1','1','18',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'3',NULL,'2012-10-29');
INSERT INTO `teach_task` VALUES ('139','5','12','2013','1','1','18',NULL,'2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'3','9','2012-11-04');
INSERT INTO `teach_task` VALUES ('141','6','28','2013','1','1','18',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'3',NULL,'2012-10-29');
INSERT INTO `teach_task` VALUES ('142','5','28','2013','1','1','18',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'3',NULL,'2012-10-29');
INSERT INTO `teach_task` VALUES ('143','5','78','2013','1','1','18',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'3',NULL,'2012-11-04');
--
-- 表的结构 `teach_time`
-- 
DROP TABLE IF EXISTS `teach_time`;
CREATE TABLE `teach_time` (
  `teachid` int(10) unsigned NOT NULL COMMENT '授课代码',
  `week` tinyint(3) unsigned NOT NULL COMMENT '星期',
  `seq` tinyint(3) unsigned NOT NULL COMMENT '课序',
  `type` tinyint(3) unsigned NOT NULL COMMENT '单双周',
  `teach_building` tinyint(3) unsigned NOT NULL COMMENT '教学楼编号',
  `roomid` int(10) unsigned NOT NULL COMMENT '教室编号',
  `operator` int(10) unsigned DEFAULT NULL COMMENT '操作员',
  `update_date` date DEFAULT NULL COMMENT '操作日期',
  PRIMARY KEY (`teachid`,`week`,`seq`),
  UNIQUE KEY `week` (`week`,`seq`,`type`,`teach_building`,`roomid`),
  CONSTRAINT `fk_teach_time_teach_task_teachid` FOREIGN KEY (`teachid`) REFERENCES `teach_task` (`teachid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='授课时间';
--
-- 表的结构 `teacher`
-- 
DROP TABLE IF EXISTS `teacher`;
CREATE TABLE `teacher` (
  `userid` int(10) unsigned NOT NULL COMMENT '教工代码',
  `name` varchar(32) NOT NULL COMMENT '姓名',
  `gender` tinyint(3) unsigned NOT NULL DEFAULT '3' COMMENT '性别',
  `nationality` tinyint(3) unsigned DEFAULT NULL COMMENT '民族',
  `Politics_status` tinyint(3) unsigned DEFAULT NULL COMMENT '政治面貌',
  `orgid` int(10) unsigned DEFAULT NULL COMMENT '所属机构',
  `idno` varchar(32) DEFAULT NULL COMMENT '身份证号',
  `job_title` tinyint(3) unsigned DEFAULT NULL COMMENT '职称',
  `degree` tinyint(3) unsigned DEFAULT NULL COMMENT '最终学历',
  `research` text COMMENT '研究方向',
  `resume` text COMMENT '个人简历',
  `major` varchar(64) DEFAULT NULL COMMENT '所学专业',
  `school` varchar(64) DEFAULT NULL COMMENT '毕业学校',
  `telphone1` varchar(16) DEFAULT NULL COMMENT '移动电话',
  `telphone2` varchar(16) DEFAULT NULL COMMENT '固定电话',
  `email1` varchar(32) DEFAULT NULL COMMENT '电子邮件1',
  `email2` varchar(32) DEFAULT NULL COMMENT '电子邮件2',
  `qq` varchar(16) DEFAULT NULL COMMENT ' QQ号码',
  `address` varchar(64) DEFAULT NULL COMMENT '通讯地址',
  `operator` int(10) unsigned DEFAULT NULL COMMENT '操作员',
  `update_date` date DEFAULT NULL COMMENT '操作日期',
  PRIMARY KEY (`userid`),
  KEY `FK_teacher_organize_orgid` (`orgid`),
  CONSTRAINT `FK_teacher_login_userid` FOREIGN KEY (`userid`) REFERENCES `login` (`userid`),
  CONSTRAINT `FK_teacher_organize_orgid` FOREIGN KEY (`orgid`) REFERENCES `organize` (`orgid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='教师';

-- 
-- 导出`teacher`表中的数据 `teacher`
--
INSERT INTO `teacher` VALUES ('5','曹素珍','2','1','2','2','','2','1','',NULL,'','','13919311111','','12312321@qq.com','','',NULL,'5','2012-10-25');
INSERT INTO `teacher` VALUES ('6','张国治','1','1','2','2','','1','1','',NULL,NULL,'','12313122132','','12312321@qq.com','','',NULL,NULL,'2012-10-11');
INSERT INTO `teacher` VALUES ('7','张志昌','1','1','2','2','','1','1','',NULL,NULL,'','213213213','','','','',NULL,NULL,'2012-10-11');
--
-- 表的结构 `teaching_diary`
-- 
DROP TABLE IF EXISTS `teaching_diary`;
CREATE TABLE `teaching_diary` (
  `teachid` int(10) unsigned NOT NULL COMMENT '授课代码',
  `userid` int(10) unsigned NOT NULL COMMENT '记录者编号',
  `week` tinyint(3) unsigned NOT NULL COMMENT '授课周',
  `week_day` tinyint(3) unsigned NOT NULL COMMENT '授课星期',
  `seq` tinyint(3) unsigned NOT NULL COMMENT '授课序号',
  `title` varchar(128) NOT NULL COMMENT '授课标题',
  `content` text NOT NULL COMMENT '授课内容',
  `problems` text NOT NULL COMMENT '遇到的问题',
  `suggestion` text NOT NULL COMMENT '授课建议',
  `operator` int(10) unsigned DEFAULT NULL COMMENT '操作员',
  `update_date` date DEFAULT NULL COMMENT '操作日期'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='教学日志';
--
-- 表的结构 `thesis`
-- 
DROP TABLE IF EXISTS `thesis`;
CREATE TABLE `thesis` (
  `userid` int(10) unsigned NOT NULL COMMENT '学号',
  `thesisid` int(10) unsigned NOT NULL COMMENT '选题代码',
  `state` tinyint(3) unsigned NOT NULL COMMENT '论文状态',
  `title_source` varchar(64) DEFAULT NULL COMMENT '选题来源',
  `word_num` int(10) unsigned DEFAULT NULL COMMENT '论文字数统计',
  `deadline` date DEFAULT NULL COMMENT '完成时间',
  `first_score` char(4) DEFAULT NULL COMMENT '初评成绩',
  `final_score` char(4) DEFAULT NULL COMMENT '总评成绩',
  `comment` text COMMENT '导师评语',
  `operator` int(10) unsigned DEFAULT NULL COMMENT '操作员',
  `update_date` date DEFAULT NULL COMMENT '操作日期',
  PRIMARY KEY (`userid`,`thesisid`),
  KEY `FK_thesis_thesis_title_thesisid` (`thesisid`),
  CONSTRAINT `FK_thesis_student_userid` FOREIGN KEY (`userid`) REFERENCES `student` (`userid`),
  CONSTRAINT `FK_thesis_thesis_title_thesisid` FOREIGN KEY (`thesisid`) REFERENCES `thesis_title` (`thesisid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='学生论文';

-- 
-- 导出`thesis`表中的数据 `thesis`
--
INSERT INTO `thesis` VALUES ('1','1','0','老师出题','5000','2013-06-12',NULL,NULL,NULL,NULL,NULL);
INSERT INTO `thesis` VALUES ('2','26','0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `thesis` VALUES ('3','1','0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `thesis` VALUES ('3','24','0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `thesis` VALUES ('3','26','0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
--
-- 表的结构 `thesis_process`
-- 
DROP TABLE IF EXISTS `thesis_process`;
CREATE TABLE `thesis_process` (
  `userid` int(10) unsigned NOT NULL COMMENT '学生',
  `thesisid` int(10) unsigned NOT NULL COMMENT '论文题目',
  `seq` int(10) unsigned NOT NULL COMMENT '次序',
  `content` varchar(1024) NOT NULL COMMENT '谈论内容',
  `progress` varchar(128) NOT NULL COMMENT '进展',
  `date` date NOT NULL COMMENT '讨论日期',
  `operator` int(10) unsigned DEFAULT NULL COMMENT '操作员',
  `update_date` date DEFAULT NULL COMMENT '操作日期',
  PRIMARY KEY (`userid`,`thesisid`,`seq`),
  KEY `FK_thesis_process_thesis_title_thesisid` (`thesisid`),
  CONSTRAINT `FK_thesis_process_student_userid` FOREIGN KEY (`userid`) REFERENCES `student` (`userid`),
  CONSTRAINT `FK_thesis_process_thesis_title_thesisid` FOREIGN KEY (`thesisid`) REFERENCES `thesis_title` (`thesisid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='学生论文';
--
-- 表的结构 `thesis_title`
-- 
DROP TABLE IF EXISTS `thesis_title`;
CREATE TABLE `thesis_title` (
  `thesisid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '选题代码',
  `teacherid` int(10) unsigned NOT NULL COMMENT '指导教师代码',
  `name` varchar(64) NOT NULL COMMENT '题目名称',
  `content` varchar(1024) DEFAULT NULL COMMENT '内容与要求',
  `type` tinyint(3) unsigned DEFAULT NULL COMMENT '类别',
  `max_num` tinyint(3) unsigned NOT NULL COMMENT '允许最大人数',
  `select_num` tinyint(3) unsigned DEFAULT NULL COMMENT '已选人数',
  `result` tinyint(3) unsigned DEFAULT NULL COMMENT '评审结果',
  `comment` varchar(128) DEFAULT NULL COMMENT '评审评语',
  `category` tinyint(3) unsigned NOT NULL COMMENT '论文类别',
  `grade` year(4) NOT NULL COMMENT '针对年级',
  `majorid` int(10) unsigned NOT NULL COMMENT '针对专业',
  `level` tinyint(3) unsigned NOT NULL COMMENT '学生层次',
  `operator` int(10) unsigned DEFAULT NULL COMMENT '操作员',
  `update_date` date DEFAULT NULL COMMENT '操作日期',
  PRIMARY KEY (`thesisid`),
  KEY `i_pk_teacherid` (`teacherid`),
  KEY `i_pk_type` (`type`),
  KEY `i_pk_major` (`majorid`),
  KEY `i_pk_level` (`level`),
  CONSTRAINT `FK_thesis_title_major_majorid` FOREIGN KEY (`majorid`) REFERENCES `major` (`majorid`),
  CONSTRAINT `FK_thesis_title_teacher_teacherid` FOREIGN KEY (`teacherid`) REFERENCES `teacher` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COMMENT='论文选题';

-- 
-- 导出`thesis_title`表中的数据 `thesis_title`
--
INSERT INTO `thesis_title` VALUES ('1','5','中文简繁体转换','ff','2','3','2','2','大傻测试','1','2009','1','1','9','2012-11-06');
INSERT INTO `thesis_title` VALUES ('2','5','基于web的文档相似检测系统的研究与开发','Javascript刷新页面的几种方法： ','2','3','0','2','讽德诵功飞','1','2010','1','1','9','2012-11-06');
INSERT INTO `thesis_title` VALUES ('3','5','Sphinx全文搜索引擎搭建','','1','3','0','1',NULL,'3','2012','1','1','5','2012-10-26');
INSERT INTO `thesis_title` VALUES ('6','5','最小生成树','','1','4','0','2','很好。。。','1','2010','1','1','9','2012-11-06');
INSERT INTO `thesis_title` VALUES ('13','6','test','','1','6','0','2','好吧。你是test','1','2010','1','1','9','2012-11-06');
INSERT INTO `thesis_title` VALUES ('22','5','ttt','dsfsd','1','5','0','3','不通过','2','2010','1','1','9','2012-11-06');
INSERT INTO `thesis_title` VALUES ('24','5','本科生教学质量检测','十分大方','1','3','1','2','地方管理富士康','1','2009','1','1','9','2012-11-06');
INSERT INTO `thesis_title` VALUES ('26','5','09级test','啥地方','1','4','2','2','123','2','2009','1','1','9','2012-11-06');
INSERT INTO `thesis_title` VALUES ('27','6','学年论文测试题目','学年论文测试题目题目内容与要求','1','5','0','2','学年论文测试题目','1','2009','1','1','9','2012-11-06');
INSERT INTO `thesis_title` VALUES ('28','6','学年论文测试题目2','学年论文测试题目2题目内容与要求','1','5','0','2','速度放大','1','2009','1','1','9','2012-11-06');
INSERT INTO `thesis_title` VALUES ('29','6','学年论文测试题目3','学年论文测试题目3题目内容与要求','1','5','0','2','的解放路上公开','1','2009','1','1','9','2012-11-06');
INSERT INTO `thesis_title` VALUES ('30','6','毕业论文测试题目1','毕业论文测试题目1题目内容与要求','1','5',NULL,'3','不同意就是不同意','1','2009','1','1','9','2012-11-06');
INSERT INTO `thesis_title` VALUES ('31','6','毕业论文测试题目2','毕业论文测试题目2题目内容与要求','1','5',NULL,'1','','2','2009','1','1','9','2012-11-05');
INSERT INTO `thesis_title` VALUES ('39','5','测试题目','这个是题目与要求题目内容与要求','1','5','0','3','不想通过','1','2009','1','1','9','2012-11-06');
INSERT INTO `thesis_title` VALUES ('40','5','毕业论文测试题目4654','的撒娇锋利的fjasdfsd 的电视剧sdjfl流口水的肌肤kdsjf 来说肯定积分','1','5','0','2','123456','2','2010','1','1','9','2012-11-06');
INSERT INTO `thesis_title` VALUES ('41','5','测试题目','ds    离开家地方离开时间lk 淡绿色房间挨了觉得浪费卡时间段去；1经历经历了金利科技离开就离开就离开解离开解了解立刻进来空间了解理解立刻解老客户灵魂健康和V1金利科技来就离开解看了 解离开简历库就离开家','1','5','0','1',NULL,'1','2010','1','1','5','2012-11-06');
INSERT INTO `thesis_title` VALUES ('42','5','大傻测试','大傻测试题目内容与要求','1','5','0','1',NULL,'1','2009','1','1','5','2012-11-06');
--
-- 表的结构 `workload`
-- 
DROP TABLE IF EXISTS `workload`;
CREATE TABLE `workload` (
  `userid` int(10) unsigned NOT NULL COMMENT '教工代码',
  `year` year(4) NOT NULL COMMENT '学年',
  `semester` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '学期',
  `thsum_time` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '理论课总学时',
  `expsum_time` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '实验课总学时',
  `papthisum_time` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '指导论文总学时',
  `examsum_time` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '监考总学时',
  `prasum_time` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '指导实习总学时',
  `others` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '其他',
  `total_time` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '工作量总计',
  `operator` int(10) unsigned DEFAULT NULL COMMENT '操作员',
  `update_date` date DEFAULT NULL COMMENT '操作日期',
  PRIMARY KEY (`userid`,`year`,`semester`),
  CONSTRAINT `FK_wordload_teacher_userid` FOREIGN KEY (`userid`) REFERENCES `teacher` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='教师工作量';

-- 
-- 导出`workload`表中的数据 `workload`
--
INSERT INTO `workload` VALUES ('5','2009','1','1','1','1','1','1','1','26',NULL,NULL);
INSERT INTO `workload` VALUES ('5','2009','2','1','1','1','1','1','1','24',NULL,NULL);
INSERT INTO `workload` VALUES ('5','2010','1','1','1','1','1','1','1','1',NULL,NULL);
INSERT INTO `workload` VALUES ('5','2010','2','1','1','1','1','1','1','1',NULL,NULL);
INSERT INTO `workload` VALUES ('5','2011','1','1','1','1','1','1','1','1',NULL,NULL);
INSERT INTO `workload` VALUES ('5','2011','2','1','1','1','1','1','1','1',NULL,NULL);
INSERT INTO `workload` VALUES ('5','2012','1','1','1','1','1','1','1','1',NULL,NULL);
INSERT INTO `workload` VALUES ('5','2012','2','1','1','1','1','1','1','1',NULL,NULL);
INSERT INTO `workload` VALUES ('5','2013','1','1','1','1','1','1','1','5',NULL,NULL);
INSERT INTO `workload` VALUES ('5','2013','2','1','1','1','1','1','1','1',NULL,NULL);
INSERT INTO `workload` VALUES ('6','2009','1','1','1','1','1','1','1','1',NULL,NULL);
INSERT INTO `workload` VALUES ('6','2009','2','1','1','1','1','1','1','6',NULL,NULL);
INSERT INTO `workload` VALUES ('6','2010','1','1','1','1','1','1','1','1',NULL,NULL);
INSERT INTO `workload` VALUES ('6','2010','2','1','1','1','1','1','1','1',NULL,NULL);
INSERT INTO `workload` VALUES ('6','2011','1','1','1','1','1','1','1','1',NULL,NULL);
INSERT INTO `workload` VALUES ('6','2011','2','1','1','1','1','1','1','1',NULL,NULL);
INSERT INTO `workload` VALUES ('6','2012','1','1','1','1','1','1','1','1',NULL,NULL);
INSERT INTO `workload` VALUES ('6','2012','2','1','1','1','1','1','1','1',NULL,NULL);
INSERT INTO `workload` VALUES ('6','2013','1','1','1','1','1','1','1','6',NULL,NULL);
INSERT INTO `workload` VALUES ('6','2013','2','1','1','1','1','1','1','1',NULL,NULL);
INSERT INTO `workload` VALUES ('7','2009','1','1','1','1','1','1','1','1',NULL,NULL);
INSERT INTO `workload` VALUES ('7','2009','2','1','1','1','1','1','1','7',NULL,NULL);
INSERT INTO `workload` VALUES ('7','2010','1','1','1','1','1','1','1','1',NULL,NULL);
INSERT INTO `workload` VALUES ('7','2010','2','1','1','1','1','1','1','1',NULL,NULL);
INSERT INTO `workload` VALUES ('7','2011','1','1','1','1','1','1','1','1',NULL,NULL);
INSERT INTO `workload` VALUES ('7','2011','2','1','1','1','1','1','1','1',NULL,NULL);
INSERT INTO `workload` VALUES ('7','2012','1','1','1','1','1','1','1','1',NULL,NULL);
INSERT INTO `workload` VALUES ('7','2012','2','1','1','1','1','1','1','1',NULL,NULL);
INSERT INTO `workload` VALUES ('7','2013','1','1','1','1','1','1','1','7',NULL,NULL);
INSERT INTO `workload` VALUES ('7','2013','2','1','1','1','1','1','1','1',NULL,NULL);