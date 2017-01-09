{include file='_header.tpl'}
<link rel="stylesheet" type="text/css" href="{'manager/css/wangEditor.min.css'|cdnurl}">
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
          {include file="../../_top_two_nav.tpl"}
          <li>
            <a href="/contest/contest/index">活动管理</a>
          </li>
          <li class="active">编辑活动</li>
        </ol>
      </div>
      <div class="right-con">
        <div class="panel panel-blue">
          <div class="panel-heading">
            <h3 class="panel-title">编辑活动</h3>
          </div>
          {include file="../contest/_events_edit.tpl"}
        </div>
        
    </div>
  </div>
  <!-- rightEnd -->
</div>
</div>
<!-- upload -->
<script type="text/javascript">var serverPath = '{RES_FILEVIEW_URL}/';</script>
<script src="{'manager/lib/plupload/plupload.full.min.js'|cdnurl}"></script>
<script src="{'manager/lib/wangEditor.min.js'|cdnurl}"></script>
<script src="{'manager_contest/js/distpicker.data.js'|cdnurl}"></script>
<script src="{'manager_contest/js/distpicker.js'|cdnurl}"></script>
{include file='../contest/_jsform.tpl'}
{include file="_foot.tpl"}

