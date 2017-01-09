{include file="dist/_header.tpl"}
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
                        <h3 class="panel-title">销量明细</h3>
                    </div>
                    {include file="./template/_sales_list.tpl"}
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
{include file="dist/_footer.tpl"}
