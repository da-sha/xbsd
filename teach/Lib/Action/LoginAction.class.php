<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-5
 * -----------------------------------------------------------
 * 创建人	：大傻 lilei.zh@qq.com
 * -----------------------------------------------------------
 * 文件说明	; LoginAction.class.php UTF-8
 * -----------------------------------------------------------
 */

class LoginAction extends CommonAction{

	public function _initialize(){
		parent::_initialize();
	}
	
	public function index(){
		if($this->getGroupId() != C('GUEST_GROUPID')){
			$this->assign('jumpUrl',U('Index/index'));
			$this->error(L('logined'));
			return;
		}
		$this->assign('notlogin',1);
        $this->display();
	}
	/**
	 * 登录
	 */
	public function login(){
		$login = D('Login');

		$user = trim($_GET['user']);
		$pwd = trim($_GET['pwd']);

		if(empty($user) || empty($pwd)){
           $this->error("用户名或密码不能为空");
		}

		$condition = array();
        $condition['user_code'] = array('eq',$user);

		import ( '@.ORG.RBAC' );
        $authInfo = RBAC::authenticate($condition);
        //使用用户名、密码和状态的方式进行认证
        if(false === $authInfo) {
            $this->error("用户不存在或密码错误");
        }else {
            if($authInfo['user_psd'] != $pwd) {
            	$this->error("用户不存在或密码错误");
            }

			session(C('USER_AUTH_KEY'),$authInfo['userid']);
			session('user_code',$authInfo['user_code']);

			$groupid = $authInfo['roleid'];

			/*
			if($groupid == '2' || $groupid == '1'){
				session(C('ADMIN_AUTH_KEY'),true);
			}
			*/
			session(C('ADMIN_AUTH_KEY'),true);

			session('groupid',$groupid);


            //$login->saveLoginInfo();
           // 缓存访问权限
            RBAC::saveAccessList();

			$this->assign('jumpUrl',U('Index/index'));
			$this->success("登录成功");
		}
	}

    /**
     * 退出登录
     */
    public function logout()
    {
		if(isset($_SESSION[C('USER_AUTH_KEY')])) {
			unset($_SESSION[C('USER_AUTH_KEY')]);
			unset($_SESSION);
			session_destroy();
            $this->assign('jumpUrl',U('Login/index'));
			$this->success(L('loginouted'));
        }else {
			$this->assign('jumpUrl',U('Login/index'));
			$this->success(L('loginouted'));
        }
    }
}

?>
