<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-7
 * -----------------------------------------------------------
 * 创建人	：大傻 lilei.zh@qq.com
 * -----------------------------------------------------------
 * 文件说明	; CommentModel.class.php UTF-8
 * -----------------------------------------------------------
 */

class CommonModel extends Model {

	//获取系统日期
	function getDate() {
		//return date('Y-m-d H:i:s');
        return time();  //v3 add
	}

	//获取登录用户ID
	function getUser() {
		return session( C('USER_AUTH_KEY') ) ;
	}

	/**
	 * 获得用户名
	 */
	function getUserCode() {
		return session('user_code') ;
	}

	// 自动填充设置
    protected $_auto = array(
		array('operator','getUser',3,'callback'),
        array('update_date','getDate',3,'callback'),
    );
}

?>
