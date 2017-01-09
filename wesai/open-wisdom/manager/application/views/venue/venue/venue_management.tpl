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
					<li class="active">添加场馆</li>
				</ol>
			</div>
			<div class="right-con">
				<div class="panel panel-blue day-statistics ">
					<div class="panel-heading">
						<h3 class="panel-title">编辑场地</h3>
					</div>
					<div class="panel-body  event-details">
						<div class="clearfix addbutton-box">
								<a class="pull-right add-button" href="/venue/venue/add_venues">添加新场馆</a>
						</div>
						<div class="padd80">
							<div class="row field-list-root">
								<div class="col-sm-4 field-list-box">
									<div class="field-list-item">
										<div class="head exceed-omitted">
											北京朝阳体育馆
										</div>
										<div class="main clearfix">
											<div class="the-shelves"><span class="icons sales-status"></span>上架中</div>
											<div>
												<div class="dt">场馆地址：</div>
												<div class="dd exceed-omitted2">北京市朝阳区广河南里二条黄金卡</div>
											</div>
											<div class="underline ">
												<div class="dt">综合场馆：</div>
												<div class="dd">羽毛球、篮球、乒乓球</div>
											</div>

											<div>开放时间</div>
											<div>
												<div class="dt">工作日：</div>
												<div class="dd">9:00-22:00</div>
											</div>
											<div>
												<div class="dt">周   末：  </div>
												<div class="dd">9:00-22:00</div>
											</div>
											<div>
												<div class="dt">周   末： </div>
												<div class="dd">9:00-22:00</div>
											</div>
											<div class="underline ">
												<div class="dt">卖票数量： </div>
												<div class="dd">100张</div>
											</div>
											<div class="underline "><a href="">查看预订状态</a></div>
											<div class="underline "><a href="/venue/venue/site_management">场地管理</a></div>
											<div class="underline "><a href="/venue/venue/venue_ticket">场馆票管理</a></div>
											<div class="btn-group clearfix pull-right">
												<a href="/venue/venue/edit_venues" class="huobtn btn-blue pull-left mrl20">编辑</a>
												<a href="" class="btn-edit huobtn js-Status Sale pull-left mrl20">下架</a>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-4 field-list-box off-the-shelf">
									<div class="field-list-item">
										<div class="head exceed-omitted">
											北京朝阳体育馆
										</div>
										<div class="main clearfix">
											<div class="the-shelves"><span class="icons sales-status"></span>上架中</div>
											<div>
												<div class="dt">场馆地址：</div>
												<div class="dd exceed-omitted2">北京市朝阳区广河南里二条黄金卡</div>
											</div>
											<div class="underline ">
												<div class="dt">综合场馆：</div>
												<div class="dd">羽毛球、篮球、乒乓球</div>
											</div>

											<div>开放时间</div>
											<div>
												<div class="dt">工作日：</div>
												<div class="dd">9:00-22:00</div>
											</div>
											<div>
												<div class="dt">周   末：  </div>
												<div class="dd">9:00-22:00</div>
											</div>
											<div>
												<div class="dt">周   末： </div>
												<div class="dd">9:00-22:00</div>
											</div>
											<div class="underline ">
												<div class="dt">卖票数量： </div>
												<div class="dd">100张</div>
											</div>
											<div class="underline "><a href="">查看预订状态</a></div>
											<div class="underline "><a href="/venue/venue/site_management">场地管理</a></div>
											<div class="underline "><a href="/venue/venue/venue_ticket">场馆票管理</a></div>
											<div class="btn-group clearfix pull-right">
												<a href="/venue/venue/edit_venues" class="huobtn btn-blue pull-left mrl20">编辑</a>
												<a href="javascript:;" class="btn-edit huobtn js_statusSale pull-left mrl20">下架</a>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-4 field-list-box">
									<div class="field-list-item">
										<div class="head exceed-omitted">
											北京朝阳体育馆
										</div>
										<div class="main clearfix">
											<div class="the-shelves"><span class="icons sales-status"></span>上架中</div>
											<div>
												<div class="dt">场馆地址：</div>
												<div class="dd exceed-omitted2">北京市朝阳区广河南里二条黄金卡</div>
											</div>
											<div class="underline ">
												<div class="dt">综合场馆：</div>
												<div class="dd">羽毛球、篮球、乒乓球</div>
											</div>

											<div>开放时间</div>
											<div>
												<div class="dt">工作日：</div>
												<div class="dd">9:00-22:00</div>
											</div>
											<div>
												<div class="dt">周   末：  </div>
												<div class="dd">9:00-22:00</div>
											</div>
											<div>
												<div class="dt">周   末： </div>
												<div class="dd">9:00-22:00</div>
											</div>
											<div class="underline ">
												<div class="dt">卖票数量： </div>
												<div class="dd">100张</div>
											</div>
											<div class="underline "><a href="">查看预订状态</a></div>
											<div class="underline "><a href="/venue/venue/site_management">场地管理</a></div>
											<div class="underline "><a href="/venue/venue/venue_ticket">场馆票管理</a></div>
											<div class="btn-group clearfix pull-right">
												<a href="/venue/venue/edit_venues" class="huobtn btn-blue pull-left mrl20">编辑</a>
												<a href="" class="btn-edit huobtn js-Status Sale pull-left mrl20">下架</a>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-4 field-list-box off-the-shelf">
									<div class="field-list-item">
										<div class="head exceed-omitted">
											北京朝阳体育馆
										</div>
										<div class="main clearfix">
											<div><span class="icons sales-status"></span>下架</div>
											<div>
												<div class="dt">场馆地址：</div>
												<div class="dd exceed-omitted2">北京市朝阳区广河南里二条黄金卡</div>
											</div>
											<div class="underline ">
												<div class="dt">综合场馆：</div>
												<div class="dd">羽毛球、篮球、乒乓球</div>
											</div>

											<div>开放时间</div>
											<div>
												<div class="dt">工作日：</div>
												<div class="dd">9:00-22:00</div>
											</div>
											<div>
												<div class="dt">周   末：  </div>
												<div class="dd">9:00-22:00</div>
											</div>
											<div>
												<div class="dt">周   末： </div>
												<div class="dd">9:00-22:00</div>
											</div>
											<div class="underline ">
												<div class="dt">卖票数量： </div>
												<div class="dd">100张</div>
											</div>
											<div class="underline "><a href="">查看预订状态</a></div>
											<div class="underline "><a href="/venue/venue/site_management">场地管理</a></div>
											<div class="underline "><a href="/venue/venue/venue_ticket">场馆票管理</a></div>
											<div class="btn-group clearfix pull-right">
												<a href="/venue/venue/edit_venues" class="huobtn btn-blue pull-left mrl20">编辑</a>
												<a href="javascript:;" class="btn-edit huobtn js_statusSale pull-left mrl20" data-venue-id="{}">下架</a>
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

<script>
	$(function(){
		$(document).on('click',".js_statusSale",function(){
			var _that=$(this);
			var id=_that.attr('data-venue-id');
			var params={
				'id':id
			}
			statusSaleVenue(params).done(function(rs) {
			    
			})
		});
	})
</script>
{include file='venue/_foot.tpl'}



