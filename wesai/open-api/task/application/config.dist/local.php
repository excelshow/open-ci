<?php
################################################################
# 框架目录
################################################################
define('SYSTEM_PATH', '/home/local/code/open-base/ci/system');
define('APP_PATH', '/home/local/code/open-api/task/application');
define('WORKSPACE', dirname(dirname(APP_PATH)));
date_default_timezone_set("Asia/Shanghai");

################################################################
# CI ENV
################################################################
$_SERVER['CI_ENV'] = 'development';

################################################################
# API
################################################################
define('API_HOST_OPEN_API', 'api_host_open_api');
define('API_HOST_OPEN_CONTEST', 'api_host_open_contest');
$API_HOST_CONFIG[API_HOST_OPEN_API]['api.api.local.wechatsport.cn'][]      = '10.2.2.5';
$API_HOST_CONFIG[API_HOST_OPEN_CONTEST]['contest.api.local.wechatsport.cn'][]      = '10.2.2.5';

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
