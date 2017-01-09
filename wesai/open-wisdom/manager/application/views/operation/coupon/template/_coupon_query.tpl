<div class="clearfix addbutton-box">
    <a class="pull-right add-button" href="/dist/dist/add_dist">创建分销方案</a>
</div>
<div id="dist-list" class="wesai-dist">
  
</div>
<!-- 翻页 -->
<nav>{$page_ctrl}</nav>
{include file="dist/template/_disalog.tpl"}
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
                <td>{{dist_contest_id}}</td>
                <td>{{name}}</td>
                <td>{{publish_state_name}}</td>
                <td>北京微赛科技时代有限公司</td>
                <td>{{rebate_seller}}%</td>
                <td class="{{state_class}}">{{state_name}}</td>
                <td data-corp-id="{{owner_corp_id}}" data-plan-id="{{dist_contest_id}}">
                    {{#if closed}}
                    <a class="seedetails-btn" style="visibility: hidden;">通过</a>
                    <a class="seedetails-btn" style="visibility: hidden;">打回</a>
                    {{else}}
                    <a class="dist-state seedetails-btn" href="javascript:;" data-change-type="{{btn_class}}">编辑</a>
                    <a class="dist-state seedetails-btn" href="javascript:;" data-change-type="closed">提交审核</a>                    
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
//  状态处理
function stateDispose(data){
    console.log(data)
    switch (data.state) {
        case 1:
                data.btn_class = 'using';
                data.btn_name = '开启';
                data.state_class = 'refund-ing';
            break;
        case 2:
                data.btn_class = 'stop';
                data.btn_name = '暂停';
                data.state_class = 'refund-success';
            break;                
        case 3:
                data.closed = 1;
                data.state_class = 'refund-fail';
            break;
    }
    return data;
}
{/literal}
{if !empty($data)} {foreach from=$data item=item}
    var obj={
        owner_corp_id:{$item->owner_corp_id},
        dist_contest_id:{$item->dist_contest_id},
        type:{$item->type},
        state:{$item->state},
        state_name:'{$solution_state[$item->state]}',
        rebate_seller:{$item->rebate_seller},
        name:"{$item->contest->name}",
        logo:"{$item->contest->logo}",
        publish_state:{$item->contest->publish_state},
        publish_state_name:'{$on_state[$item->contest->publish_state]}',
        location:"{$item->contest->location}",
        sdate_start:{$item->contest->sdate_start},
        sdate_end:{$item->contest->sdate_end}
    }
    obj = stateDispose(obj);
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
    $(document).on('click', 'a.dist-state', function() {
        var corpId = $(this).parent().attr('data-corp-id');
        var planId = $(this).parent().attr('data-plan-id');
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
        if (changeType == 'closed') {
            obj['tittle'] = '关闭提示';
            msg.content = '您确认关闭分销［活动名］吗？暂停后，此活动将，并无法再次开启。';
        } else if (changeType == 'using') {
            obj['tittle'] = '开启提示';
            msg.content = '您确认开启分销［活动名］吗？开启后，此活动将在分销商的页面上架';
        } else if (changeType == 'stop') {
            obj['tittle'] = '暂停提示';
            msg.content = '您确认暂停分销［活动名］吗？关闭后，此活动将在分销商的页面下架';
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
        var params = {
            'owner_corp_id': corpId,
            'contest_plan_id': planId
        }
        if (changeType == 'using') {
            changePlanStateToUsing(params).done(function(rs) {
                if (rs.error == "0") {
                    $('#dialog').hide();
                    layer.msg('已开启');
                    window.location.reload()
                } else {
                    layer.msg('修改失败！');
                }
            })
        } else if (changeType == 'stop') {
            changePlanStateToStop(params).done(function(rs) {
                if (rs.error == "0") {
                    $('#dialog').hide();
                    layer.msg('已暂停');
                    window.location.reload()
                } else {
                    layer.msg('修改失败！');
                }
            })
        } else if (changeType == 'closed') {
            changePlanStateToClosed(params).done(function(rs) {
                if (rs.error == "0") {
                    $('#dialog').hide();
                    layer.msg('已关闭');
                    window.location.reload()
                } else {
                    layer.msg('修改失败！');
                }
            })
        }
    });
})
</script>
{/literal}