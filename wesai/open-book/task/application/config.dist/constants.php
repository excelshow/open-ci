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

/**
 * 订单状态
 **/
// 初始化
define('ORDER_STATE_INIT', 1);
// 支付中
define('ORDER_STATE_PAYING', 2);
// 支付失败
define('ORDER_STATE_FAILED', 3);
// 支付完成
define('ORDER_STATE_COMPLETED', 4);
// 订单关闭
define('ORDER_STATE_CLOSED', 5);
// 退款中
define('ORDER_STATE_REFUNDING', 6);
// 退款失败
define('ORDER_STATE_REFUND_FAILED', 7);
// 退款完成
define('ORDER_STATE_REFUND_COMPLETED', 8);

//订单支付状态
$ORDER_PAY_SATATELIST = array(
	ORDER_STATE_INIT             => '创建',
	ORDER_STATE_PAYING           => '等待付款',
	ORDER_STATE_FAILED           => '支付失败',
	ORDER_STATE_COMPLETED        => '支付完成',
	ORDER_STATE_CLOSED           => '报名成功',
	ORDER_STATE_REFUNDING        => '退款中',
	ORDER_STATE_REFUND_FAILED    => '退款失败',
	ORDER_STATE_REFUND_COMPLETED => '退款完成',
);

//支付通道配置
//微信支付 JSAPI
define('ORDER_PAY_CHANNEL_WEIXIN_JSAPI', 1);
// 微信支付 APP
define('ORDER_PAY_CHANNEL_WEIXIN_APP', 2);
//支付宝支付 APP
define('ORDER_PAY_CHANNEL_ALIPAY_APP', 3);
// 支付宝支付 WAP
define('ORDER_PAY_CHANNEL_ALIPAY_WAP', 4);
// 微信支付 二维码
define('ORDER_PAY_CHANNEL_WEIXIN_NATIVE', 5);

//订单来源
//微信公众号
define('ORDER_SOURCE_WEIXIN', 1);
//微赛APP
define('ORDER_SOURCE_APP', 2);
//M站
define('ORDER_SOURCE_MSITE', 3);

// 订单超时时间
define('ORDER_TIME_EXPIRE', 10); // 分钟

//支付系统对应的service标识
define('ORDER_PAY_SERVICE', 104);

//订单系统成功订单状态
define('ORDER_PAY_SYS_ORDER_STATE_SUCCESS', 3);

/**
 * 短信业务标识
 */
define('SMS_BIZ_VERIFY_CODE', 'common-code');
define('SMS_BIZ_BOOK_SUCCESS', 'book-success');

/**
 * 短信调用端
 */
define('SMS_CLIENT_ID_BOOK', 'book');

/**
 * 核销状态
 */
// 初始化
define('ORDER_VERIFY_STATE_INIT', 0);
// 未核销
define('ORDER_VERIFY_STATE_UNVERIFIED', 1);
// 已核销
define('ORDER_VERIFY_STATE_VERIFIED', 2);

// 订单签名密钥
define('ORDER_ENCRYPT_KEY', 'c43579eeb09cb210a71c3c6bd52461ab');


################################################################
# 微信相关,不经常变更配置
################################################################
//获取网页授权地址
define('WEIXIN_SNSAPI_AUTHORIZE_URL', 'https://open.weixin.qq.com/connect/oauth2/authorize');
// scope 类型
define('WEIXIN_SNSAPI_SCOPE_BASE', 'snsapi_base');
define('WEIXIN_SNSAPI_SCOPE_USERINFO', 'snsapi_userinfo');


//订单支付结果
define('MQ_TOPIC_ORDER_PAY_RESULT', 'pay_mq_order_success_104');
//订单支付完成处理队列
define('MQ_TOPIC_ORDER_PAY_COMPLETED', 'book_mq_order_pay_completed');
// 超时订单
define('MQ_TOPIC_BOOK_ORDER_EXPIRED', 'book_mq_order_expired');

// 场地规则
define('MQ_TOPIC_BOOK_VENUE_RES_RULE', 'book_mq_venue_res_rule');

// 订单默认失效时间，单位（分钟）
define('ORDER_PAY_TIME_LIMIT', 11);
