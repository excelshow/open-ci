<div class="sidebar">
	<ul class="nav nav-sidebar">
		<li class="nav-tit expansion-nav">
			<a href="/">
				<span class="icon home-icon"> 应用首页</span>
			</a>
		</li>
		<li class="nav-tit" role="contest">
			<a href="javascript:;"><span class="icon edit-icon">活动报名</span>
				<span class="chevron-icon icon"></span>
			</a>
			<ul class="nav signup_nav">
				<li role="/contest/analysis">
					<a href="/contest/analysis/index">统计</a>
				</li>
				<li role="/contest/contest" activityManagement="/contest/formorder">
					<a href="/contest/contest/index">活动管理</a>
				</li>
				<li role="/contest/order">
					<a href="/contest/order/list_order">订单管理</a>
				</li>
			</ul>
		</li>
	</ul>
</div>
<script>
	$(function () {
		//初始化左侧栏
		$(".nav-sidebar .nav-tit").each(function () {
			judgmentNav($(this));
		});

		//左侧栏元素
		$(".signup_nav li").each(function () {
			judgmentNav($(this));
		});

		function judgmentNav(that) {
			var act_menu           = that.attr("role");
			var activityManagement = that.attr("activityManagement");
			if (menu_action.indexOf(act_menu) !== -1) {
				that.addClass("expansion-nav").siblings().removeClass("expansion-nav");

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
