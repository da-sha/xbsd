/* ------------------------------------------------------------
 * 日期		：2012-11-26
 * -----------------------------------------------------------
 * 创建人	：大傻 lilei.zh@qq.com
 * -----------------------------------------------------------
 * 文件说明	; dasha_chosen.js UTF-8
 * -----------------------------------------------------------
 */
$(document).ready(function(){
	$("table.edit_table").die("dblclick") ;
	$("table.edit_table").live("dblclick",function(){
		this_form = $(this).closest("form") ;
		$(this).find("td.edit_textarea").each(function(){
			$(this).html( "<textarea class='editing_input' name='" + $(this).attr("name") + "'>" + $(this).text() + "</textarea>" ) ;
			$(this).data("data", $(this).text() ) ;
		}) ;
		$(this).find("td.edit_input").each(function(){
			$(this).data("data", $(this).text() ) ;
			$(this).html( "<input  class='editing_input' name='" + $(this).attr("name") + "' value='" + $(this).text() + "'/>" ) ;
		}) ;
		this_form.find(".edit_confirm").data( "show" , this_form.find(".edit_confirm").text() ) ;
		this_form.find(".edit_confirm").html("<input class='edit_table_save button' type='button' value='保存'><input class='edit_table_reset button' type='button' value='取消'>") ;
	}) ;
	$(".edit_table_save").die("click") ;
	$(".edit_table_save").live("click",function(){
		this_form = $(this).closest("form") ;
		dia = art.dialog() ;
		$.post( this_form.attr("action") , this_form.serialize(), function( data ){
			if( data.status == 1 )
			{
				dia.close() ;
				art.dialog.tips( data.info ) ;
				$.each( data.data , function( key , value ){
					this_form.find(".editing_input[name='" + key +"']").closest("td").html( value ) ;
				}) ;
				this_form.find(".edit_confirm").html( this_form.find(".edit_confirm").data("show") ) ;
			}
			else{
				dia.content( data.info ) ;
			}
		}, "json") ;
	}) ;
	$(".edit_table_reset").die("click") ;
	$(".edit_table_reset").live("click",function(){
		this_form = $(this).closest("form") ;
		this_form.find(".editing_input").each( function(){
			$(this).closest("td").html( $(this).closest("td").data("data") ) ;
		}) ;
		this_form.find(".edit_confirm").html( this_form.find(".edit_confirm").data("show") ) ;
	}) ;
});
