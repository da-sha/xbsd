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



<?php echo define_json('major',$major_list);?>
<?php echo define_json('admissiondate',$admissiondate_list);?>
<?php echo define_json('semester',$semester_list);?>

<?php echo define_json('level',$level_list);?>
<?php echo define_json('property',$course_property_list);?>
<?php echo define_json('cou_type',$course_type_list);?>
<?php echo define_json('exam_type',$exam_list);?>
<?php echo define_json('cou_category',$course_category_list);?>
<script src="../Public/js/course.js"></script>
<script type="text/javascript" src="../Public/js/easyui/plugins/datagrid-detailview.js"></script>

<script>
	/*自动填充约束*/
	function calc(selector,index){
		var editors = $(selector).edatagrid("getEditors",index);
			
		var planweektime_index = 5;
		var expweektime_index = 6;
		var week_index = 7;
		var totaltime_index = 8;
		
		var planweektime_editor = editors[planweektime_index];
		var expweektime_editor = editors[expweektime_index];
		var week_editor = editors[week_index];
		var totaltime_editor = editors[totaltime_index];

		planweektime_editor.target.bind('change', function(){
			calculate();
		});
		expweektime_editor.target.bind('change', function(){
			calculate();
		});
		week_editor.target.bind('change', function(){
			calculate();
		});
		$(week_editor.target).numberbox('setValue',18);	/*默认为18周*/
		
		function calculate(){
			var cost = (planweektime_editor.target.val() * 1 + expweektime_editor.target.val() * 1) * week_editor.target.val();
			$(totaltime_editor.target).numberbox('setValue',cost);
		}
	}
    $(function(){
        $('#major_course_table').edatagrid({
			toolbar:"#tb_major_course",
            destroyMsg:{
                norecord:{	// when no record is selected
                    title:'Warning',
                    msg:'没有选择数据'
                },
                confirm:{	// when select a row
                    title:'Confirm',
                    msg:'你确定要删除吗?'
                }
            },
            singleSelect:true,
            url: '__URL__/get_major_course',  
            saveUrl: '__URL__/major_insert',
            updateUrl: '__URL__/major_update',  
            destroyUrl: '__URL__/major_delete',
			onBeforeSave: function(index){
				if($('#major_course_form').form('validate') == false){
					return false;
				};
			},
			onBeforeEdit: function(rowIndex, rowData){
				rowData.majorid = $("#major_course_form input[name='majorid']").val();
				rowData.admissiondate = $("#major_course_form input[name='admissiondate']").val();
				
				
			},
			onEdit:function(index,row){
				calc('#major_course_table',index);
			},
			onAdd: function(index,row){
				calc('#major_course_table',index);
			}
        });
		$('#academy_course_table').edatagrid({
            toolbar:"#tb_academy_course_add",
            rowStyler: function(index,row){  
                if (row.added == 1){  
                    return 'background-color:#6293BB;color:#fff;font-weight:bold;';  
                }
            },
            destroyMsg:{
                norecord:{	// when no record is selected
                    title:'Warning',
                    msg:'没有选择数据'
                },
                confirm:{	// when select a row
                    title:'Confirm',
                    msg:'你确定要删除吗?'
                }
            },
            singleSelect:true,
            url: '__URL__/get_academy_course',  
            saveUrl: '__URL__/academy_insert',
            updateUrl: '__URL__/academy_update',  
            destroyUrl: '__URL__/academy_delete',
			onBeforeSave: function(index){
				if($('#academy_course_form').form('validate') == false){
					return false;
				};
			},
			onBeforeEdit: function(rowIndex, rowData){
				rowData.admissiondate = $("#academy_course_form input[name='admissiondate']").val();
			},
			onEdit:function(index,row){
				calc('#academy_course_table',index);
			},
			onAdd: function(index,row){
				calc('#academy_course_table',index);
			}
        });
        $('#school_course_table').edatagrid({
            toolbar:"#tb_school_course",
			destroyMsg:{
                norecord:{	// when no record is selected
                    title:'Warning',
                    msg:'没有选择数据'
                },
                confirm:{	// when select a row
                    title:'Confirm',
                    msg:'你确定要删除吗?'
                }
            },
            singleSelect:true,
            url: '__URL__/get_school_course',  
            saveUrl: '__URL__/school_insert',
            updateUrl: '__URL__/school_update',  
            destroyUrl: '__URL__/school_delete',
			onBeforeSave: function(index){
				if($('#school_course_form').form('validate') == false){
					return false;
				};
			},
			onBeforeEdit: function(rowIndex, rowData){
				rowData.admissiondate = $("#school_course_form input[name='admissiondate']").val();
			},
			onEdit:function(index,row){
				calc('#school_course_table',index);
			},
			onAdd: function(index,row){
				calc('#school_course_table',index);
			}
        });
    });
    
</script>
<div id="tt" class="easyui-tabs" style="width:auto;height:auto;">  
    <div title="专业平台课程" data-options="" style="overflow:auto">  
        <table id="major_course_table" style="width:auto;height:auto;overflow:scroll"
            data-options="idField:'courseid',nowrap:true,pageSize:20">
            <thead>
				<tr>
					<th data-options="field:'ck',checkbox:true"></th>
					<th data-options="field:'courseid',width:60,sortable:true,align:'center'">授课ID</th>
					<th data-options="field:'name',width:200,sortable:true,editor:{
						type:'text',
						options:{
						required:true
						}
						}">课程名称</th>
					<th data-options="field:'cou_type',width:80,
						editor:{
						type:'combobox',
						options:{
							valueField:'id',
							textField:'name',
							data:cou_type,
							panelHeight:90,
							required:true,
							editable:false
							}
							},
						formatter:data_formatter('cou_type')
						">课程类型</th>
					<th data-options="field:'property',width:80,
						editor:{
						type:'combobox',
						options:{
							valueField:'id',
							textField:'name',
							data:property,
							panelHeight:90,
							required:true,
							editable:false
							}
							},
						formatter:data_formatter('property')
						">课程性质</th>
					<th data-options="field:'exam_type',width:80,
						editor:{
						type:'combobox',
						options:{
							valueField:'id',
							textField:'name',
							data:exam_type,
							panelHeight:70,
							required:true,
							editable:false
							}
							},
						formatter:data_formatter('exam_type')
						">考试类型</th>
					<th data-options="field:'semester',width:80,
						editor:{
						type:'combobox',
						options:{
							valueField:'id',
							textField:'name',
							data:semester,
							panelHeight:190,
							required:true,
							editable:false
							}
							},
						formatter:data_formatter('semester')
						">开课学期</th>
					<th data-options="field:'planweektime',width:80,
						editor:{
						type:'numberbox',
						options:{
							required:true
							}
						}
						">理论周学时</th>
					<th data-options="field:'expweektime',width:80,
						editor:{
						type:'numberbox',
						options:{
							required:true
							}
						}
						">实验周学时</th>
					<th data-options="field:'week',width:80,
						editor:{
						type:'numberbox',
						options:{
							required:true
							}
						}
						">开设周数</th>
					<th data-options="field:'totaltime',width:80,
						editor:{
						type:'numberbox',
						options:{
							required:true
							}
						}
						">总学时</th>
					<th data-options="field:'credit',width:80,
						editor:{
						type:'numberbox',
						options:{
							precision:1,
							required:true
							}
						}
						">学分</th>
				</tr>
			</thead>
        </table>
        <div id="tb_major_course" style="padding:5px;height:auto">
            <form id="major_course_form">
				专业<input name="majorid" class="easyui-combobox" style="width:170px"  
				data-options="data:major,valueField:'majorid',textField:'name',panelHeight:200,required:true,editable:false" />
				年级<input name="admissiondate" class="easyui-combobox" style="width:70px"  
				data-options="data:admissiondate,valueField:'id',textField:'name',panelHeight:110,required:true,editable:false"/>
				<a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="
					if($('#major_course_form').form('validate')){
						$('#major_course_table').datagrid('load',$('#major_course_form').serializeJson())
					};
					" plain="true" title="查看指定专业的计划课程">查看</a>
				<a href="#" class="easyui-linkbutton" onclick="
					if($('#major_course_form').form('validate')){
						$('#major_course_table').datagrid('load',$('#major_course_form').serializeJson())
					};
					" plain="true" title="查看指定专业的计划课程">复用往届课程</a>
				<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#major_course_table').edatagrid('addRow')">增加</a>  
				<a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#major_course_table').edatagrid('saveRow')">保存</a>  
				<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:$('#major_course_table').edatagrid('destroyRow')">删除</a>  
				<a href="#" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#major_course_table').edatagrid('cancelRow')">撤销</a>
			</form>
        </div>  
    </div>  
	<div title="学院平台课程" style="">
		<table id="academy_course_table" style="width:auto;height:auto;overflow:scroll"
            data-options="idField:'courseid',nowrap:true,pageSize:20">
            <thead>
				<tr>
					<th data-options="field:'ck',checkbox:true"></th>
					<th data-options="field:'courseid',width:60,sortable:true,align:'center'">授课ID</th>
					<th data-options="field:'name',width:200,sortable:true,editor:{
						type:'text',
						options:{
						required:true
						}
						}">课程名称</th>
					<th data-options="field:'cou_type',width:80,
						editor:{
						type:'combobox',
						options:{
							valueField:'id',
							textField:'name',
							data:cou_type,
							panelHeight:90,
							required:true,
							editable:false
							}
							},
						formatter:data_formatter('cou_type')
						">课程类型</th>
					<th data-options="field:'property',width:80,
						editor:{
						type:'combobox',
						options:{
							valueField:'id',
							textField:'name',
							data:property,
							panelHeight:90,
							required:true,
							editable:false
							}
							},
						formatter:data_formatter('property')
						">课程性质</th>
					<th data-options="field:'exam_type',width:80,
						editor:{
						type:'combobox',
						options:{
							valueField:'id',
							textField:'name',
							data:exam_type,
							panelHeight:70,
							required:true,
							editable:false
							}
							},
						formatter:data_formatter('exam_type')
						">考试类型</th>
					<th data-options="field:'semester',width:80,
						editor:{
						type:'combobox',
						options:{
							valueField:'id',
							textField:'name',
							data:semester,
							panelHeight:190,
							required:true,
							editable:false
							}
							},
						formatter:data_formatter('semester')
						">开课学期</th>
					<th data-options="field:'planweektime',width:80,
						editor:{
						type:'numberbox',
						options:{
							required:true
							}
						}
						">理论周学时</th>
					<th data-options="field:'expweektime',width:80,
						editor:{
						type:'numberbox',
						options:{
							required:true
							}
						}
						">实验周学时</th>
					<th data-options="field:'week',width:80,
						editor:{
						type:'numberbox',
						options:{
							required:true
							}
						}
						">开设周数</th>
					<th data-options="field:'totaltime',width:80,
						editor:{
						type:'numberbox',
						options:{
							required:true
							}
						}
						">总学时</th>
					<th data-options="field:'credit',width:80,
						editor:{
						type:'numberbox',
						options:{
							required:true,
							precision:1
							}
						}
						">学分</th>
				</tr>
			</thead>
        </table>
        <div id="tb_academy_course_add" style="padding:5px;height:auto">  
			 <form id="academy_course_form">
				年级<input name="admissiondate" class="easyui-combobox" style="width:70px"  
				data-options="data:admissiondate,valueField:'id',textField:'name',panelHeight:110,required:true,editable:false"/>
				<a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="
					if($('#academy_course_form').form('validate')){
						$('#academy_course_table').datagrid('load',$('#academy_course_form').serializeJson())
					};
					" plain="true" title="查看指定专业的计划课程">查看</a>
				<a href="#" class="easyui-linkbutton" onclick="
					if($('#academy_course_form').form('validate')){
						$('#academy_course_table').datagrid('load',$('#academy_course_form').serializeJson())
					};
					" plain="true" title="查看指定专业的计划课程">复用往届课程</a>
				<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#academy_course_table').edatagrid('addRow')">增加</a>  
				<a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#academy_course_table').edatagrid('saveRow')">保存</a>  
				<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:$('#academy_course_table').edatagrid('destroyRow')">删除</a>  
				<a href="#" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#academy_course_table').edatagrid('cancelRow')">撤销</a>  
			</form>
        </div>  
	</div>
    <div title="学校平台课程" data-options="" style="overflow:auto">  
        <table id="school_course_table" style="width:auto;height:auto;overflow:scroll"
            data-options="idField:'courseid',nowrap:true,pageSize:20">
             <thead>
				<tr>
					<th data-options="field:'ck',checkbox:true"></th>
					<th data-options="field:'courseid',width:60,sortable:true,align:'center'">授课ID</th>
					<th data-options="field:'name',width:200,sortable:true,editor:{
						type:'text',
						options:{
						required:true
						}
						}">课程名称</th>
					<th data-options="field:'cou_type',width:80,
						editor:{
							type:'combobox',
							options:{
								valueField:'id',
								textField:'name',
								data:cou_type,
								panelHeight:90,
								required:true,
								editable:false
							}
						},
						formatter:data_formatter('cou_type')
						">课程类型</th>
					<th data-options="field:'property',width:80,
						editor:{
						type:'combobox',
						options:{
							valueField:'id',
							textField:'name',
							data:property,
							panelHeight:90,
							required:true,
							editable:false
							}
							},
						formatter:data_formatter('property')
						">课程性质</th>
					<th data-options="field:'exam_type',width:80,
						editor:{
						type:'combobox',
						options:{
							valueField:'id',
							textField:'name',
							data:exam_type,
							panelHeight:70,
							required:true,
							editable:false
							}
							},
						formatter:data_formatter('exam_type')
						">考试类型</th>
					<th data-options="field:'semester',width:80,
						editor:{
						type:'combobox',
						options:{
							valueField:'id',
							textField:'name',
							data:semester,
							panelHeight:190,
							required:true,
							editable:false
							}
							},
						formatter:data_formatter('semester')
						">开课学期</th>
					<th data-options="field:'planweektime',width:80,
						editor:{
						type:'numberbox',
						options:{
							required:true
							}
						}
						">理论周学时</th>
					<th data-options="field:'expweektime',width:80,
						editor:{
						type:'numberbox',
						options:{
							required:true
							}
						}
						">实验周学时</th>
					<th data-options="field:'week',width:80,
						editor:{
						type:'numberbox',
						options:{
							required:true
							}
						}
						">开设周数</th>
					<th data-options="field:'totaltime',width:80,
						editor:{
						type:'numberbox',
						options:{
							required:true
							}
						}
						">总学时</th>
					<th data-options="field:'credit',width:80,
						editor:{
						type:'numberbox',
						options:{
							required:true,
							precision:1
							}
						}
						">学分</th>
				</tr>
			</thead>
        </table>
        <div id="tb_school_course" style="padding:5px;height:auto">  
            <div>
                <form id="school_course_form">
					年级<input name="admissiondate" class="easyui-combobox" style="width:70px"  
						data-options="data:admissiondate,valueField:'id',textField:'name',panelHeight:110,required:true,editable:false"/>
						<a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="
							if($('#school_course_form').form('validate')){
								$('#school_course_table').datagrid('load',$('#school_course_form').serializeJson())
							};
							" plain="true" title="查看指定专业的计划课程">查看</a>
						<a href="#" class="easyui-linkbutton" onclick="
							if($('#school_course_form').form('validate')){
								$('#school_course_table').datagrid('load',$('#school_course_form').serializeJson())
							};
							" plain="true" title="查看指定专业的计划课程">复用往届课程</a>
						<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#school_course_table').edatagrid('addRow')">增加</a>  
						<a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#school_course_table').edatagrid('saveRow')">保存</a>  
						<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:$('#school_course_table').edatagrid('destroyRow')">删除</a>  
						<a href="#" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#school_course_table').edatagrid('cancelRow')">撤销</a>  
                </form>
				
            </div>  
        </div>  
    </div>
</div>



</body>
</html>