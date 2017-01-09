<div class="sidebar">
	<ul class="nav nav-sidebar">
		<li class="nav-tit expansion-nav" role="">
			<a href="/venue/home/index">
				<span class="icon home-icon"> 应用首页</span>
			</a>
		</li>
		<li class="nav-tit" role="venue">
			<a href="javascript:;"><span class="icon edit-icon">场馆预订</span>
				<span class="chevron-icon icon"></span>
			</a>
			<ul class="nav signup_nav">
				<li role="/venue/analysis">
					<a href="/venue/analysis/index">统计</a>
				</li>
				<li role="/venue/venue" activityManagement="/venue/formorder">
					<a href="/venue/venue/get_list">场馆管理</a>
				</li>
				<li role="/order/order_list">
					<a href="/venue/order/order_list">订单管理</a>
				</li>
				<li role="/venue/view_venue_status">
					<a href="/venue/venue/view_venue_status">查看预订状态</a>
				</li>
				<li role="/venue/verifypc">
					<a href="/venue/verifypc/index">核销</a>
				</li>
			</ul>
		</li>
	</ul>
</div>
<script>
	$(function () {
		//左侧栏元素
		$(".signup_nav li").each(function () {
			judgmentNav($(this));
		});
		function judgmentNav(that) {
			var act_menu           = that.attr("role");
			var activityManagement = that.attr("activityManagement");
			if (menu_action.indexOf(act_menu) !== -1) {
				that.addClass("expansion-nav").siblings().removeClass("expansion-nav");
				$('.nav-tit').removeClass('expansion-nav');
				that.parent().parent('.nav-tit').addClass('expansion-nav');
			} else if (menu_action.indexOf(activityManagement) !== -1) {
				that.addClass("expansion-nav").siblings().removeClass("expansion-nav");
			}
		}
		$('.nav-sidebar').on('click', '.nav-tit', function () {
			var that = $(this);
			if (that.hasClass('expansion-nav')) {
				that.removeClass('expansion-nav');
			} else {
				that.addClass('expansion-nav');
				that.siblings().removeClass('expansion-nav');
			}
		});
	});
</script>
