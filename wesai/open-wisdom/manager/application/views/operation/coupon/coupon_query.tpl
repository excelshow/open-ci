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
                    <li class="">优惠券</li>
                    <li class="">优惠券规则查询</li>
                </ol>
            </div>
            <div class="right-con">
                <div class="panel panel-blue day-statistics ">
                    <div class="panel-heading df">
                        <h3 class="panel-title active"><a href="/dist/supplier/all_dist">优惠券规则查询</a></h3>
                    </div>
                    {include file="./template/_coupon_query.tpl"}
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
