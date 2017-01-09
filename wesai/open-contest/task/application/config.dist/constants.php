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

// 企业号授权关系状态
// 已授权
define('T_CORP_AUTHORIZATION_STATE_ON', 1);
// 已取消授权
define('T_CORP_AUTHORIZATION_STATE_OFF', 2);

/**
 * REDIS TOPICS
 **/
// 马拉松状态变更队列
define('MQ_TOPIC_MALATHION_STATE_CHANGE', 'contest_mq_malathion_state_change');
//订单支付完成处理队列
define('MQ_TOPIC_ORDER_PAY_COMPLETED', 'contest_mq_order_pay_completed');
// 来自微信的活动文件上传队列
define('MQ_TOPIC_WX_FILE_UPLOAD', 'contest_mq_wx_file_upload');
//订单支付结果
define('MQ_TOPIC_ORDER_PAY_RESULT', 'pay_mq_order_success_101');
//生成报名邀请码
define('MQ_TOPIC_CREATE_CONTEST_ITEM_INVITE_CODE', 'contest_mq_create_item_invite_code');
// 活动订单导出
define('MQ_TOPIC_CONTEST_ORDER_EXPORT', 'contest_mq_order_export');
// 超时订单
define('MQ_TOPIC_CONTEST_ORDER_EXPIRED', 'contest_mq_order_expired');

define('MQ_TOPIC_ANALYSIS_CONTEST', 'contest_mq_analysis_contest');
define('MQ_TOPIC_ANALYSIS_CONTEST_ITEM', 'contest_mq_analysis_contest_item');
define('MQ_TOPIC_ANALYSIS_ORDER', 'contest_mq_analysis_order');
define('MQ_TOPIC_ANALYSIS_ORDER_CALC', 'contest_mq_analysis_order_calc');
define('MQ_TOPIC_ANALYSIS_CONTEST_ITEM_CALC', 'contest_mq_analysis_contest_item_calc');
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
// 订单关闭(报名成功)
define('ORDER_STATE_CLOSED', 5);
// 退款中(通道待申请)
define('ORDER_STATE_REFUNDING_NOT_YET_APPLY', 6);
// 退款中(已申请)
define('ORDER_STATE_REFUNDING_APPLIED', 7);
// 退款失败
define('ORDER_STATE_REFUND_FAILED', 8);
// 退款完成
define('ORDER_STATE_REFUND_COMPLETED', 9);

//订单支付状态
$ORDER_PAY_SATATELIST = array(
	ORDER_STATE_INIT                    => '创建',
	ORDER_STATE_PAYING                  => '等待付款',
	ORDER_STATE_FAILED                  => '支付失败',
	ORDER_STATE_COMPLETED               => '支付完成',
	ORDER_STATE_CLOSED                  => '报名成功',
	ORDER_STATE_REFUNDING_NOT_YET_APPLY => '退款中(通道待申请)',
	ORDER_STATE_REFUNDING_APPLIED       => '退款中(已申请)',
	ORDER_STATE_REFUND_FAILED           => '退款失败',
	ORDER_STATE_REFUND_COMPLETED        => '退款完成',
);

// 抽签状态
// 抽签中
define('ORDER_LOTTERY_STATE_INIT', 1);
// 中签
define('ORDER_LOTTERY_STATE_SUCCESS', 2);
// 未中签
define('ORDER_LOTTERY_STATE_FAILED', 3);

$ORDER_LOTTERY_STATELIST = array(
	ORDER_LOTTERY_STATE_INIT    => '抽签中',
	ORDER_LOTTERY_STATE_SUCCESS => '已中签',
	ORDER_LOTTERY_STATE_FAILED  => '未中签',
);

/**
 * 活动项目参赛人数是否有上限
 **/
// 活动项目参赛人数有上限
define('CONTEST_ITEMS_HAS_QUOTA_YES', 1);
// 活动项目参赛人数无上限
define('CONTEST_ITEMS_HAS_QUOTA_NO', 2);


// 通知消息类型
// 手机短信
define('NOTIFY_TYPE_SMS', 1);

// 通知消息来源
// 微赛跑步
define('NOTIFY_SOURCE_CONTEST', 1);

/**
 * 马拉松比赛是否需要抽签
 **/
// 需要抽签
define('MALATHION_LOTTERY_YES', 1);
// 不需要抽签
define('MALATHION_LOTTERY_NO', 2);

//支付通道配置
//微信支付 JSAPI
define('ORDER_PAY_CHANNEL_WEIXIN_JSAPI', 1);
// 微信支付 APP
define('ORDER_PAY_CHANNEL_WEIXIN_APP', 2);
//支付宝支付 APP
define('ORDER_PAY_CHANNEL_ALIPAY_APP', 3);
//支付宝支付 WAP
define('ORDER_PAY_CHANNEL_ALIPAY_WAP', 4);
// 微信支付 二维码
define('ORDER_PAY_CHANNEL_WEIXIN_NATIVE', 5);


$ORDER_PAY_CHANNEL_LIST = array(
	ORDER_PAY_CHANNEL_WEIXIN_JSAPI  => '微信支付 JSAPI',
	ORDER_PAY_CHANNEL_WEIXIN_APP    => '微信支付 APP',
	ORDER_PAY_CHANNEL_ALIPAY_APP    => '支付宝 APP',
	ORDER_PAY_CHANNEL_ALIPAY_WAP    => '支付宝 WAP',
	ORDER_PAY_CHANNEL_WEIXIN_NATIVE => '微信支付 二维码',
);

//订单来源
//微信公众号
define('ORDER_SOURCE_WEIXIN', 1);
//微赛APP
define('ORDER_SOURCE_APP', 2);
//M站
define('ORDER_SOURCE_MSITE', 3);

//短信通道渠道
//微赛
define('SMS_SERVICE_CHANNEL_WESAI', 1);
//微票儿
define('SMS_SERVICE_CHANNEL_WEPIAO', 2);

// 订单渠道
// 微赛
define('ORDER_CHANNEL_WESAI', 1);
// 微票儿
define('ORDER_CHANNEL_WEPIAO', 2);

// 活动个性化短信模板
// 报名成功
define('CONTEST_ENROL_SUCCESS', 1);
// 领取装备
define('CONTEST_PACKAGE_RECEIVE', 2);

/**
 * 地理位置行政区划级别
 **/
// 国家
define('LOCATION_LEVEL_COUNTRY', 1);
// 省份／直辖市
define('LOCATION_LEVEL_PROVINCE', 2);
// 市
define('LOCATION_LEVEL_CITY', 3);


/**
 * 竞赛类型
 **/
// 马拉松
define('CONTEST_GTYPE_MALATHION', 1);

/**
 * 活动项目状态
 **/
// 正常
define('CONTEST_ITEM_STATE_OK', 1);
// 已删除
define('CONTEST_ITEM_STATE_NG', 2);

/**
 * 短信业务标识
 */
define('SMS_BIZ_VERIFY_CODE', 'common-code');
define('SMS_BIZ_CONTEST_SUCCESS', 'contest-success');
define('SMS_BIZ_CONTEST_GET', 'contest-get');

/**
 * 短信调用端
 */
define('SMS_CLIENT_ID_CONTEST', 'contest');

//支付系统对应的service标识
define('ORDER_PAY_SERVICE', 101);

//订单系统成功订单状态
define('ORDER_PAY_SYS_ORDER_STATE_SUCCESS', 3);

// 订单默认失效时间，单位（分钟）
define('ORDER_PAY_TIME_LIMIT', 11);


// 单人报名
define('CONTEST_ITEM_TYPE_SINGLE', 1);
// 团体报名
define('CONTEST_ITEM_TYPE_TEAM', 2);

################################################################
# 微信相关,不经常变更配置
################################################################
// 微信多媒体文件下载API
define('WEIXIN_FILE_DOWNLOAD_API', 'http://file.api.weixin.qq.com/cgi-bin/media/get');

//获取用户信息 需要手机号
define('GET_USER_EXT_MOBILE_YES', 1);