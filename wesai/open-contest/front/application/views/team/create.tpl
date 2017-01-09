{include file="../header.tpl"}
<div class="page-group">
	<div class="page" id="team-create">
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
				<div class="detail-info-main">创建团队</div>
					<ul id="team-create-info">
						<li>
							<div class="item-content">
								<div class="item-inner">
									<div class="item-title label">团队名称</div>
									<div class="item-input">
										<input name="name" type="text" maxlength="20"  placeholder="团队名称"></div>
								</div>
							</div>
						</li>
						<li>
							<div class="item-content">
								<div class="item-inner">
									<div class="item-title label">团长姓名</div>
									<div class="item-input">
										<input name="leader_name" type="text" maxlength="10"  placeholder="团长姓名"></div>
								</div>
							</div>
						</li>
						<li>
							<div class="item-content">
								<div class="item-inner">
									<div class="item-title label">团长电话</div>
									<div class="item-input">
										<input name="leader_contact" type="tel" placeholder="团长电话"></div>
								</div>
							</div>
						</li>
					</ul>
				</div>
				<p>
					<div class="padding1">
						<a id="team-create-submit" href="javascript:void(0);" class="button  button-round button-big button-fill">创建团队</a>
					</div>
				</p>
			</div>
		</div>
	</div>

</div>
{include file='../wxsdk.tpl'}
{include file="../footer.tpl"}
