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


<link rel="stylesheet" href="../Public/css/aa.css"/>
<script src="../Public/js/aa.js"></script>
<script type="text/javascript">
	$(function(){
		function checkCondition(){
			if( $("#select_year_id").val() != "" && $("#select_semester_id").val() != "" )
			{
				return true ;
			}
			else{
				return false ;
			}
		}
		$(".condition").change(function(){
			if( checkCondition() )
			{
				self.location = "__URL__/<?php echo ($_GET['_URL_'][1]); ?>/year/"+$("#select_year_id").val()+"/semester/"+$("#select_semester_id").val()
			}
		}) ;
	}) ;
</script>
<fieldset>
	<div class="mainbox">
		请选择：
		<select name="year" class="condition" id="select_year_id">
			<option value="">---请选择---</option>
			<?php if(is_array($year_list)): $i = 0; $__LIST__ = $year_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$year): $mod = ($i % 2 );++$i; if($year == $_GET['year']): ?><option value="<?php echo ($year); ?>" selected><?php echo ($year); ?></option>
					<?php else: ?>
					<option value="<?php echo ($year); ?>"><?php echo ($year); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
		</select>
		<select name="semester" class="condition" id="select_semester_id">
			<option value="">---请选择---</option>
			<?php if(is_array($semester_list)): $i = 0; $__LIST__ = $semester_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$semester): $mod = ($i % 2 );++$i; if($semester['id'] == $_GET['semester']): ?><option value="<?php echo ($semester['id']); ?>" selected><?php echo ($semester['name']); ?></option>
					<?php else: ?>
					<option value="<?php echo ($semester['id']); ?>"><?php echo ($semester['name']); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
		</select>
	</div>
	<div class="table-list">
		<table width="100%" cellspacing="0">
			<thead>
				<tr>
					<th align="left">考试日期</th>
					<th align="left">考试时间</th>
					<th align="left">科目</th>
					<th align="left">考试类型</th>
					<th align="left">考场</th>
					<th align="left">考试人数</th>
					<th align="left">主考</th>
					<th align="left">监考</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
			<?php if(is_array($exam_list)): $i = 0; $__LIST__ = $exam_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$exam): $mod = ($i % 2 );++$i;?><tr>
					<td rowspan="<?php echo ($exam['count']); ?>"><?php echo ($exam['date']); ?></td>
					<td rowspan="<?php echo ($exam['count']); ?>"><?php echo ($exam['timename']); ?></td>
					
					<?php if(is_array($exam['info'])): $j = 0; $__LIST__ = $exam['info'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$info): $mod = ($j % 2 );++$j; if($j != 1): ?><tr><?php endif; ?>
						<td><?php echo ($info['coursename']); ?></td>
						<td><?php echo ($info['typename']); ?></td>
						<td><?php echo ($info['roomname']); ?></td>
						<td><?php echo ($info['num']); ?></td>
						<td>
							<?php echo ($info['examiner1']); ?>
						</td>
						<td>
							<?php echo ($info['examiner2']); ?>
						</td>
					<?php if($j == 1): ?><td rowspan="<?php echo ($exam['count']); ?>" align="center">
							<?php if($info['flag'] == 0): ?><a href="__URL__/arrange_teacher/year/<?php echo ($_GET['year']); ?>/semester/<?php echo ($_GET['semester']); ?>/date/<?php echo ($exam['date']); ?>/time/<?php echo ($exam['time']); ?>" class="green">安排监考</a>
							<?php else: ?>
								<a href="__URL__/delete_exam/year/<?php echo ($_GET['year']); ?>/semester/<?php echo ($_GET['semester']); ?>/date/<?php echo ($exam['date']); ?>/time/<?php echo ($exam['time']); ?>" class="red">删除</a><?php endif; ?>
						</td><?php endif; ?>
					</tr><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
			</tbody>
		</table>
		<div id="dasha_footer"><?php echo ($show); ?></div>
	</div>
</fieldset>
</body>
</html>