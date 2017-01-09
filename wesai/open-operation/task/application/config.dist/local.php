<?php
################################################################
# 框架目录
################################################################
define('SYSTEM_PATH', '/home/local/code/open-base/ci/system');
define('APP_PATH', '/home/local/code/open-operation/task/application');
define('WORKSPACE', dirname(dirname(APP_PATH)));
date_default_timezone_set("Asia/Shanghai");

################################################################
# CI ENV
################################################################
$_SERVER['CI_ENV'] = 'development';

################################################################
# API
################################################################
define('API_HOST_OPEN_OPERATION', 'api_host_open_operation');
define('API_HOST_OPEN_WEIXIN', 'api_host_open_weixin');
$API_HOST_CONFIG[API_HOST_OPEN_OPERATION]['operation.api.local.wechatsport.cn'][]      = '10.2.2.5';
$API_HOST_CONFIG[API_HOST_OPEN_WEIXIN]['weixin.api.local.wechatsport.cn'][]      = '10.2.2.5';

################################################################
# REDIS
################################################################
define('OPERATION_MQ_KEY_PRE', 'operation_mq_');
$REDIS_HOST_CONFIG['redismq']['host']       = '10.2.2.5';
$REDIS_HOST_CONFIG['redismq']['port']       = 6379;
$REDIS_HOST_CONFIG['redismq']['password']   = 'hiwesai';
$REDIS_HOST_CONFIG['redismq']['persistent'] = false;
$REDIS_HOST_CONFIG['redismq']['timeout']    = 1;
$REDIS_HOST_CONFIG['redismq']['key_suffix'] = OPERATION_MQ_KEY_PRE;

################################################################
# 其他
################################################################
// 智慧体育开放平台(预)
define('COMPONENT_APPID', 'wxebb324c7958b6170');

// 营销活动域名
define('OPERATION_DOMAIN_SUF', '.operation.local.wechatsport.cn');

// 静态域名
define('_UPLOAD_RES_CDN_DOMAIN_', 'img.wesai.com');

