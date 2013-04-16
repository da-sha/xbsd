<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-7
 * -----------------------------------------------------------
 * 创建人	：大傻 lilei.zh@qq.com
 * -----------------------------------------------------------
 * 文件说明	; TeacherAction.class.php UTF-8
 * -----------------------------------------------------------
 */

class TeacherAction  extends CommonAction{

	protected $db_teacher;

	public function _initialize(){
		parent::_initialize();
		$this->db_teacher = M("teacher") ;
	}
	public function index() {

		$db = M("teacher") ;
		$count = $db->count();
		if($count === '0')
		{
			$this->assign("show", "对不起，暂时没有数据！");
			$this->display();
		}
		import('ORG.Util.Page');
		$page = new Page($count, C('PAGESIZE'));
		$show = $page->show();
		$this->assign("show", $show);
		$data = $db->field("
						userid,
						name,
						get_field_dict_name('teacher','gender',gender) as gender,
						get_field_dict_name('teacher','nationality',nationality) as nationality,
						get_org_name(orgid) as orgid,
						get_field_dict_name('teacher','job_title',job_title) as job_title,
						get_field_dict_name('teacher','degree',degree) as degree,
						telphone1,
						email1
						")
					->limit($page->firstRow . ',' . $page->listRows)
					->select();
		$this->assign('list', $data);
		$this->display();
	}
	public function edit() {
		$userid = $this->_get("userid") ;
		if( !$userid )
		{
			$this->error("对不起，参数错误！") ;
			return ;
		}
		$db = M("teacher") ;
		$data = $db->find($userid) ;
		if( !$data )
		{
			$this->error("对不起，该用户资料不存在！") ;
			return ;
		}
		$this->assign( "data" ,$data) ;
		$this->get_field_dict( 'student', 'gender', 'gen_list');
		$this->get_field_dict( 'teacher', 'nationality', 'nat_list');
		$this->get_field_dict( 'teacher', 'job_title', 'job_list');
		$this->get_field_dict( 'teacher', 'degree', 'deg_list');
		$db = M('organize');
		$data = $db->field("orgid as id,name")->select();
		$this->assign( "org_list", $data ) ;
		$this->display() ;
	}

	public function show()
	{
		$db = new TeacherModel() ;
		$data = $db->find( $db->getUser() ) ;
		if( $data === false )
		{
			$this->error("对不起，没有找到该用户资料，请重新登录！", "myurl") ;
		}
		$this->get_field_dict( 'student', 'gender', 't_gender');
		$this->get_field_dict( 'teacher', 'job_title', 'job_list');
		$this->get_field_dict( 'teacher', 'nationality', 'nat_list');
		$comdb = M("organize") ;
		$data['org'] = $comdb->field("name")->find($data['orgid']) ;
		$data['org'] = $data['org']['name'] ;
		$this->assign("teacher", $data ) ;
		$this->display() ;
	}
	public function add()
	{
		$this->get_field_dict( 'student', 'gender', 'gen_list');
		$this->get_field_dict( 'teacher', 'nationality', 'nat_list');
		$this->get_field_dict( 'teacher', 'job_title', 'job_list');
		$this->get_field_dict( 'teacher', 'degree', 'deg_list');
		$db = M('organize');
		$data = $db->field("orgid as id,name")->select();
		$this->assign( "org_list", $data ) ;
		$this->display();
	}

	public function update_all()
	{
		$db = new TeacherModel() ;
		$data = $db->create() ;
		if( $data )
		{
			if( false!==$db->save() )
			{
				$this->assign('jumpUrl',"__URL__/index");
				$this->success('操作成功');
			}
			else
			{
				$this->error('操作失败：'.$db->getDbError());
			}
        }
		else
		{
            $this->error('操作失败：数据验证( '.$db->getError().' )');
        }
	}
	public function insert() {
		$db = new TeacherModel();
		if ($db->create()) {
			if (false !== $db->add()) {
				$this->success('恭喜您添加课程成功！', "__URL__/add");
			} else {
				$this->error('操作失败：' . $db->getDbError(), "__URL__/add");
			}
		} else {
			$this->error('操作失败：数据验证( ' . $db->getError() . ' )');
		}
	}
	public function delete() {
		$userid = $this->_get("userid");
		if ($userid) {
			$db = M("teacher");
			if (false !== $db->where("userid={$userid}")->delete()) {
				$this->success('删除成功');
			} else {
				$this->error("删除失败" . $db->getDbError());
			}
		}
		else
		{
			$this->error("删除失败！参数错误！");
		}
	}

	public function updata()
	{
		$db = new TeacherModel() ;
		$data = $db->create() ;
		if( $data )
		{
			$db->userid = $db->getUser() ;
			if( $db->userid == false )
			{
				$this->error( "您没有登录或者登录过期，请重新登录" , __APP__."/Login" ) ;
			}
			else
			{
				if( false!==$db->save() )
				{
					$this->assign('jumpUrl',"__URL__/show");
					$this->success('操作成功');
				}
				else
				{
					$this->error('操作失败：'.$db->getDbError());
				}
			}
        }
		else
		{
            $this->error('操作失败：数据验证( '.$db->getError().' )');
        }
	}

	/**
	 * 将教师的信息全部选出已json编码，方便autocomplete插件查找
	 */
	public function teacher_info_json(){
		$teachers = $this->db_teacher->field("userid,
					name,
					get_user_code(userid) as user_code
					")->select();

		//dump($teachers);
		$results = array();
		$q = $_GET["q"];
		/*
		* Search for term if it is given
		*/
		if (isset($_GET['q'])) {
			if ($q) {
				foreach ($teachers as $key => $teacher) {
					//echo $teacher["name"]."\n";
					if(preg_match("/".$q."/", $teacher['name'])){
						$results[] = array($teacher['name'], $teacher);
						//$results[] = array($teacher['userid'], $teacher);
					}
				}
			}
		}
		echo json_encode($results);
	}

	public function depnotice(){
		$notice = new NoticeModel();
		$where = "type=2";
		$count = $notice->where($where)->count();
		import('ORG.Util.Page');
		$page = new Page($count, C('PAGESIZE'));
		$show = $page->show();
		$this->assign("show", $show);
		$data = $notice->where($where)
					->field("noticeid,
							get_org_name(orgid) as orgid,
							source,
							get_field_dict_name('notice','type',type) as type,
							title,
							content,
							get_field_dict_name('notice','importance',importance) as importance,
							update_date")
					->order("update_date desc")
					->limit($page->firstRow . ',' . $page->listRows)
					->select();
		if($data){
			$this->assign('list', $data);
		}else{
			$this->assign('show', "暂无公告！");
		}
		$this->display();
	}
	
	public function teacherlist(){
		$db = M("teacher, organize") ;
		$where = "teacher.orgid=organize.orgid";
		$count = $db->where($where)->count();
		import('ORG.Util.Page');
		$page = new Page($count, C('PAGESIZE'));
		$show = $page->show();
		$this->assign("show", $show);
		$data = $db->where($where)
					->field("teacher.userid, 
							organize.name as orgname, 
							teacher.name, 
							get_field_dict_name('teacher','teacher.job_title',teacher.job_title) as job_title")
					->limit($page->firstRow . ',' . $page->listRows)
					->select();
		if(!$data){
			$this->assign('show', "暂无老师！");
		}else{
			$this->assign('teacherlist', $data);
		}
		$this->display();
	}
	
	public function teacherinfo()
	{
		$db = new TeacherModel() ;
		$data = $db->field("*,get_field_dict_name('teacher','gender',gender) as gender_name,
			get_field_dict_name('teacher','nationality',nationality) as nationality_name,
			get_org_name(orgid) as orgid_name,
			get_field_dict_name('teacher','job_title',job_title) as job_title_name,
			get_field_dict_name('teacher','Politics_status',Politics_status) as Politics_status_name,
			get_field_dict_name('teacher','degree',degree) as degree_name")
			->find( $_GET['userid'] ) ;
		if( $data === false )
		{
			$this->error("对不起，没有找到该用户资料，请重新登录！", "myurl") ;
			return ;
		}
		$this->assign("teacher", $data ) ;
		$this->display() ;
	}
}

?>
