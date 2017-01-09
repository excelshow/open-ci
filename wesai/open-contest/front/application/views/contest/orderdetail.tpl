{include file='../contest/header.tpl'}
<link rel="stylesheet" href="{'css/root_navred.css'|cdnurl}">
<!-- 订单详情 -->
<div class="wrap-content gray_bg">
	<div class="head-top white_bg">
		<div class="order-tip"><span class="tip">!</span> 您的订单详情如下</div>
		{include file='../contest/_contesttop.tpl'}
		<div class="reslect-box">
			<ul class="reg-list">
				<li>
					<div class="col title">{$iteminfodata->name}</div>
					<div class="col price fR">{if $iteminfodata->fee == 0}免费{else}{($iteminfodata->fee/100)|string_format:"%.2f"}<span>元</span>{/if}</div>
				</li>
				<li>
					<div class="col title">数量</div>
					<div class="col price fR" style="color: #000;">x{$orderdata->count}</div>
				</li>
				<li>
					<div class="col title">总价</div>
					<div class="col price fR">{$orderdata->amount/100|string_format:"%.2f"}<span>元</span></div>
				</li>
			</ul>
		</div>
	</div>
	<div class="pannel white_bg">
		<h4 class="h-title">{$industry_name.enrol_info}</h4>
		<div class="order-detail">
			<ul>
				{if !empty($enrolInfo)}
					{foreach from=$enrolInfo key=k item=v}
						{if $v->title == '订单状态'}
							<li><strong>{$v->title}:</strong><span class="red_color">{if $v->state == $smarty.const.ORDER_STATE_CLOSED}{$industry_name.order_closed}{else}{$v->value}{/if}</span></li>
						{elseif $v->type == 'uploadfile'}
							<li>
								<strong>{$v->title}:</strong><img src="{$v->value|cdnurl:'upload'}?imageMogr2/thumbnail/x250">
							</li>
						{else}
							<li><strong>{$v->title}:</strong>{$v->value}</li>
						{/if}
					{/foreach}
				{/if}
			</ul>
		</div>
	</div>
	<div>
		<div id="qrcode" style="text-align: center;background: #FFFFFF;"></div>
	</div>
	<div class="pannel white_bg pd-b50">
		<h4 class="h-title">温馨提示</h4>
		<p class="six_color p-txt pd-tb">1、所有选手接收到“{$industry_name.order_closed}”短信通知后即为报名成功</p>
		<p class="six_color p-txt pd-tb">2、一经{$industry_name.order_closed}，不接受退款、变更等请求, 谢谢合作。</p>
		<h4 class="h-title">如有疑问，请联系我们：</h4>
		<p class="six_color p-txt">客服电话：{$contestData->service_tel}</p>
	</div>
</div>

{include file='../contest/_jsconfig.tpl'}
{include file='../contest/order_qrcode.tpl'}
{include file='../contest/_fixnav.tpl'}
{include file='../contest/footer.tpl'}
