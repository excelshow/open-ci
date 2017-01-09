<div class="card contest-list">
	<div class="lebal-block">
		<div class="card-content">
			<div class="item-title font-sise18 line-clamp2 line-clamp">{$contestInfo.name}</div>
			<div class="color-gray mat5"><i class="iconfont mar5"></i>
				{include file="./_location.tpl"}{$contestInfo.location}
			</div>
			<div class="color-gray mat5"><i class="iconfont mar5"></i>
				{$contestInfo.sdate_start|date_format:'%Y年%m月%d日'}－{$contestInfo.sdate_end|date_format:'%Y年%m月%d日'}
			</div>
		</div>
	</div>
</div>
