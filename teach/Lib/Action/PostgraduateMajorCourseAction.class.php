<?php
/*-------------------------------------------------------------------
* Purpose:
*         研究生专业课程管理
* Time:
*         2012年11月6日 10:10:55
* Author:
*         张彦升
--------------------------------------------------------------------*/

class PostgraduateMajorCourseAction extends MajorCourseAction {
	
	public function _initialize(){
		parent::_initialize();
		
		$this->level = $this->LEVEL_POSTGRADUATE_ID;
	}
}

?>
