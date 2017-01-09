{include file='../contest/header.tpl'}
<input type="button" value="支付" onclick="do_wx_pay()" />
<script type="text/javascript">
    var APPID     = '{$jssdk_sdata.appid}';
    var TIMESTAMP = '{$jssdk_sdata.timestamp}';
    var NONCESTR  = '{$jssdk_sdata.noncestr}';
    var SIGNATURE = '{$jssdk_sdata.signature}';
</script>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript" src="{'js/weixin.js'|cdnurl}"></script>
<script type="text/javascript" src="{'js/jquery.min.js'|cdnurl}"></script>
<script type="text/javascript">
    var paying = false;
    var itn = setInterval(function () {
        var oid = {$orderid};
        $.ajax({
            url: 'ajax_get_order_by_id?oid=' + oid,
            method: 'GET'
        })
        .done(function (msg) {
            msg = eval('(' + msg + ')');
            console.log(msg);

            if (msg.error != 0) {
                alert('error');
                return;
            }
            if (undefined != msg.prepay_data && '' != msg.prepay_data) {
                clearInterval(itn);
                do_wx_pay(msg.prepay_data);
                paying = true;
            }
        });

    }, 2000);

    function do_wx_pay(prepay_data) {
        if (paying) {
            return false;
        }
        wx.chooseWXPay({
            timestamp : prepay_data.timeStamp, // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
            nonceStr  : prepay_data.nonceStr, // 支付签名随机串，不长于 32 位
            package   : prepay_data.package, // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
            signType  : prepay_data.signType, // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
            paySign   : prepay_data.paySign, // 支付签名
            success: function (res) {
                // 支付成功后的回调函数
                console.log(res);
            }
        });

        paying = false;
    }
</script>
{include file='../contest/footer.tpl'}
