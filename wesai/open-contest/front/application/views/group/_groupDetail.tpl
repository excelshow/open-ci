<div class="page-group">
	<div class="page" id="group-detail">
		<div class="content">
			<div class="list-block mar0 root">
				{include file='../_contestInfo.tpl'}
				<ul id="group-detail-contest-item">
					{foreach from=$itemList item=value }
						<li class="item-content">
							<a href="javascript:void (0);" class="item-list">
								<div class="ub-flex">
									<div class="ub-f1 list-tit-settings line-clamp line-clamp3">{$value.name}</div>
									<div class="red-color fee-info">{($value.fee/100)|string_format:"%.2f"} 元</div>
								</div>
							</a>
						</li>
					{/foreach}
				</ul>
			</div>
			<div class="list-block root">
				<div class="detail-info-main">小组信息</div>
				<p class="prompt-text group_prompt">{$TEMPLATE_LANG[$contestInfo.template]['group_detail_tips']}</p>
				<ul id="group-detail-group">
					<li class="item-content">
						<div class="item-inner">
							<div class="item-title">小组名称</div>
							<div class="item-after">{$groupInfo.name}</div>
						</div>
					</li>
					<li class="item-content">
						<div class="item-inner">
							<div class="item-title">组长姓名</div>
							<div class="item-after">{$groupInfo.leader_name}</div>
						</div>
					</li>
					<li class="item-content">
						<div class="item-inner">
							<div class="item-title">组长电话</div>
							<div class="item-after">{$groupInfo.leader_contact}</div>
						</div>
					</li>
					<li class="item-content">
						<div class="item-inner">
							<div class="item-title">已加入人数</div>
							<div class="item-after">{$groupInfo.cur_member_count}</div>
						</div>
					</li>
					<li class="item-content">
						<div class="item-inner">
							<div class="item-title">状态</div>
							<div class="item-after red-color">
								{if $groupInfo.state == 1}进行中{/if}
								{if $groupInfo.state == 2}支付中{/if}
								{if $groupInfo.state == 3}支付失败{/if}
								{if $groupInfo.state == 4}支付成功{/if}
								{if $groupInfo.state == 5}取消{/if}
							</div>
						</div>
					</li>
				</ul>
			</div>
			<div class="list-block root">
				<div class="detail-info-main">成员名单</div>
				<div class="group-detail-enroldata">
					{if !empty($enrolDataList)}
						{foreach from=$enrolDataList item=item}
							<li class="item-content">
								<a href="javascript:;" data-url="/enroldata/detail?enrol_data_id={$item.pk_enrol_data}" class="item-inner">
									<div class="item-title">{$itemList[$item.fk_contest_items].name}</div>
									<div class="item-after">{$item.enrol_data_detail[0].value}</div>
								</a>
							</li>
						{/foreach}
					{/if}
				</div>
			</div>
			<div>
				{if $groupInfo.state == $smarty.const.CONTEST_GROUP_STATE_INIT}
					{if $groupInfo.cur_member_count > 0}
						<div class="padding1">
							<div class="row">
								<div class="col-100">
									<p>
										<a href="/group/join?group_id={$groupInfo.pk_group}" class="button button-big  button-round button-warning">{$TEMPLATE_LANG[$contestInfo.template]['group_join_title']}</a>
									</p>
								</div>
								<div class="col-100">
									<a id="group-detail-submit" data-group_id="{$groupInfo.pk_group}" data-cid="{$contestInfo.pk_contest}" href="javascript:void(0);" class="button button-big  button-round button-fill">提交报名</a>
								</div>
							</div>
						</div>
					{else}
						<div class="padding1">
							<a href="/group/join?group_id={$groupInfo.pk_group}" class="button button-big  button-round button-fill button-warning">{$TEMPLATE_LANG[$contestInfo.template]['group_join_title']} </a>
						</div>
					{/if}
				{else}
					{if !empty($orderInfo)}
						<div class="padding1">
							<a href="/order/detail?oid={$orderInfo.pk_order}" class="button button-big  button-round button-fill"> 查看详情</a>
						</div>
					{/if}
				{/if}
			</div>
		</div>
	</div>
</div>

