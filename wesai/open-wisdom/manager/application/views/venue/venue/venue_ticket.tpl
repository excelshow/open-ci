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
					<li class="active">场馆票管理</li>
				</ol>
			</div>
			<div class="right-con">
				<div class="panel panel-blue day-statistics ">
					<div class="panel-heading">
						<h3 class="panel-title">场馆票管理</h3>
					</div>
					<div class="panel-body  event-details">
						<div class="clearfix addbutton-box">
								<a class="pull-right add-button" href="/contest/contest/add_item">添加场馆票</a>
						</div>
						<div class="ticket-padd76">
							<div class="row field-list-root">
								<div class="col-sm-12">
									<div class="ticket-head pull-left ub ub-ac ub-pc"><span>单次票</span></div>
									<div class="ticket-con">
										<ul class="ticket-des ub ub-ac ub-pc">
											<li class="f1 the-shelves">上架中</li>
											<li class="f1">项目: 篮球</li>
											<li class="ub-flex Price-box">
												<span class="ub ub-ac ub-pc">价格</span>
												<span class="f1">
													<p>工作日票：  <span class="Price-red">50元</span> </p>
													<p>周 末 票：  <span class="Price-red">100元</span></p>  
													<p>节 假 日：  <span class="Price-red">150元</span></p>   
												</span>
											</li>
											<li class="f1">库存:100张</li>

											<li class="f1 dotted-line">
												<a href="" class="huobtn btn-blue pull-left mrl20">编辑</a>
												<a href="" class="btn-edit huobtn pull-left mrl20">下架</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<div class="ticket-padd76 off-the-shelf">
							<div class="row field-list-root">
								<div class="col-sm-12">
									<div class="ticket-head pull-left ub ub-ac ub-pc"><span>单次票</span></div>
									<div class="ticket-con">
										<ul class="ticket-des ub ub-ac ub-pc">
											<li class="f1 the-shelves">上架中</li>
											<li class="f1">项目: 篮球</li>
											<li class="ub-flex Price-box">
												<span class="ub ub-ac ub-pc">价格</span>
												<span class="f1">
													<p>工作日票：   <span class="Price-red"> 50元</span> </p>
													<p>周 末 票：   <span class="Price-red"> 100元</span></p>  
													<p>节 假 日：  <span class="Price-red"> 150元</span></p>   
												</span>
											</li>
											<li class="f1">库存:100张</li>

											<li class="f1 dotted-line">
												<a href="" class="huobtn btn-blue pull-left mrl20">编辑</a>
												<a href="" class="btn-edit huobtn pull-left mrl20">下架</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<div class="ticket-padd76">
							<div class="row field-list-root">
								<div class="col-sm-12">
									<div class="ticket-head pull-left ub ub-ac ub-pc"><span>单次票</span></div>
									<div class="ticket-con">
										<ul class="ticket-des ub ub-ac ub-pc">
											<li class="f1 the-shelves">上架中</li>
											<li class="f1">项目: 篮球</li>
											<li class="ub-flex Price-box">
												<span class="ub ub-ac ub-pc">价格</span>
												<span class="f1">
													<p>工作日票：  <span class="Price-red"> 50元</span> </p>
													<p>周 末 票：   <span class="Price-red"> 100元</span></p>  
													<p>节 假 日：  <span class="Price-red"> 150元</span></p>   
												</span>
											</li>
											<li class="f1">库存:100张</li>

											<li class="f1 dotted-line">
												<a href="" class="huobtn btn-blue pull-left mrl20">编辑</a>
												<a href="" class="btn-edit huobtn pull-left mrl20">下架</a>
											</li>
										</ul>
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


