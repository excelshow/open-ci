<div class="panel-body event-details operation-coupon">
    <div class="filter-condition">
        <div class="filter-title clearfix">
            <p>活动筛选</p>
        </div>
        <div class="filter-type clearfix">
            <div class="dt">查询优惠券规则:</div>
            <div class="dd">
                <select class="coupon_sel">
                    {foreach from=$data item=item}
                    <option class="checked{$item->voucher_rule_id}" value="{$item->name}" data-corp-id="{$item->corp_id}" data-rule-id="{$item->voucher_rule_id}">{$item->name}</option>
                    {/foreach}

                </select>
            </div>
        </div>
        <div class="filter-type clearfix">
            <div class="dt">请输入卡密查询:</div>
            <div class="dd">
                <input class="code-text" type="text">
            </div>
        </div>
        <div class="filter-type clearfix">
            <div class="dt">卡密状态:</div>
            <div class="dd">
                <a class="link_gtype state link_state_color" data-state="" href="javascript:;">全部</a>
                {foreach from=$OPERATION_VOUCHER_STATE_LIST key=key item=item }
                    <a class="link_gtype state{$key}" data-state="{$key}" href="javascript:;">{$item}</a>
                {/foreach}
            </div>
        </div>
        <div class="filter-type clearfix">
            <div class="query_card">查询</div>
        </div>
        <div></div>
    </div>
</div>
<div class="wesai-dist">
    <table class="table txt-cen">
        <thead class="bg-f6f3f3">
            <tr>
                <th>卡密</th>
                <th>截止日期</th>
                <th>面值金额(元)</th>
                <th>最低使用金额(元)</th>
                <th>状态</th>
            </tr>
        </thead>
        <tbody id="card-list">
        </tbody>
    </table>
</div>
<!-- 翻页 -->
{if empty($listdata->data)}
{else}
    <nav>{$listdata->page_ctrl}</nav>
{/if}
{literal}
<script id="card-list-template" type="text/x-handlebars-template">
{{#each data}}
    <tr>
        <td>{{code}}</td>
        <td>{{stop_time}}</td>
        <td>{{value}}</td>
        <td>{{value_min}}</td>
        <td>{{state_name}}</td>
    </tr>
{{/each}}
</script>
{/literal}
{literal}
<script type="text/javascript">
var DATA = {
    data:[]
};
{/literal}
{if empty($listdata->data)}
{else}
{foreach from=$listdata->data item=item}
    var obj = {
        code:{$item->code},
        value:{$item->value}/100,
        value_min:{$item->value_min}/100,
        stop_time:"{$item->stop_time}",
        state:"{$item->state}"
    };
    {foreach from=$OPERATION_VOUCHER_STATE item=item}
        obj.state_name = {$item}[obj.state];
    {/foreach}
    DATA.data.push(obj);
{/foreach}
{/if}
{literal}
$(function() {
    var Save = (function() {
        var prefix = "venue_order_serch_";
        var StorageGetter = function(key) {
            return sessionStorage.getItem(prefix + key)
        }
        var StorageSetter = function(key, val) {
            return sessionStorage.setItem(prefix + key, val)
        }
        var StorageRemove = function(key) {
            return sessionStorage.removeItem(prefix + key)
        }
        var StorageClear = function() {
            return sessionStorage.clear()
        }
        return {
            StorageGetter: StorageGetter,
            StorageSetter: StorageSetter,
            StorageRemove: StorageRemove,
            StorageClear: StorageClear
        }
    })();
    var Request = GetRequest();
    var CORP_ID = Request['voucher_rule_id'];
    var STATE = Request['state'];
    list()
    function list(){
        var distList = $("#card-list-template").html();
        var _val = Save.StorageGetter("code_text");
        $('.code-text').val(_val);
        distList = Handlebars.compile(distList);
        $('#card-list').html(distList(DATA));
        $(".checked"+CORP_ID).attr("selected", true);
        if (STATE) {
            $('.link_state_color').removeClass('link_state_color');
            $(".state"+STATE).addClass('link_state_color');
        }
    }

    function loadCardList(state) {
        var corp_id = $(".coupon_sel option:selected").attr("data-corp-id");
        var voucher_rule_id = $(".coupon_sel option:selected").attr("data-rule-id");
        var code = $(".code-text").val();
        var state = $(".link_gtype.link_state_color").data('state');
        var params = {
            corp_id: corp_id,
            voucher_rule_id: voucher_rule_id,
            code: code
        }
        if (state) {
            params.state = state;
        }
        if (state) {
            window.location.href = "/operation/coupon/query_list?corp_id=" + corp_id + "&voucher_rule_id=" + voucher_rule_id + "&code=" + code + "&state=" + state;
        } else {
            window.location.href = "/operation/coupon/query_list?corp_id=" + corp_id + "&voucher_rule_id=" + voucher_rule_id + "&code=" + code;
        }
    }

    //  获取url参数
    function GetRequest() {
        var url = location.search; //获取url中"?"符后的字串
        var theRequest = new Object();
        if (url.indexOf("?") != -1) {
            var str = url.substr(1);
            var strs = str.split("&");
            for (var i = 0; i < strs.length; i++) {
                theRequest[strs[i].split("=")[0]] = unescape(strs[i].split("=")[1]);
            }
        }
        return theRequest;
    }

    function getRuleList(){
        var len = $(".coupon_sel option").length;
        if (len > 0) {
            loadCardList();
        }else {
            layer.msg("暂无信息")
            return;
        }
    }
    $(document).on('click', ".query_card", function(e) {
        getRuleList()
    })
    $('a.link_gtype').on('click', function() {
        $(this).parent('.dd').find('.link_state_color').removeClass('link_state_color');
        $(this).addClass('link_state_color');
        getRuleList();
    });
    $('.code-text').on('input', function() {
        var _val = $(this).val();
        Save.StorageSetter("code_text",_val);
    });
})
</script>
{/literal}
