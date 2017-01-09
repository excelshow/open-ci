{include file='venue/_header.tpl'}
<style type="text/css">
    .btn-forbid-click {
        background-color: #bababa;
        pointer-events: none;
        cursor: default;
    }
</style>
<link rel="stylesheet" type="text/css" href="{'manager/css/wangEditor.min.css'|cdnurl}">
<!--—自适应布局---->
<div class="container-fluid">
    <div class="row">
        <!-- leftStart -->
        {include file='venue/_leftside.tpl'}
        <!-- leftEnt -->
        <!-- rightStart-->
        <div class="right-main">
            <div class="breadcrumbs-box">
                <ol class="breadcrumb">
                    {include file="venue/_top_sub_navi.tpl"}
                    <li class="active">场馆管理</li>
                </ol>
            </div>
            <div class="right-con">
                <div class="panel panel-blue day-statistics ">
                    <div class="panel-heading">
                        <h3 class="panel-title">场馆管理</h3>
                    </div>
                    <div class="panel-body  event-details">
                        <div class="clearfix addbutton-box">
                                <a class="pull-right add-button" href="/venue/venue/get_add_page">添加新场馆</a>
                        </div>
                        <div class="padd80">
                            <div class="row field-list-root">
                                {if !empty($data)}
                                {foreach from=$data item=item}
                                <div class="col-sm-4 field-list-box {if $item->state == $smarty.const.VENUE_STATE_DOWN}off-the-shelf{/if}">
                                    <div class="field-list-item">
                                        <div class="head exceed-omitted">
                                            {$item->name}
                                        </div>
                                        <div class="main clearfix">
                                            <div class="the-shelves"><span class="icons sales-status"></span>
                                                {if $item->state == $smarty.const.VENUE_STATE_DOWN}下架
                                                {elseif $item->state ==$smarty.const.VENUE_STATE_UPING}上架中
                                                {elseif $item->state ==$smarty.const.VENUE_STATE_UP}上架
                                                {/if}
                                            </div>
                                            <div>
                                                <div class="dt">场馆地址：</div>
                                                <div class="dd exceed-omitted2">{$item->address}</div>
                                            </div>
                                            
                                            <div class="underline ">
                                                {if $item->type_count > 1}
                                                <div class="dt">综合场馆：</div>
                                                {else}
                                                <div class="dt">单一场馆：</div>
                                                {/if}
                                                <div class="dd">
                                                    {$item->type_name}
                                                </div>
                                            </div>                                                 

                                            <div>开放时间</div>
                                            <div>
                                                <div class="dt">工作日：</div>
                                                <div class="dd">{$item->open_time['working_days']['start']}-{$item->open_time['working_days']['end']}</div>
                                            </div>
                                            <div>
                                                <div class="dt">周   末：  </div>
                                                <div class="dd">{$item->open_time['weekend']['start']}-{$item->open_time['weekend']['end']}</div>
                                            </div>
                                            <div>
                                                <div class="dt">节假日： </div>
                                                <div class="dd">{$item->open_time['holidays']['start']}-{$item->open_time['holidays']['end']}</div>
                                            </div>
                                            <div class="underline " style="display:none;">
                                                <div class="dt">卖票数量： </div>
                                                <div class="dd">100张</div>
                                            </div>
                                            <div class="underline "><a href="/venue/venue/view_venue_status?venue_id={$item->venue_id}">查看预订状态</a></div>
                                            <div class="underline "><a href="/venue/area_res/get_list_by_venue?venue_id={$item->venue_id}">场地管理</a></div>
                                            <div class="btn-venue-manage btn-group clearfix pull-right">
                                                <a href="/venue/venue/get_edit_page?venue_id={$item->venue_id}" class="huobtn mrl20 {if $item->state == $smarty.const.VENUE_STATE_DOWN}btn-blue{else}btn-forbid-click{/if}">编辑</a>
                                                {if $item->state == $smarty.const.VENUE_STATE_DOWN}
                                                    <a href="javascript:;" class="btn-edit huobtn js_statusSale mrl20" data-venue-id="{$item->venue_id}" data-venue-state="{$item->state}">上架</a>
                                                {elseif $item->state ==$smarty.const.VENUE_STATE_UPING}
                                                    <a href="javascript:;" class="btn-edit huobtn js_statusSale mrl20" data-venue-id="{$item->venue_id}" data-venue-state="{$item->state}">下架</a>
                                                {elseif $item->state ==$smarty.const.VENUE_STATE_UP}
                                                    <a href="javascript:;" class="btn-edit huobtn js_statusSale mrl20" data-venue-id="{$item->venue_id}" data-venue-state="{$item->state}">下架</a>
                                                {/if}

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {/foreach}
                                {else}
                                {/if}

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- rightEnd -->
    </div>
</div>
<!-- upload -->

<script src="{'manager/lib/plupload/plupload.full.min.js'|cdnurl}"></script>
<script src="{'manager/lib/wangEditor.min.js'|cdnurl}"></script>
<script src="{'manager_contest/js/distpicker.data.js'|cdnurl}"></script>
<script src="{'manager_contest/js/distpicker.js'|cdnurl}"></script>


<script>
    $(function(){
        $(document).on('click',".js_statusSale",function(){
            var _that=$(this);
            var id=_that.attr('data-venue-id');
            var state=_that.attr('data-venue-state');
            var params={
                'venue_id':id
            }
            if(state == 1){
                //如果状态是下架（1） 执行上架操作
                venueShelve(params).done(function(rs){
                    //请求地址 venue/venue/ajax_shelve
                    window.location.reload();
                })
            }else{
                //如果状态是上架中或者上架（2 3 ）执行下架操作
                venueunShelve(params).done(function(rs) {
                    //请求地址 venue/venue/ajax_unshelve
                    window.location.reload();
                })
            }

        });
    })
</script>

{include file='venue/_foot.tpl'}

