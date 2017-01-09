<?php
class Error_Code {
    const SUCCESS = 0;
    const ERROR_PARAM = -1;

    public static $info= array(
            self::SUCCESS => '成功',
            self::ERROR_PARAM => '参数错误',

        );

    public static function desc($code){
        return empty(self::$info[$code])?'':self::$info[$code];
    }
}
