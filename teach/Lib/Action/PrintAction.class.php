<?php

/* ------------------------------------------------------------
 * 日期		：2012-11-26
 * -----------------------------------------------------------
 * 创建人	：大傻 lilei.zh@qq.com
 * -----------------------------------------------------------
 * 文件说明	; PrintAction.class.php UTF-8
 * -----------------------------------------------------------
 */
class PrintAction extends CommonAction{
	public function index() {
		$this->display() ;
	}
	public function thesistitle()
	{
		$thesisid = $this->_get("thesisid") ;
		$db = M("thesis_title") ;
		$data = $db->field("*,get_major_name(majorid) as major_name")->find($thesisid) ;
		$teadb = M("teacher") ;
		$data['teacher'] = $teadb->field("name,get_field_dict_name('teacher','job_title',job_title) as job_title")->find($data['teacherid']) ;
		$this->assign("thesis", $data ) ;
		$this->display() ;
	}
}

?>
