<?php

class Error_Code
{
	const SUCCESS     = 0;
	const ERROR_PARAM = -1;
	const ERROR_DB    = -2;
	const ERROR_THROW = -3;
	const ERROR_REDIS = -4;


	const ERROR_GET_CONTEST_LIST_FAILED 		= -101;
	const ERROR_GET_CONTEST_ITEM_LIST_FAILED 	= -102;
	const ERROR_GET_CONTEST_DETAIL              = -103;
	const ERROR_LIST_ITEM_FORM                  = -104;
	const ERROR_CONTEST_CREATE_ORDER            = -105;
	const ERROR_CONTEST_COMPLETE_ORDER          = -106;
	const ERROR_GET_CONTEST_ITEMS               = -107;

	const ERROR_TOKEN_NOT_EMPTY 					= -201;
	const ERROR_CHECK_SIGN_FAILED 					= -202;
	const ERROR_CHECK_TOKEN_FAILED 					= -202;

    public static $info = array(
		self::SUCCESS     => '成功',
		self::ERROR_PARAM => '参数错误',
		self::ERROR_DB    => '数据库操作错误',
		self::ERROR_THROW => '异常错误',
		self::ERROR_REDIS => 'REDIS错误',


		self::ERROR_GET_CONTEST_LIST_FAILED 		=> '获取活动列表失败',
		self::ERROR_GET_CONTEST_ITEM_LIST_FAILED 	=> '获取项目列表失败',
		self::ERROR_GET_CONTEST_DETAIL              => '获取活动详情失败',
		self::ERROR_LIST_ITEM_FORM                  => '获取项目表单失败',
		self::ERROR_CONTEST_CREATE_ORDER            => '活动报名下单失败',
		self::ERROR_CONTEST_COMPLETE_ORDER          => '订单更新完成失败',
		self::ERROR_GET_CONTEST_ITEMS               => '获取项目信息失败',

		self::ERROR_TOKEN_NOT_EMPTY						=> 'token未传递',
		self::ERROR_CHECK_SIGN_FAILED					=> 'sign验证失败',
		self::ERROR_CHECK_TOKEN_FAILED					=> 'token验证失败',
	);

    public static function desc($code){
        return empty(self::$info[$code]) ? '' : self::$info[$code];
    }
}
