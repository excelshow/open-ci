<?php
################################################################
# 框架目录
################################################################
define('SYSTEM_PATH', '/home/local/code/open-base/ci/system/');
define('APP_PATH', "/home/local/code/open-api/front/application");
define('WORKSPACE', dirname(dirname(APP_PATH)));
date_default_timezone_set("Asia/Shanghai");

################################################################
# CI ENV
################################################################
$_SERVER['CI_ENV'] = 'development';

################################################################
# API
################################################################
define('API_HOST_OPEN_USER', 'api_host_open_user');
define('API_HOST_OPEN_API', 'api_host_open_api');
define('INTERNAL_API_SYSTEM_CONTEST_HOST_CONFIG', 'api_host_open_contest');
define('INTERNAL_API_SYSTEM_TICKET_HOST_CONFIG', 'api_host_open_ticket');
define('INTERNAL_API_SYSTEM_VENUE_HOST_CONFIG', 'api_host_open_venue');
$API_HOST_CONFIG[API_HOST_OPEN_USER]['user.api.local.wechatsport.cn'][]        = '10.2.2.5';
$API_HOST_CONFIG[API_HOST_OPEN_API]['api.api.local.wechatsport.cn'][]          = '10.2.2.5';
// api调用的内部服务地址 api配置依赖 constansts中的对应配置
$API_HOST_CONFIG[INTERNAL_API_SYSTEM_CONTEST_HOST_CONFIG]['contest.api.local.wechatsport.cn'][] = '10.2.2.5';
$API_HOST_CONFIG[INTERNAL_API_SYSTEM_TICKET_HOST_CONFIG]['ticket.api.local.wechatsport.cn'][] = '10.2.2.5';
// todo，这里要看怎么做
$API_HOST_CONFIG[INTERNAL_API_SYSTEM_VENUE_HOST_CONFIG]['book.api.local.wechatsport.cn'][] = '10.2.2.5';

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
# REDIS
################################################################
define('OPENAPI_MQ_KEY_PRE', 'openapi_mq_');
$REDIS_HOST_CONFIG['redismq']['host']       = '10.2.2.5';
$REDIS_HOST_CONFIG['redismq']['port']       = 6379;
$REDIS_HOST_CONFIG['redismq']['password']   = 'hiwesai';
$REDIS_HOST_CONFIG['redismq']['persistent'] = false;
$REDIS_HOST_CONFIG['redismq']['timeout']    = 1;
$REDIS_HOST_CONFIG['redismq']['key_suffix'] = OPENAPI_MQ_KEY_PRE;