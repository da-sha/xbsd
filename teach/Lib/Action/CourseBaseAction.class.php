<?php

/*-------------------------------------------------------------------
* Purpose:
*         课程基类
* Time:
*         2012年11月6日 10:10:55
* Author:
*         张彦升
--------------------------------------------------------------------*/

abstract class CourseBaseAction extends CommonAction{
	protected $db_teach_task;
	protected $db_course;
	
	function _initialize(){
		parent::_initialize();
		
		$this->db_teach_task = new TeachTaskModel();
		$this->db_course = D("course");
	}
/**
     *
     * Enter description here ...
     */
	function insert() {
		$name = MODULE_NAME;
		$model = D ($name);

		if (false === $model->create ()) {
			$this->error ( $model->getError () );
		}
		$id = $model->add();

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
			$this->error (L('add_error').': '.$model->getDbError());
		}
	}
	
	/**
     * 删除
     *
     */
	function delete(){
		$name = MODULE_NAME;
		$model = M ( $name );
		$pk = $model->getPk ();
		$id = $_REQUEST [$pk];
		if (isset ( $id )) {
			if(false!==$model->delete($id)){
				if($this->isAjax() == true){
					echo json_encode(array('success'=>true));
					return;
				}else{
					$this->success(L('delete_ok'));
				}
			}else{
				$this->error(L('delete_error').': '.$model->getDbError());
			}
		}else{
			$this->error (L('do_empty'));
		}
	}
	 /**
     * 更新操作
     */
	function update() {
		$name = MODULE_NAME;
		$model = D ( $name );
		if (false === $model->create ()) {
			$this->error ( $model->getError () );
		}
		if (false !== $model->save ()) {
			if($this->isAjax() == true){
				echo json_encode(array('success'=>true));
				return;
			}else{
				$jumpUrl = $_POST['forward'] ? $_POST['forward'] : U(MODULE_NAME.'/index');
				$this->assign ( 'jumpUrl',$jumpUrl );
				$this->success (L('update_ok'));
			}
			
		} else {
			$this->success (L('edit_error').': '.$model->getError());
		}
	}
	/**
	 * 恢复数据字典
	 *
	 */	
	public function recover_data_dict(){
		
	}

	/**
	 * 检测老师是否带该课程
	 */
	public function cou_teacher_check($teachid){
		$course = $this->db_teach_task->where("teachid={$teachid}")->find();

		if ($course["userid"] != $this->get_user_id()) {
			return false;
		}
		return true;
	}
	/**
	 * 检测学生是否选修该课程
	 */
	public function cou_student_check($teachid){
		$db_select_course = M("select_course");
		$stu_select_course = $db_select_course->where("userid={$this->get_user_id()}
					 and teachid={$teachid}")->find();
		if (empty($stu_select_course) == true) {
			return false;
		}
		return true;
	}
	/**
	 * 根据当前时间判断春秋季,2为秋季，1为春季
	 */
	protected function get_quarter(){
		$month = intval(date("n"));
		
		if ($month >=3 && $month <= 8) {
			return $this->sys_config_value("spring_semester");
			//return 1;
		}
		return $this->sys_config_value("autumn_semester");
		//return 2;
	}
	
	/**
	 * 根据注册日期得到其相对本学期是第几学期
	 */
	protected function get_class_semester($admissiondate){
		$quarter = ( date('Y') - $admissiondate) * 2 + $this->get_quarter() - 1;
		return $quarter;
	}
	
	/**
	* 课程基本信息
	*/
	protected function get_basic_info($teachid){
		$db = new Model();
		$tables = "teach_task,course";
		$where = "teach_task.teachid={$teachid} and teach_task.courseid=course.courseid";
		$data = $db->table($tables)
			->where($where)
			->field("
				teach_task.teachid,
				course.name as name,
				get_field_dict_name('course','level',course.level ) as level,
				get_field_dict_name('course','property',course.property ) as property,
				get_field_dict_name('course','cou_type',course.cou_type ) as cou_type,
				get_field_dict_name('course','cou_category',course.cou_category ) as cou_category,
				get_field_dict_name('course','exam_type',course.exam_type ) as exam_type,
				course.planweektime,
				course.expweektime,
				course.week,
				teach_task.textbook,
				teach_task.publisher,
				teach_task.author,
				teach_task.publish_date,
				teach_task.publish_num,
				teach_task.creative,
				teach_task.reference
				")
			->find();
		//echo $db->getLastSql();
		//dump($data);
		return $data;
	}
	
	/**
	 * 得到该管理者的机构号
	 */	
	protected function get_orgid() {
		return 2;
	}
	
	/**
	 * 将课程模块中用到的数据字典内容全部赋值到模板
	 */	
	protected function assign_course_field_dict(){
		$orgid = $this->get_orgid();
		$db_major = new MajorModel();
		$year = date("Y");
		//针对年级
		$year -= 1;
		$admission = array();
		for($i = 0; $i < 4; $i++ ){
			$admission[$i]['id'] = $year;
			$admission[$i]['name'] = $year;
			$year--;
		}
		$admission[$i]['id'] = $this->sys_config("not_limit_admission") ; ;
		$admission[$i]['name'] = "不限年" ;		//省略级
		
		$this->assign("admissiondate_list", $admission);
	}
	
	/**
	 * 向模板中assign下一学期的年份
	 *
	 */	
	protected function assign_next_teach_year(){
		$year = date("Y");
		$month = date("n");
		
		if( $month > 8 )
		{
			$year = $year + 1 ;
		}
		$this->assign("teach_year", $year ) ;
		
		return $year;
	}
	/**
	 * 向模板中assign当前学期的年份
	 *
	 */	
	protected function assign_cur_teach_year(){
		$year = date("Y");
		$this->assign("teach_year", $year ) ;
		
		return $year;
	}
	
	/**
	 * 向模板中assign季度
	 */
	protected function assign_quarter(){
		$this->get_field_dict( 'teach_task', 'teach_semester', 'quarter_list');
	}
	/**
	 * 得到当前是秋季还是春季学期的id
	 */	
	protected function assign_cur_quarter_id(){
		$semester = $this->get_quarter();
		$this->assign("semester",$semester);
		
		return $semester;
	}
	/**
	 * 得到下一学期是秋季还是春季学期的id
	 */	
	protected function assign_next_quarter_id(){
		$semester = $this->get_quarter();
		$semester = $semester % 2 + 1;
		$this->assign("semester",$semester);
		
		return $semester;
	}
	/**
	 * 向模板中assign本科生年级列表
	 */	
	protected function assign_undergraduate_grade_list($extend = 0){
		$year = date("Y");
		
		$semester = $this->get_quarter();
		/*春季,我们还没毕业，新生还没来*/
		if ($semester == $this->sys_config_value("spring_semester")) {
			$year -= 1;
		}
		$t_extend = $extend;
		
		while ($t_extend) {
			$year++;
			$t_extend--;
		}
		$admission = array();
		for($i = 0; $i < 4 + $extend; $i++ ){
			$admission[$i]['id'] = $year;
			$admission[$i]['name'] = $year;
			$year--;
		}
		
		$this->assign("admissiondate_list", $admission);
	}
	/**
	 * 向模板中assign硕士生年级列表
	 */	
	protected function assign_postgraduate_grade_list(){
		$year = date("Y");
		
		$semester = $this->get_quarter();
		/*春季,我们还没毕业，新生还没来*/
		if ($semester == $this->sys_config_value("spring_semester")) {
			$year -= 1;
		}
		$admission = array();
		for($i = 0; $i < 3; $i++ ){
			$admission[$i]['id'] = $year;
			$admission[$i]['name'] = $year;
			$year--;
		}
		/*
		$admission[$i]['id'] = $this->sys_config("not_limit_admission") ; ;
		$admission[$i]['name'] = "不限年" ;		//省略级
		*/
		
		$this->assign("admissiondate_list", $admission);
		
	}
	/**
	 * 向模板中assign本科生的专业列表
	 *
	 */	
	protected function assign_undergraduate_major_list(){
		$orgid = $this->get_orgid();
		$level = C("LEVEL_UNDERGRADUATE_ID");
		
		$major_list = $this->get_major_list($orgid,$level);
		$this->assign("major_list", $major_list);
	}
	/**
	* 向模板中assign研究生的专业列表
	*
	*/	
	protected function assign_postgreaduate_major_list(){
		$orgid = $this->get_orgid();
		$level = C("LEVEL_POSTGRADUATE_ID");
		
		$major_list = $this->get_major_list($orgid,$level);
		$this->assign("major_list", $major_list);
	}
	
	/**
	 * 得到专业列表
	 */	
	private function get_major_list($orgid,$level){
		$db_major = D("Major");
		$major = $db_major->where("orgid={$orgid} and level={$level}")
			->select();
		return $major;
	}
	
	/**
	 * 向模板中assign班级的学期列表（第一学期至第八学期）
	 * 注意这里默认实现使用major_course的数据字典，若要使用其它，
	 * 还需重载 
	 */	
	protected function assign_class_semester_list(){
		$this->get_field_dict("course", "semester", "semester_list") ;
	}
	
	/**
	 * 向模板中assign学生层次列表
	 */	
	protected function assign_level_list(){
		/*学生层次*/
		$this->get_field_dict("course", "level", "level_list") ;
	}
	/**
	 * 向模板中assign课程性质
	 */	
	protected function assign_course_property_list(){
		$this->get_field_dict( 'course', 'property', 'course_property_list');
	}
	/**
	 * 向模板中assign课程类型
	 */	
	protected function assign_course_type_list(){
		$this->get_field_dict( 'course', 'cou_type', 'course_type_list');
	}
	/**
	* 向模板中assign课程考试方式
	*/	
	protected function assign_exam_type_list(){
		$this->get_field_dict( 'course', 'exam_type', 'exam_list');
	}
	/**
	 * 向模板中assign课程分类
	 */	
	protected function assign_course_category_list(){
		/*课程类型*/
		$this->get_field_dict('course', 'cou_category', 'course_category_list');
	}
	
	/**
	 * 将php查询后的数组编码成easyui可接受的格式然后返回
	 */	
	protected function easyui_return_data($total,$data = array()){
		if ($total == 0) {
			$result='{"total":0,"rows":[]}';
			print_r($result);
		}
		else{
			$result='{"total":'.$total.',"rows":'.json_encode($data).'}';
			print_r($result);
		}
	}
}
?>
