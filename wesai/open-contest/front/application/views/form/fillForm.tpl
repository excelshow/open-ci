{include file='../header.tpl'}
<div class="page-group">
	<div class="page" id="fillForm-page">
		<div class="content">
			<div class="list-block mar0 root">
				{include file='../_contestInfo.tpl'}
				<ul id="group-detail-contest-item">
					<li class="item-content">
						<a href="javascript:void (0);"  class="item-list">
							<div class="ub-flex">
								<div class="ub-f1 list-tit-settings line-clamp line-clamp3">{$itemInfo.name}</div>
								<div class="red-color fee-info "><span class="unit-price" data-price="{($itemInfo.fee/100)|string_format:"%.2f"}">{($itemInfo.fee/100)|string_format:"%.2f"}</span> 元</div>
							</div>
						</a>
					</li>
				</ul>
				{if empty($group_id) && empty($team_id)}
					{if $itemInfo.multi_buy == 1}
						<div class="gw_main clearfix">
							<div class="gw_num fr">
								<span class="jian"><i class="iconfont">&#xe729;</i></span>
								<span class="num" data-cur_stock="{$itemInfo.cur_stock}" id="quantity">1</span>
								<span class="add activity"><i class="iconfont">&#xe727;</i></span>
							</div>
						</div>
					{/if}
				{/if}
			</div>
			<div class="list-block root">
				<div class="detail-info-main">填写表单</div>
				<ul id="formList"></ul>
				<div class="padding1">
					<a id="from-submit" href="javascript:void(0);" class="button  button-round button-big button-fill">提交</a>
				</div>
			</div>
		</div>
	</div>
</div>
{include file='../wxsdk.tpl'}
{include file='../footer.tpl'}
