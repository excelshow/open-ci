<?php

class Error_Code
{
	const SUCCESS     = 0;
	const ERROR_PARAM = -1;
	const ERROR_DB    = -2;
	const ERROR_THROW = -3;
	const ERROR_REDIS = -4;
	const ERROR_CONTROLLER_NOT_EXISTS = -10;
	const ERROR_SYSTEM = -11;
	const ERROR_HTTP_METHOD = -12;
	const ERROR_INTERNAL_HOST_CONFIG = -13;
	const ERROR_AUTHORIZATION_NOT_EXISTS = -14;
	const ERROR_AUTHORIZATION = -15;
	const ERROR_AUTHORIZATION_TIMEOUT = -16;
	const ERROR_TOKEN_NOT_EXISTS = -17;
	const ERROR_TOKEN_TIMEOUT = -18;
	const ERROR_TOKEN_ERROR = -19;
	const ERROR_TOKEN_SET_ERROR = -20;

	const ERROR_PRODUCT_STATE_SAVE_FAILED = -101;
	const ERROR_PRODUCT_DELETE_FAILED = -102;
	const ERROR_ORDER_TEST_FAILED = -103;
	const ERROR_SOFTYKT_TEST_SIGN_FAILED = -104;

    public static $info = array(
		self::SUCCESS     => '成功',
		self::ERROR_PARAM => '参数错误',
		self::ERROR_DB    => '数据库操作错误',
		self::ERROR_THROW => '异常错误',
		self::ERROR_REDIS => 'REDIS错误',
		self::ERROR_CONTROLLER_NOT_EXISTS => '无效的请求',
		self::ERROR_SYSTEM => '系统错误',
		self::ERROR_HTTP_METHOD => 'HTTP METHOD 错误',
		self::ERROR_INTERNAL_HOST_CONFIG => '服务暂未开通',
		self::ERROR_AUTHORIZATION => 'Authorization参数错误',
		self::ERROR_AUTHORIZATION_NOT_EXISTS => 'Authorization未传递',
		self::ERROR_AUTHORIZATION_TIMEOUT => 'Authorization已超时',
		self::ERROR_TOKEN_NOT_EXISTS => 'Token未传递',
		self::ERROR_TOKEN_TIMEOUT => 'Token已超时',
		self::ERROR_TOKEN_ERROR => 'Token异常',
		self::ERROR_TOKEN_SET_ERROR => 'Token设置异常',

		self::ERROR_PRODUCT_STATE_SAVE_FAILED 	=> '商品状态修改失败',
		self::ERROR_PRODUCT_DELETE_FAILED 		=> '商品删除失败',
		self::ERROR_ORDER_TEST_FAILED 			=> '消费失败',
		self::ERROR_SOFTYKT_TEST_SIGN_FAILED 	=> '金飞鹰签名验证失败',

	);

    public static function desc($code){
        return empty(self::$info[$code]) ? '' : self::$info[$code];
    }
}
