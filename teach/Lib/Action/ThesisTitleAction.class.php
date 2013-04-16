<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-22
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	; ThesisTitleAction.class.php UTF-8
 * -----------------------------------------------------------
 */

class ThesisTitleAction extends CommonAction {

	protected $db, $type, $thesis_info;

	/**
	 * @see CommonAction::_initialize()
	 */
	public function _initialize() {
		$this->db = M('thesis_title');
		$this->category = $_GET['category'];
		$this->assign("category", $this->category);
		$this->assign("teacherid", $this->get_user_id());
		parent::_initialize();
	}

	public function deal_argc() {
		//如果get没有值，尝试从session取值
		$arg = array( "result", "year", "category","majorid",) ;
		foreach ($arg as $key => $value) {
			if( !$_GET[$value] )
			{
				$_GET[$value] = session($value) ;
			}
			else
			{
				 session( $value , $_GET[$value] ) ;
			}
		}
		$this->assign("category", $this->category ) ;
		//专业列表
		$major = new MajorModel();
		$major_data = $major->field("majorid, name")->select();
		$this->assign("major_list", $major_data );
		//年级列表
		if( $majorid = $this->_get("majorid") )
		{
			$majoryear = M("major_year") ;
			$year_list = $majoryear->field("year as id,year as name")->where("majorid={$majorid}")->select();
			$this->assign("year_list", $year_list);
		}
		$this->get_field_dict("thesis_title", "result", "result_list") ;
		$result = $this->_get("result") ;
		$grade = $this->_get("year") ;
		if( !$majorid || !$grade )
		{
			$this->assign( "show", "对不起，请选择论专业和学年！" ) ;
			return false;
		}
		return array("majorid" => $majorid, "result" => $result, "grade" => $grade);
	}

	public function index_jwadmin() {
		$data = $this->deal_argc() ;
		if( $data === false )
		{
			$this->display();
			return ;
		}
		$where = "category={$this->category} and grade={$data['grade']} and majorid={$data['majorid']} and result={$data['result']}";
		if (false === $this->search_thesis($where)) {
			$this->display();
			return;
		}
		$this->get_sys_config("thesis_result_wait", "thesis_result_wait"); //1
		$this->get_sys_config("thesis_charge_result_pass", "thesis_charge_result_pass"); //2
		$this->get_sys_config("thesis_dean_result_pass", "thesis_dean_result_pass");  //3
		$this->get_sys_config("thesis_result_failed", "thesis_result_failed"); //4
		$this->display();
	}
	
	public function index_teacher() {
		if( !$_GET["year"] )
		{
			$_GET["year"] = session("year");
		}
		else {
			session( "year" , $_GET["year"] );
		}
		$year_list = array() ;
		$Y = Date("Y") ;
		for( $i = 2009 ; $i <= $Y ; $i++ )
		{
			array_push( $year_list, $i ) ;
		}
		$this->assign( "year_list" , $year_list ) ;
		$where = "category={$this->category} and grade={$_GET["year"]} and teacherid={$this->get_user_id()}";
		if (false === $this->search_thesis($where)) {
			$this->display() ;
			return;
		}
		$this->get_sys_config("thesis_result_wait", "thesis_result_wait"); //1
		$this->get_sys_config("thesis_charge_result_pass", "thesis_charge_result_pass"); //2
		$this->get_sys_config("thesis_dean_result_pass", "thesis_dean_result_pass");  //3
		$this->get_sys_config("thesis_result_failed", "thesis_result_failed"); //4
		$this->display() ;
	}

	public function search_thesis($where) {
		$count = $this->db->where($where)->count();
		if ($count == 0) {
			$this->assign("show", "当前没有数据！");
			return FALSE;
		}
		import('ORG.Util.Page');
		$page = new Page($count, C('PAGESIZE'));
		$show = $page->show();
		$this->assign("show", $show);
		$thesis = $this->db->where($where)
				->field("thesisid,get_teacher_name(teacherid) as teacherid,
					name,content,get_field_dict_name('thesis_title','type',type) as type,
					max_num,select_num,result,grade,get_major_name(majorid) as majorid,
					get_field_dict_name('thesis_title','result',result) as result_name,
					comment,get_field_dict_name('thesis_title','category',category) as category,
					get_field_dict_name('thesis_title','level',level) as level")
				->limit($page->firstRow . ',' . $page->listRows)
				->select();
		$this->assign('list', $thesis);
		return TRUE;
	}

	public function index_dean() {
		if (!( $data = $this->deal_argc() )) {
			$this->display() ;
			return;
		}
		$where = "result={$data['result']} and category={$this->category} and majorid={$data['majorid']} and grade={$data['grade']}";
		$this->search_thesis($where);
		$this->get_sys_config("thesis_charge_result_pass", "thesis_charge_result_pass");
		$this->display();
	}

	public function mythesis() { //学生自己的论文信息
		$category = $this->_get("category");
		if (!$category) {
			$this->error("错误的参数");
			return;
		}
		$db = M("student,class");
		//学生基本信息
		$data = $db->field("
			student.name as stu_name,
			get_field_dict_name('student','gender' ,gender) as gender_name,
			class.name as cla_name,
			student.admissiondate,
			get_major_name(majorid) as majorid_name
			")->where("student.userid={$this->get_user_id()} and student.classid=class.classid")->find();
		if (false !== $data) {
			$this->assign("student", $data);
		}
		//获取导师信息
		$thesis_taskdb = M("thesis_task,guide_task");
		$guide_teacher = $thesis_taskdb->field("teacherid,assign_num")->where("thesis_task.studentid={$this->get_user_id()} and thesis_task.category={$this->category} and thesis_task.guideid=guide_task.guideid")->find();
		if ($guide_teacher) {
			$teacherdb = M("teacher");
			$teacherdata = $teacherdb->field("name,get_org_name(orgid) as  orgid_name,
				get_field_dict_name('teacher','job_title',job_title) as job_title,research,telphone1")
					->find($guide_teacher['teacherid']);
			if (false !== $teacherdata) {
				$this->assign("teacher", $teacherdata);
			}
		}
		//本论文基本信息
		$thesisdb = M("thesis,thesis_title");
		$where = "thesis_title.category=$category and thesis.userid={$this->get_user_id()} and thesis.thesisid=thesis_title.thesisid";
		$thesisdata = $thesisdb->field("
			thesis.thesisid,thesis.userid,teacherid,get_field_dict_name('thesis','state',state) as state_name, state,
			title_source, word_num, deadline, first_score, final_score, thesis.comment, teacherid, name,
			content, get_field_dict_name('thesis_title','type',type) as type_name, category
			")->where($where)->find();
		$this->assign("mythesis", $thesisdata);

		if ($thesisdata) {
			if ($thesisdata['state'] == $this->sys_config_value("student_thesis_state_failed")
					|| $thesisdata['state'] == $this->sys_config_value("student_thesis_state_wait")) {
				$this->assign("can_withdraw", true);
			}
			$this->assign("show", "当前论文状态：" . $thesisdata['state_name']);
		} else {
			$this->assign("show", "<span class='red'>对不起，你没有目前没有选择论文！</span>");
		}
		$this->display();
	}

	/**
	 * 添加论文选题
	 */
	public function add() {
		$category = $this->_get("category");
		if (!$category) {
			$this->error("错误的参数！");
			return;
		}
		$graduation_thesis = $this->sys_config_value("graduation_thesis");
		$term_thesis = $this->sys_config_value("term_thesis");
		switch ($category) {
			case $graduation_thesis:
				if (!$this->sys_config_value("allow_add_graduation_thesis_title")) {
					$this->error("对不起，目前不允许添加毕业论文选题！");
					return;
				}
				break;
			case $term_thesis:
				if (!$this->sys_config_value("allow_add_term_thesis_title")) {
					$this->error("对不起，目前不允许添加学年论文选题！");
					return;
				}
				break;
			default:
				$this->error("对不起，哥没有见过你！");
				return;
				break;
		}
		$this->assign("category_value", $category);
		$this->get_field_dict('thesis_title', 'type', 'type_list');
		$this->get_field_dict('thesis_title', 'level', 'level_list');

		$model = new DataDictModel();
		$data = $model->field('fd_mean as name')->where("tb_name='thesis_title' and fd_name='category' and fd_value={$category}")->find();
		if ($data != false) {
			$this->assign("category_name", $data['name']);
		}

		$major_db = new MajorModel();
		$major = $major_db->field('majorid as id, name')->select();
		$this->assign('major_list', $major);
		$this->display("edit");
	}

	public function delete() {
		$thesisid = $this->_get("thesisid");
		if (!$thesisid) {
			$this->error("对不起，参数错误！");
			return;
		}
		$data = $this->db->find($thesisid);
		if ($data['result'] == $this->sys_config_value("thesis_charge_result_pass") and
				$this->get_user_type() == $this->sys_config_value("role_type_teacher")) {
			$this->error("审核已经通过，不允许删除！");
			return;
		} else {
			if (false !== $this->delete_select($thesisid)) {
				if (false !== $this->db->delete($thesisid)) {
					$this->success("删除成功！");
				} else {
					$this->error("删除失败！");
				}
			} else {
				$this->error("删除失败！");
			}
		}
	}

	private function delete_select($thesisid) {
		$db = M("thesis");
		$where = "thesisid={$thesisid}";
		if (false !== $db->where($where)->delete()) {
			$data['select_num'] = 0;
			$data['thesisid'] = $thesisid;
			$this->db->save($data);
			return true;
		}
		return false;
	}

	public function insert() {
		$category = $_POST["category"];
		if ($category) {
			$graduation_thesis = $this->sys_config_value("graduation_thesis");
			$term_thesis = $this->sys_config_value("term_thesis");
			switch ($category) {
				case $graduation_thesis:
					if (!$this->sys_config_value("allow_add_graduation_thesis_title")) {
						$this->error("对不起，目前不允许添加毕业论文选题！");
						return;
					}
					break;
				case $term_thesis:
					if (!$this->sys_config_value("allow_add_term_thesis_title")) {
						$this->error("对不起，目前不允许添加学年论文选题！");
						return;
					}
					break;
				default:
					$this->error("对不起，未知的参数！");
					return;
					break;
			}
		}
		$_POST["teacherid"] = $this->get_user_id();
		$_POST["select_num"] = 0;
		$db = new ThesisTitleModel();
		if (false !== $db->create()) {
			if (false !== $db->add()) {
				$this->success("添加论文题目成功，提交审核中！！", "__URL__/index_teacher/majorid/{$_POST['majorid']}/year/{$_POST['grade']}/category/{$_POST['category']}/result/1");
			}
		}
	}

	/**
	 *
	 */
	public function edit() {
		$this->assignthesis();
		if ($this->thesis_info['result'] == $this->sys_config_value("thesis_charge_result_pass")) {
			$this->error("审核已经通过，不允许修改！");
			return;
		}
		$db = M("major_year");
		$year = $db->field("year")->where("majorid={$this->thesis_info['majorid']}")->select();
		if ($year) {
			$this->assign("year_list", $year);
		}
		$this->get_field_dict('thesis_title', 'type', 'type_list');
		$this->get_field_dict('thesis_title', 'level', 'level_list');
		$major_db = new MajorModel();
		$major = $major_db->field('majorid as id, name')->select();
		$this->assign('major_list', $major);
		$this->display();
	}

	public function update() {
		/* 如果是教师编辑过之后将其重新设为待审核状态 */
		$thesisid = $_POST['thesisid'];
		$data = $this->db->find($thesisid);
		if ($data['result'] == $this->sys_config_value("thesis_charge_result_pass")) {
			$this->error("审核已经通过，不允许修改！");
			return;
		} else {
			if (false === $this->delete_select($thesisid)) {
				$this->error("对不起，删除选课失败！");
				return;
			}
			$_POST['result'] = $this->sys_config_value("thesis_result_wait");
			$_POST['comment'] = "";
		}
		if (false !== $this->db->create()) {
			if (false !== $this->db->save()) {
				$this->success("修改论文题目成功，提交审核中！", "__URL__/index_teacher/majorid/{$_POST['majorid']}/year/{$_POST['grade']}/category/{$_POST['category']}/result/1");
			} else {
				$this->error("修改论文题目失败！");
			}
		} else {
			$this->error("数据校验失败！");
		}
	}

	/**
	 *
	 */
	public function select() {
		if ($this->getGroupId() != 4) {
			$this->error("只有学生可以选修");
			return;
		}
		switch ($this->category) {
			case $this->sys_config_value("graduation_thesis"):
				if (!$this->sys_config_value("allow_student_select_graduation_thesis")) {
					$this->assign("show", "<span class='red'>对不起，目前不允许选择毕业论文选题！</span>");
					$this->display();
					return;
				}
				break;
			case $this->sys_config_value("term_thesis"):
				if (!$this->sys_config_value("allow_student_select_term_thesis")) {
					$this->assign("show", "<span class='red'>对不起，目前不允许选择学年论文选题！</span>");
					$this->display();
					return;
				}
				break;
			default:
				$this->error("对不起，未知的参数！");
				return;
				break;
		}
		//查找该学生导师信息
		$thesis_taskdb = M("thesis_task,guide_task");
		$guide_teacher = $thesis_taskdb
				->field("teacherid,assign_num,majorid,grade,assign_num")
				->where("thesis_task.studentid={$this->get_user_id()}
					and thesis_task.category={$this->category}
					and thesis_task.guideid=guide_task.guideid")
				->find();
		if (!$guide_teacher) {
			$this->assign("show", "对不起，您目前没有安排导师！");
			$this->display();
			return;
		}
		$teacherdb = M("teacher");
		$teacherdata = $teacherdb->field("name,get_org_name(orgid) as  orgid_name,
			get_field_dict_name('teacher','job_title',job_title) as job_title,research,telphone1")
				->find($guide_teacher['teacherid']);
		if (false !== $teacherdata) {
			$this->assign("teacher", $teacherdata);
			//获取导师题目
			$thesis_titledb = M("thesis_title");
			$where = "teacherid={$guide_teacher['teacherid']}
				and category={$this->category}
				and grade={$guide_teacher['grade']}
				and majorid={$guide_teacher['majorid']}";
			$count = $thesis_titledb->where($where)->count();
			if ($count == 0) {
				$this->assign("show", "对不起，当前没有针对您的论文题目！");
				$this->display();
				return;
			}
			import('ORG.Util.Page');
			$page = new Page($count, C('PAGESIZE'));
			$show = $page->show();
			$this->assign("show", $show);
			$title_data = $thesis_titledb->field("thesisid,name,
				get_field_dict_name('thesis_title','type',type) as type,
				grade,get_major_name(majorid) as majorid,
				get_field_dict_name('thesis_title','level',level) as level,
				get_field_dict_name('thesis_title','result',result) as result_name,result,
				select_num,max_num")
					->where($where)
					->select();
			if ($title_data) {
				$this->assign("title_list", $title_data);
			}
		}
		$this->get_sys_config("thesis_dean_result_pass", "thesis_dean_result_pass");  //3
		$this->display();
	}

	public function approve() {
		$this->assignthesis();
		$this->display();
	}

	/**
	 * 批准
	 */
	public function do_approve() {
		$_POST['result'] = $this->sys_config_value("thesis_charge_result_pass");
		$this->save_ajax_post();
	}

	/**
	 * 不予批准
	 */
	public function do_not_approve() {
		$_POST['result'] = $this->sys_config_value("thesis_result_failed");
		$this->save_ajax_post();
	}

	/**
	 * 执行选修论文动作
	 */
	public function do_select() {
		$thesisid = $_GET['thesisid'];
		if (!$thesisid) {
			$this->ajaxReturn(NULL, "错误的参数！", 0);
			return;
		}
		//查询该学生目前有没有选论文
		$thests = $this->db->field("category")->find($thesisid);
		$thesisdb = M("thesis,thesis_title");
		$data = $thesisdb->field("thesis_title.name")->where("thesis.userid={$this->get_user_id()} and thesis.thesisid=thesis_title.thesisid and thesis_title.category={$thests['category']}")->find();
		if ($data) {
			$this->ajaxReturn(NULL, "您已选<span class='green'>" . $data['name'] . "</span>论文，该类型不能重复选择！", 0);
			return;
		}
		if ($this->db->where("thesisid={$thesisid} and max_num>select_num and result={$this->sys_config_value('thesis_dean_result_pass')}")->setInc('select_num')) {
			$data = array('thesisid' => $thesisid,
				'userid' => $this->get_user_id(),
				'state' => $this->sys_config_value("student_thesis_state_wait"));
			$user_thesis = M('thesis');
			$ret = $user_thesis->create($data);
			if (FALSE !== $ret && false !== $user_thesis->add()) {
				$this->ajaxReturn(NULL, "您已选题成功", 1);
			} else {//选题失败，回退
				$this->db->where("thesisid={$thesisid} and select_num>0")->setDec('select_num');
				$this->ajaxReturn(NULL, "对不起，选题失败！", 0);
			}
		} else {
			$this->ajaxReturn($this->db->getLastSql(), "对不起，该论文人数已满！", 0);
		}
	}

	/**
	 * 退选课程
	 */
	public function withdraw() {
		$thesisid = $_GET['thesisid'];
		$thesisdb = M("thesis");
		$where = "thesisid={$thesisid} and userid={$this->get_user_id()}";
		$data = $thesisdb->where($where)->find();
		if ($data['state'] == $this->sys_config_value("student_thesis_state_pass")) {
			$this->ajaxReturn(NULL, "对不起，题目已经通过，不允许删除！", 0);
			return;
		}
		$ret = $thesisdb->where($where)->delete();
		if ($ret < 1) {
			$this->ajaxReturn($db->getLastSql(), "对不起，删除选题失败！", 0);
			return;
		}
		if ($data['state'] == $this->sys_config_value("student_thesis_state_wait")) {
			$this->db->where("thesisid={$thesisid} and select_num>0")->setDec("select_num");
		}
		$this->ajaxReturn(NULL, "退选成功", 1);
	}

	//输出论文信息
	private function assignthesis($show_comment = TRUE) {
		$thesisid = $this->_get("thesisid");
		if (!$thesisid) {
			$this->error("错误的参数！");
			return;
		}
		$this->thesis_info = $this->db->field("
			thesisid,get_teacher_name(teacherid) as teacherid,name,content,
			get_field_dict_name('thesis_title','type',type) as type_name,type,max_num,select_num,result,
			get_field_dict_name('thesis_title','result',result) as result_name,comment,category,
			get_field_dict_name('thesis_title','category',category) as category_name,
			grade,majorid,get_major_name(majorid) as majorid_name,level,
			get_field_dict_name('thesis_title','level',level) as level_name,dean_comment
			")->find($thesisid);
		if (!$this->thesis_info) {
			$this->error("无效的论文选题！");
			return;
		}
		if ($show_comment) {
			$this->assign("show_comment", TRUE);
		}
		$this->assign("thesis", $this->thesis_info);
	}

	private function save_ajax_post() {//保存post过来的审批结果
		$db = new ThesisTitleModel();
		if ($db->create() && $db->save()) {
			$this->ajaxReturn(NULL, "审批成功！", 1);
		} else {
			$this->ajaxReturn(NULL, "审批失败！" . $db->getLastSql(), 0);
		}
	}

	//查看当前论文信息机当前论文选修学生
	public function view() {
		$this->assignthesis();
		$db = M("thesis");
		$student = $db->field("get_student_name(userid) as userid_name,userid,
				get_field_dict_name('thesis','state',state) as state_name,
				state")
				->where("thesisid={$this->_get("thesisid")}")
				->select();
		$this->assign("student_list", $student);
		$this->assign("student_thesis_state_wait", $this->sys_config_value("student_thesis_state_wait"));
		$this->display();
	}

	//得到某个机构下所有的教师节信息
	public function getTeacher() {
		$orgid = $this->_get("orgid");
		if ($orgid) {
			$db = new TeacherModel();
			$teacher = $db->table("teacher")
							->where("orgid={$orgid}")
							->field("userid as id,telphone1, get_field_dict_name('teacher','job_title',job_title) as job_title ,name, get_user_code(userid) as code")->select();
			if ($teacher) {
				$this->ajaxReturn($teacher, "查询成功！", 1);
			} else {
				$this->ajaxReturn(0, "查询失败！", 0);
			}
		} else {
			$this->ajaxReturn(0, "查询失败！", 0);
		}
	}

	public function selectteacher() {
		$db = new OrganizeModel();
		$org = $db->where("property=1")->field("orgid as id, name")->select();
		$this->assign("orgid_list", $org);
		$this->display();
	}

	public function thesistask() {
		$major = new MajorModel();
		$major_data = $major->field("majorid, name")->select();
		$this->assign("major_list", $major_data);
		//年级列表
		if ($majorid = session("majorid")) {
			$majoryear = M("major_year");
			$year_list = $majoryear->field("year as id,year as name")->where("majorid={$majorid}")->select();
			$this->assign("year_list", $year_list);
		}
		$this->assign("show", "没有数据，请选择！");
		$this->assign("guide_year", date("Y"));
		$this->get_field_dict("thesis_task", "semester", "semester_list");
		if (date("m") > $this->sys_config_value("semester_cut_off_line")) {
			$this->assign("cur_semester", $this->sys_config_value("autumn_semester"));
		} else {
			$this->assign("cur_semester", $this->sys_config_value("spring_semester"));
		}
		$this->display();
	}

	public function getstudent() {
		//获取此专业下的所有学生
		$studnet_db = M("student,class");
		$student = $studnet_db->field("userid as id,student.name,get_user_code(userid) as user_code")
						->where("class.majorid={$this->_get("majorid")} and class.register_date={$this->_get("year")}
							and student.classid=class.classid
							and userid NOT IN
							(
								SELECT studentid FROM thesis_task,guide_task
								WHERE
								guide_task.majorid={$this->_get("majorid")}
								AND guide_task.grade={$this->_get("year")}
								AND guide_task.category={$this->category}
								AND thesis_task.guideid=guide_task.guideid
							)"
						)->select();
		if ($student !== false) {
			$this->ajaxReturn($student, "查询成功", 1);
		} else {
			$this->ajaxReturn(NULL, "查询失败！" . $studnet_db->getDbError(), 0);
		}
	}

	public function arrange_student() {
		//dump($_POST) ;
		$guide_task_info['category'] = $_POST["category"];
		$guide_task_info['year'] = $_POST["guide_year"];
		$guide_task_info['semester'] = $_POST["guide_semester"];
		$guide_task_info['majorid'] = $_POST["majorid_id"];
		$guide_task_info['grade'] = $_POST["grade"];
		$guide_taskdb = M("guide_task");

		$guide_task_succeed = 0;
		$guide_task_failed = 0;
		$thesis_task_succeed = 0;
		$thesis_task_failed = 0;

		foreach ($_POST["arrange"] as $key => $guide_value) {
			$guide_task_info['teacherid'] = $guide_value['teacher_id'];
			$guide_task_info['assign_num'] = count($guide_value['student_list']);
			$guide_task_info['real_num'] = $guide_task_info['assign_num'];
			//$guide_task_info['real_num']这个有问题
			if ($guide_task_info['assign_num'] > 0 && FALSE !== $guide_taskdb->create($guide_task_info)) {
				if (FALSE !== $guide_taskdb->add()) {
					$guide_task_succeed++;
					$thesis_task_info['guideid'] = $guide_taskdb->getLastInsID();
					$thesis_task = M("thesis_task");
					foreach ($guide_value['student_list'] as $key1 => $thesis_value) {
						$thesis_task_info['category'] = $guide_task_info['category'];
						$thesis_task_info['studentid'] = $thesis_value;
						if (false !== $thesis_task->create($thesis_task_info)) {
							if (false !== $thesis_task->add()) {
								$thesis_task_succeed++;
							} else {
								$thesis_task_failed++;
							}
						} else {
							$thesis_task_failed++;
						}
					}
				} else {
					$guide_task_failed++;
					$thesis_task_failed += count($guide_value['student_list']);
				}
			} else {
				$guide_task_failed++;
				$thesis_task_failed += count($guide_value['student_list']);
			}
		}
		if ($thesis_task_failed > 0 || $guide_task_failed > 0) {
			$this->ajaxReturn(NULL, "安排论文指导组" . $guide_task_failed . "次失败！安排学生" . $thesis_task_failed . "次失败！", 0);
		} else {
			$this->ajaxReturn(NULL, "安排论文成功！", 1);
		}
	}

	public function thesistaskview() {
		$this->assign("category", $this->category);
		if ($_GET['majorid'] && $_GET['year']) {
			session("majorid", $this->_get("majorid"));
			session("year", $this->_get("year"));
		}
		if (!$_GET['majorid'] && !$_GET['year']) {
			$_GET['majorid'] = $_SESSION["majorid"];
			$_GET['year'] = $_SESSION["year"];
		}
		//专业列表
		$major = new MajorModel();
		$major_data = $major->field("majorid, name")->select();
		$this->assign("major_list", $major_data);
		//年级列表
		if ($majorid = $this->_get("majorid")) {
			$majoryear = M("major_year");
			$year_list = $majoryear->field("year as id,year as name")->where("majorid={$majorid}")->select();
			$this->assign("year_list", $year_list);
		}
		if (!$majorid || !$year = $this->_get("year") || $this->_get("category")) {
			$this->assign("show", "请选择专业学生！");
			$this->display();
			return;
		}

		$db = M("guide_task,teacher");
		$task = $db->where("guide_task.majorid={$majorid} and guide_task.grade={$this->_get("year")} and guide_task.category={$this->_get("category")} and guide_task.teacherid=teacher.userid")
						->field("teacher.telphone1, guideid, get_field_dict_name('teacher','job_title',job_title) as job_title, teacherid, guide_task.year, guide_task.semester,teacher.name,guide_task.assign_num,guide_task.real_num")->select();
		$thesis_taskdb = M("thesis_task");
		$guide_taskdb = M("guide_task");
		foreach ($task as $key => $value) {
			$temp = $guide_taskdb->field("SUM(assign_num) AS num")
					->where("teacherid ={$value['teacherid']} AND year={$value['year']} AND semester={$value['semester']}")
					->find();
			$task[$key]['sum_num'] = $temp['num'];
			$temp = $thesis_taskdb->field("GROUP_CONCAT( get_student_name(studentid) ) as student_list")->where("guideid={$value['guideid']}")->find();
			$task[$key]['student_list'] = $temp['student_list'];
		}
		//dump($task) ;
		$this->assign("task_list", $task);
		if ($task == NULL) {
			$this->assign("show", "对不起，当前没有安排论文！");
		}
		$this->display();
	}

	public function deleteguide() {
		$guideid = $this->_get("guideid");
		//获取该选题下所有学生论文选题ID,然后删除所有相关信息
		$db = M("thesis_task,thesis,thesis_title") ;
		$data = $db->field("thesis_title.thesisid,thesis.userid")
				->where("guideid=$guideid and studentid=userid 
					and thesis.thesisid=thesis_title.thesisid 
					and thesis_task.category=thesis_title.category")->select() ;
		$thesis_processdb = M("thesis_process") ;
		$thesis_processdb->where("guideid=$guideid")->delete() ;
		$thesisdb = M("thesis") ;
		foreach ($data as $key => $value) {
			$where = "thesisid={$value['thesisid']} and userid={$value['userid']}" ;
			$this->db->where("thesisid={$value['thesisid']}")->setDec("select_num") ;
			$thesis_processdb->where( $where )->delete() ;
			$thesisdb->where( $where )->delete() ;
		}
		$db = M("thesis_task");
		if (false !== $db->where("guideid={$guideid}")->delete()) {
			$db = M("guide_task");
			if (false !== $db->where("guideid={$guideid}")->delete()) {
				$this->success("删除成功！");
			} else {
				$this->success("部分删除失败！");
			}
		} else {
			$this->error("删除失败！");
		}
	}

	public function thesis_title_view() {
		$this->assignthesis(false);
		if ($this->thesis_info['result'] !== $this->sys_config_value("thesis_dean_result_pass")) {
			$this->assign("have_pass", FALSE);
			$this->assign("show", "该论文正在内部审核中。。。");
		} else {
			$this->assign("have_pass", true);
		}
		$db = M("thesis");
		$student = $db->field("get_student_name(userid) as userid_name,userid,
				get_field_dict_name('thesis','state',state) as state_name,
				state")
				->where("thesisid={$this->_get("thesisid")}")
				->select();
		$this->assign("student_list", $student);
		$this->display("view");
	}

	public function review_select() { //教师审批学生选题
		//dump($_GET) ;
		if (!$_GET['state'] || !$_GET['userid'] || !$_GET['thesisid']) {
			$this->ajaxReturn(NULL, "错误的参数！", 0);
			return;
		}
		if ($this->_get('state') == "pass") {
			$data['state'] = $this->sys_config_value("student_thesis_state_pass");
		} elseif ($this->_get('state') == "fail") {
			$data['state'] = $this->sys_config_value("student_thesis_state_failed");
		}
		$thesisdb = M("thesis");
		if (false !== $thesisdb->where("thesisid={$_GET['thesisid']} and userid={$_GET['userid']}")->save($data)) {
			//如果审核未通过，删除学生占用名额
			if ($this->_get('state') == "fail") {
				$this->db->where("thesisid={$_GET['thesisid']}  and select_num>0")->setDec("select_num");
			}
			$data = $thesisdb->field("get_field_dict_name('thesis','state',{$data['state']}) as date_name")->find();
			$this->ajaxReturn($data['date_name'], "审核成功！", 1);
		} else {
			$this->ajaxReturn($thesisdb->getLastSql(), "审核失败！", 0);
		}
	}

	public function dean_comment() {
		$this->assignthesis();
		$this->assign("edit_dean_comment", TRUE);
		$this->display("view");
	}

	public function dean_comment_approve() {
		$_POST['result'] = $this->sys_config_value("thesis_dean_result_pass");
		$this->save_ajax_post();
	}

	public function dean_comment_not_approve() {
		$_POST['result'] = $this->sys_config_value("thesis_result_failed");
		$this->save_ajax_post();
	}

}

?>