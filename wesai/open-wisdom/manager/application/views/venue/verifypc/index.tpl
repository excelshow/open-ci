 {include file='venue/_header.tpl'}
<script src="{'manager/venue/lib/handlebars.js'|cdnurl}"></script>
<script src="{'manager/venue/js/jquery.page.js'|cdnurl}"></script>
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
                    <li class="active">核销</li>
                </ol>
            </div>
            <div class="right-con">
                <div class="right-con">
                    <div class="panel panel-blue">
                        <div class="panel-heading">
                            <h3 class="panel-title">核销</h3>
                        </div>
                        <div class="panel-body padd15 verify">
                            <div class="filter-condition">
                                <h3 class="text-center">请输入核销码</h3>
                                <h4 class="text-center">
                                    <input type="text" class="text-center" id="js_code">
                                    <button type="button" class="btn btn-primary verify-btm">确认核销</button>
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-blue">
                        <div class="panel-heading">
                            <h3 class="panel-title">核销记录</h3>
                        </div>
                        <div id="verifyList">
                            <div class="day-statistics">
                                <table class="table txt-cen">
                                    <thead>
                                        <tr>
                                            <th>手机</th>
                                            <th>核销码</th>
                                            <th>名称</th>
                                            <th>金额(元)</th>
                                            <th>下单时间</th>
                                            <th>核销时间</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {if !empty($data)} {foreach from=$data item=item}
                                        <tr>
                                            <td>{$item->mobile}</td>
                                            <td>{$item->code}</td>
                                            <td>
                                                <p>{$item->venue_name}</p>
                                                <p>{$item->venue_area_res_name}</p>
                                                <p>{$item->venue_area_type}</p>
                                                <p>{$item->day} {$item->start}-{$item->end}</p>
                                            </td>
                                            <td>{$item->price}</td>
                                            <td>{$item->order_time}</td>
                                            <td>{$item->verify_time}</td>
                                        </tr>
                                        {/foreach} {/if}
                                    </tbody>
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
    <div class="dialog-bg" id="dialog" style="display:none"></div>
    <div class="loading">
        <div class="loading-icon"></div>
    </div>
</div>
{literal}
<script id="dialog-template" type="text/x-handlebars-template">
    <div class="verify-main">
        <div class="padd40 title-text">核销详情</div>
        <div class="con-box">
            <div class="img-box text-center">
                <p class=""><span class="state-img"></span></p>
                <p class="">已成功核销</p>
            </div>
            <div class="padd40">
                <button type="button" class="btn btn-primary continue_write_off">继续核销</button>
            </div>
            <div class="padd-info">
                <div class="padd40 info-text clearfix">
                    <p class="pull-left">信息</p>
                </div>
                <div class="padd40 info-text clearfix">
                    <p class="pull-left">场馆名称</p>
                    <p class="pull-right clearfix">
                        <span>{{venue_name}}</span>
                        <span>{{venue_type}}</span>
                        <span>{{venue_res_name}}</span>
                        <span>{{day}}</span>
                        <span>{{start}}</span> —
                        <span>{{end}}</span>
                    </p>
                </div>
                <div class="padd40 info-text clearfix">
                    <p class="pull-left">联系电话</p>
                    <p class="pull-right">{{phone}}</p>
                </div>
                <div class="padd40 info-text clearfix">
                    <p class="pull-left">支付金额</p>
                    <p class="pull-right">{{price}} 元 </p>
                </div>
                <div class="padd40 info-text clearfix">
                    <p class="pull-left">下单时间</p>
                    <p class="pull-right">{{ctime}}</p>
                </div>
            </div>
        </div>
        <div class="close-button" title="删除图片"></div>
    </div>
</script>
{/literal} {literal}
<script type="text/javascript">
$(function() {
    $('.loading').hide();
    $('.verify-btm').on('click', function() {
        verify_code();
    });
    document.onkeydown = function(e) {
        var ev = document.all ? window.event : e;
        if (ev.keyCode == 13) {
            verify_code();
        }
    };

    function verify_code() {
        var code = $('#js_code').val();
        if (!code) {
            alert('核销码不能为空');
            return;
        }
        var params = {
            code: code
        }
        order_verify(params).done(function(rs) {
            var dialog = $('#dialog-template').html();
            var dialog = Handlebars.compile(dialog);
            $('#dialog').html(dialog(rs.result));
            $('#dialog').show();
        })
    }

    $(document).on('click', '.close-button', function() {
        $('#dialog').hide();
    });
    $(document).on('click', '.continue_write_off', function() {
        var code = $('#js_code').val('');
        $('#dialog').hide();
    })
})
</script>
{/literal} {include file='venue/_foot.tpl'}
