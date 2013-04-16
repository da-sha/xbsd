<?php
/*-------------------------------------------------------------------
* Purpose:
*         教师的课程模块
* Time:
*         2012年11月6日 10:10:55
* Author:
*         张彦升
--------------------------------------------------------------------*/
class TeacherCourseAction extends CourseBaseAction{
	/**
	 * 初始化方法
	 */	
	function _initialize(){
		parent::_initialize();
		
		/**
		 * 模拟表，构建子菜单,注意这里的action会分为教师和学生两个子项，
		 * 在模板中会增加相应名称
		 */
		$this->child_menu = array(
			array("name"=>"基本信息","model"=>"TeacherCourse","action"=>"basic_info","cla"=>"basic_info","icon"=>"","xpos"=>"","ypos"=>"","condition"=>"return true;"),	//condition为显示条件
			array("name"=>"考核管理","model"=>"Assessment","action"=>"teacher_assess","cla"=>"assessment","icon"=>"","xpos"=>"","ypos"=>"","condition"=>"return true;"),
			array("name"=>"课程异动","model"=>"CourseChange","action"=>"teacher_cou_change","cla"=>"cou_change","icon"=>"","xpos"=>"","ypos"=>"","condition"=>"return true;"),
			array("name"=>"成绩信息","model"=>"Score","action"=>"score","cla"=>"score","icon"=>"","xpos"=>"","ypos"=>"","condition"=>"return true;"),
			array("name"=>"课程进度","model"=>"TeachPlan","action"=>"teacher_plan","cla"=>"teachplan","icon"=>"","xpos"=>"","ypos"=>"","condition"=>"return true;"),
			array("name"=>"选课学生","model"=>"SelectCourse","action"=>"select_student","cla"=>"select_course","icon"=>"","xpos"=>"","ypos"=>"","condition"=>"return \$teach[classname]=='';"),
			);
	}
	function now_course() {
		$userid = $this->get_user_id();
		
		$finish_status = C("TEACH_TASK_FINISH_STATUS_NOW");	//在授课程
		$course_type_require = C("COURSE_TYPE_REQUIRE");	//必修课
		
		$Model = new Model(); // 实例化一个model对象 没有对应任何数据表

		//选出必修课程和选修课程合并
		$teachs = $Model->query(" select teach_task.teachid,
							get_course_name(teach_task.courseid) as coursename,
							get_field_dict_name('course','level',course.level ) as level,
							get_field_dict_name('course','property',course.property ) as property,
							get_field_dict_name('course','cou_type',course.cou_type ) as cou_type,
							get_field_dict_name('course','cou_category',course.cou_category ) as cou_category,
							get_class_name(teach_class.classid) as classname,
							get_class_grade(teach_class.classid) as grade
						from teach_task,teach_class,course
						where teach_task.userid={$userid}
							 and teach_task.finish_status=$finish_status
							 and teach_task.teachid=teach_class.teachid
							 and teach_task.courseid=course.courseid
							 and course.cou_type = {$course_type_require}
						union
						select teach_task.teachid,
							get_course_name(teach_task.courseid) as coursename,
							get_field_dict_name('course','level',course.level ) as level,
							get_field_dict_name('course','property',course.property ) as property,
							get_field_dict_name('course','cou_type',course.cou_type ) as cou_type,
							get_field_dict_name('course','cou_category',course.cou_category ) as cou_category,
							'' as classname,
							'' as grade
						from teach_task,course
						where teach_task.userid={$userid}
							 and teach_task.finish_status=$finish_status
							 and teach_task.courseid=course.courseid
							 and course.cou_type != {$course_type_require}
						");


		//$sql = $Model->getLastSql();
		//echo $sql;
		//dump($teachs);
		$this->assign("teachs",$teachs);
		$this->display();
	}
	function past_course() {
		$userid = $this->get_user_id();
		
		$finish_status = C("TEACH_TASK_FINISH_STATUS_PAST");	//在授课程
		
		$db = new Model();
		$where = "teach_task.userid={$userid} and teach_task.finish_status={$finish_status}
				and teach_task.courseid=course.courseid";
		$tables = "teach_task,course";
		$count = $db->table($tables)->where($where)->count();
		import('ORG.Util.Page');
		$page = new Page($count, C("PAGESIZE"));
		$show = $page->show();
		$this->assign("show", $show);
		
		$course = $db->table($tables)->where($where)
			->field("course.name,
					teach_task.teachid,
					get_field_dict_name('course','level',course.level ) as level,
					get_field_dict_name('course','property',course.property ) as property,
					get_field_dict_name('course','cou_type',course.cou_type ) as cou_type,
					get_field_dict_name('course','cou_category',course.cou_category ) as cou_category,
					get_field_dict_name('teach_task','teach_quarter',teach_task.teach_quarter ) as teach_quarter,
					teach_task.teach_year
					")
			->limit($page->firstRow . ',' . $page->listRows)
			->select();
		
		$this->assign("course_list",$course);
		//dump($course);
		$this->display();
	}
	function future_course() {
		$userid = $this->get_user_id();
		
		$finish_status = C("TEACH_TASK_FINISH_STATUS_FUTURE");	//拟授课程
		
		$db = new Model();
		$where = "teach_task.userid={$userid} and teach_task.finish_status={$finish_status}
				and teach_task.courseid=course.courseid";
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
					teach_task.teach_year
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
		$this->assign("TEACH_TASK_APPROVAL_STATUS_NO",C("TEACH_TASK_APPROVAL_STATUS_NO"));
		$this->assign("TEACH_TASK_APPROVAL_STATUS_YES",C("TEACH_TASK_APPROVAL_STATUS_YES"));
		$this->assign("TEACH_TASK_APPROVAL_STATUS_WAIT",C("TEACH_TASK_APPROVAL_STATUS_WAIT"));
	
		$this->assign("course_list",$teachs);
		//dump($teachs);
		$this->display();
	}
	/**
	* 课程基本信息
	*/
	public function basic_info(){
		$teachid = $_GET['teachid'];
		if ($this->cou_teacher_check($teachid) == false) {
			return false;
		}
		$data = $this->get_basic_info($teachid);

		$this->assign("course",$data);
		$this->display() ;
	}
	/**
	 * 更新基本信息操作
	 */	
	public function update_basic_info(){
		$teachid = $_POST["teachid"];
		if ($this->cou_teacher_check($teachid) == false) {
			$this->returnMessage("对不起，您没有权利操作该课程");
			return true;
		}
		if (false === $this->db_teach_task->create ()) {
			$this->error ( $this->db_teach_task->getError () );
		}
		if (false !== $this->db_teach_task->save ()) {
			$this->success (L('edit_ok'));
		} else {
			$this->success (L('edit_error').': '.$this->db_teach_task->getError());
		}
	}
	/**
	 * 教师确认授课模块
	 */
	public function teacher_approval(){
		$b_approval = $_GET['b_approval'];
		$teachid = $_GET["teachid"];
		
		if ($this->cou_teacher_check($teachid) == false) {
			$this->returnMessage("对不起，您没有权利操作该课程");
			return true;
		}
		$data["teacher_approval_comment"] = $_GET["teacher_approval_comment"];
		if ($b_approval == 1) {
			$data['status'] = C("TEACH_TASK_APPROVAL_STATUS_YES");	//同意
			$ret = $this->db_teach_task->where("teachid=$teachid")->save($data);
		}else{
			$data['status'] = C("TEACH_TASK_APPROVAL_STATUS_NO");	//不同意
			$ret = $this->db_teach_task->where("teachid=$teachid")->save($data);
		}
		if ($ret == false) {
			$this->error($this->db_teach_task->getDbError());
		}else{
			$this->success("操作成功");
		}
	}
	
	/**
	 * approval模块
	 */	
	public function approval(){
		$teachid = $_GET["teachid"];
		
		if ($this->cou_teacher_check($teachid) == false) {
			$this->returnMessage("对不起，您没有权利操作该课程");
			return true;
		}
		$this->assign("teachid",$teachid);
		$course = $this->db_teach_task->where("teachid={$teachid}")->find();
		$this->assign("course",$course);
		
		$this->display();
	}
}
?>