{include file="../header.tpl"}
<div class="page-group">
	<div class="page" id="team-join">
		<div class="content">
			<div class="invitation-bg">
				<div class="blue-bg">
					<div class="invitation-info">
						<div class="contest-title">{$teamInfo.leader_name}邀请您加入TA的团队，一起参加“{$contestInfo.name}”</div>
						<div class="address">地点：
							{include file="../_location.tpl"}{$contestInfo.location}
						</div>
						<div class="time dashed-line">活动时间：
							<time>{$contestInfo.sdate_start|date_format:'%Y年%m月%d日'}－{$contestInfo.sdate_end|date_format:'%Y年%m月%d日'}</time>
						</div>
					</div>
					<div class="invitation-link"><a href="#" class="open-about">{$TEMPLATE_LANG[$contestInfo.template]['team_join_link_contest_detail']}</a></div>
					<div>
						<div class="join-contest-tit">{$TEMPLATE_LANG[$contestInfo.template]['team_join_item_list']}</div>
						<ul class="contest-list" id="team-join-contest-item">
							<li class="dashed-line padtb10 clearfix">
								<div class="ub-flex">
									<div class="ub-f1 contest-list-title">{$itemInfo.name}</div>
									<div class="red-color fee-info">{($itemInfo.fee/100)|string_format:"%.2f"} 元</div>
								</div>
								{if $contestInfo.publish_state == $smarty.const.CONTEST_PUBLISH_STATE_SELLING}
									{if ($teamInfo.state == $smarty.const.CONTEST_TEAM_STATE_INIT)}
										{if ($teamInfo.max_member_count == $teamInfo.cur_member_count)}
											<div class="fr button mat5 btn-red button-round padlr">满额</div>
										{else}
											{if ($contestInfo.publish_state != $smarty.const.CONTEST_PUBLISH_STATE_SELLING)}
												<div class="fr button mat5 btn-red button-round padlr">已停止</div>
											{else}
												{if (strtotime($contestInfo.sdate_end) <= $smarty.now)}
													<div class="fr button mat5 btn-red button-round padlr">已停止</div>
												{else}
													<a class="clearfix mat5 verify_your_phone_number_join" href="javascript:;" data-href="/form/fillForm?item_id={$itemInfo.pk_contest_items}&team_id={$teamInfo.pk_team}&cid={$contestInfo.pk_contest}">
														<div class="fr button btn-bluegreen button-round padlr">{$TEMPLATE_LANG[$contestInfo.template]['team_join_button_buy']}</div>
													</a>
												{/if}
											{/if}
										{/if}
									{/if}
								{else}
									<div class="fr button btn-gray button-round padlr">已下架</div>
								{/if}
							</li>
						</ul>
					</div>
					{if $teamInfo.state == $smarty.const.CONTEST_TEAM_STATE_INIT and $contestInfo.publish_state == CONTEST_PUBLISH_STATE_SELLING}
						{if !empty($submitOrder)}
							{if $teamInfo.max_member_count == $teamInfo.cur_member_count and strtotime($itemInfo.end_time) > $smarty.now }
								<div class="content-block">
									<a href="javascript:;" class="button button-big button-fill button-round submit-order-btn" data-team_id="{$teamInfo.pk_team}" data-item="{$teamInfo.fk_contest_items}">提交订单</a>
								</div>
							{elseif $teamInfo.max_member_count > $teamInfo.cur_member_count and strtotime($itemInfo.end_time) > $smarty.now}
								<div class="content-block">
									<a href="javascript:;" class="button button-big button-fill button-round disabled">提交订单</a>
								</div>
							{else}
								<div class="content-block">
									<a href="javascript:;" class="button button-big button-fill button-round button-danger">已过期</a>
								</div>
							{/if}
						{/if}
					{else}
						<div class="content-block">
							<a href="/contest/index" class="button button-big button-fill button-round">查看其它活动</a>
						</div>
					{/if}
					<div class="list-block dashed">
						<div class="join-contest-tit dashed-line">{$TEMPLATE_LANG[$contestInfo.template]['team_join_member_list']}</div>
						<ul id="enroldata_list" class="transparent" data-state="{$teamInfo.state}"></ul>
					</div>
				</div>
				<div class="pic-transparent"></div>
			</div>
		{include file='../_verifyPhone.tpl'}
		</div>
	</div>
	<!-- About Popup -->
	<div class="popup popup-about mask-popup popup-about-team">
	  	<div class="mask-info ub-flex ub-column">
	  		<div class="mask-info-title">{$TEMPLATE_LANG[$contestInfo.template]['team_join_contest_info']} <a class="close-btn close-popup" href="javascript:;"></a></div>
	  		<div class="mask-info-con ub-f1">
				{$contestInfo.intro}
	  		</div>
	  	</div>
	</div>
</div>
{include file='../wxsdk.tpl'}
<script>
	//微信分享内容
	var shareData = {
	    title : '{$contestInfo.name}',
	    desc  : '{$contestInfo.name}',
	    link  : document.URL,
	    imgUrl: '{$contestInfo.logo|cdnurl:"upload"}'
	};
	wxshareContent(shareData);
	wxshareContentLine(shareData);
</script>
{include file="../footer.tpl"}
