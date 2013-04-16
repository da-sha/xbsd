<?php
/*-------------------------------------------------------------------
* Purpose:
*         课程的管理模块
* Time:
*         2012年11月6日 10:10:55
* Author:
*         张彦升
--------------------------------------------------------------------*/
class CourseTransitionAction extends CourseBaseAction{
	protected $db_course;
	/**
	 * 
	 */
	function _initialize(){
		$this->db_course = M("course");
		
		parent::_initialize();
	}
	/**
	 * 课程过渡，其用户通常为系主任，院长等
	 */	
	public function transition(){
		$this->assign_course_field_dict();
		
		/*beginning get course data*/

		$keyword = $_GET["keyword"];
		$searchtype = $_GET["searchtype"];
		$level = $_GET["level"];
		$cou_category = $_GET["cou_category"];
		$majorid = $_GET["majorid"];
		$admissiondate = $_GET["admissiondate"];

		$this->assign($_GET);

		/*检验搜索设置*/
		$where = array();
		if(!empty($keyword) && !empty($searchtype)){
			$where[$searchtype]=array('like','%'.$keyword.'%');
		}
		if($level){
			$where["course.level"] = $level;
		}
		if($cou_category){
			$where["course.cou_category"] = $cou_category;
		}
		
		$where["teach_task.finish_status"] = C("TEACH_TASK_FINISH_STATUS_NOW");
		$where["_complex"] = "teach_task.courseid=course.courseid and teacher.userid=teach_task.userid";
		
		$db = new Model();
		$tables = "course,teach_task,teacher";
		
		$count = $db->table($tables)->where($where)->count();
		import('ORG.Util.Page');
		$page = new Page($count, C("PAGESIZE"));
		$show = $page->show();
		$this->assign("show", $show);
		$data = $db->table($tables)->where($where)
			->field("
				teach_task.teachid,
				course.courseid,
				course.name as course_name,
				teacher.name as teacher_name,
				get_field_dict_name('course','level',course.level ) as level,
				get_field_dict_name('course','property',course.property ) as property,
				get_field_dict_name('course','cou_type',course.cou_type ) as cou_type,
				get_field_dict_name('course','cou_category',course.cou_category ) as cou_category,
				get_field_dict_name('course','exam_type',course.exam_type ) as exam_type,
				get_field_dict_name('teach_task','finish_status',teach_task.finish_status ) as finish_status,
				course.week as week
				"
				)
			->limit($page->firstRow . ',' . $page->listRows)
			->select();
		$this->assign('teach_list', $data);
		$this->display();
	}
	/**
	 * 全部过渡
	 */
	public function do_transition_all() {
		$teachs = $_POST['teachs'];
		
		if(!empty($teachs) && is_array($teachs)){
			$data['finish_status'] = C("TEACH_TASK_FINISH_STATUS_PAST");	/*已授*/
			
			foreach($teachs as $key=>$teachid){
				$ret = $this->db_teach_task->where("teachid={$teachid}")->save($data);
				if ($ret == false) {
					$this->error("课程过渡出错");
				}
			}
			if ($ret) {
				$this->success("课程已过渡至已授");
			}
		}else{
			$this->error(L('do_empty'));
		}
	}
	/**
	 * 过渡单个课程
	 */	
	public function do_transition() {
		$teachid = $_GET["teachid"];
		
		$data['finish_status'] = C("TEACH_TASK_FINISH_STATUS_PAST");	/*已授*/
		
		$ret = $this->db_teach_task->where("teachid={$teachid}")->save($data);
		if ($ret) {
			$this->success("课程已过渡至已授");
		}else{
			$this->error("课程过渡出错");
		}
	}
	/**
	 * 恢复过渡
	 */
	public function recover(){
		$this->assign_course_field_dict();
		/*beginning get course data*/

		$keyword = $_GET["keyword"];
		$searchtype = $_GET["searchtype"];
		$level = $_GET["level"];
		$cou_category = $_GET["cou_category"];
		$majorid = $_GET["majorid"];
		$admissiondate = $_GET["admissiondate"];

		$this->assign($_GET);

		/*检验搜索设置*/
		$where = array();
		if(!empty($keyword) && !empty($searchtype)){
			$where[$searchtype]=array('like','%'.$keyword.'%');
		}
		if($level){
			$where["course.level"] = $level;
		}
		if($cou_category){
			$where["course.cou_category"] = $cou_category;
		}
		
		$where["teach_task.finish_status"] = C("TEACH_TASK_FINISH_STATUS_PAST");
		$where["_complex"] = "teach_task.courseid=course.courseid";
		
		$db = new Model();
		$tables = "course,teach_task";
		
		$count = $db->table($tables)->where($where)->count();
		import('ORG.Util.Page');
		$page = new Page($count, 15);
		$show = $page->show();
		$this->assign("show", $show);
		$data = $db->table($tables)->where($where)
			->field("
				teach_task.teachid,
				course.courseid,
				course.name as course_name,
				get_teacher_name(teach_task.userid) as teacher_name,
				get_field_dict_name('course','level',course.level ) as level,
				get_field_dict_name('course','property',course.property ) as property,
				get_field_dict_name('course','cou_type',course.cou_type ) as cou_type,
				get_field_dict_name('course','cou_category',course.cou_category ) as cou_category,
				get_field_dict_name('course','exam_type',course.exam_type ) as exam_type,
				get_field_dict_name('teach_task','finish_status',teach_task.finish_status ) as finish_status,
				course.week as week
				"
				)
			->limit($page->firstRow . ',' . $page->listRows)
			->select();
		$this->assign('teach_list', $data);
		$this->display();
	}
	/**
	* 全部恢复
	*/
	public function do_recover_all() {
		$teachs = $_POST['teachs'];
		
		if(!empty($teachs) && is_array($teachs)){
			$data['finish_status'] = C("TEACH_TASK_FINISH_STATUS_NOW");	/*在授*/
			
			foreach($teachs as $key=>$teachid){
				$ret = $this->db_teach_task->where("teachid={$teachid}")->save($data);
				if ($ret == false) {
					$this->error("课程过渡出错");
				}
			}
			if ($ret) {
				$this->success("课程已过渡至已授");
			}
		}else{
			$this->error(L('do_empty'));
		}
	}
	/**
	 * 恢复单个课程
	 */	
	public function do_recover() {
		$teachid = $_GET["teachid"];
		
		$data['finish_status'] = C("TEACH_TASK_FINISH_STATUS_NOW");	/*在授*/
		
		$ret = $this->db_teach_task->where("teachid={$teachid}")->save($data);
		if ($ret) {
			$this->success("课程已过渡至已授");
		}else{
			$this->error("课程过渡出错");
		}
	}
}
?>
