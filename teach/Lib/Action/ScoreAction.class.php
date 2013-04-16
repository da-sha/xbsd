<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-24
 * -----------------------------------------------------------
 * 创建人	：大傻 lilei.zh@qq.com
 * -----------------------------------------------------------
 * 文件说明	; ScoreAction.class.php UTF-8
 * -----------------------------------------------------------
 */

class ScoreAction extends CourseAction{

	protected $db_score,$db_assessment,$db_student;

	public function _initialize(){
		$this->db_score = M("score");
		$this->db_assessment = M("assessment");
		$this->db_student = M("student");
		
		parent::_initialize();
	}

	public function index()
	{
		$db = M('teach_task') ;
		$data = $db->field("teachid as id,get_course_name(courseid) as name")->select() ;
		$this->assign( 'tea_list', $data) ;
		$db = M("assessment") ;
		$this->display() ;
	}
	
	public function main()
	{
		$assessid = $this->_get("assessid") ;
		$db = M("score") ;
		$where = "assessmentid={$assessid}" ;
		$data = $db->field("userid,get_student_name(userid) as userid_name,assessmentid,get_assess_name(assessmentid) as assessmentid_name,get_assess_type(assessmentid) as type,mark")
				->where($where)
				->select();
		$this->assign("assess_list", $data);
		$this->display() ;
	}
	/**
	 * This is method edit
	 * 
	 */	
	public function edit()
	{
		$teachid = $_GET["teachid"];
		$studentid = $_GET["studentid"];
		/*选出所有该课程的考核及其成绩*/
		$assessment = $this->getassessment($teachid);
		
		$db = new Model();
		$assessment_mark = $db->table("score,assessment")
			->where("assessment.teachid={$teachid} and
				score.userid={$studentid} and
				score.assessmentid=assessment.assessmentid")
			->field("assessment.assessmentid as assessmentid,
                assessment.name as name,
				score.mark as mark")
			->select();

		/*将选出的成绩进行整合*/
		foreach($assessment_mark as $assess_mark){
			$assessment[$assess_mark['assessmentid']]['name'] = $assess_mark["name"];
			$assessment[$assess_mark['assessmentid']]['mark'] = $assess_mark["mark"];
		}
		
		$this->assign("assessment_mark",$assessment);

		/**
		 * 将该学生在该课程的所有考核成绩全部取出
		 */
		$student_info = $this->db_student->where("userid={$studentid}")->find();
		$this->assign("student_info",$student_info);
		
		$this->display();
	}
	/**
	 * 
	 */
	public function update(){
		$data = $_POST;
		$userid = $data["studentid"];

		unset($data['studentid']);
		unset($data["__hash__"]);

		/*剩下的全部是考核*/
		/*note:__hash__验证在这里失效*/
		$values = array();
		$index = 0;
		foreach($data as $assessmentid=>$mark){
			$values[$index]['userid'] = $userid;
			$values[$index]['assessmentid'] = $assessmentid;
			$values[$index]['mark'] = $mark;
			$index++;
		}
		//log_data($values);
		$ret = $this->db_score->addAll($values,array(),true);
		if ($ret == 0) {
			$this->error($this->db_score->getDbError());
		}else{
			$this->success("数据更新成功");
		}
	}
	public function delete()
	{
		$assessid = $this->_get("assessid") ;
		$userid = $this->_get("userid") ;
		if( !$assessid || !$userid )
		{
			$this->error("错误的参数！") ;
			return ;
		}
		$db = M("score") ;
		$where = "userid={$userid} and assessmentid={$assessid}" ;
		if( false != $db->where($where)->delete() )
		{
			$this->success('删除成功!',"__URL__/main/assessid/{$assessid}");
		}
		else
		{
			$this->error( "删除失败".$db->getDbError() );
		}
	}
	public function add()
	{
		$assessid = $this->_get("assessid") ;
		if( $assessid )
		{
			$this->assign("assess", $assessid) ;
			$db = M("assessment") ;
			$teach = $db->find($assessid) ;
			if( !$teach )
			{
				$this->error("对不起，选课信息丢失！") ;
				return;
			}
			$db = M() ;
			$sql = "SELECT
				selectid,
				userid,
				get_student_name(userid) as userid_name,
				get_assess_name({$assessid}) as assess_name,
				get_assess_type({$assessid}) as type,
				teachid
				FROM select_course
				WHERE
				teachid={$teach['teachid']}
				AND
				userid NOT IN (SELECT userid FROM score where assessmentid={$assessid})" ;
			$data = $db->query($sql) ;
			$this->assign("flag" , "1") ;
			$this->assign("data_list", $data) ;
		}
		else
		{
			$this->assign("show", "请选择具体考核！") ;
		}
		$this->display() ;
	}
	public function insert()
	{
		$user = $_POST["userid"] ;
		$mark = $_POST["mark"] ;
		$len = count($user) ;
		if( $len != count($mark) )
		{
			$this->error("对不起，您提交的数据有误！") ;
			return;
		}
		$db = new ScoreModel() ;
		$data = array(
			"assessmentid"=>$this->_post("assessmentid") ,
			"userid"=>"",
			"mark"=>"",
		) ;
		for( $i = 0 ; $i < $len ; $i++ )
		{
			$data["userid"] = $user[$i] ;
			$data["mark"] = $mark[$i] ;
			if( !$db->create($data) )
			{
				$this->error("对不起,数据校验错误！".$db->getDbError() ) ;
			}
			if( false !== $db->add() )
			{
				$this->success("恭喜您登录学生成绩成功！", "__URL__/main/assessid/{$this->_post("assessmentid")}");
			}
			else {
				$this->error( "对不起，登录学生成绩失败！".$db->getDbError() ) ;
			}
		}
	}
	/**
	 * 显示成绩信息
	 */
	public function score(){
		$teachid = $_GET['teachid'];
		$course = $this->db_teach_task->where("teachid={$teachid}")->find();

		if ($course["userid"] != $this->get_user_id()) {
			$this->error("您没有带该项课程");
			return true;
		}

		/*选出所有该课程的考核*/
		$assessment = $this->getassessment($teachid);
		$this->assign("assessment",$assessment);

		$scores = array();

		/*将选该课的学生全部选出来*/

		$db = new Model();
		$students_data = $db->table("student,select_course")
            ->where("select_course.teachid={$teachid} and
            student.userid=select_course.userid")
            ->field("student.userid as userid,
                student.name as name")
            ->select();

		$students = array();
		
		foreach($students_data as $student){
            $students[$student['userid']] = $student['name'];
        }

        //dump($students);
		/*将每个学生在改课程的考核全部选出来*/
        foreach ($assessment as $assess){
            $where = "select_course.teachid={$teachid} and
                score.userid=select_course.userid and
                score.assessmentid={$assess['assessmentid']}
                ";
            //echo $where;
            $data = $db->table("select_course,score")
                ->where($where)
                ->field("score.userid,score.mark")
                ->select();
            //dump($data);
            foreach($data as $score){
                $scores[$score['userid']][$assess['assessmentid']] = $score['mark'];
            }
        }
		foreach($students as $userid=>$name){
            $scores[$userid]['username'] = $name;
        }
        //dump($scores);
        //log_data($scores);
        $this->assign("scores",$scores);
		if ($course["finish_status"] == C("TEACH_TASK_FINISH_STATUS_PAST")) {
			/*past*/
			$this->display("score_past");
		}else if ($course["finish_status"] == C("TEACH_TASK_FINISH_STATUS_NOW")) {
			/*now*/
			$this->display("score_now");
		}
	}
	/**
	 * 得到根据teachid得到考核
	 */	
	private function getassessment($teachid){
		if( $teachid == 0){
			return;
		}
		$where = "teachid={$teachid}" ;
		$data = $this->db_assessment->where($where)->order("seq")->select() ;
		$assessment = array();
		
		foreach($data as $item){
			$assessment[$item["assessmentid"]] = $item;
		}
		return $assessment;
	}
	/**
	* 显示成绩信息
	*/
	public function admin_score(){
		$teachid = $_GET['teachid'];

		/*选出所有该课程的考核*/
		$assessment = $this->getassessment($teachid);
		$this->assign("assessment",$assessment);

		$scores = array();

		/*将选该课的学生全部选出来*/

		$db = new Model();
		$students_data = $db->table("student,select_course")
			->where("select_course.teachid={$teachid} and
            student.userid=select_course.userid")
			->field("student.userid as userid,
                student.name as name")
			->select();

		$students = array();
		
		foreach($students_data as $student){
			$students[$student['userid']] = $student['name'];
		}

		//dump($students);
		/*将每个学生在改课程的考核全部选出来*/
		foreach ($assessment as $assess){
			$where = "select_course.teachid={$teachid} and
                score.userid=select_course.userid and
                score.assessmentid={$assess['assessmentid']}
                ";
			//echo $where;
			$data = $db->table("select_course,score")
				->where($where)
				->field("score.userid,score.mark")
				->select();
			//dump($data);
			foreach($data as $score){
				$scores[$score['userid']][$assess['assessmentid']] = $score['mark'];
			}
		}
		foreach($students as $userid=>$name){
			$scores[$userid]['username'] = $name;
		}
		//dump($scores);
		//log_data($scores);
		$this->assign("scores",$scores);
		$this->display("score_past");
	}
}

?>
