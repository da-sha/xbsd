<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-13
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	; OrganizeAction.class.php UTF-8
 * -----------------------------------------------------------
 */

class OrganizeAction extends CommonAction {

	public function index() {
		$org = new OrganizeModel();
		$count = $org->count();
		import('ORG.Util.Page');
		$page = new Page($count, C('PAGESIZE'));
		$show = $page->show();
		$this->assign("show", $show);
		$data = $org->where($where)
					->field("orgid,
							name,
							get_field_dict_name('organize','level',level) as level,
							get_org_name(high_org) as high_org,
							address")
					->limit($page->firstRow . ',' . $page->listRows)
					->select();
		$this->assign('list', $data);
		$this->display();
	}

	public function delete() {
		$orgid = $this->_get("orgid");
		if ($orgid) {
			$db = new OrganizeModel();
			if (false !== $db->where("orgid={$orgid}")->delete()) {
				$this->success('删除成功', "__URL__/index");
			} else {
				$this->error("删除失败" . $db->getDbError(), "__URL__/index");
			}
		}
	}

	public function add() {
		$this->get_field_dict( 'organize', 'level', 'level_list');	
		$db = new OrganizeModel();
		$high = $db->field('orgid as id, name')->select();
		$this->assign("high_list", $high);
		$this->display();
	}

	public function insert() {
		$db = new OrganizeModel();
		if ($db->create()) {
			if (false !== $db->add()) {
				$this->success('恭喜您添加机构成功！', "__URL__/index");
			} else {
				$this->error('操作失败：' . $db->getDbError(), "__URL__/index");
			}
		} else {
			$this->error('操作失败：数据验证( ' . $db->getError() . ' )');
		}
	}

	public function edit() {
		$orgid = $this->_get("orgid");
		$db = new OrganizeModel();
		$data = $db->find($orgid);
		$this->assign("org", $data);
		$this->get_field_dict( 'organize', 'level', 'level_list');
		$high = $db->field('orgid as id, name')->select();
		$this->assign("high_list", $high);
		$this->display();
	}

	public function update() {
		$db = new OrganizeModel();
		if ($data = $db->create()) {
			if (false !== $db->save()) {
				$this->success('恭喜您编辑机构成功！', "__URL__/index");
			} else {
				$this->error('操作失败：' . $db->getDbError(), "__URL__/index");
			}
		} else {
			$this->error('操作失败：数据验证( ' . $db->getError() . ' )');
		}
	}
}

?>
