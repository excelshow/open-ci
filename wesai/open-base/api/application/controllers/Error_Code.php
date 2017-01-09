<?php

class Error_Code
{
	const SUCCESS     = 0;
	const ERROR_PARAM = -1;
	const ERROR_DB    = -2;
	const ERROR_THROW = -3;
	const ERROR_REDIS = -4;

    public static $info = array(
		self::SUCCESS     => '成功',
		self::ERROR_PARAM => '参数错误',
		self::ERROR_DB    => '数据库操作错误',
		self::ERROR_THROW => '异常错误',
		self::ERROR_REDIS => 'REDIS错误',
    );

    public static function desc($code){
        return empty(self::$info[$code]) ? '' : self::$info[$code];
    }
}
