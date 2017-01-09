<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
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

##############################################################################
#                              业务相关     START                             #
##############################################################################

// 常量配置
// access token 超时时间
define('EXPIRES_IN', 86400); 

define('ERROR_PRE_COMMON', 0); 

/**
 * Api Params IO Type
 */
define('API_PARAMS_IO_TYPE_IN', 1);
define('API_PARAMS_IO_TYPE_OUT', 2);

// 报名
define('OPEN_API_SYSTEM_CONTEST', 1);
define('OPEN_API_SYSTEM_CONTEST_NAME', 'contest');
// 票务
define('OPEN_API_SYSTEM_TICKET', 2);
define('OPEN_API_SYSTEM_TICKET_NAME', 'ticket');
// 场馆
define('OPEN_API_SYSTEM_VENUE', 3);
define('OPEN_API_SYSTEM_VENUE_NAME', 'venue');

$OPEN_API_SYSTEM_NAME_LIST = array(
	OPEN_API_SYSTEM_CONTEST_NAME => OPEN_API_SYSTEM_CONTEST,
	OPEN_API_SYSTEM_TICKET_NAME => OPEN_API_SYSTEM_TICKET,
	OPEN_API_SYSTEM_VENUE_NAME => OPEN_API_SYSTEM_VENUE,
);

/**
 * Internal System
 */
// 报名
define('INTERNAL_API_SYSTEM_CONTEST', 1);
define('INTERNAL_API_SYSTEM_CONTEST_NAME', 'contest');
// 票务
define('INTERNAL_API_SYSTEM_TICKET', 2);
define('INTERNAL_API_SYSTEM_TICKET_NAME', 'ticket');
// 场馆
define('INTERNAL_API_SYSTEM_VENUE', 3);
define('INTERNAL_API_SYSTEM_VENUE_NAME', 'venue');

$INTERNAL_API_SYSTEM_LIST = array(
	INTERNAL_API_SYSTEM_CONTEST_NAME => INTERNAL_API_SYSTEM_CONTEST,
	INTERNAL_API_SYSTEM_TICKET_NAME => INTERNAL_API_SYSTEM_TICKET,
	INTERNAL_API_SYSTEM_VENUE_NAME => INTERNAL_API_SYSTEM_VENUE,
);

// host config 需配置到config中的host配置里面
$INTERNAL_API_SYSTEM_HOST_CONFIG_LIST = array(
	INTERNAL_API_SYSTEM_CONTEST => INTERNAL_API_SYSTEM_CONTEST_HOST_CONFIG,
	INTERNAL_API_SYSTEM_TICKET => INTERNAL_API_SYSTEM_TICKET_HOST_CONFIG,
	INTERNAL_API_SYSTEM_VENUE => INTERNAL_API_SYSTEM_VENUE_HOST_CONFIG,
);

/**
 * Api Method
 */
define('API_METHOD_GET', 1);
define('API_METHOD_GET_NAME', 'get');
define('API_METHOD_POST', 2);
define('API_METHOD_POST_NAME', 'post');

$OPEN_API_METHOD_LIST = array(
	API_METHOD_GET_NAME => API_METHOD_GET,
	API_METHOD_POST_NAME => API_METHOD_POST,
);

// api 状态
define('OPEN_API_STATE_OK', 1);
define('OPEN_API_STATE_NG', 2);

/**
 * 是否为空
 */
define('API_PARAMS_NULL_ALLOW', 1);
define('API_PARAMS_NULL_NOT', 2);

/**
 * 参数类型
 */
define('API_PARAMS_TYPE_NUMBER', 1);
define('API_PARAMS_TYPE_TEXT', 2);
define('API_PARAMS_TYPE_OBJECT', 3);
define('API_PARAMS_TYPE_ARRAY', 4);


//金飞鹰回调type 用于redis区分
define('SOF_API_CODE_SUCCESS', 666);  		//金飞鹰接口返回code 成功
define('SOF_TYPE_PRODUCT_SAVE', 1);  		//修改商品信息
define('SOF_TYPE_PRODUCT_STATE_SAVE', 2);  //修改商品状态
define('SOF_TYPE_PRODUCT_DELETE', 3);  	//商品删除
define('SOF_TYPE_ORDER_TEST', 4);  		//验票
define('SOF_TYPE_RETURN_TICKET', 5);  	//退票

//redis   金飞鹰回调结果队列key
define('MQ_TOPIC_SOFTYKT_EXT_CALLBACK', 'softykt_ext_callback');
