<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-23
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	; CourseChangeModel.class.php UTF-8
 * -----------------------------------------------------------
 */

class CourseChangeModel extends CommonModel {

	protected $_validate = array(
        array('teachid', 'require', '授课代码必须！'),
		array('type', 'require', '类型必须！'),
		array('teacherid', 'number', '教师代码必须！'),
    );

	protected $_auto = array(
		array('operator','getUser',3,'callback'),
		array('update_date','getDate',3,'callback'),
	);
}

?>
