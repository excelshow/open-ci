{include file='../header.tpl'}
<div class="page-group">
	<div class="page" id="group-create">
		<div class="content">
			<div class="list-block mar0 root">
				{include file='../_contestInfo.tpl'}
				<ul id="group-detail-contest-item">
					{foreach from=$itemList item=value }
					<li class="item-content">
						<a href="javascript:void (0);"  class="item-list">
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
				<div class="detail-info-main">创建小组</div>
				<p class="prompt-text group_prompt">{$TEMPLATE_LANG[$contestInfo.template]['group_tips']}</p>
				<ul id="group-create-info">
					<li>
						<div class="item-content">
							<div class="item-inner">
								<div class="item-title label">小组名称</div>
								<div class="item-input">
									<input name="name" maxlength="20" type="text" placeholder="小组名称"></div>
							</div>
						</div>
					</li>
					<li>
						<div class="item-content">
							<div class="item-inner">
								<div class="item-title label">组长姓名</div>
								<div class="item-input">
									<input name="leader_name" maxlength="10"  type="text" placeholder="组长姓名"></div>
							</div>
						</div>
					</li>
					<li>
						<div class="item-content">
							<div class="item-inner">
								<div class="item-title label">组长电话</div>
								<div class="item-input">
									<input name="leader_contact" type="tel" placeholder="小组电话"></div>
							</div>
						</div>
					</li>
				</ul>
			</div>
			<p>
				<div class="padding1">
					<a id="group-create-submit" href="javascript:void(0);" class="button  button-round button-big button-fill">创建小组</a>
				</div>
			</p>
		</div>
	</div>
</div>
{include file='../wxsdk.tpl'}
{include file='../footer.tpl'}

