<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-14 
 * -----------------------------------------------------------
 * 创建人	：大傻 lilei.zh@qq.com
 * -----------------------------------------------------------
 * 文件说明	; MenuModel.class.php UTF-8
 * -----------------------------------------------------------
 */

class MenuModel extends CommonModel{
	protected $_validate = array(
        array('name', 'require', '标题必须！'),
        array('menuid', 'require', '父菜单必须！'),
		array('url', 'require', 'URL必须！')
    );
    // 自动填充设置
    protected $_auto = array(
		array('operator','getUser',3,'callback'),
        array('update_date','getDate',3,'callback'),
    );
}

?>
