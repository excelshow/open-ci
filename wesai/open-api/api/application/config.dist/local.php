<?php
################################################################
# 框架目录
################################################################
define('SYSTEM_PATH', '/home/local/code/open-base/ci/system');
define('APP_PATH', '/home/local/code/open-api/api/application');
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


$API_HOST_CONFIG[API_HOST_OPEN_CONTEST]['contest.api.local.wechatsport.cn'][] = '10.2.2.5';
$API_HOST_CONFIG[API_HOST_OPEN_USER]['user.api.local.wechatsport.cn'][] = '10.2.2.5';


################################################################
# DB
################################################################
define('OPEN_API_DB_CONFIG', 'open_api');
// 自定义的数据库配置
$DB_CONFIG[OPEN_API_DB_CONFIG]['master']     = array(
	'dsn'  => 'mysql:host=10.2.2.5;dbname=open_api',
	'user' => 'root',
	'pwd'  => 'root',
);
$DB_CONFIG[OPEN_API_DB_CONFIG]['slaves'][]   = array(
	'dsn'  => 'mysql:host=10.2.2.5;dbname=open_api',
	'user' => 'root',
	'pwd'  => 'root',
);
$DB_CONFIG[OPEN_API_DB_CONFIG]['persistent'] = false;    // 是否启用 PDO 长连接
$DB_CONFIG[OPEN_API_DB_CONFIG]['timeout']    = 1;        // 数据库操作超时时间，单位（秒）
$DB_CONFIG[OPEN_API_DB_CONFIG]['character']  = 'utf8';   // 连接字符集

