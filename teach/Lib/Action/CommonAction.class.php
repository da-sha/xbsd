<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-7
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	; CommonAction.class.php UTF-8
 * -----------------------------------------------------------
 */

abstract class CommonAction extends Action {
	protected $menudata,$role_map,$cache_model;
	protected  $userid,$groupid = 0;

	function _initialize()
	{
		$this->menudata = F('Menu');
        //dump($this->menudata);
        $this->groupid = $_SESSION['groupid'];

        if ($this->groupid == 0) {
        	$this->groupid = C('GUEST_GROUPID');
        	session('groupid',$this->groupid);
        }

		$this->cache_model=array('Menu','Role');
		$this->menudata = F('Menu');
		
		/*没有被缓存*/
		if(empty($this->menudata) == true){
			foreach($this->cache_model as $r){
				savecache($r);
			}
		}
		$role = F('Role');
		/*用户角色*/

		foreach ($role as $item) {
			$this->role_map[$item['id']] = $item;
		}
		$this->assign('module_name',MODULE_NAME);
		$this->assign('action_name',ACTION_NAME);
        /*
		dump($_SESSION);
		echo "<script>";
		echo "alert('hello')";
		echo "</script>";
		*/
        $this->userid = $_SESSION[C('USER_AUTH_KEY')];

		// 用户权限检查
		/*
		if (C ( 'USER_AUTH_ON' ) && !in_array(MODULE_NAME,explode(',',C('NOT_AUTH_MODULE')))) {
			import ( 'ORG.Util.RBAC' );

			if (! RBAC::AccessDecision ('teach')) {
				//检查认证识别号

				if (! $_SESSION [C ( 'USER_AUTH_KEY' )]) {
					//跳转到认证网关
					redirect ( PHP_FILE . C ( 'USER_AUTH_GATEWAY' ) );
				}
				// 没有权限 抛出错误
				if (C ( 'RBAC_ERROR_PAGE' )) {
					// 定义权限错误页面
					redirect ( C ( 'RBAC_ERROR_PAGE' ) );
				} else {
					if (C ( 'GUEST_AUTH_ON' )) {
						$this->assign ( 'jumpUrl', PHP_FILE . C ( 'USER_AUTH_GATEWAY' ) );
					}
					// 提示错误信息
					$this->error ( L ( '_VALID_ACCESS_' ) );
				}
			}
		}
		*/

		$menuid = intval($_GET['menuid']);

		if(empty($menuid)){
			$menuid = cookie('menuid');
		}
		if(!empty($menuid)){
			$nav = $this->getnav($menuid,1);
			if(nav)$this->assign('nav', $nav);
		}

		import("@.ORG.Form");
		$this->assign('Form', new Form());
		$this->use_default_nav(1);
	}
	/**
	 * 判断是否复用菜单
	 * 该函数务必在_initialize之后调用，也就是冲涮掉默认的设置
	 *
	 */	
	protected function use_default_nav($b_use) {
		/*默认使用上级菜单做导航*/
		if ($b_use == true) {
			$this->assign("default_nav",1);
		}else{
			$this->assign("default_nav",0);
		}
	}
	/*
	 * $table_name:表名
	 * $field_name:字段名
	 * $tpl_controlid:界面显示控件ID号
	 */
	public function get_field_dict($table_name, $field_name, $tpl_controlid) {
		$model = new DataDictModel();
		$data = $model->field('fd_value as id,fd_mean as name')->where("tb_name='{$table_name}' and fd_name='{$field_name}'")->order('seq')->select();
		$this->assign($tpl_controlid, $data);
	}
	public function get_field_dict_data($table_name, $field_name) {
		$model = new DataDictModel();
		$data = $model->field('fd_value as id,fd_mean as name')->where("tb_name='{$table_name}' and fd_name='{$field_name}'")->order('seq')->select();
		return $data;
	}
	protected function get_tb_comment( $tb_name )
	{
		$db = new Model();
		$data = $db->table('information_schema.TABLES')
				->where("TABLE_SCHEMA='tsms' and TABLE_NAME='{$tb_name}'")
				->field('TABLE_COMMENT as comment')
				->find();
		return $data["comment"];
	}
	protected function get_fd_comment( $tb_name , $fd_name )
	{
		$db = M();
		$data = $db->table('information_schema.COLUMNS')
				->where("TABLE_SCHEMA='{$this->get_db_name()}' and TABLE_NAME='{$tb_name}' and COLUMN_NAME='{$fd_name}'")
				->field('COLUMN_COMMENT as comment')
				->find();
		return $data["comment"];
	}
	protected function sys_config( $varname )
	{
		$db = M('config') ;
		$data = $db->where("varname='{$varname}'")->find();
		return $data ;
	}
	protected function sys_config_value( $varname )
	{
		$db = M('config') ;
		$data = $db->where("varname='{$varname}'")->find();
		return $data['value'] ;
	}
	protected function get_sys_config( $varname , $varid )
	{
		$db = M('config') ;
		$data = $db->where("varname='{$varname}'")->find();
		$this->assign($varid, $data['value']) ;
	}
	protected function get_db_name()
	{
		return C("DB_NAME") ;
	}

	protected function get_user_id()
	{
		return session( C('USER_AUTH_KEY') ) ;
	}
	protected function get_user_code()
	{
		return session('user_code') ;
	}
	/*
	 *得到局部导航菜单
	 */
	public function getnav($menuid,$isnav=0){
		if($menuid){
			$bnav = $this->menudata[$menuid];

			if(empty($bnav['action'])){
				$bnav['action'] ='index';
			}
			$array = array('menuid'=> $bnav['id']);
			parse_str($bnav['data'],$c);
			$bnav['data'] = $c + $array;
		}
		$nav = $this->getChildMenu($menuid,$isnav);

		$navdata['bnav']=$bnav;
		$navdata['nav']=$nav;
		return $navdata;
	}
	/**
	 * 根据父菜单编号得到子菜单
	 * @param unknown_type $menuid
	 */
	public function getChildMenu($menuid,$isnav = 0){
		$nav = array();
		if($this->menudata){
			$accessList = $_SESSION['_ACCESS_LIST'];
			foreach($this->menudata as $key=>$module) {
				if($module['parentid'] != $menuid || $module['status']==0 ||
					($this->isRoleBelongTo($module['groupid'],$this->getGroupId()) == false && $module['groupid'] != C('GUEST_GROUPID'))){
					continue;
				}
				if(isset($accessList[strtoupper('Teach')][strtoupper($module['model'])]) ||
						$_SESSION[C('ADMIN_AUTH_KEY')]) {
					if(empty($module['action'])){
						$module['action']='index';
					}
					//检测动作权限
					if(isset($accessList[strtoupper('Teach')][strtoupper($module['model'])][strtoupper($module['action'])]) ||
							$_SESSION[C('ADMIN_AUTH_KEY')]){
						$nav[$key]  = $module;
						if($isnav){
							$array=array('menuid'=> $nav[$key]['parentid']);
							cookie('menuid',$nav[$key]['parentid']);
							//$_SESSION['menuid'] = $nav[$key]['parentid'];
						}else{
							 $array=array('menuid'=> $nav[$key]['id']);
						}
						if(empty($menuid) && empty($isnav)){
							$array=array();
						}
						$c=array();
						parse_str($nav[$key]['data'],$c);
						$nav[$key]['data'] = $c + $array;
					}
				}
			}
		}

		return $nav;
	}
	/**
     * 默认操作
     */
	public function index() {
        $name = MODULE_NAME;
		$model = M ($name);
        $list = $model->where($_REQUEST['where'])->select();
        $this->assign('list', $list);
        $this->display();
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
			if(in_array($name,$this->cache_model)){
				savecache($name);
			}
			$jumpUrl = $_POST['forward'] ? $_POST['forward'] : U(MODULE_NAME.'/index');
			$this->assign ( 'jumpUrl',$jumpUrl );
			$this->success (L('add_ok'));
		} else {
			$this->error (L('add_error').': '.$model->getDbError());
		}
	}
	/**
     * 添加
     *
     */
	function add() {
		$name = MODULE_NAME;
		$this->display ('edit');
	}

	function edit() {
		$name = MODULE_NAME;
		$model = M ( $name );
		$pk=ucfirst($model->getPk ());
		$id = $_REQUEST [$model->getPk ()];
		if(empty($id))   $this->error(L('do_empty'));
		$do='getBy'.$pk;
		$vo = $model->$do ( $id );
		if($vo['setup']) $vo['setup']=string2array($vo['setup']);
		$this->assign ( 'vo', $vo );
		$this->display ();
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
			if(in_array($name,$this->cache_model)){
				savecache($name);
			}
		
			$jumpUrl = $_POST['forward'] ? $_POST['forward'] : U(MODULE_NAME.'/index');
			$this->assign ( 'jumpUrl',$jumpUrl );
			$this->success (L('update_ok'));
		} else {
			$this->success (L('edit_error').': '.$model->getError());
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
				if(in_array($name,$this->cache_model)){
					savecache($name);
				}
				$this->success(L('delete_ok'));
			}else{
				$this->error(L('delete_error').': '.$model->getDbError());
			}
		}else{
			$this->error (L('do_empty'));
		}
	}
	/**
     * 批量操作
     *
     */
	public function listorder()
	{
		$name = MODULE_NAME;
		$model = M ( $name );
		$pk = $model->getPk ();
		$ids = $_POST['listorders'];
		foreach($ids as $key=>$r) {
			$data['seq']=$r;
			$model->where($pk .'='.$key)->save($data);
		}
		if(in_array($name,$this->cache_model)) savecache($name);
		$this->success (L('do_ok'));
	}
	/**
	 *
	 */
	public function getGroupId(){
		return $this->groupid;
	}

	/**
	 * 根据类型返回数据
	 * @param string $message
	 */
	public function returnMessage($message,$data = array()){
		if($this->isAjax() == true){
			$this->ajaxReturn($data,$message,1);
		}else{
			$this->show($message,'utf-8','text/xml');
		}
	}
	
	/**
	 * 通过对角色表的查询，得到$child是否是$father的子用户组
	 *
	 * @param int $father 父用户组id
	 * @param int $child 子用户组id
	 * @return boolean 如果是则返回true，否则返回false
	 */	
		protected function isRoleBelongTo($father,$child) {
		if ($child == 0 || $father == 0) {
			return false;
		}
		while ($child > 0) {
			if ($child == $father) {
				return true;
			}
			$child = $this->role_map[$child]['pid'];
		}
		return false;
	}
	
}

?>
