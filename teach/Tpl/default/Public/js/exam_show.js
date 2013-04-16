$(function(){
	//函数区
	function checkCondition(){
		if( $("#select_year_id").val() != "" && $("#select_semester_id").val() != "" )
		{
			return true ;
		}
		else{
			return false ;
		}
	}
	function getOption( value )
	{
		var str = "<option value='"+ value.teachid+"'";
		str = str + " val_teachid='"+ value.teachid+"'";
		str = str + " val_courseid='"+ value.courseid+"'";
		str = str + " val_num='"+ value.num+"'";
		str = str + ">"+value.coursename+"("+value.num+")-"+value.classname+"</option>" ;
		return str ;
	}
	function getLimitNum( curdiv )
	{
		var number =0 ; 
		if( !isNaN(curdiv.find("a").attr("val_num")) )
		{
			number = parseInt( curdiv.find("a").attr("val_num") ) ;
		}
		var allnum = parseInt( curdiv.closest("tr").find(".room_num").val() ) ;
		return allnum - number ;
	}

	function recal( aoption )
	{
		aoption.text( aoption.attr("val_coursename")+"("+aoption.attr("val_num")+"人)" );
	}
	function OToD( aOption , aDiv , tips )
	{
		var limitnum = getLimitNum( aDiv );
		var optionnum = parseInt( aOption.attr("val_num") );
		limitnum = Math.min( limitnum , optionnum) ;
		if( limitnum <= 0 )
		{
			if(tips == "T")
			{
				art.dialog.tips("该教室容量已满！") ;
			}
			return ;
		}
		var alink = aDiv.find("a") ;
		if( alink.size() > 0 )
		{
			if( alink.attr("val_courseid") == aOption.attr("val_courseid") )
			{
				alink.attr("val_num" , limitnum + parseInt( alink.attr("val_num") ) ) ;
				recal( alink ) ;
			}else{
				if(tips == "T")
				{
					art.dialog.tips("同一个教室无法安排不同考试！") ;
				}
				limitnum = 0 ;
			}
		}
		else{
			var str = "<a href='#'";
			str = str + " val_courseid='"+ aOption.attr("val_courseid")+"'";
			str = str + " val_num='"+ limitnum+"'";
			str = str + " val_coursename='"+ aOption.attr("val_coursename")+"'";
			str = str + "></a>" ;
			alink = $(str) ;
			recal( alink ) ;
			alink.appendTo(aDiv) ;
		}
		aOption.attr("val_num" , optionnum-limitnum) ;
		recal( aOption ) ;
	}
	function isSuccess()
	{
		var success = true;
		if( $("#teach_course select option").size() == 0 )
		{
			art.dialog.tips("没有选择课程！") ;
			success = false ;
		}
		$("#teach_course select option").each(function(){
			if( $(this).attr("val_num") > 0 )
			{
				success = false ;
				art.dialog.tips("教室容量不足！") ;
				return false ;
			}
		});
		return success ;
	}
	$("#exam_data").datepicker();
	$(".class_name:first").addClass("aam_cur_input") ;
	if($("#select_year_id").val() != "" && $("#select_semester_id").val()!= "" )
	{
		$.get( URL+"/examcourse/teachyear/"+$("#select_year_id").val()+"/teachquarter/"+$("#select_semester_id").val() , function(data){
			dialog = art.dialog({
				title: "考试课程",
				show:false,
				close:function () {
					this.hide();
					return false;
				},
				button: [
					{
						name: '确定',
						callback: function () {
							$("#teach_course select").empty() ;
							$(".class_name a").remove() ;
							$("#chosen_list option").each(function(){
								var cur_course = $("#teach_course select option[val_courseid="+$(this).attr("val_courseid")+"]") ;
								var data = $(this).data("data") ;
								if( cur_course.size() > 0 )
								{
									cur_course.attr("val_num", parseInt(cur_course.attr("val_num")) + parseInt(data.num) ) ;
								}else{
									$(this).clone(false).removeAttr("val_teachid").attr("val_coursename", data.coursename).appendTo( $("#teach_course select") ) ;
								}
								recal($("#teach_course select option:last")) ; 
							}) ;
							this.hide() ;
							return false;
						},
						focus: true
					}],
				content:data,
				top:'10%',
				left:'10%'
			}) ;
		});
	}
	$(".condition").change(function(){
		if( checkCondition() )
		{
			self.location = URL+"/show/year/"+$("#select_year_id").val()+"/semester/"+$("#select_semester_id").val()
		}
	}) ;
	$("#add_course").click( function(){
		if(checkCondition())
		{
			dialog.show() ;
		}else{
			art.dialog.tips("请首先选择相应学期！") ;
		}
	}) ;
	$(".class_name").click(function(){
		$(".aam_cur_input").removeClass("aam_cur_input") ;
		$(this).addClass("aam_cur_input");
	}) ;
	$(".sub_room_num").click(function(){
		next_input = $(this).next(":input") ;
		if( next_input.val() == "" || isNaN( next_input.val() ) || parseInt(next_input.val()) < 0 )
		{
			next_input.val( 0 ) ;
		}
		if( parseInt(next_input.val()) > 0 )
		{
			next_input.val( parseInt(next_input.val()) - 1 ) ;
		}
	}) ;
	$(".add_room_num").click(function(){
		var next_input = $(this).prev(":input") ;
		if( next_input.val() == "" || isNaN( next_input.val() ) || parseInt(next_input.val()) < 0 )
		{
			next_input.val(0) ;
		}
		next_input.val( parseInt(next_input.val() ) + 1 ) ;
	}) ;
	$("#set_room_num").click(function(){
		$("table input.room_num").val( $(this).siblings("input.room_num").val() ) ;
	}) ;
	$("#auto_arrange").click(function(){
		$("#teach_course select option").each(function(){
			var cur_option = $(this) ;
			$(".class_name").each(function(){
				OToD( cur_option , $(this) , "F" ) ;
				if( cur_option.attr("val_num") <= 0 )
				{
					return false ;
				}
			}) ;
		}) ;
		if( isSuccess() == true )
		{
			art.dialog.tips("自动安排成功！") ;
		}
		else{
		}
	}) ;
	$("#submit_all").click(function(){
		var postdata ;
		var temp  = {} ;
		temp.arrange = new Array();
		if( isSuccess() )
		{
			if( $("#exam_data").val().length > 0 )
			{
				postdata = $("#submit_form").serialize() ;
				var one_info= {} ;
				var alink ;
				$(".class_name").each(function(){
					alink = $(this).find("a") ;
					if( alink.attr("val_num") > 0 ){
						one_info= {}
						one_info.roomid = $(this).attr("val_roomid") ;
						one_info.num = alink.attr("val_num") ;
						one_info.coursename = alink.attr("val_coursename") ;
						one_info.courseid = alink.attr("val_courseid") ;
						temp.arrange.push( one_info ) ;
					}
				}) ;
				postdata =postdata +"&"+ $.param(temp) ;
				$.post(URL+"/arrange_exam", postdata, function(data){
					art.dialog.tips(data.info) ;
					setTimeout(function() {
						self.location = URL+"/index/year/"+YEAR+"/semester/"+SEMESTER ;
					}, 1000);
				},"json");
			}else{
				art.dialog.tips("对不起，请选择考试日期！") ;
			}
		}
		else{
		}

	}) ;
	//动态事件绑定区域
	$("#teach_course select").unbind() ;
	$("#teach_course select").dblclick(function(){
		if( $(this).find("option:selected").size() > 0 )
		{
			OToD( $(this).find("option:selected") , $(".aam_cur_input") , "T" ) ;
		}
	}) ;

	$("#course_id").die("change") ;
	$("#course_id").live( "change" , function(){
		if( $(this).val() != "" )
		{
			var closrForm = $(this).closest("form") ;
			$.post( closrForm.attr("action"), closrForm.serialize(), function(data){
				if( data.status == 1 )
				{
					$("#teach_list").empty() ;
					$.each(data.data, function(id,value){
						$( getOption(value) ).data("data", value).appendTo("#teach_list") ;
					})
				}else{
					alert(data.info) ;
				}
			},"json") ;
		}
	}) ;
	$("#teach_list").die("dblclick") ;
	$("#teach_list").live("dblclick",function(){
		if( $("#chosen_list option[value="+$(this).val()+"]").size() == 0 )
		{
			$(this).find("option:selected").clone(false)
			.data("data" , $(this).find("option:selected").data("data"))
			.appendTo($("#chosen_list")) ;
		}
	});
	$("#chosen_list").die("dblclick") ;
	$("#chosen_list").live("dblclick",function(){
		$(this).find("option:selected").remove() ;
	});
	$("#select_teach").die("click") ;
	$("#select_teach").live("click",function(){
		$("#teach_list option:selected").each(function(){
			if( $("#chosen_list option[value="+$(this).val()+"]").size() == 0 )
			{
				$(this).clone(false)
				.data("data" , $(this).data("data"))
				.appendTo( $("#chosen_list") ) ;
			}
		});
	}) ;
	$("#unselect_teach").die("click") ;
	$("#unselect_teach").live("click",function(){
		$("#chosen_list option:selected").remove() ;
	}) ;
	$(".class_name a").die("dblclick") ;
	$(".class_name a").live("dblclick",function(){
		var vur_num = parseInt( $(this).attr("val_num") ) ;
		var cur_course = $("#teach_course select option[val_courseid="+$(this).attr("val_courseid")+"]") ;
		if( cur_course.size() > 0 )
		{
			cur_course.attr("val_num" , vur_num + parseInt( cur_course.attr("val_num") ) ) ;
			recal(cur_course) ;
			$(this).remove() ;
		}
		else{
			alert("error") ;
		}
	}) ;
}) ;