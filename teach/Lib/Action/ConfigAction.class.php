<?php

/*------------------------------------------------------------
 * 日期		：2012-11-6
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	; ConfigAction.class.php UTF-8
 * -----------------------------------------------------------
*/

class ConfigAction extends CommonAction {
	public function index() {
		$this->get_field_dict( 'config', 'type', 'type_list');
		$db = new Model('config');
		$data = $db->where("type=1")
				->field("id, varname,info, value")
				->select();
		if($data){
			$this->assign( "config_list", $data);
		}else{
			$this->assign( "show", "暂时没有配置数据");
		}
		$this->display();
	}

	public function sys() {
		$type = $this->_get("type");
		$db = new Model('config');
		$data = $db->where("type={$type}")
				->field("id, varname,info, value")
				->select();
		if($data){
			$this->assign( "config_list", $data);
		}else{
			$this->assign( "show", "暂时没有配置数据");
		}
		$this->display();
	}

	public function add() {
		$this->get_field_dict( 'config', 'type', 'type_list');
		$this->display();
	}

	public function insert() {
		$db = new Model("config");
		if ($data = $db->create()) {
			if (false !== $db->add()) {
				$this->success('恭喜您添加成功！', "__URL__/index");
			} else {
				$this->error('操作失败：' . $db->getDbError(), "__URL__/index");
			}
		} else {
			$this->error('操作失败：数据验证( ' . $db->getError() . ' )');
		}
	}

	public function update() {
		if("删除"==$_POST['submit']){

			if (!empty($_POST['sid']) && is_array($_POST['sid'])) {
				$db = new Model('config');
				$id = implode(',', $_POST['sid']);
				if (false !== $db->where('id in(' . $id . ')')->delete()) {
					$this->assign('jumpUrl', __URL__ . '/index');
					$this->success('操作成功');
				} else {
					$this->error('操作失败：' . $db->getDbError());
				}
			} else {
				$this->error('请选择删除的项',  __URL__ . '/index');
			}
		}else{
			$db = new Model("config");
			$temp=array();
			if ($data = $db->create() ) {
				dump($data);
				$j = count($data['id']);
				for($i=0; $i<$j; $i++){
					$temp['id'] = $data['id'][$i];
					$temp['value'] = $data['value'][$i];
					if ( $db->create($temp) ){
						if (false ===$db->save()){
							$this->error('操作失败' );
						}
					}
				}
				$this->success("恭喜您编辑成功") ;
			} else {
				$this->error('操作失败：数据验证( ' . $db->getError() . ' )');
			}
		}
	}

	public function delete() {
		$db = new Model("config");
		$temp=array();
		if ($data = $db->create() ) {
			$j = count($data['id']);
			for($i=0; $i<$j; $i++){
				$temp['id'] = $data['id'][$i];
				$temp['value'] = $data['value'][$i];
				if ( $db->create($temp) ){
					if (false ===$db->save()){
						$this->error('操作失败' );
					}
				}
			}
			$this->success("恭喜您编辑成功") ;
		} else {
			$this->error('操作失败：数据验证( ' . $db->getError() . ' )');
		}
	}
}
?>