<!-- 必须js -->
<script src="{'js/jquery.min.js'|cdnurl}"></script>
<!-- wx -->
{if isset($jssdk_sdata)}
<script type="text/javascript">
var APPID = '{$jssdk_sdata.appid}';
var TIMESTAMP = '{$jssdk_sdata.timestamp}';
var NONCESTR = '{$jssdk_sdata.noncestr}';
var SIGNATURE = '{$jssdk_sdata.signature}';
</script>
<script src="{'js/jweixin-1.0.0.js'|cdnurl}"></script>
<script src="{'js/weixin.js'|cdnurl}"></script>
{/if}
