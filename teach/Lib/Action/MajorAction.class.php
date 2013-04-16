<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-10
 * -----------------------------------------------------------
 * 创建人	：大傻 lilei.zh@qq.com
 * -----------------------------------------------------------
 * 文件说明	; MajorAction.class.php UTF-8
 * -----------------------------------------------------------
 */

class MajorAction extends CommonAction {
	public function index() {
		if( $_GET['level'] )
		{
			session("level", $_GET['level']) ;
		}else{
			$_GET['level'] = session("level" ) ;
		}
		$this->get_field_dict("major", "level", "level_list") ;
		$this->assign('show', "请先选择学生层次！");
		$this->display();
	}

	public function body(){
		$level = $this->_get("level");
		if(!$level){
			$this->assign('show', "请先选择学生层次！");
		}else{
			session("level", $_GET['level']) ;
			$db = new MajorModel();
			$where = "major.level={$level} and major.orgid=organize.orgid";
			$count = $db->table("organize, major")->where($where)->count();
			import('ORG.Util.Page');
			$page = new Page($count, C('PAGESIZE'));
			$show = $page->show();
			$this->assign("show", $show);
			$data = $db->table("organize, major")
					->where($where)
					->field("major.majorid,
						major.name,
						organize.name as orgid,
						get_field_dict_name('major','type',major.type) as type,
						get_field_dict_name('major','level',major.level) as level,
						get_field_dict_name('major','School_system',major.School_system) as School_system")
					->limit($page->firstRow . ',' . $page->listRows)
					->select();

			if($data){
				$this->assign('major_list', $data);
			}else{
				$this->assign('show', "没有专业信息！");
			}
		}
		$this->display();
	}

	public function add() {
		$db = M("organize");
		$org_data = $db->field("orgid as id,name")->select();
		$this->assign("org_list", $org_data);
		$this->get_field_dict("major", "level", 'level_list');
		$this->get_field_dict("major", "type", 'type_list');
		$this->get_field_dict("major", "school_system", 'schs_list');
		$this->display();
	}

	public function insert() {
		$db = new MajorModel();
		if ( $data = $db->create() && false !== $db->add() ) {
			$this->success('恭喜您添加专业成功！', "__URL__/index/level/{$_POST['level']}");
		} else {
			$this->error("操作失败：数据验证({$db->getError()})");
		}
	}

	public function edit() {
		$this->get_field_dict("major", "School_system", 'schs_list');
		$majorid = $this->_get("majorid");
		$db = M("major");
		$data = $db->field("*,get_field_dict_name('major','type',type) as type,get_field_dict_name('major','level',level) as level")->find($majorid);
		$this->assign("major", $data);
		$db = M("organize");
		$org_data = $db->field("orgid as id,name")
					->select();
		$this->assign("org_list", $org_data);
		$this->display();
	}

	public function update() {
		$db = new MajorModel();
		if (false !== $db->create() && false !== $db->save() ) {
			$this->success('编辑成功！', "__URL__/index/level/{$_POST['level']}");
		} else {
			$this->error("更新错误,{$db->getError()})");
		}
	}

	public function delete() {
		$majorid = $this->_get("majorid");
		if ($majorid) {
			$db = M("major");
			$data = $db->field("level")->find($majorid);
			if (false !== $db->where("majorid={$majorid}")->delete()) {
				$this->success('删除成功', "__URL__/index/level/{$data['level']}");
			} else {
				$this->error("删除失败" . $db->getDbError(), "__URL__/index/level/{$data['level']}");
			}
		}
	}

	public function majoryear()
	{
		$major = new MajorModel();
		$major_data = $major->field("majorid, name")->select();
		$this->assign("major_list", $major_data );
		$year = date("Y")+1 ;
		$year_data = array();
		for($i = 0; $i <= 4; $i++ ){
			$year_data[$i]['id'] = $year;
			$year_data[$i]['name'] = $year."学年";
			$year--;
		}
		$this->assign("year_list", $year_data);
		$this->display();
	}
	public function ajax_get_major_year(){
		$majorid = $this->_get("majorid") ;
		if(!$majorid)
		{
			$this->ajaxReturn( NULL, "错误的参数", 0 ) ;
			return ;
		}
		$db = M("major_year") ;
		$data = $db->field("year")->where("majorid={$majorid}")->select();
		if( $data )
		{
			$this->ajaxReturn($data, "查询成功", 1 ) ;
		}
		else {
			$this->ajaxReturn( NULL, "对不起，没有找到数据！", 0 ) ;
		}
	}
	public function ajax_insert_major_year()
	{
		$data["majorid"] = $this->_get("majorid") ;
		$data["year"] = $this->_get("year") ;
		if( !$data["majorid"] || !$data["year"] )
		{
			$this->ajaxReturn( NULL , "错误的参数！", 0 ) ;
		}
		$db = M("major_year") ;
		if( FALSE != $db->create($data) )
		{
			if( FALSE != $db->add() )
			{
				$this->ajaxReturn( NULL , "恭喜您插入数据成功！", 1 ) ;
			}
			else{
				$this->ajaxReturn( NULL , "插入数据失败！", 0 ) ;
			}
		}
		else
		{
			$this->ajaxReturn( NULL , "数据验证错误！", 0 ) ;
		}
	}
	public function delete_year()
	{
		if( $_POST["majorid"] )
		{
			$my_db = M("major_year") ;
			$success_num = 0 ;
			$fail_num = 0 ;
			foreach ($_POST["major_year"] as $key => $value) {
				if( false !== $my_db->where("year={$value} and majorid={$_POST["majorid"]}")->delete() )
				{
					$success_num++ ;
				}
				else{
					$fail_num++ ;
				}
			}
			if( $fail_num == 0 )
			{
				$this->ajaxReturn( NULL , "恭喜您删除成功！", 1 ) ;
			}
			else{
				$this->ajaxReturn( NULL , "删除数据成功".$success_num."次成功，".$fail_num."次失败！", 1 ) ;
			}
		}
	}
}
?>
