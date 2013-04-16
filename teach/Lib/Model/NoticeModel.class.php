<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-20
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	; NoticeModel.class.php UTF-8
 * -----------------------------------------------------------
 */

class NoticeModel extends CommonModel {

	protected $_validate = array(
        array('title', 'require', '标题必须！'),
		array('content', 'require', '通知公告内容必须！'),
		array('type', 'require', '公告类型必须！'),
		array('category', 'require', '公告对象必须！'),
    );
}

?>
