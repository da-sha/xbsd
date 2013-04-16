<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-12
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	; ClaccAction.class.php UTF-8
 * -----------------------------------------------------------
 */

class ClassAction extends CommonAction {

	public function index() {
		$this->get_field_dict("major", "level", "level_list") ;
		$major = new MajorModel();
		$major_data = $major->field("majorid, name")->select();
		$this->assign("major_list", $major_data );

		$level = $this->_get("level");
		$this->assign("const_level",$level);
		$majorid = $this->_get("majorid");
		$this->assign('const_major', $majorid );

		$year = $this->_get("year");
		//根据专业找到专业招收年
		$db = new ClassModel();
		$yearlist = $db->where("majorid={$majorid}")
				->distinct(true)
				->field("register_date as id, register_date as name")
				->select();
		$this->assign('const_year', $year );
		$this->assign('year_list', $yearlist );

		if ($majorid && $year && $level) {
			$cls = new ClassModel();
			$where = "majorid={$majorid} and register_date={$year}";
			$count = $cls->where($where)->count();
			import('ORG.Util.Page');
			$page = new Page($count, C('PAGESIZE'));
			$show = $page->show();
			$this->assign("show", $show);
			$data = $cls->where($where)
					->field("classid,
								name,
								get_major_name(majorid) as majorid,
								get_teacher_name(head_teacher) as head_teacher,
								get_teacher_name(instructor) as instructor,
								num,
								get_student_name(moniter) as moniter,
								get_teacher_name(operator) as operator,
								register_date")
					->limit($page->firstRow . ',' . $page->listRows)
					->select();
			if($data){
				$this->assign('class_list', $data);
			}else{
				$this->assign('show', "此专业暂时没有这一级的班级");
			}
		} else {
			$this->assign("show", "请首先选择学生层次、专业、年级！");
		}
		$this->display();
	}

	public function body(){
		$majorid = $this->_get("majorid");
		$year = $this->_get("year");
		$level = $this->_get("level");
		$this->assign("year", $year );

		if ($majorid && $year && $level) {
			$cls = new ClassModel();
			$where = "majorid={$majorid} and register_date={$year}";
			$count = $cls->where($where)->count();
			import('ORG.Util.Page');
			$page = new Page($count, C('PAGESIZE'));
			$show = $page->show();
			$this->assign("show", $show);
			$data = $cls->where($where)
					->field("classid,
								name,
								get_major_name(majorid) as majorid,
								get_teacher_name(head_teacher) as head_teacher,
								get_teacher_name(instructor) as instructor,
								num,
								get_student_name(moniter) as moniter,
								get_teacher_name(operator) as operator,
								register_date")
					->limit($page->firstRow . ',' . $page->listRows)
					->select();
			if($data){
				$this->assign('class_list', $data);
			}else{
				$this->assign('show', "此专业暂时没有这一级的班级");
			}
		} else {
			$this->assign("show", "请首先选择学生层次、专业、年级！");
		}
		$this->display();
	}
	public function delete() {
		$classid = $this->_get("classid");
		if ($classid) {
			$temp = new Model("class, major");
			$data = $temp->where("class.classid={$classid} and class.majorid=major.majorid")
						->field("major.level, class.majorid, class.register_date")
						->find();
			$db = new ClassModel();
			if (false !== $db->where("classid={$classid}")->delete()) {
				$this->success('删除成功', "__URL__/index/majorid/{$data['majorid']}/year/{$data['register_date']}/level/{$data['level']}");
			} else {
				$this->error("删除失败" . $db->getDbError(), "__URL__/index/majorid/{$data['majorid']}/year/{$data['register_date']}/level/{$data['level']}");
			}
		}else{
			$this->error("删除失败");
		}
	}

	public function add() {
		$this->get_field_dict("major", "level", "level_list") ;
		$day = date('Y');
		$year = array();
		for($i = 0; $i < 4; $i++ ){
			$year[$i]['id']=$day-$i;
			$year[$i]['name']=$day-$i;
		}
		$this->assign("register_date_list", $year);
		$this->display();
	}

	public function insert() {
		$db = new ClassModel();
		if ($data = $db->create()) {
			if (false !== $db->add()) {
				$this->success('恭喜您添加班级成功！', "__URL__/index/majorid/{$data['majorid']}/level/{$_POST['level']}/year/{$data['register_date']}");
			} else {
				$this->error('操作失败：' . $db->getDbError(), "__URL__/add");
			}
		} else {
			$this->error('操作失败：数据验证( ' . $db->getError() . ' )');
		}
	}

	public function edit() {
		$classid = $this->_get("classid");
		$db = new Model();
		$level =$db->table("class, major")
				->where("class.classid={$classid} and class.majorid=major.majorid")
				->field("major.level")
				->find();
		$this->assign("level_const", $level['level']);
		$this->get_field_dict("major", "level", "level_list") ;

		$major = new MajorModel();
		$major_data = $major->field("majorid as id, name")->select();
		$this->assign("major_list", $major_data);

		$day = date('Y');
		$year = array();
		for($i = 0; $i < 4; $i++ ){
			$year[$i]['id']=$day - $i;
			$year[$i]['name']=$day- $i;
		}
		$this->assign("register_date_list", $year);

		$db = new ClassModel();
		$data = $db->where("classid={$classid}")
					->field("classid,
								name,
								majorid,
								head_teacher,
								instructor,
								get_teacher_name(head_teacher) as htname,
								get_teacher_name(instructor) as instr,
								num,
								moniter,
								operator,
								register_date")
					->find();
		$this->assign("class", $data);
		$db = new TeacherModel();
		$teacher = $db->field("userid as id, name")->select();
		$this->display();
	}

	public function update() {
		$db = new ClassModel();
		if ($data = $db->create()) {
			if (false !== $db->save()) {
				$this->success('恭喜您编辑班级成功！', "__URL__/index/majorid/{$data[majorid]}/level/{$_POST['level']}/year/{$data['register_date']}");
			} else {
				$this->error('操作失败：' . $db->getDbError(), "__URL__/edit/classid/{$data[classid]}");
			}
		} else {
			$this->error('操作失败：数据验证( ' . $db->getError() . ' )', "__URL__/edit/classid/{$data[classid]}");
		}
	}

	public function tea_class()
	{
		$classdb = M("class") ;
		$data = $classdb->field("classid,name,register_date")->where("head_teacher={$this->get_user_id()}")->select() ;
		if($data)
		{
			$this->assign("class_list", $data ) ;
		}else
		{
			$this->assign( "show" , "对不起，没有找到您指导的班级！" ) ;
		}
		$this->display() ;
	}
}

?>
