<?php
/*-------------------------------------------------------------------
* Purpose:
*         对象集中管理
* Time:
*         2012年11月6日 10:10:55
* Author:
*         张彦升
--------------------------------------------------------------------*/

class UndergraduatePlanCourseAction extends PlanCourseAction {
	
	public function _initialize(){
		parent::_initialize();
		
		$this->level = C("LEVEL_UNDERGRADUATE_ID");
		$this->school_course = A('UndergraduateSchoolCourse');
		$this->academy_course = A('UndergraduateAcademyCourse');
		$this->major_course = A('UndergraduateMajorCourse');
	}
	/**
	 * 向界面显示模块
	 */
	public function _before_index(){

	}
	/**
	 * 专业课数据
	 */
	public function get_major_course(){		
		$this->major_course->get_course();
	}
	/**
	 * 专业课数据
	 */
	public function major_insert(){
		$this->major_course->insert();
	}
	/**
	* 专业课数据
	*/
	public function major_delete(){
		$this->major_course->delete();
	}
	/**
	* 专业课数据
	*/
	public function major_update(){
		$this->major_course->update();
	}
	
	/**
	* 专业课数据
	*/
	public function get_academy_course(){		
		$this->academy_course->get_course();
	}
	/**
	 * 专业课数据
	 */
	public function academy_insert(){
		$this->academy_course->insert();
	}
	/**
	* 专业课数据
	*/
	public function academy_delete(){
		$this->academy_course->delete();
	}
	/**
	* 专业课数据
	*/
	public function academy_update(){
		$this->academy_course->update();
	}
	/**
	* 专业课数据
	*/
	public function get_school_course(){		
		$this->school_course->get_course();
	}
	/**
	 * 专业课数据
	 */
	public function school_insert(){
		$this->school_course->insert();
	}
	/**
	* 专业课数据
	*/
	public function school_delete(){
		$this->school_course->delete();
	}
	/**
	* 专业课数据
	*/
	public function school_update(){
		$this->school_course->update();
	}
}

?>
