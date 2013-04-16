<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-21 
 * -----------------------------------------------------------
 * 创建人	：大傻 lilei.zh@qq.com
 * -----------------------------------------------------------
 * 文件说明	; TeachTimeAction.class.php UTF-8
 * -----------------------------------------------------------
 */

class TeachTimeAction extends CommonAction{
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
			$db = M('teach_time,teach_task') ;
			$where = "teach_time.teachid=teach_task.teachid and teach_task.classid={$classid}" ;
			$count = $db->where($where)->count();
			import('ORG.Util.Page');
			$page = new Page($count, C('PAGESIZE'));
			$show = $page->show();
			$this->assign("show", $show);
			$data = $db->where($where)
					->field("
						teach_task.courseid,
						get_course_name(teach_task.courseid) as courseid_name,
						teach_time.teachid, 
						get_field_dict_name('teach_time','day',teach_time.day) as day,
						get_field_dict_name('teach_time','seq',teach_time.seq) as seq, 
						get_field_dict_name('teach_time','type',teach_time.type) as type,
						teach_time.place
						")
					->limit($page->firstRow . ',' . $page->listRows)
					->select();
			$this->assign( "teatime_list" , $data ) ;
		} else {
			$this->assign("show", "请首先选择班级！");
		}
		$this->display() ;
	}
	public function add()
	{
		
		$classid = $this->_get("classid") ;
		if( $classid )
		{
			$this->assign( "classid" ,$classid) ;
			$db = M("teach_task") ;
			$data = $db->field("
					teachid as id,
					get_course_name(courseid) as name"
					)
					->where("classid={$classid}")
					->select() ;
			if($data)
			{
				$this->assign("teach_list" , $data ) ;
			}
			$this->get_field_dict("teach_time", "day", "day_list") ;
			$this->get_field_dict("teach_time", "seq", "seq_list") ;
			$this->get_field_dict("teach_time", "type", "type_list") ;
		}
		$this->display() ;
	}
	public function insert()
	{
		$db = new TeachTimeModel() ;
		if ( $data = $db->create() ) {
			if (false !== $db->add()) {
				$this->success('恭喜您添加课程成功！', "__URL__/add/classid/{$this->_get('classid')}");
			} else {
				$this->error('操作失败：' . $db->getDbError(), "__URL__/add/classid/{$this->_get('classid')}");
			}
		} else {
			$this->error('操作失败：数据验证( ' . $db->getError() . ' )');
		}
	}
	public function delete()
	{
		$db = M('teach_task') ;
		$data = $db->field( "classid" )->find( $this->_get('teachid') ) ;
		if( null == $data ){
			$this->error("删除失败，你要删除的项不存在" . $db->getDbError() );
			return ;
		}
		$db = M('teach_time') ;
		$where = "teachid={$this->_get('teachid')} and
				day={$this->_get('day')} and
				seq={$this->_get('seq')}" ;
		if (false !== $db->where($where)->delete()) {
			$this->success('删除成功', "__URL__/index/classid/{$data['classid']}");
		} else {
			$this->error("删除失败" . $db->getDbError(), "__URL__/index/classid/{$data['classid']}");
		}
	}
	public function edit()
	{
		
		$db = M("teach_task") ;
		$classid = $db->field("classid")->find($this->_get('teachid')) ;
		$classid = $classid['classid'] ;
		if( $classid )
		{
			$this->assign( "classid" ,$classid) ;
			$db = M("teach_task") ;
			$data = $db->field("
					teachid as id,
					get_course_name(courseid) as name"
					)
					->where("classid={$classid}")
					->select() ;
			if($data)
			{
				$this->assign("teach_list" , $data ) ;
			}
			$this->get_field_dict("teach_time", "day", "day_list") ;
			$this->get_field_dict("teach_time", "seq", "seq_list") ;
			$this->get_field_dict("teach_time", "type", "type_list") ;
		}
		
		$db = M("teach_time") ;
		$where = "teachid={$this->_get('teachid')} and
				day={$this->_get('day')} and
				seq={$this->_get('seq')}" ;
		$data = $db->where($where)->find() ;
		
		if( $data )
		{
			$this->assign( "time" , $data) ;
		}
		$this->display() ;
	}
	public function update()
	{
		$db = M("teach_task") ;
		$classid = $db->field("classid")->find($this->_get('teachid')) ;
		$classid = $classid['classid'] ;
		
		$db = new TeachTimeModel() ;
		$data = $db->create() ;
		if( $data )
		{
			$where = "teachid={$this->_post('old_teachid')} and 
				day={$this->_post('old_day')} and 
				seq={$this->_post('old_seq')}" ;
			if( false !== $db->where( $where )->save() )
			{
				$this->success("恭喜您编辑成功！", "__URL__/index/classid/{$classid}");
			}
			else
			{
				$this->error("对不起，编辑失败！".$db->getDbError() ) ;
			}
		}
		else {
			$this->error('操作失败：数据验证('.$db->getError().')');
		}
	}
}

?>
