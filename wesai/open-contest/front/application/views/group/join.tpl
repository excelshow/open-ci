{include file="../header.tpl"}
<div class="page-group">
	<div class="page" id="group-join">
		<div class="content">
			<div class="invitation-bg">
				<div class="blue-bg">
					<div class="invitation-info">
						<div class="contest-title">{$groupInfo.leader_name}邀请您加入TA的小组，一起参加“{$contestInfo.name}”</div>
						<div class="address">地点：
							{include file="../_location.tpl"}{$contestInfo.location}</div>
						<div class="time dashed-line">活动时间：
							<time>{$contestInfo.sdate_start|date_format:'%Y年%m月%d日'}－{$contestInfo.sdate_end|date_format:'%Y年%m月%d日'}</time>
						</div>
					</div>
					<div class="invitation-link">
						<a href="javascript:;" class="open-about">{$TEMPLATE_LANG[$contestInfo.template]['group_join_link_contest_detail']}</a>
					</div>
					<div>
						<div class="join-contest-tit">{$TEMPLATE_LANG[$contestInfo.template]['group_join_item_list']}</div>
						<ul class="contest-list" id="team-join-contest-item">
							{foreach from=$itemList item=value }
								<li class="dashed-line padtb10 clearfix">
									<div class="ub-flex">
										<div class="ub-f1 contest-list-title">{$value.name}</div>
										<div class="red-color fee-info">{($value.fee/100)|string_format:"%.2f"} 元</div>
									</div>
									{if $contestInfo.publish_state == $smarty.const.CONTEST_PUBLISH_STATE_SELLING}
										{if $value.cur_stock == 0}
											<div class="fr button mat5 btn-red button-round padlr">满额</div>
										{else}
											{if ($groupInfo.state == $smarty.const.CONTEST_GROUP_STATE_INIT) and strtotime($value.end_time) > time() }
												<a class="clearfix mat5 verify_your_phone_number_join" href="javascript:;" data-href="/form/fillForm?item_id={$value.pk_contest_items}&group_id={$groupInfo.pk_group}&cid={$contestInfo.pk_contest}&U_multi_buy=2">
													<div class="fr button mat5 btn-bluegreen button-round padlr">{$TEMPLATE_LANG[$contestInfo.template]['group_join_button_buy']} </div>
												</a>
											{else}
												<div class="fr button mat5 btn-red button-round padlr">已停止</div>
											{/if}
										{/if}
									{else}
										<div class="fr button mat5 btn-gray button-round padlr">已下架</div>
									{/if}
								</li>
							{/foreach}
						</ul>
					</div>
					{if $groupInfo.state == $smarty.const.CONTEST_GROUP_STATE_INIT and $contestInfo.publish_state == $smarty.const.CONTEST_PUBLISH_STATE_SELLING}
						{if !empty($submitOrder)}
							{if $groupInfo.cur_member_count >= 1 and strtotime($contestInfo.sdate_end) > time()}
								<div class="content-block">
									<a href="javascript:;" class="button button-big button-fill button-round submit-order-btn" data-group_id="{$groupInfo.pk_group}" data-cid="{$groupInfo.fk_contest}">提交订单</a>
								</div>
							{elseif $groupInfo.cur_member_count == 0 and strtotime($contestInfo.sdate_end) > time() }
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
					<div class="list-block">
						<div class="join-contest-tit">{$TEMPLATE_LANG[$contestInfo.template]['group_join_member_list']}</div>
						<ul class="transparent" id="group_enroldata_list" data-state="{$groupInfo.state}">
						</ul>
					</div>
				</div>
				<div class="pic-transparent"></div>
			</div>
		</div>
	</div>
	<!-- About Popup -->
	<div class="popup popup-about mask-popup popup-about-team">
		<div class="mask-info ub-flex ub-column">
			<div class="mask-info-title">{$TEMPLATE_LANG[$contestInfo.template]['group_join_contest_info']}
				<a class="close-btn close-popup" href="javascript:;"></a></div>
			<div class="mask-info-con ub-f1">
				{$contestInfo.intro}
			</div>
		</div>
	</div>
	{include file='../_verifyPhone.tpl'}
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
