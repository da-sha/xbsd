<?php

/* ------------------------------------------------------------
 * 日期		：2012-10-6
 * -----------------------------------------------------------
 * 创建人	：大傻 lilei.zh@qq.com
 * -----------------------------------------------------------
 * 文件说明	; AjaxAction.class.php UTF-8
 * -----------------------------------------------------------
 */

class AjaxAction extends Action{
	public function getteacher()
	{
		$orgid = $this->_get("orgid") ;
		$db = M("teacher") ;
		if( $orgid )
		{
			$where = "orgid={$orgid}" ;
			$data = $db->field("userid as id,get_user_code(userid) as code,name")
					->where($where)
					->select() ;
		}
		else {
			$data = $db->field("userid as id,get_user_code(userid) as code,name")->select() ;
		}
		if($data)
		{
			$this->ajaxReturn($data, "succeed", 1);
		}
		else
		{
			$this->ajaxReturn( NULL , $db->getDbError(), 0);
		}
	}

	public function getclassStudent()
	{
		$classid = $this->_get("classid") ;
		$db = M("student") ;
		if( $classid )
		{
			$where = "classid={$classid}" ;
			$data = $db->where($where)
					->field("userid as id,get_user_code(userid) as code,name")
					->select() ;
		}else {
			$this->ajaxReturn( NULL , $db->getDbError(), 0);
		}
		if($data)
		{
			$this->ajaxReturn($data, "succeed", 1);
		}else
		{
			$this->ajaxReturn( NULL , $db->getDbError(), 0);
		}
	}
	public function getteacherinfo()
	{
		$userid = $this->_get('userid') ;
		if($userid)
		{
			$db = M("teacher") ;
			$data = $db->field("userid,
				name,
				gender,
				get_field_dict_name( 'teacher','gender',gender) as gender_name,
				nationality,
				get_field_dict_name( 'teacher','nationality',nationality) as nationality_name,
				Politics_status,
				orgid,
				get_org_name(orgid) as orgid_name,
				idno,
				job_title,
				get_field_dict_name( 'teacher','job_title',job_title) as job_title_name,
				degree,
				get_field_dict_name( 'teacher','degree',degree) as degree_name,
				research,
				resume,
				major,
				school,
				telphone1,
				telphone2,
				email1,
				email2,
				qq,
				address")
					->find( $userid ) ;
			if($data)
			{
				$this->ajaxReturn($data, "succeed", 1);
			}
			else
			{
				$this->ajaxReturn( NULL , $db->getDbError(), 0);
			}
		}
		else
		{
			$this->ajaxReturn( NULL , "错误的参数", 0);
		}
	}

	public function getcourse()
	{
		$db = M("course") ;
		$data = $db->field("courseid as id,courseid as code,name")
				->select() ;
		if($data)
		{
			$this->ajaxReturn($data, "succeed", 1);
		}
		else
		{
			$this->ajaxReturn( NULL , $db->getDbError(), 0);
		}
	}
	public function getstudent()
	{
		$db = M("student") ;
		$data = $db->field("userid as id,userid as code,name")
				->select() ;
		if($data)
		{
			$this->ajaxReturn($data, "succeed", 1);
		}
		else
		{
			$this->ajaxReturn( NULL , $db->getDbError(), 0);
		}
	}
	//根据一个学生层次找到这个层次的所有专业
	public function getMajor(){
		$level = $this->_get("level");
		$db = new MajorModel();
		$major = $db->where("level={$level}")->field('majorid as id, name')->select();
		if( $major )
		{
			$this->ajaxReturn($major, "查询成功", 1 ) ;
		}
		else {
			$this->ajaxReturn( NULL, "对不起，没有专业！", 0 ) ;
		}
	}
	//找到某一个专业招收学生的学年
	public function getyear(){
		$majorid = $this->_get("majorid");
		$db = new ClassModel();
		$year = $db->where("majorid={$majorid}")
				->distinct(true)
				->field("register_date as year")
				->select();
		if( $year )
		{
			$this->ajaxReturn($year, "查询成功", 1 ) ;
		}
		else {
			$this->ajaxReturn( NULL, "对不起，没有这一级！", 0 ) ;
		}
	}
	//根据某一个专业和学年找到这个专业招收学生的班级
	public function getClass() {
		$majorid = $this->_get("majorid");
		$year = $this->_get("year");
		$db = new ClassModel();
		$class = $db->where("majorid={$majorid} and register_date={$year}")->field("classid as id, name")->select();
		if ($class){
			$this->ajaxReturn($class, "查询成功！", 1);
		} else {
			$this->ajaxReturn(0, "查询失败！", 0);
		}
	}

}

?>
