<?php
/*-------------------------------------------------------------------
* Purpose:
*         专业平台课程管理模块
* Time:
*         2012年11月6日 10:10:55
* Author:
*         张彦升
--------------------------------------------------------------------*/
abstract class MajorCourseAction extends CourseAction{
	protected $level;
	protected $cou_category;
	/**
	 * 
	 */
	public function _initialize(){
		parent::_initialize();
		
		$this->cou_category = C("COURSE_CATEGORY_MAJOR");
	}
	/**
	 * 得到专业平台课程信息
	 */
	public function get_course(){
		
		$majorid = $_POST["majorid"];
		$admissiondate = $_POST["admissiondate"];
		
		if (empty($majorid) || empty($admissiondate)) {
			$this->easyui_return_data(0);
			return;
		}
		$where = array();
		
		$where['cou_category'] = $this->cou_category;
		$where['level'] = $this->level;

		$where["majorid"] = $majorid;
		$where["grade"] = $admissiondate;

		$total = $this->db_course->where($where)->count();
		
		$data = $this->db_course->where($where)
			->select();
		
		$this->easyui_return_data($total,$data);
	}
	
	public function insert(){
		$_POST['cou_category'] = $this->cou_category;
		$_POST['level'] = $this->level;
		$_POST['grade'] = $_POST["admissiondate"];
		
		if (false === $this->db_course->create ()) {
			$this->easyui_return_data(0);
			return;
		}
		$id = $this->db_course->add();

		$_REQUEST["courseid"] = $id;
		
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
			$this->error (L('add_error').': '.$this->db_course->getDbError());
		}
	}
	
	public function update(){
		if (false === $this->db_course->create ()) {
			$this->easyui_return_data(0);
			return;
		}
		if (false !== $this->db_course->save ()) {
			if($this->isAjax() == true){
				echo json_encode($_REQUEST);
				return;
			}else{
				$jumpUrl = $_POST['forward'] ? $_POST['forward'] : U(MODULE_NAME.'/index');
				$this->assign ( 'jumpUrl',$jumpUrl );
				$this->success (L('update_ok'));
			}
		} else {
			$this->success (L('edit_error').': '.$this->db_course->getError());
		}
	}
	
	public function delete(){
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
			if(false!==$this->db_course->delete($id)){
				if($this->isAjax() == true){
					echo json_encode(array('success'=>true));
					return;
				}else{
					$this->success(L('delete_ok'));
				}
			}else{
				$this->error(L('delete_error').': '.$this->db_course->getDbError());
			}
		}else{
			$this->error (L('do_empty'));
		}
	}
}
?>
