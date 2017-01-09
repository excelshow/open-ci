{include file='../header.tpl'}
<script src="{'manager_contest/js/template.js'|cdnurl}"></script>
<script src="{'manager_contest/js/jquery-ui.js'|cdnurl}"></script>
<script src="{'manager_contest/js/formdata.list.js'|cdnurl}"></script>
<div class="right-box">
	<div class="top-nav"><a href="/">主页</a> >> 报名管理 >> 报名模版  >> 新建</div>
	<div class="content">
	<div class="inner-bg">
	<div class="setting-content">
		<!-- left -->
		<!--right-->
        <div class="show-list">
        	<div class="save-btn-list">
        		 <button type="button" class="btn btn-success mgr20" id="saveformbtn">保存</button>
        		 <button type="button" class="btn btn-default" onclick="window.location.reload()">取消</button>
        	</div>
        	<div class="q-list">
        		<div class="tmpl-list pd10" style="border-bottom:solid 1px #eee">
					<dl>
					<dt>报名表名称<i>*</i></dt>
					<dd><input type="text" class="form-control" placeholder="请填写报名表名称" id="fname" value="{$itemname}报名表"/></dd>
					<input type="hidden" name="itemid" id="itemid" value="{$itemid}">
					</dl>
			    </div>
        	</div>
        </div>
	  </div>
	</div>
    </div>
</div>
</div>
{literal}
<!-- 基本操作 -->
<!--组件列表-->
<script type="text/javascript">
//添加组件列表
$("#group-list-box li").on("click",function()
{
	var o_index = $(this).attr("order");
	var _type_ = $(this).attr("_type_");
	var o_obj = formConfig[_type_];
	var  rankorder = $("#question-list ul li").length+1;
	var idhasItem = $("#"+_type_).text();
	if(idhasItem!="")
	{
		alert("该选项已经存在，不能重复添加");
		return false;
	}
	//添加组件
	var formtooldata = {
		"rankorder":rankorder,
		"itemobj":o_obj
	}
	var obj_html = template('formqlist', formtooldata);
	$(".pre-text").remove();
	$("#question-list ul").append(obj_html);
	$(this).addClass("disabled");
	
})
//拖动排序
$(function() {
	 $( ".sortable" ).sortable({
	 cursor: "move",
	 items :"li",                        //只是li可以拖动
	 opacity: 0.6,                       //拖动时，透明度为0.6
	 revert: true,                       //释放时，增加动画
	 update : function(event, ui){       //更新排序之后
	     udpateItemOrder();
	  }
	  });
 });
//移除组件
$(document).on("click",".del_item",function(){
	var did = $(this).attr("did");
	$("#"+did).remove();
    $("#x_"+did).removeClass("disabled");
    udpateItemOrder();
    return false;
})
function udpateItemOrder(){
	$("#question-list ul li").each(function(){
		  var q_index= $(this).index() +1;
		  $(this).find(".q-order").text(q_index);
	})
}
//保存表单数据
$("#saveformbtn").click(function(){
	var name = $("#fname").val();
	if(name=="")
	{
		$("#fname").focus();
		return false;
	}
	var itemid = $("#itemid").val();
	var formstr = {
	  '_type_'    : 'form',   // 表单对象标识
	  '_collections_' :[]
	}
	$("#question-list ul li").each(function(){
		var typeId = $(this).attr("id");
		var formobj = formConfig[typeId];
		var _required_ = $(this).find(".set-R input[type='checkbox']").is(':checked');
		formobj._realize_._override_._required_ = _required_;
		if(typeId=="policybox")
		{
			var plocytxt = $(this).find("textarea").val();
			formobj._realize_._override_._intro_ = plocytxt;
		}
		if(typeId=="receivelocationbox")
		{
			var receivelocationtxt = $(this).find("textarea").val();
			var optionarr = receivelocationtxt.split(",");
         	for (var i = 0; i < optionarr.length; i++) {
        		var option = {};
        		option.txt = optionarr[i];
         		option.val = (i + 1).toString();
         		formobj._realize_._override_._options_.push(option);
    		}
		}
		formstr._collections_.push(formobj);
	})
	if(formstr._collections_.length<1)
	{
		alert("至少选择一个选项");
		return false;
	}
	//获取提名对象
	var postData = {
		"name":name,
		"itemid":itemid,
		"formstr":formstr
	}
	//console.log(postData);
	$.post("/contest/formorder/ajax_addform",postData,function(data){
        if(data.error == '0'){
            window.location.href = "/contest/formorder/detail_form?formid="+data['lastid'];
        }else{
            alert(data['info']);
        }
    },'json');
	return false;
})
$(".g-list li").each(function() {
	var text = $.trim($(this).text());
	if(text.length <6)
	{
		$(this).css("line-height","34px");
	}
})
</script>
{/literal}
