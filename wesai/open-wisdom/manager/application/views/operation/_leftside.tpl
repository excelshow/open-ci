<div class="sidebar">
    <ul class="nav nav-sidebar">
        <li class="nav-tit expansion-nav" role="">
            <a href="/operation/market/activity_list">
                <span class="icon market-icon">营销活动</span>
            </a>
        </li>
        <li class="nav-tit" role="contest">
            <a href="javascript:;"><span class="icon coupon-icon">优惠券</span>
				<span class="chevron-icon icon"></span>
			</a>
            <ul class="nav signup_nav">
                <li role="/operation/coupon/rule_list">
                    <a href="/operation/coupon/rule_list">优惠券规则查询</a>
                </li>
                <li role="/operation/coupon/add_coupon" activityManagement="/contest/formorder">
                    <a href="/operation/coupon/add_coupon">设置优惠券规则</a>
                </li>
                <li role="/operation/coupon/query_list">
                    <a href="/operation/coupon/query_list">查询卡密</a>
                </li>
            </ul>
        </li>
    </ul>
</div>
<script>
$(function() {
    //左侧栏元素
    $(".signup_nav li").each(function() {
        judgmentNav($(this));
    });

    function judgmentNav(that) {
        var act_menu = that.attr("role");
        var activityManagement = that.attr("activityManagement");
        if (menu_action.indexOf(act_menu) !== -1) {
            that.addClass("expansion-nav").siblings().removeClass("expansion-nav");
            $('.nav-tit').removeClass('expansion-nav');
            that.parent().parent('.nav-tit').addClass('expansion-nav');
        } else if (menu_action.indexOf(activityManagement) !== -1) {
            that.addClass("expansion-nav").siblings().removeClass("expansion-nav");
        }
    }
    $('.nav-sidebar').on('click', '.nav-tit', function() {
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
