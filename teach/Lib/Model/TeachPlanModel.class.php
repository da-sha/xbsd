<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-24
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	; TeachPlanModel.class.php UTF-8
 * -----------------------------------------------------------
 */

class TeachPlanModel extends CommonModel {

	protected $_validate = array(
        array('teachid', 'require', '授课代码必须！'),
		array('seq', 'require', '序号必须！'),
		array('plan_title', 'require', '计划标题必须！'),
		array('plan_time', 'require', '计划课时必须！'),
		array('plan_time', 'number', '计划课时必须是数字！'),
		array('plan_content', 'require', '教学计划内容必须！'),
    );
}

?>
