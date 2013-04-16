<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-22
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	; Thesis_titleModel.class.php UTF-8
 * -----------------------------------------------------------
 */

class ThesisTitleModel extends CommonModel {

	protected $_validate = array(
        array('teacherid', 'require', '教师必须！'),
		array('name', 'require', '论文题目名称必须！'),
		array('max_number', 'require', '允许最大人数必须！'),
		array('max_number', 'number', '允许最大人数必须是数字！'),
		array('select_number', 'number', '已选人数必须是数字！'),
		array('category', 'require', '论文类别必须！'),
		array('majorid', 'require', '针对专业必须！'),
		array('grade', 'require', '针对年级必须！'),
		array('level', 'require', '学生层次必须！'),
    );
    protected $_auto = array(
		array('operator','getUser',3,'callback'),
        array('update_date','getDate',3,'callback'),
        array('result','1',1),
    );
}

?>
