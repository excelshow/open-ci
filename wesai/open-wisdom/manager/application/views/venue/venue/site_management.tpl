{include file='venue/_header.tpl'}
<link rel="stylesheet" type="text/css" href="{'manager/css/wangEditor.min.css'|cdnurl}">
<!--—自适应布局---->
<div class="container-fluid">
	<div class="row">
		<!-- leftStart -->
		{include file='venue/_leftside.tpl'}
		<!-- leftEnt -->
		<!-- rightStart-->
		<div class="right-main">
			<div class="breadcrumbs-box">
				<ol class="breadcrumb">
					{include file="venue/_top_sub_navi.tpl"}
					<li><a href="/venue/venue/get_list">场馆管理</a></li>
					<li class="active">场地管理</li>
				</ol>
			</div>
			<div class="right-con">
				<div class="panel panel-blue day-statistics ">
					<div class="panel-heading">
						<h3 class="panel-title">场地管理</h3>
					</div>
					<div class="panel-body  event-details">
						<div class="clearfix addbutton-box">
								<a class="pull-right add-button" href="/contest/contest/add_item">添加新场地</a>
						</div>
						<div class="padd80">
							<div class="row field-list-root">
								<div class="col-sm-4 field-list-box">
									<div class="field-list-item">
										<div class="head exceed-omitted">
											3号场地
										</div>
										<div class="main clearfix">
											<div class="the-shelves"><span class="icons sales-status"></span>上架中</div>
											<div class="underline">
												<div class="dt">场地项目：</div>
												<div class="dd">篮球</div>
											</div>
											<div class="underline site-main">
												<div>价格</div>
												<div>工作日：</div>
												<div>
													<div class="dt">9:00-22:00</div>
													<div class="dd text-right">20元</div>
												</div>
												<div>
													<div class="dt">9:00-22:00</div>
													<div class="dd text-right">20元</div>
												</div>
												<div>
													<div class="dt">9:00-22:00</div>
													<div class="dd text-right">20元</div>
												</div>
												<div>周   末：</div>
												<div>
													<div class="dt">9:00-22:00</div>
													<div class="dd text-right">20元</div>
												</div>
												<div>
													<div class="dt">9:00-22:00</div>
													<div class="dd text-right">20元</div>
												</div>
												<div>
													<div class="dt">9:00-22:00</div>
													<div class="dd text-right">20元</div>
												</div>
												<div>节假日：</div>
												<div>
													<div class="dt">9:00-22:00</div>
													<div class="dd text-right">20元</div>
												</div>
												<div>
													<div class="dt">9:00-22:00</div>
													<div class="dd text-right">20元</div>
												</div>
												<div>
													<div class="dt">9:00-22:00</div>
													<div class="dd text-right">20元</div>
												</div>
											</div>
											<div class="btn-group clearfix pull-right">
												<a href="" class="huobtn btn-blue pull-left mrl20">编辑</a>
												<a href="" class="btn-edit huobtn pull-left mrl20">下架</a>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-4 field-list-box off-the-shelf">
									<div class="field-list-item">
										<div class="head exceed-omitted">
											3号场地
										</div>
										<div class="main clearfix">
											<div class="the-shelves"><span class="icons sales-status"></span>上架中</div>
											<div class="underline">
												<div class="dt">场地项目：</div>
												<div class="dd">篮球</div>
											</div>
											<div class="underline site-main">
												<div>价格</div>
												<div>工作日：</div>
												<div>
													<div class="dt">9:00-22:00</div>
													<div class="dd text-right">20元</div>
												</div>
												<div>
													<div class="dt">9:00-22:00</div>
													<div class="dd text-right">20元</div>
												</div>
												<div>
													<div class="dt">9:00-22:00</div>
													<div class="dd text-right">20元</div>
												</div>
												<div>周   末：</div>
												<div>
													<div class="dt">9:00-22:00</div>
													<div class="dd text-right">20元</div>
												</div>
												<div>
													<div class="dt">9:00-22:00</div>
													<div class="dd text-right">20元</div>
												</div>
												<div>
													<div class="dt">9:00-22:00</div>
													<div class="dd text-right">20元</div>
												</div>
												<div>节假日：</div>
												<div>
													<div class="dt">9:00-22:00</div>
													<div class="dd text-right">20元</div>
												</div>
												<div>
													<div class="dt">9:00-22:00</div>
													<div class="dd text-right">20元</div>
												</div>
												<div>
													<div class="dt">9:00-22:00</div>
													<div class="dd text-right">20元</div>
												</div>
											</div>
											<div class="btn-group clearfix pull-right">
												<a href="" class="huobtn btn-blue pull-left mrl20">编辑</a>
												<a href="" class="btn-edit huobtn pull-left mrl20">下架</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</div>
		<!-- rightEnd -->
	</div>
</div>
<!-- upload -->

<script src="{'manager/lib/plupload/plupload.full.min.js'|cdnurl}"></script>
<script src="{'manager/lib/wangEditor.min.js'|cdnurl}"></script>
<script src="{'manager_contest/js/distpicker.data.js'|cdnurl}"></script>
<script src="{'manager_contest/js/distpicker.js'|cdnurl}"></script>

{include file='venue/_foot.tpl'}

