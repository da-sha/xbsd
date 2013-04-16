<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel='stylesheet' type='text/css' href='../Public/css/style.css'>

<script type="text/javascript" src="../Public/js/jquery.min.js"></script> 
<script type="text/javascript" src="../Public/js/jquery.artDialog.js?skin=default"></script> 
<script type="text/javascript" src="../Public/js/iframeTools.js"></script>
<script type="text/javascript" src="../Public/js/jquery.form.js"></script> 
<script type="text/javascript" src="../Public/js/jquery.validate.js"></script> 
<script type="text/javascript" src="../Public/js/MyDate/WdatePicker.js"></script> 
<script type="text/javascript" src="../Public/js/jquery.colorpicker.js"></script> 
<script type="text/javascript" src="../Public/js/my.js"></script> 
<script type="text/javascript" src="../Public/js/swfupload.js"></script> 

<title><?php echo L('welcome');?></title>
</head>
<body>

<header>
<!-- 主导航 -->
<div class="header">
	<div class="logo"><a href="" target="_blank"><img src="../Public/images/logo.gif"></a></div>
	      <div class="menu">
	          <ul>
	          	<?php if(is_array($menuGroupList)): $i = 0; $__LIST__ = $menuGroupList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$tag): $mod = ($i % 2 );++$i;?><li id="menu_<?php echo ($tag["id"]); ?>" onClick="sethighlight(<?php echo ($tag["id"]); ?>);">
					<a href="javascript:void(0);" onClick="sethighlight(<?php echo ($tag["id"]); ?>);"><?php echo ($tag["name"]); ?></a>
					</li><?php endforeach; endif; else: echo "" ;endif; ?>
	           </ul>
	       </div>

	<div class="right">
		<?php echo L('welcome_user'); echo ($_SESSION['user_code']); ?>
		 <i>|</i> 
		 [<?php echo ($usergroup); ?>]
		 <i>|</i>
		 <?php if($notlogin == 1): ?>[<a href="<?php echo U('Login/index');?>" target="_top"><?php echo L('login');?></a>]
		 <?php else: ?>
		 [<a href="<?php echo U('Login/logout');?>" target="_top"><?php echo L('logout');?></a>]<?php endif; ?>
	</div>
</div>
<!-- 主导航 end-->
</header>

<!-- header end -->
<!-- Main_content begin -->
<div id="Main_content">

	<div id="MainBox" >
	    <div class="main_box">
			<iframe name="main" id="Main" src='<?php echo U("Index/main");?>' frameborder="false" scrolling="auto"  width="100%" height="auto" allowtransparency="true"></iframe>
		</div>
    </div>

	<div id="leftMenuBox">
    	<div id="leftMenu">
			<div style="padding-left:12px; padding-left:10px;">
				<ul>
					<!-- 一级导航 -->
					<?php if(is_array($menu)): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$level1): $mod = ($i % 2 );++$i;?><li id="nav_<?php echo ($level1['bnav']['id']); ?>">
						<!-- 左部一级菜单  -->
						<?php if(is_array($level1['nav'])): $i = 0; $__LIST__ = $level1['nav'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$level2): $mod = ($i % 2 );++$i;?><dl >
							<dt><?php echo ($level2['bnav']['name']); ?></dt>
							<!-- 左部二级菜单 -->
							<?php if(is_array($level2['nav'])): $i = 0; $__LIST__ = $level2['nav'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$level3): $mod = ($i % 2 );++$i;?><dd id="nav_<?php echo ($level3['bnav']['id']); ?>">
								<span onclick="javascript:gourl('<?php echo ($level3['bnav']['id']); ?>','<?php echo U($level3['bnav']['model'].'/'.$level3['bnav']['action'],$level3['bnav']['data']);?>')">
								<a href="<?php echo U($level3['bnav']['model'].'/'.$level3['bnav']['action'],$level3['bnav']['data']);?>" target="main"><?php echo ($level3['bnav']['name']); ?></a>
								</span>
							</dd><?php endforeach; endif; else: echo "" ;endif; ?>
						</dl><?php endforeach; endif; else: echo "" ;endif; ?>
					</li><?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>
			</div>
		</div>


		<div id="Main_drop">
			<a  href="javascript:toggleMenu(1);" class="on"><img src="../Public/images/admin_barclose.gif" width="11" height="60" border="0"  /></a>   
			<a  href="javascript:toggleMenu(0);" class="off" style="display:none;"><img src="../Public/images/admin_baropen.gif" width="11" height="60" border="0"  /></a>  
		</div>
    </div>

</div>
<!-- Main_content end -->
<div id="footer" class="footer" >
	Powered by <a href="" target="_blank">西北师范大学</a>Copyright 2012-2013 </div>

<script language="JavaScript">
if(!Array.prototype.map)
Array.prototype.map = function(fn,scope) {
    var result = [],ri = 0;
    for (var i = 0,n = this.length; i < n; i++){
        if(i in this){
            result[ri++]  = fn.call(scope ,this[i],i,this);
        }
    }
    return result;
};
var getWindowWH = function(){
    return ["Height","Width"].map(function(name){
        return window["inner"+name] ||
        document.compatMode === "CSS1Compat" && document.documentElement[ "client" + name ] || document.body[ "client" + name ]
    });
}
window.onload = function (){
    if(!+"\v1" && !document.querySelector) { //IE6 IE7
        document.body.onresize = resize;
        } else { 
        window.onresize = resize;
    }
    function resize() {
        wSize();
        return false;
    }
}
function wSize(){
    var str=getWindowWH();
    var strs= new Array();
    strs=str.toString().split(",");
    var h = strs[0] - 95;
    $('#leftMenu').height(h);
    $('#Main').height(h); 
}
wSize();

function gourl(n,url){
    $('#leftMenu dl dd').removeClass('on');
    $('#nav_'+n).addClass('on');
    window.main.location.href=url;
}
function toggleMenu(doit){
    if(doit==1){
        $('#Main_drop a.on').hide();
        $('#Main_drop a.off').show();
        $('#MainBox .main_box').css('margin-left','24px');
        $('#leftMenu').hide();
        }else{
        $('#Main_drop a.off').hide();
        $('#Main_drop a.on').show();
        $('#leftMenu').show();
        $('#MainBox .main_box').css('margin-left','224px');
    }
}	

function sethighlight(n) {
    $('.menu li').removeClass('current');
    $('#menu_'+n).addClass('current');
    $('#leftMenu li').hide();
    $('#nav_'+n).show();
    $('#leftMenu dl dd').removeClass('on');
    $('#nav_'+n+' dl').eq(0).children("dd").eq(0).addClass('on');
    url = $('#nav_'+n+' dd a').eq(0).attr('href');
    window.main.location.href= url;
}

sethighlight("<?php echo ($first_nav_id); ?>");
</script>


</body>
</html>