<?php
################################################################
# 框架目录
################################################################
define('SYSTEM_PATH', '/home/local/code/open-base/ci/system');
define('APP_PATH', '/home/local/code/open-contest/task/application');
define('WORKSPACE', dirname(dirname(APP_PATH)));
date_default_timezone_set("Asia/Shanghai");

################################################################
# CI ENV
################################################################
$_SERVER['CI_ENV'] = 'development';

################################################################
# API
################################################################
define('API_HOST_OPEN_CONTEST', 'api_host_open_contest');
define('API_HOST_OPEN_USER', 'api_host_open_user');
define('API_HOST_OPEN_RES', 'api_host_open_res');
define('API_HOST_OPEN_SMS', 'api_host_open_sms');
define('API_HOST_OPEN_WEIXIN', 'api_host_open_weixin');
define('API_HOST_OPEN_PAY', 'api_host_open_pay');
define('API_HOST_OPEN_DIST', 'api_host_open_dist');
$API_HOST_CONFIG[API_HOST_OPEN_CONTEST]['contest.api.zhaodc.wechatsport.cn'][] = '10.2.2.109';
$API_HOST_CONFIG[API_HOST_OPEN_USER]['user.api.zhaodc.wechatsport.cn'][]       = '10.2.2.109';
$API_HOST_CONFIG[API_HOST_OPEN_RES]['res.api.wesai.com'][]                     = '10.2.3.46';
$API_HOST_CONFIG[API_HOST_OPEN_SMS]['sms.api.zhaodc.wechatsport.cn'][]         = '10.2.2.109';
$API_HOST_CONFIG[API_HOST_OPEN_WEIXIN]['weixin.api.zhaodc.wechatsport.cn'][]   = '10.2.2.109';
$API_HOST_CONFIG[API_HOST_OPEN_PAY]['pay.api.zhaodc.wechatsport.cn'][]         = '10.2.2.109';
$API_HOST_CONFIG[API_HOST_OPEN_DIST]['dist.api.like.wechatsport.cn'][]         = '10.2.2.5';
define('API_HOST_OPEN_API', 'api_host_open_api');
$API_HOST_CONFIG[API_HOST_OPEN_API]['api.api.local.wechatsport.cn'][] = '10.2.2.5';

################################################################
# REDIS
################################################################
$REDIS_HOST_CONFIG['redis'] = array(
	'host'       => '10.2.2.5',
	'port'       => 6379,
	'password'   => 'hiwesai',
	'persistent' => false,
	'timeout'    => 1,
	'key_suffix' => '',
);

