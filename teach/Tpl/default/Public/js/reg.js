/**
 * add by lisency 
 * @date 2012年9月22日 08:35:28
 * @description: 为注册模块添加功能
 */

jQuery(function($){
	/*控制注册填id区域*/
	fsetdefault($("#teacher_id_input"),$("#teacher_id_label"),"reg_id_teacher_id");
	fsetdefault($("#postgraduate_id_input"),$("#postgraduate_id_label"),"reg_id_postgraduate_id");
	fsetdefault($("#graduate_id_input"),$("#graduate_id_label"),"reg_id_graduate_id");
	
	fsetdefault($("#teacher_name_input"),$("#teacher_name_label"),"reg_id_name");
	fsetdefault($("#postgraduate_name_input"),$("#postgraduate_name_label"),"reg_id_name");
	fsetdefault($("#graduate_name_input"),$("#graduate_name_label"),"reg_id_name");

    /*设置注册信息填入框中的数据*/
    var fsetInputBorder = function(input){
		input.blur(function(){
			 if(input.attr("value") == ""){
            input.css({
                "border-color": "red",
            });
        }
		}).focus(function(){
            input.css({
                "border-color": "#fc0",
            });
		});
    };

    fsetInputBorder($("#reg_user_name"));
    fsetInputBorder($("#reg_mail"));
    fsetInputBorder($("#reg_pwd"));
    fsetInputBorder($("#reg_repeat_pwd"));
    
});