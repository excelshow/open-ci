<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Userinfo_model 
 *  用户信息
 */
class Userinfo_model
{
	public function getUserInfo(){
		return empty($_SESSION[$_SESSION['appId']]) ? array() : $_SESSION[$_SESSION['appId']];
	}

	public function setUserInfo($uid, $openid, $apppk, $fk_corp){
        $appId = $_SESSION['appId'];
		if (!array_key_exists($appId, $_SESSION)) {
            $_SESSION[$appId] = compact('uid', 'openid', 'apppk', 'fk_corp');
        }
	}

    public function setCurrentAppId($appId){
        $_SESSION['appId'] = $appId;
    }

    public function getCurrentAppId(){
        return $_SESSION['appId'];
    }
    
    public function setCaptchaCode($code){
		$_SESSION['_captchaCode_'] = $code;
    }
}
