{include file='../../_header.tpl'}
	<div class="page-group">
       <div class="page" id="page-index">
       		<div class="volume-tab row no-gutter">
       			<div class="col-33 volume-tab-item activity" data-state="2"><a href="javascript:;">可使用</a></div>
       			<div class="col-33 volume-tab-item" data-state="3"><a href="javascript:;">已使用</a></div>
       			<div class="col-33 volume-tab-item" data-state=""><a href="javascript:;">全部</a></div>
       		</div>
			<div class="content posfixed pull-to-refresh-content infinite-scroll" data-ptr-distance="55">
				<!-- 加载提示符 -->
				<div class="pull-to-refresh-layer">
			        <div class="preloader"></div>
			        <div class="pull-to-refresh-arrow"></div>
				</div>
				<div class="volume-list mab10">
					<input type="hidden" id="session_url" value="{$smarty.session.appId}{$smarty.const.CONTEST_DOMAIN_SUF}">
					<ul class="volume-main bg-white padd10 volume-main-list" id="voucherList">
						<li class="nodata">暂无数据</li>
					</ul>
				</div>
				<div class="volume-protocol">
					<div class="volume-protocol-tit">使用规则</div>
					<div class="volume-protocol-con">
						<p>1、优惠券请在提交订单中使用，可抵扣部分金额；</p>
						<p>2、优惠券不兑换现金，不找零；</p>
						<p>3、优惠券仅限在注明日期内有效；</p>
					</div>
				</div>
				<!-- 加载提示符 -->
		        <div class="infinite-scroll-preloader">
		            <div class="preloader"></div>
		        </div>
			</div>
			{include file='../../footerNav.tpl'}
       </div>
    </div>

{include file='../../_wxconfig.tpl'}
{include file='../../_footer.tpl'}