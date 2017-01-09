<div class="clearfix addbutton-box">
    <a class="pull-right add-button" href="/operation/market/add_activity">创建新活动</a>
</div>
<div class="panel panel-blue day-statistics">
    <div id="market-list" class="wesai-dist"></div>
</div>
<!-- 翻页 -->
<nav>{$page_ctrl}</nav>
{include file="operation/template/_disalog.tpl"}
{include file="./_bing_coupon.tpl"}
{literal}
<script id="market-list-template" type="text/x-handlebars-template">
    <table class="table txt-cen">
        <thead class="bg-f6f3f3">
            <tr>
                <th>编号</th>
                <th>活动标题</th>
                <th>活动开始时间</th>
                <th>活动结束时间</th>
                <th>排序</th>
                <th>状态</th>
                <th>已关联的优惠券</th>
                <th>操作</th>
            </tr>
        </thead>
        {{#each data}}
        <tbody>
            <tr>
                <td>{{activity_id}}</td>
                <td>{{name}}</td>
                <td>{{time_start}}</td>
                <td>{{time_end}}</td>
                <td>{{orderby}}</td>
                <td>{{state_name}}</td>
                <td class="coupon_list">
                    {{#if ruleList_state}}
                        {{#each ruleList}}
                        <p class="rel" data-rule-id="{{voucher_rule_id}}" data-type="{{type}}"> {{name}}
                        <span class="del_coupon"><i class="iconfont icon-lajitong"></i></span>
                        </p>
                        {{/each}}
                    {{else}}
                        {{#each ruleList}}
                        <p class="rel" data-rule-id="{{voucher_rule_id}}" data-type="{{type}}">{{name}}
                        </p>
                        {{/each}}
                    {{/if}}
                </td>
                <td class="params_id" data-corp-id="{{corp_id}}" data-plan-id="{{activity_id}}" data-name="{{name}}">
                    {{#if stoping}}
                    <a class="market-state seedetails-btn" href="javascript:;" data-change-type="stop">下架</a>
                    <a class="seedetails-btn" href="javascript:;" style="visibility: hidden;">下架</a>
                    <a class="seedetails-btn" href="javascript:;" style="visibility: hidden;">关联优惠券</a>
                    {{/if}}
                    {{#if edit}}
                    <a class="edit-market seedetails-btn" href="/operation/market/edit_activity?activity_id={{activity_id}}">编辑</a>
                    <a class="market-state seedetails-btn" href="javascript:;" data-change-type="start">发布</a>
                    <a class="bing-coupon seedetails-btn" href="javascript:;" data-change-type="bing">关联优惠券</a>
                    {{/if}}
                </td>
            </tr>
        </tbody>
        {{/each}}
    </table>
</script>
{/literal}

{literal}
<script type="text/javascript">
var ruleList,obj;
var DATA = {
    data: []
};
//  状态处理
function stateDispose(data) {
    switch (data.state) {
        case 1:
            data.edit = 1
            data.ruleList_state = 1;
            break;
        case 2:
            data.stoping = 1;
            break;
        case 3:
            data.edit = 1;
            data.ruleList_state = 1;
            break;
        case 4:
            break;
    }
    return data;
}
{/literal}
{if !empty($data)} {foreach from=$data item=item}
var list = [];
{if !empty($item->operation)} {foreach from=$item->operation item=bing}
    ruleList = {
        voucher_rule_id:{$bing->voucher_rule_id},
        name:"{$bing->name}",
        type:"{$bing->type}"
    };
    list.push(ruleList);
{/foreach} {/if}
    obj = {
        corp_id: {$item->corp_id},
        activity_id: {$item->activity_id},
        name: "{$item->name}",
        time_start: "{$item->time_start}",
        time_end: "{$item->time_end}",
        orderby: "{$item->orderby}",
        number: {$item->number},
        number_max: {$item->number_max},
        number_invite_one: {$item->number_invite_one},
        state: {$item->state},
        desc_rule: null,
        desc: null,
        ruleList:list
    }
    obj = stateDispose(obj);
    {foreach from=$OPERATION_ACTIVITY_STATE item=item}
        obj.state_name = {$item}[obj.state];
    {/foreach}
    DATA.data.push(obj);
{/foreach} {/if}
{literal}
$(function() {
    loadList(DATA)
    function loadList(data) {
        var distList = $("#market-list-template").html();
        distList = Handlebars.compile(distList);
        $('#market-list').html(distList(data));
    }

    //  关联优惠券
    $(document).on('click', 'a.bing-coupon', function() {
        var corpId = $(this).parent().attr('data-corp-id');
        var planId = $(this).parent().attr('data-plan-id');
        var name = $(this).parent().attr('data-name');
        var changeType = $(this).attr('data-change-type');
        var obj = {
            'confirm': '关联',
            'cancel': '取消',
            'corp_id': corpId,
            'plan_id': planId,
            'title':'关联优惠券',
            'name':name,
            'change_type': changeType,
        }
        var params = {
            corp_id:corpId,
            activity_id:planId
        }
        get_nobind_operation(params).done(function(data){
            if (!data.error) {
                obj.data = data.data;
                bingCoupon(obj);
                $('#bing-coupon').show();
            }
        })
    })
    function bingCoupon(obj) {
        var bingCouponHtml = $("#bing-coupon-template").html();
        bingCouponHtml = Handlebars.compile(bingCouponHtml);
        $('#bing-coupon').html(bingCouponHtml(obj));
    };

    //  关闭优惠券链接
    $(document).on('click',".rel .del_coupon",function(){
        var _this = $(this)
        var corpId = $(this).parent().parent().next().attr('data-corp-id');
        var planId = $(this).parent().parent().next().attr('data-plan-id');
        var ruleId = $(this).parent().attr('data-rule-id');
        var type = $(this).parent().attr('data-type');
        var params={
            corp_id:corpId,
            activity_id:planId,
            type:type,
            rule:ruleId,
        }
        unbind_operation(params).done(function(data){
            if (!data.error) {
                layer.msg("取消关联成功");
                $(_this).parent().remove();
            }

        })
    })
    //  修改方案状态
    $(document).on('click', 'a.market-state', function() {
        var corpId = $(this).parent().attr('data-corp-id');
        var planId = $(this).parent().attr('data-plan-id');
        var changeType = $(this).attr('data-change-type');
        var coupon_num = $(this).parent().parent().find(".coupon_list").children().length;
        var obj = {
            'confirm': '确认',
            'cancel': '取消',
            'corp_id': corpId,
            'plan_id': planId,
            'change_type': changeType,
            'message': [],
        }
        var msg = {
            content: ''
        }
        if (changeType == 'start') {
            if (coupon_num < 1) {
                layer.msg("您还没有关联优惠券");
                return;
            }
            obj['tittle'] = '发布提示';
            msg.content = '您确认关发布吗？';
        } else if (changeType == 'stop') {
            obj['tittle'] = '下架提示';
            msg.content = '您确认下架吗？';
        }else {
            alert("请重新选择");
            return;
        }
        obj.message.push(msg)
        dialogPopupShow(obj);
        $('#dialog').show();
    });
    function dialogPopupShow(obj) {
        var dialogTep = $("#dialog-template").html();
        dialogTep = Handlebars.compile(dialogTep);
        $('#dialog').html(dialogTep(obj));
    };
    //  取消弹窗
    $(document).on('click', '.cancel-bg', function() {
        $('#dialog').hide();
        $('#bing-coupon').hide();
    });

    //  弹窗内容确定
    $(document).on('click', '.coupon.confirm-bg', function() {
        var state;
        var corpId = $(this).attr('data-corp-id');
        var planId = $(this).attr('data-plan-id');
        var changeType = $(this).attr('data-change-type');
        if (changeType == 'start') {
            state = 2;
        } else if (changeType == 'stop') {
            state = 3;
        }
        var params = {
            corp_id: corpId,
            activity_id: planId,
            state:state
        }
        modify_activity_state(params).done(function(data) {
            if (data.error == "0") {
                $('#dialog').hide();
                layer.msg('修改成功');
                window.location.reload()
            } else {
                layer.msg('修改失败');
            }
        })
    });
})
</script>
{/literal}
