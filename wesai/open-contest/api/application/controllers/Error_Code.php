<?php

class Error_Code
{
    const SUCCESS     = 0;
    const ERROR_PARAM = -1;
    const ERROR_DB    = -2;
    const ERROR_THROW = -3;
    const ERROR_REDIS = -4;

    const  ERROR_CONTEST_INVALID_GTYPE                  = -100;
    const  ERROR_CONTEST_NOT_EXISTS                     = -101;
    const  ERROR_MALATHION_NOT_EXISTS                   = -102;
    const  ERROR_MALATHION_INVALID_STATE                = -103;
    const  ERROR_MALATHION_STATE_1_TO_2_FAIL            = -104;
    const  ERROR_MALATHION_STATE_2_TO_3_FAIL            = -105;
    const  ERROR_MALATHION_STATE_3_TO_4_FAIL            = -106;
    const  ERROR_MALATHION_STATE_4_TO_5_FAIL            = -107;
    const  ERROR_MALATHION_STATE_5_TO_6_FAIL            = -108;
    const  ERROR_MALATHION_STATE_6_TO_7_FAIL            = -109;
    const  ERROR_MALATHION_STATE_7_TO_8_FAIL            = -110;
    const  ERROR_MALATHION_STATE_8_TO_9_FAIL            = -111;
    const  ERROR_MALATHION_STATE_9_TO_10_FAIL           = -112;
    const  ERROR_MALATHION_STATE_10_TO_11_FAIL          = -113;
    const  ERROR_MALATHION_STATE_11_TO_12_FAIL          = -114;
    const  ERROR_MALATHION_STATE_12_TO_13_FAIL          = -115;
    const  ERROR_MALATHION_STATE_4_TO_8_FAIL            = -116;
    const  ERROR_MALATHION_LOTTERY_INVALID              = -117;
    const  ERROR_CONTEST_CANNOT_BE_EDITED               = -118;
    const  ERROR_CONTEST_ADD_TAG_UNITS_FAIL             = -119;
    const  ERROR_CONTEST_ITEMS_NOT_EXISTS               = -120;
    const  ERROR_CONTEST_ITEMS_STATE_INVALID            = -121;
    const  ERROR_CONTEST_ITEMS_QUOTA_FULFIL             = -122;
    const  ERROR_CONTEST_LOCATION_NOT_SET               = -123;
    const  ERROR_CONTEST_UNITS_NOT_SET                  = -124;
    const  ERROR_CONTEST_ITEMS_EMPTY                    = -125;
    const  ERROR_CONTEST_ITEMS_ENROLFORM_NOT_SET        = -126;
    const  ERROR_MALATHION_OFFLINE_CONTEST              = -127;
    const  ERROR_CONTEST_SMS_TEMPLATE_NOT_EXISTS        = -128;
    const  ERROR_CONTEST_SMS_TEMPLATE_ALREADY_EXISTS    = -129;
    const  ERROR_MALATHION_STATE_2_TO_1_FAIL            = -130;
    const  ERROR_CONTEST_PUBLISH_STATE_INVALID          = -131;
    const  ERROR_CONTEST_ITEM_ENROL_FORM_NOT_ENOUGH     = -132;
    const  ERROR_CONTEST_ITEM_MUST_SET_MAX_PLAYER       = -133;
    const  ERROR_CONTEST_ITEM_INVITE_CODE_CREATE_FAILED = -134;
    const  ERROR_CONTEST_ITEM_INVITE_CODE_NOT_EXISTS    = -135;
    const  ERROR_CONTEST_ITEM_INVITE_CODE_USED          = -136;
    const  ERROR_CONTEST_ITEM_INVITE_CODE_EXPIRED       = -137;
    const  ERROR_CONTEST_ITEM_INVITE_CODE_NECESSARY     = -138;
    const  ERROR_CONTEST_ITEMS_CLOSED                   = -139;
    const  ERROR_CONTEST_UNITS_EXIST                    = -140;
    const  ERROR_TEAM_ENROL_DATA_NOT_ENOUGH             = -141;
    const  ERROR_CONTEST_ITEM_TYPE_INVALID              = -142;
    const  ERROR_CONTEST_INFO_NOT_ENOUGH                = -143;

    const  ERROR_FORM_NOT_EXISTS                   = -200;
    const  ERROR_FORM_DATA_INVALID                 = -201;
    const  ERROR_FORM_INPUT_INVALID                = -202;
    const  ERROR_FORM_ALREADY_EXISTS               = -203;
    const  ERROR_ADD_ENROL_FORM_ITEM_FAILED        = -204;
    const  ERROR_ENROL_FORM_ITEM_NOT_EXISTS        = -205;
    const  ERROR_ENROL_FORM_ITEM_STATE_INVALID     = -206;
    const  ERROR_ENROL_DATA_NOT_EXISTS             = -207;
    const  ERROR_ENROL_DATA_SET_VERIFY_CODE_FAILED = -208;
    const  ERROR_ENROL_DATA_DETAIL_NOT_EXISTS      = -209;
    const  ERROR_ENROL_DATA_REMOVE_FAILED          = -210;
    const  ERROR_ENROL_DATA_VERIFY_OVERFLOW        = -211;
    const  ERROR_ENROL_DATA_VERIFY_FAILED          = -212;
    const  ERROR_ENROL_DATA_VERIFY_ITEM_NOT_MATCH  = -213;
    const  ERROR_ENROL_DATA_VERIFY_CORP_NOT_MATCH  = -214;
    const  ERROR_ENROL_DATA_TYPE_INVALID           = -215;
    const  ERROR_ENROL_DATA_VERIFY_CODE_NOT_EXISTS = -216;

    const ERROR_ORDER_INVALID_AMOUNT                  = -300;
    const ERROR_ORDER_INVALID_STATE                   = -301;
    const ERROR_ORDER_CREATE_FAILED                   = -302;
    const ERROR_ORDER_PREPAY_FAILED                   = -303;
    const ERROR_ORDER_NOT_EXISTS                      = -304;
    const ERROR_ORDER_REFUND_UID_NOT_MATCH            = -305;
    const ERROR_ORDER_REFUND_APPLY_FAILED             = -306;
    const ERROR_CAN_NOT_REORDER_SAME_CONTEST          = -307;
    const ERROR_ORDER_ALREADY_EXPIRED                 = -308;
    const ERROR_ORDER_VERIFY_OVERFLOW                 = -309;
    const ERROR_ORDER_VERIFY_ITEM_NOT_MATCH           = -310;
    const ERROR_ORDER_VERIFY_ITEM_HAS_ALREADY_STARTED = -311;
    const ERROR_ORDER_NOT_CREATED                     = -312;

    const ERROR_TEAM_NOT_EXISTS                           = -401;
    const ERROR_TEAM_STATE_INVALID                        = -402;
    const ERROR_TEAM_EDIT_NO_AUTH                         = -403;
    const ERROR_GROUP_NOT_EXISTS                          = -404;
    const ERROR_GROUP_STATE_INVALID                       = -405;
    const ERROR_GROUP_EDIT_NO_AUTH                        = -406;
    const ERROR_TEAM_USER_MAPPING_NOT_EXISTS              = -407;
    const ERROR_GROUP_USER_MAPPING_NOT_EXISTS             = -408;
    const ERROR_TEAM_FULL                                 = -409;
    const ERROR_GROUP_IN_PAYING_CAN_NOT_REMOVE_ENROL_DATA = -410;
    const ERROR_TEAM_IN_PAYING_CAN_NOT_REMOVE_ENROL_DATA  = -411;
    const ERROR_SHARING_SETTINGS_NOT_EXISTS               = -412;
    const ERROR_SHARING_SETTINGS_ALREADY_EXISTS           = -413;

    const  ERROR_PARTNER_CONTEST_EXT_NOT_EXISTS     = -501;
    const  ERROR_PARTNER_CONTEST_EXT_ALREADY_EXISTS = -502;
    const  ERROR_PARTNER_ORDER_NOT_EXISTS           = -503;
    const  ERROR_PARTNER_CONTEST_EXT_SAVE_FAILED    = -504;
    const  ERROR_PARTNER_ORDER_STATE_INVALID        = -505;


    public static $info = array(
        self::SUCCESS     => '成功',
        self::ERROR_PARAM => '参数错误',
        self::ERROR_DB    => '数据库错误',
        self::ERROR_THROW => '异常错误',
        self::ERROR_REDIS => 'Redis 队列服务错误',

        self::ERROR_CONTEST_INVALID_GTYPE                  => '竞赛类型错误',
        self::ERROR_CONTEST_NOT_EXISTS                     => '活动不存在',
        self::ERROR_MALATHION_NOT_EXISTS                   => '马拉松活动不存在',
        self::ERROR_MALATHION_INVALID_STATE                => '马拉松活动状态异常',
        self::ERROR_MALATHION_STATE_1_TO_2_FAIL            => '上架马拉松活动失败',
        self::ERROR_MALATHION_STATE_2_TO_3_FAIL            => '修改状态为报名开始失败',
        self::ERROR_MALATHION_STATE_3_TO_4_FAIL            => '修改状态为报名结束失败',
        self::ERROR_MALATHION_STATE_4_TO_5_FAIL            => '修改状态为资格审查中失败',
        self::ERROR_MALATHION_STATE_5_TO_6_FAIL            => '修改状态为抽签中失败',
        self::ERROR_MALATHION_STATE_6_TO_7_FAIL            => '修改状态为抽签结束失败',
        self::ERROR_MALATHION_STATE_7_TO_8_FAIL            => '修改状态为装备领取中失败',
        self::ERROR_MALATHION_STATE_8_TO_9_FAIL            => '修改状态为装备领取结束失败',
        self::ERROR_MALATHION_STATE_9_TO_10_FAIL           => '修改状态为检录中失败',
        self::ERROR_MALATHION_STATE_10_TO_11_FAIL          => '修改状态为检录结束失败',
        self::ERROR_MALATHION_STATE_11_TO_12_FAIL          => '修改状态为竞赛开始失败',
        self::ERROR_MALATHION_STATE_12_TO_13_FAIL          => '修改状态为竞赛结束失败',
        self::ERROR_MALATHION_STATE_4_TO_8_FAIL            => '修改状态为装备领取中失败',
        self::ERROR_MALATHION_LOTTERY_INVALID              => '活动抽签状态异常',
        self::ERROR_CONTEST_CANNOT_BE_EDITED               => '活动状态异常，不可编辑',
        self::ERROR_CONTEST_ADD_TAG_UNITS_FAIL             => '增加活动组织单位失败',
        self::ERROR_CONTEST_ITEMS_NOT_EXISTS               => '活动项目不存在',
        self::ERROR_CONTEST_ITEMS_STATE_INVALID            => '活动项目状态异常',
        self::ERROR_CONTEST_ITEMS_QUOTA_FULFIL             => '活动项目报名满额',
        self::ERROR_CONTEST_LOCATION_NOT_SET               => '活动未设置地理位置',
        self::ERROR_CONTEST_UNITS_NOT_SET                  => '活动未设置组织单位',
        self::ERROR_CONTEST_ITEMS_EMPTY                    => '请先添加活动项目',
        self::ERROR_CONTEST_ITEMS_ENROLFORM_NOT_SET        => '活动项目未关联报名表',
        self::ERROR_MALATHION_OFFLINE_CONTEST              => '强制下线活动失败',
        self::ERROR_CONTEST_SMS_TEMPLATE_NOT_EXISTS        => '活动短信模板不存在',
        self::ERROR_CONTEST_SMS_TEMPLATE_ALREADY_EXISTS    => '活动短信模板已存在',
        self::ERROR_MALATHION_STATE_2_TO_1_FAIL            => '下架马拉松失败',
        self::ERROR_CONTEST_PUBLISH_STATE_INVALID          => '活动状态异常',
        self::ERROR_CONTEST_ITEM_ENROL_FORM_NOT_ENOUGH     => '有活动项目未设置报名表',
        self::ERROR_CONTEST_ITEM_MUST_SET_MAX_PLAYER       => '需要邀请报名的活动项目必须设定参加人数上限',
        self::ERROR_CONTEST_ITEM_INVITE_CODE_CREATE_FAILED => '创建报名邀请码失败',
        self::ERROR_CONTEST_ITEM_INVITE_CODE_NOT_EXISTS    => '邀请码无效',
        self::ERROR_CONTEST_ITEM_INVITE_CODE_USED          => '邀请码已被使用',
        self::ERROR_CONTEST_ITEM_INVITE_CODE_EXPIRED       => '邀请码已作废',
        self::ERROR_CONTEST_ITEM_INVITE_CODE_NECESSARY     => '该项目报名需要提供邀请码',
        self::ERROR_CONTEST_ITEMS_CLOSED                   => '项目已停止',
        self::ERROR_CONTEST_UNITS_EXIST                    => '组织单位已存在',
        self::ERROR_TEAM_ENROL_DATA_NOT_ENOUGH             => '团队人数不足',
        self::ERROR_CONTEST_ITEM_TYPE_INVALID              => '活动项目类型错误',
        self::ERROR_CONTEST_INFO_NOT_ENOUGH                => '活动资料不足',

        self::ERROR_FORM_NOT_EXISTS                   => '报名表单不存在',
        self::ERROR_FORM_DATA_INVALID                 => '表单数据异常',
        self::ERROR_FORM_INPUT_INVALID                => '用户提交的表单数据异常',
        self::ERROR_FORM_ALREADY_EXISTS               => '活动项目报名表单已存在',
        self::ERROR_ADD_ENROL_FORM_ITEM_FAILED        => '增加表单项目失败',
        self::ERROR_ENROL_FORM_ITEM_NOT_EXISTS        => '表单项目不存在',
        self::ERROR_ENROL_FORM_ITEM_STATE_INVALID     => '表单项目状态异常',
        self::ERROR_ENROL_DATA_NOT_EXISTS             => '报名资料不存在',
        self::ERROR_ENROL_DATA_SET_VERIFY_CODE_FAILED => '设定核销码失败',
        self::ERROR_ENROL_DATA_DETAIL_NOT_EXISTS      => '报名详情不存在',
        self::ERROR_ENROL_DATA_REMOVE_FAILED          => '删除报名资料失败',
        self::ERROR_ENROL_DATA_VERIFY_OVERFLOW        => '核销次数超限',
        self::ERROR_ENROL_DATA_VERIFY_FAILED          => '核销失败',
        self::ERROR_ENROL_DATA_VERIFY_CORP_NOT_MATCH  => '核销码无效',
        self::ERROR_ENROL_DATA_VERIFY_ITEM_NOT_MATCH  => '不在本次核销范围',
        self::ERROR_ENROL_DATA_TYPE_INVALID           => '报名资料无效',

        self::ERROR_ORDER_INVALID_AMOUNT                  => '订单金额有误',
        self::ERROR_ORDER_INVALID_STATE                   => '订单状态异常',
        self::ERROR_ORDER_CREATE_FAILED                   => '订单创建失败',
        self::ERROR_ORDER_PREPAY_FAILED                   => '订单预支付失败',
        self::ERROR_ORDER_NOT_EXISTS                      => '订单不存在',
        self::ERROR_ORDER_REFUND_UID_NOT_MATCH            => '退款用户与下单用户不一致',
        self::ERROR_ORDER_REFUND_APPLY_FAILED             => '申请退款失败',
        self::ERROR_CAN_NOT_REORDER_SAME_CONTEST          => '不能重复报名同一活动',
        self::ERROR_ORDER_ALREADY_EXPIRED                 => '订单已超时',
        self::ERROR_ORDER_VERIFY_OVERFLOW                 => '订单核销超限',
        self::ERROR_ORDER_VERIFY_ITEM_NOT_MATCH           => '订单不属于本次检票范围',
        self::ERROR_ORDER_VERIFY_ITEM_HAS_ALREADY_STARTED => '项目已开始,停止检票',
        self::ERROR_ORDER_NOT_CREATED                     => '未下单',

        self::ERROR_TEAM_NOT_EXISTS                           => '团队不存在',
        self::ERROR_TEAM_STATE_INVALID                        => '团队状态异常',
        self::ERROR_TEAM_EDIT_NO_AUTH                         => '没有编辑权限',
        self::ERROR_GROUP_NOT_EXISTS                          => '小组不存在',
        self::ERROR_GROUP_STATE_INVALID                       => '小组状态异常',
        self::ERROR_GROUP_EDIT_NO_AUTH                        => '没有编辑权限',
        self::ERROR_TEAM_USER_MAPPING_NOT_EXISTS              => '绑定关系不存在',
        self::ERROR_GROUP_USER_MAPPING_NOT_EXISTS             => '绑定关系不存在',
        self::ERROR_TEAM_FULL                                 => '团队满员',
        self::ERROR_GROUP_IN_PAYING_CAN_NOT_REMOVE_ENROL_DATA => '已下单，报名资料不可变更',
        self::ERROR_TEAM_IN_PAYING_CAN_NOT_REMOVE_ENROL_DATA  => '已下单，报名资料不可变更',

        self::ERROR_SHARING_SETTINGS_NOT_EXISTS     => '活动分享信息不存在',
        self::ERROR_SHARING_SETTINGS_ALREADY_EXISTS => '活动分享信息已存在',

        self::ERROR_PARTNER_CONTEST_EXT_NOT_EXISTS     => '合作方商品信息不存在',
        self::ERROR_PARTNER_CONTEST_EXT_ALREADY_EXISTS => '合作方商品信息已存在',
        self::ERROR_PARTNER_ORDER_NOT_EXISTS           => '合作方订单信息不存在',
        self::ERROR_PARTNER_CONTEST_EXT_SAVE_FAILED    => '合作方商品信息保存失败',
        self::ERROR_PARTNER_ORDER_STATE_INVALID        => '合作方订单状态异常',

    );

    public static function desc($code)
    {
        return empty(self::$info[$code]) ? '' : self::$info[$code];
    }
}
