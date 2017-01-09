{include file='venue/_header.tpl'}
<div class="mask-bg"></div>
{include file='_jsconfig.tpl'}
<script type="text/javascript">
    var itn = setInterval(function () {
        $.ajax({
                   url   : 'get_info?order_id=' + {$oid},
                   method: 'GET'
               })
         .done(function (msg) {
             msg = eval('(' + msg + ')');
             if (msg.error != 0) {
                 alert('error');
                 return;
             }
             if (msg.orderState != {$smarty.const.ORDER_STATE_INIT} && msg.orderState != {$smarty.const.ORDER_STATE_PAYING}) {
                 location.href = '/order/detail?order_id={$oid}';
             }
         });
    }, 2000);

    $("body").css("background-color", "#f2f2f2");
</script>
<!-- end -->
<div class="act-pannel act-pannel-disabled" id="yetpay">处理中请稍后...</div>
{include file='venue/_foot.tpl'}
