{include file='_header.tpl'}
<!--—自适应布局---->
<div class="container-fluid">
	<div class="row">
		<!-- leftStart -->
		{include file="_leftside_manage.tpl"}
		<!-- leftEnt -->
		<!-- rightStart-->
		<div class="right-main">
			<div class="breadcrumbs-box">
				<ol class="breadcrumb">
					{include file="../../_top_sub_navi.tpl"}
					<li class="active">应用首页</li>
				</ol>
			</div>
			<div class="right-con">
			<div class="panel panel-blue">
				<div class="panel-heading">
					<h3 class="panel-title">活动报名</h3>
				</div>
				{include file="../../contest/analysis/_contest_total.tpl"}
			</div>
				<div class="panel panel-blue">
					<div class="panel-heading">
						<h3 class="panel-title">帮助中心</h3>
					</div>
					<div class="">
						<div class="help-module">
							<h4>绑定公众号步骤</h4>
							<div class="help-process">
								<div class="help-tit"><a href="/wxapps/auth"><span>1</span>公众号管理</a></div>
								<div class="help-tit"><span>2</span>点击绑定公众号</div>
								<div class="help-tit"><span>3</span>扫码授权</div>
							</div>

						</div>
						<div class="help-module">
							<h4>创建报名活动步骤</h4>
							<div class="help-process">
								<div class="help-tit"><a href="/contest/contest/add_contest"><span>1</span>添加活动</a></div>
								<div class="help-tit"><span>2</span>活动添加项目</div>
								<div class="help-tit"><span>3</span>绑定公众号</div>
								<div class="help-tit"><span>4</span>上架活动</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- rightEnd -->
	</div>
</div>
{include file="_foot.tpl"}
