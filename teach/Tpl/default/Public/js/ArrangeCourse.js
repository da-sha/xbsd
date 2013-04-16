/*
 * 排课管理模块
 */
function ArrangeCourse() {
    /*共有方法*/
    this.init = function(){
        $("#majorid").hide();
    }
}

$(document).ready(function() {
        var arrangeCourse = new ArrangeCourse();
        arrangeCourse.init();

        sum_input = $("#condition_table tr td :input").size();

        //检测数据是否为空
        function isNull(selecter, tips) {
        var win = art.dialog.top;
        if ($(win.document).find(selecter).val() == '') {
        $(win.document).find(selecter).after("<span id='dasha_empty_tip' class='red'><br>" + tips + "不能为空！</span>");
        $(win.document).find(selecter).one("click", function() {
            $(art.dialog.top.document).find("#dasha_empty_tip").remove();
            });
        return true;
        }
        return false;
        }
        //检测数组是否为空
        function arrayIsNull(selecter, tips) {
            boolNull = false;
            var win = art.dialog.top;
            $.each($(win.document).find(selecter), function() {
                    if ($(this).val() == '') {
                    $(this).parent("td").append("<span id='dasha_empty_tip' class='red'>" + tips + "不能为空！</span>");
                    $(this).one("click", function() {
                        $(art.dialog.top.document).find("#dasha_empty_tip").remove();
                        });
                    boolNull = true;
                    return false;
                    }
                    });
            return boolNull;
        }
        //检测是否有空数据
        function check() {
            $("#dasha_empty_tip").remove();
            if (!isNull("#start_week_id", "开始授课学期") &&
                    !isNull("#week_id", "开设周数") &&
                    !isNull("#totaltime_id", "总学时") &&
                    !isNull("#teach_year", "开课学年") &&
                    !isNull("#teach_semester", "开设学期") &&
                    !isNull("#userid", "教师") &&
                    !arrayIsNull(".class_select", "授课班级")
               ) {
                return true;
            }
            return false;
        }
        //调用dialog进行排课
        $(".dasha_dialog").die("click");
        $(".dasha_dialog").live("click", function() {
                loadart = art.dialog.through({});
                $.get($(this).attr("href"), function(data) {
                    loadart.close();
                    var dialog = art.dialog.through({
title: "排课",
top: '10%',
content: data,
lock: true,
resize: true,
opacity: 0.1,
esc: true,
button: [{
name: '确认排课',
callback: function() {
if (check() == true) {
var win = art.dialog.top;
$.ajax({
type: "POST",
url: URL + "/insert",
data: $(win.document).find("#teach_task").serialize(),
success: function(msg) {
art.dialog.tips(msg);
dialog.close();
}
});
}
return false;
},
focus: true
}],
cancelVal: '关闭',
    cancel: true
    });
});
return false;
});
//调用dialog进行编辑
$(".dasha_edit_dialog").die("click");
$(".dasha_edit_dialog").live("click", function() {
        loadart = art.dialog.through({});
        $.get($(this).attr("href"), function(data) {
            loadart.close();
            editdialog = art.dialog.through({
title: "查看排课信息",
top: '10%',
content: data,
lock: true,
resize: true,
opacity: 0.1,
esc: true,
cancelVal: '关闭',
cancel: true
});
            });
        return false;
        });
//分页链接动态加载！
$(".pagelink a").die("click");
$(".pagelink a").live("click", function() {
        loadart = art.dialog.through({});
        $.get($(this).attr("href"), function(data) {
            $("#main_content").html(data);
            });
        return false;
        });

//
$("#cou_category").change(function() {
        if ($(this).val() != '') {
        if ($(this).val() == 2) {
        $("#majorid").show();
        $("#sel").val("");
        $("#sel").attr("selected", "true");
        }
        else {
        $("#majorid").hide();
        $("#sel").val("false");
        }
        }
        });

//
$("#admissiondate").change(function() {
        if ($(this).val() != '') {
        url = "__APP__/ArrangeCourse/getClass/admissiondate/" + $(this).val();
        $("select#classid").html("<option value=''>--正在努力加载中--</option>");
        $.getJSON(url, function(data) {
            if (data.status == 1) {
            $("select#classid").html("<option value=''>--请选择班级--</option>");
            $.each(data.data, function(id, value) {
                $("select#classid").append("<option value='" + value.id + "'>" + value.name + "</option>");
                })
            }
            else {
            $("select#classid").html("<option value=''>---没有找到班级---</option>");
            }
            });
        }
        });
var get_body_info = function() {
    if (sum_input == count_not_null()) {
        loadart = art.dialog.through({});
        $.get(URL + "/body", $("#course_condition").serialize(), function(data) {
                loadart.close();
                $("#main_content").html(data);
                });
    }
};
var count_not_null = function() {
    all_input = $("#condition_table tr td :input");
    ready_input = 0;
    $.each(all_input, function(i, data) {
            if ('' != $(this).val()) {
            ready_input++;
            }
            else {
            $(this).addClass("input_border_red");
            $(this).one("click", function() {
                $(this).removeClass("input_border_red");
                });
            return false;
            }
            });
    return ready_input;
};
$("#condition_table tr td :input,.pagelink a,.dasha_dialog,.dasha_edit_dialog").ajaxError(function(event, request, settings) {
        loadart.close();
        art.dialog.tips("又出错了，请检查网络！", 2);
        });
//这个必须放到最后
$("#condition_table tr td :input").change(function() {
        get_body_info();
        });
});

