<?php
/**
 * @date 2012年9月21日 20:36:46
 * @author lisency
 * @deprecation 对导航部分共用模块进行抽象化
 * 所有继承自NavAction的全部将检查导航及菜单
 */

abstract class NavAction extends CommonAction{

	/**
	 * 检查用户类型的目的是为了调菜单，调菜单在这里，
	 * @see CommonAction::_initialize()
	 */
	public function _initialize(){
		parent::_initialize();

		$this->assign('usergroup',$this->role_map[$this->getGroupId()]['name']);
		/*在这里需要设置默认权限控制信息*/

		$nav = array();

		foreach($this->menudata as $key=>$menu) {
			if($menu['parentid'] != 0 || $menu['status'] == 0){
				continue;
			}
			if ($this->isRoleBelongTo($menu['groupid'],$this->getGroupId()) == true) {
				$nav[$key] = $menu;
			}
		}

		$this->assign('menuGroupList',$nav);
		$keys = array_keys($nav);
		$this->assign("first_nav_id",$keys[0]);
		//dump($nav);
		//dump($_SESSION);
		$menu = array();
		foreach($nav as $key=>$r){
			/*得到一级菜单*/
			$level1_menu = $this->getnav($r['id']);

			/*得到二级菜单*/
			foreach ($level1_menu['nav'] as $l1key=>$level2_menu) {
				$level2_menu = $this->getnav($level2_menu['id']);
				foreach ($level2_menu['nav'] as $l2key=>$level3_menu) {
					$level2_menu['nav'][$l2key] = $this->getnav($level3_menu['id']);
				}
				$level1_menu['nav'][$l1key] = $level2_menu;
			}
			$menu[$r['id']]  = $level1_menu;
		}
		$this->assign('menu',$menu);
	}

}
?>
