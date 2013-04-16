<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-13 
 * -----------------------------------------------------------
 * 创建人	：大傻 lilei.zh@qq.com
 * -----------------------------------------------------------
 * 文件说明	; CourseModel.class.php UTF-8
 * -----------------------------------------------------------
 */

class CourseModel extends CommonModel{
	protected $_validate = array(
        array('name', 'require', '课程名必须！'),
		array('majorid', 'require', '针对专业必选！'),
		array('semester', 'require', '开课学期必选！'),
		array('level', 'require', '学生层次必选！'),
		array('property', 'require', '课程性质必选！'),
		array('cou_type', 'require', '课程类型必选！'),
		array('cou_category', 'require', '课程类别必选！'),
		array('exam_type', 'require', '考试类型必选！'),
		array('theweektime', 'require', '理论周学时必填！'),
    );
}

?>
