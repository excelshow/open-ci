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
                    <li class="">营销活动</li>
                </ol>
            </div>
            <div class="right-con">
                <div class="panel panel-blue day-statistics">
                    <div class="panel-heading">
                        <h3 class="panel-title">营销活动查询</h3>
                    </div>
                    {include file="./template/_market_list.tpl"}
                </div>
                <!-- 翻页 -->
                <!-- <nav>{$page_ctrl}</nav> -->
            </div>
        </div>
    </div>
</div>
<!-- rightEnd -->
</div>
<!-- upload -->
<script src="{'operation/lib/js/handlebars.js'|cdnurl}"></script>
{include file="operation/_footer.tpl"}
