<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-20
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	; NoticeAction.class.php UTF-8
 * -----------------------------------------------------------
 */

class NoticeAction extends CommonAction {

	public function index() {
		$notice = new NoticeModel();
		$where = "type=2";
		$count = $notice->where($where)->count();
		import('ORG.Util.Page');
		$page = new Page($count, C('PAGESIZE'));
		$show = $page->show();
		$this->assign("show", $show);
		$data = $notice->where($where)
					->field("noticeid,
							get_org_name(orgid) as orgid,
							source,
							get_field_dict_name('notice','type',type) as type,
							title,
							type,
							content,
							get_field_dict_name('notice','importance',importance) as importance,
							update_date")
					->order("update_date desc")
					->limit($page->firstRow . ',' . $page->listRows)
					->select();
		$this->assign('list', $data);
		$this->assign('type', 2);
		$this->display();
	}

	public function add() {
		$type = $this->_get('type');
		$org = new OrganizeModel();
		$data = $org->field("orgid as id, name")->select();
		$this->get_field_dict( 'notice', 'importance', 'im_list');
		$this->assign('const_type', $type);
		$this->get_field_dict('notice', 'category', 'category_list');
		$this->assign('org_list', $data);
		$this->display();
	}

	public function insert() {
		$db = new NoticeModel();
		if ($db->create()) {
			if (false !== $db->add()) {
				$this->success('恭喜您添加成功！', "__URL__/index");
			} else {
				$this->error('操作失败：' . $db->getDbError(), "__URL__/index");
			}
		} else {
			$this->error('操作失败：数据验证( ' . $db->getError() . ' )');
		}
	}

	public function edit() {
		$noticeid = $this->_get("noticeid");
		$db = new NoticeModel();
		$data = $db->find($noticeid);
		$this->assign("notice", $data);
		$org = new OrganizeModel();
		$data = $org->field("orgid as id, name")->select();
		$this->get_field_dict('notice', 'importance', 'im_list');
		$this->get_field_dict('notice', 'type', 'type_list');
		$this->get_field_dict('notice', 'category', 'category_list');
		$this->assign('org_list', $data);
		$this->display();
	}

	public function update() {
		$db = new NoticeModel();
		if ($data = $db->create()) {
			if (false !== $db->save()) {
				$this->success('恭喜您编辑成功！', "__URL__/index");
			} else {
				$this->error('操作失败：' . $db->getDbError(), "__URL__/index");
			}
		} else {
			$this->error('操作失败：数据验证( ' . $db->getError() . ' )');
		}
	}

	public function delete() {
		$noticeid = $this->_get("noticeid");
		if ($noticeid) {
			$db = new NoticeModel();
			if (false !== $db->where("noticeid={$noticeid}")->delete()) {
				$this->success('删除成功', "__URL__/index");
			} else {
				$this->error("删除失败" . $db->getDbError(), "__URL__/index");
			}
		}
	}
	
	public function stu_insert()
	{
		$db = M("student") ;
		$data = $db->field("classid")->find( $this->get_user_id() );
		if(false !== $data )
		{
			$noticedb = new NoticeModel() ;
			$_POST['type'] = 1 ;
			$_POST['category'] = 1 ;
			if( false !== $noticedb->create() && false !== $noticedb->add() )
			{
				$this->success("插入数据成功！") ;
			} else {
				$this->error("插入数据失败！") ;
			}
		}else{
			$this->error("没有找到您所在班级！") ;
		}
	}
	public function tea_insert(){
		$_POST['type'] = 1 ;
		$_POST['category'] = 1 ;
		$noticedb = new NoticeModel() ;
		if( false !== $noticedb->create() && false !== $noticedb->add() )
		{
			$this->success("插入数据成功！" , "__APP__/Student/classnotice/classid/{$_POST['classid']}") ;
		} else {
			$this->error("插入数据失败！") ;
		}
	}

	public function student()
	{
		$this->get_field_dict('notice', 'importance', 'im_list');
		$this->display() ;
	}
	public function teacher()
	{
		$this->student() ;
	}

	//显示某一个通知公告的详细信息
	public function noticeinfo(){
		$noticeid = $this->_get("noticeid");
		$db = new NoticeModel();
		$data = $db->where("noticeid={$noticeid}")
					->field("noticeid,
							get_org_name(orgid) as orgid,
							source,
							title,
							content,
							update_date")
					->find();
		$this->assign("notice", $data);
		$this->display();
	}
}

?>
