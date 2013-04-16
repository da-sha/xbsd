<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-28
 * -----------------------------------------------------------
 * 创建人	：大傻 lilei.zh@qq.com
 * -----------------------------------------------------------
 * 文件说明	; tTeacherAction.class.php UTF-8
 * -----------------------------------------------------------
 */

class tTeacherAction extends CommonAction{
	public function pwd()
	{
		$this->display();
	}

	public function info()
	{
		$db = new TeacherModel() ;
		$data = $db->field("*,get_field_dict_name('teacher','gender',gender) as gender_name,
			get_field_dict_name('teacher','nationality',nationality) as nationality_name,
			get_org_name(orgid) as orgid_name,
			get_field_dict_name('teacher','job_title',job_title) as job_title_name,
			get_field_dict_name('teacher','Politics_status',Politics_status) as Politics_status_name,
			get_field_dict_name('teacher','degree',degree) as degree_name")
				->find( $db->getUser() ) ;
		if( $data === false )
		{
			$this->error("对不起，没有找到该用户资料，请重新登录！", "myurl") ;
			return ;
		}
		$this->assign("teacher", $data ) ;
		$this->display() ;
	}
	public function edit() {
		$db = new TeacherModel() ;
		$data = $db->find( $db->getUser() ) ;
		if( $data === false )
		{
			$this->error("对不起，没有找到该用户资料，请重新登录！", "myurl") ;
		}
		$this->get_field_dict('teacher', 'gender', 't_gender');
		$this->get_field_dict('teacher', 'job_title', 'job_list');
		$this->get_field_dict('teacher', 'nationality', 'nat_list');
		$this->get_field_dict('teacher', 'degree', 'deg_list');
		$this->get_field_dict('teacher', 'Politics_status', 'pol_list');
		$comdb = M("organize") ;
		$data['org'] = $comdb->field("name")->find($data['org']) ;
		$data['org'] = $data['org']['name'] ;
		$this->assign("teacher", $data ) ;
		$this->display() ;
	}

	public function updata()
	{
		$db = new TeacherModel() ;
		$_POST['userid'] = $db->getUser() ;
		$data = $db->create() ;
		if( $db->userid == false )
		{
			$this->error("您没有登录或者登录过期，请重新登录！") ;
			return ;
		}
		if( false !== $data && false!==$db->save() )
		{
			$this->success( "修改成功！" , "__URL__/info") ;
        }else{
			$this->error( "修改失败！" ) ;
		}
	}
	public function verify()
	{
		import("ORG.Util.Image");
		Image::buildImageVerify();
	}
	public function changepwd()
	{
		$verify = $this->_post( 'verify' ) ;
		if( $_SESSION['verify'] != md5( $verify ) )
		{
			$this->error("对不起，验证码错误！") ;
			return ;
		}
		$pwd = $this->_post("password") ;
		if( $pwd != $this->_post("repassword") )
		{
			$this->error("对不起，两次密码不一致！") ;
			return ;
		}
		$db = M('login') ;
		$data = $db->field("user_psd")->find($this->get_user_id());
		$old = $this->_post("oldpassword") ;
		if( md5($old) !== $data['user_psd'] )
		{
			$this->error("对不起，旧密码输入错误！") ;
			return ;
		}
		$date = array(
			"user_id" => $this->get_user_id() ,
			"user_psd" => md5($pwd) ,
		) ;
		$db = new LoginModel();
		$data = $db->create($date);
		if( false !== $data )
		{
			if( false !== $db->save() )
			{
				$this->success( "恭喜您，修改密码成功！", "__URL__/pwd" ) ;
			}
			else {
				$this->error( "对不起，修改密码失败！".$db->getDbError() ) ;
			}
		}
		else {
			$this->error( "对不起，修改密码失败！数据校验错误!".$db->getDbError() ) ;
		}
	}
	public function teachplan(){
		$teachid = $this->_get("teachid") ;
		if( !$teachid )
		{
			$this->error("对不起，错误的参数") ;
			return ;
		}
		$db = M("teach_plan") ;
		$where = "teachid={$teachid}" ;
		$data = $db->field("teachid,seq,plan_title")->where($where)->order("seq")->select() ;
		if($data)
		{
			$this->assign("teachp_list", $data) ;
		}
		else{
			$this->assign("show", "对不起，当前课程没有教学计划！") ;
		}

		$this->display() ;
	}
	public function teachplaninfo(){
		$teachid = $this->_get("teachid") ;
		$seq = $this->_get("seq") ;
		if(  !$teachid || !$seq )
		{
			$this->error("对不起，错误的参数") ;
			return ;
		}
		$db = M("teach_plan") ;
		$where = "teachid={$teachid} and seq={$seq}" ;
		$data = $db->field("get_teach_name(teachid) as name,teachid,seq,plan_title,plan_time,plan_content,homework,homework_deadline")->where($where)->find() ;
		if($data)
		{
			$this->assign("plan", $data) ;
		}
		$this->display();
	}

	public function plan(){
		$teachid = $this->_get("teachid") ;
		if( !$teachid )
		{
			$this->error("对不起，错误的参数") ;
			return ;
		}
		else{
			$db = new TeachTaskModel();
			$data = $db->where("teachid={$teachid}")
					->field("teach_year, teach_semester,textbook, publisher,
							publish_date, publish_num, author, reference, creative")
					->find();
			$this->assign("data",$data);
			$db = new Model();
			$teacher = $db->table('teach_task, teacher')
					->where("teach_task.teachid={$teachid} and teach_task.userid=teacher.userid")
					->field("get_teacher_name(teacher.userid) as teacher,
							get_field_dict_name('teacher','job_title',teacher.job_title) as job_title")
					->find();
			$this->assign("teacher", $teacher);
		}

		$this->display() ;
	}

	//给教师展示通知公告列表
	public function notice() {
		$notice = new NoticeModel();
		$count = $notice->count();
		import('ORG.Util.Page');
		$page = new Page($count, C('PAGESIZE'));
		$show = $page->show();
		$this->assign("show", $show);
		$data = $notice->where($where)
					->field("noticeid,
							get_org_name(orgid) as orgid,
							source,
							get_field_dict_name('notice','type',type) as type,
							title,
							content,
							get_field_dict_name('notice','importance',importance) as importance,
							update_date")
					->limit($page->firstRow . ',' . $page->listRows)
					->select();
		if($data){
			$this->assign('list', $data);
		}else{
			$this->assign('show', "暂无公告信息！");
		}
		$this->display();
	}

	//显示某一个通知公告的详细信息
	public function noticeinfo(){
		$noticeid = $this->_get("noticeid");
		$db = new NoticeModel();
		$data = $db->where("noticeid={$noticeid}")
					->field("noticeid,
							get_org_name(orgid) as orgid,
							source,
							title,
							content,
							update_date")
					->find();
		$this->assign("notice", $data);
		$this->display();
	}
}

?>
