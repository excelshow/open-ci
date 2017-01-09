{include file='../contest/header.tpl'}
<div class="mask-bg"></div>
{include file='../contest/_jsconfig.tpl'}
<script type="text/javascript">
	var itn = setInterval(function () {
		$.ajax({
			       url   : 'ajax_get_order_by_id?oid=' + {$oid},
			       method: 'GET'
		       })
		 .done(function (msg) {
			 msg = eval('(' + msg + ')');
			 if (msg.error != 0) {
				 alert('error');
				 return;
			 }
			 if (msg.orderState != {$smarty.const.ORDER_STATE_INIT} && msg.orderState != {$smarty.const.ORDER_STATE_PAYING}) {
				 location.href = '/contest/orderdetail?oid={$oid}';
			 }
		 });
	}, 2000);

	$("body").css("background-color", "#f2f2f2");
</script>
<!-- end -->
<div class="act-pannel act-pannel-disabled" id="yetpay">处理中请稍后...</div>
{include file='../contest/footer.tpl'}
