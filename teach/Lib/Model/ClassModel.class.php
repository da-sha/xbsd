<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-7
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	; ClassModel.class.php UTF-8
 * -----------------------------------------------------------
 */

class ClassModel extends CommonModel{
	// 自动验证设置
    protected $_validate = array(
        array('classid', 'require', '班级代号必须！'),
		array('name', 'require', '班级名称必须！'),
		array('num', 'require', '班级人数必须！'),
		array('num', 'number', '班级人数必须是数字！'),
		array('majorid', 'require', '所属专业必须！'),
		array('register_date', 'require', '班级成立时间必须！'),
    );
}
?>
