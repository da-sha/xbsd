<?php
/*-------------------------------------------------------------------
* Purpose:
*         本科生专业课程管理
* Time:
*         2012年11月6日 10:10:55
* Author:
*         张彦升
--------------------------------------------------------------------*/

class UndergraduatePublicCourseAction extends PublicCourseAction {
	
	public function _initialize(){
		parent::_initialize();
		
		$this->dao = D("UndergraduatePublicCourse");
		$this->level = C("LEVEL_UNDERGRADUATE_ID");
	}
	/**
	* 通过json返回课程数据
	*/	
	public function index(){
		$admissiondate = $_POST["admissiondate"];
		$semester = $_POST["semester"];
		
		if (empty($admissiondate) || empty($semester)) {
			$result='{"total":0,"rows":[]}';
			print_r($result);
			return;
		}
		$where = array();
		if($admissiondate){
			$where["grade"] = $admissiondate;
		}
		if($semester){
			$where["semester"] = $semester;
		}
		
		$total = $this->dao->where($where)->count();
		$data = $this->dao->where($where)
			->select();
		$this->easyui_return_data($total,$data);
	}
	
	public function add(){
		$_GET['p'] = $_POST['page'];
		$pagesize = $_POST["rows"];
		
		$cou_category = C("COURSE_CATEGORY_PUBLIC");
		
		$admissiondate = $_POST["admissiondate"];
		$semester = $_POST["semester"];
		$listall = $_POST["listall"];
		
		$where = array();
		if ($listall == false) {
			if (empty($admissiondate) || empty($semester)) {
				$result='{"total":0,"rows":[]}';
				print_r($result);
				return;
			}
			$where = array();
			if($admissiondate){
				$where["grade"] = $admissiondate;
			}
			if($semester){
				$where["semester"] = $semester;
			}
			

			$total = $this->dao->where($where)->count();
			$data = $this->dao->where($where)
				->select();
			foreach($data as $key => $course){
				$data[$key]['added'] = 1;
			}
			$this->easyui_return_data($total,$data);
			return;
		}
		
		$where = array();
		
		if($admissiondate){
			$where["grade"] = $admissiondate;
		}
		if($semester){
			$where["semester"] = $semester;
		}
		
		$added_course = array();
		if ($admissiondate && $semester) {
			$added_course = $this->dao->where($where)->field("courseid")->select();
		}
		
		$where = array();
		$name = $_POST['name'];
		$property = $_POST["property"];
		$exam_type = $_POST["exam_type"];

		$where['level'] = $this->level;
		$where['cou_category'] = $cou_category;
		$where['cou_type'] = C("COURSE_TYPE_REQUIRE");
		
		if($name){
			$where["name"] = array('like','%'.$name.'%');
		}
		if($property){
			$where["property"] = $property;
		}
		if($exam_type){
			$where["exam_type"] = $exam_type;
		}
		
		import('ORG.Util.Page');

		$total = $this->db_course->where($where)->count();
		
		$page=new Page($total,$pagesize);
		
		$data = $this->db_course->where($where)
			->limit($page->firstRow.','.$page->listRows)
			->select();
		
		foreach($data as $course_key=>$course){
			$data[$course_key]['added'] = 0;
			foreach($added_course as $added_key => $cou){
				if ($course["courseid"] == $cou["courseid"]) {
					$data[$course_key]['added'] = 1;
				}
			}
		}
		$this->easyui_return_data($total,$data);
	}
	
	public function insert(){
		$form_value = $_POST["form_value"];
		$courseid = $_POST["courseid"];
		
		$data = array();
		$data["grade"] = $form_value["admissiondate"];
		$data['semester'] = $form_value["semester"];
		$data["courseid"] = $courseid;
		
		$this->db_public_course->add($data);
		echo json_encode(array('success'=>true));
		return;
	}
	/**
	* 删除
	*/
	function delete(){
		$form_value = $_POST["form_value"];
		$courseid = $_POST["courseid"];
		
		$data = array();
		$data["grade"] = $form_value["admissiondate"];
		$data['semester'] = $form_value["semester"];
		$data["courseid"] = $courseid;
		
		if(false!==$this->db_public_course->where($data)->delete()){
			if($this->isAjax() == true){
				echo json_encode(array('success'=>true));
				return;
			}else{
				$this->success(L('delete_ok'));
			}
		}else{
			$this->error(L('delete_error').': '.$model->getDbError());
		}
	}
}

?>
