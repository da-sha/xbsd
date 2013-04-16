<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-22 
 * -----------------------------------------------------------
 * 创建人	：大傻 lilei.zh@qq.com
 * -----------------------------------------------------------
 * 文件说明	; SelectCourseAction.class.php UTF-8
 * -----------------------------------------------------------
 */

class SelectCourseAction extends CourseBaseAction{
	protected $db_select_course;
	
	public function _initialize(){
		parent::_initialize();
		
		$this->db_select_course = M("select_course");
	}
	/**
	 * 选出所有的在授的选修课信息，学生可以根据此项显示的信息来添加自己的课程
	 */
	public function index(){
		$userid = $this->get_user_id();
		
		$course_type_require = C("COURSE_TYPE_REQUIRE");
		
		$finish_status = C("TEACH_TASK_FINISH_STATUS_NOW");	//在授课程
		
		$db = new Model();
		$where = "teach_task.finish_status={$finish_status}
					and teach_task.courseid=course.courseid
					and course.cou_type != {$course_type_require}";
					
		$tables = "teach_task,course";
		
		$teachs = $db->table($tables)->where($where)
			->field("course.name,
					teach_task.teachid,
					get_field_dict_name('course','level',course.level ) as level,
					get_field_dict_name('course','property',course.property ) as property,
					get_field_dict_name('course','cou_type',course.cou_type ) as cou_type,
					get_field_dict_name('course','cou_category',course.cou_category ) as cou_category,
					get_field_dict_name('teach_task','teach_quarter',teach_task.teach_quarter) as teach_quarter,
					teach_task.status,
					teach_task.teach_year,
					is_select_course({$userid},teach_task.teachid) as is_select
					")
			->select();
		//echo $db->getLastSql();
		/**
		 * 选出授课班级
		 */
		$where = array();
		$db = new Model();
		$where["_complex"] = "teach_class.classid = class.classid";
		
		foreach($teachs as $key => $teach){
			$teachid = $teach["teachid"];
			$where['teach_class.teachid'] = $teachid;
			
			$classes = $db->table("teach_class,class")->where($where)
				->field("class.name as classname,class.register_date")->select();
			$teachs[$key]["classes"] = $classes;
		}
		$this->assign("course_list",$teachs);
		//dump($teachs);
		$this->display();
	}
	
	public function select_student()
	{
		$teachid = $_GET['teachid'];

		if ($this->cou_teacher_check($teachid) == false) {
			$this->error("您没有带该项课程");
			return false;
		}
		
		$db = new Model() ;
		
		$where = array();
		$where["select_course.teachid"] = $teachid;
		$where["_complex"] = "select_course.userid = student.userid
								and student.classid = class.classid";
		
		$tables = "select_course,student,class";
		$data = $db->table($tables)
				->where($where)
				->field(
						"select_course.selectid,
						student.userid as userid,
						student.name as studentname,
						class.name as classname,
						class.register_date as register_date
						"
						)
				->select();
		//echo $db->getLastSql();
		$this->assign('select_student', $data);

		$this->display() ;
	}
	public function edit()
	{
	}
	public function add()
	{
	}
	public function insert()
	{
	}
	public function update()
	{
	}
	/**
	 * 由教师删除选课的学生，
	 */
	public function delete()
	{
		$selectid = $_GET['id'];
		$select_info = $this->db_select_course->find($selectid);
		
		if(!$select_info){
			$this->error("没有该选课信息");
		}
		$teachid = $select_info['teachid'];
		$userid = $select_info['userid'];
		
		if($this->cou_teacher_check($teachid) == false){
			$this->error("你没有权限操作该课程");
			return false;
		}
		/*删除成绩信息*/
		$db = new Model();
		$ret = $db->execute("
					delete from score
					where userid = {$userid}
						and assessmentid in 
						(
							select assessmentid 
							from assessment
							where teachid = {$teachid}
						)
				");
		//echo $db->getLastSql();
		
		/*从select_course表中删除此学生选课信息*/
		$ret = $db->execute("
					delete from select_course
					where selectid = {$selectid}
				");
		//echo $db->getLastSql();
		if($ret == false){
			$this->assign("jumpURL",U("SelectCourse".'/index'));
			$this->error("删除错误",$db->getDbError());
		}
		$this->success("操作成功");
	}
	/**
	 * 选修课程
	 */ 
	public function select(){
		$userid = $this->get_user_id();
		$teachid = $_GET['teachid'];
		
		if($this->check_teachid($teachid) == false){
			$this->error("你没有权利操作该课程");
			return false;
		}
		$data = array();
		$data['teachid'] = $teachid;
		$data['userid'] = $userid;
		
		$id = $this->db_select_course->add($data);
		//echo $this->db_select_course->getLastSql();
		
		if ($id !==false) {
			$jumpUrl = $_POST['forward'] ? $_POST['forward'] : U(MODULE_NAME.'/index');
			$this->assign ( 'jumpUrl',$jumpUrl );
			$this->success (L('add_ok'));
		} else {
			$this->error(L('add_error').': '.$this->db_select_course->getDbError());
		}
	}
	/**
	 * 取消选修课程
	 */
	public function unselect(){
		$userid = $this->get_user_id();
		$teachid = $_GET['teachid'];
		
		if($this->check_teachid($teachid) == false){
			$this->error("你没有权利操作该课程");
			return false;
		}
		
		$data = array();
		$data['teachid'] = $teachid;
		$data['userid'] = $userid;
		$ret = $this->db_select_course->where($data)->delete();
		//echo $this->db_select_course->getLastSql();
		
		if ($ret !== false) {
			$jumpUrl = $_POST['forward'] ? $_POST['forward'] : U(MODULE_NAME.'/index');
			$this->assign ( 'jumpUrl',$jumpUrl );
			$this->success (L('delete_ok'));
		} else {
			$this->error(L('delete_error').': '.$this->db_select_course->getDbError());
		}
	}
	/**
	 * 检测该teachid的课程是否是当前列出的课程，防止意外修改
	 */
	public function check_teachid($teachid){
		$course_type_require = C("COURSE_TYPE_REQUIRE");
		
		$finish_status = C("TEACH_TASK_FINISH_STATUS_NOW");	//在授课程
		
		$db = new Model();
	
		$where = "teach_task.finish_status={$finish_status}
					and teach_task.courseid=course.courseid
					and course.cou_type != {$course_type_require}
					and teachid = {$teachid}";
					
		$tables = "teach_task,course";
		
		$teachs = $db->table($tables)->where($where)
			->field("teach_task.teachid")
			->find();
		/*如果有数据则表明是该课程是允许的*/
		if($teachs){
			return true;
		}
		return false;
	}
}

?>
