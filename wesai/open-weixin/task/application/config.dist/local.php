<?php
################################################################
# 框架目录
################################################################
define('SYSTEM_PATH', '/home/local/code/open-base/ci/system');
define('APP_PATH', '/home/local/code/open-weixin/task/application');
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
define('API_HOST_OPEN_WEIXIN_PROVIDER', 'api_host_open_weixin_provider');
define('API_HOST_OPEN_USER', 'api_host_open_user');
define('API_HOST_WESAI_B2C', 'api_host_wesai_b2c');
$API_HOST_CONFIG[API_HOST_OPEN_WEIXIN]['weixin.api.local.wechatsport.cn'][]    = '10.2.2.5';
$API_HOST_CONFIG[API_HOST_OPEN_WEIXIN_PROVIDER]['weixin.api.wechatsport.cn'][] = '10.2.8.123';
$API_HOST_CONFIG[API_HOST_OPEN_USER]['user.api.local.wechatsport.cn'][]        = '10.2.2.5';
$API_HOST_CONFIG[API_HOST_WESAI_B2C]['card.test.wesai.com'][]                  = 'card.test.wesai.com';

################################################################
# REDIS
################################################################
define('OPEN_CACHE_KEY_PRE', 'weixin_cache_');
$REDIS_CACHE_HOST_CONFIG['socket_type'] = 'tcp'; //`tcp` or `unix`
$REDIS_CACHE_HOST_CONFIG['socket']      = '/var/run/redis.sock'; // in case of `unix` socket type
$REDIS_CACHE_HOST_CONFIG['host']        = '10.2.2.5';
$REDIS_CACHE_HOST_CONFIG['password']    = 'hiwesai';
$REDIS_CACHE_HOST_CONFIG['port']        = 6379;
$REDIS_CACHE_HOST_CONFIG['timeout']     = 0;

// key_suffix 会自动加到topic里面
define('OPEN_MQ_KEY_PRE', 'weixin_mq_');
$REDIS_HOST_CONFIG['redismq']['host']       = '10.2.2.5';
$REDIS_HOST_CONFIG['redismq']['port']       = 6379;
$REDIS_HOST_CONFIG['redismq']['password']   = 'hiwesai';
$REDIS_HOST_CONFIG['redismq']['persistent'] = false;
$REDIS_HOST_CONFIG['redismq']['timeout']    = 1;
$REDIS_HOST_CONFIG['redismq']['key_suffix'] = OPEN_MQ_KEY_PRE;


################################################################
# 其他
################################################################
// 智慧体育开放平台(预)
define('COMPONENT_APPID', 'wxebb324c7958b6170');

// 公众号配置
$OPEN_WEIXIN_CONFIG['weixin_apps']['wx65b4fc50dcf3f215'] = array(
	'name'      => '测试号',
	'appsecret' => '4fb1be5bfc4b8e5bb24946b7eedce803',
);

// 智慧体育开放平台(devel)
$OPEN_WEIXIN_CONFIG['weixin_components'][COMPONENT_APPID] = array(
	'name'      => '智慧体育开放平台(预)',
	'appsecret' => '96199adac0b238b302f58943e484fa46',
	'token'     => '45da41760c1b6b0f0ed372974d053184',
	'aeskey'    => '571316938d9cd1ccbe8d0a099da4bf6130302d910d0',
);
