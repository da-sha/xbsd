/**
 * lisency 
 * @date 9 16
 */

jQuery(function($){
	/*设置菜单背景*/
    var $first_menus = $("#menu_list_root .first_menu");
    var $cur_fir_menu = $("#menu_list_root .cur_fir_menu");
    
    if($cur_fir_menu.length == 0){
    	$first_menus.first().addClass("cur_fir_menu")
    	.css({
    		"display": "block",
    	});
    }
    /*
    $first_menus.filter(":not('.cur_fir_menu')")
    .each(function(){
    	$(this).children("ul").css({
    		"display":"none",
    	});
    })
    */
    
    var $first_menus_link = $("#menu_list_root .first_menu .fir_menu_link");
    
    $("#menu_list_root .cur_fir_menu .second_menu:first").addClass("cur_sec_menu");
    $first_menus_link.click(function(){
        /*返回假目的不让跳转*/
    	/*如果点击当前对象则不做任何东作*/
    	var $first_menu = $(this).parent(".first_menu");
    	if($first_menu.hasClass("cur_fir_menu") == true){
    		return false;
    	}
    	
    	/*将以前的cur_first_menu去掉，添加到当前的上面，并显示子菜单*/
    	$first_menu.addClass("cur_fir_menu")
    	.children("ul")
    	.css({
    		"display": "block",
    	})
    	.end()
    	.siblings(".cur_fir_menu")
    	.removeClass("cur_fir_menu")
    	.children("ul").css({
    		//"display": "none",
    	});

    	/*设置默认子菜单*/
    	var $second_menus = $("#menu_list_root .cur_fir_menu .second_menu");
    	var $cur_sec_menu = $("#menu_list_root .cur_fir_menu .cur_sec_menu");
    	
    	/*设置默认第一个*/
    	if($cur_sec_menu.length == 0){
    		$second_menus.first().addClass("cur_sec_menu");
    	}

    	/*最后将内容导入*/
    	$("#content_wrap").load( $second_menus.filter(".cur_sec_menu").attr("href") ) ;
    	return false;
    });
    
    /*点击菜单异步传输数据*/
    $(".second_menu").click(function(){
        if($(this).hasClass("cur_sec_menu") ==  true){
            return false;
        }

        /*给当前添加并去掉以前的*/
        $(this).addClass("cur_sec_menu")
        .parent("li")
        .siblings("li")
        .each(function(){
            $(this).children(".cur_sec_menu")
            .removeClass("cur_sec_menu");
        });

		$("#content_wrap").load( $(this).attr("href") ) ;

    	return false;
    });
});
