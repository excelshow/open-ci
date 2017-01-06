<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * DIY_User_model 
 *  用户信息
 */
class DIY_User_model
{
	public function getUserInfo(){
		$user_info = empty($_SESSION[$_SESSION['appId']]) ? '' : $_SESSION[$_SESSION['appId']];
        return json_decode($user_info);
	}

    /**
     * setUserInfo
     * 
     * @param mixed $uid 
     * @param mixed $openid 
     * @param mixed $apppk  授权公众号的自增ID，其他表示都比较麻烦
     * @param mixed $corp_id 企业号自增ID
     * @param string $mobile 
     * @access public
     * @return void
     */
	public function setUserInfo($uid, $uuid, $openid, $apppk, $corp_id, $mobile='', $qrcode_url=''){
        $appId = $_SESSION['appId'];
        $_SESSION[$appId] = json_encode(compact('uid', 'uuid', 'openid', 'apppk', 'corp_id', 'mobile', 'qrcode_url'));
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

    public function setUserInfoMobile($mobile){
        $userInfo = json_decode($_SESSION[$_SESSION['appId']],true);
        $userInfo['mobile'] = $mobile;
        $_SESSION[$_SESSION['appId']] = json_encode($userInfo);
    }
}
