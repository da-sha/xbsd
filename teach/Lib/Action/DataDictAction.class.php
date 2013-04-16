<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-9
 * -----------------------------------------------------------
 * 创建人	：大傻 lilei.zh@qq.com
 * -----------------------------------------------------------
 * 文件说明	; DataDictAction.class.php UTF-8
 * -----------------------------------------------------------
 */

class DataDictAction extends CommonAction {
	public function body() {
		$tb_name = $_GET[tb_name];
		if ($tb_name) {
			$dict = new DataDictModel();
			$where ="tb_name='{$tb_name}'";
			$count = $dict->where($where)->count();
			if( $count !== '0' )
			{
				import('ORG.Util.Page');
				$page = new Page($count, C('PAGESIZE'));
				$show = $page->show();
				$this->assign("show", $show);
				$data = $dict->where($where)
							->field("tb_name,
									fd_name,
									fd_value,
									fd_mean,
									get_field_dict_name('data_dict','type',type) as type,
									seq,
									remark")
						->limit($page->firstRow . ',' . $page->listRows)->select();
				$len = count( $data ) ;
				for( $i = 0 ; $i < $len ; $i++ )
				{
					$data[$i]['comment'] = $this->get_fd_comment( $data[$i]['tb_name'], $data[$i]['fd_name'] ) ;
				}
			}
			else {
				$this->assign("show", "对不起，目前该数据表没有建立数据字典！");
			}
			$this->assign('data', $data);
		} else {
			$this->assign("show", "欢迎您的使用，请首先选择数据库");
		}
		$this->display();
	}

	public function index() {
		$db = new Model();
		$data = $db->table('information_schema.TABLES')
			->where("TABLE_SCHEMA='{$this->get_db_name()}'")
				->field('TABLE_NAME as tb_name,TABLE_COMMENT as comment')
				->select();
		$len = count($data);
		$this->assign("data", $data);
		$this->display();
	}

	public function delete() {
		$db = M("data_dict");
		if (false !== $db->where("tb_name='{$_GET['tb_name']}' and fd_name='{$_GET['fd_name']}' and fd_value={$_GET['fd_value']}")->delete()) {
			$this->success('删除成功', "__URL__/body/tb_name/{$_GET['tb_name']}");
		} else {
			$this->error("删除失败" . $db->getDbError(), "__URL__/body/tb_name/{$_GET['tb_name']}");
		}
	}

	public function edit() {
		$tb_name = $_GET['tb_name'];
		$fd_name = $_GET['fd_name'];
		$fd_value = $_GET['fd_value'];
		$dict = new DataDictModel();
		$data = $dict->where("tb_name='{$tb_name}' and fd_name='{$fd_name}' and fd_value={$fd_value}")->find();
		$this->assign('data', $data);
		$this->assign("tb_comment", $this->get_tb_comment($tb_name));
		$this->assign("fd_comment", $this->get_fd_comment($tb_name, $fd_name));
		$this->get_field_dict( "data_dict", "type", "type_list");
		$this->display();
	}
	public function update() {
		$dict = new DataDictModel();
		$data = $dict->create();
		if ($data) {
			if (false !== $dict->where("tb_name='{$data[tb_name]}' and fd_name='{$data[fd_name]}' and fd_value={$data[fd_value]}")->save()) {
				$this->assign('jumpUrl', __URL__ . "/body/tb_name/{$data['tb_name']}");
				$this->success('操作成功');
			} else {
				$this->error('操作失败：' . $dict->getDbError());
			}
		} else {
			$this->error('操作失败：数据验证( ' . $dict->getError() . ' )');
		}
	}

	public function add() {
		$tb_name = $_GET[tb_name];
		if ($tb_name == NULL) {
			$this->error('请先选择数据表', "__URL__/body");
			return ;
		}
		$db = M();
		$data = $db->table('information_schema.COLUMNS')
				->where("TABLE_SCHEMA='{$this->get_db_name()}'and TABLE_NAME='{$tb_name}'")
				->field('COLUMN_NAME as fd_name,COLUMN_COMMENT as comment')
				->select();
		$this->assign('tb_name', $tb_name);
		$this->assign('tb_comment', $this->get_tb_comment($tb_name));
		$this->assign('list', $data);
		$this->assign("fd_name", $this->_get("fd_name")) ;
		$this->get_field_dict( "data_dict" ,"type", "type_list");
		$this->display();
	}

	public function insert() {
		$dict = new DataDictModel( );
		if ($data = $dict->create()) {
			if (false !== $dict->add()) {
				$this->success('恭喜您创建数据字典成功。', "__URL__/add/tb_name/{$data['tb_name']}/fd_name/{$data['fd_name']}");
			} else {
				$this->error('操作失败：' . $dict->getDbError());
			}
		} else {
			$this->error('操作失败：数据验证( ' . $dict->getError() . ' )');
		}
	}
}
?>
