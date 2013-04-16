/**
 * 可编辑表格
 */
function EditTable($parent,child_selector){
	var texts = new Array();	/*保存子元素的所有内容*/
	var texts_off = 0;
	
	/*
	 * 公有成员
	 */
	this.replace_selector = "span";
	/*得到所有子元素内容*/
	this.getTexts = function(){
		$parent.children(child_selector)
		.each(function(){
			var text = $(this).html();
			texts.push(text);
		});
	}
	
	/*将子元素转换成输入框*/
	this.toInput = function(){
		texts_off = 0;
		$parent.children(child_selector)
		.each(function(){
			var $parent = $(this);
			var $span = $parent.children(this.replace_selector);
	
			if($parent.children('input').length > 0)
			{
				return false;
			}
	
			var inputObj = $('<input type=text />')
				.width(width - 2)
				.height(height - 2)
				.val(texts[texts_off])
				.appendTo($parent);
			$span.hide();
			texts_off ++;
		});
		texts_off = 0;
	}
}
$(function(){
	function span_to_input(){
		var $parent = $(this);
		var $span = $parent.children('span');
		/**虽然点击input不会错，但单击td还是会出差bug
		 * 解决方法：判断td中是否有input*/
		if($parent.children('input').length > 0)
		{
			return false;
		}
		//td中的文本内容：
		var text = $span.html();
		var width = $span.width();
		var height = $span.height();
		//清空td中的内容
		//$parent.empty();

		var inputObj = $('<input type=text />')
			.width(width - 2)
			.height(height - 2)
			.val(text)
			.appendTo($parent);
		$span.hide();
	}
	$(".edit_table span").click(function(){
		var $tr = $(this).parent().parent();
		$tr.addClass("status_edit");
		var $tds = $tr.children("td:not(.td_manage)");
		$tds.each(span_to_input);
		
		var $td_manage = $tr.children(".td_manage");
		$td_manage.children("a").hide();
		var btn_save = "<button class='button secondary tiny save'>保存</button>";
		var btn_cancel = "<button class='button tiny cancel'>取消</button>";
		
		$(btn_save).appendTo($td_manage);
		$(btn_cancel)
		.click(function(){
			
		})
		.appendTo($td_manage);
	});
});
