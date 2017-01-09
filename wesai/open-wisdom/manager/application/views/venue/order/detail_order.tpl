 {include file='venue/_header.tpl'}
<script src="{'manager/venue/lib/handlebars.js'|cdnurl}"></script>
<!--—自适应布局---->
<div class="container-fluid">
    <div class="row">
        <!-- leftStart -->
        {include file="../_leftside.tpl"}
        <!-- leftEnt -->
        <!-- rightStart-->
        <div class="right-main">
            <div class="breadcrumbs-box">
                <ol class="breadcrumb">
                    {include file="venue/_top_sub_navi.tpl"}
                    <li>
                        <a href="/venue/order/order_list">订单管理</a>
                    </li>
                    <li class="active">订单详情</li>
                </ol>
            </div>
            <div class="right-con">
                <div class="panel panel-blue">
                    <div class="panel-heading">
                        <h3 class="panel-title">订单详情</h3>
                    </div>
                    <div class="" id="detailOrder"></div>
                </div>
            </div>
        </div>
        <!-- rightEnd -->
    </div>
</div>
{literal}
<script id="detailOrder-template" type="text/x-handlebars-template">
    <table class="table">
        <tr>
            <td>
                <strong>订单ID：</strong>
                <span>{{order_view_id}}</span>
            </td>
        </tr>
        <tr>
            <td>
                <strong>创建时间:</strong>
                <span>{{ctime}}</span>
            </td>
        </tr>
        <tr>
            <td>
                <strong>场馆名称:</strong>
                <span>{{venue_name}}</span>
            </td>
        </tr>
        <tr>
            <td>
                <strong>项目:</strong>
                <span>{{type_name}}</span>
            </td>
        </tr>
        <tr>
            <td>
                <strong>场次:</strong> {{#with times}} {{#each this}}
                <span>{{name}}</span>
                <span>{{time_start}} - {{time_end}}</span>
                {{/each}} {{/with}}
            </td>
        </tr>
        <tr>
            <td>
                <strong>订单状态:</strong>
                <span class={{state_class}}>{{state_name}}</span>
            </td>
        </tr>
        <tr>
            <td>
                <strong>账单号:</strong>
                <span>{{wx_order}}</span>
            </td>
        </tr>
        <tr>
            <td>
                <strong>支付方式:</strong>
                <span>{{pay_method}}</span>
            </td>
        </tr>
        <tr>
            <td>
                <strong>金额:</strong>
                <span>{{price}}</span>
            </td>
        </tr>
        <tr>
            <td>
                <strong>核销码:</strong>
                <span>{{#with times}}
                    {{#each this}}
                        <span>{{code}}</span> {{/each}} {{/with}}
                </span>
            </td>
        </tr>
    </table>
</script>
{/literal}
<script type="text/javascript">
var _url = location.search;
var corp_id = _url.split('=');
var params = {
    corp_id: corp_id[1]
}
ajaxDetailOrder(params).done(function(rs) {
    rs = dataInit(rs);
    var detailOrder = $('#detailOrder-template').html();
    var detailOrder = Handlebars.compile(detailOrder);
    $('#detailOrder').html(detailOrder(rs.result)); //场馆列表

})
//  类型数据设置
function dataInit(data) {
    data.result.type_name = typeName(data.result.type);
    data.result.state_name = stateName(data.result).name;
    data.result.state_class = stateName(data.result).class;
    data.result.pay_method = payMethod(data.result.channel_id);
    return data;
}
function payMethod(method){
    var arr = []; 
    {foreach from = $order_pay_method key = key item = item}
        arr["{$key}"] = "{$item}";
    {/foreach};
    return arr[method]
}
//  类型判断
function typeName(type) {
    var arr = []; 
    {foreach from = $allow_venue_types key = key item = item}
        arr["{$item.tag_id}"] = "{$item.name}" ;
    {/foreach};
    return arr[type];
}
//  订单状态
function stateName(data) {
    var arr = {
        1: {
            "name": "待付款",
            "class": "order-state-paying"
        },
        2: {
            "name": "支付失败",
            "class": "order-state-failed"
        },
        3: {
            "name": "未使用",
            "class": "order-state-unused"
        },
        4: {
            "name": "已使用",
            "class": "order-state-used"
        }
    }
    return arr[data.state];
}
</script>
{include file='venue/_foot.tpl'}
