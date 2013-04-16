<?php
/**
 * @date 2012年9月21日 20:36:46
 * @author lisency
 * @deprecated 注册模块
 */

class RegAction extends Action{
	public function index(){
		$this->display("reg_id");
	}
	/*
	 * 填写register id
	 */
	public function reg_id() {
		$this->display();
	}
	/**
	 * 填写register详细信息
	 */
	public function reg_info() {
		$this->display();
	}
	/**
	 * 注册完成页面
	 */
	public function reg_finish() {
		$this->display();
	}
}
?>