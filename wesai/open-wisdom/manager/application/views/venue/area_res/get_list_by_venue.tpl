{include file='venue/_header.tpl'}
<link rel="stylesheet" type="text/css" href="{'manager/css/wangEditor.min.css'|cdnurl}">
<style type="text/css">
    .btn-forbid-click {
        background-color: #bababa;
        pointer-events: none;
        cursor: default;
    }
</style>
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
                    <li><a href="/venue/venue/get_list">场馆管理</a></li>
                    <li class="active">场地管理</li>
                </ol>
            </div>
            <div class="right-con">
                <div class="panel panel-blue day-statistics ">
                    <div class="panel-heading">
                        <h3 class="panel-title">场地管理</h3>
                    </div>
                    <div class="panel-body  event-details">
                        <div class="clearfix addbutton-box">
                                <a class="pull-right add-button" href="/venue/area_res/get_add_page?venue_id={$venue_id}">添加新场地</a>
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
                                                        {if $item->state == $smarty.const.VENUE_AREA_RES_STATE_UNSHELVE}下架
                                                        {elseif $item->state == $smarty.const.VENUE_AREA_RES_STATE_SHELEVING}上架中
                                                        {elseif $item->state ==$smarty.const.VENUE_AREA_RES_STATE_SHELVE}上架
                                                        {elseif $item->state ==$smarty.const.VENUE_AREA_RES_STATE_CAN_SELL}可售
                                                        {/if}
                                                    </div>
                                                    <div class="underline">
                                                        <div class="dt">场地项目：</div>
                                                        <div class="dd">{$item->type_name}</div>
                                                    </div>
                                                    <div class="site-main">
                                                    <div>价格</div>
                                                        {foreach from=$item->rules item=rule key=key}
                                                            {if $key == 1}
                                                                <div>工作日：</div>
                                                            {else if $key == 2}
                                                                <div>周   末：</div>
                                                            {else}
                                                                <div>节假日：</div>
                                                            {/if}

                                                            {foreach from=$rule item=value}
                                                                <div>
                                                                    <div class="dt">{$value->time_start|date_format:"%H:%M"}-{$value->time_end|date_format:"%H:%M"}</div>
                                                                    <div class="dd text-right">{$value->price}元</div>
                                                                </div>
                                                            {/foreach}
                                                        {/foreach}
                                                    </div>
                                                    <div class="btn-venue-manage btn-group clearfix pull-right">
                                                        <a href="/venue/area_res/get_edit_page?venue_area_res_id={$item->venue_area_res_id}" class="huobtn btn-blue mrl20 {if $item->state == $smarty.const.VENUE_AREA_RES_STATE_UNSHELVE}btn-blue{else}btn-forbid-click{/if}">编辑</a>
                                                        {if $item->state == $smarty.const.VENUE_AREA_RES_STATE_UNSHELVE}
                                                            <a href="javascript:;" class="btn-edit huobtn mrl10 update_state" data-state="{$item->state}" data-venue-area-res-id="{$item->venue_area_res_id}">上架</a>
                                                        {elseif $item->state == $smarty.const.VENUE_AREA_RES_STATE_SHELEVING}
                                                            <a href="javascript:;" class="btn-edit huobtn mrl10">上架中</a>
                                                        {elseif $item->state == $smarty.const.VENUE_AREA_RES_STATE_SHELVE || $item->state == $smarty.const.VENUE_AREA_RES_STATE_CAN_SELL}
                                                            <a href="javascript:;" class="btn-edit huobtn mrl10 update_state" data-state="{$item->state}" data-venue-area-res-id="{$item->venue_area_res_id}">下架</a>
                                                        {/if}
                                                        {if $item->state == $smarty.const.VENUE_AREA_RES_STATE_SHELVE}
                                                        <a href="javascript:;" class="btn-edit huobtn mrl10 update_to_sell" data-state="{$item->state}" data-venue-area-res-id="{$item->venue_area_res_id}">可售</a>
                                                        {/if}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    {/foreach}
                                {/if}
                            </div>
                        </div>
                    </div>
                    <nav>{$page_ctrl}</nav>
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
<script type="text/javascript">
    $(document).on('click',".update_state",function(){
        var _that = $(this);
        var id = _that.data('venue-area-res-id');
        var state = _that.data('state');
        var params = {
            'venue_area_res_id':id
        }

        if(state == 1){
            //如果状态是下架（1） 执行上架操作
            ajaxAreaResShelve(params).done(function(rs){
                window.location.reload();
            })
        }else{
            //如果状态是上架(3)执行下架操作
            ajaxAreaResUnshelve(params).done(function(rs) {
                window.location.reload();
            })
        }
    });
    $(document).on('click',".update_to_sell",function(){
        var _that = $(this);
        var id = _that.data('venue-area-res-id');
        var state = _that.data('state');
        var params = {
            'venue_area_res_id':id
        }
        ajaxAreaResToSell(params).done(function(rs){
            window.location.reload();
        })
    });
</script>
{include file='venue/_foot.tpl'}

