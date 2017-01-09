/*base tools ui*/
window.alert = function(msg)
{
    layer.msg(msg);
}
/*left menu*/
jQuery(".left-side li h4").click(function(){
  jQuery(this).parent().addClass("curr").siblings().removeClass("curr");
})

//显示菜单
jQuery(".left-side li h4").each(function(){
    var act_menu = $(this).parent().attr("for");
    console.log(menu_action)
    if(menu_action.indexOf(act_menu) > 0)
    {
      jQuery(this).trigger("click");
    }
})
//jQuery(".left-side dt").eq(1).trigger("click");
/*setting-tab*/
jQuery(".setting-nav span").click(function () {
  jQuery(this).addClass("on").siblings().removeClass("on");
  var index = jQuery(".setting-nav span").index(this);
  jQuery(".hook").hide();
  jQuery(".hook").eq(index).removeClass("hide").show();
  return false;
})
/*createform*/
function create_form(itemid,title){
    var postData = {
      itemid:itemid,
      name:title
    }
    if(confirm("确定创建报名表单？"))
    {
      $.post("/contest/formorder/ajax_add_form",postData,function(data){
      if(data.error == '0'){
            window.location.href = "/contest/formorder/edit_form?formid="+data['lastid'];
        }else{
            alert(data['info']);
        }
    },'json');
    }
    
    return false;
}
/*contest add*/
/*发布赛事*/
function changeContestPublishState(cid, act){
  if(confirm("确定执行该操作?"))
  {
    jQuery.post(act, {cid: cid, act: act}, function (data) {
      if (data['error'] != 0) {
        $(".mask-bg").hide();
        $(".toast-box").hide();
        alert(data['info']);
      } else {
        location.reload();
      }
    }, "json");
    return false;
  }
  return false;
}

$(function(){
 setTimeout(function(){
  $(".loading-toast").remove();
 },"200")
})

