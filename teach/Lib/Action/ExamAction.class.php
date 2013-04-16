<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-25
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	; ExamAction.class.php UTF-8
 * -----------------------------------------------------------
 */

class ExamAction extends CommonAction {
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
	public function show() {
		$this->showconditoin() ;
		$year = $_GET['year'];
		$semester = $_GET['semester'];
		if( !$year && !$semester ){
			$this->assign('show', "请先选择学年和学期！");
		}else{
			$where = "year={$year} and semester={$semester}" ;
			$db = M("exam_room") ;
			$data = $db->field("roomid,addr")->where($where)->select() ;
			$this->assign("room_list", $data) ;
			$this->get_sys_config("default_room_limit", "default_room_limit") ;
			$this->get_field_dict("exam", "type", "type_list") ;
			$this->get_field_dict("exam", "time", "time_list") ;
		}
		$this->display();
	}
	public function examview(){
		$this->showconditoin() ;
		$db = M('teach_task, course, exam');
		$data = $db->where("teach_task.teach_year={$teachyear} and teach_task.teach_quarter={$teachquarter} 
							and teach_task.teachid=exam.teachid and teach_task.courseid=course.courseid")
					->distinct(true)
					->field('exam.examid, exam.date,exam.time, exam.roomid, course.name, exam.type, examiner1, examiner2')
					->select();
		if( $data ){
			$this->assign('exam_list', $data);
		}else{
			$this->assign('show', "暂无考试安排！");
		}
		$this->display() ;
	}

	public function exam_room(){
		$this->showconditoin() ;
		$arg = array("year" , "semester") ;
		$where = "roomid>0";
		foreach ($arg as $value) {
			if( $_GET[$value] )
			{
				$where = $where.' and '.$value."=".$_GET[$value] ;
			}
		}
		$db = M("exam_room") ;
		$count = $db->where($where)->count() ;
		if( $count > 0 )
		{
			import('ORG.Util.Page');
			$page = new Page($count, C('PAGESIZE'));
			$show = $page->show();
			$this->assign("show", $show);
			$data = $db->field("*,get_field_dict_name('exam_room','semester',semester) as semester")
					->where($where)->limit($page->firstRow.','.$page->listRows)->select() ;
			$this->assign("room_list", $data ) ;
		}else{
			$this->assign("show", "当前没有数据！");
		}
		$this->display() ;
	}
	public function insert_room(){
		$db = M("exam_room") ;
		if( false !== $db->create() && false !== $db->add() )
		{
			$this->success("添加成功！") ;
		}else{
			$this->error("对不起，添加失败！") ;
		}
	}
	public function delete_room(){
		$id = $_GET["roomid"] ;
		$db = M("exam_room") ;
		if( false !== $db->delete($id) )
		{
			$this->success("删除成功！") ;
		}else{
			$this->error("删除失败！") ;
		}
	}
	public function examcourse(){
		$teachyear = $_GET['teachyear'];
		$teachquarter = $_GET['teachquarter'];
		if( !$teachyear && !$teachquarter ){
			$this->error('参数错误');
		}
		$db = M('teach_task, course');
		$data = $db->where("teach_task.teach_year={$teachyear} and teach_task.teach_quarter={$teachquarter} and teach_task.courseid=course.courseid")
					->distinct(true)
					->field('teach_task.courseid as id, course.name ')
					->select();
		
		$this->assign('course_list', $data);
		$this->display();
	}
	public function allcourse(){
		$teachyear = $_POST['year'];
		$teachquarter = $_POST['semester'];
		$courseid = $_POST['courseid'];
		if( !$teachyear && !$teachquarter && !$courseid ){
			$this->ajaxReturn( NULL , "请先选择课程！", 0);
		}
		$db = M('teach_task, teach_class, class');
		$data = $db->where("teach_task.teach_year={$teachyear} and teach_task.teach_quarter={$teachquarter} and 
			teach_task.courseid={$courseid} and teach_task.teachid=teach_class.teachid and teach_class.classid=class.classid")
					->distinct(true)
					->field('teach_task.teachid,courseid,
						get_course_name(courseid) as coursename,
						class.name as classname, 
						get_student_count(teach_task.teachid) as num')
					->select();
		if( $data ){
			$this->ajaxReturn($data, "succeed", 1);
		}else{
			$this->ajaxReturn( NULL , "抱歉，没有查询到数据！", 0);
		}
		$this->display();
	}
	public function arrange_exam(){
		$data = array(
			"type"=>$_POST["type"],
			"date"=>$_POST["date"],
			"time"=>$_POST["time"],
			"year"=>$_POST["year"],
			"semester"=>$_POST["semester"],
			"coursename"=>"",
			"roomid"=>"",
			"num"=>"",
		) ;
		$db = new ExamModel() ;
		$success = 0 ;
		$fail = 0 ;
		foreach ($_POST["arrange"] as $value) {
			$data["coursename"] = $value["coursename"] ;
			$data["courseid"] = $value["courseid"] ;
			$data["roomid"] = $value["roomid"] ;
			$data["num"] = $value["num"] ;
			if( $db->create($data) && $db->add() )
			{
				$success++ ;
			}else {
				$fail++ ;
			}
			//echo $db->getLastSql()."<br/>" ;
		}
		$this->ajaxReturn( NULL, "安排考试成功{$success}次，失败{$fail}次！", 1) ;
	}
	public function arrange_teacher(){
		$year = $_GET['year'];
		$semester = $_GET['semester'];
		if( !$year && !$semester ){
			$this->assign('show', "请先选择学年和学期！");
		}else{
			$where = "year={$year} and semester={$semester}" ;
			$examwhere = $where." and date='{$_GET['date']}' and time={$_GET['time']} and flag='0'" ;
			$examdb = M("exam") ;
			$courseid = $examdb->distinct(true)->field("courseid")->where($examwhere)->select() ;
			$teachtaskdb =M("teach_task") ; 
			foreach ($courseid as $key=>$value) {
				$data[$key] = $teachtaskdb->field("GROUP_CONCAT(userid) as user,courseid")
						->where("courseid={$value['courseid']}")->find() ;
			}
			$this->assign("user_list", $data ) ;
			$examinerdb = M("examiner") ;
			$data = $examinerdb->query("SELECT `teacherid`,get_examiner_count({$year},{$semester},teacherid) as workcount,get_teacher_name(teacherid) as teachername FROM `examiner` WHERE ".$where) ;
			$this->assign("examiner_list", $data) ;
			$data = $examdb->field("*,get_field_dict_name('exam' , 'time' , time) as timename,
				get_field_dict_name('exam' , 'type' , type) as typename,get_room_name(roomid) as roomname")
					->where($examwhere)
					->select() ;
			$this->assign("exam_list", $data) ;
		}
		$this->display();
	}
	public function examiner(){
		$this->showconditoin() ;
		$year = $_GET['year'];
		$semester = $_GET['semester'];
		if( !$year && !$semester ){
			$this->assign('show', "请先选择学年和学期！");
		}else{
			$db = new OrganizeModel();
			$org = $db->field("orgid as id, name")->select();
			$this->assign("org_list", $org);
			$where = "year={$year} and semester={$semester}" ;
			$db = M("examiner") ;
			$data = $db->field("id,teacherid,get_teacher_name(teacherid) as teachername")->where($where)->select() ;
			$this->assign("examiner_list", $data) ;
		}
		$this->display() ;
	}
	public function addexaminer(){
		$db = M("examiner") ;
		if( false !== $db->create() && false !== $db->add() ){
			$data["id"] = $db->getLastInsID() ;
			$this->ajaxReturn($data, "插入成功！", 1) ;
		}else{
			$this->ajaxReturn(NULL, "插入失败！", 0 ) ;
		}
	}
	public function delexaminer(){
		$db = M("examiner") ;
		if( false !== $db->delete( $_GET['id']) ){
			$this->ajaxReturn(NULL, "删除成功！", 1) ;
		}else{
			$this->ajaxReturn(NULL, "对不起，删除失败！", 0) ;
		}
	}
	public function index(){
		$this->showconditoin() ;
		$year = $_GET['year'];
		$semester = $_GET['semester'];
		if( !$year && !$semester ){
			$this->assign('show', "请先选择学年和学期！");
		}else{
			$where = "year={$year} and semester={$semester}" ;
			$db = M("exam") ;
			$datedata = $db->distinct(true)->field("date,time,get_field_dict_name('exam' , 'time' , time) as timename")->where( $where )->select() ;
			
			foreach ($datedata as $key => $value) {
				$tempwhere = $where." and date='{$value['date']}' and time={$value['time']}" ;
				$datedata[ $key ]["info"] = $db->field("coursename,num,flag,
					get_teacher_name_notip(examiner1) as examiner1,
					get_teacher_name_notip(examiner2) as examiner2,
					get_field_dict_name('exam' , 'type' , type) as typename,
					get_room_name(roomid) as roomname")
						->where( $tempwhere )
						->select() ;
				$datedata[ $key ]["count"] = $db->where( $tempwhere )->count() ;
			}
			$this->assign( "exam_list", $datedata ) ;
		}
		$this->display() ;
	}
	public function arrange_examiner(){
		$db = new ExamModel() ;
		$success = 0 ;
		$fail = 0 ;
		foreach ($_POST["data"] as $value) {
			$value["flag"] = 1 ;
			if( false !== $db->create($value) && false !== $db->save() ){
				$success++ ;
			}else{
				$fail++ ;
			}
		}
		$this->ajaxReturn(NULL, "安排监考成功{$success}个，失败{$fail}个！", 1) ;
	}
	public function delete_exam(){
		if($_GET["year"]&&$_GET["semester"]&&$_GET["date"]&&$_GET["time"]){
			$where = "year={$_GET["year"]} and semester={$_GET["semester"]} and date='{$_GET["date"]}' and time={$_GET["time"]}" ;
			$db = M("exam") ;
			if( false !== $db->where($where)->delete() ){
				$this->success("删除成功！") ;
			}else{
				$this->error("对不起，删除失败！") ;
			}
		}else{
			$this->error("对不起，参数错误！") ;
		}
	}
	public function myexaminer(){
		$this->showconditoin() ;
		$year = $_GET['year'];
		$semester = $_GET['semester'];
		if( !$year && !$semester ){
			$this->assign('show', "请先选择学年和学期！");
		}else{
			$db = M("exam") ;
			$userid = $this->get_user_id() ;
			$data = $db->field("*,get_field_dict_name('exam','type',type) as type,
				get_teacher_name(examiner1) as examiner1,
				get_teacher_name(examiner2) as examiner2,
				get_room_name(roomid) as roomname")
					->where("year={$year} and semester={$semester} and (examiner1={$userid} or examiner2={$userid})")->select() ;
			$this->assign("list", $data) ;
		}
		$this->show() ;
	}
}

?>
