<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-7
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	; StudentModel.class.php UTF-8
 * -----------------------------------------------------------
 */

class StudentModel extends CommonModel{
	// 自动验证设置
    protected $_validate = array(
        array('userid', 'require', '用户编号必须！'),
		array('name', 'require', '学生姓名必须！'),
		array('gender', 'require', '性别必须！'),
		array('classid', 'require', '所属班级必须！'),
		array('majorid', 'require', '所属专业必须！'),
		array('admissiondate', 'require', '入学时间必须！'),
		array('email', 'email', '邮箱格式错误！', 2),

    );
}

?>
