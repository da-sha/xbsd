<?php
/**
 * Role(会员组管理)
 */
class RoleAction extends CommonAction {

	protected $dao,$roledata;

    function _initialize()
    {
		parent::_initialize();
		$this->roledata = F('Role');
    }

	public function _before_add(){
		$this->assign("roledata",$this->roledata);
	}
	
	public function _before_edit(){
		$this->assign("roledata",$this->roledata);
	}
    /*
     * 在这里添加默认用户权限
     */
	public function _before_insert()
    {
	}


	public function _before_update()
    {
	}

}
?>