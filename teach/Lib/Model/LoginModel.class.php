<?php
/**
 * @author lisency
 * @date 2012年9月25日 10:13:03
 * @deprecated 登录数据库管理
 */

class LoginModel extends CommonModel{
	/**
	 * 自动填充字段
	 */
	protected $_auto = array(
		array('operator','getUser',3,'callback'),
        array('update_date','getDate',3,'callback'),
    );

    /**
     * 保存登录信息
     */
    public function saveLoginInfo(){
		$data = array();
		$data['userid'] = session(C("USER_AUTH_KEY"));
		$data['update_date'] = time();
		$data['last_ip'] =  get_client_ip();
		$data['login_count'] = session('login_count');

		$this->save($data);
    }
}
?>