<?php
################################################################
# 框架目录
################################################################
define('SYSTEM_PATH', '/home/local/code/open-base/ci/system');
define('APP_PATH', '/home/local/code/open-operation/front/application');
define('WORKSPACE', dirname(dirname(APP_PATH)));
date_default_timezone_set("Asia/Shanghai");

################################################################
# CI ENV
################################################################
$_SERVER['CI_ENV'] = 'development';

# API
################################################################
define('API_HOST_OPEN_WEIXIN', 'api_host_open_weixin');
define('API_HOST_OPEN_USER', 'api_host_open_user');
define('API_HOST_OPEN_RES', 'api_host_open_res');
define('API_HOST_OPEN_OPERATION', 'api_host_open_operation');

$API_HOST_CONFIG[API_HOST_OPEN_WEIXIN]['weixin.api.local.wechatsport.cn'][]		  = '10.2.2.5';
$API_HOST_CONFIG[API_HOST_OPEN_USER]['user.api.local.wechatsport.cn'][]			  = '10.2.2.5';
$API_HOST_CONFIG[API_HOST_OPEN_RES]['res.api.wesai.com'][]						  = '10.2.3.46';
$API_HOST_CONFIG[API_HOST_OPEN_OPERATION]['operation.api.local.wechatsport.cn'][] = '10.2.2.5';

################################################################
# SESSION
################################################################
define('SESSION_DRIVER', 'redis');
define('SESSION_SAVE_PATH', 'tcp://10.2.2.5:6379?auth=hiwesai');

################################################################
# STATIC
################################################################
define('_STATIC_RES_CDN_DOMAIN_', 'static.local.wechatsport.cn');
define('_UPLOAD_RES_CDN_DOMAIN_', 'img.wesai.com');
// 静态文件版本
define('RELEASE_VERSION', time());
// smarty debug
define('SMARTY_DEBUG', false);

################################################################
# 微信相关,可变内容
################################################################
// 智慧体育开放平台(预),从微信开放平台获取
define('COMPONENT_APPID', 'wxebb324c7958b6170');
// 微信前端跳转代理
define('WEIXIN_REDIRECT_PROXY_URL', 'http://proxy.local.wechatsport.cn/proxy?url=');
// weixin debug
define('WEIXIN_JSAPI_DEBUG', false);


################################################################
# 其他内部系统
################################################################
// 用户系统
define('USER_DOMAIN_SUF', '.user.local.wechatsport.cn');
// 报名域名
define('CONTEST_DOMAIN_SUF', '.contest.local.wechatsport.cn');
// 票务域名
define('TICKET_DOMAIN_SUF', '.contest.local.wechatsport.cn');
// 场馆预定
define('VENUE_DOMAIN_SUF', '.venue.local.wechatsport.cn');
// 优惠劵
define('OPERATION_DOMAIN_SUF', '.operation.local.wechatsport.cn');
