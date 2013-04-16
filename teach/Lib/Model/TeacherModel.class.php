<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-7
 * -----------------------------------------------------------
 * 创建人	：大傻 lilei.zh@qq.com
 * -----------------------------------------------------------
 * 文件说明	; TeacherModel.class.php UTF-8
 * -----------------------------------------------------------
 */

class TeacherModel extends CommonModel {
	// 自动验证设置
    protected $_validate = array(
		array('userid', 'require', '用户编号必须！'),
        array('name', 'require', '姓名必须！'),
		array('gender', 'require', '性别必须！'),
        array('email1', 'email', '邮箱1格式错误！', 2),
		array('email2', 'email', '邮箱2格式错误！', 2),
    );
}

?>
