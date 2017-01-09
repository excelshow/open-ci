<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * 用户授权
 */

class Snsclass
{

    public function sns_function(){}

    public function get_snsapi_url($redirect_uri, $scope, $state) {
        $params = array();
        $params['appid'] = WEIXIN_APPID;
        $params['redirect_uri'] = $redirect_uri;
        $params['response_type'] = 'code';
        $params['scope'] = $scope;
        $params['state'] = $state;

        $url = WEIXIN_SNSAPI_AUTHORIZE_URL;
        $url .= "?" . http_build_query($params);
        $url .= "#wechat_redirect";
        //echo $url;exit;
        
        return $url;
    }

    public function get_access_token($code) {
        $params = array();
        $params['appid'] = WEIXIN_APPID;
        $params['secret'] = WEIXIN_SECRET;
        $params['code'] = $code;
        $params['grant_type'] = 'authorization_code';

        $url = WEIXIN_SNSAPI_ACCESSTOKEN_URL;
        $url .= "?" . http_build_query($params);

        $CI = & get_instance();
        $CI->load->library('curl');
        $ret = $CI->curl->get($url);

        if(!empty($ret)){
            $ret_json = json_decode($ret);
            if($ret_json && isset($ret_json->access_token)){
                return $ret_json; 	
            }
        }
        return false;		
    }

    public function get_userinfo($token,$openid) {
        $params = array();
        $params['openid'] = $openid;
        $params['access_token'] = $token;
        $params['lang'] = 'zh_CN';

        $url = WEIXIN_SNSAPI_USERINFO_URL;
        $url .= "?" . http_build_query($params);

        $CI = & get_instance();
        $CI->load->library('curl');
        $ret = $CI->curl->get($url);

        if(!empty($ret)){
            $ret_json = json_decode($ret);
            if($ret_json && isset($ret_json->openid)){
                return $ret_json; 	
            }
        }
        return false;
    }

}
