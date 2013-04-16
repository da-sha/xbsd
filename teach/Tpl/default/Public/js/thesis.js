/**
 * 为按钮预留
 */

$(function(){
	$(".thesis_view").die("click") ;
	$(".thesis_view").live("click",function(){
		$.get( $(this).attr("href") , function(data){
			art.dialog.through({
					title:"查看论文选题",
					content: data
				});
		}) ;
		return false ;
	}) ;
	var trySubmit = function()
	{
		url = ACTION ;
		succeed = true ;
		$(".check_input").each(function(i){
			if( $(this).val() != '')
			{
				url += "/"+$(this).attr("name")+"/"+$(this).val() ;
			}
			else
			{
				succeed = false ;
				$(this).addClass( "input_border_red" ) ;
				$(this).one("click", function(){
					$(this).removeClass( "input_border_red" ) ;
				});
				return false ;
			}
		}) ;
		if( succeed == true)
		{
			self.location = url ;
		}
	}
	$("#majorid_id").die( "change" ) ;
	$("#majorid_id").live( "change" , function(){
		$("#year_id").html("<option value=''>---正在努力加载！---</option>") ;
		$.getJSON( APP + "/Major/ajax_get_major_year/majorid/"+$(this).val(), function(data){
			if( data.status == 1 )
			{
				$("#year_id").html("<option value=''>--请选择学年--</option>") ;
				$.each(data.data, function(id,value){
					$("#year_id").append( "<option value='"+value.year+"'>"+value.year+"级"+"</option>");
				})
			}
			else
			{
				$("#year_id").html("<option value=''>---"+data.info+"---</option>") ;
			}
		}) ;
	});
	$(".check_input").die( "change" ) ;
	$(".check_input").live( "change" , trySubmit );
	var approve_button_callback = function( post_url ){
		var win = art.dialog.top;
		$thesis_approve = $(win.document).find("#approve_form_id");
		if( $(win.document).find("#comment").val() == '' )
		{
			$(win.document).find("#comment").select();
			art.dialog.tips("评审评语不能为空") ;
		}else{
			try{
				url = URL + post_url ;
				$.post( url , $thesis_approve.serialize(), function( ajaxdata ){
					if( ajaxdata.status == 1 )
					{
						art.dialog.opener.setTimeout("window.location.reload();", 500);
						art.dialog.tips(ajaxdata.info,0.5);
					}
					else{
						art.dialog.tips(ajaxdata.info,0.5);
					}
				}, 'json') ;
			}catch( e ){
				art.dialog.tips( e.message );
			}
			finally{
				this.close() ;
			}
		}
	}
	$(".approve_dialog").die( "click" ) ;
	$(".approve_dialog").live( "click" , function(){
		if( $(this).attr("operator") == "dean")
		{
			approve = "/dean_comment_approve" ;
			not_approve = "/dean_comment_not_approve" ;
		}
		else
		{
			approve = "/do_approve" ;
			not_approve = "/do_not_approve" ;
		}
		$.get( $(this).attr("href") , function(data){
			art.dialog.through({
					title:"审核论文选题",
					content: data,
					button: [{
						name: '同意',
						callback: function () {
							approve_button_callback.call(this,approve);
							return false ;
						},
						focus: true
					},{
						name: '不同意',
						callback: function () {
							art.dialog.confirm("确认不同意", function(){
								approve_button_callback.call(this,not_approve);
							});
							return false ;
						}
					}]
				});
		}) ;
		return false ;
	}) ;
	$(".withdraw_title").die( "click") ;
	$(".withdraw_title").live( "click" , function(){
		art.dialog.confirm("确认退选", function(){
			$.getJSON( URL+ "/withdraw/thesisid/" + $("#mythesis_id").attr("mythesis_id") , function(data){
				if( data.status == 1 )
				{
					art.dialog.tips( data.info+"1秒后刷新页面！" ) ;
					setTimeout(function() {
						self.location.reload()
					}, 1000);
				}
				else
				{
					alert( data.data ) ;
					art.dialog.tips( data.info ) ;
				}
			}) ;
		});
	}) ;
	$(".thesis_do_select").die( "click" ) ;
	$(".thesis_do_select").live( "click" , function(){
		$.get( $(this).attr("href") , function(data){
			dlg = art.dialog.through({
				title:"查看论文选题",
				content: data,
				button:[
					{
						name:"选修" ,
						disabled: true,
						callback:function(){
							var top_doc = art.dialog.top.document ;
							url = URL + "/do_select/thesisid/" + $(top_doc).find("#view_thesis_title").attr("thesis_id") ;
							$.getJSON(url, function( data ){
								if( data.status == 1 )
								{
									art.dialog.tips( data.info+"1秒后刷新页面！" ) ;
									setTimeout(function() {
										self.location.reload()
									}, 1000);
								}
								else{
									art.dialog.tips( data.info ) ;
								}
							}) ;
						}
					}
				],
				cancelVal: '关闭',
				cancel: true 
			});
			var top_doc = art.dialog.top.document ;
			if( $(top_doc).find("#have_pass").val()  )
			{
				dlg.button({
					name:"选修" ,
					disabled: false,
					focus: true
				}) ;
			}
		}) ;
		return false ;
	}) ;
	function review( jq_a )
	{
		$.getJSON( jq_a.attr("href"), function(data){
			if( data.status == 1 )
			{
				art.dialog.tips( data.info ) ;
				that_div = jq_a.closest("div") ;
				that_div.prev("div").find("span").html(data.data) ;
				that_div.empty() ;
			}
			else
			{
				alert( data.data ) ;
				art.dialog.tips( data.info ) ;
			}
		}) ;
	}
	$(".dasha_right a").die( "click") ;
	$(".dasha_right a").live( "click" , function(){
		that_a = $(this) ;
		if( that_a.attr("state") == "fail" )
		{
			art.dialog.confirm("确认不通过", function(){
				review( that_a ) ;
			}) ;
		}
		else{
			review( that_a ) ;
		}
		return false ;
	}) ;
	$(".print").click(function(){
		this_form  = $(this).closest("form") ;
		this_form.find("input.content").val( $(".want_print").html() ) ;
		this_form.submit() ;
	}) ;
	$("*").ajaxError( function( event,request, settings ){
		art.dialog.tips("又出错了，请检查网络！", 2);
	}) ;
	
}) ;