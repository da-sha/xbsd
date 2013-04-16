<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-9
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	; StudentAction.class.php UTF-8
 * -----------------------------------------------------------
 */

class StudentAction extends CommonAction {

	public function index(){
		$userid = $this->get_user_id();
		$db = new Model("login, student, class");
		$where = "student.userid={$userid} and login.userid=student.userid and student.classid=class.classid";
		$profile = $db->where($where)
					->field("login.user_code,
						student.name,
						get_field_dict_name('student','gender',student.gender) as gender,
						student.idno,
						get_field_dict_name('student','nationality',student.nationality) as nationality,
						student.birthday,
						get_field_dict_name('student','Politics_status',student.Politics_status) as Politics_status,
						student.admissiondate,
						get_class_name(student.classid) as classname,
						get_major_name(class.majorid) as majorname,
						student.qq,
						student.email,
						student.telephone,
						student.introduction")
					->find();
		$this->assign("stu",$profile);
		$this->display();
	}

	public function edit(){
		$userid = $this->get_user_id();
		$db = new Model("login, student, class");
		$where = "student.userid={$userid} and login.userid=student.userid and student.classid=class.classid";
		$profile = $db->where($where)
					->field("student.userid,
						login.user_code,
						student.name,
						student.gender,
						student.idno,
						student.nationality,
						student.birthday,
						student.Politics_status,
						student.admissiondate,
						get_class_name(student.classid) as classname,
						get_major_name(class.majorid) as majorname,
						student.qq,
						student.email,
						student.telephone,
						student.introduction")
					->find();
		$this->assign("stu",$profile);
		$this->get_field_dict('student', 'gender', 'gender_list');
		$this->get_field_dict('student', 'nationality', 'nationality_list');
		$this->get_field_dict('student', 'Politics_status', 'Politics_status_list');
		$this->display();
	}

	public function update(){
		$db = new StudentModel();
		if ($data = $db->create()) {
			if (false !== $db->save()) {
				$this->success('恭喜您编辑成功！', "__URL__/edit");
			} else {
				$this->error('操作失败：' . $db->getDbError());
			}
		} else {
			$this->error('操作失败：数据验证(' . $db->getError() . ')');
		}
	}
	
	public function get_class_info( $classid )
	{
		//找到班级的基本信息
		$db = new ClassModel();
		$data = $db->field("name,
							get_major_name(majorid) as majorname,
							get_teacher_name(head_teacher) as htname,
							get_teacher_name(instructor) as instr,
							num,
							register_date")
					->find($classid);
		$this->assign("class", $data);

		//找到班级的有关人员及联系号码
		//班主任
		$db = new Model();
		$teacher = $db->table("class, teacher")
				->where("class.classid={$classid} and class.head_teacher=teacher.userid")
				->field("teacher.telphone1 as htphone")
				->find();
		$this->assign("teacherlist", $teacher);
		//辅导员
		$db = new Model();
		$instr = $db->table("class, teacher")
				->where("class.classid={$classid} and class.instructor=teacher.userid")
				->field("teacher.telphone1 as instrphone")
				->find();
		$this->assign("instrlist", $instr);
		//班长
		$db = new StudentModel();
		$moni = $this->sys_config_value("monitor");
		$monitor = $db->where("classid={$classid} and duty={$moni}")
				->field("name as monitor, telephone as monitorphone")
				->find();
		$this->assign("monitor", $monitor);
		//学习委员
		$stud = $this->sys_config_value("study");
		$study = $db->where("classid={$classid} and duty={$stud}")
				->field("name as study, telephone as studyphone")
				->find();
		$this->assign("study", $study);
		//团支书
		$tz = $this->sys_config_value("tzs");
		$tzs = $db->where("classid={$classid} and duty={$tz}")
				->field("name as tzs, telephone as tzsphone")
				->find();
		$this->assign("tzs", $tzs);
		
	}
	public function class_info()
	{
		$classid = $this->_get("classid") ;
		if($classid)
		{
			$this->get_class_info( $classid ) ;
		}  else {
			$this->error("对不起，错误的参数") ;
		}
		$this->display();
	}

	//显示一个学生登陆之后，他所在班级的详细信息
	public function myclass(){
		$userid = $this->get_user_id();
		$db = new StudentModel();
		$classid = $db->field("classid")->find($userid);
		$classid = $classid['classid'];
		$this->get_class_info( $classid ) ;
		$this->display();
	}

	//显示一个学生登陆之后，他所在班级的奖学金、助学金等信息
	public function myaward(){
		$userid = $this->get_user_id();
		$db = new StudentModel();
		$classid = $db->field("classid")->find($userid);

		$classdb = new ClassModel();
		$year = $classdb->field("register_date")->find($classid['classid']);
		$year = $year['register_date'];
		$yearlist = array();
		$i = 0;
		for($i = 0; $i < 4; $i++ ){
			$yearlist[$i]["id"] = $year + $i;
			$yearlist[$i]["year"] = $year + $i;
		}
		$this->assign("yearlist", $yearlist);

		$type = $this->_get("type");
		$this->assign("type", $type);
		$this->assign("show", "请先选择年份！");
		$this->display();
	}

	//显示一个学生登陆之后，他所在班级的奖学金、助学金等具体信息
	public function awardinfo(){
		$year = $this->_get("year");
		$type = $this->_get("type");
		$userid = $this->get_user_id();
		$db = new StudentModel();
		$classid = $db->field("classid")->find($userid);
		$classid = $classid['classid'];

		$db = new Model("student, award");
		$data = $db->where("student.classid={$classid} and student.userid=award.userid and award.year={$year} and award.type={$type}")
					->field("student.name as stuname,
							award.name,
							award.type,
							award.money,
							award.award")
					->select();
		if($data){
			$this->assign("datalist", $data);
		}else{
			$this->assign("show", "暂无信息！");
		}
		$this->assign("type", $type);
		$this->display();
	}


	//显示一个学生登陆之后，他所在班级的班级公告
	public function classnotice() {
		$classid = $this->_get("classid") ;
		if( !$classid )
		{
			$userid = $this->get_user_id();
			$db = new StudentModel();
			$classid = $db->field("classid")->find($userid);
			$classid = $classid['classid'];
		}
		
		$notice = new NoticeModel();
		$where = "notice.type=1 and notice.classid={$classid}";
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
							content,
							get_field_dict_name('notice','importance',importance) as importance,
							update_date")
					->order("update_date desc")
					->limit($page->firstRow . ',' . $page->listRows)
					->select();
		if($data){
			$this->assign('list', $data);
		}else{
			$this->assign('show', "暂无公告！");
		}
		$this->display();
	}
//	public function ce() {
//		$i = 0;
//		$ts=array();
//		for($i = 0; $i < 30; $i++){
//			$ts["admissiondate"] ="201";
//			$ts["classid"] ="35";
//			$ts["user_code"] =$ts["admissiondate"].$ts["classid"].$i;
//			$ts["name"] ="stu".$ts["admissiondate"].$ts["classid"].$i;
//			$ts["gender"] ="1";
//			$ts["idno"] ="622921199105017589";
//			$ts["Politics_status"] ="1";
//			$ts["duty"] = "1";
//			$ts["qq"] ="1043977511";
//			$ts["email"] ="1043977511@qq.com";
//			$ts["telephone"] ="15002530863";
//			$db = new StudentModel();
//			if ($data = $db->create($ts)) {
//				$temp = new Model();
//				$level = $temp->table("major,class")
//						->where("class.classid={$data['classid']} and class.majorid=major.majorid")
//						->field("major.level")
//						->find();
//				//先在login表中创建用户
//				$pwd = md5("111111");
//				$login = array(
//					"user_code"=>$ts['user_code'],
//					"user_psd"=>$pwd
//				);
//				$temp = new Model("login");
//				if ($temp->create($login)) {
//					if (false !== $temp->add()) {
//						$userid=$temp->getLastInsID();
//					}else{
//						echo "插入数据错误";
//					}
//				} else {
//					echo "插入数据错误";
//				}
//				//插入一个新增的学生
//				$db->userid = $userid;
//				if (false !== $db->add()) {
//					echo "插入数据成功\n";
//				} else {
//					echo "插入数据错误";
//					$db=new Model("login");
//					if (false === $db->where("userid={$userid}")->delete()) {
//						echo "插入数据错误";
//					}
//				}
//			} else {
//				$temp = new Model();
//				$level = $temp->table("major,class")
//						->where("class.classid={$_POST['classid']} and class.majorid=major.majorid")
//						->field("major.level")
//						->find();
//				$this->error('操作失败：数据验证( '. $db->getDbError(), "__URL__/index/level/{$level['level']}");
//			}
//		}
//	}
}

?>
