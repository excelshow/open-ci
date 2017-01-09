{include file='_header.tpl'}
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
					{include file="../../_top_two_nav.tpl"}
					<li>
						<a href="/contest/contest/index">活动管理</a>
					</li>
					<li class="active">新增项目</li>
				</ol>
			</div>
			<div class="right-con">
				<div class="panel panel-blue">
					<div class="panel-heading">
						<h3 class="panel-title">新增项目</h3>
					</div>
					{include file="../contest/edititem/_items.tpl"}
				</div>
			</div>
		</div>
	</div>
</div>
{include file='_foot.tpl'}
