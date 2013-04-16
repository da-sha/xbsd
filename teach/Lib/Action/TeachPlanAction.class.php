<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-24
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	; TeachPlanAction.class.php UTF-8
 * -----------------------------------------------------------
 */

class TeachPlanAction extends CourseAction {
	protected $db_teach_plan;

	public function _initialize(){
		$this->db_teach_plan = M("TeachPlan");

		parent::_initialize();
	}

	public function index() {
		return;
	}

	public function _before_add() {
	}

	public function _before_insert() {
		$teachid = $_GET["teachid"];
		if ($this->cou_teacher_check($teachid) == false) {
			$this->error("对不起，你无权操作该考核") ;
			return ;
		}
		$_POST["teachid"] = $teachid;
		$seq = $this->db_teach_plan->where("teachid={$teachid}")
			->field("max(seq) as seq")->find();
		$_POST["seq"] = $seq['seq'] + 1;
	}

	public function _before_edit() {
		$teach_plan_id = $_GET['id'];
		
		if(empty($teach_plan_id) == true)
		{
			$this->error("对不起，参数错误！") ;
			return ;
		}

		$data = $this->db_teach_plan->find( $teach_plan_id ) ;
		$this->assign( "plan" , $data ) ;
	}

	public function _before_update() {
		$teach_plan_id = $_POST["id"];
		
		if ($this->check_teach_plan($teach_plan_id) == false) {
			$this->error("对不起，你无权操作该考核") ;
			return ;
		}
	}
	
	public function _before_delete(){
		$teach_plan_id = $_POST["id"];
		if ($this->check_teach_plan($teach_plan_id) == false) {
			$this->error("对不起，你无权操作该考核") ;
			return ;
		}
	}
	/**
	* 对考核进行验证,是否是授该课的老师在操作
	*
	* @param int $teachid 授课号
	* @param int $assessmentid 考核号
	* @return boolean 
	*/	
	private function check_teach_plan($teach_plan_id) {
		$teach_plan = $this->db_teach_plan->where("id=$teach_plan_id")->find();
		$course = $this->db_teach_task->where("teachid={$teach_plan['teachid']}")->find();
		if ($course['userid'] != $this->get_user_id()) {
			return false;
		}
		return true;
	}

	/**
	 * 教师显示课程进度模块
	 *
	 */	
	public function teacher_plan(){
		$teachid = $this->_get("teachid") ;
		if ($this->cou_teacher_check($teachid) == false) {
			$this->error("您没有带该项课程");
		}
		$where = "teachid={$teachid}" ;
		$course = $this->db_teach_task->where($where)->find();
		
		$data = $this->db_teach_plan->where($where)
			->field("
					id,
					plan_title,
					publish_time,
					week_num,
					week_time,
					plan_content,
					teach_method,
					seq"
				)
			->order("seq")
			->select();
		$this->assign('plan_list', $data);
		
		if ($course["finish_status"] == C("TEACH_TASK_FINISH_STATUS_PAST")) {
			/*past*/
			$this->display("teachplan_no_operation");
		}else if ($course["finish_status"] == C("TEACH_TASK_FINISH_STATUS_NOW")) {
			/*now*/
			$this->display("teachplan_has_operation");
		}
	}
	/**
	 * 学生显示课程进度模块
	 *
	 */	
	public function student_plan(){
		$teachid = $this->_get("teachid") ;
		if ($this->cou_student_check($teachid) == false) {
			$this->error("您没有带该项课程");
		}
		$where = "teachid={$teachid}" ;
		$course = $this->db_teach_task->where($where)->find();
		
		$data = $this->db_teach_plan->where($where)
			->field("
					id,
					plan_title,
					publish_time,
					week_num,
					week_time,
					plan_content,
					teach_method,
					seq"
				)
			->order("seq")
			->select();
		$this->assign('plan_list', $data);
		
		$this->display();
	}
	/**
	* 管理员显示课程进度模块
	*/	
	public function admin_plan(){
		$teachid = $this->_get("teachid") ;
		$where = "teachid={$teachid}" ;
		$course = $this->db_teach_task->where($where)->find();
		
		$data = $this->db_teach_plan->where($where)
			->field("
					id,
					plan_title,
					publish_time,
					week_num,
					week_time,
					plan_content,
					teach_method,
					seq"
				)
			->order("seq")
			->select();
		$this->assign('plan_list', $data);
		
		$this->display("teachplan_no_operation");
	}
}

?>
