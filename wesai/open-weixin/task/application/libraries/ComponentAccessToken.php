<?php

class ComponentAccessToken
{
    private static $_instance = null;
    protected $CI = null;

    public static function getInstance() {
        if (self::$_instance instanceof static) {
            return self::$_instance;
        }
        return new static;
    }

    public function __construct(){
        $this->CI = & get_instance();
        $this->CI->load->library('curl');
        $this->CI->load->model('Component_model');
    }

    private function call_weixin($appid, $secret, $token, $aeskey){
        $url = WEIXIN_GET_COMPONENT_ACCESS_TOKEN_URL;

        $result = $this->CI->Component_model->get($appid);
        if($result->error !== 0){
            log_message('error', '获取第三方verify ticket失败'.$result->error);
            return false;
        }
        $component_verify_ticket = $result->result->component_verify_ticket;
        $params = array(
            'component_appid' => $appid,
            'component_appsecret' => $secret,
            'component_verify_ticket' => $component_verify_ticket
        );
        $data = json_encode($params);

        $ret = '';
        $max = 3;
        $loop = 1;
        while(true){
            $ret = $this->CI->curl->post($data, $url);
            if(!empty($ret) || $loop >= $max){
                break;
            }
            $loop ++;
        }

        return $ret;
    }

    public function get_access_token($appid, $secret, $token='', $aeskey='', $name='') {
        $access_token = $this->call_weixin($appid, $secret, $token, $aeskey);
        $access_token = json_decode($access_token);
        if(empty($access_token) || empty($access_token->component_access_token)){
            log_message('error', 'call_weixin Error!'.json_encode($access_token));
            return false;
        }

        $result = $this->CI->Component_model->setAccessToken($appid, $secret, $access_token->component_access_token, $name);
        if($result->error !== 0){
            log_message('error', '设置开放平台access token失败'.$result->error);
            return false;
        }

        return true;
    }

    private function call_weixin_for_authorizer_refresh($component_appid, $authorizer_appid, $authorizer_refresh_token){
        $url = WEIXIN_GET_COMPONENT_AUTHORIZER_ACCESS_TOKEN_URL;

        $result = $this->CI->Component_model->get($component_appid);
        if($result->error !== 0){
            log_message('error', '获取第三方verify ticket失败'.$result->error);
            return false;
        }
        $params = array(
            'component_access_token' => $result->result->component_access_token
        );
        $url .= "?" . http_build_query($params);

        $params = array(
            'component_appid' => $component_appid,
            'authorizer_appid' => $authorizer_appid,
            'authorizer_refresh_token' => $authorizer_refresh_token
        );
        $data = json_encode($params);

        $ret = '';
        $max = 3;
        $loop = 1;
        while(true){
            $ret = $this->CI->curl->post($data, $url);
            if(!empty($ret) || $loop >= $max){
                break;
            }
            $loop ++;
        }

        return $ret;
    }

    public function refresh_authorizer_access_token($component_appid, $authorizer_appid, $authorizer_refresh_token){
        $access_token = $this->call_weixin_for_authorizer_refresh($component_appid, $authorizer_appid, $authorizer_refresh_token);
        if(empty($access_token)){
            log_message('error', 'call_weixin_for_authorizer_refresh Error!');
            return false;
        }
        $access_token = json_decode($access_token);
        if(!empty($access_token->errcode)){
            log_message('error', 'call_weixin_for_authorizer_refresh Error! ' . $access_token->errcode . ' ' . $access_token->errmsg);
            return false;
        }
        $result = $this->CI->Component_model->refreshAuthorizerToken($authorizer_appid, $access_token->authorizer_access_token, $access_token->authorizer_refresh_token);
        if($result->error !== 0){
            log_message('error', '刷新授权公众号的access token失败'.$result->error);
            return false;
        }
        $this->refresh_authorizer_jsapi_ticket($authorizer_appid, $access_token->authorizer_access_token);

        return true;
    }

    private function refresh_authorizer_jsapi_ticket($authorizer_appid, $authorizer_access_token){
        $params = array(
            'access_token' => $authorizer_access_token,
            'type'=>'jsapi'
        );

        $url = WEIXIN_GET_JSAPI_TICKET_URL;
        $url .= "?" . http_build_query($params);

        $max = 3;
        $loop = 1;
        while(true){
            $ret = $this->CI->curl->get($url);
            if(!empty($ret) || $loop >= $max){
                break;
            }
            $loop ++;
        }
        // 
        $ticket = json_decode($ret);
        if(empty($ticket) || empty($ticket->ticket)){
            log_message('error', 'refresh_authorizer_jsapi_ticket Error!'.$ret);
            return false;
        }

        $result = $this->CI->Component_model->refreshAuthorizerJsapiTicket($authorizer_appid, $ticket->ticket);
        if($result->error !== 0){
            log_message('error', '刷新授权公众号的jsapi ticket失败'.$result->error);
            return false;
        }

    }

}

