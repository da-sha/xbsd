<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-25
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	; ExamModel.class.php UTF-8
 * -----------------------------------------------------------
 */

class ExamModel extends CommonModel {

	protected $_validate = array(
        array('teachid', 'require', '授课代码必须！'),
		array('type', 'require', '考试类型必须！'),
		array('date', 'require', '考试日期必须是数字！'),
		array('time', 'require', '考试时间必须！'),
		array('teach_building', 'require', '考试教学楼必须！'),
		array('teach_building', 'number', '考试教学楼必须是数字！'),
		array('roomid', 'require', '考试教室编号必须！'),
		array('roomid', 'number', '考试教室编号必须是数字！'),
    );
}

?>
