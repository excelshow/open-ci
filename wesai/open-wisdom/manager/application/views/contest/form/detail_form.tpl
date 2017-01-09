

{include file='_header.tpl'}

<link rel="stylesheet" href="{'manager_contest/css/main.css'|cdnurl}">
<!--—自适应布局---->
<div class="container-fluid">
    <div class="row">
            <!-- leftStart -->
            {include file="_leftside.tpl"}
            <!-- leftEnt -->
            <!-- rightStart-->
      <div class="right-main">
          <div class="breadcrumbs-box">
              <ol class="breadcrumb">
	              {include file="../../_top_sub_navi.tpl"}
	              <li><a href="/contest/contest/index">活动管理</a></li>
                <li class="active">修改报名表</li>
              </ol>
          </div>
          <div class="right-con">
            {literal}
            <style type="text/css">
            .glyphicon-remove,.glyphicon-plus,.glyphicon-minus,.left-side{display: none;}
            .right-box{margin-left:0}
            </style>
            {/literal}
            <script src="{'manager_contest/js/template.js'|cdnurl}"></script>
            <script src="{'manager_contest/js/jquery-ui.js'|cdnurl}"></script>
            <div class="right-box">
                <div class="content">
                <div class="setting-content">
                    <!--right-->
                    <div class="preview_model clear" id="preview_for_test">
                        <div class="pull-left">
                        当前为预览页不记录内容，请勿发送给他人填写！
                        </div>
                        <div class="pull-right">
                          <a href="javascript:history.go(-1)" ><span class="glyphicon glyphicon-arrow-left"></span>返回</a>
                        </div>
                    </div>
                    <div class="show-list" style="float:left;width:100%">
                        <div class="q-list">
                            <div class="tmpl-list pd10" style="border-bottom:solid 1px #eee">
                                <!-- <h5 class="text-center">表单名称</h5> -->
                            </div>
                            <!-- 问题列表 -->
                            <div class="question-list" id="question-list">
                              <ul class="sortable"></ul>
                            </div>
                             <!-- 列表 -->
                             {include file='./_preitemlist.tpl'}
                        </div>
                    </div>
                  </div>
                </div>
            </div>
          </div>
      </div>
            <!-- rightEnd -->
    </div>
</div>

{include file="_foot.tpl"}
<script type="text/javascript">
//初始化列表问题列表
(function ($) {
  $.getUrlParam = function (name) {
   var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
   var r = window.location.search.substr(1).match(reg);
   if (r != null) return unescape(r[2]); return null;
  }
 })(jQuery);
 var postinfo={
      'form_id':$.getUrlParam('form_id')
 }
$.get("/contest/form/ajax_list",postinfo,function(data){

   if(data.error ==0){
        for(var i=0;i<data.data.length;i++){
            data.data[i].option_values=JSON.parse(data.data[i].option_values)
        }
        var formdatalist = {
            isEmpty:data.data.length,
            list: data.data,
        };
        var objlist_html = template('initformqlist', formdatalist);
        $("#question-list ul").html(objlist_html);
        return false;
   }else{
     alert(data.info);
   }
},"json")

</script>

