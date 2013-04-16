<?php
/*-------------------------------------------------------------------
* Purpose:
*         公共课管理模块
* Time:
*         2012年11月6日 10:10:55
* Author:
*         张彦升
--------------------------------------------------------------------*/
class PublicCourseAction extends CourseBaseAction{
	protected $level;
	protected $dao;
	protected $db_public_course,$db_course;
	/**
	 * 
	 */
	function _initialize(){
		parent::_initialize();
		
		$this->db_public_course = D("PublicCourse");
		$this->db_course = D("Course");
	}
}
?>
