<?php
//	$path = "/XBSD/index.php/Login" ;
//	$curpath = substr( $_SERVER['REQUEST_URI'] , 0, 21 ) ;
//	if( $curpath != $path )
//	{
//		if( $_SESSION["user_id"] == null || $_SESSION["user_type"] == null )
//		{
//			echo "<head><meta http-equiv='refresh' content='3;url=Login'></head>" ;
//			echo '对不起，您没有登录，3秒后自动跳转；' ;
//		}
//	}
	define("APP_DEBUG", true);
	//项目名称
	define("APP_NAME", "teach");
	//项目路径
	define("APP_PATH", "./teach/");
	//引入框架核心文件
	require './ThinkPHP/ThinkPHP.php';
	
?>