<?php

class Error_Code
{
	const SUCCESS     = 0;
	const ERROR_PARAM = -1;
	const ERROR_DB    = -2;
	const ERROR_THROW = -3;
	const ERROR_REDIS = -4;

	const ERROR_GET_TOKEN_FAILED 				= -101;
	const ERROR_CURL_TOKEN_FAILED 				= -102;
	const ERROR_UPDATE_TOKEN_FAILED 			= -103;
	const ERROR_CURL_CONTEST_DETAIL_FAILED 		= -104;
	const ERROR_CURL_CONTEST_ITEM_DETAIL_FAILED = -105;
	const ERROR_CURL_ITEM_FROM_DETAIL_FAILED 	= -106;


	const ERROR_CREATE_CONTEST_FAILED 				= -201;
	const ERROR_CONTEST_ALREADY_EXISTS 				= -202;
	const ERROR_CONTEST_NOT_EXISTS 					= -203;
	const ERROR_CONTEST_ITEM_ALREADY_EXISTS 		= -204;
	const ERROR_CONTEST_ITEM_NOT_EXISTS 			= -205;
	const ERROR_CREATE_CONTEST_ITEM_FAILED 			= -206;
	const ERROR_CREATE_CONTEST_ITEM_FROM_FAILED 	= -207;
	const ERROR_CONTEST_ITEM_FROM_ALREADY_EXISTS 	= -208;
	const ERROR_CONTEST_ITEM_FROM_NOT_EXISTS 		= -209;
	const ERROR_MODIFY_CONTEST_FAILED 				= -210;
	const ERROR_MODIFY_CONTEST_ITEM_FAILED 			= -211;
	const ERROR_ORDER_ALREADY_EXISTS 	 			= -212;
	const ERROR_ORDER_NOT_EXISTS 	 				= -213;
	const ERROR_CREATE_ORDER_FAILED 				= -214;
	const ERROR_CONTEST_ORDER_STATE 				= -215;
	const ERROR_CREATE_ORDER_COMPLETE_FAILED 		= -216;
	const ERROR_ORDER_COMPLETE_NOT_EXISTS 			= -217;
	const ERROR_ORDER_COMPLETE_ORDER_ID 			= -218;
	const ERROR_ORDER_COMPLETE_VERIFY_NUMBER		= -219;




	public static $info = array(
		self::SUCCESS     => '成功',
		self::ERROR_PARAM => '参数错误',
		self::ERROR_DB    => '数据库操作错误',
		self::ERROR_THROW => '异常错误',
		self::ERROR_REDIS => 'REDIS错误',


		self::ERROR_GET_TOKEN_FAILED 				=> '获取数据库token信息失败',
		self::ERROR_CURL_TOKEN_FAILED 				=> '获取新的token信息失败',
		self::ERROR_UPDATE_TOKEN_FAILED 			=> '更新token信息失败',
		self::ERROR_CURL_CONTEST_DETAIL_FAILED 		=> '获取新的活动详情失败',
		self::ERROR_CURL_CONTEST_ITEM_DETAIL_FAILED => '获取新的项目详情失败',
		self::ERROR_CURL_ITEM_FROM_DETAIL_FAILED 	=> '获取新的表单详情失败',

		self::ERROR_CREATE_CONTEST_FAILED 				=> '新增活动信息失败',
		self::ERROR_CONTEST_ALREADY_EXISTS 				=> '活动信息已存在',
		self::ERROR_CONTEST_NOT_EXISTS 					=> '活动信息不存在',
		self::ERROR_CONTEST_ITEM_ALREADY_EXISTS			=> '项目信息已存在',
		self::ERROR_CONTEST_ITEM_NOT_EXISTS				=> '项目信息不存在',
		self::ERROR_CREATE_CONTEST_ITEM_FAILED			=> '新增项目信息失败',
		self::ERROR_CREATE_CONTEST_ITEM_FROM_FAILED		=> '新增表单信息失败',
		self::ERROR_CONTEST_ITEM_FROM_ALREADY_EXISTS	=> '表单信息已存在',
		self::ERROR_CONTEST_ITEM_FROM_NOT_EXISTS		=> '表单信息不存在',
		self::ERROR_MODIFY_CONTEST_FAILED				=> '修改活动信息失败',
		self::ERROR_MODIFY_CONTEST_ITEM_FAILED			=> '修改项目信息失败',
		self::ERROR_ORDER_ALREADY_EXISTS				=> '订单信息已存在',
		self::ERROR_ORDER_NOT_EXISTS					=> '订单信息不存在',
		self::ERROR_CREATE_ORDER_FAILED					=> '新增订单信息失败',
		self::ERROR_CONTEST_ORDER_STATE					=> '订单信息状态错误',
		self::ERROR_CREATE_ORDER_COMPLETE_FAILED		=> '新增核销信息错误',
		self::ERROR_ORDER_COMPLETE_NOT_EXISTS			=> '核销信息不存在',
		self::ERROR_ORDER_COMPLETE_ORDER_ID				=> '核销信息对应订单号错误',
		self::ERROR_ORDER_COMPLETE_VERIFY_NUMBER		=> '核销信息核销次数错误',
    );

    public static function desc($code){
        return empty(self::$info[$code]) ? '' : self::$info[$code];
    }
}
