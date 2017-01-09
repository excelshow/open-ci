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

//发送客服消息
define('WEIXIN_CUSTOM_MSG_SEND_URL', 'https://api.weixin.qq.com/cgi-bin/message/custom/send');

//代金券规则
define('OPERATION_VOUCHER_RULE_STATE_NOSUBJECT', '1');//状态 未审批
define('OPERATION_VOUCHER_RULE_STATE_WAIT', '2');//状态 待审批
define('OPERATION_VOUCHER_RULE_STATE_PASSED', '3');//状态 通过
define('OPERATION_VOUCHER_RULE_STATE_NOTPASSED', '4');//状态 不通过
define('OPERATION_VOUCHER_RULE_STATE_GENERATING', '5');//状态 生成中
define('OPERATION_VOUCHER_RULE_STATE_GENERATED', '6'); //状态 已生成
define('OPERATION_VOUCHER_RULE_STATE_CANCEL', '7');//作废
define('OPERATION_VOUCHER_RULE_STATE_CANCEL_FINISH', '8');//作废完成
define('OPERATION_VOUCHER_RULE_STATE_OVERDUE', '9');//过期

//代金券

define('OPERATION_VOUCHER_STATE_NOTALLOT', '1'); //状态 已生成未分配
define('OPERATION_VOUCHER_STATE_NOTUSE', '2'); //状态 已分配未使用
define('OPERATION_VOUCHER_STATE_USE', '3'); //状态 已使用
define('OPERATION_VOUCHER_STATE_CANCEL', '4'); //状态 销毁
define('OPERATION_VOUCHER_STATE_OVERDUE', '5'); //状态 过期

//活动
define('OPERATION_ACTIVITY_STATE_STOP', '1'); //状态 暂存
define('OPERATION_ACTIVITY_STATE_START', '2'); //状态 上架
define('OPERATION_ACTIVITY_STATE_DOWN', '3'); //状态 下架
define('OPERATION_ACTIVITY_STATE_END', '4'); //状态 结束


####################################################################
# 微信卡券服务
####################################################################
define('OPERATION_MQ_CARD_CREATED', 'card_created');
define('OPERATION_MQ_CARD_UPDATED', 'card_updated');
define('OPERATION_MQ_CARD_DELETED', 'card_deleted');
define('OPERATION_MQ_CARD_CODE_UPDATED', 'card_code_updated');

define('OPERATION_MQ_CARD_PASS_CHECK', 'wxmsg_event_card_pass_check');
define('OPERATION_MQ_CARD_NOT_PASS_CHECK', 'wxmsg_event_card_not_pass_check');

define('OPENERATION_MQ_WXMSG_EVENT_USER_GET_CARD', 'wxmsg_event_user_get_card'); //用户领取会员卡
define('OPENERATION_MQ_WXMSG_EVENT_USER_DEL_CARD', 'wxmsg_event_user_del_card'); //用户删除卡包中的会员卡
define('OPENERATION_MQ_WXMSG_EVENT_SUBMIT_MEMBERCARD_USER_INFO', 'wxmsg_event_submit_membercard_user_info');//激活会员卡


/**
 * 卡券类型
 */
// 会员卡
define('CARD_TYPE_MEMBER_CARD', 1);

$CARD_TYPE_NAME_LIST = [
    CARD_TYPE_MEMBER_CARD => 'MEMBER_CARD',
];

/**
 * Code展示类型
 */
// 不展示
define('CODE_TYPE_NONE', 1);
// 只文字
define('CODE_TYPE_TEXT', 2);
// 一维码 + Code
define('CODE_TYPE_BARCODE', 3);
// 二维码 + Code
define('CODE_TYPE_QRCODE', 4);
// 仅一维码
define('CODE_TYPE_ONLY_BARCODE', 5);
// 仅二维码
define('CODE_TYPE_ONLY_QRCODE', 6);

$CODE_TYPE_NAME_LIST = [
    CODE_TYPE_NONE => 'CODE_TYPE_NONE',
    CODE_TYPE_TEXT => 'CODE_TYPE_TEXT',
    CODE_TYPE_BARCODE => 'CODE_TYPE_BARCODE',
    CODE_TYPE_QRCODE => 'CODE_TYPE_QRCODE',
    CODE_TYPE_ONLY_BARCODE => 'CODE_TYPE_ONLY_BARCODE',
    CODE_TYPE_ONLY_QRCODE => 'CODE_TYPE_ONLY_QRCODE',
];

define('DATE_TYPE_PERMANENT', 1);
define('DATE_TYPE_FIX_TIME_RANGE', 2);
define('DATE_TYPE_FIX_TERM', 3);

$DATE_TYPE_NAME_LIST = [
    DATE_TYPE_PERMANENT => 'DATE_TYPE_PERMANENT',
    DATE_TYPE_FIX_TIME_RANGE => 'DATE_TYPE_FIX_TIME_RANGE',
    DATE_TYPE_FIX_TERM => 'DATE_TYPE_FIX_TERM',
];

define('TIME_LIMT_TYPE_MONDAY', 1);
define('TIME_LIMT_TYPE_TUESDAY', 2);
define('TIME_LIMT_TYPE_WEDNESDAY', 3);
define('TIME_LIMT_TYPE_THURSDAY', 4);
define('TIME_LIMT_TYPE_FRIDAY', 5);
define('TIME_LIMT_TYPE_SATURDAY', 6);
define('TIME_LIMT_TYPE_SUNDAY', 7);

$TIME_LIMIT_TYPE_NAME_LIST = [
    TIME_LIMT_TYPE_MONDAY    => 'MONDAY',
    TIME_LIMT_TYPE_TUESDAY   => 'TUESDAY',
    TIME_LIMT_TYPE_WEDNESDAY => 'WEDNESDAY',
    TIME_LIMT_TYPE_THURSDAY  => 'THURSDAY',
    TIME_LIMT_TYPE_FRIDAY    => 'FRIDAY',
    TIME_LIMT_TYPE_SATURDAY  => 'SATURDAY',
    TIME_LIMT_TYPE_SUNDAY    => 'SUNDAY',
];

define('BIZ_SERVICE_DELIVER', 1);
define('BIZ_SERVICE_FREE_PARK', 2);
define('BIZ_SERVICE_WITH_PET', 3);
define('BIZ_SERVICE_FREE_WIFI', 4);

$BUSINESS_SERVICE_NAME_LIST = [
    BIZ_SERVICE_DELIVER   => 'BIZ_SERVICE_DELIVER',
    BIZ_SERVICE_FREE_PARK => 'BIZ_SERVICE_FREE_PARK',
    BIZ_SERVICE_WITH_PET  => 'BIZ_SERVICE_WITH_PET',
    BIZ_SERVICE_FREE_WIFI => 'BIZ_SERVICE_FREE_WIFI',
];

/**
 * 卡背景
 */

// 背景色
define('CARD_BACKGROUND_TYPE_COLOR', 1);
// 背景图
define('CARD_BACKGROUND_TYPE_PIC', 2);

/**
 * 卡券状态
 */
define('CARD_STATE_CREATED', 1);
define('CARD_STATE_CHECKING', 2);
define('CARD_STATE_PASS', 3);
define('CARD_STATE_DELETED', 4);
define('CARD_STATE_NOT_PASS', 5);
