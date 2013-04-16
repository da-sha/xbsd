/**
 * lisency
 * @date 2012年9月17日 19:24:33
 */

jQuery(function($){
	/*控制用户登录区域*/
	fsetdefault($("#u"),$("#user_label"),"user_name");
	fsetdefault($("#p"),$("#pwd_label"),"user_pwd");
	
	/*默认选中项*/
	$("#u").select();
	
	/*登录默认enter键控制*/
	document.onkeydown = function(e){
	    var theEvent = window.event || e;
	    var code = theEvent.keyCode || theEvent.which;
	
	    if(code == 13){
	        $(".login_btn[type=submit]").click();
	    }
	};
	
	/*传输用户信息*/
	$(".login_btn[type=submit]").click(function(){
	    var user_name = $("#u").val();
	    var pwd = $("#p").val();
	    
	    if(user_name == '' || pwd == ''){
	        $("#error_prompt").html("用户名或密码不可为空");
	    }else{
	        var url = APP + "/Login/login" ;
	        pwd = md5(pwd);
	        
	        $.get(url,{
	        	"user":user_name,
	        	"pwd": pwd,
	        },function(data){
	            $("#error_prompt").html(data.info);
	            if(data.status == 1){
	            	setTimeout(function(){	window.location.href = APP + "/Index/index";},500);
	            }
	        },"json");
	    }
	});	
});
