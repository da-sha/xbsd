/* ------------------------------------------------------------
 * 日期		：2012-9-24
 * -----------------------------------------------------------
 * 创建人	：大傻 lilei.zh@qq.com
 * -----------------------------------------------------------
 * 文件说明	; TeacherAction.class.php UTF-8
 * -----------------------------------------------------------
 */
$(document).ready(function(){
	$("#teach").change(function(){
		$("#search").attr("href", action+"/main");
		$("#admin_cou").attr("href", action+"/main");
		$("#add").attr("href", action+"/add/assessid");
		$("#add_cou").attr("href", action+"/add");
		var teachid = $(this).val();
		var url = action+'/getassessment/teachid/'+teachid ;
		assess = E("assess") ;
		assess.options.length=1;
		
		assess[0] = new Option( "--正在加载中--" , 0 ) ;
		$.getJSON(url, function(data){
			assess[0] = new Option( "---请选择考核---" , 0 ) ;
			$.each(data.data, function(id,value){
				assess.add( new Option( value.name , value.id ));
			})
		});
	});
	$("#assess").change(function(){
		$("#search").attr("href", action+"/main/assessid/"+$("#assess").val());
		$("#admin_cou").attr("href", action+"/main/assessid/"+$("#assess").val());
		$("#add").attr("href", action+"/add/assessid/"+$("#assess").val());
		$("#add_cou").attr("href", action+"/add/assessid/"+$("#assess").val());
	});
	$("#admin_cou,#search").click(function(){
		$("#admin_cou").addClass("on") ;
		$("#add_cou").removeClass("on") ;
	});
	$("#add_cou,#add").click(function(){
		$("#add_cou").addClass("on") ;
		$("#admin_cou").removeClass("on") ;
	});
});
function E(id)
{
	return document.getElementById(id) ;
}
