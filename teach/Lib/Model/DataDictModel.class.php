<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-7
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	; DataDictModel.class.php UTF-8
 * -----------------------------------------------------------
 */

class DataDictModel extends CommonModel {

	// 自动验证设置
	protected $_validate = array(
		array('tb_name', 'require', '数据表名不能为空！'),
		array('fd_name', 'require', '字段名不能为空！'),
		array('fd_value', 'require', '字段值不能为空！'),
		array('fd_value', 'number', '字段值必须为数字！'),
		array('fd_mean', 'require', '字段含义不能为空！'),
		array('type', 'require', '类型不能为空！'),
		array('seq', 'number', '序号必须为数字！'),
	);
}

?>
