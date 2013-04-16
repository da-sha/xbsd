/*
SQLyog 企业版 - MySQL GUI v7.14 
MySQL - 5.0.45-community-nt-log : Database - teach
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`teach` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `teach`;

/*Table structure for table `menu` */

DROP TABLE IF EXISTS `menu`;

CREATE TABLE `menu` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `seq` tinyint(3) unsigned default '1',
  `name` varchar(32) NOT NULL,
  `menuid` int(10) unsigned default '0',
  `type` tinyint(3) unsigned default NULL,
  `url` varchar(64) NOT NULL,
  `operator` int(10) unsigned default NULL,
  `update_data` date default NULL,
  PRIMARY KEY  (`id`),
  KEY `i_pk_menuid` (`menuid`),
  KEY `i_pk_type` (`type`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

/*Data for the table `menu` */

insert  into `menu`(`id`,`seq`,`name`,`menuid`,`type`,`url`,`operator`,`update_data`) values (1,1,'首页',0,1,'/index/home',NULL,NULL),(2,2,'课程',0,1,'/course/index',NULL,NULL),(3,3,'教务支持',0,1,'/index/home',NULL,NULL),(4,1,'院系专业课',2,1,'',NULL,NULL),(5,2,'院系选修课',2,1,'',NULL,NULL),(6,1,'师资队伍',3,1,'',NULL,NULL),(19,3,'学院动态',1,3,'',NULL,NULL),(18,2,'课程动态',1,3,'',NULL,NULL),(10,1,'登录首页',1,2,'',NULL,NULL),(11,2,'班级动态',1,2,'',NULL,NULL),(12,3,'课程动态',1,2,'',NULL,NULL),(13,4,'学院动态',1,2,'',NULL,NULL),(14,1,'我的课程',2,2,'',NULL,NULL),(15,2,'论文管理',2,2,'',NULL,NULL),(17,1,'登录首页',1,3,'',NULL,NULL),(20,1,'我的课程',2,3,'',NULL,NULL),(21,2,'论文管理',2,3,'',NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
