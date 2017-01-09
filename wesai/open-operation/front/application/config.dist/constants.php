<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. IVICE_OPEN_BOOK', '104');

// TOKEN配置
define('TOKEN_OPEN_BOOK', '123123');
|  is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESCTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

################################################################
# 微信相关,不经常变更配置
################################################################
//获取网页授权地址
define('WEIXIN_SNSAPI_AUTHORIZE_URL', 'https://open.weixin.qq.com/connect/oauth2/authorize');
// scope 类型
define('WEIXIN_SNSAPI_SCOPE_BASE', 'snsapi_base');
define('WEIXIN_SNSAPI_SCOPE_USERINFO', 'snsapi_userinfo');


//活动
define('OPERATION_ACTIVITY_STATE_STOP', '1'); //状态 暂存
define('OPERATION_ACTIVITY_STATE_START', '2'); //状态 上架
define('OPERATION_ACTIVITY_STATE_DOWN', '3'); //状态 下架
define('OPERATION_ACTIVITY_STATE_END', '4'); //状态 结束

$OPERATION_ACTIVITY_STATE_LIST = array(
	OPERATION_ACTIVITY_STATE_STOP  => '暂存',
	OPERATION_ACTIVITY_STATE_START => '上架',
	OPERATION_ACTIVITY_STATE_DOWN  => '活动已结束',
	OPERATION_ACTIVITY_STATE_END   => '活动已结束',
);

//状态
define('OPERATION_LIST_STATE_STOP', '1'); //状态 未使用
define('OPERATION_LIST_STATE_START', '2'); //状态 已使用 
define('OPERATION_LIST_STATE_DOWN', '3'); //状态 下架
define('OPERATION_LIST_STATE_END', '4'); //状态 已废弃
define('OPERATION_LIST_STATE_EXPIRED', '5'); //状态 过期

$OPERATION_LIST_STATE_LIST = array(
	OPERATION_LIST_STATE_STOP  => '立即使用',
	OPERATION_LIST_STATE_START => '已使用 ',
	OPERATION_LIST_STATE_DOWN  => '下架',
	OPERATION_LIST_STATE_END   => '已废弃',
	OPERATION_LIST_STATE_EXPIRED =>'已过期'
);
/*
* 金飞鹰配置
*/
define('SOF_SOFTYKTSECRECT', 'dLEWDrUcQeEoCqm7MPitiKXu66BapPOD');
define('SOF_APPID', 'D_78510694');
define('SOF_JURL', 'http://testapi.softykt.com');                               //域名
define('SOF_ACCESSTOKENURL', SOF_JURL . '/api1_0/api_get_token');                   //获取accesst_oken
define('SOF_DISTRIBUTORGETPRODUCTURL', SOF_JURL . '/api1_0/distributor_get_product');  //获取产品列表
