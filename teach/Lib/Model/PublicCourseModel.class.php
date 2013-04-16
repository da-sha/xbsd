<?php

/*-------------------------------------------------------------------
* Purpose:
*         公共课管理模块
* Time:
*         2012年11月6日 10:10:55
* Author:
*         张彦升
--------------------------------------------------------------------*/

class PublicCourseModel extends CommonModel{

	protected $_validate = array(
		array('id', 'require', '专业代码必须！'),
    );
}

?>
