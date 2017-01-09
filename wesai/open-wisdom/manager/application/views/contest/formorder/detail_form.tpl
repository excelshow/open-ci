

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
                                <h5 class="text-center">{$formdata->name}</h5>
                            </div>
                            <!-- 问题列表 -->
                            <div class="question-list" id="question-list">
                              <ul class="sortable"></ul>
                            </div>
                             <!-- 列表 -->
                             {include file='../formorder/_preitemlist.tpl'}
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
var formjson = {$formqlist|json_encode};
var formid = {$formdata->pk_enrol_form};
var formdatalist = {
    isEmpty:formjson.length,
    list: formjson,
};
var objlist_html = template('initformqlist', formdatalist);
$("#question-list ul").html(objlist_html);
</script>

