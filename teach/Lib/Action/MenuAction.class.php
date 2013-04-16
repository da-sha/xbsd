<?php
/**
 * Menu(菜单管理)
 * 根据不同用户的隶属关系，将在同一组的用户全部提取出来
 * 方便直白显示
 */
class MenuAction extends CommonAction
{
    protected $dao,$roleid;
	
	function _initialize()
	{
		parent::_initialize() ;
		
		$this->dao = M('menu');
		$this->assign('actionname',$this->getActionName());


		$this->roleid = $_GET['roleid'];
		/*默认为系统管理员菜单*/
		if($this->roleid == 0){
			$this->roleid = 1;
		}

		$this->assign("roleid",$this->roleid);
	}
	/**
     * 列表
     */
	public function index()
	{
		$result = $this->menudata;

		foreach($result as $r) {
			if ($this->isRoleBelongTo($this->roleid,$r['groupid']) == false) {
				continue;
			}
			$r['str_manage'] = '<a href="'.
					U('Menu/add',array( 'parentid' => $r['id'],'roleid'=>$this->roleid)).
					'">'.
					L('menu_add_submenu').
					'</a> | <a href="'.
					U('Menu/edit',array( 'id' => $r['id'],'roleid'=>$this->roleid)).
					'">'.
					L('edit').
					'</a> | <a href="javascript:confirm_delete(\''.
					U('Menu/delete',array( 'id' => $r['id'],'roleid'=>$this->roleid)).
					'\')">'.
					L('delete').
					'</a> ';
			$r['status'] ? $r['status']='<font color="green">'.L('enable').'</font>' : $r['status']='<font color="red">'.L(' disable').'</font>' ;
			$r['group_name'] = $this->role_map[$r['groupid']]['name'];
			$array[] = $r;
		}
		$str  = "<tr>
					<td width='40' align='center'><input name='listorders[\$id]' type='text' size='3' value='\$seq'></td>
					<td align='center'>\$id</td>
					<td >\$spacer\$name</td>
					<td align='center'>\$model</td>
					<td align='center'>\$action</td>
					<td align='center'>\$group_name</td>
					<td align='center'>\$status</td>
					<td align='center'>\$str_manage</td>
				</tr>";
		import ( '@.ORG.Tree' );
		$tree = new Tree ($array);
		$tree->icon = array('&nbsp;&nbsp;&nbsp;'.L('tree_1'),'&nbsp;&nbsp;&nbsp;'.L('tree_2'),'&nbsp;&nbsp;&nbsp;'.L('tree_3'));
		$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		$select_categorys = $tree->get_tree(0, $str);
		$tree_data["data"] = $select_categorys;
		$tree_data['group_name'] = $this->role_map[$this->roleid]["name"];

		/*选出最高级的菜单项*/
		$role = $this->filterRoleByParent(0);
		$this->assign("role",$role);
		$this->assign('tree_data', $tree_data);
		$this->display();
	}
	/**
     * 提交
     */
	public function _before_add()
	{
		$parentid =	intval($_GET['parentid']);
		import ( '@.ORG.Tree' );
		$result = $this->menudata;
		foreach($result as $r) {
			if ($this->isRoleBelongTo($this->roleid,$r['groupid']) == false) {
			//if($r['groupid'] != $this->roleid){
				continue;
			}
			$r['selected'] = $r['id'] == $parentid ? 'selected' : '';
			$array[] = $r;
		}

		$str  = "<option value='\$id' \$selected>\$spacer \$name</option>";

		$tree = new Tree ($array);
		$tree->icon = array(L('tree_1'),L('tree_2'),L('tree_3'));
		$select_categorys = $tree->get_tree(0, $str,$parentid);
		$this->assign('select_categorys', $select_categorys);

		if (empty($parentid) == true) {
			$vo['groupid'] = C('GUEST_GROUPID');
		}else{
			$vo['groupid'] = $result[$parentid]['groupid'];
		}
		$this->assign("vo",$vo);
		$role = $this->filterRoleByParent($this->roleid,true);
		$this->assign('role',$role);
	}

	/**
	 *
	 */
	public function _before_insert(){
		$_POST['model'] = ucfirst($_POST['model']);
		$_POST['action'] = strtolower($_POST['action']);
	}
	/**
	 *
	 */
	public function _before_update(){
		$_POST['model'] = ucfirst($_POST['model']);
		$_POST['action'] = strtolower($_POST['action']);
	}

	/**
	 *
	 */
	function edit() {
		$id =	intval($_GET['id']);;
		$vo = $this->menudata[$id];
		$parentid =	intval($vo['parentid']);
		import ( '@.ORG.Tree' );
		$result = $this->menudata;

		foreach($result as $r) {
			if ($this->isRoleBelongTo($this->roleid,$r['groupid']) == false) {
			//if($r['groupid'] != $this->roleid){
				continue;
			}
			$r['selected'] = $r['id'] == $parentid ? 'selected' : '';
			$array[] = $r;
		}
		$str  = "<option value='\$id' \$selected>\$spacer \$name</option>";
		$tree = new Tree ($array);
		$tree->icon = array(L('tree_1'),L('tree_2'),L('tree_3'));
		$select_categorys = $tree->get_tree(0, $str,$parentid);
		$this->assign('select_categorys', $select_categorys);
		$this->assign ( 'vo', $vo );

		$role = $this->filterRoleByParent($this->roleid,true);
		$this->assign('role',$role);
		$this->display ();
	}

	/**
	 * 通过父节点选出所有子用户组
	 *
	 * @param int $parentid 父用户组id
	 * @param boolean $b_user_father 是否将父用户组一并保留
	 * @return array 过滤后的用户组
	 *
	 */		
	private function filterRoleByParent($parentid,$b_user_father = false) {
		$data = F('Role');
		$role = array();
		
		foreach($data as $key => $item){
			if ($item['pid'] == $parentid) {
				$role[] = $item;
			}else if ($b_user_father == true && $item['id'] == $parentid) {
				$role[] = $item;
			}
		}
		return $role;
	}
}
?>
