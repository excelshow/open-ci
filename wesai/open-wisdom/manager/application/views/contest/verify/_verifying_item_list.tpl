{if !empty($itemList)}
	{foreach from=$itemList item=item}
		<li class="row">
			<div class="item-con">
				<div class="img-box"><img src="{$item->banner|cdnurl:'upload'}?imageMogr2/thumbnail/x120" alt=""></div>
				<div class="ub item">
					<div class="item-tit">活动名称</div>
					<div class="ub-f1 des">{$item->cname}</div>
				</div>
				<div class="ub item">
					<div class="item-tit">项目名称</div>
					<div class="ub-f1 des">{$item->name}</div>
				</div>
				<div class="ub item">
					<div class="item-tit">开赛时间</div>
					<div class="ub-f1 des txt-blue">{$item->start_time}</div>
				</div>
				<div class="ub item">
					<div class="item-tit">报名人数</div>
					<div class="ub-f1 des txt-red">{$item->order_count}</div>
				</div>
				<div class="ub item">
					<div class="item-tit">检票人数</div>
					<div class="ub-f1 des txt-green">{$item->order_count_verified}</div>
				</div>
				<div class="submit-btn">
					<a data-item-id="{$item->pk_contest_items}" class="start-scan">开始检票</a>
				</div>
			</div>
		</li>
	{/foreach}
{/if}


