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
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', true);

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
defined('FILE_READ_MODE') OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ') OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESCTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

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
defined('EXIT_SUCCESS') OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


##############################################################################
#                              业务相关     START                             #
##############################################################################

/**
 * OpenAPI System
 */

// 报名
define('OPEN_API_SYSTEM_CONTEST', 1);
// 票务
define('OPEN_API_SYSTEM_TICKET', 2);
// 场馆
define('OPEN_API_SYSTEM_VENUE', 3);

$OPEN_API_SYSTEM_LIST = array(
	OPEN_API_SYSTEM_CONTEST,
	OPEN_API_SYSTEM_TICKET,
	OPEN_API_SYSTEM_VENUE,
);

/**
 * Internal System
 */
// 报名
define('INTERNAL_API_SYSTEM_CONTEST', 1);
// 票务
define('INTERNAL_API_SYSTEM_TICKET', 2);
// 场馆
define('INTERNAL_API_SYSTEM_VENUE', 3);

$INTERNAL_API_SYSTEM_LIST = array(
	INTERNAL_API_SYSTEM_CONTEST,
	INTERNAL_API_SYSTEM_TICKET,
	INTERNAL_API_SYSTEM_VENUE,
);

/**
 * OpenAPI State
 */
define('OPEN_API_STATE_OK', 1);
define('OPEN_API_STATE_NG', 2);

/**
 * Api Method
 */
define('API_METHOD_GET', 1);
define('API_METHOD_POST', 2);

$OPEN_API_METHOD_LIST = array(
	API_METHOD_GET,
	API_METHOD_POST,
);

/**
 * Api Role
 */
// 普通角色
define('OPEN_API_ROLE_NORMAL', 1);

$OPEN_API_ROLE_LIST = array(
	OPEN_API_ROLE_NORMAL,
);

/**
 * Role Api Mapping State
 */
define('ROLE_API_MAPPING_STATE_OK', 1);
define('ROLE_API_MAPPING_STATE_NG', 2);

/**
 * Api Params IO Type
 */
define('API_PARAMS_IO_TYPE_IN', 1);
define('API_PARAMS_IO_TYPE_OUT', 2);

$API_IO_TYPE_LIST = array(
	API_PARAMS_IO_TYPE_IN,
	API_PARAMS_IO_TYPE_OUT,
);

/**
 * Api Params Value Type
 */
define('API_PARAMS_VALUE_TYPE_INT', 1);
define('API_PARAMS_VALUE_TYPE_STRING', 2);
define('API_PARAMS_VALUE_TYPE_BOOL', 3);
define('API_PARAMS_VALUE_TYPE_JSON', 4);

$API_PARAMS_VALUE_TYPE_LIST = array(
	API_PARAMS_VALUE_TYPE_INT,
	API_PARAMS_VALUE_TYPE_STRING,
	API_PARAMS_VALUE_TYPE_BOOL,
	API_PARAMS_VALUE_TYPE_JSON,
);

/**
 * Api Params State
 */
define('API_PARAMS_STATE_OK', 1);
define('API_PARAMS_STATE_NG', 2);

/**
 * 是否为空
 */
define('API_PARAMS_NULL_ALLOW', 1);
define('API_PARAMS_NULL_NOT', 2);


##############################################################################
#                              业务相关     END                               #
##############################################################################
/*
* 金飞鹰配置
*/
define('SOF_URL', 'http://testapi.softykt.com');                               //域名
define('SOF_ACCESSTOKEN_URL', SOF_URL . '/api1_0/api_get_token');                //获取accesst_oken
define('SOF_SCENIC_GET_PRODUCT_URL', SOF_URL . '/Api1_0/scenic_get_product'); //获取产品列表
define('SOF_SCENIC_PUT_ORDER_URL', SOF_URL . '/Api1_0/scenic_put_order');  //下单
define('SOF_SCENIC_GET_ORDER_URL', SOF_URL . '/Api1_0/scenic_get_order');  //获取订单列表
define('SOF_DISTRIBUTOR_RESMS_URL', SOF_URL . '/Api1_0/distributor_re_sms');  //重发消费码
define('SOF_SCENIC_RETURNTICKET_URL', SOF_URL . '/Api1_0/scenic_return_ticket');  //退票
define('SOF_API_CODE_SUCCESS', 666);  		//金飞鹰接口返回code 成功



