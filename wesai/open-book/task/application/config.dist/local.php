<?php
################################################################
# 框架目录
################################################################
define('SYSTEM_PATH', '/home/local/code/open-base/ci/system');
define('APP_PATH', '/home/local/code/open-book/task/application');
define('WORKSPACE', dirname(dirname(APP_PATH)));
date_default_timezone_set("Asia/Shanghai");

################################################################
# CI ENV
################################################################
$_SERVER['CI_ENV'] = 'development';

################################################################
# API
################################################################
define('API_HOST_OPEN_WEIXIN', 'api_host_open_weixin');
define('API_HOST_OPEN_CONTEST', 'api_host_open_contest');
define('API_HOST_OPEN_USER', 'api_host_open_user');
define('API_HOST_OPEN_BOOK', 'api_host_open_book');
define('API_HOST_OPEN_PAY', 'api_host_open_pay');
define('API_HOST_OPEN_SMS', 'api_host_open_sms');
define('API_HOST_OPEN_RES', 'api_host_open_res');
define('API_HOST_OPEN_VENUE', 'api_host_open_venue');
$API_HOST_CONFIG[API_HOST_OPEN_WEIXIN]['weixin.api.local.wechatsport.cn'][]      = '10.2.2.5';
$API_HOST_CONFIG[API_HOST_OPEN_CONTEST]['contest.api.local.wechatsport.cn'][]    = '10.2.2.5';
$API_HOST_CONFIG[API_HOST_OPEN_USER]['user.api.local.wechatsport.cn'][]          = '10.2.2.5';
$API_HOST_CONFIG[API_HOST_OPEN_BOOK]['book.api.local.wechatsport.cn'][]          = '10.2.2.5';
$API_HOST_CONFIG[API_HOST_OPEN_PAY]['pay.api.local.wechatsport.cn'][]            = '10.2.2.5';
$API_HOST_CONFIG[API_HOST_OPEN_SMS]['sms.api.local.wechatsport.cn'][]            = '10.2.2.5';
$API_HOST_CONFIG[API_HOST_OPEN_VENUE]['venue.api.local.wechatsport.cn'][]        = '10.2.2.5';
$API_HOST_CONFIG[API_HOST_OPEN_RES]['res.api.wesai.com'][]                       = '10.2.3.46';

$REDIS_HOST_CONFIG['redis'] = array(
	'host'       => '10.2.2.5',
	'port'       => 6379,
	'password'   => 'hiwesai',
	'persistent' => false,
	'timeout'    => 1,
	'key_suffix' => '',
);

