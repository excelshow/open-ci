{include file="operation/_header.tpl"}
<link rel="stylesheet" type="text/css" href="{'manager_operation/lib/css/wangEditor.min.css'|cdnurl}">
<!-- 自适应布局 -->
<div class="container-fluid">
    <div class="row">
        <!-- leftStart -->
        {include file="operation/_leftside.tpl"}
        <!-- leftEnt -->
        <!-- rightStart-->
        <div class="right-main">
            <div class="breadcrumbs-box">
                <ol class="breadcrumb">
                    {include file="operation/_top_sub_navi.tpl"}
                    <li class="active">优惠券</li>
                    <li class="active">设置优惠券规则</li>
                </ol>
            </div>
            <div class="right-con">
                <div class="panel panel-blue day-statistics ">
                    <div class="panel-heading">
                        <h3 class="panel-title">设置优惠券规则</h3>
                    </div>
                    {include file="./template/_edit_coupon.tpl"}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- rightEnd -->
</div>
<!-- upload -->
<script src="{'manager_operation/lib/js/handlebars.js'|cdnurl}"></script>
<script src="{'manager_operation/lib/js/layer.js'|cdnurl}"></script>
{include file="operation/_footer.tpl"}
