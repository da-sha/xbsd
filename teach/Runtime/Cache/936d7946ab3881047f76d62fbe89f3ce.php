<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo C('DEFAULT_CHARSET');?>" />
        <title><?php echo L('welcome');?></title>
        <link rel="stylesheet" type="text/css" href="../Public/js/easyui/themes/default/easyui.css">
        <link rel="stylesheet" type="text/css" href="../Public/js/easyui/themes/icon.css">
        <link rel="stylesheet" type="text/css" href="../Public/css/style.css" /> 

        <script type="text/javascript" src="../Public/js/jquery.min.js"></script> 
        <script type="text/javascript" src="../Public/js/jquery.artDialog.js?skin=default"></script> 
        <script type="text/javascript" src="../Public/js/iframeTools.js"></script>
        <script type="text/javascript" src="../Public/js/jquery.form.js"></script> 
        <script type="text/javascript" src="../Public/js/jquery.validate.js"></script> 
        <script type="text/javascript" src="../Public/js/MyDate/WdatePicker.js"></script> 
        <script type="text/javascript" src="../Public/js/jquery.colorpicker.js"></script> 
        <script type="text/javascript" src="../Public/js/my.js"></script> 
        <script type="text/javascript" src="../Public/js/global.js"></script> 
        <script type="text/javascript" src="../Public/js/jquery.autocomplete.js"></script> 
        <script type="text/javascript" src="../Public/js/swfupload.js"></script> 
        <script type="text/javascript" src="../Public/js/easyui/jquery.easyui.min.js"></script>
        <script type="text/javascript" src="../Public/js/easyui/locale/easyui-lang-zh_CN.js"></script>
        <script type="text/javascript" src="../Public/js/json.js"></script> 
        <script type="text/javascript" src="../Public/js/easyui/plugins/jquery.edatagrid.js"></script> 

    </head>
    <script language="JavaScript">
        <!--
        var ROOT =	 '__ROOT__';
        var URL = '__URL__';
        var APP	 =	 '__APP__';
        var PUBLIC = '__PUBLIC__';
        var ACTION = '__ACTION__' ;
        //-->
    </script>
        <style>
        .combobox-item:hover{
            cursor:pointer;
            background-color: #429cc7;
        }
    </style>
    <body width="100%">
        <div id="loader" ><?php echo L('load_page');?></div>
        <div id="result" class="result none"></div>
        <div class="mainbox">
            <div id="nav" class="mainnav_title">
                <ul>
                    <?php if($default_nav == 1): ?><a href="<?php echo U($nav[bnav][model].'/'.$nav[bnav][action],$nav[bnav][data]);?>" id="child_nav_<?php echo ($nav["bnav"]["id"]); ?>"><?php echo ($nav["bnav"]["name"]); ?></a>|<?php endif; ?>
                    <?php if(is_array($nav["nav"])): $i = 0; $__LIST__ = $nav["nav"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vonav): $mod = ($i % 2 );++$i;?><a href="<?php echo U($vonav[model].'/'.$vonav[action],$vonav[data]);?>" id="child_nav_<?php echo ($nav["bnav"]["id"]); ?>"><?php echo ($vonav["name"]); ?></a>|<?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
            <script>
                //|str_replace=__ROOT__.'/index.php','',###
                var onurl ='<?php echo ($_SERVER["REQUEST_URI"]); ?>';
                jQuery(document).ready(function(){
                    $('#nav ul a ').each(function(i){
                        if($('#nav ul a').length>1){
                            var thisurl= $(this).attr('href');
                            thisurl = thisurl.replace('&menuid=<?php echo cookie("menuid");?>','');
                            if(onurl.indexOf(thisurl) == 0 ){
                                $(this).addClass('on').siblings().removeClass('on');
                            }
                        }else{
                        $('#nav ul').hide();
                        }
                    });
                    if($('#nav ul a ').hasClass('on')==false){
                        $('#nav ul a ').eq(0).addClass('on');
                    }
                });
            </script>


<script type="text/javascript" src="../Public/js/thesis.js"></script>
<div class="mainbox">
	请选择：
	<select class="check_input limit_width" name="majorid" id="majorid_id">
		<option value="">--请选择专业--</option>
		<?php if(is_array($major_list)): $i = 0; $__LIST__ = $major_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ma): $mod = ($i % 2 );++$i; if($_GET['majorid'] == $ma[majorid]): ?><option value="<?php echo ($ma['majorid']); ?>" selected><?php echo ($ma[name]); ?></option>
				<?php else: ?>
				<option value="<?php echo ($ma['majorid']); ?>"><?php echo ($ma[name]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
	</select>
	<select class="check_input" name="year" id="year_id">
		<option value="">--请选择学年--</option>
		<?php if(is_array($year_list)): $i = 0; $__LIST__ = $year_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$year): $mod = ($i % 2 );++$i; if($_GET['year'] == $year['id']): ?><option value="<?php echo ($year['id']); ?>" selected><?php echo ($year['name']); ?></option>
				<?php else: ?>
				<option value="<?php echo ($year['id']); ?>"><?php echo ($year['name']); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
	</select>
	<select class="check_input" id="result_id" name="result">
		<option value="">---请选择论文状态---</option>
		<?php if(is_array($result_list)): $i = 0; $__LIST__ = $result_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$result): $mod = ($i % 2 );++$i; if($_GET['result'] == $result['id']): ?><option value="<?php echo ($result['id']); ?>" selected><?php echo ($result['name']); ?></option>
				<?php else: ?>
				<option value="<?php echo ($result['id']); ?>"><?php echo ($result['name']); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
	</select>
	<input class="check_input" type="hidden" name="category" id="category_value" value="<?php echo ($_GET['category']); ?>">
</div>
<div class="table-list">
	<table width="100%" cellspacing="0">
		<thead>
			<tr>
				<th align="left" >题目名称</th>
				<th>类别</th>
				<th>针对年级</th>
				<th>针对专业</th>
				<th>学生层次</th>
				<th>已选/限选</th>
				<th>评审</th>
				<th>管理操作</th>
			</tr>
		</thead>
		<tbody>
			<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><tr>
				<td align="left"><?php echo ($data[name]); ?></td>
				<td align="center"><?php echo ($data[type]); ?></td>
				<td align="center"><?php echo ($data[grade]); ?></td>
				<td align="center"><?php echo ($data[majorid]); ?></td>
				<td align="center"><?php echo ($data[level]); ?></td>
				<td align="center"><?php echo ($data['select_num']); ?>/<?php echo ($data['max_num']); ?></td>
				<td align="center">
					<?php if($data['result'] == $thesis_result_failed): ?><span class="red"><?php echo ($data['result_name']); ?></span>
					<?php else: ?>
					<span class="green"><?php echo ($data[result_name]); ?></span><?php endif; ?>
				</td>
				<td align="center">
					<?php if($data['result'] == $thesis_result_wait): ?><a class="green approve_dialog" class href="__URL__/approve/thesisid/<?php echo ($data[thesisid]); ?>/category/<?php echo ($category); ?>">审核</a>
					<?php else: ?>
						<a class="blue thesis_view" href="__URL__/view/thesisid/<?php echo ($data[thesisid]); ?>">查看</a><?php endif; ?>
				</td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</tbody>
	</table>
	<div id="dasha_footer"><?php echo ($show); ?></div>
</div>
<style type="text/css">
<!--
.select * {
margin: 0;
padding: 0;
}
.select {
border:1px solid #cccccc;
float: left;
display: inline;
}

.select div {
border:1px solid #f9f9f9;
float: left;
}
/* 子选择器，在FF等非IE浏览器中识别 */
.select>div {
width:120px;
height: 17px;
overflow:hidden;
}

/* 通配选择符，只在IE浏览器中识别 */
* html .select div select {
display:block;
float: left;
margin: -2px;
}
.select div>select {
display:block;
width:124px;
float:none;
margin: -2px;
padding: 0px;
}
.select:hover {
border:1px solid #666666;
}
.select select>option {
text-indent: 2px;
}
-->
</style>
</body>
</html>