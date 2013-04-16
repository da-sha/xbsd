<?php
/* ------------------------------------------------------------
 * 日期		：2012-9-1
 * -----------------------------------------------------------
 * 创建人	：大傻 lilei.zh@qq.com
 * -----------------------------------------------------------
 * 文件说明	; LoginAction.class.php UTF-8
 * -----------------------------------------------------------
 */
/**
 * This is class IndexAction
 *
 */
class IndexAction extends NavAction {
	/**
	 * (non-PHPdoc)
	 * @see NavAction::_initialize()
	 */
	public function _initialize(){
		parent::_initialize();
	}
	public function index(){
		if($this->getGroupId() == C('GUEST_GROUPID')){
			$this->redirect("Login/index");
		}

		$this->display();
	}
	
	/**
	 * 保存缓存
	 */
	public function cache() {
		foreach($this->cache_model as $r){
			savecache($r);
		}
		$forward = $_GET['forward'] ?   $_GET['forward']  : U('Index/main');
		$this->assign ( 'jumpUrl', $forward );
		$this->success(L('do_success'));
	}

	public function main() {
        $this->display();
    }
}