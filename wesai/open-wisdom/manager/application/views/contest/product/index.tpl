{include file='_header.tpl'}
<link rel="stylesheet" type="text/css" href="{'manager/css/wangEditor.min.css'|cdnurl}">
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
          {include file="../../_top_two_nav.tpl"}
          <li class="active">同步商品</li>
        </ol>
      </div>
      <div class="right-con">
        <div class="panel panel-blue">
          <div class="panel-heading">
            <h3 class="panel-title">同步商品结果</h3>
          </div>
          {if !empty($result)}{$result->result}{/if}
        </div>
    </div>
  </div>
  <!-- rightEnd -->
</div>
</div>

{include file="_foot.tpl"}

