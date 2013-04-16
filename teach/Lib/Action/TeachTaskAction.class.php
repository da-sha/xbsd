<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-21 
 * -----------------------------------------------------------
 * 创建人	：大傻 lilei.zh@qq.com
 * -----------------------------------------------------------
 * 文件说明	; TeachTaskAction.class.php UTF-8
 * -----------------------------------------------------------
 */

class TeachTaskAction extends CommonAction{
	public function index()
	{
		$db = M('class') ;
		$data = $db->field("classid as id,name")->select() ;
		if( false !== $data )
		{
			$this->assign("cla_list", $data ) ;
		}
		$classid = $this->_get("classid") ;
		if( $classid )
		{
			$db = M("teach_task") ;
			$where = "classid={$classid}";
			$count = $db->where($where)->count();
			if( $count === '0' )
			{
				$this->assign("show", "对不起，没有数据！");
				$this->display() ;
				return;
			}
			import('ORG.Util.Page');
			$page = new Page($count, C('PAGESIZE'));
			$show = $page->show();
			$this->assign("show", $show);
			$data = $db->where($where)
					->field("
						teachid,
						get_class_name(classid) as classid,
						get_teacher_name(userid) as userid,
						get_course_name(courseid) as courseid,
						teach_year,
						get_field_dict_name('teach_task','teach_semester',teach_semester) as teach_semester,
						week
						")
					->limit($page->firstRow . ',' . $page->listRows)
					->select();
			$this->assign( "teatask_list" , $data ) ;
		} else {
			$this->assign("show", "请首先选择班级！");
		}
		$this->display() ;
	}
	public function add()
	{
		$classid = $this->_get("classid");
		if( $classid )
		{
			$db = M("class") ;
			$data = $db->field("classid,name,majorid")
				->find( $classid ) ;
			if( $data )
			{
				$this->assign( "class", $data ) ;
				$db = M('course') ;
				$where = "majorid={$data['majorid']}" ;
				$course_data = $db->field("courseid as id,name")->where($where)->select() ;
				$this->assign("cou_list", $course_data) ;
			}
			else
			{
				$this->error("系统错误，班级信息丢失！") ;
				return ;
			}
		}
		else
		{
			$this->error("请选择班级！") ;
			return;
		}
		$this->display() ;
	}
	public function insert()
	{
		$db = new TeachTaskModel() ;
		if ($db->create()) {
			if (false !== $db->add()) {
				$this->success('恭喜您添加课程成功！', "__URL__/add/classid/{$db->classid}");
			} else {
				$this->error('操作失败：' . $db->getDbError());
			}
		} else {
			$this->error('操作失败：数据验证( ' . $db->getError() . ' )');
		}
	}
	public function edit()
	{
		
		$teachid = $this->_get('teachid') ;
		if( $teachid )
		{
			$db = M('teach_task') ;
			$data = $db->find( $teachid ) ;
			if( $data )
			{
				$this->assign( "t_task", $data ) ;
				$db = M("class") ;
				$data = $db->field("classid,name,major")->find( $data['classid'] ) ;
				if( $data )
				{
					$this->assign( "class", $data ) ;
					$db = M('course') ;
					$where = "majorid={$data['major']}" ;
					$course_data = $db->field("courseid as id,name")->where($where)->select() ;
					$this->assign("cou_list", $course_data) ;
				}
			}
		}
		$this->display() ;
	}
	public function update()
	{
		$db = new TeachTaskModel() ;
		$data = $db->create() ;
		if( $data )
		{
			if( false !== $db->save() )
			{
				$this->success("恭喜您编辑成功！", "__URL__/index/classid/{$data['classid']}");
			}
			else
			{
				$this->error("对不起，编辑失败！".$db->getDbError() , "__URL__/index/classid/{$data['classid']}") ;
			}
		}
		else {
			$this->error('操作失败：数据验证('.$db->getError().')', "__URL__/index/classid/{$data['classid']}");
		}
	}
	public function delete()
	{
		$db = M('teach_task') ;
		$data = $db->field( "classid" )->find( $this->_get('teachid') ) ;
		if( null == $data ){
			$this->error("删除失败，你要删除的项不存在" . $db->getDbError() );
		}
		if (false !== $db->where("teachid={$this->_get('teachid')}")->delete()) {
			$this->success('删除成功', "__URL__/index/classid/{$data['classid']}");
		} else {
			$this->error("删除失败" . $db->getDbError(), "__URL__/index/classid/{$data['classid']}");
		}
	}
}

?>
