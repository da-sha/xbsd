<?php
/*-------------------------------------------------------------------
* Purpose:
*         
* Time:
*         2012年11月6日 10:10:55
* Author:
*         张彦升
--------------------------------------------------------------------*/

abstract class PlanCourseAction extends CourseBaseAction {
	protected $level;			//学生层次
	protected $school_course;	//学校计划课程
	protected $academy_course;	//学院计划课程
	protected $major_course;	//专业计划课程
	
	public function _initialize(){
		parent::_initialize();
	}
	
	/**
	 * 向界面显示模块
	 */
	public function index(){
		$this->assign_undergraduate_major_list();
		$this->assign_undergraduate_grade_list();
		$this->assign_class_semester_list();

		$this->assign_level_list();
		$this->assign_course_property_list();
		$this->assign_course_category_list();
		$this->assign_course_type_list();
		$this->assign_exam_type_list();

		$this->display();
	}
}

?>
