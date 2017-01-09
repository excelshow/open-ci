{include file='_header.tpl'}
<!--—自适应布局---->
<div class="container-fluid">
	<div class="row">
		<!-- leftStart -->
		{include file="_leftside.tpl"}
		<!-- leftEnt -->
		<!-- rightStart-->
		<div class="right-main">
			<div class="breadcrumbs-box">
				<ol class="breadcrumb">
					{include file="../../_top_sub_navi.tpl"}
					<li class="active">活动报名</li>
					<li class="active">统计</li>
				</ol>
			</div>
			<div class="right-con">
			<div class="panel panel-blue">
				<div class="panel-heading">
					<h3 class="panel-title">应用总概览</h3>
				</div>
				{include file="./_contest_total.tpl"}
			</div>
				{*{include file="./_contest_daily.tpl"}*}
			</div>
		</div>
		<!-- rightEnd -->
	</div>
</div>

{include file='../../_foot.tpl'}
