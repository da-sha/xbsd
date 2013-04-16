$(function(){
	$(".aac_l_top").die("change") ;
	$(".aac_l_top").live("change",function(){
		if( $(this).val() != "" ){
			var curform = $(this).closest("form") ;
			$(".aac_l_content").html("<option value=''>正在努力加载中</option>") ;
			$.getJSON(curform.attr("action"), curform.serialize(), function(data){
				if( data.status == 1 ){
					$(".aac_l_content").empty() ;
					$.each( data.data ,function( key , value ){
						var aoption = $("<option></option>") ;
						$.each( value ,function( akey , avalue ){
							aoption.attr( akey , avalue ) ;
						}) ;
						aoption.val( value.id ) ;
						aoption.text( value.name ) ;
						$(".aac_l_content").append(aoption) ;
					}) ;
				}else{
					$(".aac_l_content").html("<option value=''>对不起，没有找到信息</option>") ;
				}
			}) ;
		}
	}) ;
	
	$(".aac_l_content").die("dblclick") ;
	$(".aac_l_content").live("dblclick",function(){
		var select = $(this).find("option:selected") ;
		if( select.val() != "" && $(".aac_r_content option[value="+select.val()+"]" ).size() == 0 )
		{
			select.clone(false).appendTo($(".aac_r_content")) ;
		}
	}) ;
	
	$(".aac_r_content").die("dblclick") ;
	$(".aac_r_content").live("dblclick",function(){
		if( $(this).val() != "" )
		{
			$(this).find("option:selected").remove() ;
		}
	}) ;
	$(".aac_add_move").die("click") ;
	$(".aac_add_move").live("click", function(){
		$(".aac_l_content").find("option:selected").each(function(){
			if( $(this).val() != '' && $(".aac_r_content option[value="+$(this).val()+"]" ).size() == 0){
				$(this).clone(false).appendTo($(".aac_r_content")) ;
			}
		}) ;
	}) ;
	$(".aac_remove_move").die("click") ;
	$(".aac_remove_move").live("click", function(){
		$(".aac_r_content").find("option:selected").remove() ;
	}) ;
}) ;