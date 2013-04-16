<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-16 
 * -----------------------------------------------------------
 * 创建人	：大傻 lilei.zh@qq.com
 * -----------------------------------------------------------
 * 文件说明	; OrgdutyAction.class.php UTF-8
 * -----------------------------------------------------------
 */

class OrgdutyAction extends CommonAction{
	public function index()
	{
		$db = M( "organize" ) ;
		$org_data = $db->field("orgid,name")->select() ;
		$this->assign( "org_list" , $org_data) ;
		$orgid = $this->_post("orgid") ;
		if( NULL === $orgid )
		{
			$orgid = $this->_get("orgid") ;
		}
		if( $orgid )
		{
			$db = M("orgduty") ;
			$where = "orgid={$orgid}";
			$count = $db->where($where)->count();
			import('ORG.Util.Page');
			$page = new Page($count, C('PAGESIZE'));
			$show = $page->show();
			$this->assign("show", $show);
			$data = $db->where($where)
					->field(
							"userid,
							get_teacher_name(userid) as user_name,
							orgid,
							get_org_name(orgid) as org_name,
							name,
							get_field_dict_name( 'orgduty' , 'name' , name ) as duty_name,
							get_field_dict_name( 'orgduty' , 'level' , level ) as level,
							office"
							)
					->limit($page->firstRow . ',' . $page->listRows)
					->select() ;
			$this->assign( "orgduty_list" , $data ) ;
		}
		else
		{
			$this->assign( "show" , "请选择机构！" ) ;
		}
		$this->display() ;
	}
	public function add()
	{
		$db = M( "organize" ) ;
		$org_data = $db->field("orgid as id,name")->select() ;
		$this->assign( "org_list" , $org_data) ;
		$this->get_field_dict( 'orgduty', 'name', 'name_list');
		$this->get_field_dict( 'orgduty', 'level', 'lev_list');
		$this->display() ;
	}
	public function edit()
	{
		
		if( null === $this->_get('userid') ||
			null === $this->_get('orgid') ||
			null === $this->_get('name') )
		{
			$this->error("对不起，参数错误！") ;
			return ;
		}
		$db = M("orgduty") ;
		$where = "userid ={$this->_get('userid')} and orgid ={$this->_get('orgid')} and name ={$this->_get('name')}" ;
		$data = $db->field("userid,
							get_teacher_name(userid) as user_name,
							orgid,
							name,
							level,
							office")
							->where( $where )
							->find() ;
		if( null === $data )
		{
			$this->error( "对不起，该资料不存在！" ) ;
			return;
		}
		$this->assign("duty_info" , $data ) ;
		$db = M( "organize" ) ;
		$org_data = $db->field("orgid as id,name")->select() ;
		$this->assign( "org_list" , $org_data) ;
		$this->get_field_dict( 'orgduty', 'name', 'name_list');
		$this->get_field_dict( 'orgduty', 'level', 'lev_list');
		$this->display() ;
	}

	public function insert()
	{
		$db = new OrgdutyModel() ;
		$data = $db->create() ;
		if( $data )
		{
			if( false !== $db->add() )
			{
				$this->success( "添加教师职务成功" , "__URL__/add");
			}
			else 
			{
				$this->error( "添加教师职务失败！".$db->getDbError() );
			}
		}
		else
		{
			$this->error( "添加教师职务失败！数据验证失败！".$db->getDbError() );
		}
	}
	public function delete()
	{
		$where = "userid={$this->_get('userid')} and
			orgid={$this->_get('orgid')} and
			name={$this->_get('name')}" ;
		$db = M( "orgduty" ) ;
		if( false !== $db->where($where)->delete() )
		{
			$this->success("恭喜你删除成功","__URL__/index/orgid/{$this->_get('orgid')}") ;
		}
		else
		{
			$this->error("对不起，删除失败！".$db->getDbError()) ;
		}
	}

	public function save()
	{
		$db = new OrgdutyModel() ;
		$data = $db->create() ;
		if( $data )
		{
			if( false !== $db->save() )
			{
				$this->success( "编辑教师职务成功" , "__URL__/index/orgid/{$data['orgid']}");
			}
			else 
			{
				$this->error( "编辑教师职务失败！".$db->getDbError() );
			}
		}
		else
		{
			$this->error( "编辑教师职务失败！数据验证失败！".$db->getDbError() );
		}
	}
}

?>
