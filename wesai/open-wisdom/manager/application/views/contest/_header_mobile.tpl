<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>核销助手</title>
	<meta name="viewport" content="initial-scale=1, maximum-scale=1">
	<link rel="shortcut icon" href="/favicon.ico">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">

	<script type="text/javascript" src="{'manager/lib/jweixin-1.1.0.js'|cdnurl}"></script>
	<script>
		wx.config({
          	debug    : false,
          	appId    : '{$jsApiConfig.appId}',
          	timestamp: '{$jsApiConfig.timestamp}',
          	nonceStr : '{$jsApiConfig.nonceStr}',
          	signature: '{$jsApiConfig.signature}',
          	jsApiList: ['scanQRCode']
      	});
		
	</script>


</head>
<body>