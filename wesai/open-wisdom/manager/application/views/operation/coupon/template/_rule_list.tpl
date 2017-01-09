<div class="clearfix addbutton-box">
    <a class="pull-right add-button" href="/operation/coupon/add_coupon">创建优惠券规则</a>
</div>
<div class="panel panel-blue day-statistics">
    <div id="dist-list" class="wesai-dist"></div>
</div>
<!-- 翻页 -->
<nav>{$page_ctrl}</nav>
{include file="operation/template/_disalog.tpl"}
{literal}
<script id="dist-list-template" type="text/x-handlebars-template">
 <table class="table txt-cen">
        <thead class="bg-f6f3f3">
            <tr>
                <th>优惠券名称</th>
                <th>有效期</th>
                <th>数量</th>
                <th>面值金额(元)</th>
                <th>最低使用金额(元)</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
        </thead>
        {{#each data}}
        <tbody>
            <tr>
                <td>{{name}}</td>
                <td>{{stop_time}}</td>
                <td>{{number}}</td>
                <td>{{value}}</td>
                <td>{{value_min}}</td>
                <td class="{{state_class}}">{{state_name}}</td>
                <td data-corp-id="{{corp_id}}" data-plan-id="{{voucher_rule_id}}" data-name="{{name}}">
                    {{#if edit}}
                    <a class="seedetails-btn" href="/operation/coupon/edit_coupon?voucher_rule_id={{voucher_rule_id}}" >编辑</a>
                    <a class="rule-state seedetails-btn submit_verify" href="javascript:;" data-change-type="verify" data-sub-state="{{sub_state}}">提交审核</a>
                    {{/if}}
                    {{#if back}}
                    <a class="rule-state seedetails-btn" href="javascript:;" data-change-type="pass">通过</a>
                    <a class="rule-state seedetails-btn" href="javascript:;" data-change-type="back">打回</a>
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
var DATA = {
    data:[]
};
{/literal}
{if !empty($data)} {foreach from=$data item=item}
    var obj={
        voucher_rule_id:{$item->voucher_rule_id},
        name:"{$item->name}",
        corp_id:{$item->corp_id},
        scope_type:{$item->scope_type},
        state:{$item->state},
        number:{$item->number},
        number_created:{$item->number_created},
        number_used:{$item->number_used},
        value:{$item->value}/100,
        value_min:{$item->value_min}/100,
        stop_time:"{$item->stop_time}",

    };
    {foreach from=$OPERATION_VOUCHER_RULE_STATE item=item}
        obj.state_name = {$item}[obj.state];
        if (obj.state == 1) {
            obj.edit = 1;
            obj.sub_state = 1;
        }
        if (obj.state == 4) {
            obj.edit = 1;
            obj.sub_state = 4;
        }
        if (obj.state == 2) {
            obj.back = 1;
        }
    {/foreach}
    DATA.data.push(obj);
{/foreach} {/if}
{literal}
$(function() {
    loadDistList(DATA)
    function loadDistList(data){
        var queryURL = GetRequest();
        var _state = queryURL['state'] || '';
        $('.filter-state .state'+_state).addClass('link_state_color');
        var distList = $("#dist-list-template").html();
        distList = Handlebars.compile(distList);
        $('#dist-list').html(distList(data));
    }

    //  状态修改
    $(document).on('click','.filter-state .link_gtype',function(){
        var _state = $(this).attr('data-state');
        window.location.href = "/dist/dist/dist_list?state="+ _state;
    })
    //  修改方案状态
    $(document).on('click', 'a.rule-state', function() {
        var corpId = $(this).parent().attr('data-corp-id');
        var planId = $(this).parent().attr('data-plan-id');
        var name = $(this).parent().attr('data-name');
        var changeType = $(this).attr('data-change-type');
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
        if (changeType == 'verify') {
            obj['tittle'] = '提交审核';
            msg.content = '你确定提交审核吗？';
        } else if (changeType == 'pass') {
            obj['tittle'] = '通过提示';
            msg.content = name+'确定通过吗？';
        } else if (changeType == 'back') {
            obj['tittle'] = '打回提示';
            msg.content = name+'确定打回吗？';
        } else {
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
    $(document).on('click', '.cancel-bg', function() {
        $('#dialog').hide();
    });
    $(document).on('click', '.confirm-bg', function() {
        var corpId = $(this).attr('data-corp-id');
        var planId = $(this).attr('data-plan-id');
        var changeType = $(this).attr('data-change-type');
        var sub_state = $(".submit_verify").attr('data-sub-state');
        var params = {
            'corp_id': corpId,
            'voucher_rule_id': planId
        }
        console.log(params)
        if (changeType == 'verify') {
            var change_state = nosubject_to_wait_post;
            if (sub_state == 4) {
                change_state = notpass_to_wait_post;
            }
            change_state(params).done(function(rs) {
                if (rs.error == "0") {
                    $('#dialog').hide();
                    layer.msg('已提交审核');
                    window.location.reload()
                } else {
                    layer.msg('提交审核失败');
                }
            })
        } else if (changeType == 'pass') {
            coupon_state_passed(params).done(function(rs) {
                if (rs.error == "0") {
                    $('#dialog').hide();
                    layer.msg('已通过');
                    window.location.reload()
                } else {
                    layer.msg('通过失败！');
                }
            })
        } else if (changeType == 'back') {
            coupon_state_notpassed(params).done(function(rs) {
                if (rs.error == "0") {
                    $('#dialog').hide();
                    layer.msg('已打回');
                    window.location.reload()
                } else {
                    layer.msg('打回失败！');
                }
            })
        }
    });
})
</script>
{/literal}
