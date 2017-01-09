
	<div class="panel-body">
		<div class="row data-tit">
			<div class="col-md-3">
				<div class="data-head money-num">
					<h2 class="num">{($total.amount/100)|string_format:"%.2f"}</h2>
					<span cladd="des">总金额(元)</span>
				</div>
			</div>
			<div class="col-md-3">
				<div class="data-head people-num">
					<h2 class="num">{$total.count|string_format:"%d"}</h2>
					<span cladd="des">总订单</span>
				</div>
			</div>
			<div class="col-md-3">
				<div class="data-head events-num">
					<h2 class="num">{($today.amount/100)|string_format:"%.2f"}</h2>
					<span cladd="des">今日新增金额(元)</span>
				</div>
			</div>
			<div class="col-md-3">
				<div class="data-head items-num">
					<h2 class="num">{$today.count|string_format:"%d"}</h2>
					<span cladd="des">今日新增订单</span>
				</div>
			</div>
		</div>
		{*<div class="row">*}
			{*<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>*}
		{*</div>*}
		{*<div class="sub-data row">*}
			{*<div class="col-md-3">*}
				{*<h2 class="num">{($analysisMonthly.amount_sum/100)|string_format:"%.2f"}</h2>*}
				{*<span cladd="des">月总金额</span>*}
			{*</div>*}
			{*<div class="col-md-3">*}
				{*<h2 class="num">{$analysisMonthly.order_count|string_format:"%d"}</h2>*}
				{*<span cladd="des">月报名人数</span>*}
			{*</div>*}
			{*<div class="col-md-3">*}
				{*<h2 class="num">{$analysisMonthly.contest_count|string_format:"%d"}</h2>*}
				{*<span cladd="des">月活动数</span>*}
			{*</div>*}
			{*<div class="col-md-3">*}
				{*<h2 class="num">{$analysisMonthly.item_count|string_format:"%d"}</h2>*}
				{*<span cladd="des">月项目数</span>*}
			{*</div>*}
		{*</div>*}
	</div>

{*{include file='./_chat_script.tpl'}*}

