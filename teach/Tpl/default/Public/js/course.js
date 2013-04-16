/*
*	课程管理对象
*
*/
function CourseManage() {
    /*
    *私有方法
    */
    var getUrl = function($cou_main) {
	    return APP + "/" + $cou_main.attr("data-model") + "/"
             + $cou_main.attr('data-action')
             + "/teachid/" + $cou_main.attr('data-teachid');
    };
    
    var couMenuEvent = function($cou_main) {
        var event = function() {
            $li = $(this);
            var class_name = $li.attr("class");
            /*如果焦点在当前点击的li上面则什么都不做*/
            if (class_name == "on") {
                return;
            } else {
                $li.siblings().removeClass("on");
                $li.addClass("on");

                var url = getUrl($cou_main);
                $cou_main.siblings().slideUp("slow")
                .end()
                .slideDown("slow")
                .siblings().empty();
                load($cou_main);
            }
        }
        return event;
    };

    /*切换到fulmode*/
    var simplemodeEvent = function($simplemode, $fulmode) {
        var event = function() {
            $simplemode.slideUp("slow");
            $fulmode.slideDown("slow");
            $li_on = $fulmode.find("ul li.on");

            if ($li_on.length == 0) {
                $fulmode.find("ul li").first().addClass("on");
                $cou_main = $fulmode.find(".cou_main>div").first();

                load($cou_main);
                $cou_main.slideDown("slow");
            }
        };
        return event;
    };
    
    /*切换到simplemode*/
    var fulmodeEvent = function($simplemode,$fulmode) {
        var event = function() {
            $simplemode.slideDown("slow");
            $fulmode.slideUp("slow");
        };
        return event;
    };
    /*为所有菜单项添加事件*/
    var addMenuEvent = function() {
        $(".cou_list").children().each(function() {
            $list = $(this);
            /*得到cou_list所有孩子的id*/
            var $ul_menu = $list.find(".cou_menu");
            var $cou_main = $list.find(".cou_main>div");
            
            $ul_menu.children().each(function(index) {
                $li = $(this);
                $main = $cou_main.eq(index);
                $li.bind("click", couMenuEvent($main));
                $main.bind("reload",reloadEvent($main));
            });
        });
    };

    /*添加模式切换事件*/
    var addModeEvent = function() {
        $(".cou_list").children().each(function() {
            $list = $(this);

            $simplemode = $list.children(".simplemode");
            $fulmode = $list.children(".fulmode");

            $simplemode.bind("click", simplemodeEvent($simplemode,$fulmode));
            $fulmode.children(".ful_title").bind("click",fulmodeEvent($simplemode,$fulmode));
        });
    };
    var load = function($cou_main){
        var url = getUrl($cou_main);
        $cou_main.load(url,loadFinished);
    };
    var reloadEvent = function($cou_main){
    	var event = function(){
    		load($cou_main);
    	};
    	return event;
    };
    /*编辑对话框，传入编辑对象的内容*/
    var editDialog = function($main_div,obj){
    	obj.title = obj.title || "";
    	obj.width = obj.width || 500;
    	obj.height = obj.height || 600;
    	
    	if (obj.editurl == undefined) {
        	return;
    	}
    	if (obj.posturl == undefined) {
        	return;
    	}
    	
    	var dialog = function(){
    		art.dialog.open(obj.editurl,{
    			title: obj.title,
    			width: obj.width,
    			height: obj.height,
    			button:[{
    				name:"确定",
    				focus: true,
    				callback: function(){
    					$.ajax({
    			    		url:obj.posturl,
    			    		success: function(data, textStatus, jqXHR){
    			    			$main_div.trigger("reload");
    			    			art.dialog.tips(data.info,0.5);
    			    		},
    			    		type: 'POST',
    			    		error: function(XMLHttpRequest, textStatus, errorThrown){
    			    			art.dialog.through({
    			    				title:textStatus,
            	    				content: "发生错误"
    			    			});
    			    		},
    			    		dataType : 'json',
    			    		data:obj.values_callback(this)
    			    	});
    				}
    			}],
    			cancel: true
    		});
    	};
    	return dialog;
    };
    
    /*删除窗口*/
    var deleteDialog = function($cou_main,delete_url){
        var dialog = function () {
    	    art.dialog.confirm("确定删除信息吗?", function(){
    	    	$.ajax({
    	    		url:delete_url,
    	    		success: function(data, textStatus, jqXHR){
    	    			$cou_main.trigger("reload");
    	    			art.dialog.tips(data.info,0.5);
    	    		},
    	    		type: 'GET',
    	    		error: function(XMLHttpRequest, textStatus, errorThrown){
    	    			art.dialog.through({
    	    				title:textStatus,
    	    				content: "发生错误"
    	    			});
    	    		},
    	    		dataType : 'json',
    	    	});
    	    });
        }
        return dialog;
    }
    
    /*排序窗口*/
    var listorderDialog = function ($cou_main,listorder_url,selector) {
        var dialog = function () {
        	$form = $cou_main.find(selector);;
        	values = $form.serialize();
	    	$.ajax({
	    		url:listorder_url,
	    		success: function(data, textStatus, jqXHR){
	    			$cou_main.trigger("reload");
	    			art.dialog.tips(data.info,0.5);
	    		},
	    		type: 'POST',
	    		error: function(XMLHttpRequest, textStatus, errorThrown){
	    			art.dialog.through({
	    				title:textStatus,
	    				content: "发生错误"
	    			});
	    		},
	    		dataType : 'json',
	    		data:values
	    	});
        }
        return dialog;
    }
    
    var valueCallback = function (selector) {
        var inner = function(context){
    	    var iframe = context.iframe.contentWindow;
        	$form = $(iframe.document).find(selector);;
        	values = $form.serialize();
        	return values;
        }
        return inner;
    }
    
    /**
     * 增加插入监听
     */
    var addInsertEvent = function($cou_main){
    	$insert = $cou_main.find("[execution=insert]");
    	
    	var f_insert_dialog = Function();
    	
    	switch($cou_main.attr("class")){
    	case "basic_info":
    		break;
    	case "assessment":
    		f_insert_dialog = editDialog($cou_main,{
            	title:"增加考核",
            	width : 600,
            	height : 150,
            	editurl : APP + "/Assessment/add",
            	posturl : APP + "/Assessment/insert/teachid/" + $cou_main.attr("data-teachid"),
            	values_callback : valueCallback("#assessment_form")
                });
    		break;
    	case "cou_change":
    		f_insert_dialog = editDialog($cou_main,{
            	title:"增加异动",
            	width : 600,
            	height : 500,
            	editurl : APP + "/CourseChange/add",
            	posturl : APP + "/CourseChange/insert/teachid/" + $cou_main.attr("data-teachid"),
            	values_callback : valueCallback("#cou_change_edit")
                });
    		break;
    	case "score":
    		break;
    	case "teachplan":
    		f_insert_dialog = editDialog($cou_main,{
            	title:"增加教学计划",
            	width : 600,
            	height : 400,
            	editurl : APP + "/TeachPlan/add",
            	posturl : APP + "/TeachPlan/insert/teachid/" + $cou_main.attr("data-teachid"),
            	values_callback : valueCallback("#teach_plan_edit")
                });
    		break;
    	}
    	
    	$insert.bind("click",f_insert_dialog);
    };
    
    /**
     * 更新和删除时间监听
     */
    var addUpdateEvent = function ($cou_main) {
        /*选出所有的需有更新和删除操作的对象*/
    	$updates = $cou_main.find("[execution=update]");
    	
    	$updates.each(function (index){
    	    $update = $(this);
    	    
        	item_id = $update.attr("item-id");
        	var f_update_dialog = Function();
        	
        	switch($cou_main.attr("class")){
        	case "basic_info":
        		f_update_dialog = function(){
                    $form = $cou_main.find("#basic_info_edit");;
                    values = $form.serialize();
                    $.ajax({
    	    		url:APP + "/TeacherCourse/update_basic_info",
    	    		success: function(data, textStatus, jqXHR){
    	    			$cou_main.trigger("reload");
    	    			art.dialog.tips(data.info,0.5);
    	    		},
    	    		type: 'POST',
    	    		error: function(XMLHttpRequest, textStatus, errorThrown){
    	    			art.dialog.through({
    	    				title:textStatus,
    	    				content: "发生错误"
    	    			});
    	    		},
    	    		dataType : 'json',
                    data: values
    	    	});
                }
        		break;
        	case "assessment":
        		f_update_dialog = editDialog($cou_main,{
                	title:"修改考核",
                	width : 600,
                	height : 150,
                	editurl : APP + "/Assessment/edit/assessmentid/" + item_id,
                	posturl : APP + "/Assessment/update",
                	values_callback : valueCallback("#assessment_form")
                    });
					break;
        	case "cou_change":
        	    f_update_dialog = editDialog($cou_main,{
            	title:"修改异动",
            	width : 600,
            	height : 500,
            	editurl : APP + "/CourseChange/edit/id/" + item_id,
            	posturl : APP + "/CourseChange/update",
            	values_callback : valueCallback("#cou_change_edit")
                });
        		break;
        	case "score":
        	    f_update_dialog = editDialog($cou_main,{
                	title:"修改成绩",
                	width : 300,
                	height : 500,
                	editurl : APP + "/Score/edit/studentid/" + item_id + "/teachid/" + $cou_main.attr("data-teachid"),
                	posturl : APP + "/Score/update",
                	values_callback : valueCallback("#score_form")
                    });
        		break;
        	case "teachplan":
        	    f_update_dialog = editDialog($cou_main,{
            	title:"修改课程异动",
            	width : 600,
            	height : 400,
            	editurl : APP + "/TeachPlan/edit/id/" + item_id,
            	posturl : APP + "/TeachPlan/update",
            	values_callback : valueCallback("#teach_plan_edit")
                });
        		break;
			case "select_course":
				break;
        	}
        	
        	$update.bind("click",f_update_dialog);
    	});
    }
	var addDeleteEvent = function($cou_main){
    	$deletes = $cou_main.find("[execution=delete]");
		
		$deletes.each(function (index){
    	    $delete = $deletes.eq(index);
    	    
        	item_id = $delete.attr("item-id");
        	var f_delete_dialog = Function();
        	
        	switch($cou_main.attr("class")){
        	case "basic_info":
				break;
        	case "assessment":
                f_delete_dialog = deleteDialog($cou_main,APP + "/Assessment/delete/assessmentid/" + item_id);
        		break;
        	case "cou_change":
                f_delete_dialog = deleteDialog($cou_main,APP + "/CourseChange/delete/id/" + item_id);
        		break;
        	case "score":
        	case "teachplan":
                f_delete_dialog = deleteDialog($cou_main,APP + "/TeachPlan/delete/id/" + item_id);
        		break;
			case "select_course":
				f_delete_dialog = deleteDialog($cou_main,APP + "/SelectCourse/delete/id/" + item_id);
				break;
        	}
        	
        	$delete.bind("click",f_delete_dialog);
    	});
	}
    /*添加排序动作*/
    var addListOrderEvent = function ($cou_main) {
    	$listorder = $cou_main.find("[execution=listorder]");
    	
    	var f_listorder_dialog = Function();
    	
    	switch($cou_main.attr("class")){
    	case "basic_info":
    		break;
    	case "assessment":
            f_listorder_dialog = listorderDialog($cou_main,APP + "/Assessment/listorder","#assess");
    		break;
    	case "cou_change":
    		break;
    	case "score":
    		break;
    	case "teachplan":
            f_listorder_dialog = listorderDialog($cou_main,APP + "/TeachPlan/listorder","#teachplan");
    		break;
    	}
    	
    	$listorder.bind("click",f_listorder_dialog);
    }
    /**
     * 在load操作完成之后，所进行的动作
     */
    var loadFinished = function(){
        $cou_main = $(this);
        addInsertEvent($cou_main);
		addDeleteEvent($cou_main);
        addUpdateEvent($cou_main);
        addListOrderEvent($cou_main);
    };
    /*
    * 公有方法
    */
    this.init = function() {
        addMenuEvent();
        addModeEvent();
        $(".cou_main>div").hide();
        $(".cou_main").each(function() {
            $(this).children().first().show();
        });
    };

}
/*在加入easyui之后*/
function data_formatter(formmat_name){
    var data;

    switch(formmat_name){
        case "level":
            data = level;
            break;
        case "property":
            data = property;
            break;
        case "cou_type":
            data = cou_type;
            break;
        case "exam_type":
            data = exam_type;
            break;
        case "cou_category":
            data = cou_category;
            break;
        case "semester":
            data = semester;
            break;
        case "major":
            data = major;
            break;
        case "admissiondate":
            data = admissiondate;
            break;
        case "teach_workload":
            data = teach_workload;
            break;
    }
    return function (value){
        for(var i=0; i<data.length; i++){
            if (data[i].id == value) return data[i].name;
        }
        return value;
    }
}

jQuery(function($) {
    var courseManage = new CourseManage();
    courseManage.init();
});
