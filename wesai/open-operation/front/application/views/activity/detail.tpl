	{include file='../_header.tpl'}
	<div class="page-group">
       <div class="page" id="page-coupon">
       			{if strtotime($activityInfo->result->time_start) > time() or strtotime($activityInfo->result->time_end) < time()}
           			<div class="content bg-bule expired-state">
           		{else}
       				{if $activityInfo->result->state != $smarty.const.OPERATION_ACTIVITY_STATE_START}
           			<div class="content bg-bule expired-state">
       				{else}
       					<div class="content bg-bule">
       				{/if}
           		{/if}
           	
					<div class="banna-main">
						<img src="{$activityInfo->result->banner}" alt="">
						<div class="activity-title">{$activityInfo->result->name}</div>
					</div>
					<div class="expired-state-info activity-not-started">
						{if strtotime($activityInfo->result->time_start) > time()}
							活动未开始
						{else}
							{if strtotime($activityInfo->result->time_end) < time()}
				    			活动已结束
				    		{else}
				    			{$OPERATION_ACTIVITY_STATE_LIST[$activityInfo->result->state]}
				    		{/if}
						{/if}
					</div>
					<div class="volume-main flower">
						{if !empty(numberData)}
						<div class="number-data">可领取次数{$activityInfo->result->numberData->numberAction}/{$activityInfo->result->numberData->numberAll}</div>
						{/if}
						<div class="volume-list">
							<ul class="">
								{foreach from=$activityInfo->result->operation item=foo}
								<li class="volume-item {if $foo->number > $foo->number_allot} success {else} failure {/if}" data-voucher_rule_id="{$foo->voucher_rule_id}" data-corp_id="{$activityInfo->result->corp_id}" data-type="{$foo->type}" data-activity_id="{$activityInfo->result->activity_id}" data-name="{$foo->name}">
									<a class="row no-gutter" href="javascript:;">
										<div class="volume-info col-70">
											<div class="volume-info-con">
												<div class="volume-info-price">
													<div class="pre-price">
														<i class="iconfont">&#xe635;</i><span class="pre-price-num">{($foo->value/100)}</span>
													</div>
													<div class="volume-condition row no-gutter">
														<div class="volume-condition-des font-size14   col-65">满 {($foo->value_min/100)} 减 {($foo->value/100)} 元</div>
					
														<div class="volume-tit font-size18  col-35">优惠券</div>
													</div>
												</div>
											</div>
										</div>
										<div class="volume-btn col-30">
											{if $foo->number > $foo->number_allot}
											<div class="volume-btn-info alert-text-title">领取</div>
											{else}
											<div class="volume-btn-info">领光啦</div>
											{/if}
										</div>
									</a>
								</li>
								{/foreach}
							</ul>
						</div>
						<div class="share-btn-main"><p><a href="javascript:;" class="button button-fill button-big button-round share-btn">分享一起来领</a></p></div>
						<div class="volume-rule">
							 <div class="volume-rule-tit">
							 	<i class="volume-rule-tit-line"></i>
							 	<span class="marlg10">活动规则</span>
							 	<i class="volume-rule-tit-line"></i>
							 </div>
							 <div class="volume-rule-con">
							 	{$activityInfo->result->desc_rule}
							 </div>
						</div>
						<div class="volume-qr-code">
							<div class="volume-qr-inline"></div>
							{if $activityInfo->result->need_follow == 1} 
								<p class="volume-qr-code-des">请识别图中二维码关注公众号关注后才能领取呦~</p>
							{else}
								<p class="volume-qr-code-des">请关注公众号~</p>
							{/if}
						</div>
	           		</div>
           	</div>
           	<input type="hidden" name="" id="invitationInfo" data-component_authorizer_app_id="{$activityInfo->result->corp_id}" data-activity_id="{$activityInfo->result->activity_id}" data-wxState="{$userExtInfo->ext_weixin->state}" data-need_follow="{$activityInfo->result->need_follow}">
       </div>
       <div class="popup transparent-layer close-popup">
       </div>
   </div>
	{include file='../_wxconfig.tpl'}
	<script>
		//微信分享内容
			var _url = window.location.host+window.location.pathname;
			var shareData = {
			    title : '{$activityInfo->result->name}',
			    desc  : '{$activityInfo->result->name}',
			    link  : _url+'?aid={$activityInfo->result->activity_id}&uuid={$uuid}',
			    imgUrl: '{$activityInfo->result->banner}'
			};
			wxshareContent(shareData);
			wxshareContentLine(shareData);
	</script>
	{include file='../_footer.tpl'}
