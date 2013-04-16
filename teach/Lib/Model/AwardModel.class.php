<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-20
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	; AwardModel.class.php UTF-8
 * -----------------------------------------------------------
 */

class AwardModel extends CommonModel {

	protected $_validate = array(
        array('userid', 'require', '学生代码必须！'),
		array('year', 'require', '学期必须！'),
		array('year', 'number', '学期必须是数字！'),
		array('name', 'require', '奖励名称必须！'),
		array('type', 'require', '奖励类型必须！'),
		array('money', 'number', '奖励金额必须是数字！'),
    );
}
?>
