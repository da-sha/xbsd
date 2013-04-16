<?php
/*-------------------------------------------------------------------
* Purpose:
*         
* Time:
*         2012年11月6日 10:10:55
* Author:
*         张彦升
--------------------------------------------------------------------*/

class PostgraduatePlanCourseAction extends PlanCourseAction {
	
	public function _initialize(){
		parent::_initialize();
		
		$this->level = C("LEVEL_POSTGRADUATE_ID");
		$this->school_course = A('PostgraduateSchoolCourse');
		$this->academy_course = A('PostgradateAcademyCourse');
		$this->major_course = A('PostgradateMajorCourse');
	}
	/**
	 * 向界面显示模块
	 */
	public function _before_index(){

	}
}

?>
