<?php
/*-------------------------------------------------------------------
* Purpose:
*         学生的课程模块
* Time:
*         2012年11月6日 10:10:55
* Author:
*         张彦升
--------------------------------------------------------------------*/
class StudentCourseAction extends CourseBaseAction{
	/**
	 * 初始化方法
	 *
	 */	
	function _initialize(){
		parent::_initialize();
		
		/**
		 * 模拟表，构建子菜单,注意这里的action会分为教师和学生两个子项，
		 * 在模板中会增加相应名称
		 */
		$this->child_menu = array(
			array("name"=>"基本信息","model"=>"StudentCourse","action"=>"basic_info","cla"=>"basic_info","icon"=>"","xpos"=>"","ypos"=>""),
			array("name"=>"考核成绩","model"=>"Assessment","action"=>"student_assess","cla"=>"assessment","icon"=>"","xpos"=>"","ypos"=>""),
			array("name"=>"课程异动","model"=>"CourseChange","action"=>"student_cou_change","cla"=>"cou_change","icon"=>"","xpos"=>"","ypos"=>""),
			array("name"=>"课程进度","model"=>"TeachPlan","action"=>"student_plan","cla"=>"teachplan","icon"=>"","xpos"=>"","ypos"=>""),
			);
	}
	function now_course() {
		$userid = $this->get_user_id();

		$finish_status = C("TEACH_TASK_FINISH_STATUS_NOW");	//在修课程
		
		$db = new Model();
		$where = "select_course.userid={$userid}
				 and teach_task.teachid=select_course.teachid
				 and teach_task.finish_status={$finish_status}
				 and teach_task.courseid=course.courseid";

		$tables = "teach_task,select_course,course";
		$course = $db->table($tables)
			->where($where)
			->field("teach_task.teachid,
					get_course_name(teach_task.courseid) as coursename,
					get_field_dict_name('course','property',course.property ) as property,
					get_field_dict_name('course','cou_type',course.cou_type ) as cou_type,
					get_field_dict_name('course','cou_category',course.cou_category ) as cou_category
					")
			->select();
		
		$this->assign("teachs",$course);

		//echo $db->getLastSql();
		//dump($course);
		/*其框架显示与教师相同*/
		$this->display();
	}
	function past_course() {
		$userid = $this->get_user_id();
		
		$finish_status = C("TEACH_TASK_FINISH_STATUS_PAST");	//已修课程
		
		$db = new Model();
		$where = "select_course.userid={$userid}
				 and teach_task.teachid=select_course.teachid
				 and teach_task.finish_status={$finish_status}
				 and teach_task.courseid=course.courseid";

		$tables = "teach_task,course,select_course";
		$count = $db->table($tables)->where($where)->count();
		import('ORG.Util.Page');
		$page = new Page($count, C("PAGESIZE"));
		$show = $page->show();
		$this->assign("show", $show);
		
		$course = $db->table($tables)
			->where($where)
			->field("teach_task.teachid,
					teach_task.courseid,
					course.name as coursename,
					teach_task.teach_year as teach_year,
					course.credit,
					get_field_dict_name('course','cou_type',cou_type ) as cou_type,
					get_field_dict_name('course','semester',semester ) as semester,
					get_teacher_name(teach_task.userid) as teacher_name
				")
			->limit($page->firstRow . ',' . $page->listRows)
			->select();
		
		$this->assign("course_list",$course);
		$this->display();
	}
	function future_course() {
		$userid = $this->get_user_id();
		$semester_id = $_GET["semester_id"];
		$this->assign("semester_id",$semester_id);
		
		$finish_status = C("TEACH_TASK_FINISH_STATUS_FUTURE");	//拟修课程
		
		$this->get_field_dict("course","semester","semesters");
		
		$course_type_require = C("COURSE_TYPE_REQUIRE");
		
		$course_category_major = C("COURSE_CATEGORY_MAJOR");
		$course_category_academy = C("COURSE_CATEGORY_ACADEMY");
		$course_category_school = C("COURSE_CATEGORY_SCHOOL");
		
		/*选出该学生所在班级的注册日期*/
		$db = new Model();
		$userdata = $db->table("student,class")
						->where("student.classid = class.classid
								and student.userid={$userid}")
						->field("student.admissiondate,
								class.majorid,
								class.classid")
						->find();
		$admissiondate = $userdata["admissiondate"];
		$majorid = $userdata["majorid"];
		$classid = $userdata["classid"];
		
		$semester = $this->get_class_semester($admissiondate);
		$semester += 1;	//定位到下一学期
		if (empty($semester_id) == true) {
			$semester_id = $semester;
		}
		$this->assign("semester",$semester);

		/*选出专业平台必修课，学院平台必修课，学校平台必修课的并集*/
		
		$data = $db->query("
					select  courseid,
							name,
							get_field_dict_name('course','semester', semester ) as course_semester,
							get_field_dict_name('course','property', property ) as property,
							get_field_dict_name('course','cou_type', cou_type ) as cou_type,
							get_field_dict_name('course','cou_category',cou_category ) as cou_category,
							get_field_dict_name('course','exam_type', course.exam_type ) as exam_type
					from 	course
					where   grade = {$admissiondate}
							and semester = {$semester_id}
							and cou_type = {$course_type_require}
							and cou_category = {$course_category_major}
							and majorid = {$majorid}
					union
					select  courseid,
							name,
							get_field_dict_name('course','semester', semester ) as course_semester,
							get_field_dict_name('course','property', property ) as property,
							get_field_dict_name('course','cou_type', cou_type ) as cou_type,
							get_field_dict_name('course','cou_category',cou_category ) as cou_category,
							get_field_dict_name('course','exam_type', course.exam_type ) as exam_type
					from    course
					where   grade = {$admissiondate}
							and semester = {$semester_id}
							and cou_type = {$course_type_require}
							and cou_category != {$course_category_major}
					");
		//echo $db->getLastSql();

		$this->assign("course_list",$data);
		
		$this->display();
	}
	/**
	* 课程基本信息
	*/
	public function basic_info(){
		$teachid = $_GET['teachid'];
		if ($this->cou_student_check($teachid) == false) {
			return false;
		}

		$data = $this->get_basic_info($teachid);

		$this->assign("course",$data);
		$this->display() ;
	}
}
?>