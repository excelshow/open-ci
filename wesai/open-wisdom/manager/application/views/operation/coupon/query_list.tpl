{include file="operation/_header.tpl"}
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
                    <li class="active">查询卡密</li>
                </ol>
            </div>
            <div class="right-con">
                <div class="panel panel-blue day-statistics ">
                    <div class="panel-heading">
                        <h3 class="panel-title">销量明细</h3>
                    </div>
                    {include file="./template/_query_list.tpl"}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- rightEnd -->
</div>
<!-- upload -->
<script src="{'manager_operation/lib/js/handlebars.js'|cdnurl}"></script>
{include file="operation/_footer.tpl"}
