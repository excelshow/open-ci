//添加组件列表

(function($) {
    $.getUrlParam = function(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]);
        return null;
    }
})(jQuery);
var postinfo = {
    'form_id': $('#form_id').val(),
}
if (postinfo.form_id) {
    $.get("/contest/form/ajax_list", postinfo, function(data) {

        if (data.error == 0) {

            for (var i = 0; i < data.data.length; i++) {
                data.data[i].option_values = JSON.parse(data.data[i].option_values)
            }
            var formdatalist = {
                isEmpty: data.data.length,
                list: data.data,
            };
            var objlist_html = template('initformqlist', formdatalist);
            $("#question-list ul").html(objlist_html);
            initOrder();
            return false;
        } else {
            alert(data.info);
        }
    }, "json")
}

$("#group-list-box li").on("click", function() {
    var o_index = $(this).attr("order");
    var _type_ = $(this).attr("field_type");
    var title = $(this).text();
    var name = 'name';
    var item_id = $.getUrlParam('item_id');
    var form_id = $('#form_id').val();
    var option_value = [];
    switch (_type_) {
        case "radio":
        case "checkbox":
        case "select":
            option_value = [{
                'labeltxt': '选项1'
            }, {
                'labeltxt': '选项2'
            }];
            break;
        case "policy":
            option_value = [{
                'labeltxt': '免责声明条款'
            }];
            break;
        default:
            break;
    }
    var rankorder = $("#question-list ul li").length + 1;
    //添加组件
    var postinfo = {
        form_id: form_id,
        type: _type_,
        title: title,
        is_required: 1,
        option_values: option_value
    };
    $.post("/contest/form/ajax_add_form_item", postinfo, function(data) {
        if (data.error == 0) {
            //题目object
            console.log(data);
            if (data.data[0].fk_enrol_form) {
                if ($('#form_id').val()) {
                    $('#form_id').val(data.data[0].fk_enrol_form)
                }
            }
            for (var i = 0; i < data.data.length; i++) {
                data.data[i].option_values = JSON.parse(data.data[i].option_values)
            }
            var formdatalist = {
                isEmpty: data.data.length,
                list: data.data,
            };
            var objlist_html = template('initformqlist', formdatalist);
            $("#question-list ul").html(objlist_html);

            $("#question-list ul li:last").addClass("hover").siblings().removeClass("hover");
            var pos = $("#question-list ul li:last").position();
            var v_top = pos.top;
            $('body,html').animate({
                scrollTop: v_top
            }, 400);
            $("#question-list ul li.hover").find("input").eq(0).focus().select();
            sortinit();
            initOrder();
            alert('添加成功！');
            return false;
        } else {
            alert(data.info);
        }
    }, "json")

    return false;
});
//移除组件
$(document).on("click", ".del_item", function() {
        var qid = $(this).attr("did");
        layer.open({
            title: '修改确认',
            content: '<div class="text-danger" style="padding-bottom:20px;">确定要删除改项目吗？',
            btn: ['确定', '取消'],
            yes: function(index, layero) {
                jQuery.post('/contest/form/ajax_delete', {
                    form_item_id: qid
                }, function(data) {
                    if (data.error == 0) {
                        alert('删除成功！');
                        initOrder();
                        $("#q" + qid).remove();
                        initOrder();
                        layer.close(index);
                    } else {
                        layer.close(index);
                        alert('删除失败！');
                    }
                }, "json")

            },
            btn2: function(index) {
                layer.close(index);
            }
        });
        return false;
    })
    //拖动排序
function sortinit() {
    $(".sortable").sortable({
        cursor: "move",
        items: "li", //只是li可以拖动
        opacity: 0.6, //拖动时，透明度为0.6
        revert: true, //释放时，增加动画
        stop: function(event, ui) { //更新排序之后
            initOrder();
            //更新顺序
            var orderlist = [];
            jQuery('.sortable li').each(function() {
                var qid = jQuery(this).attr("qid");
                var objarr = {};
                objarr['qid'] = qid;
                objarr['seq'] = jQuery(this).index();
                orderlist.push(objarr);
            });
            var params = {
                'params': JSON.stringify(orderlist)
            }
            jQuery.post("/contest/form/ajax_set_seq", params, function(data) {
                if (data.error == 0) {
                    initOrder();
                    alert('更改成功')
                }
            }, "json")
        }
    });
    //移动选项
    $(".option-list").sortable({
        cursor: "move",
        items: "label", //只是li可以拖动
        opacity: 0.6, //拖动时，透明度为0.6
        revert: true, //释放时，增加动画
        start: function() {
            jQuery(".sortable").sortable('disable');
        },
        stop: function() {
            initOrder();
            var qid = jQuery(this).attr("qid");
            var field_type = jQuery("#q" + qid).attr("type");
            updateOption(qid, field_type);
        }
    });
}
/**编辑题目标题*/
jQuery(document).on("blur", ".q-title", function() {
        var n_val = jQuery(this).val();
        var o_val = jQuery(this).attr("data-value");
        var qid = jQuery(this).attr("qid");
        var type = jQuery(this).attr("type");
        var $thiobj = jQuery(this);
        var $thiobj = jQuery(this);
        if (n_val == "") {
            $thiobj.parent().find(".err-show").remove();
            err_tips($thiobj, "未填写标题");
            return false;
        } else {
            $thiobj.parent().find(".err-show").remove();
        }
        if (n_val != o_val) {
            jQuery.post("/contest/formorder/ajax_update_form_item", {
                form_item_id: qid,
                title: n_val,
                type:type
            }, function(data) {
                if (data.error == 0) {
                    initOrder();
                    alert("修改成功");
                    $thiobj.attr("data-value", n_val);
                    //quickSave();
                    $thiobj.parent().find(".err-show").remove();
                } else {
                    //alert(data.info);
                    err_tips($thiobj, data.info);
                }
            }, "json");
        }
        return false;
    })
    /**是否必选*/
jQuery(document).on("click", ".isRequired", function() {
        var qid = jQuery(this).attr("qid");
        var type = jQuery(this).parents("li").attr('type');
        if (jQuery(this).is(':checked')) {
            var required = "1";
        } else {
            var required = "2";
        }
        //提交
        jQuery.post("/contest/formorder/ajax_update_form_item", {
            form_item_id: qid,
            is_required: required,
            type:type
        }, function(data) {
            if (data.error == 0) {
                initOrder();
                alert("修改成功");
            } else {
                alert(data.info);
            }
        }, "json");
    })
    /*添加题目选项**/
jQuery(document).on("click", ".add-option", function() {
        //同步到右侧
        var qid = jQuery(this).attr("qid");
        var field_type = jQuery('#q' + qid).attr("type");
        var labeltxt = "选项";
        switch (field_type) {
            case "select":
                var $itemopt = '<label>';
                $itemopt += '<span class="glyphicon glyphicon-th-list"></span> ';
                $itemopt += '<input type="text" data-value="' + labeltxt + '" value="' + labeltxt + '" class="iRtxt" qid="' + qid + '"/>';
                $itemopt += '<span class="del_option glyphicon glyphicon-minus" title="删除选项" qid="' + qid + '"></span>';
                $itemopt += '</label>';
                jQuery(this).parent().before($itemopt);
                updateOption(qid, field_type);
                break;
            case "radio":
                var $itemopt = '<label>';
                $itemopt += '<span class="glyphicon glyphicon-th-list"></span> ';
                $itemopt += '<input type="radio"  name="r' + qid + '" value="' + labeltxt + '">';
                $itemopt += '<input type="text" data-value="' + labeltxt + '" value="' + labeltxt + '" class="iRtxt" qid="' + qid + '"/>';
                $itemopt += '<span class="del_option glyphicon glyphicon-minus" title="删除选项" qid="' + qid + '"></span>';
                $itemopt += '</label>';
                jQuery(this).parent().before($itemopt);
                updateOption(qid, field_type);
                break;
            case "checkbox":
                var $itemopt = '<label>';
                $itemopt += '<span class="glyphicon glyphicon-th-list"></span> ';
                $itemopt += '<input type="checkbox"  name="ck' + qid + '[]" value="' + labeltxt + '">';
                $itemopt += '<input type="text" data-value="' + labeltxt + '" value="' + labeltxt + '" class="iRtxt" qid="' + qid + '"/>';
                $itemopt += '<span class="del_option glyphicon glyphicon-minus" title="删除选项" qid="' + qid + '"></span>';
                $itemopt += '</label>';
                jQuery(this).parent().before($itemopt);
                updateOption(qid, field_type);
                break;
            default:
                alert("操作失败");
                break;
        }
        return false;
    })
    /***编辑选项**/
jQuery(document).on("blur", "input.iRtxt", function() {
    var qid = jQuery(this).attr("qid");
    var field_type = jQuery("#q" + qid).attr("type");
    var n_val = jQuery(this).val();
    var o_val = jQuery(this).attr("data-value");
    var $thiobj = jQuery(this);
    if (n_val == "") {
        $thiobj.parent().find(".err-show").remove();
        err_tips($thiobj, "未填写选项内容");
        return false;
    } else {
        $thiobj.parent().find(".err-show").remove();
    }
    //判断是否有变化
    if (n_val != o_val) {
        updateOption(qid, field_type);
        jQuery(this).attr("data-value", n_val);
    }
    return false;
})
jQuery(document).on("blur", ".policy textarea", function() {
        var qid = jQuery(this).attr("qid");
        var field_type = jQuery("#q" + qid).attr("type");
        var n_val = jQuery(this).val();
        var o_val = jQuery(this).attr("data-value");
        var $thiobj = jQuery(this);
        if (n_val == "") {
            $thiobj.parent().find(".err-show").remove();
            err_tips($thiobj, "未填写条款内容");
            return false;
        } else {
            $thiobj.parent().find(".err-show").remove();
        }
        //判断是否有变化
        if (n_val != o_val) {
            updateOption(qid, field_type);
            jQuery(this).attr("data-value", n_val);
        }
        return false;
    })
    /**删除选项*/
jQuery(document).on("click", ".del_option", function() {
        var qid = jQuery(this).attr("qid");
        var field_type = jQuery("#q" + qid).attr("type");
        //选项
        switch (field_type) {
            case "radio":
            case "checkbox":
            case "select":
                var len = jQuery("#q" + qid).find("input.iRtxt").length;
                if (len < 3) {
                    alert("至少保留两个选项");
                    return false;
                } else {
                    jQuery(this).parent().remove();
                    updateOption(qid, field_type);
                }
                break;
            default:
                var field_options = "";
        }
        return false;
    })
    //选项更新
function updateOption(qid, field_type) {
    switch (field_type) {
        case "radio":
        case "checkbox":
        case "select":
            var field_options = [];
            jQuery("#q" + qid).find("input.iRtxt").each(function() {
                var val = jQuery(this).val();
                var obj = {
                    'labeltxt': val
                };
                if (val != "") {
                    field_options.push(obj);
                }
            })
            var len = field_options.length;
            if (len < 2) {
                alert("至少保留两个选项");
                return false;
            }
            if (len > 10) {
                alert("最多添加10选项");
                return false;
            }
            break;
        case "policy":
            var field_options = [];
            var valtxt = jQuery("#q" + qid).find("textarea").val();
            field_options.push({
                'labeltxt': valtxt
            });
            break;
        default:
            var field_options = "";
            break;
    }
    //提交
    jQuery.post("/contest/formorder/ajax_update_form_item", {
        form_item_id: qid,
        option_values: field_options,
        type:field_type
    }, function(data) {
        if (data.error == 0) {
            initOrder();
            alert('修改成功')
        } else {
            alert(data.info);
        }
    }, "json");
}
//错误提示
function err_tips(obj, msg) {
    var o_x = obj.offset().top;
    var o_y = obj.offset().left;
    obj.parent().append('<span class="err-show">' + msg + '</span>');
}
//自动排序号
function initOrder() {
    $("#question-list ul li").each(function() {
        var q_index = $(this).index() + 1;
        $(this).find(".q-order").text(q_index + "、");
    })
}
initOrder();
sortinit();