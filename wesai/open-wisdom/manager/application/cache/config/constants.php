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


##########################################################################
#                         报名  START                                       #
##########################################################################
//活动状态
$CONTEST_STATE_LIST = array(
	1 => '暂存',
	2 => '上架',
	3 => '报名中',
	4 => '下架',
);

//$CONTEST_STATE_LIST = array(
//	1  => '暂存',
//	2  => '资格审核中',
//	3  => '抽签中',
//	4  => '抽签结束',
//	5  => '装备领取中',
//	6  => '装备领取完成',
//	7  => '检录中',
//	8  => '检录完成',
//	9  => '竞赛开始',
//	10 => '竞赛结束',
//);

//活动级别
$CONTEST_LEVEL_LIST       = array(
	1   => 'CAA认证A类活动',
	2   => 'CAA认证B类活动',
	4   => 'CAA金牌活动',
	8   => 'CAA银牌活动',
	16  => 'CAA铜牌活动',
	32  => 'CAA认证赛道',
	64  => 'IAAF金标活动',
	128 => 'IAAF银标活动',
	256 => 'IAAF铜标活动',
	512 => 'AIMS认证活动',
	0   => '其他',
);
$NEWCONTEST_LEVEL_LIST    = array(
	0   => array('其他', 'O'),
	1   => array('CAA认证A类活动', "A"),
	2   => array('CAA认证B类活动', "B"),
	4   => array('CAA金牌活动', "CAA"),
	8   => array('CAA银牌活动', "CAA"),
	16  => array('CAA铜牌活动', "CAA"),
	32  => array('CAA认证赛道', '道'),
	64  => array('IAAF金标活动', 'IAAF'),
	128 => array('IAAF银标活动', 'IAAF'),
	256 => array('IAAF铜标活动', 'IAAF'),
	512 => array('AIMS认证活动', 'AIMS'),
);
$CONTEST_UNITS_LIST       = array(
	1 => '主办单位',
	2 => '联合主办单位',
	3 => '承办单位',
	4 => '协办单位',
	5 => '备案单位',
);
$CONTEST_LOTTERY_LIST     = array(
	1 => '需要抽签',
	2 => '无需抽签',
);
$CONTEST_DELIVERGEAR_LIST = array(
	1 => '需要邮寄装备',
	2 => '无需邮寄装备',
);
$CONTEST_CHANGESTATE_LIST = array(
	"online"        => array("actname" => "上架", "apiurl" => "contest/online.json"),
	"start_selling" => array("actname" => "开始报名", "apiurl" => "contest/start_selling.json"),
	"offline"       => array("actname" => "下架", "apiurl" => "contest/offline.json"),
	"re_online"     => array("actname" => "重新上架", "apiurl" => "contest/re_online.json"),
);


//	"rstart"        => array("actname" => "报名开始", "apiurl" => "contest/malathion/change_state_enrolling.json"),
//	"rend"          => array("actname" => "报名结束", "apiurl" => "contest/malathion/change_state_enrol_completed.json"),
//	"auditing"      => array("actname" => "资格审核中", "apiurl" => "contest/malathion/change_state_reviewing.json"),
//	"ballot_start"  => array("actname" => "抽签中", "apiurl" => "contest/malathion/change_state_balloting.json"),
//	"ballot_end"    => array("actname" => "抽签结束", "apiurl" => "contest/malathion/change_state_ballot_completed.json"),
//	"gstart"        => array("actname" => "装备领取中", "apiurl" => "contest/malathion/change_state_receiving.json"),
//	"gstart_v2"     => array("actname" => "装备领取中", "apiurl" => "contest/malathion/change_state_enrol_completed_to_receiving.json"),
//	"gend"          => array("actname" => "领取装备结束", "apiurl" => "contest/malathion/change_state_receive_completed.json"),
//	"cstart"        => array("actname" => "开始检录", "apiurl" => "contest/malathion/change_state_rollcall.json"),
//	"cend"          => array("actname" => "检录结束", "apiurl" => "contest/malathion/change_state_rollcall_completed.json"),
//	"contest_start" => array("actname" => "竞赛开始", "apiurl" => "contest/malathion/change_state_start.json"),
//	"contest_over"  => array("actname" => "竞赛结束", "apiurl" => "contest/malathion/change_state_over.json"),


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
// 未抽签
define('ORDER_LOTTERY_STATE_INIT', 0);
// 抽签中
define('ORDER_LOTTERY_STATE_START', 1);
// 中签
define('ORDER_LOTTERY_STATE_SUCCESS', 2);
// 未中签
define('ORDER_LOTTERY_STATE_FAILED', 3);
//抽签状态
$ORDER_LOTTERY_STATELIST = array(
	ORDER_LOTTERY_STATE_INIT    => '未抽签',
	ORDER_LOTTERY_STATE_START   => '抽签中',
	ORDER_LOTTERY_STATE_SUCCESS => '已中签',
	ORDER_LOTTERY_STATE_FAILED  => '未中签',
);

define('SEX_MALE', 1);
define('SEX_FEMALE', 2);

$SEX_LIST = array(
	SEX_MALE   => '男',
	SEX_FEMALE => '女',
);
define('RES_FILEVIEW_URL', 'http://img.wesai.com');

$CONTEST_DELIVER_GEAR_OPTIONS = array(
	1 => '邮寄',
	2 => '自取',
);

$ORDER_CHANNEL_LIST   = array(
	1 => '微信支付',
	2 => '支付宝',
);
$CONTEST_COUNTRY_LIST = array(
	1 => '国内',
	2 => '国外',
);
$CONTEST_SOURCE_LIST  = array(
	1 => '微赛',
	2 => '我要赛',
);

define('CONTEST_PUBLISH_STATE_DRAFT', 1);
define('CONTEST_PUBLISH_STATE_ON', 2);
define('CONTEST_PUBLISH_STATE_SELLING', 3);
define('CONTEST_PUBLISH_STATE_OFF', 4);


/**
 * 竞赛类型
 **/
//默认值
define('CONTEST_GTYPE_DEFAULT', 1);
// 马拉松
define('CONTEST_GTYPE_MALATHION', 2);

$CONTEST_GTYPE_LIST = array(
	CONTEST_GTYPE_DEFAULT   => '其他',
	CONTEST_GTYPE_MALATHION => '马拉松',
);


/**
 * 活动项目是否需要邀请报名
 */
define('CONTEST_ITEM_INVITE_REQUIRED_YES', 1);
define('CONTEST_ITEM_INVITE_REQUIRED_NO', 2);

$CONTEST_ITEM_INVITE_REQUIRED_OPTIONS = array(
	CONTEST_ITEM_INVITE_REQUIRED_NO  => '不需要',
	CONTEST_ITEM_INVITE_REQUIRED_YES => '需要',
);

/**
 * 活动项目状态
 **/
// 正常
define('CONTEST_ITEM_STATE_OK', 1);
// 已删除
define('CONTEST_ITEM_STATE_NG', 2);


// 表单项目是否有效
define('ENROL_FORM_ITEM_STATE_OK', 1);
define('ENROL_FORM_ITEM_STATE_NG', 2);

//国内
define('CONTEST_COUNTRY_SCOPE_INTERNAL', 1);
//国外
define('CONTEST_COUNTRY_SCOPE_EXTERNAL', 2);

// 订单签名密钥
define('ORDER_ENCRYPT_KEY', 'c43579eeb09cb210a71c3c6bd52461ab');

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

$PAY_CHANNEL_LIST = array(
	ORDER_PAY_CHANNEL_WEIXIN_JSAPI  => '微信支付',
	ORDER_PAY_CHANNEL_WEIXIN_APP    => '微信支付',
	ORDER_PAY_CHANNEL_ALIPAY_APP    => '支付宝',
	ORDER_PAY_CHANNEL_ALIPAY_WAP    => '支付宝',
	ORDER_PAY_CHANNEL_WEIXIN_NATIVE => '微信支付',
);
##########################################################################
#                         报名  END                                       #
##########################################################################

#################################################
# 微信企业号
#################################################
//企业号获取登录用户信息
define('QYWX_GET_LOGIN_USER_INFO', 'https://qyapi.weixin.qq.com/cgi-bin/service/get_login_info?access_token=');

#################################################
# 微信公众号
#################################################
define('WEIXIN_OPEN_PRE_AUTHCODE_URL', 'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode');
define('WEIXIN_OPEN_API_QUERYAUTH_URL', 'https://api.weixin.qq.com/cgi-bin/component/api_query_auth');
define('WEIXIN_OPEN_API_AUTHORIZER_INFO_URL', 'https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info');
define('WEIXIN_OPEN_COMPONENTLOGINPAGE_URL', 'https://mp.weixin.qq.com/cgi-bin/componentloginpage');
//创建自定义菜单
define('WEIXIN_CREATE_MENU_URL', 'https://api.weixin.qq.com/cgi-bin/menu/create');
//查询当前自定义菜单列表
define('WEIXIN_GET_MENU_URL', 'https://api.weixin.qq.com/cgi-bin/menu/get');
//删除自定义菜单
define('WEIXIN_DEL_MENU_URL', 'https://api.weixin.qq.com/cgi-bin/menu/delete');
//获取自定义菜单配置接口
define('WEIXIN_GET_SELFMENU_URL', 'https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info');
//获取网页授权地址
define('WEIXIN_SNSAPI_AUTHORIZE_URL', 'https://open.weixin.qq.com/connect/oauth2/authorize');
// scope 类型
define('WEIXIN_SNSAPI_SCOPE_BASE', 'snsapi_base');
define('WEIXIN_SNSAPI_SCOPE_USERINFO', 'snsapi_userinfo');
// 根据code获取网页授权access_token地址
define('WEIXIN_SNSAPI_ACCESSTOKEN_URL', 'https://api.weixin.qq.com/sns/oauth2/access_token');
// 根据access_token获取网页授权用户信息地址
define('WEIXIN_SNSAPI_USERINFO_URL', 'https://api.weixin.qq.com/sns/userinfo');

// 微信端Oauth2登录
define('WEIXIN_OAUTH2_LOGIN_URL', 'https://open.weixin.qq.com/connect/oauth2/authorize');
define('WEIXIN_OAUTH2_GET_USER_INFO', 'https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo');

##################################################
# 业务相关
##################################################
// 静态文件文件夹
define('_STATIC_DIR', 'manager');
//项目URL一级路径
define('_MENU_WXAPPS_DIR_', 'wxapps');
define('_MENU_APPCENTER_DIR_', 'appcenter');
define('_MENU_CONTEST_DIR_', 'contest');

// 报名域名
define('CONTEST_DOMAIN_SUF', '.contest.devel.wx.wesai.com');
// 票务域名
define('TICKET_DOMAIN_SUF', '.ticket.devel.wx.wesai.com');

// cache pre
define('OPEN_CACHE_KEY_PRE', 'open_wisdom_cache_');
