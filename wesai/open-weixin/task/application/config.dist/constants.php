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

/*------------------------业务相关配置--------------------*/

// 消息队列
// 企业号应用套件事件队列
define('QYWX_MQ_CORP_SUITE_EVENT', 'mq_corp_suite_event');

// 需要刷新token的授权纪录
define('QYWX_MQ_AUTH_NEED_REFRESH_TOKEN', 'auth_need_refresh_token');
// 需要刷新jsapi_ticket的企业
define('QYWX_MQ_CORP_NEED_REFRESH_TICKET', 'corp_need_refresh_ticket');
// 需要刷新token的服务商
define('QYWX_MQ_PROVIDER_NEED_REFRESH_TOKEN', 'provider_need_refresh_token');
// 需要刷新token的应用套件
define('QYWX_MQ_SUITE_NEED_REFRESH_TOKEN', 'suite_need_refresh_token');

// 企业号授权关系状态
// 已授权
define('T_CORP_AUTHORIZATION_STATE_ON', 1);
// 已取消授权
define('T_CORP_AUTHORIZATION_STATE_OFF', 2);

##################################################
# 微信公众号
##################################################
// 获取ACCESS_TOKEN地址
define('WEIXIN_GET_ACCESS_TOKEN_URL', 'https://api.weixin.qq.com/cgi-bin/token');
// 获取COMPONENT_ACCESS_TOKEN地址
define('WEIXIN_GET_COMPONENT_ACCESS_TOKEN_URL', 'https://api.weixin.qq.com/cgi-bin/component/api_component_token');
// 获取COMPONENT_AUTHORIZER_ACCESS_TOKEN地址
define('WEIXIN_GET_COMPONENT_AUTHORIZER_ACCESS_TOKEN_URL', 'https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token');
// 获取JSAPI_TICKET地址
define('WEIXIN_GET_JSAPI_TICKET_URL', 'https://api.weixin.qq.com/cgi-bin/ticket/getticket');
//创建自定义菜单
define('WEIXIN_CREATE_MENU_URL', 'https://api.weixin.qq.com/cgi-bin/menu/create');
//查询当前自定义菜单列表
define('WEIXIN_GET_MENU_URL', 'https://api.weixin.qq.com/cgi-bin/menu/get');
//删除自定义菜单
define('WEIXIN_DEL_MENU_URL', 'https://api.weixin.qq.com/cgi-bin/menu/delete');

##################################################
# 微信企业号
##################################################
define('QYWX_GET_SUITE_TOKEN', 'https://qyapi.weixin.qq.com/cgi-bin/service/get_suite_token');
define('QYWX_GET_PERMANENT_CODE', 'https://qyapi.weixin.qq.com/cgi-bin/service/get_permanent_code?suite_access_token=');
define('QYWX_GET_AUTH_INFO', 'https://qyapi.weixin.qq.com/cgi-bin/service/get_auth_info?suite_access_token=');
define('QYWX_GET_CROP_TOKEN', 'https://qyapi.weixin.qq.com/cgi-bin/service/get_corp_token?suite_access_token=');
define('QYWX_GET_PROVIDER_TOKEN', 'https://qyapi.weixin.qq.com/cgi-bin/service/get_provider_token');
define('QYWX_GET_JSAPI_TICKET', 'https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=');

##################################################
# 业务相关
##################################################

// MQ
define('MQ_TOPIC_AUTHORIZER_WILL_REFRESH', 'authorizer_will_refresh');
