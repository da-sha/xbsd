<?php
/*-------------------------------------------------------------------
* Purpose:
*         本科生排课模块
* Time:
*         2012年11月12日 8:22:37
* Author:
*         张彦升
--------------------------------------------------------------------*/
class UndergraduateArrangeCourseAction extends ArrangeCourseAction{
	protected $level;
	
	public function _initialize() {
		parent::_initialize();
		
		$this->level = C("LEVEL_UNDERGRADUATE_ID");
	}
	public function index(){
		$this->assign_teach_workload();
		$this->assign_undergraduate_major_list();
		$this->assign_undergraduate_grade_list();
		$this->assign_class_semester_list();

		$this->assign_level_list();
		$this->assign_course_property_list();
		$this->assign_course_category_list();
		$this->assign_course_type_list();
		$this->assign_exam_type_list();
		
		$this->display();
	}
	
	private function assign_teach_workload() {
		$db_teacher = D("teacher");

		$where = array();

		//$year = $this->assign_next_teach_year();
		//$quarter = $this->assign_next_quarter_id();
		$year = $this->assign_cur_teach_year();
		$quarter = $this->get_quarter();
		
		$same_teach_year = 0;		/*同时授课学年*/
		$same_quarter = 0;
		
		if ($quarter == $this->sys_config_value("spring_semester")) {
			$same_teach_year = $year - 1;
			$same_quarter = $this->sys_config_value("autumn_semester");
		}else{
			$same_quarter = $this->sys_config_value("spring_semester");
			$same_teach_year = $year + 1;
		}

		$where['orgid'] = $this->get_orgid();

		$year2 = $year - 2;
		$year1 = $year - 1;
		
		$teach_workload = $db_teacher->where($where)
			->field("get_user_code(teacher.userid) as user_code,
				teacher.name,
				teacher.userid as id,
				get_teach_workload(teacher.userid,'$year','$quarter') + get_teach_workload(teacher.userid,'$same_teach_year','$same_quarter') as this_year_workload,
				get_teach_workload(teacher.userid,'{$year2}','$quarter') + get_teach_workload(teacher.userid,'{$year2}','$same_quarter')
				+ get_teach_workload(teacher.userid,'{$year1}','$quarter') + get_teach_workload(teacher.userid,'{$year1}','$same_quarter')
				+ get_teach_workload(teacher.userid,'$year','$quarter') + get_teach_workload(teacher.userid,'$same_teach_year','$same_quarter')
					 as three_year_workload
			")
			->select();
		//echo $db_teacher->getLastSql();
		$this->assign("teach_workload",$teach_workload);
	}
	/**
	 * 得到专业必修课的课程信息
	 */	
	public function get_major_require_data(){
		$majorid = $_POST["majorid"];

		$year = date("Y");
		$quarter = $this->assign_next_quarter_id();
		/*如果是下一学期是春季*/
		if ($quarter == $this->sys_config_value("spring_semester")) {
			$year = $this->assign_next_teach_year();
		}
		
		$this->arrange_major_require_course($year,$quarter);
		
		$t_quarter = $quarter - 1;
		//得到本年度要安排的课程
		$where = array();
		$where['course.cou_type'] = C("COURSE_TYPE_REQUIRE");
		$where['course.level'] = $this->level;
		$where['teach_task.finish_status'] = C("TEACH_TASK_FINISH_STATUS_FUTURE");
		
		if ($majorid) {
			$where['course.majorid'] = $majorid;
		}

		$where['course.cou_category'] = C("COURSE_CATEGORY_MAJOR");
		$where['_complex'] = "course.majorid=class.majorid 
				and course.semester=({$year} - course.grade)*2+{$t_quarter}
				and teach_task.teachid = teach_class.teachid
				and teach_task.courseid = course.courseid
				and teach_class.classid = class.classid
				and teach_task.status = 1
				";
		
		$db = new Model();
		$tables = "course,class,teach_task,teach_class";
		
		$total = $db->table($tables)->where($where)->count();
		
		$data = $db->table($tables)
				->where($where)
				->field("
				class.name as classname,
				class.register_date as grade,
				course.*,
				teach_task.teachid,
				teach_task.userid
				")
				->select();
		$sql = $db->getLastSql();
		
		$this->easyui_return_data($total,$data);
	}
	/**
	 * 将专业必修课分配到teacher_task表中
	 */
	protected function arrange_major_require_course($year,$quarter){
		$db = new Model();
		$db->execute("call arrange_major_require_course({$year},{$quarter})");
	}
	/**
	* 将专业必修课分配到teacher_task表中
	*/
	protected function arrange_academy_require_course($year,$quarter){
		$db = new Model();
		$db->execute("call arrange_academy_require_course({$year},{$quarter})");
		
		$sql = $db->getLastSql();
	}
	/**
	* 将专业必修课分配到teacher_task表中
	*/
	protected function arrange_school_require_course($year,$quarter){
		$db = new Model();
		$db->execute("call arrange_school_require_course({$year},{$quarter})");
	}
	/**
	* 将专业选修课分配到teacher_task表中
	*/
	protected function arrange_major_select_course($year,$quarter){
		$db = new Model();
		$db->execute("call arrange_major_select_course({$year},{$quarter})");
	}
	/**
	* 将学院选修课分配到teacher_task表中
	*/
	protected function arrange_academy_select_course($year,$quarter){
		$db = new Model();
		$db->execute("call arrange_academy_select_course({$year},{$quarter})");
	}
	/**
	* 将学院选修课分配到teacher_task表中
	*/
	protected function arrange_school_select_course($year,$quarter){
		$db = new Model();
		$db->execute("call arrange_school_select_course({$year},{$quarter})");
	}
	/**
	 * 更新教师数据
	 */
	public function major_require_update() {
		$_POST['status'] = C("TEACH_TASK_APPROVAL_STATUS_WAIT");
		if (false === $this->db_teach_task->create ()) {
			$this->easyui_return_data(0);
			return;
		}
		if (false !== $this->db_teach_task->save ()) {
			if($this->isAjax() == true){
				echo json_encode($_REQUEST);
				return;
			}else{
				$jumpUrl = $_POST['forward'] ? $_POST['forward'] : U(MODULE_NAME.'/index');
				$this->assign ( 'jumpUrl',$jumpUrl );
				$this->success (L('update_ok'));
			}
		} else {
			$this->success (L('edit_error').': '.$this->db_teach_task->getError());
		}
	}
	/**
	 * 得到学校平台必修课的课程信息
	 */	
	public function get_school_require_data(){
		$year = date("Y");
		$quarter = $this->assign_next_quarter_id();
		/*如果是下一学期是春季*/
		if ($quarter == $this->sys_config_value("spring_semester")) {
			$year = $this->assign_next_teach_year();
		}
		
		$this->arrange_school_require_course($year,$quarter);
		
		$t_quarter = $quarter - 1;
		//得到本年度要安排的课程
		$where = array();
		$where['course.cou_type'] = C("COURSE_TYPE_REQUIRE");
		$where['course.level'] = $this->level;
		$where['teach_task.finish_status'] = C("TEACH_TASK_FINISH_STATUS_FUTURE");

		$where['course.cou_category'] = C("COURSE_CATEGORY_SCHOOL");
		$where['_complex'] = "course.semester=({$year} - course.grade)*2+{$t_quarter}
				and teach_task.teachid = teach_class.teachid
				and teach_task.courseid = course.courseid
				and teach_class.classid = class.classid
				and teach_task.status = 1
				";

		$db = new Model();
		$tables = "course,class,teach_task,teach_class";
		
		$total = $db->table($tables)->where($where)->count();
		
		$data = $db->table($tables)
			->where($where)
			->field("
				class.name as classname,
				class.register_date as grade,
				course.*,
				teach_task.teachid,
				teach_task.userid
				")
			->select();
		$sql = $db->getLastSql();
		
		$this->easyui_return_data($total,$data);
	}
	/**
	* 更新教师数据
	*/
	public function school_require_update() {
		$_POST['status'] = C("TEACH_TASK_APPROVAL_STATUS_WAIT");
		if (false === $this->db_teach_task->create ()) {
			$this->easyui_return_data(0);
			return;
		}
		if (false !== $this->db_teach_task->save ()) {
			if($this->isAjax() == true){
				echo json_encode($_REQUEST);
				return;
			}else{
				$jumpUrl = $_POST['forward'] ? $_POST['forward'] : U(MODULE_NAME.'/index');
				$this->assign ( 'jumpUrl',$jumpUrl );
				$this->success (L('update_ok'));
			}
		} else {
			$this->success (L('edit_error').': '.$this->db_teach_task->getError());
		}
	}
	/**
	* 得到学校平台必修课的课程信息
	*/	
	public function get_academy_require_data(){
		$majorid = $_POST["majorid"];

		$year = date("Y");
		$quarter = $this->assign_next_quarter_id();
		/*如果是下一学期是春季*/
		if ($quarter == $this->sys_config_value("spring_semester")) {
			$year = $this->assign_next_teach_year();
		}
		
		$this->arrange_academy_require_course($year,$quarter);
		
		$t_quarter = $quarter - 1;
		//得到本年度要安排的课程
		$where = array();
		$where['course.cou_type'] = C("COURSE_TYPE_REQUIRE");
		$where['course.level'] = $this->level;
		$where['teach_task.finish_status'] = C("TEACH_TASK_FINISH_STATUS_FUTURE");
		if ($majorid) {
			$where['class.majorid'] = $majorid;
		}

		$where['course.cou_category'] = C("COURSE_CATEGORY_ACADEMY");
		$where['_complex'] = "course.semester=({$year} - course.grade)*2+{$t_quarter}
				and teach_task.teachid = teach_class.teachid
				and teach_task.courseid = course.courseid
				and teach_class.classid = class.classid
				and teach_task.status = 1
				";
		
		$db = new Model();
		$tables = "course,class,teach_task,teach_class";
		
		$total = $db->table($tables)->where($where)->count();
		
		$data = $db->table($tables)
			->where($where)
			->field("
				class.name as classname,
				class.register_date as grade,
				course.*,
				teach_task.teachid,
				teach_task.userid
				")
			->select();
		$sql = $db->getLastSql();
		
		$this->easyui_return_data($total,$data);
	}
	/**
	* 更新教师数据
	*/
	public function academy_require_update() {
		$_POST['status'] = C("TEACH_TASK_APPROVAL_STATUS_WAIT");
		if (false === $this->db_teach_task->create ()) {
			$this->easyui_return_data(0);
			return;
		}
		if (false !== $this->db_teach_task->save ()) {
			if($this->isAjax() == true){
				echo json_encode($_REQUEST);
				return;
			}else{
				$jumpUrl = $_POST['forward'] ? $_POST['forward'] : U(MODULE_NAME.'/index');
				$this->assign ( 'jumpUrl',$jumpUrl );
				$this->success (L('update_ok'));
			}
		} else {
			$this->success (L('edit_error').': '.$this->db_teach_task->getError());
		}
	}
	/**
	* 得到专业平台选修课的课程信息
	*/	
	public function get_major_select_data(){
		$year = date("Y");
		$quarter = $this->assign_next_quarter_id();
		/*如果是下一学期是春季*/
		if ($quarter == $this->sys_config_value("spring_semester")) {
			$year = $this->assign_next_teach_year();
		}
		
		$this->arrange_major_select_course($year,$quarter);
		
		$t_quarter = $quarter - 1;
		//得到本年度要安排的课程
		$where = array();
		$where['course.cou_type'] = array('neq',C("COURSE_TYPE_REQUIRE"));
		$where['course.level'] = $this->level;
		$where['teach_task.finish_status'] = C("TEACH_TASK_FINISH_STATUS_FUTURE");
		
		$where['course.cou_category'] = C("COURSE_CATEGORY_MAJOR");
		$where['_complex'] = "course.semester=({$year} - course.grade)*2+{$t_quarter}
				and teach_task.courseid = course.courseid
				and teach_task.status = 1
				";
		
		$db = new Model();
		$tables = "course,teach_task";
		
		$total = $db->table($tables)->where($where)->count();
		
		$data = $db->table($tables)
			->where($where)
			->field("
				course.*,
				teach_task.*
				")
			->select();
		$sql = $db->getLastSql();
		
		$this->easyui_return_data($total,$data);
	}
	/**
	* 更新教师数据
	*/
	public function major_select_update() {
		$_POST['status'] = C("TEACH_TASK_APPROVAL_STATUS_WAIT");
		if (false === $this->db_teach_task->create ()) {
			$this->easyui_return_data(0);
			return;
		}
		if (false !== $this->db_teach_task->save ()) {
			if($this->isAjax() == true){
				echo json_encode($_REQUEST);
				return;
			}else{
				$jumpUrl = $_POST['forward'] ? $_POST['forward'] : U(MODULE_NAME.'/index');
				$this->assign ( 'jumpUrl',$jumpUrl );
				$this->success (L('update_ok'));
			}
		} else {
			$this->success (L('edit_error').': '.$this->db_teach_task->getError());
		}
	}
	public function major_select_delete(){
		$id = $_REQUEST ['id'];
		
		/*从teach_class表中删除数据*/
		$db_teach_class = D("TeachClass");
		$db_teach_class->where("teachid = {$id}")->delete();
		
		if (isset ( $id )) {
			if(false!==$this->db_teach_task->delete($id)){
				if($this->isAjax() == true){
					$this->easyui_return_data(0);
					return;
				}else{
					$this->success(L('delete_ok'));
				}
			}else{
				$this->error(L('delete_error').': '.$this->teach_task->getDbError());
			}
		}else{
			$this->error (L('do_empty'));
		}
	}
	/**
	 * 向teach_task表中插入新的数据
	 */	
	public function teach_task_insert(){
		$data = array();
		
		$data['courseid'] = $_POST['courseid'];
		$data['teach_year'] = $_POST['teach_year'];
		$data['teach_quarter'] = $_POST['teach_quarter'];
		$data['status'] = 1;
		
		if (false === $this->db_teach_task->create ($data)) {
			//log_data(array(1,$this->db_teach_task->getError ()));
			$this->error ( $this->db_teach_task->getError () );
		}
		$id = $this->db_teach_task->add();

		$old_teachid = $_REQUEST['teachid'];
		
		$db_teach_class = D(TeachClass);
		$old_data = $db_teach_class->where("teachid = {$old_teachid}")->select();
		
		/*向teachclass表中添加数据*/
		$data = array();
		$data['teachid'] = $id;
		
		foreach($old_data as $key => $class){
			$data['classid'] = $class['classid'];
			$db_teach_class->add($data);
		}
		
		$_REQUEST["teachid"] = $id;
		
		if ($id !==false) {
			if($this->isAjax() == true){
				echo json_encode($_REQUEST);
				return;
			}else{
				$jumpUrl = $_POST['forward'] ? $_POST['forward'] : U(MODULE_NAME.'/index');
				$this->assign ( 'jumpUrl',$jumpUrl );
				$this->success (L('add_ok'));
			}
		} else {
			$this->error (L('add_error').': '.$this->db_teach_task->getDbError());
		}
	}
	/**
	* 得到专业平台选修课的课程信息
	*/	
	public function get_academy_select_data(){
		$year = date("Y");
		$quarter = $this->assign_next_quarter_id();
		/*如果是下一学期是春季*/
		if ($quarter == $this->sys_config_value("spring_semester")) {
			$year = $this->assign_next_teach_year();
		}
		
		$this->arrange_academy_select_course($year,$quarter);
		
		$t_quarter = $quarter - 1;
		//得到本年度要安排的课程
		$where = array();
		$where['course.cou_type'] = array('neq',C("COURSE_TYPE_REQUIRE"));
		$where['course.level'] = $this->level;
		$where['teach_task.finish_status'] = C("TEACH_TASK_FINISH_STATUS_FUTURE");
		
		$where['course.cou_category'] = C("COURSE_CATEGORY_ACADEMY");
		$where['_complex'] = "course.semester=({$year} - course.grade)*2+{$t_quarter}
				and teach_task.courseid = course.courseid
				and teach_task.status = 1
				";
		
		$db = new Model();
		$tables = "course,teach_task";
		
		$total = $db->table($tables)->where($where)->count();
		
		$data = $db->table($tables)
			->where($where)
			->field("
				course.*,
				teach_task.*
				")
			->select();
		$sql = $db->getLastSql();
		
		$this->easyui_return_data($total,$data);
	}
	/**
	* 更新教师数据
	*/
	public function academy_select_update() {
		$_POST['status'] = C("TEACH_TASK_APPROVAL_STATUS_WAIT");
		if (false === $this->db_teach_task->create ()) {
			//log_data(array(1,$this->db_teach_task->getError ()));
			$this->easyui_return_data(0);
			return;
		}
		if (false !== $this->db_teach_task->save ()) {
			if($this->isAjax() == true){
				echo json_encode($_REQUEST);
				return;
			}else{
				$jumpUrl = $_POST['forward'] ? $_POST['forward'] : U(MODULE_NAME.'/index');
				$this->assign ( 'jumpUrl',$jumpUrl );
				$this->success (L('update_ok'));
			}
		} else {
			$this->success (L('edit_error').': '.$this->db_teach_task->getError());
		}
	}
	public function academy_select_delete(){
		$id = $_REQUEST ['id'];
		
		/*从teach_class表中删除数据*/
		$db_teach_class = D("TeachClass");
		$db_teach_class->where("teachid = {$id}")->delete();
		
		if (isset ( $id )) {
			if(false!==$this->db_teach_task->delete($id)){
				if($this->isAjax() == true){
					$this->easyui_return_data(0);
					return;
				}else{
					$this->success(L('delete_ok'));
				}
			}else{
				$this->error(L('delete_error').': '.$this->teach_task->getDbError());
			}
		}else{
			$this->error (L('do_empty'));
		}
	}
	/**
	* 得到专业平台选修课的课程信息
	*/	
	public function get_school_select_data(){
		$year = date("Y");
		$quarter = $this->assign_next_quarter_id();
		/*如果是下一学期是春季*/
		if ($quarter == $this->sys_config_value("spring_semester")) {
			$year = $this->assign_next_teach_year();
		}
		
		$this->arrange_school_select_course($year,$quarter);
		
		$t_quarter = $quarter - 1;
		//得到本年度要安排的课程
		$where = array();
		$where['course.cou_type'] = array('neq',C("COURSE_TYPE_REQUIRE"));
		$where['course.level'] = $this->level;
		$where['teach_task.finish_status'] = C("TEACH_TASK_FINISH_STATUS_FUTURE");
		
		$where['course.cou_category'] = C("COURSE_CATEGORY_SCHOOL");
		$where['_complex'] = "teach_task.courseid = course.courseid
				and teach_task.status = 1
				";
		
		$db = new Model();
		$tables = "course,teach_task";
		
		$total = $db->table($tables)->where($where)->count();
		
		$data = $db->table($tables)
			->where($where)
			->field("
				course.*,
				teach_task.*
				")
			->select();
		$sql = $db->getLastSql();
		
		$this->easyui_return_data($total,$data);
	}
	/**
	* 更新教师数据
	*/
	public function school_select_update() {
		$_POST['status'] = C("TEACH_TASK_APPROVAL_STATUS_WAIT");
		if (false === $this->db_teach_task->create ()) {
			//log_data(array(1,$this->db_teach_task->getError ()));
			$this->easyui_return_data(0);
			return;
		}
		if (false !== $this->db_teach_task->save ()) {
			if($this->isAjax() == true){
				echo json_encode($_REQUEST);
				return;
			}else{
				$jumpUrl = $_POST['forward'] ? $_POST['forward'] : U(MODULE_NAME.'/index');
				$this->assign ( 'jumpUrl',$jumpUrl );
				$this->success (L('update_ok'));
			}
		} else {
			$this->success (L('edit_error').': '.$this->db_teach_task->getError());
		}
	}
	public function school_select_delete(){
		$id = $_REQUEST ['id'];
		
		if (isset ( $id )) {
			if(false!==$this->db_teach_task->delete($id)){
				if($this->isAjax() == true){
					$this->easyui_return_data(0);
					return;
				}else{
					$this->success(L('delete_ok'));
				}
			}else{
				$this->error(L('delete_error').': '.$this->teach_task->getDbError());
			}
		}else{
			$this->error (L('do_empty'));
		}
	}
}

?>
