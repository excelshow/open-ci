<?php

class Error_Code
{
	const SUCCESS     = 0;
	const ERROR_PARAM = -1;
	const ERROR_DB    = -2;
	const ERROR_THROW = -3;
	const ERROR_REDIS = -4;

	const  ERROR_VOUCHER_GENERATE_FAILED   		= -202;
	const  ERROR_VOUCHER_RULE_STATE_ERROR   	= -203;
	const  ERROR_VOUCHER_RULE_NOTEXIST      	= -204;
	const  ERROR_ADD_VOUCHER_RULE_FAILED        = -205;
	const  ERROR_SAVE_VOUCHER_RULE_STATE_FAILED = -206;

	const  ERROR_VOUCHER_CHANGE_FAILED       	= -207;
	const  ERROR_VOUCHER_CODE_USEED       		= -208;
	const  ERROR_VOUCHER_NOTEXIST       		= -209;
	const  ERROR_VOUCHER_CODE_OVERDUE      		= -210;
	const  ERROR_VOUCHER_CODE_NOHAVE       		= -211;
	const  ERROR_VOUCHER_RULE_OVERDUE      		= -212;
	const  ERROR_VOUCHER_APP_ID_NOT_MATCH       = -213;
	const  ERROR_VOUCHER_USER_NOT_MATCH         = -214;
	const  ERROR_VOUCHER_VALUE_MIN_NOT_MATCH    = -215;
	const  ERROR_VOUCHER_USE_ALREADY            = -216;
	const  ERROR_VOUCHER_CONSUME_FAILED         = -217;
	const  ERROR_VOUCHER_CHANGE_USER_NOTBELONG  = -218;

	const  ERROR_VOUCHER_RULE_VALUE_ERROR  		= -219;
	const  ERROR_VOUCHER_RULE_VALUE_MIX_ERROR  	= -220;
	const  ERROR_VOUCHER_RULE_VALUE_MIX_BIG  	= -221;
	const  ERROR_VOUCHER_RULE_CORP_NOTBELONG  	= -222;

	const  ERROR_ADD_ACTIVITY_FAILED        	= -301;
	const  ERROR_ACTIVITY_NOTEXIST        		= -302;
	const  ERROR_ACTIVITY_BING_RULE_FAILED      = -303;
	const  ERROR_ACTIVITY_NOTSTART        		= -304;
	const  ERROR_ACTIVITY_END        			= -305;
	const  ERROR_ACTIVITY_RULE_NOBIND  			= -306;
	const  ERROR_ACTIVITY_ADD_INVITE_FAILED    	= -307;
	const  ERROR_ACTIVITY_PARTAKE_USEUP       	= -308;
	const  ERROR_ACTIVITY_PARTAKE_MIX 			= -309;
	const  ERROR_ACTIVITY_BING_HAVE        		= -310;
	const  ERROR_ACTIVITY_INVITE_HAVE       	= -311;
	const  ERROR_ACTIVITY_CORP_ERROR        	= -312;
	const  ERROR_ACTIVITY_NUMBER_MAX_ERROR  	= -313;
	const  ERROR_ACTIVITY_TIME_END_ERROR  		= -314;

	const ERROR_CARD_CORP_SERVICE_TYPE_ID_DUPLICATED = - 501;
	const ERROR_CARD_CREATE_FAILED = -502;
	const ERROR_CARD_UPDATE_FAILED = -503;
	const ERROR_CARD_PARAM_REQUIRED = -504;
	const ERROR_CARD_PARAM_INVALID = -505;
	const ERROR_CARD_CUSTOM_FIELD_OVERFLOW = -506;
    const ERROR_CARD_EXT_CREATE_FAILED = -507;
    const ERROR_CARD_EXT_CUSTOM_CREATE_FAILED = -508;
    const ERROR_CARD_TEXT_IMAGE_CREATE_FAILED = -509;
    const ERROR_CARD_NOT_EXISTS = -510;
    const ERROR_CARD_CODE_ALREADY_BEEN_RECEIVED = -511;
    const ERROR_CARD_CODE_CREATE_FAILED = -512;
    const ERROR_CARD_CODE_NOT_EXISTS = -513;
    const ERROR_CARD_CODE_STATE_INVALID = -514;
    const ERROR_CARD_CODE_INCREASE_BALANCE_FAILED = -515;
    const ERROR_CARD_CODE_INCREASE_BONUS_FAILED = -516;
    const ERROR_CARD_CODE_UPDATE_FAILED = -517;
    const ERROR_CARD_VS_CODE_NOT_MATCH = -518;
    const ERROR_CARD_CODE_RECEIVE_FALIED = -519;
    const ERROR_CARD_STATE_INVALID = -520;
    const ERROR_CARD_CHANGE_STATE_FROM_CREATED_TO_CHECKING = -521;
    const ERROR_CARD_CHANGE_STATE_FROM_CHECKING_TO_NOT_PASS = -522;
    const ERROR_CARD_CHANGE_STATE_FROM_CHECKING_TO_PASS = -522;
    const ERROR_CARD_WX_CARD_ID_ALREADY_EXISTS = -523;

	public static $info = array(
		self::SUCCESS     => '成功',
		self::ERROR_PARAM => '参数错误',
		self::ERROR_DB    => '数据库操作错误',
		self::ERROR_THROW => '异常错误',
		self::ERROR_REDIS => 'REDIS错误',

		self::ERROR_VOUCHER_GENERATE_FAILED			=> '代金券添加失败',
		self::ERROR_VOUCHER_RULE_STATE_ERROR    	=> '代金券规则状态错误',
		self::ERROR_VOUCHER_RULE_NOTEXIST       	=> '代金券规则不存在',
		self::ERROR_ADD_VOUCHER_RULE_FAILED        	=> '增加代金券规则失败',
		self::ERROR_SAVE_VOUCHER_RULE_STATE_FAILED  => '修改代金券状态失败',

		self::ERROR_VOUCHER_CHANGE_FAILED        	=> '代金券转让失败',
		self::ERROR_VOUCHER_CODE_USEED        		=> '代金券已使用',
		self::ERROR_VOUCHER_NOTEXIST        		=> '代金券不存在',
		self::ERROR_VOUCHER_CODE_OVERDUE       		=> '代金券已过期',
		self::ERROR_VOUCHER_CODE_NOHAVE      		=> '代金券被抢光了',
		self::ERROR_VOUCHER_RULE_OVERDUE    		=> '代金券规则已过期',
		self::ERROR_VOUCHER_APP_ID_NOT_MATCH        => '代金券所属公众号不匹配',
		self::ERROR_VOUCHER_USER_NOT_MATCH          => '代金券所属用户不匹配',
		self::ERROR_VOUCHER_VALUE_MIN_NOT_MATCH     => '代金券最低使用金额不匹配',
		self::ERROR_VOUCHER_USE_ALREADY             => '代金券已经被使用',
		self::ERROR_VOUCHER_CONSUME_FAILED          => '代金券消费失败',
		self::ERROR_VOUCHER_CHANGE_USER_NOTBELONG   => '代金券不属于该用户',

		self::ERROR_VOUCHER_RULE_VALUE_ERROR  		=> '代金券金额错误',
		self::ERROR_VOUCHER_RULE_VALUE_MIX_ERROR  	=> '代金券最低使用金额错误',
		self::ERROR_VOUCHER_RULE_VALUE_MIX_BIG  	=> '代金券最低使用金额过大',
		self::ERROR_VOUCHER_RULE_CORP_NOTBELONG  	=> '代金券规则不属于该企业',

		self::ERROR_ADD_ACTIVITY_FAILED        		=> '增加活动失败',
		self::ERROR_ACTIVITY_NOTEXIST        		=> '活动不存在',
		self::ERROR_ACTIVITY_BING_RULE_FAILED       => '活动绑定规则失败',
		self::ERROR_ACTIVITY_NOTSTART        		=> '活动还未开始',
		self::ERROR_ACTIVITY_END        			=> '活动已经结束',
		self::ERROR_ACTIVITY_RULE_NOBIND   			=> '活动下没有该规则',
		self::ERROR_ACTIVITY_ADD_INVITE_FAILED     	=> '添加邀请人信息失败',
		self::ERROR_ACTIVITY_PARTAKE_USEUP        	=> '参加活动次数已用完',
		self::ERROR_ACTIVITY_PARTAKE_MIX        	=> '参加活动次数已达上限',
		self::ERROR_ACTIVITY_BING_HAVE        		=> '活动与规则已经有绑定关系',
		self::ERROR_ACTIVITY_INVITE_HAVE        	=> '不要重复邀请',
		self::ERROR_ACTIVITY_CORP_ERROR         	=> '该活动不属于该企业',
		self::ERROR_ACTIVITY_NUMBER_MAX_ERROR   	=> '总参与次数不能小于默认参与次数',
		self::ERROR_ACTIVITY_TIME_END_ERROR   		=> '活动结束时间不能小于当前时间',

        self::ERROR_CARD_CORP_SERVICE_TYPE_ID_DUPLICATED => '同一个服务ID下，不可重复创建微信卡券',
        self::ERROR_CARD_CREATE_FAILED => '创建卡券失败',
        self::ERROR_CARD_UPDATE_FAILED => '更新卡券失败',
        self::ERROR_CARD_PARAM_REQUIRED => '参数错误 %s 必填',
        self::ERROR_CARD_PARAM_INVALID => '参数错误 %s 不合法',
        self::ERROR_CARD_CUSTOM_FIELD_OVERFLOW => '自定义会员权益超限',
        self::ERROR_CARD_EXT_CREATE_FAILED => '创建卡券失败',
        self::ERROR_CARD_EXT_CUSTOM_CREATE_FAILED => '创建卡券失败',
        self::ERROR_CARD_TEXT_IMAGE_CREATE_FAILED => '创建卡券失败',
        self::ERROR_CARD_NOT_EXISTS => '卡券不存在',
        self::ERROR_CARD_CODE_ALREADY_BEEN_RECEIVED => 'Code已被领取过，不可重复领取',
        self::ERROR_CARD_CODE_CREATE_FAILED => '生成卡Code码失败',
        self::ERROR_CARD_CODE_NOT_EXISTS => '卡Code码不存在',
        self::ERROR_CARD_CODE_STATE_INVALID => '卡Code码状态异常',
        self::ERROR_CARD_CODE_INCREASE_BALANCE_FAILED => '更新余额失败',
        self::ERROR_CARD_CODE_INCREASE_BONUS_FAILED => '更新积分失败',
        self::ERROR_CARD_CODE_UPDATE_FAILED => '更新卡Code资料失败',
        self::ERROR_CARD_VS_CODE_NOT_MATCH => '卡券和Code不相符',
        self::ERROR_CARD_CODE_RECEIVE_FALIED => '更新用户领取卡Code信息失败',
        self::ERROR_CARD_STATE_INVALID => '卡券状态异常',
        self::ERROR_CARD_CHANGE_STATE_FROM_CREATED_TO_CHECKING => '更新卡券状态到审核中失败',
        self::ERROR_CARD_CHANGE_STATE_FROM_CHECKING_TO_NOT_PASS => '更新卡券状态到审核未通过失败',
        self::ERROR_CARD_CHANGE_STATE_FROM_CHECKING_TO_PASS => '更新卡券状态到审核通过失败',
        self::ERROR_CARD_WX_CARD_ID_ALREADY_EXISTS => '微信卡券已生成，不可重复创建',
	);

    public static function desc($code){
        return empty(self::$info[$code]) ? '' : self::$info[$code];
    }
}
