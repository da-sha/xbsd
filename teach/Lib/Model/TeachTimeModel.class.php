<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-21
 * -----------------------------------------------------------
 * 创建人	：大傻 lilei.zh@qq.com
 * -----------------------------------------------------------
 * 文件说明	; TeachTimeModel.class.php UTF-8
 * -----------------------------------------------------------
 */

class TeachTimeModel extends CommonModel{
	// 自动验证设置
    protected $_validate = array(
        array('teachid', 'require', '授课编号必须！'),
		array('week', 'require', '授课星期必须！'),
		array('seq', 'require', '授课序号必须！'),
		array('type', 'require', '授课单双周必须！'),
		array('teach_building', 'require', '上课教学楼必须！'),
		array('teach_building', 'require', '上课教学楼必须是数字！'),
		array('roomid', 'require', '上课教师编号必须！'),
		array('roomid', 'number', '上课教师编号必须是数字！'),
    );
}

?>
