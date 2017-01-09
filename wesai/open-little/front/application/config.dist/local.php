<?php
################################################################
# 框架目录
################################################################
define('SYSTEM_PATH', '/home/local/code/open-base/ci/system/');
define('APP_PATH', "/home/local/code/open-little/front/application");
define('WORKSPACE', dirname(dirname(APP_PATH)));
date_default_timezone_set("Asia/Shanghai");

################################################################
# CI ENV
################################################################
$_SERVER['CI_ENV'] = 'development';

################################################################
# SESSION
################################################################
define('SESSION_DRIVER', 'files');
define('SESSION_SAVE_PATH', null);

################################################################
# API
################################################################
define('API_HOST_OPEN_LITTLE', 'api_host_open_little');
define('API_HOST_OPEN_WESAI_ZHTY', 'api_host_open_wesai_zhty');
$API_HOST_CONFIG[API_HOST_OPEN_LITTLE]['little.api.local.wechatsport.cn'][]        = '10.2.2.5';
$API_HOST_CONFIG[API_HOST_OPEN_WESAI_ZHTY]['openapi.local.wechatsport.cn'][]        = '10.2.2.5';

################################################################
# REDIS
################################################################
define('LITTLE_MQ_KEY_PRE', 'little_mq_');
$REDIS_HOST_CONFIG['redismq']['host']       = '10.2.2.5';
$REDIS_HOST_CONFIG['redismq']['port']       = 6379;
$REDIS_HOST_CONFIG['redismq']['password']   = 'hiwesai';
$REDIS_HOST_CONFIG['redismq']['persistent'] = false;
$REDIS_HOST_CONFIG['redismq']['timeout']    = 1;
$REDIS_HOST_CONFIG['redismq']['key_suffix'] = LITTLE_MQ_KEY_PRE;
