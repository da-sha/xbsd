<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-22
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	; ThesisAction.class.php UTF-8
 * -----------------------------------------------------------
 */

class ThesisAction extends CommonAction {

	public function index() {
		$db = new MajorModel();
		$major = $db->field('majorid, name')->select();
		$this->assign('list', $major );
		$this->display();
	}

	public function getClass() {
		$majorid = $this->_get("majorid");
		$db = new ClassModel();
		$class = $db->where("majorid={$majorid}")->field("classid, name")->select();
		if ($class){
			$this->ajaxReturn($class, "查询成功！", 1);
		} else {
			$this->ajaxReturn(0, "查询失败！", 0);
		}
	}

	public function writes()
	{
		$_SESSION['majorid'] = $this->_get('majorid');
		$_SESSION['classid'] = $this->_get('classid');
		echo "成功";
	}

	public function body()
	{
		$classid = $_SESSION['classid'];
		if (!$classid) {
			$this->assign("show", "请先选择专业和班级");
			$this->display();
		} else {
			$temp = new ThesisModel();
			$thesis = $temp->table('student, thesis')
							->where("student.classid={$classid} and student.userid=thesis.userid")
							->field("get_student_name(thesis.userid) as userid,
									thesis.userid as id,
									thesis.thesisid as tid,
									get_thesis_name(thesis.thesisid) as thesisid,
									thesis.title_source,
									thesis.word_num,
									thesis.deadline,
									thesis.first_score,
									thesis.final_score,
									thesis.comment")
							->select();
			if( !$thesis )
			{
				$this->assign("show", "此班级还未选论文题目");
				$this->display();
			}  else {
				$this->assign('list', $thesis);
				$this->display();
			}
		}
	}

	public function add() {

		$classid = $_SESSION['classid'];
		if (!$classid) {
			$this->error("请先选择专业和班级");
		} else {
			$db = new ClassModel();
			$class = $db->where("classid={$classid}")->field('name')->find();
			$this->assign('classname', $class[name]);

			$db = new StudentModel();
			$stu = $db->where("classid={$classid}")->field('userid as id, name')->select();
			$this->assign('name_list', $stu);

			$db = new ThesisTitleModel();
			$thesis = $db->field('thesisid as id, name')->select();
			$this->assign('thesis_list', $thesis);
			$this->display();
		}
	}

	public function insert() {
		$db = new ThesisModel();
		if ($data = $db->create()) {
			$temp = new ThesisModel();
			if( $temp->where("userid={$data['userid']}")->select() )
			{
				$this->error('该同学已经选过题目了，不能重复选题');
			} else {
				if (false !== $db->add()) {
					$this->success('恭喜您选题成功！', "__URL__/body");
				} else {
					$this->error('操作失败：' . $db->getDbError(), "__URL__/body");
				}
			}
		} else {
			$this->error('操作失败：数据验证( ' . $db->getError() . ' )');
		}
	}

	public function edit() {

		$majorid = $_SESSION['majorid'];
		$classid = $_SESSION['classid'];
		$userid = $this->_get("userid");
		$thesisid = $this->_get("thesisid");
		
		$db = new ClassModel();
		$class = $db->where("classid={$classid}")->field('name')->find();
		$this->assign('classname', $class[name]);

		$db = new StudentModel();
		$stu = $db->where("classid={$classid}")->field('userid as id, name')->select();
		$this->assign('name_list', $stu);

		$db = new ThesisTitleModel();
		$thesis = $db->field('thesisid as id, name')->select();
		$this->assign('thesis_list', $thesis);

		$db = new ThesisModel();
		$data = $db->where("userid={$userid} and thesisid={$thesisid}")->find();
		$this->assign('thesis', $data);
		$this->display();
	}

	public function update() {
		$db = new ThesisModel();
		if ($data = $db->create()) {
			if (false !== $db->where("userid={$data['userid']} and thesisid={$data['thesisid']}")->save()) {
				$this->success('恭喜您编辑选题成功！', "__URL__/body");
			} else {
				$this->error('操作失败：' . $db->getDbError(), "__URL__/body");
			}
		} else {
			$this->error('操作失败：数据验证( ' . $db->getError() . ' )');
		}
	}

	public function delete() {
		$userid = $this->_get("userid");
		$thesisid = $this->_get("thesisid");
		$db = new ThesisModel();
		if (false !== $db->where("userid={$userid} and thesisid={$thesisid}")->delete()) {
			$this->success('删除成功', "__URL__/body");
		} else {
			$this->error("删除失败" . $db->getDbError(), "__URL__/body");
		}
	}
	public function teacher_process()
	{
		$guidetaskdb=M("guide_task") ;
		$guide = $guidetaskdb->field("guideid,year,semester,
			category,get_field_dict_name('guide_task','category',category) as category_name,
			grade,majorid,get_major_name(majorid) as majorid_name")->where("teacherid = {$this->get_user_id()} and category={$this->_get('category')}")->select() ;
		$thesis_taskdb = M("thesis_task") ;
		if( !$guide )
		{
			$this->assign( "show", "<span class='red'>对不起，没有找到指导信息!</span>") ;
		}
		foreach ($guide as $key => $value) {
			$guide[$key]['student']=$thesis_taskdb->field("studentid,get_student_name(studentid) as studentid_name,category")->where("guideid={$value['guideid']}")->select() ;
		}
		$this->assign("guide_list" , $guide) ;
		$this->display() ;
	}

	public function stuthesis()
	{
		$studentid = $this->_get("studentid") ;
		$category = $this->_get("category") ;
		if( !$studentid || !$category )
		{
			$this->assign("show", "对不起，错误的参数！" ) ;
			$this->display() ;
			return ;
		}
		$db = M("student,class") ;
		//学生基本信息
		$data = $db->field("
			student.name as stu_name,
			get_field_dict_name('student','gender' ,gender) as gender_name,
			class.name as cla_name,
			student.admissiondate,
			get_major_name(majorid) as majorid_name
			")->where("student.userid={$studentid} and student.classid=class.classid")->find();
		if( false !== $data )
		{
			$this->assign("student", $data ) ;
		}
		//本论文基本信息
		$thesisdb = M("thesis,thesis_title") ;
		$where = "thesis_title.category=$category and thesis.userid={$studentid} and thesis.thesisid=thesis_title.thesisid" ;
		$thesisdata = $thesisdb->field("
			thesis.thesisid,teacherid,get_field_dict_name('thesis','state',state) as state_name, state,
			title_source, word_num, deadline, first_score, final_score, thesis.comment, teacherid, name,
			content, get_field_dict_name('thesis_title','type',type) as type_name, category,thesis.userid
			")->where( $where )->find() ;
		$this->assign( "thesis" , $thesisdata ) ;
		if( $thesisdata )
		{
			if( $thesisdata['state'] == $this->sys_config_value("student_thesis_state_failed")
					||  $thesisdata['state'] == $this->sys_config_value("student_thesis_state_wait")  )
			{
				$this->assign("can_withdraw", true ) ;
			}
			$this->assign( "show" , "当前论文状态：".$thesisdata['state_name'] ) ;
		}
		else
		{
			$this->assign( "show" , "<span class='red'>对不起，该学生目前没有选择论文！</span>" ) ;
		}
		$this->display() ;
	}
	public function guide_record(){
		$db = new Model('thesis_process');
		$guideid = $this->_get("guideid") ;
		import('ORG.Util.Page');
		$where = "guideid={$guideid}";
		$total = $db->where($where)->count();
		$page=new Page($total,C(PAGESIZE));
		$this->assign( "show", $page->show() ) ;
		$data = $db->where($where)
				->field("processid,
						content,
						progress,
						date")
				->limit($page->firstRow.','.$page->listRows)
				->order("date desc")
				->select();
		$this->assign( "record_list" , $data ) ;
		$this->display() ;
	}
	
	public function ajax_update(){
		$db = new ThesisModel() ;
		$data = $db->create() ;
		if( false !== $data && false !== $db->save() )
		{
			$this->ajaxReturn( $data , "更新成功！", 1 ) ;
		}
		else{
			$this->ajaxReturn( $data , "更新失败！", 0 ) ;
		}
	}

	public function delete_record(){
		$db = M("thesis_process") ;
		$processid = $_GET["processid"] ;
		if( $processid && false !== $db->delete($processid) )
		{
			$this->ajaxReturn( NULL , "删除成功！", 1 ) ;
		}else{
			$this->ajaxReturn( NULL , "删除失败！", 0 ) ;
		}
	}

	public function insert_record(){
		$_POST["date"] = date('Y-m-d') ;
		$db = new ThesisProcessModel();
		if ( false !== $db->create() && false !== $db->add() ) {
			$this->success("添加成功！") ;
		} else {
			$this->error("对不起，添加成功！") ;
		}
	}
	
}

?>