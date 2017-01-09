

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
            <div class="row">
                <div class="col-sm-12">
                    <div class="app-list">
                        <div class="ibox-content">
                                
                            <div class="setting-content">
                                <div class="clearfix">
                                    <div class="pull-right">
                                        <a href="javascript:history.go(-1)" ><span class="glyphicon glyphicon-arrow-left"></span>返回</a>
                                    </div>
                                </div>
                                <!-- left -->
                                {include file='../formorder/_leftform.tpl'}
                                <!--right-->
                                
                                
                                <div class="show-list">

                                    <div class="q-list">
                                        <div class="tmpl-list pd10" style="border-bottom:solid 1px #eee">
                                            <div class="text-center pd10">报名表单<i>*</i><a href="/contest/formorder/detail_form?formid={$formdata->pk_enrol_form}&itemid={$formdata->fk_contest_items}" class="btn btn-success btn-sm pull-right">预览报名表单</a></div>
                                                <!-- 
                                                    <input type="text" class="form-control" placeholder="请填写报名表名称" id="fname" value="{$formdata->name}" />
                                                -->
                                                <input type="hidden" name="formid" id="formid" value="{$formdata->pk_enrol_form}">
                                        </div>
                                        <!-- 问题列表 -->
                                        <div class="question-list" id="question-list">
                                            <ul class="sortable">
                                            </ul>
                                        </div>
                                        <!-- 列表 -->
                                        {include file='../formorder/_qitemlist.tpl'}
                                        <!-- 添加组件 -->
                                        {include file='../formorder/_buildform.tpl'}
                                    </div>
                                </div>
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
<script src="{'manager_contest/js/template.js'|cdnurl}"></script>
<script src="{'manager_contest/js/jquery-ui.js'|cdnurl}"></script>
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
<!-- 基本操作 -->
<script src="{'manager_contest/js/formjs.js'|cdnurl}"></script>
 

