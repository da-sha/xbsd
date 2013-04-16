<?php

class WorkloadAction extends CommonAction {
	private function showconditoin(){
		$this->get_field_dict("exam_room", "semester", "semester_list") ;
		$year = date("Y") ;
		$year_list = array(1,1,1,1) ;
		for( $i = -2 ; $i <2 ; $i++ )
		{
			$year_list[$i+2] = $year+$i ;
		}
		$this->assign("year_list" , $year_list ) ;
		$syear = session("year");
		if( $_GET['year'] ){
			session( 'year' , $_GET['year'] ) ;
			session( 'semester' , $_GET['semester'] ) ;
		}else{
			$_GET['year'] = session( 'year' ) ;
			$_GET['semester'] = session( 'semester' ) ;
		}
	}
	public function index(){
		$this->showconditoin() ;
		$year = $_GET['year'];
		$semester = $_GET['semester'];
		if( !$year && !$semester ){
			$this->assign('show', "请先选择学年和学期！");
		}else{
			$basewhere="year={$year} and semester={$semester}" ;
			//获取常规工作量
			$db = M("workload") ;
			$where = $basewhere." and userid={$this->get_user_id()}" ;
			$data = $db->where($where)
					->field("*,get_teacher_name(userid) as tea_name,get_field_dict_name('workload','semester',semester) as semester")
					->find() ;
			$this->assign( "work", $data ) ;
		}
		$this->display();
	}
	public function insert(){
		$db = M("extendwork") ;
		$_POST["teacherid"] = $this->get_user_id() ;
		if( false !== $db->create() && false !== $db->add() ){
			$this->success("恭喜你添加成功！") ;
		}else{
			$this->error("对不起，插入数据失败！") ;
		}
	}
	public function apply(){
		$this->showconditoin() ;
		$this->display();
	}
	public function extendview(){
		$id = $_GET["id"] ;
		if($id){
			$db = M("extendwork") ;
			$data = $db->field("*,get_teacher_name(teacherid) as teacher,get_field_dict_name('extendwork','state',state) as statename")->find($id) ;
			$this->assign("extend" , $data ) ;
			$this->display() ;
		}else{
			$this->error("错误的参数！") ;
		}
	}
	public function extra(){
		$this->showconditoin() ;
		$year = $_GET['year'];
		$semester = $_GET['semester'];
		if( !$year && !$semester ){
			$this->assign('show', "请先选择学年和学期！");
		}else{
			$basewhere="year={$year} and semester={$semester}" ;
			//获取给教师额为工作量
			$otherdb = M("extendwork") ;
			$where = $basewhere." and teacherid={$this->get_user_id()}" ;
			$extenddata = $otherdb->where($where)
					->field("*,get_field_dict_name('extendwork','state',state) as statename")
					->select() ;
			if($extenddata == NULL)
			{
				$this->assign("show", "暂无额外工作量！") ;
			}else{
				$this->assign( "extendlist", $extenddata ) ;
			}
		}
		$this->display();
	}
	
	public function approve(){
		$this->showconditoin() ;
		$this->get_field_dict("extendwork", "state", "state_list") ;
		$year = $_GET['year'];
		$semester = $_GET['semester'];
		if( $_GET['state'] ){
			session("state", $_GET['state']) ;
		}else{
			$_GET['state'] = session("state") ;
		}
		$state = $_GET['state'];
		if( !$year && !$semester && !$state ){
			$this->assign('show', "请先选择学年,学期和状态！");
		}else{
			$where="year={$year} and semester={$semester} and state={$state}" ;
			$db = M("extendwork") ;
			$data = $db->field("*,get_teacher_name(teacherid) as teacher,get_field_dict_name('extendwork','state',state) as statename")->where($where)->select() ;
			if( $data ){
				$this->assign("extendlist", $data) ;
			}else{
				$this->assign("show", "对不起，没有数据！") ;
			}
		}
		$this->display() ;
	}
	public function approveview(){
		$id = $_GET["id"] ;
		if($id){
			$db = M("extendwork") ;
			$data = $db->field("*,get_teacher_name(teacherid) as teacher,get_field_dict_name('extendwork','state',state) as statename")->find($id) ;
			$this->assign("extend" , $data ) ;
			$this->display() ;
		}else{
			$this->error("错误的参数！") ;
		}
	}
	public function doapprove(){
		if( $_POST["state"] == "pass" ){
			 $_POST["state"] = 1 ;
		}else{
			$_POST["state"] = 2 ;
		}
		$db = M("extendwork") ;
		if( false !== $db->create() && false !== $db->save() ){
			$this->success("修改成功！") ;
		}else{
			$this->error("修改失败！");
		}
	}

	public function teacherworkload() {
		$this->showconditoin() ;
		$year = $_GET['year'];
		$semester = $_GET['semester'];
		if( !$year && !$semester ){
			$this->assign('show', "请先选择学年和学期！");
		}else{
			$db = M("workload") ;
			$where = "year={$year} and semester={$semester}";
			$count = $db->where($where)->count();
			import('ORG.Util.Page');
			$page = new Page($count, C('PAGESIZE'));
			$show = $page->show();
			$this->assign("show", $show);
			$data = $db->where($where)
					->field("get_teacher_name(userid) as tea_name, thsum_time, expsum_time, papthisum_time, 
							examsum_time, prasum_time, others, total_time")
					->limit($page->firstRow . ',' . $page->listRows)
					->select() ;
			if (!$data){
				$this->assign( "show", "此学年学期暂无工作量信息！" ) ;
			}  else {
				$this->assign( "workloadlist", $data ) ;
			}
		}
		$this->display();
	}
	
	public function iniworkload() {
		$this->showconditoin() ;
		$year = $_GET['year'];
		$semester = $_GET['semester'];
		if( !$year && !$semester ){
			$this->assign('show', "请先选择学年和学期！");
		}else{
			$teacherdb = M('teacher');
			$teacher = $teacherdb->field('userid')->select();
			$data = array(
				"year"=>$year,
				"semester"=>$semester,
				"userid"=>"",
			) ;
			$db = M("workload") ;
			$success = 0 ;
			$faild = 0 ;
			foreach ($teacher as $value) {
				$data["userid"] = $value["userid"] ;
				if( false !== $db->create($data) && false !== $db->add() ){
					$success++ ;
				}else{
					$faild++ ;
				}
			}
			$this->assign("show","成功".$success."次，失败".$faild."次") ;
		}
		$this->display();
	}
}

?>
