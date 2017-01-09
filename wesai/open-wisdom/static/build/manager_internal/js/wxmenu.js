//微信菜单
var data = {
    title: app_mpinfo.authorizer_info.nick_name,
    list: app_menu.button,
    menucount: app_menu.button.length
};
var ZhMenu = {
    init: function() {
        var html = template('menu-show', data);
        document.getElementById('pre-mpmenu').innerHTML = html;
        $(".second-level").each(function() {
            if ($(this).parent("ul").find(".sub-edit-menu").length > 4) {
                $(this).hide();
            }
        });
    },
    editmenu: function(obj) {
        var ehtml = template('menu-edit', obj);
        document.getElementById('edit-box').innerHTML = ehtml;
        return false;
    }
};
ZhMenu.init();
//新增菜单
$(document).on("click", "#edit-nav li", function() {
        $(this).addClass("active").siblings().removeClass("active");
        var role             = $(this).attr("role");
        window.location.hash = role;
        var index            = $("#edit-nav li").index(this);
        $(".create-form").hide();
        $(".create-form").eq(index).show().removeClass("hide");
        return false;
    }
);
$(document).on("click", ".first-level", function() {
        var ops = {
            name: "菜单名称",
            level: 1,
            type: "view"
        };
        var sub_html = '';
        sub_html += '<div class="menu-btn"><strong class="edit_menu"  level="1" vname="' + ops.name + '" mtype="view" murl="" mkey="">' + ops.name + '</strong>';
        sub_html += '<ul class="sub-btn-list">';
        sub_html += '<li class="list-item plus second-level">+</li>';
        sub_html += '</ul></div>';
        $(this).before(sub_html);
        $(this).prev(".menu-btn").find("strong.edit_menu").trigger("click");
        return false;
    })
    //新增子菜单
$(document).on("click", ".second-level", function() {
        var ops = {
            name: "子菜单名称",
            level: 2,
            type: "view"
        }
        if ($(this).parent("ul").find(".sub-edit-menu").length > 4) {
            $(this).hide();
            return false;
        }
        var sub_html = '<li class="list-item  sub-edit-menu" level="2" vname="' + ops.name + '" mtype="view" murl="" mkey="">' + ops.name + '</li>';
        $(this).before(sub_html);
        $(this).prev("li.sub-edit-menu").trigger("click");
        if ($(this).parent("ul").find(".sub-edit-menu").length > 4) {
            $(this).hide();
        }
        $(this).parent().parent().find("strong").attr("mtype", "");
        return false;
    })
    //设置菜单
$(document).on("click", ".edit_menu,.sub-edit-menu", function() {
        var level = $(this).attr("level");
        var name = $(this).attr("vname");
        var type = $(this).attr("mtype");
        var url = $(this).attr("murl");
        var ops = {
            name: name,
            level: level,
            type: type,
            url: url
        }
        $(".curr_menu").removeClass("curr_menu");
        $(this).addClass("curr_menu");
        ZhMenu.editmenu(ops);
        return false;
    })
    //删除子菜单
$(document).on("click", ".del_submenu", function() {
        /*弹出层*/
        var menu_name = $("#menu-name").val();
        layer.open({
            title: '温馨提示',
            content: '<div class="text-danger">删除确认</div>删除后' + menu_name + '”子菜单下设置的内容将被删除',
            btn: ['确定', '取消'], //只是为了演示
            yes: function(index, layero) {
                //do something
                $(".curr_menu").parent().find(".second-level").show();
                if ($(".curr_menu").parent("ul").find(".sub-edit-menu").length < 2) {
                    $(".curr_menu").parent().parent().find("strong").attr("mtype", "view");
                }
                $(".curr_menu").remove();
                $("#edit-box").html('<p class="help-block no-edit">点击左侧菜单进行编辑操作</p>');
                layer.close(index); //如果设定了yes回调，需进行手工关闭
            },
            btn2: function() {
                layer.closeAll();
            }
        });
        return false;
    })
    //修改菜单名称
$(document).on("keyup", "#menu-name", function() {
    var mname = $(this).val();
    var wcount = mname.replace(/[^\x00-\xff]/g, '**').length;
    var maxlength = $(this).attr("maxlength");
    if (wcount > maxlength + 1) {
        $(".menu-tip").removeClass("hidden").show().text("字数超过上限");
        return false;
    } else {
        $(this).parent().find(".menu-tip").hide();
    }
    return false;
})
$(document).on("blur", "#menu-name", function() {
        var thisname = $(this).val();
        if (thisname == "") {
            $(".menu-tip").removeClass("hidden").show().text("请输入菜单名称");
            return false;
        }
        var maxlength = $(this).attr("maxlength");
        var newname = cutString(thisname, maxlength);
        $(".curr_menu").text(newname);
        $(".curr_menu").attr("vname", newname);
        $(this).parent().find(".menu-tip").hide().text();
        $(this).val(newname);
        isAllowSubmit = true;
    })
    //修改url
$(document).on("blur", "#menu-url", function() {
    var murl = $(this).val();
    var rgUrl = IsURL(murl);
    if (!rgUrl) {
        $(".url-tip").removeClass("hidden").show().text("请输入正确的链接地址");
        return false;
    }
    $(".curr_menu").attr("murl", murl);
    $(this).parent().find(".url-tip").hide();
    return false;
})
$(document).on("click", ".del_menu", function() {
        /*弹出层*/
        var menu_name = $("#menu-name").val();
        layer.open({
            title: '温馨提示',
            content: '<div class="text-danger">删除确认</div>删除后' + menu_name + '”菜单下设置的内容将被删除',
            btn: ['确定', '取消'], //只是为了演示
            yes: function(index, layero) {
                //do something
                $(".curr_menu").parent().remove();
                $("#edit-box").html('<p class="help-block no-edit">点击左侧菜单进行编辑操作</p>');
                layer.close(index); //如果设定了yes回调，需进行手工关闭
            },
            btn2: function() {
                layer.closeAll();
            }
        });
        return false;
    })
    //错误提示
function menuerror_tip() {
    var vname = $(".curr_menu").attr("vname");
    var murl = $(".curr_menu").attr("murl");
    if (vname == "") {
        $(".menu-tip").removeClass("hidden").show().text("请输入菜单名称");
        return false;
    }
    var rgUrl = IsURL(murl);
    if (!rgUrl) {
        $(".url-tip").removeClass("hidden").show().text("请输入正确的链接地址");
        return false;
    }
}
//拖动排序
$("#order-start").click(function() {
    $(".menulist").addClass("sortable-mod");
    $("#order-start").hide();
    $("#order-end").removeClass("hidden").show();
    $("#edit-box").html('<p class="help-block no-edit">请通过拖拽左边的菜单进行排序</p>');
    $(document).removeClass("curr_menu");
    $(".edit_menu,.sub-edit-menu").on("click", function() {
        return false;
    });
    startSort();
    return false;
})
$("#order-end").click(function() {
        $(".menulist").removeClass("sortable-mod");
        $("#order-start").show();
        $("#order-end").hide();
        $("#edit-box").html('<p class="help-block no-edit">点击左侧菜单进行编辑操作</p>');
        $(".edit_menu,.sub-edit-menu").off("click");
        endSort();
        return false;
    })
    /*
     * 保存并发布
     */
$("#submit_button").click(function() {
        var appid = "{$appid}";
        var menulist = [];
        var errcount = 0;
        $(".edit_menu").each(function() {
                var $pmenu = $(this);
                var newobj = {};
                //子菜单
                var submeucount = $pmenu.parent().find(".sub-edit-menu").length;
                //有子菜单
                if (submeucount > 0) {
                    newobj.name = $(this).attr("vname");
                    var sub_buttonlist = [];
                    var sub_obj = {};
                    //子菜单
                    $pmenu.parent().find(".sub-edit-menu").each(function() {
                        sub_obj = treate_menu($(this));
                        if ($.isEmptyObject(sub_obj) == true) {
                            $(this).trigger("click");
                            errcount++;
                            return false;
                        } else {
                            sub_buttonlist.push(sub_obj);
                        }

                    })
                    newobj.sub_button = sub_buttonlist;
                } else {
                    newobj = treate_menu($pmenu);
                    if ($.isEmptyObject(newobj) == true) {
                        $pmenu.trigger("click");
                        errcount++;
                        return false;
                    }
                }
                menulist.push(newobj);
            })
            //check
        if (errcount > 0) {
            menuerror_tip();
            return false;
        }
        var menu = {
            "button": menulist
        };
        var postdata = {
            appid: appid,
            menu: menu
        };
        $.post("/wxapps/manage/ajax_menu_save", postdata, function(data) {
            if (data.errcode == 0) {
                layer.msg(data.errmsg)
            } else {
                layer.msg(data.errmsg)
            }
        }, "json");
        return false;
    })
    //验证菜单结构
function treate_menu($obj) {
    var pobj = {};
    var vname = $obj.attr("vname");
    var level = $obj.attr("level");
    var mtype = $obj.attr("mtype");
    var murl = $obj.attr("murl");
    var mkey = $obj.attr("mkey");
    if (vname == "") {
        return {};
    }
    if (murl == undefined) {
        murl = "";
    }
    if (mkey == undefined) {
        mkey = "";
    }
    if (mtype == "view") {
        pobj.name = vname;
        pobj.type = mtype;
        pobj.url = murl;
        if (murl == "") {
            return {};
        }
    } else {
        pobj.name = vname;
        pobj.type = mtype;
        pobj.key = mkey;
    }
    return pobj;
}
//菜单结构
function get_menu($obj) {
    var pobj = {};
    var vname = $obj.attr("vname");
    var level = $obj.attr("level");
    var mtype = $obj.attr("mtype");
    var murl = $obj.attr("murl");
    var mkey = $obj.attr("mkey");
    if (murl == undefined) {
        murl = "";
    }
    if (mkey == undefined) {
        mkey = "";
    }
    if (mtype == "view") {
        pobj.name = vname;
        pobj.type = mtype;
        pobj.url = murl;
    } else {
        pobj.name = vname;
        pobj.type = mtype;
        pobj.key = mkey;
    }
    return pobj;
}

function startSort() {
    $(".sortable-mod").sortable({
        cursor: "move",
        items: "div",
        opacity: 0.6,
        revert: true,
        update: function(event, ui) {
            $("#order-start").hide();
            $("#order-end").show();
        }
    });
    $(".menu-btn").sortable({
        cursor: "move",
        items: "li",
        opacity: 0.6,
        revert: true,
        update: function(event, ui) {
            $("#order-start").hide();
            $("#order-end").show();
        }
    });
}

function endSort() {
    $(".menulist").sortable('destroy');
    $(".menu-btn").sortable('destroy');
    $(".sortable-mod").sortable('destroy');
}
//预览
function preview_mpmenu() {
    //获取菜单数据
    var premenulist = [];
    var predata = {
            mlist: premenulist
        }
        //
    $(".edit_menu").each(function() {
        var $pmenu = $(this);
        var newobj = {};
        //子菜单
        var submeucount = $pmenu.parent().find(".sub-edit-menu").length;
        //有子菜单
        if (submeucount > 0) {
            newobj.name = $(this).attr("vname");
            var sub_buttonlist = [];
            var sub_obj = {};
            //子菜单
            $pmenu.parent().find(".sub-edit-menu").each(function() {
                sub_obj = get_menu($(this));
                sub_buttonlist.push(sub_obj);
            })
            newobj.sub_button = sub_buttonlist;
        } else {
            newobj = get_menu($pmenu);
        }
        premenulist.push(newobj);
    })
    var prehtml = template('menu-preview', predata);
    layer.open({
        title: app_mpinfo.authorizer_info.nick_name,
        content: prehtml,
        area: ['320px', '480px'],
        btn: ['退出预览'], //只是为了演示
        yes: function(index, layero) {
            layer.close(index); //如果设定了yes回调，需进行手工关闭
            layer.closeAll();
        }
    });
    return false;
}
jQuery(function() {
    jQuery(document).on("click", "#fNav li", function() {
        jQuery(this).find("div").toggleClass("show");
        jQuery(this).siblings("li").find("div").removeClass("show");
        var mtype = jQuery(this).attr("mtype");
        var murl = jQuery(this).attr("murl");
        if (mtype == "view" && murl != "") {
            window.open(murl, "_blank");
        }
        return false;
    })
    jQuery(document).on("click", "#fNav li div a", function() {
        var mtype = jQuery(this).attr("mtype");
        var murl = jQuery(this).attr("murl");
        if (mtype == "view" && murl != "") {
            window.open(murl, "_blank");
        }
        return false;
    })
})

window.onbeforeunload = function(e) {
    var e = window.event || e;
    if (e.returnValue = '要重新加载该网站吗？') {
        //这里可以做一些你想在页面关闭前最后想做的事情
    } else {
        //当用户点击取消时你想做的事情
    }
}
