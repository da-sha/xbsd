<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-24
 * -----------------------------------------------------------
 * 创建人	：大傻 lilei.zh@qq.com
 * -----------------------------------------------------------
 * 文件说明	; AssessmentAction.class.php UTF-8
 * -----------------------------------------------------------
 */

class AssessmentAction extends CourseBaseAction{
	protected $db_assessment;

	public function _initialize(){
		$this->db_assessment = M('assessment');
		parent::_initialize();
	}

	public function index()
	{
		return;
	}
	public function _before_add()
	{
		$this->get_field_dict("assessment", "type", "asse_list") ;
	}
	/**
	 * teachid是通过get得到的
	 */
	public function insert() {
		$teachid = $_GET["teachid"];
		if ($this->cou_teacher_check($teachid) == false) {
			$this->error("对不起，你无权操作该考核") ;
			return ;
		}
		$_POST["teachid"] = $teachid;
		$seq = $this->db_assessment->where("teachid={$teachid}")
			->field("max(seq) as seq")->find();
		$_POST["seq"] = $seq['seq'] + 1;
		
		$model = D ("Assessment");

		if (false === $model->create ()) {
			$this->error ( $model->getError () );
		}
		$id = $model->add();
		
		if ($id !==false) {
			$jumpUrl = $_POST['forward'] ? $_POST['forward'] : U(MODULE_NAME.'/index');
			$this->assign ( 'jumpUrl',$jumpUrl );
			$this->success (L('add_ok'));
		} else {
			$this->error (L('add_error').': '.$model->getDbError());
		}
	}
	/**
	 * 在更新前，检测权限
	 *
	 */	
	public function update(){
		$assessmentid = $_POST["assessmentid"];
		
		if ($this->check_assessment($assessmentid) == false) {
			$this->error("对不起，你无权操作该考核") ;
			return ;
		}
		
		if($_POST['setup']){
			$_POST['setup'] = array2string($_POST['setup']);
		}

		$model = D ( "Assessment" );
		if (false === $model->create ()) {
			$this->error ( $model->getError () );
		}
		if (false !== $model->save ()) {
			$jumpUrl = $_POST['forward'] ? $_POST['forward'] : U(MODULE_NAME.'/index');
			$this->assign ( 'jumpUrl',$jumpUrl );
			$this->success (L('update_ok'));
		} else {
			$this->success (L('edit_error').': '.$model->getError());
		}
	}
	/**
	 * @see CommonAction::edit()
	 */
	public function _before_edit()
	{
		$asseid = $this->_get('assessmentid') ;
		if(empty($asseid) == true)
		{
			$this->error("对不起，参数错误！") ;
			return ;
		}
		$data = $this->db_assessment->find( $asseid ) ;
		$this->assign( "assess" , $data ) ;
		$this->get_field_dict("assessment", "type", "asse_list") ;
	}
	
	public function delete() {
		$assessmentid = $_POST["assessmentid"];
		
		if ($this->check_assessment($assessmentid) == false) {
			$this->error("对不起，你无权操作该考核") ;
			return ;
		}
		
		$model = M ( "Assessment" );
		$pk = $model->getPk ();
		$id = $_REQUEST [$pk];
		if (isset ( $id )) {
			if(false!==$model->delete($id)){
				$this->success(L('delete_ok'));
			}else{
				$this->error(L('delete_error').': '.$model->getDbError());
			}
		}else{
			$this->error (L('do_empty'));
		}
	}
	/**
	 * 教师考核管理
	 */
	public function teacher_assess(){
		$teachid = $_GET["teachid"];
		if ($this->cou_teacher_check($teachid) == false) {
			$this->error("您没有带该项课程");
		}
		
		$where = "teachid={$teachid}" ;
		$course = $this->db_teach_task->where($where)->find();
		
		$data = $this->db_assessment->where($where)
			->field("
						assessmentid,
						name,
						get_field_dict_name('assessment','type',type ) as type,
						weight,
						seq"
				)
			->order("seq")
			->select();
		$this->assign('asse_list', $data);
		
		if ($course["finish_status"] == C("TEACH_TASK_FINISH_STATUS_PAST")) {
			/*past*/
			$this->display("assess_no_operation");
		}else if ($course["finish_status"] == C("TEACH_TASK_FINISH_STATUS_NOW")) {
			/*now*/
			$this->display("assess_has_operation");
		}
	}
	/**
	* 学生考核管理,将成绩一并显示
	*/
	public function student_assess(){
		$userid = $this->get_user_id();
		
		$teachid = $_GET["teachid"];
		if ($this->cou_student_check($teachid) == false) {
			$this->error("对不起，您没有选该项课程");
			return true;
		}

		$where = "assessment.teachid={$teachid}
				 and score.assessmentid=assessment.assessmentid
				 and score.userid={$userid}" ;
				
		$db = new Model();
		$tables = "assessment,score";
		$data = $db->table($tables)->where($where)
			->field("
						assessment.assessmentid,
						assessment.name,
						get_field_dict_name('assessment','type',type ) as type,
						assessment.weight,
						score.mark,
						assessment.seq"
				)
			->order("seq")
			->select();
		$this->assign('asse_list', $data);
		$this->display();
	}
	/**
	* 管理员考核管理
	*/
	public function admin_assess(){
		$teachid = $_GET["teachid"];
		
		$where = "teachid={$teachid}" ;
		$course = $this->db_teach_task->where($where)->find();
		
		$data = $this->db_assessment->where($where)
			->field("
						assessmentid,
						name,
						get_field_dict_name('assessment','type',type ) as type,
						weight,
						seq"
				)
			->order("seq")
			->select();
		$this->assign('asse_list', $data);
		
		$this->display("assess_no_operation");
	}
	/**
	 * 对考核进行验证,是否是授该课的老师在操作
	 *
	 * @param int $teachid 授课号
	 * @param int $assessmentid 考核号
	 * @return boolean 
	 */	
	private function check_assessment($assessmentid) {
		$assessment = $this->db_assessment->where("assessmentid={$assessmentid}")->find();
		$course = $this->db_teach_task->where("teachid={$assessment['teachid']}")->find();
		if ($course['userid'] != $this->get_user_id()) {
			return false;
		}
		return true;
	}
	
}

?>
