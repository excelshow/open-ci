<?php

class Error_Code
{
	const SUCCESS     = 0;
	const ERROR_PARAM = -1;
	const ERROR_DB    = -2;
	const ERROR_THROW = -3;
	const ERROR_REDIS = -4;

	const ERROR_API_DUPLICATED              = -101;
	const ERROR_ADD_API_FAILED              = -102;
	const ERROR_API_NOT_EXISTS              = -103;
	const ERROR_UPDATE_API_FAILED           = -104;
	const ERROR_API_STATE_INVALID           = -105;
	const ERROR_API_SYSTEM_INVALID          = -106;
	const ERROR_API_ROLE_INVALID            = -107;
	const ERROR_ADD_API_PARAM_FAILED        = -108;
	const ERROR_API_PARAM_NOT_EXISTS        = -109;
	const ERROR_API_PARAM_STATE_INVALID     = -110;
	const ERROR_ROLE_API_MAPPING_DUPLICATED = -111;
	const ERROR_ADD_ROLE_API_MAPPING_FAILED = -112;
	const ERROR_ROLE_API_MAPPING_NOT_EXISTS = -113;
	const ERROR_API_PARAM_DUPLICATED        = -114;

	const ERROR_API_SOFTYKT_GET_TOKEN_FAILED        		= -201;
	const ERROR_CONTEST_ORDER_SOFTYKT_ORDERNUMBER_NOT_FOUND	= -202;
	const ERROR_CORP_SOFTYKT_INFO_NOT_FOUND					= -203;
	const ERROR_SOFTYKT_ORDER_PLACE_FAILED					= -204;
	const ERROR_SOFTYKT_ORDER_SMS_FAILED					= -205;
	const ERROR_UPDATE_SOFTYKT_TOKEN_FAILED        			= -206;
	const ERROR_GET_SOFTYKT_TOKEN_FAILED        			= -207;



	public static $info = array(
		self::SUCCESS     => '成功',
		self::ERROR_PARAM => '参数错误',
		self::ERROR_DB    => '数据库操作错误',
		self::ERROR_THROW => '异常错误',
		self::ERROR_REDIS => 'REDIS错误',

		self::ERROR_API_DUPLICATED              => 'API已经存在,不能重复添加',
		self::ERROR_ADD_API_FAILED              => '新增API失败',
		self::ERROR_API_NOT_EXISTS              => 'API不存在',
		self::ERROR_UPDATE_API_FAILED           => '更新API失败',
		self::ERROR_API_STATE_INVALID           => 'API状态异常',
		self::ERROR_API_SYSTEM_INVALID          => 'API系统非法',
		self::ERROR_API_ROLE_INVALID            => 'API角色非法',
		self::ERROR_ADD_API_PARAM_FAILED        => '新增API参数',
		self::ERROR_API_PARAM_NOT_EXISTS        => 'API参数不存在',
		self::ERROR_API_PARAM_STATE_INVALID     => 'API参数状态异常',
		self::ERROR_ROLE_API_MAPPING_DUPLICATED => 'API已经绑定过该角色',
		self::ERROR_ADD_ROLE_API_MAPPING_FAILED => 'API绑定角色失败',
		self::ERROR_ROLE_API_MAPPING_NOT_EXISTS => '绑定关系不存在',
		self::ERROR_API_PARAM_DUPLICATED        => 'API参数重复',

		self::ERROR_API_SOFTYKT_GET_TOKEN_FAILED        		=> '调用金飞鹰API接口 获取token失败',
		self::ERROR_CONTEST_ORDER_SOFTYKT_ORDERNUMBER_NOT_FOUND	=> '未找到金飞鹰对应订单号',
		self::ERROR_CORP_SOFTYKT_INFO_NOT_FOUND                 => '该企业未设置金飞鹰接口信息',
		self::ERROR_SOFTYKT_ORDER_PLACE_FAILED                 	=> '金飞鹰下单失败',
		self::ERROR_SOFTYKT_ORDER_SMS_FAILED                 	=> '重发消费码失败',
		self::ERROR_UPDATE_SOFTYKT_TOKEN_FAILED                 => '更新金飞鹰token失败',
		self::ERROR_GET_SOFTYKT_TOKEN_FAILED                 	=> '读取金飞鹰token失败',

	);

	public static function desc($code)
	{
		return empty(self::$info[$code]) ? '' : self::$info[$code];
	}
}
