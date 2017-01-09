<div class="page-group">
	<div class="page" id="team-detail">
		<div class="content">
			<div class="list-block mar0 root">
				{include file="../_contestInfo.tpl"}
				<ul id="group-detail-contest-item">
					<li class="item-content">
						<a href="javascript:void (0);"  class="item-list">
							<div class="ub-flex">
								<div class="ub-f1 list-tit-settings line-clamp line-clamp3">{$itemInfo.name}</div>
								<div class="red-color fee-info">{($itemInfo.fee/100)|string_format:"%.2f"} 元</div>
							</div>
						</a>
					</li>
				</ul>
			</div>
			<div class="list-block root">
				<div class="detail-info-main">团队信息</div>
				<ul id="group-detail-group">
					<li class="item-content">
						<div class="item-inner">
							<div class="item-title">团队名称</div>
							<div class="item-after">{$teamInfo.name}</div>
						</div>
					</li>
					<li class="item-content">
						<div class="item-inner">
							<div class="item-title">团长姓名</div>
							<div class="item-after">{$teamInfo.leader_name}</div>
						</div>
					</li>
					<li class="item-content">
						<div class="item-inner">
							<div class="item-title">团长电话</div>
							<div class="item-after">{$teamInfo.leader_contact}</div>
						</div>
					</li>
					<li class="item-content">
						<div class="item-inner">
							<div class="item-title">已加入人数</div>
							<div class="item-after">{$teamInfo.cur_member_count}/{$itemInfo.team_size}  </div>
						</div>
					</li>
					<li class="item-content">
						<div class="item-inner">
							<div class="item-title">状态</div>
							<div class="item-after red-color">
								{if $teamInfo.state == 1}进行中{/if}
								{if $teamInfo.state == 2}支付中{/if}
								{if $teamInfo.state == 3}支付失败{/if}
								{if $teamInfo.state == 4}支付成功{/if}
								{if $teamInfo.state == 5}取消{/if}
							</div>
						</div>
					</li>
				</ul>
			</div>
			<div class="list-block root">
				<div class="detail-info-main">成员名单</div>
				<ul>
				{if !empty($enrolDataList)}
					{foreach from=$enrolDataList item=temp}
					<li class="item-content">
						<a href="javascript:;" data-url="/enroldata/detail?enrol_data_id={$temp.pk_enrol_data}" class="item-inner">
							<div class="item-title">{$itemInfo.name}</div>
								<div class="item-after">{$temp.enrol_data_detail[0].value}</div>
						</a>
					</li>
					{/foreach}
				{/if}
				</ul>
			</div>
			<p>
				{if $teamInfo.state == $smarty.const.CONTEST_TEAM_STATE_INIT}
					{if $itemInfo.team_size == $teamInfo.cur_member_count}
						<div class="padding1">
							<div class="row">
								<div class="col-100"><a  href="javascript:;" id="from-submit" data-team_id="{$teamInfo.pk_team}" data-item_id="{$itemInfo.pk_contest_items}" class="button button-big  button-round button-fill">提交报名</a></div>
							</div>
						</div>
					{else}
						<div class="padding1">
							<a href="join?team_id={$teamInfo.pk_team}" class="button button-big  button-round button-fill button-warning">{$TEMPLATE_LANG[$contestInfo.template]['team_join_title']}</a>
						</div>
					{/if}
				{else}
					{if !empty($orderInfo)}
					<div class="padding1">
						<a href="/order/detail?oid={$orderInfo.pk_order}" class="button button-big  button-round button-fill">查看详情</a>
					</div>
					{/if}
				{/if}
			</p>
				
		</div>
	</div>
</div>
