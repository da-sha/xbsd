<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script type="text/javascript" src="../Public/js/jquery.min.js"></script> 
        <script type="text/javascript" src="../Public/js/jquery.artDialog.js?skin=default"></script> 
        <script type="text/javascript" src="../Public/js/iframeTools.js"></script>
        
        <link rel='stylesheet' type='text/css' href='../Public/css/style.css'>
 		<link rel='stylesheet' type='text/css' href='../Public/css/login.css'>
        <script type="text/javascript" src="../Public/js/global.js"></script>
        <script type="text/javascript" src="../Public/js/md5.js"></script>
        <script type="text/javascript" src="../Public/js/login.js"></script>
        
        <title><?php echo L('welcome');?></title>
        <script type="text/javascript">
            /*global data field for this page*/

        /*暴露URL路径*/
        var URL = "__URL__";
        var APP = "__APP__";

        /*for the input form*/
        defaults_text["user_name"] = "用户名";
        defaults_text["user_pwd"] = "密码";
        </script>
    </head>
    <body>
        <header>
		<!-- 主导航 -->
		<div class="header">
			<div class="logo"><a href="" target="_blank"><img src="../Public/images/logo.gif"></a></div>
		</div>
		<!-- 主导航 end-->
		</header>
		       
		<div class="box_left">
          		<div class="news">
          		
          		</div>
        </div>
		<div class="box_right">
		 <div class="login_box">
		     <div id="error_prompt"></div>
		     <ul>
		         <li class="login_input">
		         <label id="user_label" for="u" class="txt_default"></label>
		         <input  type="text" id="u" class="input_text">
		         </input>
		         </li>
		         <li class="login_input">
		         <label id="pwd_label" for="p" class="txt_default"></label>
		         <input type="password" id="p" class="input_text">
		         </input>
		         </li>
		         <li class="save_passwd">
		         <label title="为了保证您的信息安全，请不要在网吧或者公共机房勾选此项！" for="autologin">
		             <input type="checkbox" id="autologin">下次自动登录</input>
		         </label>
		         <a href="">忘记密码？</a>
		         </li>
		         <li>
		         <input type="submit" class="login_btn" value="登录"></input>
		         <input type="button" value="新用户" class="login_btn"></input>
		         </li>
		     </ul>
		 </div>
		</div>
    </body>
</html>