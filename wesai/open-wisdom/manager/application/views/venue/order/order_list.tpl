{include file='venue/_header.tpl'}
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
                    <li class="active">订单管理</li>
                </ol>
            </div>
            <div class="right-con">
                <div class="panel panel-blue day-statistics ">
                    <div class="panel-heading">
                        <h3 class="panel-title">场馆管理</h3>
                    </div>
                    <div class="panel-body  event-details">
                        <div class="filter-condition">
                            <div class="filter-type clearfix">
                                <div class="dt">场馆项目:</div>
                                <div class="dd">
                                    <a class="link_gtype types" data-params="types" data-type="" href="javascript:;">全部</a> {foreach from=$allow_venue_types key=key item=item}
                                    <a class="link_gtype types{$item.tag_id}" data-params="types" data-type="{$item.tag_id}" href="javascript:;">{$item.name}</a> 
                                    {/foreach}
                                </div>
                            </div>
                            <div class="filter-state clearfix">
                                <div class="dt">订单状态:</div>
                                <div class="dd">
                                    <a class="link_gtype state is_use" data-params="state" data-state="" href="javascript:;">全部</a> {foreach from=$venue_order_pay_satatelist key=key item=item}
                                    <a class="link_gtype state{$item.state} is_use{$item.is_use}" data-params="state" data-state="{$item.state}" data-use="{$item.is_use}" href="javascript:;">{$item.name}</a> {/foreach}
                                </div>
                            </div>
                            <div class="filter-date-item clearfix">
                                <div class="dt">日期:</div>
                                <div class="dd">
                                    <a class="link_gtype date_period" href="javascript:;" data-params="period" date-period='' date-type="1">全部</a>
                                    <a class="link_gtype date_period1" data-params="period" date-period='1' date-type="1" href="javascript:;">当日</a>
                                    <a class="link_gtype date_period2" data-params="period" date-period='2' date-type="1" href="javascript:;">近一周</a>
                                    <a class="link_gtype date_period3" data-params="period" date-period='3' date-type="1" href="javascript:;">近一月</a>
                                    <div class="choose-date">
                                        <input type="text" id="inpstart" class="pull-left" value="">
                                        <span class="horizontal-line"></span>
                                        <input type="text" id="inpend" value="" class="pull-right">
                                    </div>
                                    <a class="search-confirm link_gtype date_period4" date-period='4' id="dateSearch" href="javascript:;" data-params="time" date-type="2">确认</a>
                                </div>
                            </div>
                            <div class="filter-search clearfix">
                                <div class="dt">搜索:</div>
                                <div class="dd">
                                    <input type="text" class="search_text" value="">
                                    <a class="search-confirm" id="searchText" href="javascript:;" data-params="name">确认</a>
                                    <a class="link_state_color" href="/venue/order/order_list" data-parmeter="">条件清空</a>
                                </div>
                            </div>
                        </div>
                        <div id="orderList">
                            <table class="table txt-cen">
                                <thead>
                                    <tr>
                                        <th>订单ID</th>
                                        <th>场馆名称</th>
                                        <th>项目</th>
                                        <th>预订场次</th>
                                        <th>创建时间</th>
                                        <th>手机号</th>
                                        <th>订单状态</th>
                                        <th>金额(元)</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                {if !empty($data)} {foreach from=$data item=item}
                                <tbody>
                                    <tr>
                                        <td>{$item->order_id}</td>
                                        <td>{$item->venue_name}</td>
                                        <td>{$item->type_name}</td>
                                        <td>{foreach from=$item->res_times item=res_times}
                                            <div>{$res_times->name}</div>
                                            <div>{$res_times->day}</div>
                                            <div>{$res_times->start} - {$res_times->end}</div>
                                            {/foreach}
                                        </td>
                                        <td>{$item->ctime}</td>
                                        <td>{$item->mobile}</td>
                                        <!-- <td >{$item->state}</td> -->
                                        <td class={$item->state_class}>{$item->state_name}</td>
                                        <td>{$item->amount} 元</td>
                                        <td>
                                            <a href="/venue/order/detail_order?corp_id={$item->order_id}" class="seedetails-btn">查看</a>
                                        </td>
                                    </tr>
                                </tbody>
                                {/foreach} {/if}
                            </table>
                        </div>
                    </div>
                    <!-- 翻页 -->
                    <nav>{$page_ctrl}</nav>
                </div>
            </div>
        </div>
    </div>
    <!-- rightEnd -->
</div>
<!-- upload -->
<div class="dialog-bg" id="dialog" style="display:none"></div>
{literal}
<script id="listVenues-template" type="text/x-handlebars-template">
    {{#if this}}
    <select class="form-control">
        {{#each this}}
        <option value="{{venue_id}}" data-venue-area-res-id={{venue_area_res_id}}>{{name}}</option>
        {{/each}}
    </select>
    {{/if}}
</script>
<script id="noDataShow-template" type="text/x-handlebars-template">
    <div class="no-data-show" id="noDataShow">暂无数据</div>
</script>
{/literal}
<div class="loading">
    <div class="loading-icon"></div>
</div>
<script src="{'manager/venue/lib/handlebars.js'|cdnurl}"></script>
<script src="{'manager/venue/lib/lodash.min.js'|cdnurl}"></script>
<script>
var userInfo = {$smarty.session.userInfo};
</script>
<script>
$('.loading').hide();
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
var start = {
    dateCell: '#inpstart',
    format: 'YYYY-MM-DD',
    minDate: '2011-06-30 23:59:59', //设定最小日期为当前日期
    // isinitVal:true,
    festival: true,
    ishmsVal: false,
    maxPage:0,
    maxDate: '2099-06-30 23:59:59', //最大日期
    choosefun: function(elem, datas) {
        end.minDate = datas; //开始日选好后，重置结束日的最小日期
        var inpend = $("#inpend").val();
        datestate($('.filter-date-item .link_gtype'));
    },
    clearfun: function(elem, val) {
        datestate($('.filter-date-item .link_gtype'));
    }
};

var end = {
    dateCell: '#inpend',
    format: 'YYYY-MM-DD',
    minDate: jeDate.now(0), //设定最小日期为当前日期
    festival: true,
    maxDate: '2099-06-16 23:59:59', //最大日期
    choosefun: function(elem, datas) {
        start.maxDate = datas;
        var inpstart = $("#inpstart").val();
        $('#dateSearch').addClass('date-btn-bg link_state_color');
        datestate($('.filter-date-item .link_gtype'));
    },
    clearfun: function(elem, val) {
        $('#dateSearch').removeClass('date-btn-bg link_state_color');
        datestate($('.filter-date-item .link_gtype'));
    }
};
jeDate(start);
jeDate(end);
function datestate(ele) {
    ele.removeClass('link_state_color');
}
$('a.link_gtype').on('click', function() {
    var that = $(this);
    that.parent('.dd').find('.link_state_color').removeClass('link_state_color');
    that.addClass('link_state_color');
    var data = $(this).data('params');
    getOrderList(data);
});
$('.search_text').on('input', function() {
    var len = $('.search_text').val().length;
    if (len > 0) {
        $('#searchText').addClass('date-btn-bg');
    } else {
        $('#searchText').removeClass('date-btn-bg');
        getOrderList();
    };

});
$(document).on('click', '#dateSearch.date-btn-bg', function() {
    var data = $(this).data('params');
    getOrderList(data);
});
$(document).on('click', '#searchText.date-btn-bg', function() {
    var data = $(this).data('params');
    var text = $('.search_text').val();
    Save.StorageSetter("search-text", text);
    getOrderList(data);
});

function getOrderList(par) {
    var type = $('.filter-type ').find('.link_state_color').attr('data-type');
    var state = $('.filter-state').find('.link_state_color').attr('data-state');
    var use = $('.filter-state').find('.link_state_color').attr('data-use');
    var date_period = $('.filter-date-item').find('.link_state_color').attr('date-period');
    var date_type = $('.filter-date-item').find('.link_state_color').attr('date-type');
    var dataDate = $('.filter-date-item').find('.link_state_color').attr('data-date');
    var name = $('.search_text').val();
    var date_start = $('#inpstart').val();
    var date_end = $('#inpend').val();
    if (date_type == 1) {
        $('#inpend').val('');
        $('#inpstart').val('');
    }
    var params = {
        types: type||'',
        name: name||'',
        state: state||'',
        is_use:use||'',
        date_start: date_start||'',
        date_end: date_end||'',
        date_period: date_period||'',
        date_type: date_type||'',
    }
    window.location.href = "/venue/order/order_list?types="+params.types+"&name="+params.name+"&state="+params.state+"&is_use="+params.is_use+"&date_period="+params.date_period+"&date_start="+params.date_start+"&date_end="+params.date_end+"&date_type="+date_type;
}

//  增加筛选class
addSelClass()
function addSelClass(){
    var Request = new Object();
    Request = GetRequest();
    var urlKey = [];
    var urlValue;
    var types = Request['types'] || '';
    var state = Request['state'] || '';
    var name = Request['name'] || '';
    var is_use = Request['is_use'] || '';
    var date_period = Request['date_period'] || '';
    var date_start = Request['date_start'] || '';
    var date_end = Request['date_end'] || '';
    var date_type = Request['date_type'] || '';
    $('.types'+types).addClass('link_state_color');
    if (state ==4) {
        $('.is_use'+is_use).addClass('link_state_color');
    }else{
        $('.state'+state).addClass('link_state_color');
    }
    $('.date_period'+date_period).addClass('link_state_color');
    if (date_type == 2) {
        $("#inpstart").val(date_start);
        $("#inpend").val(date_end);
        $("#dateSearch").addClass('date-btn-bg');
    }
    if (name!='') {
        var text = Save.StorageGetter('search-text');
        $(".search_text").val(text);
        $("#searchText").addClass('date-btn-bg');
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
$("filter-condition").find()
</script>
{include file='venue/_foot.tpl'}
