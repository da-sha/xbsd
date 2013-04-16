<?php

/*-------------------------------------------------------------------
* Purpose:
*         
* Time:
*         2012年11月2日 20:07:56
* Author:
*         张彦升
--------------------------------------------------------------------*/

class RoleModel extends CommonModel {

	protected $_validate = array(
		array('name', 'require', '用户组名必须！'),
		array('pid', 'require', '父节点必须！'),
    );
}

?>
