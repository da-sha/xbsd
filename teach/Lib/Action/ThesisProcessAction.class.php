<?php

/* ------------------------------------------------------------
 * 日期		：2012-11-17
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	; ThesisProcessAction.class.php UTF-8
 * -----------------------------------------------------------
 */
class ThesisProcessAction extends CommonAction{
	public function index(){
		$category = $_GET["category"] ;
		$userid = $this->get_user_id();
		$thesisdb = M("thesis,thesis_title");
		$where = "thesis_title.category=$category and thesis.userid={$userid} and thesis.thesisid=thesis_title.thesisid";
		$data = $thesisdb->field("thesis.thesisid")->where($where)->find() ;
		if( $data["thesisid"] )
		{
			$this->assign("thesisid", $data["thesisid"]) ;
			$where = "userid={$userid} and thesisid={$data["thesisid"]}" ;
			$db = M("thesis_process") ;
			$count = $db->where( $where )->count() ;
			if( $count > 0 )
			{
				import('ORG.Util.Page');
				$page = new Page( $count, C('PAGESIZE'));
				$show = $page->show();
				$this->assign( "show" , $show);
				$data = $db->where( $where )
						->limit($page->firstRow.','.$page->listRows)
						->select() ;
				if( $data !== false )
				{
					$this->assign( "record_list", $data ) ;
				}
			}
			else{
				$this->assign("show", "对不起，当前没有记录！") ;
			}
		}
		else
		{
			$this->assign("show", "对不起，您当前没有选择论题！") ;
		}
		$this->display();
	}


	public function insert_record(){
		$_POST["date"] = date('Y-m-d') ;
		$_POST["userid"] = $this->get_user_id();
		$db = new ThesisProcessModel();
		if ( false !== $db->create() && false !== $db->add() ) {
			$this->success("添加成功！") ;
		} else {
			$this->error("对不起，添加 失败！！") ;
		}
	}
	
	public function _before_delete() {
		$_REQUEST['processid'] = $_GET['processid'];
	}
}

?>
