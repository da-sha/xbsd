/* ------------------------------------------------------------
 * 日期		：2012-10-7
 * -----------------------------------------------------------
 * 创建人	：大傻 lilei.zh@qq.com
 * -----------------------------------------------------------
 * 文件说明	; dasha_chosen.js UTF-8
 * -----------------------------------------------------------
 */
$(document).ready(function(){
	$("body").prepend("<div class='dasha_chosen_content'><table cellpadding='0' cellspacing='0'><tbody><tr><td><div class='dasha_chosen_title'><div class='dasha_chosen_tilte_text'>"+titletip+"</div><div class='dasha_chosen_close'><a href='#'>"+closetip+"</a></div></div></td></tr><tr><td height='200' align='center' valign='middle'><select id='dasha_chosen_user' size='12'></select></td></tr></tbody></table></div>");
	$(".dasha_chosen_content").hide() ;
	$("#dasha_chosen_user").empty();
	$("#dasha_chosen_user").append( "<option value=''>"+trytip+"</option>");
	$.getJSON( ajaxurl , function(data){
		$("#dasha_chosen_user").empty();
		$.each(data.data, function(id,value){
			$("#dasha_chosen_user").append( "<option value='"+value.id+"'>"+value.name+"("+value.code+")</option>");
		})
	});
	$(".dasha_chosen_input").click(function(){
		cur_input = $(this) ;
		cur_value = $("#"+cur_input.attr("id")+"_chosen_value");
		if( cur_value.length == 0 )
		{
			cur_input.after("<input type='hidden'id='"+cur_input.attr("id")+"_chosen_value'"+" name='"+cur_input.attr("name")+"'></input>");
			cur_value = $("#"+cur_input.attr("id")+"_chosen_value");
		}
		var pos = cur_input.position() ;
		$(".dasha_chosen_content").css("top", pos.top+ cur_input.outerHeight()+"px" );
		$(".dasha_chosen_content").css("left", pos.left+"px" );
		$(".dasha_chosen_content").slideDown("normal") ;
	});
	$(".dasha_chosen_close").click(function(){
		cur_value.val($("#dasha_chosen_user").val());
		cur_input.val($("select#dasha_chosen_user option:selected").text());
		$(".dasha_chosen_content").slideUp("normal") ;
		return false ;
	});
	$("#dasha_chosen_user").change(function(){
		if( $("#dasha_chosen_user").val() && $("#dasha_chosen_user").val() != '')
		{
			$(".dasha_chosen_content").slideUp("normal") ;
			cur_value.attr("value",$("#dasha_chosen_user").val());
			cur_input.val($("select#dasha_chosen_user option:selected").text());
		}
		//下面为了兼容IE6。。。。
		$("#dasha_chosen_user").append( "<option selected value=''>dfg</option>");
		$("select#dasha_chosen_user option:selected").remove() ;
	});
});
