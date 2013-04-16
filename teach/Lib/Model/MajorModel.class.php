<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-10
 * -----------------------------------------------------------
 * 创建人	：大傻 lilei.zh@qq.com
 * -----------------------------------------------------------
 * 文件说明	; MajorModel.class.php UTF-8
 * -----------------------------------------------------------
 */

class MajorModel extends CommonModel{
	protected $_validate = array(
        array('majorid', 'require', '专业代号必须！'),
		array('name', 'require', '专业名称必须！'),
		array('orgid', 'require', '所属机构必须！'),
		array('school_system', 'require', '学制必须是数字！'),
    );
}

?>
