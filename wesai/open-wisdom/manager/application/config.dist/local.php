<?php
################################################################
# 框架目录
################################################################
define('SYSTEM_PATH', '/home/local/code/open-base/ci/system');
define('APP_PATH', '/home/local/code/open-wisdom/manager/application');
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
define('API_HOST_OPEN_WEIXIN', 'api_host_open_weixin');
define('API_HOST_OPEN_WEIXIN_PROVIDER', 'api_host_open_weixin_provider');
define('API_HOST_OPEN_VENUE', 'api_host_open_venue');
define('API_HOST_OPEN_COMMON', 'api_host_open_common');
define('API_HOST_OPEN_BOOK', 'api_host_open_book');
define('API_HOST_OPEN_OPERATION', 'api_host_open_operation');
define('API_HOST_OPEN_PAY', 'api_host_open_pay');
define('API_HOST_OPEN_API', 'api_host_open_api');

$API_HOST_CONFIG[API_HOST_OPEN_CONTEST]['contest.api.local.wechatsport.cn'][]    = '10.2.2.5';
$API_HOST_CONFIG[API_HOST_OPEN_USER]['user.api.local.wechatsport.cn'][]          = '10.2.2.5';
$API_HOST_CONFIG[API_HOST_OPEN_RES]['res.api.wesai.com'][]                       = '10.2.3.46';
$API_HOST_CONFIG[API_HOST_OPEN_WEIXIN]['weixin.api.local.wechatsport.cn'][]      = '10.2.2.5';
$API_HOST_CONFIG[API_HOST_OPEN_WEIXIN_PROVIDER]['weixin.api.wechatsport.cn'][]   = '10.2.8.123';
$API_HOST_CONFIG[API_HOST_OPEN_VENUE]['venue.api.like.wechatsport.cn'][]         = '10.2.2.5';
$API_HOST_CONFIG[API_HOST_OPEN_COMMON]['common.api.like.wechatsport.cn'][]       = '10.2.2.5';
$API_HOST_CONFIG[API_HOST_OPEN_BOOK]['book.api.like.wechatsport.cn'][]           = '10.2.2.5';
$API_HOST_CONFIG[API_HOST_OPEN_OPERATION]['operation.api.like.wechatsport.cn'][]           = '10.2.2.5';
$API_HOST_CONFIG[API_HOST_OPEN_PAY]['pay.api.like.wechatsport.cn'][]           = '10.2.2.5';
$API_HOST_CONFIG[FRONT_HOST_OPEN_API]['softykt.api.local.wechatsport.cn'][]      = '10.2.2.5';
$API_HOST_CONFIG[API_HOST_OPEN_API]['api.api.local.wechatsport.cn'][]      = '10.2.2.5';



################################################################
# SESSION
################################################################
define('SESSION_DRIVER', 'redis');
define('SESSION_SAVE_PATH', 'tcp://10.2.2.5:6379?auth=hiwesai');

################################################################
# STATIC
################################################################
///静态资源域名
define('_STATIC_RES_CDN_DOMAIN_', 'static.local.wechatsport.cn');
define('_UPLOAD_RES_CDN_DOMAIN_', 'img.wesai.com');
define('RELEASE_VERSION', time());
// smarty debug
define('SMARTY_DEBUG', false);


################################################################
# 其他
################################################################
define('BASE_DOMAIN', 'manager.local.wechatsport.cn');

// 智慧体育开放平台(预)
define('WEIXIN_OPEN_APPID', 'wxebb324c7958b6170');
define('WEIXIN_OPEN_APPSECRET', '96199adac0b238b302f58943e484fa46');
define('WEIXIN_OPEN_TOKEN', '45da41760c1b6b0f0ed372974d053184');
define('WEIXIN_OPEN_KEY', '571316938d9cd1ccbe8d0a099da4bf6130302d910d0');

// 登录授权地址
define('QYWX_LOGIN_AUTH_URL', 'http://dispatch.wechatsport.cn/proxy/qywx/user_login');

// weixin debug
define('WEIXIN_JSAPI_DEBUG', false);

// 报名域名
define('CONTEST_DOMAIN_SUF', '.contest.local.wechatsport.cn');
// 票务域名
define('TICKET_DOMAIN_SUF', '.ticket.local.wechatsport.cn');
// 场馆预定
define('BOOK_DOMAIN_SUF', '.venue.local.wechatsport.cn');

// menu菜单支持的type
$MENU_API_HANDLES = array(
    'click',
    'view',
    'scancode_push',
    'scancode_waitmsg',
    'pic_sysphoto',
    'pic_photo_or_album',
    'pic_weixin',
    'location_select',
    'media_id',
    'view_limited'
);

// 接入金飞鹰的企业IDS
$SOFTYKT_CORPS = array(6);
