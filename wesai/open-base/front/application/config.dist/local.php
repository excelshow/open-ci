<?php
################################################################
# 框架目录
################################################################
define('SYSTEM_PATH', '/path/to/ci/system');
define('APP_PATH', '/path/to/front/application');
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
