<div class="panel panel-blue">
	<div class="panel-heading">
		<h3 class="panel-title">日数据统计</h3>
	</div>
	<div class="day-statistics">
		<table class="table txt-cen">
			<thead>
			<tr>
				<th>日期</th>
				<th>总金额</th>
				<th>报名人数</th>
				<th>活动数</th>
				<th>项目数</th>
				{*<th>操作</th>*}
			</tr>
			</thead>
			<tbody>
			{if !empty($dailyList)}
				{foreach from=$dailyList item=item}
					<tr>
						<td>{$item->date}</td>
						<td>{($item->amount_sum/100)|string_format:"%.2f"}</td>
						<td>{$item->order_count|string_format:"%d"}</td>
						<td>{$item->contest_count|string_format:"%d"}</td>
						<td>{$item->item_count|string_format:"%d"}</td>
						{*<td>*}
						{*<a href="#" class="seedetails-btn">查看</a>*}
						{*</td>*}
					</tr>
				{/foreach}
			{else}
			<tr style="height:50px;">
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				{*<td>*}
				{*<a href="#" class="seedetails-btn">查看</a>*}
				{*</td>*}
			</tr>
			{/if}
			</tbody>
		</table>
	</div>
</div>
