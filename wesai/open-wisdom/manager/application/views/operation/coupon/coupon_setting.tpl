{include file="dist/_header.tpl"}
<link rel="stylesheet" type="text/css" href="{'manager_dist/css/wangEditor.min.css'|cdnurl}">
<!-- 自适应布局 -->
<div class="container-fluid">
    <div class="row">
        <!-- leftStart -->
        {include file="dist/_leftside.tpl"}
        <!-- leftEnt -->
        <!-- rightStart-->
        <div class="right-main">
            <div class="breadcrumbs-box">
                <ol class="breadcrumb">
                    {include file="dist/_top_sub_navi.tpl"}
                    <li class="active">我是供货商</li>
                </ol>
            </div>
            <div class="right-con">
                <div class="panel panel-blue day-statistics ">
                    <div class="panel-heading">
                        <h3 class="panel-title">创建分销方案</h3>
                    </div>
                    {include file="./template/_add_dist.tpl"}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- rightEnd -->
</div>
<!-- upload -->
<script src="{'manager_dist/js/add_dist.js'|cdnurl}"></script>
<script src="{'manager_dist/lib/handlebars.js'|cdnurl}"></script>
{include file="dist/_footer.tpl"}
