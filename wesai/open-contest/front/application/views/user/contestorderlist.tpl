{include file="../user/header.tpl"}
<link rel="stylesheet" href="{'css/root_navred.css'|cdnurl}">
{literal}
	<style type="text/css">
		#scroll-wrap {
			position: static;
		}
	</style>
	<script type="text/javascript">
		var noPageScroll = false;
	</script>
{/literal}
<script src="{'js/template.js'|cdnurl}"></script>
<script src="{'js/v2/lib/iscroll-probe.js'|cdnurl}"></script>
<div id="wrapper">
	<div id="scroller">
		<div class="orderWrap">
			<div class="order-list">
				{if !empty($orderlist)}
				{foreach from=$orderlist item=item}
					<div class="orderList">
						<dl class="listDl">
							<dt class="t">
								<a href="/contest/orderdetail?oid={$item->pk_order}"><img src="{$item->contest_info->poster|cdnurl:"upload"}?imageMogr2/thumbnail/82x82!"></a>
							</dt>
							<dd class="d">
								<h4 onclick="location.href='/contest/detail?cid={$item->fk_contest}';">{$item->contest_info->name}</h4>
								<ul class="clearfix">
									<li class="tim">
										<s></s><span class="m">{$item->contest_info->sdate|date_format:"%Y年%m月%d日"}</span>
									</li>
									<li class="adr"><s></s><span class="m">{$item->contest_info->location}</span></li>
									<li class="ml"><s></s><span class="m">{$item->contest_item_info->name}
											<i class="pinkred"> {$item->amount/100}元</i> </span></li>
								</ul>
							</dd>
						</dl>
						<div class="fee clearfix">
							<div class="msg">
								{if $item->state == $smarty.const.ORDER_STATE_CLOSED}
									<i><a href="#" class="pinkred">{$industry_name.order_closed}</a></i>
								{else}
									<i><a href="#" class="{if $item->state=='3'}grey9{else}pinkred{/if}">{$ORDER_PAY_SATATELIST[$item->state]}</a></i>
								{/if}
								<i class="line">|</i>
								{if $item->state == $smarty.const.ORDER_STATE_PAYING && (time() - strtotime($item->ctime)) < ($smarty.const.ORDER_TIME_EXPIRE * 60 - 5)}
									<i><a href="javascript::void(0);" onclick="startOrderPay({$item->pk_order})">立即支付</a></i>
								{else}
									<i><a href="/contest/orderdetail?oid={$item->pk_order}">订单详情</a></i>
								{/if}
							</div>
						</div>
					</div>
				{/foreach}
				{else}
					<div class="no-datalist">
						<img src="{'img/nodatalist@2x.png'|cdnurl}" width＝"94" width="94">
						<p>暂无订单</p>
						<a href="/contest/" class="gobtn">去发现</a>
					</div>
					<script type="text/javascript">
						noPageScroll = true;
					</script>
				{/if}
			</div>
			<div id="pullUp" style="display:none">
				<span class="pullUpIcon"></span><span class="pullUpLabel">上拉加载更多...</span></div>
			<div style="height:49px"></div>
		</div>
	</div>
</div>
{literal}
	<script id="orderlist" type="text/html">
		{{each list as item i}}
		<div class="orderList">
			<dl class="listDl">
				<dt class="t">
					<a href="/contest/orderdetail?oid={{item.pk_order}}"><img src="{{upload_res_url}}/{{item.contestinfo.poster}}?imageMogr2/thumbnail/92x132!" width="92" height="132"></a>
				</dt>
				<dd class="d">
					<h4>{{item.contestinfo.name}}</h4>
					<ul class="clearfix">
						<li class="tim"><s></s><span class="m">{{item.contestinfo.sdate}}</span></li>
						<li class="adr"><s></s><span class="m">{{item.contestinfo.location}}</span></li>
						<li class="ml">
							<s></s><span class="m">{{item.iteminfo.name}} <i class="pinkred"> {{item.amount/100}}元</i> </span>
						</li>
					</ul>
				</dd>
			</dl>
			<div class="fee clearfix">
				<div class="msg">
					<i><a href="#" class="{{if item.state=='3'}}grey9{{else}}pinkred{{/if}}">{{ORDER_PAY_SATATELIST[item.state]}}</a></i><i class="line">|</i>
					{{if item.state == ORDER_STATE_PAYING}}
					<i><a href="javascript::void(0);" onclick="startOrderPay({{item.pk_order}})">立即支付</a></i>
					{{else}}
					<i><a href="/contest/orderdetail?oid={{item.pk_order}}">订单详情</a></i>
					{{/if}}
				</div>
			</div>
		</div>
		{{/each}}
	</script>
{/literal}
<script type="text/javascript">
	var ORDER_STATE_PAYING = {$smarty.const.ORDER_STATE_PAYING};
	var pagetotal          = '{$pagetotal}'
	var currpage           = 2, pagesize = 10;
	var loadFreeze         = false;
	function additemHtml(jsondata, page) {
		var contest_list = {
			"page"                : jsondata.page,
			"total"               : jsondata.total,
			"list"                : jsondata.orderlist,
			"ORDER_PAY_SATATELIST": jsondata.ORDER_PAY_SATATELIST,
			"upload_res_url"      : jsondata.upload_res_url,
			"static_res_url"      : jsondata.static_res_url,
		};
		var obj_html     = template('orderlist', contest_list);
		$(".order-list").append(obj_html);
		currpage++;
	}
	var myScroll;
	var isMoved = false;
	function loaded() {
		myScroll = new IScroll('#wrapper', {
			probeType            : 1,
			mouseWheel           : true,
			click                : true,
			scrollbars           : true,
			fadeScrollbars       : true,
			interactiveScrollbars: false,
			keyBindings          : false,
			deceleration         : 0.0002,
			startY               : 0
		});
		//滑动
		myScroll.on('scrollEnd', function () {
			if (this.y <= (this.maxScrollY + 200)) {
				//滚动到底部加载数据
				if (!isMoved) {
					$("#pullUp").show().addClass("loading");
					loadMore(currpage);
				}
			}
		});
	}
	if (!noPageScroll) {
		document.addEventListener('DOMContentLoaded', loaded(), false);
	}
	/* loading */
	function hideLoading() {
		$("#pullUp").fadeOut().removeClass("loading");
		myScroll.refresh();
	}
	/*分页*/
	function loadMore(page) {
		isMoved = true;
		if (!loadFreeze) {
			jQuery.ajax({
				            type    : "GET",
				            url     : "/user/ajax_orderist",
				            data    : 'page=' + page,
				            dataType: "json",
				            success : function (data) {
					            additemHtml(data, page);
					            myScroll.refresh();
					            isMoved = false;
					            var els = $(".orderList").length;
					            if (els == pagetotal) {
						            $('.pullUpLabel')[0].innerHTML = '没有更多数据了!';
						            loadFreeze                     = true;
						            setTimeout(hideLoading(), 5000);
					            }
				            }
			            });
		} else {
			setTimeout(hideLoading(), 5000);
		}
	}

	function startOrderPay(oid) {
		jQuery.ajax({
			            type    : "POST",
			            url     : "/contest/ajax_get_order_pay_url",
			            data    : 'oid=' + oid,
			            dataType: "json",
			            success : function (data) {
				            console.log(data);
				            if (data.error != 0) {
					            if (data.error == -3) {
						            window.location.reload();
						            return;
					            }
					            alert(data.msg);
					            return;
				            }
				            location.href = data.pay_url;
			            }
		            });
	}
</script>
{include file='../contest/_fixnav.tpl'}
{include file="../user/foot.tpl"}
