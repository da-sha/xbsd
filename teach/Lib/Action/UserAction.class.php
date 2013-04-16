<?php
/**
 * 用户管理
 */
class UserAction extends CommonAction {

    public $db_login,$usergroup;
	function _initialize()
	{
		parent::_initialize();
		$this->db_login = D('Login');
		$this->usergroup=F('Role');
		$this->assign('usergroup',$this->usergroup);
	}

	function index(){
		import('ORG.Util.Page');

		$keyword=$_GET['keyword'];
		$searchtype=$_GET['searchtype'];
		$groupid =intval($_GET['groupid']);

		$this->assign($_GET);
		$where = array();
		
		if(!empty($keyword) && !empty($searchtype)){
			$where[$searchtype]=array('like','%'.$keyword.'%');
		}
		if($groupid){
			$where['roleid']=$groupid;
		}
		
		$count=$this->db_login->where($where)->count();

		$page=new Page($count,C("PAGESIZE"));
		$show=$page->show();
		$this->assign("page",$show);
		
		$list = $this->db_login->where($where)
			->field("userid,
					user_code,
					update_date,
					roleid")
			->limit($page->firstRow.','.$page->listRows)
			->select();
		$this->assign('ulist',$list);
		$this->display();
	}

	function insert(){
		$user=$this->db_login;
		$_POST['user_psd'] = pwdHash($_POST['pwd']);
		if($data=$user->create()){
			if(false!==$user->add()){
				$this->success(L('add_ok'));
			}else{
				$this->error(L('add_error'));
			}
		}else{
			$this->error($user->getError());
		}
	}

	function update(){
		$user=$this->db_login;
		$_POST['user_psd'] = $_POST['pwd'] ? pwdHash($_POST['pwd']) : $_POST['opwd'];
		if($data=$user->create()){
			if(!empty($data['userid'])){
				if(false!==$user->save()){
					$this->success(L('edit_ok'));
				}else{
					$this->error(L('edit_error').$user->getDbError());
				}
			}else{
				$this->error(L('do_error'));
			}
		}else{
			$this->error($user->getError());
		}
	}


	function _before_add(){
		$this->assign('rlist',$this->usergroup);
	}

	function edit(){
		$model = M ("login");
		$pk=ucfirst($model->getPk ());
		$id = $_REQUEST [$model->getPk ()];
		if(empty($id))   $this->error(L('do_empty'));
		$do='getBy'.$pk;
		$vo = $model->$do ( $id );
		
		$this->assign ( 'vo', $vo );
		$this->assign('rlist',$this->usergroup);
		$this->display ();
	}


	function delete(){
		$id=$_GET['id'];
		$user=$this->db_login;
		if(false!==$user->delete($id)){
			$this->success(L('delete_ok'));
		}else{
			$this->error(L('delete_error').$user->getDbError());
		}
	}

	function deleteall(){
		$ids=$_POST['ids'];
		if(!empty($ids) && is_array($ids)){
			$user=$this->db_login;
			$id=implode(',',$ids);
			if(false!==$user->delete($id)){
				$this->success(L('delete_ok'));
			}else{
				$this->error(L('delete_error'));
			}
		}else{
			$this->error(L('do_empty'));
		}
	}
}
?>