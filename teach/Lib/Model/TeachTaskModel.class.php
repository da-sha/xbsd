<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-21
 * -----------------------------------------------------------
 * 创建人	：大傻 lilei.zh@qq.com
 * -----------------------------------------------------------
 * 文件说明	; TeachTaskModel.class.php UTF-8
 * -----------------------------------------------------------
 */

class TeachTaskModel extends CommonModel {

	// 自动验证设置
	protected $_validate = array(
		array('teachid', 'require', '授课编号必须！'),
		array('userid', 'require', '授课教师编号必须！'),
		array('courseid', 'require', '课程代码必须！'),
		array('teach_semester', 'require', '授课学期必须！'),
		array('teach_year', 'require', '授课学年必须！'),
		array('start_week', 'require', '开始授课周必须！'),
		array('start_week', 'number', '开始授课周必须是数字！'),
		array('status', 'require', '授课安排状态必须！'),
	);

}

?>
