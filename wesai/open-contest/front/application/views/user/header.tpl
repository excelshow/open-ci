<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="email=no" />
    <title>{if isset($webtitle)}{$webtitle}{/if}</title>
    <link href="{'css/my.minred.css'|cdnurl}" rel="stylesheet">
    <script src="{'js/jquery.min.js'|cdnurl}"></script>
  </head>
<body>
<div class="loading-toast">
<div class="loadEffect"><span></span><p>请稍候</p></div>
</div>
<script type="text/javascript">
    var APPID     = '{$jssdk_sdata.appid}';
    var TIMESTAMP = '{$jssdk_sdata.timestamp}';
    var NONCESTR  = '{$jssdk_sdata.noncestr}';
    var SIGNATURE = '{$jssdk_sdata.signature}';
</script>
