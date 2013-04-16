<?php

/* ------------------------------------------------------------
 * 日期		：2012-10-16
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	; ArrangeCourseAction.class.php UTF-8
 * -----------------------------------------------------------
 */

abstract class ArrangeCourseAction extends CourseBaseAction{
	protected $db_tach_task;

	public function _initialize() {
		parent::_initialize();
		
		$this->db_teach_task = D("TeachTask");
	}
}

?>
