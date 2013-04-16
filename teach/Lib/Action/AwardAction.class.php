<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-20
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	; AwardAction.class.php UTF-8
 * -----------------------------------------------------------
 */

class AwardAction extends CommonAction {
	public function deal_arg( $arglist )
	{
		foreach ($arglist as $value) {
			if( $_GET[$value] )
			{
				session($value , $_GET[$value] ) ;
			}else{
				$_GET[$value] = session( $value ) ;
			}
		}
	}

	public function index() {
		$this->deal_arg( array("level","majorid","year","classid",) ) ;
		$type = $this->_get("type");
		$this->assign("typeconst", $type);
		//得到学生层次
		$level = $this->_get("level");
		$this->get_field_dict("major", "level", "level_list") ;
		$this->assign("level_const", $level);
		//得到专业
		$majorid = $this->_get("majorid");
		$db = new MajorModel();
		$majorlist = $db->where("level={$level}")->field('majorid as id, name')->select();
		$this->assign("major_list", $majorlist);
		$this->assign("const_major", $majorid);
		//得到学年
		$year = $this->_get("year");
		$db = new ClassModel();
		$yearlist = $db->where("majorid={$majorid}")
				->distinct(true)
				->field("register_date as id, register_date as name")
				->select();
		$this->assign("year_list", $yearlist);
		$this->assign("const_classyear", $year);

		$classid = $this->_get("classid");
		$classlist = $db->where("majorid={$majorid} and register_date={$year}")->field("classid as id, name")->select();
		$this->assign("class_list",$classlist);
		$this->assign("const_class", $classid);
		$this->assign("show", "请首先选择学生层次、专业、年级！");
		$this->display();
	}

	public function body()
	{
		$classid = $this->_get("classid");
		$type = $this->_get("type");
		if (!$classid) {
			$this->assign("show", "请先选择专业和班级");
			$this->display();
		} else {
			$temp = new Model("student, award");
			$where = "student.classid={$classid} and award.type={$type} and student.userid=award.userid";
			$count = $temp->where($where)->count();
			import('ORG.Util.Page');
			$page = new Page($count, C('PAGESIZE'));
			$show = $page->show();
			$this->assign("show", $show);
			$award = $temp->where($where)
							->field("award.id,
								    get_student_name(award.userid) as userid,
									award.year,
									award.name,
									award.money,
									award.award,
									get_field_dict_name('award','type',award.type) as type")
							->limit($page->firstRow . ',' . $page->listRows)
							->select();
			if( !$award )
			{
				$this->assign("show", "此班级同学无获奖记录");
				$this->display();
			}  else {
				$this->assign('list', $award);
				$this->display();
			}
		}
	}

	public function add()
	{
		$type = $this->_get("type");
		$this->assign("typeconst", $type);
		$this->get_field_dict("major", "level", "level_list") ;
		$this->get_field_dict("award", "type", "type_list") ;
		$this->get_field_dict('award', 'semester', 'semester_list');
		$day = date('Y');
		$year = array();
		for($i = 0; $i < 4; $i++ ){
			$year[$i]['id']=$day-$i;
			$year[$i]['name']=$day-$i;
		}
		$this->assign("year_list", $year);
		$this->display();
	}

	public function insert() {
		$db = new AwardModel();
		if ($data=$db->create()) {
			if (false !== $db->add()) {
				$this->success('恭喜您添加获奖记录成功！', "__URL__/index/type/{$data['type']}/level/{$_POST['level']}/majorid/{$_POST['majorid']}/
															year/{$_POST['classyear']}/classid/{$_POST['classid']}");
			} else {
				$this->error('操作失败：' . $db->getDbError(), "__URL__/add/type/{$data['type']}");
			}
		} else {
			$this->error('操作失败：数据验证( ' . $db->getError() . ' )',"__URL__/add/type/{$_POST['type']}");
		}
	}

	public function edit() {
		$this->get_field_dict('award', 'semester', 'semester_list');
		$id=$this->_get("id");
		$temp = new AwardModel();
		$type = $temp->field("type")->find($id);
		$this->assign("typeconst", $type['type']);
		$day = date('Y');
		$year = array();
		for($i = 0; $i < 4; $i++ ){
			$year[$i]['id']=$day-$i;
			$year[$i]['name']=$day-$i;
		}
		$this->assign("year_list", $year);
		$db = new AwardModel();
		$award= $db->where("id={$id}")
					->field("id,
							get_student_name(userid) as userid,
							year,
							name,
							money,
							award")
					->find();
		if($award){
			$this->assign('data', $award);
		}else{
			$this->error('对不起出错了，找不到该条获奖记录');
		}
		$this->display();
	}

	public function update() {
		$db = new AwardModel();
		if ($data = $db->create()) {
			if (false !== $db->save()) {
				$temp = new Model("award, student, class, major");
				$tempdata = $temp->where("award.id={$data['id']} and award.userid=student.userid and
										student.classid=class.classid and class.majorid=major.majorid")
								->field("major.level, major.majorid, class.classid, class.register_date")
								->find();

				$this->success('恭喜您编辑成功！', "__URL__/index/type/{$_POST['type']}/level/{$tempdata['level']}
												/majorid/{$tempdata['majorid']}/year/{$tempdata['register_date']}/classid/{$tempdata['classid']}");
			} else {
				$this->error('操作失败：' . $db->getDbError(), "__URL__/edit/id/{$data['id']}");
			}
		} else {
			$this->error('操作失败：数据验证( ' . $db->getError() . ' )');
		}
	}

	public function delete() {
		$id = $this->_get("id");
		$temp = new Model("award, student, class, major");
		$tempdata = $temp->where("award.id={$id} and award.userid=student.userid and
										student.classid=class.classid and class.majorid=major.majorid")
							->field("award.type, major.level, major.majorid, class.classid, class.register_date")
							->find();
		$db = new AwardModel();
		if (false !== $db->where("id={$id}")->delete()) {
			$this->success('删除成功', "__URL__/index/type/{$tempdata['type']}/level/{$tempdata['level']}
												/majorid/{$tempdata['majorid']}/year/{$tempdata['register_date']}/classid/{$tempdata['classid']}");
		} else {
			$this->error("删除失败" . $db->getDbError(), "__URL__/index/type/{$tempdata['type']}/level/{$tempdata['level']}
												/majorid/{$tempdata['majorid']}/year/{$tempdata['register_date']}/classid/{$tempdata['classid']}");
		}
	}

	public function showaward()
	{
		$classid = $this->_get("classid");
		$type = $this->_get("type");
		if (!$classid) {
			$this->assign("show", "请先选择专业和班级");
			$this->display();
		} else {
			$temp = new Model("student, award");
			$where = "student.classid={$classid} and award.type={$type} and student.userid=award.userid";
			$count = $temp->where($where)->count();
			import('ORG.Util.Page');
			$page = new Page($count, C('PAGESIZE'));
			$show = $page->show();
			$this->assign("show", $show);
			$award = $temp->where($where)
							->field("award.id,
								    get_student_name(award.userid) as userid,
									award.year,
									award.name,
									award.money,
									award.award,
									get_field_dict_name('award','type',award.type) as type")
							->limit($page->firstRow . ',' . $page->listRows)
							->select();
			if( !$award )
			{
				$this->assign("show", "此班级同学无获奖记录");
				$this->display();
			}  else {
				$this->assign('list', $award);
				$this->assign('classid', $classid);
				$this->assign('type', $type);
				$this->display();
			}
		}
	}

	public function addaward(){
		$classid = $this->_get("classid");
		$db = new ClassModel();
		$classname = $db->field("name as classname")->find($classid);
		$this->assign("classname", $classname['classname']);
		$type = $this->_get("type");
		if(!$classid && !$type){
			$this->error("参数有误！");
			return;
		}
		$db = new StudentModel();
		$stu = $db->where("classid={$classid}")->field("userid as id, name")->select();
		if($stu){
			$this->assign("name_list", $stu);
		}
		//学年
		$day = date('Y');
		$year = array();
		for($i = 0; $i < 4; $i++ ){
			$year[$i]['id']=$day-$i;
			$year[$i]['name']=$day-$i;
		}
		$this->assign("year_list", $year);
		//学期
		$this->get_field_dict("award", "semester", "semester_list") ;
		$this->assign("type", $type);
		$this->display();
	}

	public function insertaward(){
		$db = new AwardModel();
		$temp = new StudentModel();
		$classid = $temp->field("classid")->find($_POST['userid']);
		if ($data=$db->create()) {
			if (false !== $db->add()) {
				$this->success('恭喜您添加获奖记录成功！', "__URL__/showaward/classid/{$classid['classid']}/type/{$data['type']}");
			} else {
				$this->error('操作失败：' . $db->getDbError(), "__URL__/classaward/classid/{$classid['classid']}/type/{$_POST['type']}");
			}
		} else {
			$this->error('操作失败：数据验证( ' . $db->getError() . ' )', "__URL__/classaward/classid/{$classid['classid']}/type/{$_POST['type']}");
		}
	}

	public function deleteaward() {
		$id = $this->_get("id");
		$temp = new Model("award, student");
		$tempdata = $temp->where("award.id={$id} and award.userid=student.userid ")
							->field("award.type, student.classid")
							->find();
		$db = new AwardModel();
		if (false !== $db->where("id={$id}")->delete()) {
			$this->success('删除成功', "__URL__/showaward/type/{$tempdata['type']}/classid/{$tempdata['classid']}");
		} else {
			$this->error("删除失败" . $db->getDbError(), "__URL__/showaward/type/{$tempdata['type']}/classid/{$tempdata['classid']}");
		}
	}

	public function editaward() {
		$id = $this->_get("id");
		$temp = new Model("award, student");
		$tempdata = $temp->where("award.id={$id} and award.userid=student.userid ")
							->field("award.id, award.type, student.classid")
							->find();
		$temp = new ClassModel();
		$classname = $temp->field("name as classname")->find($tempdata['classid']);
		$this->assign("classname", $classname['classname']);
		//学生列表
		$db = new StudentModel();
		$stu = $db->where("classid={$tempdata['classid']}")->field("userid as id, name")->select();
		if($stu){
			$this->assign("name_list", $stu);
		}
		//学年
		$day = date('Y');
		$year = array();
		for($i = 0; $i < 4; $i++ ){
			$year[$i]['id']=$day-$i;
			$year[$i]['name']=$day-$i;
		}
		$this->assign("year_list", $year);
		//学期
		$this->get_field_dict("award", "semester", "semester_list") ;
		$db = new AwardModel();
		$award = $db->field("id,
							userid,
							year,
							semester,
							name,
							money,
							award,
							type")
					->find($id);
		$this->assign("award", $award);
		$this->display();
	}

	public function updateaward() {
		$db = new AwardModel();
		if ($data = $db->create()) {
			$temp = new Model("award, student");
			$tempdata = $temp->where("award.id={$data['id']} and award.userid=student.userid ")
							->field("award.type, student.classid")
							->find();
			if (false !== $db->save()) {
				$this->success('恭喜您编辑成功！', "__URL__/showaward/type/{$_POST['type']}/classid/{$tempdata['classid']}");
			} else {
				$this->error('操作失败：' . $db->getDbError(), "__URL__/editaward/id/{$data['id']}");
			}
		} else {
			$this->error('操作失败：数据验证( ' . $db->getError() . ' )', "__URL__/editaward/id/{$_POST['id']}");
		}
	}
}

?>
