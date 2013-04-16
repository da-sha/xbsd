<?php

/* ------------------------------------------------------------
 * 日期		：2012-10-12
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	; MajorCourseModel.class.php UTF-8
 * -----------------------------------------------------------
 */

class MajorPlanCourseModel extends CommonModel{

	protected $_validate = array(
		array('majorid', 'require', '专业代码必须！'),
		array('majorcourseid', 'require', '专业代码必须！'),
    );
}

?>
