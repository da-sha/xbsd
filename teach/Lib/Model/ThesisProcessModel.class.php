<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ThesisProcessModel
 *
 * @author xiaoqing
 */
class ThesisProcessModel extends CommonModel {
	
	protected $_validate = array(
        array('userid', 'require', '学生代码必须！'),
		array('thesisid', 'require', '论文选题代码必须！'),
		array('date', 'require', '讨论日期！'),
		array('content', 'require', '讨论内容！'),
		array('progress', 'require', '讨论进展！'),
    );
}

?>
