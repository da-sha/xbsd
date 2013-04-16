<?php

/*------------------------------------------------------------
 * 日期		：2012-11-6
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	; DatabaseAction.class.php UTF-8
 * -----------------------------------------------------------
*/

class DatabaseAction extends CommonAction {

	protected $db = '', $datadir =  './public/data/';
    function _initialize()
    {
		parent::_initialize();
		$db=D('');
		$this->db =   DB::getInstance();
    }
	public function index() {
		$db = new Model();
		$dataList = $db->query("SHOW TABLE STATUS LIKE '" . C('DB_PREFIX') . "%'");
		foreach ($dataList as $row) {
			$total += $row['Data_length'];
		}
		$this->assign('totalSize', $total);
		$this->assign("dataList", $dataList);
		$this->display();
	}

	public function recover() {
		if($_GET['do']=='delete'){
			if(!$_POST['files']){
				$this->error("请选择要删除的备份文件！");
			}
			foreach ((array)$_POST['files'] as $r){
				unlink($r);
			}
			$this->success(L('do_ok'));
		}elseif($_GET['do']=='import'){
			header('Content-Type: text/html; charset=UTF-8');
			$filename = $_GET['filename'];
			$file = $this->datadir.$filename;

			//读取数据文件
			$sqldata = file_get_contents($file);

			$sqlFormat = sql_split($sqldata, C('DB_PREFIX'));
			foreach ((array)$sqlFormat as $sql){
					$sql = trim($sql);
					if (strstr($sql, 'CREATE TABLE')){
						preg_match('/CREATE TABLE `([^ ]*)`/', $sql, $matches);
						$ret = $this->excuteQuery($sql);
						if($ret){echo   L('CREATE_TABLE_OK').$matches[0].' <br />';}else{echo 'Error sql:'.$sql;}
					}else{
						$ret =$this->excuteQuery($sql);
					}
			}

		}else{
			$filelist = dir_list($this->datadir);
			foreach ((array)$filelist as $r){
				$files[] = array('path'=> $r,'name' => basename($r), 'size' => filesize($r), 'time' => filemtime($r));
			}
			$this->assign('files',$files);
			$this->display();
		}
	}

	public function excuteQuery($sql='')
    {
        if(empty($sql)) {$this->error(L('do_empty'));}
        $queryType = 'INSERT|UPDATE|DELETE|REPLACE|CREATE|DROP|LOAD DATA|SELECT .* INTO|COPY|ALTER|GRANT|TRUNCATE|REVOKE|LOCK|UNLOCK';
        if (preg_match('/^\s*"?(' . $queryType . ')\s+/i', $sql)) {
            $data['result'] = $this->db->execute($sql);
            $data['type'] = 'execute';
        }else {
            $data['result'] = $this->db->query($sql);
            $data['type'] = 'query';
        }
        $data['dberror'] = $this->db->error();
        return $data;
    }
	
	public function backup() {
		if(empty( $_POST['tables'])) {
			$dataList = $this->db->query("SHOW TABLE STATUS LIKE '".C('DB_PREFIX')."%'");
			foreach ($dataList as $row){
				$table[]= $row['Name'];
			}
		}else{
			$table=$_POST['tables'];
		}
		$sql = "-- xiaoqing SQL Backup\n-- Time:".toDate(time())."\n-- http://www.yourphp.cn \n\n";
        foreach($table as $key=>$table) {
            $sql .= "--\n-- 表的结构 `$table`\n-- \n";
            $sql .= "DROP TABLE IF EXISTS `$table`;\n";
            $info = $this->db->query("SHOW CREATE TABLE  $table");
            $sql .= str_replace(array('USING BTREE','ROW_FORMAT=DYNAMIC'),'',$info[0]['Create Table']).";\n";
            $result = $this->db->query("SELECT * FROM $table ");
			if($result)$sql .= "\n-- \n-- 导出`$table`表中的数据 `$table`\n--\n";
            foreach($result as $key=>$val) {
                foreach ($val as $k=>$field){
                    if(is_string($field)) {
                        $val[$k] = '\''. $this->db->escapeString($field).'\'';
                    }elseif(empty($field)){
                        $val[$k] = 'NULL';
                    }
                }
                $sql .= "INSERT INTO `$table` VALUES (".implode(',', $val).");\n";
            }
        }
		$filename = empty($fileName)? date('YmdH').'_'.rand_string(10) : $fileName;
        $r= file_put_contents($this->datadir . $filename.'.sql', trim($sql));
		if($r){
			$this->assign ( 'jumpUrl', U(MODULE_NAME.'/recover') );
			$this->success(L('do_ok'));
		}
	}
}
function  sql_split($sql,$tablepre) {

	if($tablepre != "yourphp_") $sql = str_replace("yourphp_", $tablepre, $sql);
	//$sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=utf8",$sql);

	if($r_tablepre != $s_tablepre) $sql = str_replace($s_tablepre, $r_tablepre, $sql);
	$sql = str_replace("\r", "\n", $sql);
	$ret = array();
	$num = 0;
	$queriesarray = explode(";\n", trim($sql));
	unset($sql);
	foreach($queriesarray as $query)
	{
		$ret[$num] = '';
		$queries = explode("\n", trim($query));
		$queries = array_filter($queries);
		foreach($queries as $query)
		{
			$str1 = substr($query, 0, 1);
			if($str1 != '#' && $str1 != '-') $ret[$num] .= $query;
		}
		$num++;
	}
	return $ret;
}
?>
