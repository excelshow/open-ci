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
        $(".second-level").each(function(){
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
$(document).on("click", ".first-level", function() {
        var ops = {
            name: "菜单名称",
            level: 1,
            type: "view"
        };
        var sub_html = '';
        sub_html+='<li class="edit_menu jsMenu pre_menu_item grid_item jslevel1 ui-sortable ui-sortable-disabled size1of3" level="1" vname="' + ops.name + '" mtype="view" murl="" mkey="" id="menu_0">'
        sub_html+='<a href="javascript:void(0);" class="pre_menu_link" draggable="false">' 
        sub_html+='<i class="icon_menu_dot js_icon_menu_dot dn"></i>'
        sub_html+='<i class="icon20_common sort_gray"></i>'
        sub_html+='<span class="js_l1Title" >' + ops.name + '</span>'
        sub_html+='</a>'
        sub_html+='<div class="sub_pre_menu_box js_l2TitleBox">'
        sub_html+='<ul class="sub_pre_menu_list">'
        sub_html+='<li class="js_addMenuBox list-item plus second-level">'
        sub_html+='<a href="javascript:void(0);" class="jsSubView js_addL2Btn" title="最多添加5个子菜单" draggable="false">'
        sub_html+='<span class="sub_pre_menu_inner js_sub_pre_menu_inner">'
        sub_html+='<i class="icon14_menu_add"></i>'
        sub_html+='</span>'
        sub_html+='</a>'
        sub_html+='</li>'
        sub_html+='</ul>'
        sub_html+='<i class="arrow arrow_out"></i>'
        sub_html+='<i class="arrow arrow_in"></i>'
        sub_html+='</div>'
        sub_html+='</li>'
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
        var sub_html='';
            sub_html+= '<li class="sub-edit-menu jslevel2" level="2" class="list-item  sub-edit-menu" level="2" vname="' + ops.name + '" mtype="view" murl="" mkey="">'
            sub_html+= '<a href="javascript:void(0);" class="jsSubView" draggable="false">'
            sub_html+= '<span class="sub_pre_menu_inner js_sub_pre_menu_inner">'
            sub_html+= '<i class="icon20_common sort_gray"></i>'
            sub_html+= '<span class="js_l2Title">' + ops.name + '</span>'
            sub_html+= '</span>'
            sub_html+= '</a>'
            sub_html+= '</li>'
        $(this).before(sub_html);
        $(this).prev("li.sub-edit-menu").trigger("click");
        if ($(this).parent("ul").find(".sub-edit-menu").length > 4) {
            $(this).hide();
        }
        $(this).parents('.jslevel1').attr("mtype", "");
        return false;
    })
    //设置菜单
$(document).on("click", ".edit_menu,.sub-edit-menu", function() {
        var level = $(this).attr("level");
        var name = $(this).attr("vname");
        var type = $(this).attr("mtype");
        var value = $(this).attr("mvalue");
        var url = $(this).attr("murl");
        var menuTitle="菜单";
        var displayBlock="block";
        var displayNone="none";
        var alterText="已添加子菜单，仅可设置菜单名称。";
        if(level=='2'){
            menuTitle="子菜单";
        }
        if(type!= 'view'){
            displayBlock="none";
            displayNone="block";
            if(level=='2'){
                alterText="暂只支持类型为view和text的菜单修改。";
            }
        }

        if(level== "1"){
            if(type != '' && type != 'view' ){
                alterText="暂只支持类型为view和text的菜单修改。";
            }
        }
        var ops = {
            name: name,
            level: level,
            type: type,
            url: url,
            value: value,
            menuTitle:menuTitle,
            displayBlock:displayBlock,
            displayNone:displayNone,
            alterText:alterText
        }
        $(".current").removeClass("current");
        $(this).addClass("current");
        ZhMenu.editmenu(ops);

        if(type=='text'){
            tabSelected(0);
        }else if(type=='view'){
            tabSelected(1);
        }
        return false;
    })


$(document).on('click','.js_radio_selected',function(){
    var index = $(this).index();
    tabSelected(index);
})
function tabSelected(index){
    $('.js_radio_selected').eq(index).addClass('selected').siblings().removeClass('selected');
    $('.menu_content_container').find('.menu_content').eq(index).show().siblings().hide();
}
//删除子菜单
$(document).on("click", "#jsDelBt", function() {
        /*弹出层*/
        var menu_name = $("#menu-name").val();
        layer.open({
            title: '温馨提示',
            content: '<div class="text-danger">删除确认</div>删除后' + menu_name + '”子菜单下设置的内容将被删除',
            btn: ['确定', '取消'], //只是为了演示
            yes: function(index, layero) {
                //do something
                $(".current").parent().find(".second-level").show();
                if ($(".current").parent("ul").find(".sub-edit-menu").length < 2) {
                    $(".current").parent().parent().find("strong").attr("mtype", "view");
                }
                $(".current").remove();
                $("#edit-box").html('<div id="js_none" class="menu_initial_tips tips_global" >点击左侧菜单进行编辑操作</div>');
                layer.close(index); //如果设定了yes回调，需进行手工关闭
            },
            btn2: function() {
                layer.closeAll();
            }
        });
        return false;
    });
//更多链接
$(document).on("click", "#js_appmsgPop", function() {
        /*弹出层*/
        var option="";
        for(var x in links ){
           option+='<option value="'+links[x].link+'"  name="'+links[x].name+'">'+links[x].name+'</option>';
        }
        var menu_name = $("#menu-name").val();
        layer.open({
            title: '选择链接',
            content: '<select name="state" id="link-val" class="form-control auto_w mgr10"><option value="">请选择</option>'+option+'</select>',
            area: ['400px', 'auto'],
            btn: ['确定', '取消'], //只是为了演示
            yes: function(index, layero) {
                //do something
                var _linkVal=$("#link-val").val();
                var _name=$("#link-val").attr('name')

                $("#menu-url").val(_linkVal);
                $("#menu-url").trigger("blur");
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
        $(".current > a").find('span').text(thisname);
        $(".current").attr("vname", thisname);
        $(this).parent().find(".menu-tip").hide().text();
        $(this).val(thisname);
        isAllowSubmit = true;
    })
    //修改url

function IsURL(str_url) {
    var RegUrl = new RegExp();
    RegUrl.compile("^[A-Za-z]+://[A-Za-z0-9-_]+\\.[A-Za-z0-9-_%&\?\/.=#]+$");
    if (!RegUrl.test(str_url)) {
        return false;
    }
    return true;
}
$(document).on("blur", "#menu-url", function() {
    var murl = $(this).val();
    var rgUrl = IsURL(murl);
    if (!rgUrl) {
        $(this).parents('.frm_controls').find(".js_warn").show();
        return false;
    }
    $(".current").attr("murl", murl);
    $(".current").attr("mtype", 'view');
    $(".current").removeAttr("mvalue");
    $(this).parents('.frm_controls').find(".js_warn").hide();
    return false;
})
$(document).on("blur", "#menu-text", function() {
    var mvalue = $(this).val();
    if (mvalue=="") {
        $(".url-tip").removeClass("hidden").show().text("信息不能为空");
        return false;
    }
    $(".current").attr("mvalue", mvalue);
    $(".current").attr("mtype", 'text');
    $(".current").removeAttr("murl");
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
                $(".current").parent().remove();
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
    var vname = $(".current").attr("vname");
    var murl = $(".current").attr("murl");
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
    $("#edit-box").html('<div id="js_none" class="menu_initial_tips tips_global" >请通过拖拽左边的菜单进行排序</div>');
    $(document).removeClass("current");
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
        $("#edit-box").html('<div id="js_none" class="menu_initial_tips tips_global" >点击左侧菜单进行编辑操作</div>');
        $(".edit_menu,.sub-edit-menu").off("click");
        endSort();
        return false;
    })
    /*
     * 保存并发布
     */
$("#submit_button").click(function() {
        var menulist = [];
        var errcount = 0;
        var appid=getAppid();
        $(".edit_menu").each(function() {
                var $pmenu = $(this);
                var newobj = {};
                //子菜单
                var submeucount = $pmenu.find(".sub-edit-menu").length;
                //有子菜单
                if (submeucount > 0) {
                    newobj.name = $(this).attr("vname");
                    var sub_buttonlist = [];
                    var sub_obj = {};
                    //子菜单
                    $pmenu.find(".sub-edit-menu").each(function() {
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
    var pobj         = {};
    var vname        = $obj.attr("vname");
    var level        = $obj.attr("level");
    var mtype        = $obj.attr("mtype");
    var murl         = $obj.attr("murl");
    var mkey         = $obj.attr("mkey");
    var mmedia_id    = $obj.attr("mediaId");
    var sub_button   = $obj.attr("subButton");
    if (vname == "") {
        return {};
    }
    if (murl == undefined) {
        murl = "";
    }
    if (mkey == undefined) {
        mkey = "";
    }
    if (mmedia_id == undefined) {
        mmedia_id = "";
    }
    if (sub_button == undefined) {
        sub_button = "";
    }
    if (mtype == "view") {
        pobj.name = vname;
        pobj.type = mtype;
        pobj.url = murl;
        if (murl == "") {
            return {};
        }
    }else if(mtype=="scancode_push" || mtype=="pic_sysphoto" || mtype=="pic_photo_or_album" || mtype=="pic_weixin"){
        pobj.name       = vname;
        pobj.type       = mtype;
        pobj.key        = mkey;
        pobj.sub_button = sub_button
    }else if(mtype=="media_id"||mtype=="view_limited"){
        pobj.name = vname;
        pobj.type = mtype;
        pobj.mmedia_id = mmmedia_id;
    }else if(mtype=="location_select"){
            pobj.name       = vname;
            pobj.type       = mtype;
            pobj.key        = mkey;
    }else if(mtype=="click"){
        pobj.name       = vname;
        pobj.type       = mtype;
        pobj.key        = mkey;
    }else{
        pobj.name       = vname;
        pobj.type       = mtype;
        pobj.key        = mkey;
        pobj.mmedia_id = mmmedia_id;
        pobj.sub_button = sub_button
    }
    return pobj;
}
//菜单结构


function startSort() {
    $(".sortable-mod").sortable({
        cursor: "move",
        items: "li.jslevel1",
        opacity: 0.6,
        revert: true,
        update: function(event, ui) {
            $("#order-start").hide();
            $("#order-end").show();
        }
    });
    $(".menu-btn").sortable({
        cursor: "move",
        items: "li.jslevel2",
        opacity: 0.6,
        revert: true,
        placeholder: "ui-state-highlight",
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
$(document).on('click','#preview-menu',function() {
    //获取菜单数据
    var premenulist = [];
    var predata = {
            mlist: premenulist,
            dataTitle:data.title
        }
        //
    $(".edit_menu").each(function() {
        var $pmenu = $(this);
        var newobj = {};
        //子菜单
        var submeucount = $pmenu.find(".sub-edit-menu").length;
        //有子菜单
        if (submeucount > 0) {
            newobj.name = $(this).attr("vname");
            var sub_buttonlist = [];
            var sub_obj = {};
            //子菜单
            $pmenu.find(".sub-edit-menu").each(function() {
                sub_obj = treate_menu($(this));
                sub_buttonlist.push(sub_obj);
            })
            newobj.sub_button = sub_buttonlist;
        } else {
            newobj = treate_menu($pmenu);
        }
        premenulist.push(newobj);
    })
    predata['size1of']=predata.mlist.length;
    var prehtml = template('menu-preview', predata);
    layer.open({
        title: app_mpinfo.authorizer_info.nick_name,
        content: prehtml,
        // area: ['320px', '480px'],
        // btn: ['退出预览'], //只是为了演示
        yes: function(index, layero) {
            layer.close(index); //如果设定了yes回调，需进行手工关闭
            layer.closeAll();
        }
    });
    return false;
})
$(document).on('click','#viewClose',function(){
    $('.layui-layer-shade').remove();
    $('.layui-layer').remove();
});
$(document).on('click','.jsViewLi',function(){
    $(this).find('.jsSubViewDiv').show();
    $(this).siblings().find('.jsSubViewDiv').hide();
})
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
