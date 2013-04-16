<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-23
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	;  CourseChangeAction.class.php UTF-8
 * -----------------------------------------------------------
 */

class CourseChangeAction extends CourseBaseAction {

	protected $db_cou_change;

	public function _initialize(){
		parent::_initialize();
		$this->db_cou_change = M("course_change");
	}

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
			$temp = new Model();
			$change = $temp->table('teach_task, course_change, course')
							->where("teach_task.classid={$classid} and teach_task.teachid=course_change.teachid and course.courseid=teach_task.courseid")
							->field("course_change.id,
									get_course_name(course.courseid) as teachid,
									get_teacher_name(course_change.teacherid) as teacherid,
									get_field_dict_name('course_change','time_type',course_change.time_type) as time_type,
									get_field_dict_name('course_change','type',course_change.type) as type,
									course_change.teach_building,
									course_change.roomid,
									get_field_dict_name('course_change','week',course_change.week) as week,
									get_field_dict_name('course_change','seq',course_change.seq) as seq,
									course_change.reason")
							->select();
			if( !$change )
			{
				$this->assign("show", "此班级无课程异动记录");
				$this->display();
			}  else {
				$this->assign('list', $change);
				$this->display();
			}
		}
	}

	public function _before_add() {
		$this->before_add_edit();
	}

	public function edit() {
		$id = $this->_get('id');

		$this->before_add_edit();
		
		$change = $this->db_cou_change->find($id);
		$this->assign("change", $change);
		$db_teacher = M("teacher");
		$authored_teacher_info = $db_teacher->find($change['teacherid']);
		$this->assign("authored_teacher_info",$authored_teacher_info);
		$this->display();
	}
	public function _before_insert() {
		$_POST['teachid'] = $_GET['teachid'];
	}

	/**
	 * 课程异动信息
	 */
	public function teacher_cou_change() {
		$teachid = $_GET['teachid'];

		if ($this->cou_teacher_check($teachid) == false) {
			$this->error("您没有带该项课程");
		}
		
		$where = "teachid={$teachid}" ;
		$course = $this->db_teach_task->where($where)->find();
		
		$change = $this->db_cou_change->where($where)
			->field("id,
				get_field_dict_name('course_change','time_type',time_type) as time_type,
				get_field_dict_name('course_change','type',type) as type,
				get_teacher_name(teacherid) as teachername,
				teach_building,
				roomid,
				get_field_dict_name('course_change','week',week) as week,
				get_field_dict_name('course_change','seq',seq) as seq,
				update_date,
				reason")
			->order("update_date")
			->select();
		$this->assign('list', $change);

		if ($course["finish_status"] == C("TEACH_TASK_FINISH_STATUS_PAST")) {
			/*past*/
			$this->display("cou_change_past");
		}else if ($course["finish_status"] == C("TEACH_TASK_FINISH_STATUS_NOW")) {
			/*now*/
			$this->display("cou_change_now");
		}
	}
	/**
	 * 课程异动信息
	 */
	public function student_cou_change() {
		$teachid = $_GET["teachid"];
		if ($this->cou_student_check($teachid) == false) {
			$this->error("对不起，您没有选该项课程");
			return true;
		}
		
		$where = "teachid={$teachid}";
		$change = $this->db_cou_change->where($where)
			->field("id,
				get_field_dict_name('course_change','time_type',time_type) as time_type,
				get_field_dict_name('course_change','type',type) as type,
				get_teacher_name(teacherid) as teachername,
				teach_building,
				roomid,
				get_field_dict_name('course_change','week',week) as week,
				get_field_dict_name('course_change','seq',seq) as seq,
				update_date,
				reason")
			->order("update_date")
			->select();
		$this->assign('list', $change);
		
		$this->display();
	}
	/**
	 * 管理员异动信息
	 */
	public function admin_cou_change() {
		$teachid = $_GET['teachid'];
		
		$where = "teachid={$teachid}" ;
		$course = $this->db_teach_task->where($where)->find();
		
		$change = $this->db_cou_change->where($where)
			->field("id,
				get_field_dict_name('course_change','time_type',time_type) as time_type,
				get_field_dict_name('course_change','type',type) as type,
				get_teacher_name(teacherid) as teachername,
				teach_building,
				roomid,
				get_field_dict_name('course_change','week',week) as week,
				get_field_dict_name('course_change','seq',seq) as seq,
				update_date,
				reason")
			->order("update_date")
			->select();
		$this->assign('list', $change);
		
		$this->display("cou_change_past");
	}
	/**
	 * 在添加和更新操作之前要做的
	 */	
	private function before_add_edit() {
		$this->get_field_dict('course_change', 'type', 'type_list');
		$this->get_field_dict('course_change', 'time_type', 'time_type_list');
		$this->get_field_dict('course_change', 'week', 'day_list');
		$this->get_field_dict('course_change', 'seq', 'seq_list');
	}
}

?>
