<div class="left-side">
	<div class="side-menu">
		<ul class="navbar">
			<li for="statistics">
				<h4>统计分析<span class="caret"></span></h4>
				<div class="sublist">
					<a href="/contest/analysis/index">订单统计</a>
				</div>
			</li>
			<li for="index">
				<h4>活动管理<span class="caret"></span></h4>
				<div class="sublist">
					<a href="/contest/contest/index">活动列表</a>
				</div>
			</li>
			<li for="list_order">
				<h4>订单管理<span class="caret"></span></h4>
				<div class="sublist">
					<a href="/contest/order/list_order">订单列表</a>
				</div>
			</li>
		</ul>
	</div>
</div>
<script>
	//显示菜单
	jQuery(".side-menu li").each(function () {
		var act_menu = $(this).attr("role");
		if (menu_action.indexOf(act_menu) > 0) {
			jQuery(this).addClass("curr").siblings().removeClass("curr");
		}
	});
	var login_user = userInfo;
	jQuery(".login_user").text(login_user.user_name);
</script>
