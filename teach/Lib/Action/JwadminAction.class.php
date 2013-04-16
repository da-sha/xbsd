<?php

/* ------------------------------------------------------------
 * 日期		：2012-11-28
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	; JwadminAction.class.php UTF-8
 * -----------------------------------------------------------
 */

class JwadminAction extends CommonAction {
	public function index() {
		//根据学生层次找出，这个学生层次的所有专业
		$level = $this->_get("level");
		$this->assign("stulevel",$level);
		$db = new MajorModel();
		$major = $db->where("level={$level}")->field('majorid as id, name')->select();
		$this->assign('list', $major );

		$majorid = $this->_get("majorid");
		$year = $this->_get("year");
		$this->assign('const_major', $majorid );
		$classid = $this->_get("classid");
		$db = new ClassModel();
		//根据专业找到专业招收年
		$yearlist = $db->where("majorid={$majorid}")
				->distinct(true)
				->field("register_date as id, register_date as name")
				->select();
		$this->assign('const_year', $year );
		$this->assign('year_list', $yearlist );

		//找到班级列表和已选择的班级
		$class = $db->where("majorid={$majorid} and register_date={$year}")->field("classid as id, name")->select();
		$this->assign('class_list', $class );
		$this->assign('const_class', $classid );
		if (!$classid) {
			$this->assign("show", "请先选择专业、年份、班级！");
		} else {
			$temp = new StudentModel();
			$stu = $temp->table('student, login')
							->where("student.classid={$classid} and student.userid=login.userid")
							->field("login.user_code,
									student.userid,
									student.name,
									get_field_dict_name('student','gender',student.gender) as gender,
									student.admissiondate,
									student.idno,
									student.qq,
									student.email,
									student.telephone")
							->select();
			if( !$stu )
			{
				$this->assign("show", "此班级没有学生");
			}  else {
				$this->assign('stulist', $stu );
			}

			$db = new Model();
			$level = $db->table("major,class")
					->where("class.classid={$classid} and class.majorid=major.majorid")
					->field("major.level")
					->find();
			$this->assign("stulevel", $level['level']);
		}
		$this->display();
	}

	public function getClass() {
		$majorid = $this->_get("majorid");
		$year = $this->_get("year");
		$db = new ClassModel();
		$class = $db->where("majorid={$majorid} and register_date={$year}")->field("classid as id, name")->select();
		if ($class){
			$this->ajaxReturn($class, "查询成功！", 1);
		} else {
			$this->ajaxReturn(0, "对不起没有班级！", 0);
		}
	}

	public function body()
	{
		$classid = $this->_get("classid");
		if (!$classid) {
			$this->assign("show", "请先选择专业、年份、班级！");
		} else {
			$temp = new StudentModel();
			$stu = $temp->table('student, login')
							->where("student.classid={$classid} and student.userid=login.userid")
							->field("login.user_code,
									student.userid,
									student.name,
									get_field_dict_name('student','gender',student.gender) as gender,
									student.admissiondate,
									student.idno,
									student.qq,
									student.email,
									student.telephone")
							->select();
			if( !$stu )
			{
				$this->assign("show", "此班级没有学生");
			}  else {
				$this->assign('stulist', $stu );
			}

			$db = new Model();
			$level = $db->table("major,class")
					->where("class.classid={$classid} and class.majorid=major.majorid")
					->field("major.level")
					->find();
			$this->assign("stulevel", $level['level']);
		}
		$this->display();
	}

	public function add() {
		$level = $this->_get("level");
		//专业列表
		$db = new MajorModel();
		$major = $db->where("level={$level}")
					->field("majorid as id, name")
					->select();
		$this->assign("majorid_list", $major);
		//年级列表
		$db = new MajorModel();
		$year = $db->where("level={$level}")
					->distinct(true)
					->field("register_date as id")
					->select();
		$this->assign("year_list", $year);
		$this->assign("stulevel", $level);

		$day = date('Y');
		$year = array();
		for($i = 0; $i < 4; $i++ ){
			$year[$i]['id']=$day-$i;
			$year[$i]['name']=$day-$i;
		}
		$this->assign("admissiondate_list", $year);
		$this->get_field_dict('student', 'gender', 'gender_list');
		$this->display();
	}

	public function insert() {
		$db = new StudentModel();
		if ($data = $db->create()) {
			$temp = new Model();
			$level = $temp->table("major,class")
					->where("class.classid={$data['classid']} and class.majorid=major.majorid")
					->field("major.level")
					->find();
			//先在login表中创建用户
			$pwd = md5("111111");
			$login = array(
				"user_code"=>$_POST['user_code'],
				"user_psd"=>$pwd
			);
			$temp = new Model("login");
			if ($temp->create($login)) {
				if (false !== $temp->add()) {
					$userid=$temp->getLastInsID();
				}else{
					$this->error('操作失败：(创建用户失败 ' . $db->getError() . ' )',"__URL__/add/level/{$level['level']}");
					return;
				}
			} else {
				$this->error('操作失败：(' . $db->getError() . ' )',"__URL__/add/level/{$level['level']}");
			}
			//插入一个新增的学生
			$db->userid = $userid;
			if (false !== $db->add()) {
				$this->success('恭喜您添加成功！', "__URL__/index/level/{$level['level']}/majorid/{$_POST['majorid']}/year/
													{$_POST['year']}/classid/{$_POST['classid']}");
			} else {
				$deluser=new Model("login");
				$deluser->where("userid={$userid}")->delete();
				$this->error('操作失败：' . $db->getDbError(), "__URL__/add/level/{$level['level']}");
			}
		} else {
			$temp = new MajorModel();
			$level = $_POST['level'];
			$this->error('操作失败:'. $db->getDbError(), "__URL__/add/level/{$level}");
		}
	}


	public function delete() {
		$userid = $this->_get("userid");
		$temp = new Model();
		$level = $temp->table("major,class,student")
				->where("student.userid={$userid} and class.classid=student.classid and class.majorid=major.majorid")
				->field("major.level,
					class.majorid,
					class.register_date,
					student.classid")
				->find();
		$db = new StudentModel();
		if (false !== $db->where("userid={$userid}")->delete()) {
			$this->success('删除学生成功', "__URL__/index/level/{$level['level']}/majorid/{$level['majorid']}
											/year/{$level['register_date']}/classid/{$level['classid']}");
			$db = new Model();
			$db->where("userid={$userid}")->delete();
		} else {
			$this->error("删除失败" . $db->getDbError(), "__URL__/index/level/{$level['level']}/majorid/{$level['majorid']}
											/year/{$level['register_date']}/classid/{$level['classid']}");
		}
	}
}

?>
