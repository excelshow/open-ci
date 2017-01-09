{include file='../header.tpl'}
<div class="page-group">
	<div class="page" id="contest-detail">
		<div class="content">
			<div class="card contest-list">
				<div class="lebal-block">
					<div valign="bottom" class="card-header color-white no-border no-padding">
						<img class='card-cover' src="{$page_vars.CDN_URL}/{$result->banner}" alt=""></div>
					<div class="card-content">
						<div class="mat10">
							<div class="item-title line-clamp2 font-sise18 line-clamp">{$result->name}</div>
							{if $result->show_enrol_data_count == 1}
								<div class="red-color mat5"><i class="iconfont mar5">&#xe617;</i>已售：{if !empty($result->enrol_data_count)}{$result->enrol_data_count}{else}0{/if}</div>
							{/if}
							<div class="color-gray mat5"><i class="iconfont mar5">&#xe60f;</i>
							{include file="../_location.tpl"}{$result->location}
							</div>
							<div class="color-gray"><i class="iconfont mar5">&#xe607;</i>
								{$result->sdate_start|date_format:'%Y年%m月%d日'}－{$result->sdate_end|date_format:'%Y年%m月%d日'}
							</div>
							{if !empty($result->service_mail)}
								<div class="color-gray">
									<i class="iconfont mar5">&#xe602;</i>
									{$result->service_mail}
								</div>
							{/if}
							{if !empty($result->service_tel)}
								<div class="color-gray">
									<i class="iconfont mar5">&#xe601;</i>
									<a href="tel:{$result->service_tel}" class="tel">{$result->service_tel}</a>
								</div>
							{/if}
						</div>
					</div>
				</div>
			</div>
			<div class="list-block root">         
				<div class="detail-info-main">{$TEMPLATE_LANG[$result->template]['detail_item_list']}</div>
				<ul>
					{if !empty($contestitemList)}
						{foreach from=$contestitemList item=$v}
							<li class="item-content">
								<div class="item-list">
									<div class="ub-flex">
										<div class="ub-f1 list-tit-settings line-clamp line-clamp3">{$v->name}</div>
										<div class="red-color fee-info">{($v->fee/100)|string_format:"%.2f"} 元</div>
									</div>
									{if $result->publish_state == 3}
										{if $v->type == 1}
											{if $v->cur_stock == 0}
												<a class="fr button btn-red button-round mat5 padlr" href="javascript:;">满额</a>
											{elseif strtotime($v->end_time)< time() or $v->state != 1}
												<a class="fr button btn-red button-round mat5 padlr" href="javascript:;">已停止</a>
											{else}
												{if $smarty.const.IS_SYSTEM_UPGRADING}
													<a class="fr button btn-gray button-round mat5 padlr" href="javascript:;">升级中</a>
												{else}
													<a class="fr button btn-bluegreen button-round mat5 padlr personal-registration-btn" data-item_id="{$v->pk_contest_items}" data-cid="{$v->fk_contest}" href="javascript:;">
													{$TEMPLATE_LANG[$result->template]['detail_button_buy']}
													</a>
												{/if}
											{/if}
										{elseif $v->type==2 and $result->template == 1}
											{if $v->team_cur_stock == 0}
												<a class="fr button btn-red button-round mat5 padlr" href="javascript:;">满额</a>
											{elseif strtotime($v->end_time) < time() or $v->state != 1}
												<a class="fr button btn-red button-round mat5 padlr" href="javascript:;">已停止</a>
											{else}
												{if $smarty.const.IS_SYSTEM_UPGRADING}
													<a class="fr button btn-gray button-round mat5 padlr" href="javascript:;">升级中</a>
												{else}
													<a class="fr button btn-bluegreen button-round mat5 padlr team-registration-btn" data-item_id="{$v->pk_contest_items}" data-cid="{$v->fk_contest}" href="javascript:;">{$TEMPLATE_LANG[$result->template]['detail_button_team_buy']}</a>
												{/if}
											{/if}

										{/if}
									{else}
										<a class="fr button btn-gray button-round mat5 padlr" href="javascript:;">{$CONTEST_STATE_LIST[$result->publish_state]}</a>
									{/if}
								</div>
							</li>
						{/foreach}
						{*{if $result->publish_state == 3}*}
							{*{foreach from=$contestitemList item=$value}*}
								{*{if $value->type==1 and $result->template == 1}*}
									{*<li>*}
										{*<div class="padding1 manypeople-signup">*}
											{*{if $smarty.const.IS_SYSTEM_UPGRADING}*}

											{*{else}*}
											{*<a href="javascript:;" data-cid="{$value->fk_contest}" class="group-registration-btn btn-bluegreen">{$TEMPLATE_LANG[$result->template]['detail_button_group_buy']}</a>*}
											{*{/if}*}
										{*</div>*}
									{*</li>*}
									{*{break}*}
								{*{/if}*}
							{*{/foreach}*}
						{*{/if}*}
					{/if}
				</ul>
			</div>
			<div class="list-block root bg-white">
				<div class="detail-info-main">{$TEMPLATE_LANG[$result->template]['detail_info']}</div>
				<div class="bg-white info-pale">{$result->intro}</div>
			</div>
		</div>
	</div>
	{include file='../_verifyPhone.tpl'}
</div>
{include file='../wxsdk.tpl'}
{if empty($shareInfo)}
<script>
	//微信分享内容
	var shareData = {
		title : '{$result->name}',
		desc  : '{$result->name}',
		link  : document.URL,
		imgUrl: '{$result->logo|cdnurl:"upload"}'
	};
	wxshareContent(shareData);
	wxshareContentLine(shareData);
</script>
{else}
<script>
	//微信分享内容
	var shareData = {
		title : '{$shareInfo->title}',
		desc  : '{$shareInfo->intro}',
		link  : document.URL,
		imgUrl: '{$shareInfo->image|cdnurl:"upload"}'
	};
	//微信分享内容
	var shareFriendData = {
		title : '{$shareInfo->timeline_title}',
		desc  : '{$shareInfo->intro}',
		link  : document.URL,
		imgUrl: '{$shareInfo->image|cdnurl:"upload"}'
	};
	wxshareContent(shareData);
	wxshareContentLine(shareFriendData);
</script>
{/if}
{include file='../footer.tpl'}

