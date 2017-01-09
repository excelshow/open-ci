<?php
################################################################
# 框架目录
################################################################
define('SYSTEM_PATH', '/home/local/code/open-base/ci/system/');
define('APP_PATH', "/home/local/code/open-contest/api/application");
define('WORKSPACE', dirname(dirname(APP_PATH)));
date_default_timezone_set("Asia/Shanghai");

################################################################
# CI ENV
################################################################
$_SERVER['CI_ENV'] = 'development';

################################################################
# DB CONFIG
################################################################
define('CONTEST_DB_CONFIG', 'contestdb');
$DB_CONFIG[CONTEST_DB_CONFIG]['master']     = array(
    'dsn'  => 'mysql:host=10.2.2.5;dbname=open_contest',
    'user' => 'root',
    'pwd'  => 'root',
);
$DB_CONFIG[CONTEST_DB_CONFIG]['slaves'][]   = array(
    'dsn'  => 'mysql:host=10.2.2.5;dbname=open_contest',
    'user' => 'root',
    'pwd'  => 'root',
);
$DB_CONFIG[CONTEST_DB_CONFIG]['persistent'] = false; // 是否启用 PDO 长连接
$DB_CONFIG[CONTEST_DB_CONFIG]['timeout']    = 1; // 数据库操作超时时间，单位（秒）
$DB_CONFIG[CONTEST_DB_CONFIG]['character']  = 'utf8'; // 连接字符集

define('BATCH_DB_CONFIG', 'batchdb');
$DB_CONFIG[BATCH_DB_CONFIG]['master']     = array(
    'dsn'  => 'mysql:host=10.2.2.5;dbname=open_task',
    'user' => 'root',
    'pwd'  => 'root',
);
$DB_CONFIG[BATCH_DB_CONFIG]['slaves'][]   = array(
    'dsn'  => 'mysql:host=10.2.2.5;dbname=open_task',
    'user' => 'root',
    'pwd'  => 'root',
);
$DB_CONFIG[BATCH_DB_CONFIG]['persistent'] = false; // 是否启用 PDO 长连接
$DB_CONFIG[BATCH_DB_CONFIG]['timeout']    = 1; // 数据库操作超时时间，单位（秒）
$DB_CONFIG[BATCH_DB_CONFIG]['character']  = 'utf8'; // 连接字符集
################################################################
# REDIS CONFIG
################################################################
$REDIS_HOST_CONFIG['redis'] = array(
    'host'       => '10.2.2.5',
    'port'       => 6379,
    'password'   => 'hiwesai',
    'persistent' => false,
    'timeout'    => 1,
    'key_suffix' => '',
);

################################################################
# SPHINX CONFIG
################################################################

define('SPHINX_INDEX_CONTEST', 'ContestIndex');
define('SPHINX_INDEX_ORDER', 'OrderIndex');

// sphinx config
$SPHINX_HOST_CONFIG['sphinx'] = array(
    SPHINX_INDEX_CONTEST => array(
        'host'        => '127.0.0.1',
        'port'        => 9312,
        'timeout'     => 10,
        'index'       => SPHINX_INDEX_CONTEST,
        'max_matched' => 500,
    ),
    SPHINX_INDEX_ORDER   => array(
        'host'        => '127.0.0.1',
        'port'        => 9312,
        'timeout'     => 10,
        'index'       => SPHINX_INDEX_ORDER,
        'max_matched' => 500,
    ),
);
