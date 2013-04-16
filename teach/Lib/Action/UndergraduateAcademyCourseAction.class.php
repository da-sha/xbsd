<?php
/*-------------------------------------------------------------------
* Purpose:
*         课程的管理模块
* Time:
*         2012年11月6日 10:10:55
* Author:
*         张彦升
--------------------------------------------------------------------*/

class UndergraduateAcademyCourseAction extends AcademyCourseAction{
	/**
	 * 
	 */
	function _initialize(){
		parent::_initialize();
		
		$this->level = C("LEVEL_UNDERGRADUATE_ID");
	}
}
?>
